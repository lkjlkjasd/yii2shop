<?php
namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\RbacForm;
use backend\models\SearchForm;
use Codeception\Verify;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;

class GoodsController extends Controller{
    public $enableCsrfValidation = false;
    //添加
    public function actionAdd(){
        //创建表单模型
        $model = new Goods();
        //创建内容模型
        $content = new GoodsIntro();
        //创建request对象
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //加载数据
            $content->load($request->post());
            //判断验证规则
            if ($model->validate() && $content->validate()){
                //状态
                $model->status = 1;
                //时间
                $model->create_time = time();
                $time = date('Y-m-d',time());
//                var_dump($time);exit;
//                var_dump($day);exit;
                $day = GoodsDayCount::find()->where(['day'=>$time])->one();
                if ($day){
                    $day->count=$day->count+1;
                }else {
                    $day=new GoodsDayCount();
                    $day->day=date('Y-m-d',time());
                    $day->count=1;
                }
                $day->save();
                    //保存货号
                    $model->sn = str_replace('-','',$day->day).str_pad($day->count,5,0,STR_PAD_LEFT);
                    //保存
                    $model->save();
                    //获取goods的id
                    $content->goods_id = $model->id;
//                    var_dump($content->goods_id);exit;
                    //保存content
                    $content->save();

                    //信息提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['goods/index']);


            }
        }
        $nodels = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//        var_dump($nodels);exit;
        $nodels[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //显示页面
        return $this->render('add',['model'=>$model,'content'=>$content,'nodels'=>json_encode($nodels)]);
    }
    //列表
    public function actionIndex(){
        //查询出所用数据
        $query = Goods::find()->where(['status'=>1]);
        //创建搜索模型
//        $model = new SearchForm();
        //创建request对象
        $request = \Yii::$app->request;
        //接收搜索的数据
        $name = $request->get('name');
        $sn = $request->get('sn');
        $start = $request->get('start');
        $end = $request->get('end');
        if ($name){
            $query->andWhere(['like','name',$name]);
        }
        if ($sn){
            $query->andWhere(['like','sn',$sn]);
        }
        if ($start){
            $query->andWhere(['>','shop_price',$start]);
        }
        if ($end){
            $query->andWhere(['<','shop_price',$end]);
        }
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
        //根据id查询数据
        $model = Goods::findOne(['id'=>$id]);
        //修改状态值
//        var_dump($model['is_deleted']);exit;
        $model->status=0;
        //保存数据
        $model->save();
        //信息提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        return $this->redirect(['goods/index']);
    }
    //修改
    public function actionEdit($id){
        //创建表单模型
        $model = Goods::findOne(['id'=>$id]);
        //创建表单模型
        $content = GoodsIntro::findOne(['goods_id'=>$id]);
        //创建request对象
        $request = \Yii::$app->request;
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            //判断验证规则
            if ($model->validate()){
                //状态
                $model->status = 1;
                //时间
                $model->create_time = time();
                //保存
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['goods/index']);
            }
        }
        $nodels = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//        var_dump($nodels);exit;
        $nodels[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //显示页面
        return $this->render('add',['model'=>$model,'content'=>$content,'nodels'=>json_encode($nodels)]);
    }
    //百度文本编辑器
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://www.shop.com/",//图片访问路径前缀
                    "imagePathFormat" => "/upload/img/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                "imageRoot" => \Yii::getAlias("@webroot"),
            ],
        ]
    ];
    }
    //查看
    public function actionSee($id){
        //根据id查询数据
        $model = GoodsIntro::findOne(['goods_id'=>$id]);
//        var_dump($model);exit;
        //显示页面
        return $this->render('see',['model'=>$model]);
    }
    //接收jajx传的数据
    public function actionImgUpload(){
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

//    public function behaviors()
//    {
//        return [
//            'rbac'=>[
//                'class'=>RbacForm::class,
//                'except'=>['img-upload','upload']
//            ]
//        ];
//    }

}
