<?php
namespace backend\controllers;

use backend\models\Articlecategory;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleCategoryController extends Controller{
    //添加
    public function actionAdd(){
        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $model = new Articlecategory();
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                //加载状态
                $model->is_deleted = 0;
                //保存数据
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
//列表
    public function actionIndex(){
        //查询出所用数据
        $query = Articlecategory::find();
        //创建分页工具类
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->where(['is_deleted'=>0])->count();
        //每页显示条数
        $pager->defaultPageSize = 5;
        //查询当前显示的条数
        $rows = $query->where(['is_deleted'=>0])->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //显示页面
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }
    //删除
    public function actionDelete($id){

        //根据id修改状态
        $model = Articlecategory::findOne(['id'=>$id]);
        //修改状态值
//        var_dump($model['is_deleted']);exit;
        $model->is_deleted=1;
        //保存数据
        $model->save();
        //信息提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        return $this->redirect(['articlecategory/index']);
    }
    //修改
    public function actionEdit($id){
        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $model = Articlecategory::findOne(['id'=>$id]);
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                //保存数据
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['articlecategory/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}