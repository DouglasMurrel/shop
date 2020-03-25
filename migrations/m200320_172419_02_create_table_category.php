<?php

use yii\db\Migration;

class m200320_172419_02_create_table_category extends Migration
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
            'name' => $this->string()->notNull()->comment('Имя'),
            'content' => $this->text()->comment('Описание'),
            'keywords' => $this->string()->comment('Мета-тег keywords'),
            'description' => $this->string()->comment('Мета-тег description'),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('slug', '{{%category}}', 'slug', true);
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
    }
}
