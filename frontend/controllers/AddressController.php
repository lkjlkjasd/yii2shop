<?php
namespace frontend\controllers;


use frontend\models\Address;
use yii\web\Controller;

class AddressController extends Controller{

    //添加
    public function actionAdd(){
        //创建表单模型
        $model = new Address();
        //创建request对象
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
//           var_dump($request->post());
            $model->load($request->post(),'');
//            var_dump($model);
            //判断验证规则
            if ($model->validate()){
                $id = \Yii::$app->user->id;
                $model->member_id = $id;
                $model->save(0);
                //跳转
                return $this->redirect(['address/index']);
            }else{
                return "没保存";
            }
        }
        //显示页面
        return $this->render('address');
    }
    //列表
    public function actionIndex(){
        $id = \Yii::$app->user->id;
        //获取所有的数据
        $models = Address::find()->where(['member_id'=>$id])->all();

        //显示页面
        return $this->render('index',['models'=>$models]);
    }

    //删除
    public function actionDelete(){
//        var_dump($_GET);
        $id = $_GET['id'];
        //根据id删除
        $model = Address::findOne(['id'=>$id]);
        $model->delete();
        echo json_encode(['code'=>1]);
    }

    //修改
    public function actionEdit($id){
        //创建模型
        $model = Address::findOne(['id'=>$id]);
//        var_dump($model);exit;
        //显示页面
        return $this->render('edit',['model'=>$model]);
    }
}