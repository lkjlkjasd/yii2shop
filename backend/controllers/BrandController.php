<?php
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;

class BrandController extends Controller{

    public $enableCsrfValidation = false;
    //添加
    public function actionAdd(){
        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $model = new Brand();
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());


            //创建上传组件
//            $model->img = UploadedFile::getInstance($model,'img');
            //判断验证规则
            if ($model->validate()){
                //加载状态
                $model->is_deleted = 0;
                //保存上传文件
//                $file = '/upload/'.uniqid().'.'.$model->img->extension;
                //移动文件
//                $model->img->saveAs(\Yii::getAlias('@webroot').$file,0);
//                $model->logo = $file;
                //保存数据
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['brand/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }
    //列表
    public function actionIndex(){
        //查询出所用数据
        $query = Brand::find();
        //创建分页工具类
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->where(['is_deleted'=>0])->count();
        //每页显示条数
        $pager->defaultPageSize = 5;
        //查询当前显示的条数
        $brands = $query->where(['is_deleted'=>0])->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //显示页面
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
    }
    //删除
    public function actionDelete($id){

        //根据id修改状态
        $model = Brand::findOne(['id'=>$id]);
        //修改状态值
//        var_dump($model['is_deleted']);exit;
        $model->is_deleted=1;
        //保存数据
        $model->save();
        //信息提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        return $this->redirect(['brand/index']);
    }
    //修改
    public function actionEdit($id){
        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $model = Brand::findOne(['id'=>$id]);
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //创建上传组件
            $model->img = UploadedFile::getInstance($model,'img');
            //判断验证规则
            if ($model->validate()){
                //保存上传文件
                $file = '/upload/'.uniqid().'.'.$model->img->extension;
                //移动文件
                $model->img->saveAs(\Yii::getAlias('@webroot').$file,0);
                $model->logo = $file;
                //保存数据
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['brand/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }
    //接收jajx传的数据
    public function actionUpload(){
    //实例化上传文件夹
        $uploadFile = UploadedFile::getInstanceByName('file');
        //保存数据
        $fileName = '/upload/'.uniqid().'.'.$uploadFile->extension;
        $result = $uploadFile->saveAs(\Yii::getAlias('@webroot').$fileName);
        if ($result){
            //保存成功
            return json_encode([
               'url'=>$fileName
            ]);
        }
    }
    //过滤器
    public function behaviors(){
        return [
          'rbac'=>[
            'class'=>RbacFilter::class,
              'except'=>['upload']
          ]
        ];
    }
}