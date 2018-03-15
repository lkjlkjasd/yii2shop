<?php
namespace backend\models;


use yii\db\ActiveRecord;

class Goods extends ActiveRecord{

    public function attributeLabels(){
        return [
            'name' => '名称',
            'sn' => '货号',
            'logo' => '头像',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'sort' => '排序',
        ];
    }
    public function rules()
    {
        return [
            [['name','logo','goods_category_id','brand_id','market_price','shop_price'
            ,'stock','is_on_sale','sort'],'required'],

        ];
    }
    //关联表
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    //查询数据
    public static function addBrand(){
        //查询出所有的分类
        $rows = Brand::find()->select(['id','name'])->all();
//        var_dump($rows);exit;
        $arr = [];
        foreach ($rows as $row){
            $arr[$row->id] = $row->name;
        }
        return $arr;
//        var_dump($arr);exit;
    }
    public function getGoods(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
}