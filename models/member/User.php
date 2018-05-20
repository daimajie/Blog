<?php

namespace app\models\member;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%user}}".
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password;
    public $re_password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * public function rules()
     * {
     * return [
     * [['username', 'auth_key', 'password_hash', 'email'], 'required'],
     * [['status', 'created_at', 'updated_at'], 'integer'],
     * [['username'], 'string', 'max' => 15],
     * [['auth_key'], 'string', 'max' => 32],
     * [['password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
     * [['username'], 'unique'],
     * [['email'], 'unique'],
     * [['password_reset_token'], 'unique'],
     * ];
     * }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            're_passowrd' => '重复密码',
            'passowrd' => '密码',
            'author' => '标记为作者',
            'nickname' => '昵称',
            'photo' => '头像'
        ];
    }

    /**
     * 生成auth_key 用于自动登陆token
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }


    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 生成用户密码
     */
    public function generatePasswordHash($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsernameOrEmail($username)
    {
        $model = self::find()->where(['or', 'username=:user', 'email=:user'], [':user' => $username])->one();
        if (!$model)
            return null;
        return $model;
    }

    public static function findByUsernameAndEmail($username, $email)
    {
        $model = self::find()->where(['and', 'username=:user', 'email=:email'], [':user' => $username, ':email' => $email])->one();
        if (!$model)
            return null;
        return $model;
    }

    /**
     * 比较密码
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 根据resetToken 获取模型
     * @param $token
     * @return User|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            //'status' => self::STATUS_ACTIVE,
        ]);


    }
}