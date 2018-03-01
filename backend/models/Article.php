<?php
namespace backend\models;


use backend\controllers\ArticleCategoryController;
use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    public function attributeLabels(){
        return [
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
        ];
    }
    public function rules()
    {
        return [
            [['name','intro','article_category_id','sort',],'required'],

        ];
    }
    //关联表字段
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }

    //查询文章分类的数据
    public static function addArticle(){
        //查询出所有的文章分类
        $rows = ArticleCategory::find()->select(['id','name'])->where(['is_deleted'=>0])->all();
        $arr = [];
        foreach ($rows as $row){
            $arr[$row->id] = $row->name;
        }
        return $arr;
    }
}