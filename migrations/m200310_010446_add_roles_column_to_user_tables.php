<?php

use yii\db\Migration;

/**
 * Class m200310_010446_add_roles_column_to_user_tables
 */
class m200310_010446_add_roles_column_to_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'roles', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'roles');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200310_010446_add_roles_column_to_user_tables cannot be reverted.\n";

        return false;
    }
    */
}
