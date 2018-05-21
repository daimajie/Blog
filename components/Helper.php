<?php
namespace app\components;
use Yii;

class Helper
{
    /**
     * 生成验证码
     * @params $len 验证码长度
     * @params $expire 过期时间（单位分钟）
     * @params $key 名字
     * @return string 验证码字符串
     */
    public static function generateCaptcha($len, $expire, $key = 'captcha'){
        if($len <=0 || $len > 18) $len=6;
        $temStr = substr(uniqid(), -$len);
        //保存至session中
        $session = Yii::$app->session;

        if (!$session->isActive)
            $session->open();

        $session[$key] = [
            'captcha' => $temStr,
            'lifetime' => $expire * 60,
            'start_at' => time()
        ];

        return $temStr;
    }

    /**
     * 发送邮件
     * @params $from string #发送邮件的邮箱
     * @params $emails string|array #接受邮件的邮箱
     * @params $subject string #邮件主题
     * @params $view string #使用的视图文件
     * @params $var array #视图变量
     * @return bool #发送成功返回true 否则返回false
     */
    public static function sendEmail($from, $emails, $subject, $view, $var=[]){

        if(!is_array($emails) && !is_string($emails)){
            return false;
        }
        if(is_array($emails)){
            $messages = [];
            foreach ($emails as $email) {
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                    continue;

                $messages[] = Yii::$app->mailer->compose($view, $var)
                    ->setFrom($from)
                    ->setTo($email)
                    ->setSubject($subject);
            }
            Yii::$app->mailer->sendMultiple($messages);
        }
        if(filter_var($emails, FILTER_VALIDATE_EMAIL)){

            $ret = Yii::$app->mailer->compose($view,$var)
                ->setFrom($from)
                ->setTo($emails)
                ->setSubject($subject)
                ->send();
            return (bool)$ret;
        }
        return false;
    }


    /**
     * 循化文章数据把关联的作者头像索引转为实际地址
     * @params $data array #文章列表
     * @return array | false #转换作者头像后的文章列表
     */
    public static function photoInPlace($data){
        if(!is_array($data))
            return false;

        $pics = Yii::$app->params['pics']; //获取固定头像信息

        foreach($data as $key => &$val){
            if($val['user']['photo'] > count($pics)) continue;
            $val['user']['photo'] = $pics[$val['user']['photo']];
        }

        return $data;
    }




}