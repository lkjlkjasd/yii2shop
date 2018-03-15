<?php
namespace backend\controllers;


use backend\models\RbacForm;
use backend\models\RuleForm;
use Qiniu\Processing\PersistentFop;
use yii\web\Controller;
use yii\web\HttpException;

class RbacController extends Controller{



    public function actionTest(){
        //$authManager = \Yii::$app->authManager;
        //1 创建权限  使用路由作为权限名称,方便后面的权限检测
        //添加品牌
//       $pemission =  $authManager->createPermission('brand/add');
//       $pemission->description = '添加品牌';
//       //保存到数据库
//        $authManager->add($pemission);

//       $permission = $authManager->createPermission('brand/index');
//        $permission->description = '品牌列表';
//        //保存数据到数据库
//        $authManager->add($permission);

        //添加超级管理员
//        $role = $authManager->createRole('超级管理员');
//        //保存到数据库
//        $authManager->add($role);

        //添加普通管理员
//        $role2 =  $authManager->createRole('管理员');
//        //保存到数据库
//        $authManager->add($role2);

        //给角色关联权限 超级管理员-->添加品牌,品牌列表    普通员工-->品牌列表
        //角色(parent),权限(child)
        //获取角色
//        $role = $authManager->getRole('超级管理员');
//        $permission1 = $authManager->createPermission('brand/add');
//        $permission2 = $authManager->createPermission('brand/index');
//        $authManager->addChild($role,$permission1);
//        $authManager->addChild($role,$permission2);

//        $role = $authManager->getRole('管理员');
//        $permission = $authManager->createPermission('brand/index');
//        $authManager->addChild($role,$permission);

        //给用户指派角色
//       $role1 = $authManager->getRole('超级管理员');
//       $role2 = $authManager->getRole('管理员');
//       $authManager->assign($role1,3);
//       $authManager->assign($role2,8);
//       $rle = \Yii::$app->user->can('brand/add');
//       var_dump($rle);
    }

    public function actionAdd(){
        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $model = new RbacForm();
        $model->scenario = RbacForm::SCENARID_ADD;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
//            var_dump($model);exit;
            //判断验证规则
            if ($model->validate()){
                //创建authManager对象
                $authManager = \Yii::$app->authManager;
               $pemission =  $authManager->createPermission($model->name);
               $pemission->description = $model->description;
               //保存到数据库
                if ($authManager->add($pemission)){
                    //信息提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['rbac/index']);
                }
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }

    //列表
    public function actionIndex(){
        $authManager = \Yii::$app->authManager;
        $getPermissions = $authManager->getPermissions();
//        var_dump($rle);exit;
        //显示页面
        return $this->render('index',['getPermissions'=>$getPermissions]);
    }

    //删除
    public function actionDelete($name){
        $authManager = \Yii::$app->authManager;
        $Permissions = $authManager->getPermission($name);
        $authManager->remove($Permissions);
        //信息提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        return $this->redirect(['rbac/index']);
    }

    //修改
    public function actionEdit($name){
        $authManager = \Yii::$app->authManager;
        $pemission = $authManager->getPermission($name);

        //创建模型对象
        $model = new RbacForm();
        $model->scenario = RbacForm::SCENARID_EDIT;
        $model->name = $pemission->name;
        $model->description = $pemission->description;
        //创建request对象
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                $pemission->name = $model->name;
                $pemission->description = $model->description;
                $authManager->update($name,$pemission);
                //信息提示
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['rbac/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }

    //角色添加
    public function actionAddRule(){
        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $model = new RuleForm();
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
//            var_dump($model);exit;
            //判断验证规则
            if ($model->validate()){
                //创建authManager对象
                $authManager = \Yii::$app->authManager;
                $rule = $authManager->createRole($model->name);
                $rule->description = $model->description;
//                var_dump($model->pemission);exit;
                if ($authManager->add($rule)){
                    $arr = $model->pemission;
                    foreach ($arr as $val){
                        $king = $authManager->getPermission($val);
                        $authManager->addChild($rule,$king);
                    }
                    //信息提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['rbac/rule-index']);
                }
            }
        }
        //显示页面
        return $this->render('ruleForm',['model'=>$model]);
    }
    //列表
    public function actionRuleIndex(){
        $authManager = \Yii::$app->authManager;
        $getRoles = $authManager->getRoles();
//        var_dump($rle);exit;
        //显示页面
        return $this->render('rule',['getRoles'=>$getRoles]);
    }

    //删除
    public function actionRuleDelete($name){
        $authManager = \Yii::$app->authManager;
        $Role = $authManager->getRole($name);
        $authManager->remove($Role);
        //信息提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        return $this->redirect(['rbac/rule-index']);
    }
    //修改
    public function actionRuleEdit($name){
        $authManager = \Yii::$app->authManager;
        $Rule = $authManager->getRole($name);

        //创建模型对象
        $model = new RuleForm();
        $model->scenario = RbacForm::SCENARID_EDIT;
        $model->name = $Rule->name;
        $model->description = $Rule->description;
        //获取该角色拥有的权限
        $permissions = $authManager->getPermissionsByRole($Rule->name);
        $model->pemission = [];
        foreach ($permissions as $permission){
            $model->pemission[]=$permission->name;
        }
        //创建request对象
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                $Rule->name = $model->name;
                $Rule->description = $model->description;
                $authManager->update($name,$Rule);
                //清除该角色关联的所有权限
                $authManager->removeChildren($Rule);
                if(is_array($model->pemission)){
                    foreach ($model->pemission as $permissionName){
                        $permission = $authManager->getPermission($permissionName);
                        $authManager->addChild($Rule,$permission);
                    }
                }
                //信息提示
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['rbac/rule-index']);
            }
        }
        //显示页面
        return $this->render('ruleForm',['model'=>$model]);
    }
}