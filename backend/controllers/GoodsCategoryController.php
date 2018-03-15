<?php
namespace backend\controllers;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;

class GoodsCategoryController extends Controller{

    //添加
    public function actionAdd(){
        //创建模型对象
        $model = new GoodsCategory();
        //创建request独享
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                if ($model->parent_id){
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{ //保存
                    $model->makeRoot();

                }
                //信息提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['goods-category/index']);
            }
        }
        $nodels = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//        var_dump($nodels);exit;
        $nodels[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //显示页面
        return $this->render('add',['model'=>$model,'nodels'=>json_encode($nodels)]);
    }
    //列表
    public function actionIndex(){
        //查询出所用数据
        $query = GoodsCategory::find();
        //创建分页工具类
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->count();
        //每页显示条数
        $pager->defaultPageSize = 5;
        //查询当前显示的条数
        $goods = $query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //显示页面
        return $this->render('index',['goods'=>$goods,'pager'=>$pager]);
    }
    //删除
    public function actionDelete($id){
        //根据id查找状态
        $model = GoodsCategory::findOne(['id'=>$id]);
        if (!$model->parent_id){
            //信息提示
            \Yii::$app->session->setFlash('success','不能删除有子分类的数据');
            //跳转
            return $this->redirect(['goods-category/index']);

        }else{
            //删除数据
            $model->delete();
            //判断
            //信息提示
            \Yii::$app->session->setFlash('success','删除成功');
            //跳转
            return $this->redirect(['goods-category/index']);

        }

    }
    //修改
    public function actionEdit($id){
        //创建模型对象
        $model = GoodsCategory::findOne(['id'=>$id]);
        //创建request独享
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                if ($model->parent_id){
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{ //保存
                    if ($model->parent_id == 0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }



                }
                //信息提示
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['goods-category/index']);
            }
        }
        $nodels = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//        var_dump($nodels);exit;
        $nodels[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //显示页面
        return $this->render('add',['model'=>$model,'nodels'=>json_encode($nodels)]);
    }
}