<?php
namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class RuleForm extends Model{
    public $name;
    public $description;
    public $pemission;
    const SCENARID_ADD = 'add';
    const SCENARID_EDIT = 'edit';
    public function attributeLabels(){
        return [
            'name' => '名称',
            'description' => '描述',
            'pemission'=>'权限'
        ];
    }
    public function rules(){
        return [
            [['name','description','pemission'],'required'],
            ['name','only','on'=>self::SCENARID_ADD],
            ['name','modify','on'=>self::SCENARID_EDIT]
        ];
    }
    public function only(){
        $authManager = \Yii::$app->authManager;
        if ($authManager->getRole($this->name)){
            return $this->addError('name','角色已存在');
        }
    }

    public function modify(){
        //修改了name，验证name是否存在
        if(\Yii::$app->request->get('name') != $this->name){
            $this->only();
        }

    }
    public static function getPemission(){
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }
}