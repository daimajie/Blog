<?php
namespace app\models\member;
use yii\base\Model;
use yii\base\InvalidParamException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $username;
    public $password;
    public $re_password;


    private $_user;
    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('传递参数错误.');
        }

        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('传递参数错误.');
        }
        $this->username = $this->_user->username;
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 're_password'], 'required'],
            [['password'], 'string', 'min' => 6],
            [['re_password'], 'compare', 'compareAttribute' => 'password'],
        ];
    }



    public function attributeLabels()
    {
        return [
            'password' => '新密码',
            're_password' => '重复密码',
            'username' => '用户名'
        ];
    }

    public function resetPassword()
    {
        if(!$this->validate())
            return false;

        $user = $this->_user;
        $user->generatePasswordHash($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }


}