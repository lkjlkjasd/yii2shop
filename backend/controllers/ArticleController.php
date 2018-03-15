<?php
namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends Controller{
    //添加
    public function actionAdd(){
        //创建request对象
        $request = \Yii::$app->request;
        //创建文章模型
        $model = new Article();
        //创建文章内容模型
        $content = new ArticleDetail();
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            $content->load($request->post());
            //验证规则
            if ($model->validate() && $content->validate()){
                //加载状态
                $model->is_deleted = 0;
                //创建时间
                $model->create = time();
                //保存数据
                $model->save();
                //获取文章表的id
                $article_id = $model->attributes['id'];
                //给内容id赋值
                $content->article_id = $article_id;
                //var_dump($content->article_id);exit;
                //保存内容数据
                $content->save();
                //信息提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['article/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model,'content'=>$content]);
    }
    //列表
    public function actionIndex(){
        //查询出所用的数据
        $query = Article::find();
        //创建分页工具类
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->where(['is_deleted'=>0])->count();
        //每页显示条数
        $pager->defaultPageSize = 5;
        //查询出当前页的数据
        $articles = $query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //显示页面
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);
    }
    //查看
    public function actionSee($id){
//        var_dump($id);exit;
        $model = ArticleDetail::findOne(['article_id'=>$id]);
        //显示页面
        return $this->render('see',['model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        //查询数据
        $model = Article::findOne(['id'=>$id]);
        //修改状态
        $model->is_deleted = 1;
        //保存数据
        $model->save();
        //信息提示
        \Yii::$app->session->setFlash('success','添加成功');
        //跳转
        return $this->redirect(['article/index']);
    }
    //修改
    public function actionEdit($id){
        //创建request对象
        $request = \Yii::$app->request;
        //创建文章模型
        $model = Article::findOne(['id'=>$id]);
        //创建文章内容模型
        $content = ArticleDetail::findOne(['article_id'=>$id]);
        //判断是否post提交
        if ($request->isPost){
            //加载数据
            $model->load($request->post());
            $content->load($request->post());
            //验证规则
            if ($model->validate() && $content->validate()){
                //保存数据
                $model->save();
                //保存内容数据
                $content->save();
                //信息提示
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['article/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model,'content'=>$content]);
    }
}