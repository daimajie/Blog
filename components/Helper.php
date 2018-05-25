<?php
namespace app\components;
use Yii;
use yii\helpers\Url;

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

    //截取多字节字符串
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }

    //设置子导航
    public static function setSubNav($data){
        $tmp = [];
        foreach($data as $key => $val){
            $tmp[$key]['label'] = $val['name'];
            $tmp[$key]['url'] = Url::to(['/category/detail', 'category_id'=>$val['id']]);
        }
        $tmp['last']['label'] = '更多...';
        $tmp['last']['url'] = Url::to(['/category/index']);

        return $tmp;
    }




}