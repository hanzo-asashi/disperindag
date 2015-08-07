**ExtendedDateTimeFormattingBehavior** adds some methods for extending date&time formatting to *CDateFormatter* component.

Available formatters:

- `formatDateTimeReadable($timestamp, $dateWidth = 'medium', $timeWidth = 'medium')` formats date&time with pattern **(Today|Yesterday|&lt;date&gt;), &lt;time&gt;**.
 - **$dateWidth** is passed to `CDateFormatter::formatDateTime()` to format **&lt;date&gt;**
 - **$timeWidth** is passed to `CDateFormatter::formatDateTime()` to format **&lt;time&gt;**
- `formatDateTimeInterval($timestamp, $precisely = false)` formats date&time as a date&time interval with pattern **&lt;metric value&gt; &lt;metric&gt; ago** or more complex **&lt;first metric value&gt; &lt;first metric&gt; and &lt;second metric value&gt; &lt;second metric&gt; ago**
 - **$precisely** is setted to **true**, interval will be composed of two metrics.

**$timestamp** in both formatters can be unix timestamp (integer) or string to pass it to `strtotime()`.

---
How to use: attach this behavior to your dateFormatter instance.

For example, add this in your base controller class (`Controller`) and base console command class (`ConsoleCommand`):
```php
    public function init()
    {
        parent::init();
        Yii::app()->dateFormatter->attachBehavior('ExtendedDateTimeFormatting', 'ext.ExtendedDateTimeFormattingBehavior.ExtendedDateTimeFormattingBehavior');
    }
```
---

Example:
```php
echo Yii::app()->dateFormatter->formatDateTimeReadable('yesterday, 20:45:17');
```
returns
**Yesterday, 8:45:17 PM20**
```php
echo Yii::app()->dateFormatter->formatDateTimeInterval('yesterday, 20:45:17', true);
```
returns
**20 hours and 54 minutes ago**
