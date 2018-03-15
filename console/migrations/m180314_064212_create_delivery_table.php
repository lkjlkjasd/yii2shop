<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery`.
 */
class m180314_064212_create_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('delivery', [
            'delivery_id' => $this->primaryKey(),
//            'delivery_id' =>$this->integer()->comment('配送方式id'),
            'delivery_name' => $this->string()->comment('配送方式名称'),
            'delivery_price' => $this->float()->comment('配送方式价格')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('delivery');
    }
}
