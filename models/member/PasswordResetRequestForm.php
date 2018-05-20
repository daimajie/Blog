<?php
namespace app\models\member;
use Yii;
use yii\base\Model;
use app\components\Helper;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $username;
    public $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email','username'], 'trim'],
            [['email','username'], 'required'],
            [['username'], 'string','max'=>15],
            [['email'], 'email'],
            [['username'],'checkUsername'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '邮件地址'
        ];
    }

    /**
     * 检测用户名与邮箱是否匹配
     */
    public function checkUsername($attribute, $params){
        if(!$this->hasErrors()){
            $user = $this->getUser();
            if(!$user)
                $this->addError($attribute, '用户名与邮箱不匹配。');
        }
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {

        if(!$this->validate()){
            return false;
        }

        /*得到模型*/
        $user = $this->getUser();
        if (!$user) {
            return false;
        }

        /*确保密码重置token的存在*/
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        /*发送邮件*/
        $view = 'message/password-reset-token';
        $ret = Helper::sendEmail(
            Yii::$app->params['adminEmail'],
            $user->email,
            Yii::$app->name,
            $view,
            ['user'=>$user]
        );
        return $ret;
    }


    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsernameAndEmail($this->username,$this->email);
        }

        return $this->_user;
    }
}