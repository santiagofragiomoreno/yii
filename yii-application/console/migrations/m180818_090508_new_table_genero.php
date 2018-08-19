<?php

use yii\db\Migration;

/**
 * Class m180818_090508_new_table_genero
 */
class m180818_090508_new_table_genero extends Migration
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
        echo "m180818_090508_new_table_genero cannot be reverted.\n";

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
        
        $this->createTable('{{%genero}}', [
            'id' => $this->primaryKey(),
            'genero_nombre' => $this->char(45)->notNull(),
            
            
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%genero}}');
    }
    
}
