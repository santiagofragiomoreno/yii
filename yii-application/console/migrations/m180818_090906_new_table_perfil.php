<?php

use yii\db\Migration;

/**
 * Class m180818_090906_new_table_perfil
 */
class m180818_090906_new_table_perfil extends Migration
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
        echo "m180818_090906_new_table_perfil cannot be reverted.\n";

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
        
        $this->createTable('{{%perfil}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->char(45)->notNull(),
            'nombre' => $this->text(60),
            'apellido' => $this->text(60),
            'fecha_nacimiento' => $this->dateTime(),
            'genero_id' => $this->smallInteger(6)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%perfil}}');
    }
    
}
