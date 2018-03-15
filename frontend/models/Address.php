<?php
namespace frontend\models;


use yii\db\ActiveRecord;

class Address extends ActiveRecord{

    public function rules()
    {
        return [
            [['name','province','city','area','address','phone'],'required'],
        ];
    }
}