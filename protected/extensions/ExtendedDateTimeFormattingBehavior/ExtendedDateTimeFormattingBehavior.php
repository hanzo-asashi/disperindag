<?php

/**
 * Behavior adds some methods for extending date&time formatting to CDateFormatter component.
 * Available formatters:
 *    - formatDateTimeReadable() formats date&time with pattern "(Today|Yesterday|<date>), <time>"
 *    - formatDateTimeInterval() formats date&time as a date&time interval with pattern "<metric value> <metric> ago" or more complex "<first metric value> <first metric> and <second metric value> <second metric> ago".
 *
 * How to use: attach this behavior to your dateFormatter instance. For example, add this in your base controller class (Controller):
 * <code>
 *    public function beforeAction($action) {
 *        Yii::import('ext.ExtendedDateTimeFormattingBehavior.ExtendedDateTimeFormattingBehavior');
 *        Yii::app()->dateFormatter->attachBehavior('ExtendedDateTimeFormatting', new ExtendedDateTimeFormattingBehavior());
 *
 *        return parent::beforeAction($action);
 *    }
 * </code>
 *
 * @property CDateFormatter $owner
 *
 * @author wapmorgan (wapmorgan@gmail.com)
 */
class ExtendedDateTimeFormattingBehavior extends CBehavior
{
    /**
     * Messages category.
     */
    public $messagesCategory = 'ExtendedDateTimeFormattingBehavior';
    /**
     * Messages category path.
     */
    public $messagesCategoryPath = 'ext.ExtendedDateTimeFormattingBehavior.messages';

    /**
     * Adds extensionPath to messages source.
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if (!isset(Yii::app()->messages->extensionPaths[$this->messagesCategory])) {
            Yii::app()->messages->extensionPaths[$this->messagesCategory] = $this->messagesCategoryPath;
        }
    }

    /**
     * Formats date&time like "Today, 12:06 PM" or "Yesterday, 5:00 AM".
     *
     * @param int|string  $timestamp Unix timestamp or ready to format in strtotime() string
     * @param string|null $dateWidth If <date> will be printed, it defines width of date.
     *                               It is passed to CDateFormatter::formatDateTime() to format <date>.
     * @param string      $timeWidth Defines width of time.
     *                               It is passed to CDateFormatter::formatDateTime() to format <time>.
     *
     * @return string Localized date&time in human readable format
     */
    public function formatDateTimeReadable($timestamp, $dateWidth = 'medium', $timeWidth = 'medium')
    {
        if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        if (date('Ymd', strtotime('today')) == date('Ymd', $timestamp)) {
            return Yii::t($this->messagesCategory.'.main', 'Today, {time}', array('{time}' => $this->owner->formatDateTime($timestamp, null, $timeWidth)));
        } elseif (date('Ymd', strtotime('yesterday')) == date('Ymd', $timestamp)) {
            return Yii::t($this->messagesCategory.'.main', 'Yesterday, {time}', array('{time}' => $this->owner->formatDateTime($timestamp, null, $timeWidth)));
        } else {
            return $this->owner->formatDateTime($timestamp, $dateWidth, $timeWidth);
        }
    }

