<?php

use yii\db\Migration;

class m200314_230722_03_create_table_image extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey()->comment('id'),
            'image' => $this->string()->comment('Картинка'),
            'entity_id' => $this->integer()->notNull()->defaultValue('0')->comment('id сущности'),
            'entity_type' => $this->string()->notNull()->comment('Тип сущности'),
            'sort' => $this->integer()->notNull()->defaultValue('0')->comment('Порядок сортировки'),
        ], $tableOptions);

        $this->createIndex('entity', '{{%image}}', ['entity_id', 'entity_type'], true);
    }

    public function down()
    {
        $this->dropTable('{{%image}}');
    }
}
