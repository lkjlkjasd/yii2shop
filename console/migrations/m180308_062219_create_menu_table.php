<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m180308_062219_create_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=> $this->string()->comment('菜单名称'),
            's_menu' => $this->string()->comment('上架菜单'),
            'rule' => $this->string()->comment('地址'),
            'sort' => $this->string()->comment('排序')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('menu');
    }
}
