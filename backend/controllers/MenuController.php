<?php
namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;
use yii\web\Controller;

class MenuController extends Controller{

    //添加
    public function actionAdd(){
        //创建模型对象
        $model = new Menu();
        //创建request对象
        $requset = \Yii::$app->request;
        //判断是否post提交
        if ($requset->isPost){
            //加载数据
            $model->load($requset->post());
//            var_dump($model->rule);exit;
            //判断验证规则
            if ($model->validate()){
                //保存
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['menu/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }

    //列表
    public function actionIndex(){
        //查询出所用数据
        $query = Menu::find();
        //创建分页工具类
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->count();
        //每页显示条数
        $pager->defaultPageSize = 5;
        //查询当前显示的条数
        $menus = $query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //显示页面
        return $this->render('index',['menus'=>$menus,'pager'=>$pager]);
    }

    //修改
    public function actionEdit($id){
        //创建模型对象
        $model = Menu::findOne(['id'=>$id]);
        //创建request对象
        $requset = \Yii::$app->request;
        //判断是否post提交
        if ($requset->isPost){
            //加载数据
            $model->load($requset->post());
//            var_dump($model->rule);exit;
            //判断验证规则
            if ($model->validate()){
                //保存
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['menu/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete(){
       $data = $_POST;
//       var_dump($data);
       $id =  $data['id'];
//       var_dump($id);
        $model = Menu::findOne(['id'=>$id]);
        $model->delete();
        echo json_encode(['code'=>1]);
        //跳转
//        return $this->redirect(['menu/index']);
    }
}