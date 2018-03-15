<?php
namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

class GoodsCategory extends ActiveRecord{
    public function attributeLabels(){
        return [
            'name' => '名称',
            'intro' => '简介',
            'parent_id' => '上级分类'
        ];
    }
    public function rules()
    {
        return [
            [['name','parent_id','intro'],'required'],
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
}