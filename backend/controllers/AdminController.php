<?php
namespace backend\controllers;


use backend\models\Admin;
use backend\models\Edit;
use backend\models\Up;
use backend\models\UpForm;
use yii\data\Pagination;
use yii\web\Controller;

class AdminController extends Controller{
    //添加
    public function actionAdd(){

        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $model = new Admin();
//        var_dump($model->rule);exit;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->status = 0;
            $model->load($request->post());
            //判断验证
            if ($model->validate()){
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
//                var_dump($model->password_hash);exit;
//                var_dump($model->password_hash);exit;
                $model->created_at = time();
                //保存随机字符串
                $model->auth_key = \Yii::$app->security->generateRandomString();
                //保存数据
                $model->save();
                //创建authManager对象
                $authManager = \Yii::$app->authManager;
                $arr = $model->rule;
                foreach ($arr as $val){
                    $king = $authManager->getRole($val);
                    $authManager->assign($king,$model->id);
                }
//                exit;
                //信息提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['admin/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }
    //列表
    public function actionIndex(){
        //查询出所用数据
        $query = Admin::find();
        //创建分页工具类
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->count();
        //每页显示条数
        $pager->defaultPageSize = 5;
        //查询当前显示的条数
        $admins = $query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //显示页面
        return $this->render('index',['admins'=>$admins,'pager'=>$pager]);
    }
    //修改
    public function actionEdit($id)
    {
        $model = Admin::findOne(['id' => $id]);
        $authManger = \Yii::$app->authManager;
        $rules =  $authManger->getRolesByUser($id);
        $model->rule = [];
        foreach ($rules as $rule){
            $model->rule[]=$rule->name;
        }
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->updated_at =time();
                if ($model->save()){
                    //提交多选框
                    //清楚该角色的所有权限
                    $authManger->revokeAll($model->id);
                    if (is_array($model->rule)){
                        foreach ($model->rule as $permissionName){
                            $permission = $authManger->getRole($permissionName);
                            $authManger->assign($permission,$model->id);
                        }
                    }
                }
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['admin/index']);
            } else {
                //提示错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    //删除
    public function actionDelete($id){
        //根据id删除数据
        $model = Admin::findOne(['id'=>$id]);
        //删除
        $model->delete();
        //信息提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        return $this->redirect(['admin/index']);
    }
    //修改密码
    public function actionPass($id){
        //根据id查询数据
        $model = Admin::findOne(['id'=>$id]);
        //给密码赋值null
        $model->password_hash = null;
        //创建request对象
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                //给密码加密
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                //保存
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success','修改密码成功');
                //跳转
                return $this->redirect(['admin/index']);
            }
        }
        //显示页面
        return $this->render('edit',['model'=>$model]);
    }
    //修改当前登录用户的密码
    public function actionUp($id){
        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $user = Admin::findOne(['id'=>$id]);
        //创建模型对象
        $model = new UpForm();
        //获取用户名
        $model->username = $user->username;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断规则
            if ($model->validate()){
                //赋值
                $user->password_hash = \Yii::$app->security->generatePasswordHash($model->password_old);
                $user->save();
                //信息提示
                \Yii::$app->session->setFlash('success','修改密码成功');
                //跳转
                return $this->redirect(['admin/index']);
            }
        }
        //显示页面
        return $this->render('up',['model'=>$model]);
    }
}