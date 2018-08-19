<?php

use yii\db\Migration;

/**
 * Class m180818_081304_new_table_rol
 */
class m180818_081304_new_table_rol extends Migration
{
    /**
     * {@inheritdoc}
     */
    /*
    public function safeUp()
    {

    }
    */
    /**
     * {@inheritdoc}
     */
    /*
    public function safeDown()
    {
        echo "m180818_081304_new_table_rol cannot be reverted.\n";

        return false;
    }
    */
    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
       
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%rol}}', [
            'id' => $this->primaryKey(),
            'rol_nombre' => $this->char(45)->notNull(),
            'rol_valor' => $this->integer(11)->notNull(),
            
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%rol}}');
    }
    
}
