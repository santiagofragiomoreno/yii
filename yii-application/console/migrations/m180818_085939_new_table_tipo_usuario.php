<?php

use yii\db\Migration;

/**
 * Class m180818_085939_new_table_tipo_usuario
 */
class m180818_085939_new_table_tipo_usuario extends Migration
{
    /**
     * {@inheritdoc}
     */
    /*
    public function safeUp()
    {

    }

   */ /**
     * {@inheritdoc}
     */
    /*
    public function safeDown()
    {
        echo "m180818_085939_new_table_tipo_usuario cannot be reverted.\n";

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
        
        $this->createTable('{{%tipo_usuario}}', [
            'id' => $this->primaryKey(),
            'tipo_usuario_nombre' => $this->char(45)->notNull(),
            'tipo_usuario_valor' => $this->integer(11)->notNull(),
            
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%tipo_usuario}}');
    }
    
}
