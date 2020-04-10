<?php

use yii\db\Migration;

class m200410_224533_06_create_table_order extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey()->comment('Идентификатор заказа'),
            'user_id' => $this->integer()->notNull()->defaultValue('0')->comment('Идентификатор пользователя'),
            'name' => $this->string(50)->notNull()->defaultValue('')->comment('Имя покупателя'),
            'lastname' => $this->string()->notNull()->defaultValue('')->comment('Фамилия покупателя'),
            'oname' => $this->string()->notNull()->defaultValue('')->comment('Отчество покупателя'),
            'email' => $this->string(50)->notNull()->defaultValue('')->comment('Почта покупателя'),
            'phone' => $this->string(50)->notNull()->defaultValue('')->comment('Телефон покупателя'),
            'zipcode' => $this->string(6)->notNull()->comment('Индекс'),
            'area' => $this->string()->comment('Область'),
            'city' => $this->string()->notNull()->comment('Город'),
            'address' => $this->string()->notNull()->defaultValue('')->comment('Адрес доставки'),
            'comment' => $this->string()->notNull()->defaultValue('')->comment('Комментарий к заказу'),
            'amount' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('Сумма заказа'),
            'discount' => $this->decimal(10, 2)->notNull(),
            'status' => $this->integer(2)->notNull()->defaultValue('0')->comment('Статус заказа'),
            'created' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата и время создания'),
            'updated' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата и время обновления'),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%order}}', 'user_id');
        $this->addForeignKey('order_ibfk_1', '{{%order}}', 'user_id', '{{%user}}', 'id', 'NO ACTION', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%order}}');
    }
}
