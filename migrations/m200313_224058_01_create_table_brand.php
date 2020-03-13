<?php

use yii\db\Migration;

class m200313_224058_01_create_table_brand extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%brand}}', [
            'id' => $this->primaryKey()->comment('id'),
            'slug' => $this->string()->notNull()->comment('Машинное имя'),
            'name' => $this->string()->notNull()->comment('Имя'),
            'content' => $this->string()->comment('Описание'),
            'keywords' => $this->string()->comment('Мета-тег keywords'),
            'description' => $this->string()->comment('Мета-тег description'),
        ], $tableOptions);

        $this->createIndex('slug', '{{%brand}}', 'slug', true);
    }

    public function down()
    {
        $this->dropTable('{{%brand}}');
    }
}
