<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password_hash;
    public $rememberMe;
    public $captcha;


    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['rememberMe','safe'],
            ['captcha','captcha','captchaAction'=>'site/captcha']
        ];
    }

}