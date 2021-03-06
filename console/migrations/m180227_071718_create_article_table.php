<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180227_071718_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('名称'),
            'intro' => $this->text()->comment('简介'),
            'article_category_id' => $this->integer(11)->comment('文章分类id'),
            'sort' => $this->integer(11)->comment('排序'),
            'is_deleted' => $this->integer(2)->comment('状态0正常1删除'),
            'create' => $this->integer(11)->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
