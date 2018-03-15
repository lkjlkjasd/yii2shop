<?php
namespace backend\models;


use yii\base\Model;
use yii\db\ActiveRecord;

class UpForm extends Model {
    public $username;
    public $password;
    public $password_hash;
    public $password_old;
    public function attributeLabels(){
        return [
            'password_hash' => '旧密码',
            'password' => '新密码',
            'password_old' => '确认密码',
            'username' => '用户名'
        ];
    }
    public function rules()
    {
        return [
            [['password_hash','password','password_old'],'required'],

        ];
    }
}