<?php

use yii\db\Migration;

/**
 * Class m190805_045419_create_table_auth
 */
class m190805_045419_create_table_auth extends Migration
{
    public function safeUp() {
        $this->createTable('auth', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_auth_user_id_user', 'auth', 'user_id', 'user', 'id', 'restrict', 'cascade');
    }
    public function safeDown() {
        $this->dropForeignKey('fk_auth_user_id_user', 'auth');
        $this->dropTable('auth');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190805_045419_create_table_auth cannot be reverted.\n";

        return false;
    }
    */
}
