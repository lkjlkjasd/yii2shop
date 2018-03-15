<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_gallery`.
 */
class m180302_024118_create_goods_gallery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_gallery', [
            'id' => $this->primaryKey(),
//            goods_id	int	商品id
            'goods_id' => $this->integer()->comment('商品id'),
//path	varchar(255)	图片地址
            'path' => $this->string(255)->comment('图片地址')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_gallery');
    }
}
