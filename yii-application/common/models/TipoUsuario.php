<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tipo_usuario".
 *
 * @property int $id
 * @property string $tipo_usuario_nombre
 * @property int $tipo_usuario_valor
 */
class TipoUsuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_usuario_nombre', 'tipo_usuario_valor'], 'required'],
            [['tipo_usuario_valor'], 'integer'],
            [['tipo_usuario_nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_usuario_nombre' => 'Tipo Usuario Nombre',
            'tipo_usuario_valor' => 'Tipo Usuario Valor',
        ];
    }
    
    /*
     * relacionamos a tabla TipoUsuario con la tabla User por 'tipo_usuario_id' de la tabla User.
     *  1 user ------> 1 tipo usuario
     *  1 tipo usuario -------> N user
     */
    public function getUsers()
    {
        // el 'tipo_usuario_id' de la tabla User está relacionado directamente con el 'id' de la tabla TipoUsuario
        return $this->hasMany(User::className(), ['tipo_uduario_id' => 'id']);
    }
}
