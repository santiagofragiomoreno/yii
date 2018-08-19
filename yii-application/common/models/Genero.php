<?php

namespace common\models;

use Yii;
use common\models\Perfil;

/**
 * This is the model class for table "genero".
 *
 * @property int $id
 * @property string $genero_nombre
 *
 * @property Perfil[] $perfils
 */
class Genero extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'genero';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['genero_nombre'], 'required'],
            [['genero_nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'genero_nombre' => 'Genero Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerfils()
    {
        return $this->hasMany(Perfil::className(), ['genero_id' => 'id']);
    }
}
