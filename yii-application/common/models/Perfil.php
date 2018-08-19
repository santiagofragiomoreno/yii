<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use common\models\Genero;

/**
 * This is the model class for table "perfil".
 *
 * @property string $id
 * @property string $user_id
 * @property string $nombre
 * @property string $apellido
 * @property string $fecha_nacimiento
 * @property int $genero_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Genero $genero
 */
class Perfil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perfil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'genero_id'], 'required'],
            [['user_id', 'genero_id'], 'integer'],
            [['nombre', 'apellido'], 'string'],
            
            //formateo correcto de la fecha
            [['fecha_nacimiento'], 'date', 'format'=>'Y-m-d'],
            [['fecha_nacimiento', 'created_at', 'updated_at'], 'safe'],
            
            //regla que solo permite los valores 1,2 devueltos por getGeneroLista()
            [['genero_id'],'in', 'range'=>array_keys($this->getGeneroLista())],
            [['genero_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genero::className(), 'targetAttribute' => ['genero_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'genero_id' => 'Genero ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            
            'generoNombre' => Yii::t('app', 'Genero'), 
            'userLink' => Yii::t('app', 'User'), 
            'perfilIdLink' => Yii::t('app', 'Perfil'),
        ];
    }
    
    /** * behaviors to control time stamp, 
        * don't forget to use statement for expression 
        * 
     * */
    public function behaviors() { 
        return [
            'timestamp' => [ 'class' => 'yii\behaviors\TimestampBehavior', 
                             'attributes' => [
                                             ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'], 
                                             ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'], ], 
                             'value' => new Expression('NOW()'),
                           ],
                ];
    }

    /**
     /**
     * metodo para relacionar la tabla Perfil con la tabla Genero
     * 
     * 1 perfil ------> 1 genero(mas/fem)
     * 
     * 1 genero ------> 1 perfiles
     *
     */
    public function getGenero()
    {
        return $this->hasOne(Genero::className(), ['id' => 'genero_id']);
    }
    
    /**
     * metodo para devolver el nombre del Genero que tiene un perfil
     *
     */
    
    public function getGeneroNombre()
    {
        
        return $this->genero->genero_nombre;
    }
    
    /**
     * metodo para devolver los dos tipos de genero y tenerlos en un array (para el desplegable del formulario)
     *
     * Lista de generos para Lista desplegable
     */
    
    public function getGeneroLista(){
        
        //creamos la variable local $opciones y le asignamos una instancia de Rol,
        //con todos los registros que son devueltos como un array.
        $opciones = Genero::find()->asArray()->all();
        
        //usamos el método ArrayHelper::map para listar los valores y nombres de rol.
        return ArrayHelper::map($opciones, 'id', 'genero_nombre');
    }
    
    /**
     /**
     * metodo para relacionar la tabla Perfil con la tabla Usuario
     *
     * 1 perfil ------> 1 Usuario
     *
     * 1 usuario ------> 1 perfil
     *
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * metodo para devolver el Username de un perfil
     */
    public function getUsername()
    {
        return $this->user->username;
    }
    
    /**
     * metodo para  retornar la id del registro de User
     *
     * Operador ternario para ver si a ese usuario se le a asignado un id,
     *
     */
    
    public function getUserId(){
        
        // nos devuelve el id del Usuario que tiene este user, y si no tiene...nos devuelve '-sin id-'
        return $this->user ? $this->user->id : '-ninguno-';
    }
    
    /** 
     * @getUserLink 
     * hacemos uso de las clases auxilires Html:: y Url::
     * método que crea links al usuario relacionado
     */
    public function getUserLink() 
    { 
        $url = Url::to(['user/view', 'id'=>$this->UserId]); 
        $opciones = []; 
        return Html::a($this->getUserName(), $url, $opciones); 
    }
    
    /** 
     * @getPerfilLink 
     * hacemos uso de las clases auxilires Html:: y Url::
     * método que crea links al id del perfil del usuario relacionado
     */
    public function getPerfilIdLink() 
    { 
        $url = Url::to(['perfil/update', 'id'=>$this->id]); 
        $opciones = []; 
        return Html::a($this->id, $url, $opciones); 
    }
    
}
