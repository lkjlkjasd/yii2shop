<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord{
    public function attributeLabels(){
        return [
            'name' => '名称',
            's_menu' => '上级分类',
            'rule' => '地址',
            'sort' => '排序',
        ];
    }
    public function rules()
    {
        return [
            [['name','s_menu','rule','sort'],'required'],

        ];
    }


    public static function addMenu(){
        //查询出所有的分类
        $rows = Menu::find()->where(['s_menu'=>0])->all();
//        var_dump($rows);exit;
//        $nodels[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        $arr[0] = '顶级分类';
        foreach ($rows as $row){
            $arr[$row->id] = $row->name;
        }
        return $arr;
//        var_dump($arr);exit;
    }
    public static function getPemission(){
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','name');
    }

    public static function getMenu($menuItems){
        //获取第一级的分类
        $menus = self::find()->where(['s_menu'=>0])->all();
        foreach ($menus as $menu){
            $items = [];
            $children = self::find()->where(['s_menu'=>$menu->id])->all();
            foreach ($children as $child){
                //只添加有权限的二级菜单
                if (\Yii::$app->user->can($child->rule))
                    $items[] = ['label' => $child->name, 'url' => [$child->rule]];
            }
            //只显示有子菜单的一级菜单
            if ($items)
                $menuItems[] = ['label'=>$menu->name,'items'=>$items];
        }
        return $menuItems;
    }
}
