<?php
namespace backend\controllers;


use backend\models\Admin;
use backend\models\LoginForm;
use yii\web\Controller;

class LoginController extends Controller{
    //登录表单
    public function actionLogin(){
        //创建模型对象
        $model = new LoginForm();
        //创建request对象
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                //查询用户名表
                if ($model->login()){
                    //登录成功
                    \Yii::$app->session->setFlash('success','登录成功');
                    //跳转
                    return $this->redirect(['admin/index']);
                }
            }
        }
        //显示页面
        return $this->render('login',['model'=>$model]);
    }
    //注销
    public function actionLogout(){

        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['login/login']);
    }
}