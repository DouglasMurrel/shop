<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%product}}`.
 */
class m200403_183959_add_content_column_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'code', $this->text()->notNull()->comment('Описание'));
        $this->dropColumn('{{%product}}', 'code');
        $this->dropColumn('{{%product}}', 'corpus');
        $this->dropColumn('{{%product}}', 'parameters');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', 'content');
        $this->addColumn('{{%product}}', 'code', $this->string()->notNull()->comment('Код'));
        $this->addColumn('{{%product}}', 'corpus', $this->string()->notNull()->comment('Корпус'));
        $this->addColumn('{{%product}}', 'parameters', $this->string()->notNull()->comment('Параметры'));
    }
}
