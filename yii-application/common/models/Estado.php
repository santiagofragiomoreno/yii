<?php

namespace common\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "estado".
 *
 * @property int $id
 * @property string $estado_nombre
 * @property int $estado_valor
 */
class Estado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado_nombre', 'estado_valor'], 'required'],
            [['estado_valor'], 'integer'],
            [['estado_nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'estado_nombre' => 'Estado Nombre',
            'estado_valor' => 'Estado Valor',
        ];
    }
    
    /*
     * relacionamos a tabla Estado con la tabla User por 'estado_id' de la tabla User.
     *  1 user ------> 1 estado
     *  1 estado -------> N user
     */
    public function getUsers()
    {
        // el 'rol_id' de la tabla User está relacionado directamente con el 'id' de la tabla Rol
        return $this->hasMany(User::className(), ['estado_id' => 'id']);
    }
}
