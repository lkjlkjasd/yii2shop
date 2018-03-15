<?php
namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class RbacForm extends Model{
    public $name;
    public $description;

    const SCENARID_ADD = 'add';
    const SCENARID_EDIT = 'edit';
    public function attributeLabels(){
        return [
            'name' => '路由',
            'description' => '名称'
        ];
    }
    public function rules(){
        return [
            [['name','description'],'required'],
            ['name','only','on'=>self::SCENARID_ADD],
            ['name','modify','on'=>self::SCENARID_EDIT]
        ];
    }

    public function only(){
        $authManager = \Yii::$app->authManager;
        if (!$authManager->createPermission($this->name)){
            return $this->addError('name','权限已存在');
        }
    }

    public function modify(){
        //修改了name，验证name是否存在
        if(\Yii::$app->request->get('name') != $this->name){
            $this->modify();
        }

    }
    public static function getPemission(){
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }
}