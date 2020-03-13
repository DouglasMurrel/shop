<?php

use yii\db\Migration;

/**
 * Class m200313_125456_add_key_parent_id_to_category
 */
class m200313_125456_add_key_parent_id_to_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('parent','category','parent_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('parent','category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200313_125456_add_key_parent_id_to_category cannot be reverted.\n";

        return false;
    }
    */
}
