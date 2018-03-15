<?php
namespace backend\filters;


use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{

    //控制器动作执行前
    public function beforeAction($action){
        //return true;//放行
        //return false;//拦截
        //为了用户体验
        if (!\Yii::$app->user->can($action->uniqueId)){
            //如果用户没有登录就引导用户先登录
            if (\Yii::$app->user->isGuest){
                //必须加send方法,避免return true
                //return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            throw new HttpException(403,'对不起,您没有该操作权限');
        }
        return true;
    }
}