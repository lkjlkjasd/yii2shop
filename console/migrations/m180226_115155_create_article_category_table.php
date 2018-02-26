<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m180226_115155_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->comment('名称'),
            'intro' => $this->text()->comment('简介'),
            'sort' => $this->integer(11)->comment('排序'),
            'is_deleted' => $this->integer(1)->comment('状态0正常1删除')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
