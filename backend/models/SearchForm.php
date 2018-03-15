<?php
/**
 * Created by PhpStorm.
 * User: luoshumai
 * Date: 2018/3/3
 * Time: 11:48
 */

namespace backend\models;


use yii\base\Model;

class SearchForm extends Model
{
    public $name;//商品名
    public $sn;//货号
    public $start;//价格区间
    public $end;//价格区间

    public function rules()
    {
        return [
            [['name','sn','start','end'],'safe'],
        ];
    }

}