<?php
namespace backend\controllers;


use backend\models\GoodsGallery;
use yii\web\Controller;

class GoodsGalleryController extends Controller{
    //列表
    public function actionIndex(){
        $request = \Yii::$app->request;
        $id = $request->get('id');
//        var_dump($id);exit;
        $models = GoodsGallery::find()->where(['goods_id'=>$id])->all();
//        var_dump($models);exit;
        //创建模型
        $model = new GoodsGallery();
        if ($request->isPost){

            //接收数据
            $data = $request->post();
            var_dump($data);
            $model->goods_id = $data['id'];
            $model->path = $data['imgUrl'];
            //保存
            $model->save();

        if ($model->save()){
            return json_encode([
               'url' => $model->path
            ]);
            exit;
        }

        }
        //显示页面
        return $this->render('index',['model'=>$model,'models'=>$models,'id'=>$id]);
    }
    //删除相册
    public function actionDelete($id,$goods_id){
//        var_dump($id);exit;
        //创建request对象
        $request = \Yii::$app->request;
        //创建模型对象
        $model = GoodsGallery::findOne(['id'=>$id]);
        //删除数据
        $model->delete();
//        //跳转
        return $this->redirect(['goods-gallery/index','id'=>$goods_id]);
    }

}