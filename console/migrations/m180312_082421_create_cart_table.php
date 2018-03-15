<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m180312_082421_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'amount' => $this->integer()->comment('商品数量'),
            'member_id' => $this->integer()->comment('用户id')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cart');
    }
}
