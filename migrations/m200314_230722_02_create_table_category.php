<?php

use yii\db\Migration;

class m200314_230722_02_create_table_category extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey()->comment('id'),
            'slug' => $this->string()->notNull()->comment('Машинное имя'),
            'parent_id' => $this->integer()->notNull()->defaultValue('0')->comment('Родительская категория'),
            'name' => $this->string()->notNull()->comment('Имя'),
            'content' => $this->text()->comment('Описание'),
            'keywords' => $this->string()->comment('Мета-тег keywords'),
            'description' => $this->string()->comment('Мета-тег description'),
        ], $tableOptions);

        $this->createIndex('parent', '{{%category}}', 'parent_id');
        $this->createIndex('slug', '{{%category}}', 'slug', true);
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
    }
}
