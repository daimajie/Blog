<?php
namespace app\modules\admin\modules\member\models;
use app\models\member\User as UserModel;

class User extends UserModel
{
    //场景
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public function rules()
    {
        return [
            [['username', 'email', 'author'], 'required'],
            [['password'], 'required', 'message'=>'请填写密码'],
            [['re_password'], 'required', 'message'=>'请填写重复密码。'],

            [['username'], 'string', 'max'=>12],
            [['nickname'], 'string', 'max'=>18],
            [['password'], 'string', 'min'=>6],
            [['re_password'], 'compare', 'compareAttribute' => 'password','message' => '密码输入不一致'],
            [['author'], 'in', 'range'=>[0, 1, 2]],
            [['email'],'email'],

            [['email'],'unique'],
            [['username'], 'unique'],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['username', 'password', 're_password', 'email', 'author','nickname'],
            self::SCENARIO_UPDATE => ['username', 'email', 'author','nickname'],
        ];
    }

    /**
     * 添加用户
     * @return bool
     */
    public function signin(){
        if(!$this->validate())
            return false;

        $this->generatePasswordHash($this->password); //设置密码

        return $this->save(false);

    }

    /**
     * 修改用户信息
     */
    public function renew(){
        if(!$this->validate())
            return false;

        if(!empty($this->password)){
            $this->generatePasswordHash($this->password);
        }

        return $this->save(false);
    }
}