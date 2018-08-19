<?php

use yii\db\Migration;

/**
 * Class m180818_085503_new_table_estado
 */
class m180818_085503_new_table_estado extends Migration
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
        echo "m180818_085503_new_table_estado cannot be reverted.\n";

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
        
        $this->createTable('{{%estado}}', [
            'id' => $this->primaryKey(),
            'estado_nombre' => $this->char(45)->notNull(),
            'estado_valor' => $this->integer(11)->notNull(),
            
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%estado}}');
    }
    
}
