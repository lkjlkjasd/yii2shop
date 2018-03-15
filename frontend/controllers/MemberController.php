<?php
namespace frontend\controllers;


use backend\models\LoginForm;
use frontend\models\Cart;
use frontend\models\Member;
use yii\web\Controller;

class MemberController extends Controller{
    //注册
    public function actionRegist(){
        //创建模型对象
        $model = new Member();
        //创建request对象
        $request = \Yii::$app->request;
        if ($request->isPost){
            //加载数据
            $model->load($request->post(),'');
//            var_dump($model);exit;
            if ($model->validate() && $this->actionValidateSms($model->tel,$model->code)){
//                var_dump($model);exit;
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->status = 1;
                $model->created_at = time();
                $model->auth_key = \Yii::$app->security->generateRandomString();
//                var_dump($model);exit;
                if ($model->save(0)){
                    //跳转
                    return $this->redirect(['member/login']);
                }else{
                    //跳转
                    return $this->redirect(['member/regist']);
                }
            }
        }
        return $this->render('regist');
    }

    //登录
    public function actionLogin(){
        //创建模型对象
        $model = new LoginForm();
        //创建request对象
        $request = \Yii::$app->request;
//        var_dump($request->post());exit;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post(),'');
//            var_dump($model);exit;
            //判断验证规则
            if ($model->validate()){
                //验证用户名
                $membr = Member::findOne(['username'=>$model->username]);
//                var_dump($membr);exit;
//                var_dump($membr);exit;
                if ($membr){
                    if (\Yii::$app->security->validatePassword($model->password_hash,$membr->password_hash)){

                        $membr->last_login_time = time();
                        $membr->last_login_ip = ip2long($_SERVER["REMOTE_ADDR"]);
                        //保存到cookie
                        $duration = $model->rememberMe?3*24*3600:0;

                        $membr->save(0);
//                        var_dump($duration);exit;
//保存用户到session
                        \Yii::$app->user->login($membr,$duration);
                        //同步数据
                        $cookies = \Yii::$app->request->cookies;
                        $value = unserialize($cookies->get('carts'));
                        if ($value){
                            $member_id = \Yii::$app->user->id;
                            foreach ($value as $goods_id=>$amount){
                                $carts  = Cart::find()->where(['member_id'=>$member_id])->andWhere(['goods_id'=>$goods_id])->one();
                                if ($carts){
                                    $carts->amount = $carts->amount+$amount;
                                    $carts->save(0);
                                }else{
                                    $cart = new Cart();
                                    $cart->goods_id = $goods_id;
                                    $cart->member_id = $member_id;
                                    $cart->amount = $amount;
                                    $cart->save();
                                }
                            }
                        }

                        \Yii::$app->response->cookies->remove('carts');
                        //跳转
                        return $this->redirect(['index/index']);
                    }else{
                        //密码错误
                        return $this->redirect(['member/login']);
                    }
                }else{
                    //用户名失败
                    return $this->redirect(['member/login']);
                }
            }
        }
        //显示页面
        return $this->render('login');
    }


    //验证 用户名的方法
    public function actionName($username){

        if (Member::find()->where(['username'=>$username])->all()){
            return "false";
        }
        return "true";
    }
    //验证 邮箱的方法
    public function actionEmail($email){

        if (Member::find()->where(['email'=>$email])->all()){
            return "false";
        }
        return "true";
    }

//验证短信验证码
    public function actionValidateSms($tel,$code){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $c = $redis->get('code_'.$tel);
        if($c == $code){
            return 'true';
        }
        return 'false';
    }
    //发送短信
    public function actionSms($tel){
        //保存验证码 mysql session redis
        $code = rand(100000,999999);
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('code_'.$tel,$code,5*60);
        $r=\Yii::$app->sms->setTel($tel)
            ->setParams(['code'=>$code])
            ->send();
        if($r){
            return 'success';
        }
        return 'fail';
    }
}