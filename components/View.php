<?php
namespace app\components;
use Yii;

/**
 * 视图层使用的工具类
 * @package app\components
 */
class View
{
    /**
     * 格式化时间
     */
    public static function timeFormat($time){
        return Yii::$app->formatter->asRelativeTime($time);
    }
}