<?php

use yii\db\Migration;

class m200410_224533_07_create_table_order_item extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey()->comment('Идентификатор элемента'),
            'order_id' => $this->integer()->notNull()->comment('Идентификатор заказа'),
            'product_id' => $this->integer()->comment('Идентификатор товара'),
            'name' => $this->string()->notNull()->defaultValue('')->comment('Наименование товара'),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('Цена товара'),
            'quantity' => $this->integer()->notNull()->defaultValue('1')->comment('Количество в заказе'),
            'cost' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('Стоимость = Цена * Кол-во'),
        ], $tableOptions);

        $this->createIndex('order_id', '{{%order_item}}', 'order_id');
        $this->createIndex('product_id', '{{%order_item}}', 'product_id');
        $this->addForeignKey('order_item_ibfk_1', '{{%order_item}}', 'order_id', '{{%order}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('order_item_ibfk_2', '{{%order_item}}', 'product_id', '{{%product}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%order_item}}');
    }
}
