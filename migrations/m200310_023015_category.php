<?php

use yii\db\Migration;

/**
 * Class m200310_023015_category
 */
class m200310_023015_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey()->comment('id'),
            'parent_id' => $this->integer()->notNull()->defaultValue(0)->comment('Родительская категория'),
            'name' => $this->string()->notNull()->comment('Имя'),
            'content' => $this->string()->comment('Описание'),
            'keywords' => $this->string()->comment('Мета-тег keywords'),
            'description' => $this->string()->comment('Мета-тег description'),
            'image' => $this->string()->comment('Картинка'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200310_023015_category cannot be reverted.\n";

        return false;
    }
    */
}
