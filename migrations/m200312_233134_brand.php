<?php

use yii\db\Migration;

/**
 * Class m200312_233134_brand
 */
class m200312_233134_brand extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey()->comment('id'),
            'slug' => $this->string()->notNull()->comment('Машинное имя')->unique(),
            'name' => $this->string()->notNull()->comment('Имя'),
            'content' => $this->string()->comment('Описание'),
            'keywords' => $this->string()->comment('Мета-тег keywords'),
            'description' => $this->string()->comment('Мета-тег description'),
        ]);
        $this->alterColumn('product','brand_id','integer default 0');
        $this->addCommentOnColumn('product','brand_id','Бренд');
        $this->addForeignKey('brand_fk','product','brand_id','brand','id','SET NULL','CASCADE');
        $this->createTable('image',[
            'id' => $this->primaryKey()->comment('id'),
            'image' => $this->string()->comment('Картинка'),
            'entity_id' => $this->integer()->notNull()->defaultValue(0)->comment('id сущности'),
            'entity_type' => $this->string()->notNull()->comment('Тип сущности'),
            'sort' => $this->integer()->notNull()->defaultValue(0)->comment('Порядок сортировки'),
        ]);
        $this->createIndex('entity','image',['entity_id','entity_type'],true);
        $this->dropColumn('product','image');
        $this->dropColumn('category','image');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('brand_fk','product');
        $this->dropTable('brand');
        $this->dropTable('image');
        $this->addColumn('product','image','string');
        $this->addCommentOnColumn('product','image','Картинка');
        $this->alterColumn('product','brand_id','integer not null default 0');
        $this->addCommentOnColumn('product','brand_id','Бренд');
        $this->addColumn('category','image','string');
        $this->addCommentOnColumn('category','image','Картинка');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200312_233134_brand cannot be reverted.\n";

        return false;
    }
    */
}
