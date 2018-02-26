<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord{
    //图片
    public $img;

    public function attributeLabels(){
        return [
            'name' => '名称',
            'intro' => '简介',
            'is_deleted' => '状态',
            'sort' => '排序',
            'img' => '图片'
        ];
    }
    public function rules()
    {
        return [
          [['name','intro','sort',],'required'],

        ];
    }


}