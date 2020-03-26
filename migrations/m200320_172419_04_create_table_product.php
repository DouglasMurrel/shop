<?php

use yii\db\Migration;

class m200320_172419_04_create_table_product extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey()->comment('id'),
            'slug' => $this->string()->notNull()->comment('Машинное имя'),
            'category_id' => $this->integer()->defaultValue('0')->comment('Категория'),
            'name' => $this->string()->notNull()->comment('Имя'),
            'content' => $this->text()->comment('Описание'),
            'price' => $this->float()->notNull()->defaultValue('0')->comment('Цена'),
            'keywords' => $this->string()->comment('Мета-тег keywords'),
            'description' => $this->string()->comment('Мета-тег description'),
        ], $tableOptions);

        $this->createIndex('name', '{{%product}}', 'name', true);
        $this->createIndex('slug', '{{%product}}', 'slug', true);
        $this->addForeignKey('category_fk', '{{%product}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%product}}');
    }
}
