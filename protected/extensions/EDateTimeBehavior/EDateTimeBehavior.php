<?php
/**
 * Author: Duc Nguyen Ta Quang <ducntq@gmail.com>
 *
 * Automatically convert date and datetime field to PHP5 DateTime object
 *
 * Inspired from DateTimeI18NBehavior
 *
 * Date: 5/15/12
 * Time: 2:14 PM
 * Version: 1.0.0
 * Tested with yii-1.1.10.r3566
 */

class EDateTimeBehavior extends CActiveRecordBehavior
{
    private $mySqlDateFormat = 'Y-m-d';
    private $mySqlDateTimeFormat = 'Y-m-d H:i:s';

    public function afterFind($event)
    {
        foreach($event->sender->tableSchema->columns as $columnName => $column){
            if (($column->dbType != 'date') and ($column->dbType != 'datetime')) continue;

            if (!strlen($event->sender->$columnName)){
                $event->sender->$columnName = null;
                continue;
            }

            $timestamp = strtotime($event->sender->$columnName);

            $event->sender->$columnName = new DateTime('@' . $timestamp);
        }
    }

    public function beforeSave($event)
    {
        foreach($event->sender->tableSchema->columns as $columnName => $column){
            if (($column->dbType != 'date') and ($column->dbType != 'datetime')) continue;
            if(get_class($event->sender->$columnName) == 'DateTime')
            {
                if (($column->dbType == 'date'))
                {
                    $sqlDate = $event->sender->$columnName->format($this->mySqlDateFormat);
                    $event->sender->$columnName = $sqlDate;
                }
                else
                {
                    $sqlDateTime = $event->sender->$columnName->format($this->mySqlDateTimeFormat);
                    $event->sender->$columnName = $sqlDateTime;
                }
            }
        }
    }
}
