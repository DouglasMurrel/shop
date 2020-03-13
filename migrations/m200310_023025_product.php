<?php

use yii\db\Migration;

/**
 * Class m200310_023025_product
 */
class m200310_023025_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey()->comment('id'),
            'slug' => $this->string()->notNull()->comment('Машинное имя')->unique(),
            'category_id' => $this->integer()->defaultValue(0)->comment('Категория'),
            'brand_id' => $this->integer()->notNull()->defaultValue(0)->comment('Бренд'),
            'name' => $this->string()->notNull()->comment('Имя'),
            'content' => $this->string()->comment('Описание'),
            'price' => $this->float(2)->notNull()->defaultValue(0)->comment('Цена'),
            'keywords' => $this->string()->comment('Мета-тег keywords'),
            'description' => $this->string()->comment('Мета-тег description'),
            'image' => $this->string()->comment('Картинка'),
            'hit' => $this->boolean()->notNull()->defaultValue(0)->comment('Лидер продаж'),
            'new' => $this->boolean()->notNull()->defaultValue(0)->comment('Новый'),
            'sale' => $this->boolean()->notNull()->defaultValue(0)->comment('Распродажа'),
        ]);
        $this->addForeignKey('category_fk','product','category_id','category','id','SET NULL','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('category_fk','product');
        $this->dropTable('product');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200310_023025_product cannot be reverted.\n";

        return false;
    }
    */
}