    /**
     * Formats date&time like "12 minutes ago" or "1 year ago".
     *
     * @param int|string $timestamp Unix timestamp or ready to format in strtotime() string
     * @param bool|int   $precisely Whether result should be composed of two time metrics.
     *
     * @return string Localized interval
     */
    public function formatDateTimeInterval($timestamp, $precisely = 0)
    {
        if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        if ($timestamp === time()) {
            return Yii::t($this->messagesCategory.'.main', 'just now');
        }

        $current_time = new DateTime();
        $time_to_format = new DateTime(date('r', $timestamp));

        $interval = date_diff($time_to_format, $current_time);
        if ($interval->y > 100) { // century
            if ($precisely && ($interval->y % 100) > 0) {
                $interval = $this->formatComplexInterval($interval, 'century', 'year');
            } else {
                $interval = $this->formatCenturyInterval($interval);
            }
        } elseif ($interval->y > 0) { // year
            if ($precisely && $interval->m) {
                $interval = $this->formatComplexInterval($interval, 'year', 'month');
            } else {
                $interval = $this->formatYearInterval($interval);
            }
        } elseif ($interval->m > 0) { // month
            if ($precisely && $interval->d) {
                $interval = $this->formatComplexInterval($interval, 'month', 'day');
            } else {
                $interval = $this->formatMonthInterval($interval);
            }
        } elseif ($interval->d > 0) { // day
            if ($precisely && $interval->h) {
                $interval = $this->formatComplexInterval($interval, 'day', 'hour');
            } else {
                $interval = $this->formatDayInterval($interval);
            }
        } elseif ($interval->h > 0) { // hour
            if ($precisely && $interval->i) {
                $interval = $this->formatComplexInterval($interval, 'hour', 'minute');
            } else {
                $interval = $this->formatHourInterval($interval);
            }
        } elseif ($interval->i > 0) { // minute
            if ($precisely && $interval->s) {
                $interval = $this->formatComplexInterval($interval, 'minute', 'second');
            } else { // second
                $interval = $this->formatMinuteInterval($interval);
            }
        } else {
            $interval = $this->formatSecondInterval($interval);
        }

        return Yii::t($this->messagesCategory.'.main', '{interval} ago', array('{interval}' => $interval));
    }

    /**
     * Formats complex (composed of two metrics) interval.
     *
     * @param DateInterval $interval
     * @param string       $first
     * @param $second
     *
     * @return string
     */
    private function formatComplexInterval($interval, $first, $second)
    {
        return Yii::t($this->messagesCategory.'.main', '{first} and {second}', array(
            '{first}' => call_user_func(array($this, 'format'.$first.'interval'), $interval),
            '{second}' => call_user_func(array($this, 'format'.$second.'interval'), $interval),
        ));
    }

    /**
     * Formats century interval.
     *
     * @param DateInterval $interval
     *
     * @return string Localized century interval
     */
    private function formatCenturyInterval(DateInterval $interval)
    {
        return Yii::t($this->messagesCategory.'.main', '{n} century|{n} centuries', array(floor($interval->y / 100)));
    }

    /**
     * Formats year interval.
     *
     * @param DateInterval $interval
     *
     * @return string Localized year interval
     */
    private function formatYearInterval(DateInterval $interval)
    {
        return Yii::t($this->messagesCategory.'.main', '{n} year|{n} years', array($interval->y));
    }

    /**
     * Formats month interval.
     *
     * @param DateInterval $interval
     *
     * @return string Localized month interval
     */
    private function formatMonthInterval(DateInterval $interval)
    {
        return Yii::t($this->messagesCategory.'.main', '{n} month|{n} months', array($interval->m));
    }

    /**
     * Formats day interval.
     *
     * @param DateInterval $interval
     *
     * @return string Localized day interval
     */
    private function formatDayInterval(DateInterval $interval)
    {
        return Yii::t($this->messagesCategory.'.main', '{n} day|{n} days', array($interval->d));
    }

    /**
     * Formats hour interval.
     *
     * @param DateInterval $interval
     *
     * @return string Localized hour interval
     */
    private function formatHourInterval(DateInterval $interval)
    {
        return Yii::t($this->messagesCategory.'.main', '{n} hour|{n} hours', array($interval->h));
    }

    /**
     * Formats minute interval.
     *
     * @param DateInterval $interval
     *
     * @return string Localized minute interval
     */
    private function formatMinuteInterval(DateInterval $interval)
    {
        return Yii::t($this->messagesCategory.'.main', '{n} minute|{n} minutes', array($interval->i));
    }

    /**
     * Formats second interval.
     *
     * @param DateInterval $interval
     *
     * @return string Localized second interval
     */
    private function formatSecondInterval(DateInterval $interval)
    {
        return Yii::t($this->messagesCategory.'.main', '{n} second|{n} seconds', array($interval->s));
    }
}
