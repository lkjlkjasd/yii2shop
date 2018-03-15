<?php
namespace backend\models;


use yii\base\Model;

class LoginForm extends Model{
    public $rememberMe;
    public $username;
    public $password_hash;
    public function attributeLabels(){
        return [
            'username' => '用户名',
            'password_hash' => '密码',
            'rememberMe' => '记住我',
        ];
    }
    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['rememberMe','safe'],
        ];
    }
    //登录验证
    public function login(){
        //验证用户名
        $admin = Admin::findOne(['username'=>$this->username]);
//        var_dump($login);exit;
        if ($admin){
            //验证密码是否正确
            if (\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){
                $admin->last_login_time = time();
                $admin->last_login_ip = ip2long($_SERVER["REMOTE_ADDR"]);

                $admin->save();
                //保存到cookie
                $duration = $this->rememberMe?3*24*3600:0;
                //保存用户到session
                return \Yii::$app->user->login($admin,$duration);
            }else{
                //密码错误
                return $this->addError('password_hash','密码错误');
            }
        }else{
            //用户名错误
            return $this->addError('username','用户名错误');

        }
        return false;
    }
}