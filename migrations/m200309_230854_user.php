<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

/**
 * Class m200309_230854_user
 */
class m200309_230854_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
//            'id' => Schema::TYPE_PK,
            'id' => $this->primaryKey(),
            'email' => $this->string(180)->notNull()->unique(),
            'roles' => $this->json(),
            'password' => $this->string(),
            'passwordHash' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }

}
