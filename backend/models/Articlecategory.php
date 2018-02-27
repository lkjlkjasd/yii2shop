<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Articlecategory extends ActiveRecord{
    public function attributeLabels(){
        return [
            'name' => '名称',
            'intro' => '简介',
            'sort' => '排序',
        ];
    }
    public function rules()
    {
        return [
            [['name','intro','sort',],'required'],

        ];
    }
}