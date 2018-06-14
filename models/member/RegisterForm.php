<?php

namespace app\models\member;
use Yii;
use yii\base\Model;


/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegisterForm extends User
{
/*    public $username;
    public $password;
    public $re_password;
    public $email;*/
    public $captcha;

    /*验证*/
    public function rules()
    {
        return [
            [['username', 'password', 're_password', 'email', 'captcha'], 'required'],
            [['username'], 'unique'],
            [['email'], 'unique'],

            [['username'], 'string', 'max'=>15],
            [['password'], 'string', 'min' =>6, 'max'=>64],
            [['re_password'], 'compare', 'compareAttribute' => 'password', 'message'=>'两次密码不一致。'],
            [['email'],'email'],
            [['captcha'], 'checkCaptcha'],
        ];
    }

    /**
     * 验证邮箱验证码
     */
    public function checkCaptcha($attribute, $params){
        //验证captcha
        if(!$this->hasErrors()){
            $session = Yii::$app->session;
            $captcha = $session->get('email-captcha');
            if($this->$attribute == $captcha['captcha'] && time() <= ($captcha['lifetime'] + $captcha['start_at'])){
                return true;
            }
            $this->addError($attribute, '验证码无效，请重试。');
        }
    }

    /*label*/
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            're_password' => '重复密码',
            'email' => '邮箱',
            'captcha' => '验证码'
        ];
    }

    /*写入数据库*/
    public function store(){
        if(!$this->validate())
            return false;

        $this->generatePasswordHash($this->password);
        $this->generateAuthKey();

        return $this->save(false);
    }


}
