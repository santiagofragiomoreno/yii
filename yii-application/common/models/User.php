<?php

namespace common\models;



use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use common\models\Rol;
use common\models\Estado;
use common\models\TipoUsuario;
use common\models\Perfil;
use yii\helpers\Html;
use yii\helpers\Url;
/*use yii\helpers\Security;

/**
* User model
* @property integer $id
* @property string $username
* @property string $password_hash
* @property string $password_reset_token
* @property string $email
* @property string $auth_key
* @property integer $rol_id
* @property integer $estado_id
* @property integer $tipo_usuario_id
* @property integer $created_at
* @property integer $updated_at
* @property string $password write-only password
*/

class User extends ActiveRecord implements IdentityInterface

{
    
    const ESTADO_ACTIVO = 1;
    
    
    
    public static function tableName()
    
    {
        
        return 'user';
        
    }
    
    
    
    /**    
    * behaviors
    * METODO COMPORTAMIENTOS
    * Le indicamos al modelo como tiene que comportarse cuando
    * ocurren ciertas cosas:
    * - cuando identifiquemos el comportamiento TIMESTAMP le decimos
    *   que clase debe usar.despues la pasamos como atributos los eventos que aectarán
    *   EVENT_BEFORE_INSERT y EVENT_BEFORE_UPDATE.   
    */
    
    public function behaviors()
    
    {
        
        return [
            
            'timestamp' => [
                
                'class' => 'yii\behaviors\TimestampBehavior',
                
                'attributes' => [
                    
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                    
                ],
                
                'value' => new Expression('NOW()'),
                
            ],
            
        ];
        
    }
    
    
    
    /**    
    * reglas de validación  
    */
    
    public function rules()
    
    {
        
        return [
            
           /*ATRIBUTO-----VALIDADOR---PARAMETROS Y CONDICIONES*/ 
            ['estado_id', 'default', 'value' => self::ESTADO_ACTIVO],
            
            //regla que solo permite los valores 1,2 devueltos por getEstadoLista()
            [['estado_id'],'in', 'range'=>array_keys($this->getEstadoLista())],
            
            ['rol_id', 'default', 'value' => 1],
            
            //regla que solo permite los valores 1,2 devueltos por getRolLista()
            [['rol_id'],'in', 'range'=>array_keys($this->getRolLista())],
            
            //regla que solo permite los valores 1,2 devueltos por getTipoUsuarioLista()
            [['tipo_usuario_id'],'in', 'range'=>array_keys($this->getTipoUsuarioLista())], 
            
            ['tipo_usuario_id', 'default', 'value' => 1],
            
            ['username', 'filter', 'filter' => 'trim'],
            
            ['username', 'required'],
            
            ['username', 'unique'],
            
            ['username', 'string', 'min' => 2, 'max' => 255],
            
            ['email', 'filter', 'filter' => 'trim'],
            
            ['email', 'required'],
            
            ['email', 'email'],
            
            ['email', 'unique'],
            
        ];
        
    }
    
    
    
    /* Las etiquetas de los atributos de su modelo */
    
    public function attributeLabels()
    
    {
        
        return [
            
            /* Sus otras etiquetas de atributo */
            'rolNombre' => Yii::t('app', 'Rol'), 
            'estadoNombre' => Yii::t('app', 'Estado'), 
            'perfilId' => Yii::t('app', 'Perfil'), 
            'perfilLink' => Yii::t('app', 'Perfil'), 
            'userLink' => Yii::t('app', 'User'), 
            'username' => Yii::t('app', 'User'), 
            'tipoUsuarioNombre' => Yii::t('app', 'Tipo Usuario'), 
            'tipoUsuarioId' => Yii::t('app', 'Tipo Usuario'), 
            'userIdLink' => Yii::t('app', 'ID'),
        ];
        
    }   
    
    /**    
    * @findIdentity    
    */
    
    public static function findIdentity($id)
    
    {
        
        return static::findOne(['id' => $id, 'estado_id' => self::ESTADO_ACTIVO]);
        
    }
        
    /**    
    * @inheritdoc    
    */
    
    public static function findIdentityByAccessToken($token, $type = null)
    
    {
        
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        
    }
       
    /**    
    * Encuentra usuario por username    
    * y cuyo 'estado_id' sea ESTADO_ACTIVO (1)    
    * @return static|null
    */
    
    public static function findByUsername($username)
    
    {
        
        return static::findOne(['username' => $username, 'estado_id' => self::ESTADO_ACTIVO]);
        
    }
        
    /**
    * Encuentra usuario por clave de restablecimiento de password
    * @param string $token clave de restablecimiento de passwor
    * @return static|null
    */
    
    public static function findByPasswordResetToken($token)
    
    {
        
        if (!static::isPasswordResetTokenValid($token)) {
            
            return null;
            
        }
        
        return static::findOne([
            
            'password_reset_token' => $token,
            
            'estado_id' => self::ESTADO_ACTIVO,
            
        ]);
        
    }    
    
    /**
    * Determina si la clave de restablecimiento de password es válida
    * @param string $token clave de restablecimiento de password
    * @return boolean
    */
    
    public static function isPasswordResetTokenValid($token)
    
    {
        
        if (empty($token)) {
            
            return false;
            
        }
        
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        
        $parts = explode('_', $token);
        
        $timestamp = (int) end($parts);
        
        return $timestamp + $expire >= time();
        
    }
        
    /** 
    * @getId
    * funcion que nos devuelve el Id que tiene un usuario en la tabla USER   
    */
    
    public function getId()
    
    {
        
        return $this->getPrimaryKey();
        
    }
       
    /**    
    * @getAuthKey
    * funcion que nos devuelve el campo auth_key que tiene un usuario en la tabla USER   
    */
    
    public function getAuthKey()
    
    {
        
        return $this->auth_key;
        
    }    
    
    /**  
    * @validateAuthKey
    * validamos el campo devuelto por la funcion de arriba    
    */
    
    public function validateAuthKey($authKey)
    
    {
        
        return $this->getAuthKey() === $authKey;
        
    }
        
    /**   
    * Valida password    
    *    
    * @param string $password password a validar    
    * @return boolean si la password provista es válida para el usuario actual    
    */
    
    public function validatePassword($password)
    
    {
        
        return Yii::$app->security->validatePassword($password, $this->password_hash);
        
    }
        
    /**    
    * Genera hash de password a partir de password y la establece en el modelo   
    *    
    * @param string $password   
    */
    
    public function setPassword($password)
    
    {
        
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        
    }
        
    /**  
    * Genera clave de autenticación "recuerdame"   
    */
    
    public function generateAuthKey()
    
    {
        
        $this->auth_key = Yii::$app->security->generateRandomString();
        
    }
        
    /**    
    * Genera nueva clave de restablecimiento de password    
    * dividida en dos líneas para evitar ajuste de línea    
    */
    
    public function generatePasswordResetToken()
    
    {
        
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        
    }
      
    /**    
    * Remueve clave de restablecimiento de password   
    */
    
    public function removePasswordResetToken()
    
    {
        
        $this->password_reset_token = null;
        
    }
    
    /**
     * metodo para relacionar la tabla Perfil con la tabla Usuario
     * 
     * 1 usuario ------> 1 perfil
     * 
     * 1  perfil ------> 1 usuario
     */
    
    public function getPerfil(){
        
        // el 'user_id' de la tabla Perfil está relacionado directamente con el 'id' de la tabla User
        return $this->hasOne(Perfil::classname(), ['user_id' => 'id']);
    }
    
    /**
     * metodo para relacionar la tabla Rol con la tabla Usuario
     *
     * 1 usuario ------> 1 rol
     *
     * 1 rol ------> N usuarios
     */
    
    public function getRol(){
        
        // el 'user_id' de la tabla Perfil está relacionado directamente con el 'id' de la tabla User
        return $this->hasOne(Rol::classname(), ['id' => 'rol_id']);
    }
    
    /**
     * metodo para devolver el nombre del Rol que tiene un usuario
     *
     * Operador ternario para ver si a ese usuario se le a asignado un rol,
     * y si es así que nos devuelva el nombre del rol que tiene o si no la cadena -sin rol-
     */
    
    public function getRolNombre(){
        
        // no devuelve el nombre del rol que tiene este user, y si no tiene...nos devuelve '-sin rol-'
        return $this->rol ? $this->rol->rol_nombre : '-sin rol-';
    }
    
    /**
     * metodo para devolver los dos tipos de roles y tenerlos en un array (para el desplegable del formulario)
     *
     * Lista de roles para Lista desplegable
     */
    
    public function getRolLista(){
        
        //creamos la variable local $opciones y le asignamos una instancia de Rol, 
        //con todos los registros que son devueltos como un array.
        $opciones = Rol::find()->asArray()->all();
        
        //usamos el método ArrayHelper::map para listar los valores y nombres de rol.
        return ArrayHelper::map($opciones, 'id', 'rol_nombre');
    }
    
    /**
     * metodo para relacionar la tabla Estado con la tabla Usuario
     *
     * 1 usuario ------> 1 estado
     *
     * 1 estado ------> N usuarios
     */
    
    public function getEstado(){
        
        // el 'estado_id' de la tabla User está relacionado directamente con el 'id' de la tabla Estado
        return $this->hasOne(Estado::classname(), ['id' => 'estado_id']);
    }
    
    /**
     * metodo para devolver el nombre del Estado que tiene un usuario
     *
     * Operador ternario para ver si a ese usuario se le a asignado un estado,
     * y si es así que nos devuelva el nombre del estado que tiene o si no la cadena -sin estado-
     */
    
    public function getEstadoNombre(){
        
        // no devuelve el nombre del estado que tiene este user, y si no tiene...nos devuelve '-sin estado-'
        return $this->estado ? $this->estado->estado_nombre : '-sin estado-';
    }
    
    /**
     * metodo para devolver los tipos de estado y tenerlos en un array (para el desplegable del formulario)
     *
     * Lista de estados para Lista desplegable
     */
    
    public function getEstadoLista(){
        
        //creamos la variable local $opciones y le asignamos una instancia de estado,
        //con todos los registros que son devueltos como un array.
        $opciones = Estado::find()->asArray()->all();
        
        //usamos el método ArrayHelper::map para listar los valores y nombres de estado.
        return ArrayHelper::map($opciones, 'id', 'estado_nombre');
    }
    
    /**
     * metodo para relacionar la tabla TipoUsuario con la tabla Usuario
     *
     * 1 usuario ------> 1 tipo usuario
     *
     * 1 tipo usuario ------> N usuarios
     */
    
    public function getTipoUsuario(){
        
        // el 'tipo_usuario_id' de la tabla User está relacionado directamente con el 'id' de la tabla TipoUsuario
        return $this->hasOne(TipoUsuario::classname(), ['id' => 'tipo_usuario_id']);
    }
    
    /**
     * metodo para devolver el nombre del TipoUsuario que tiene un usuario
     *
     * Operador ternario para ver si a ese usuario se le a asignado un tipoUsuario,
     * y si es así que nos devuelva el nombre del estado que tiene o si no la cadena -sin tipo usuario-
     */
    
    public function getTipoUsuarioNombre(){
        
        // no devuelve el nombre del TipoUsuario que tiene este user, y si no tiene...nos devuelve '-sin tipo usuario-'
        return $this->tipoUsuario ? $this->tipoUsuario->tipo_usuario_nombre : '-sin tipo usuario-';
    }
    
    /**
     * metodo para devolver los tipos de usuario y tenerlos en un array (para el desplegable del formulario)
     *
     * Lista de estados para Lista desplegable
     */
    
    public function getTipoUsuarioLista(){
        
        //creamos la variable local $opciones y le asignamos una instancia de TipoUsuario,
        //con todos los registros que son devueltos como un array.
        $opciones = TipoUsuario::find()->asArray()->all();
        
        //usamos el método ArrayHelper::map para listar los valores y nombres de tipoUsuario.
        return ArrayHelper::map($opciones, 'id', 'tipo_usuario_nombre');
    }
    
    /**
     * metodo para  retornar la id del registro del TipoUsuario
     *
     * Operador ternario para ver si a ese usuario se le a asignado un tipoUsuarioid,
     * 
     */
    
    public function getTipoUsuarioId(){
        
        // no devuelve el nombre del TipoUsuario que tiene este user, y si no tiene...nos devuelve '-sin tipo usuario-'
        return $this->tipoUsuario ? $this->tipoUsuario->id : '-ninguno-';
    }
    
    /**
     * metodo para  retornar la id del registro del Perfil
     *
     * Operador ternario para ver si a ese usuario se le a asignado un Perfil,
     *
     */
    
    public function getPerfilId(){
        
        // no devuelve el nombre del Perfil que tiene este user, y si no tiene...nos devuelve '-ninguno-'
        return $this->perfil ? $this->perfil->id : '-ninguno-';
    }
    
    /** 
     * @getPerfilLink 
     * 
     */
    public function getPerfilLink() { 
        $url = Url::to(['perfil/view', 'id'=>$this->perfilId]); 
        $opciones = []; 
        return Html::a($this->perfil ? 'perfil' : 'ninguno', $url, $opciones); 
    }
    
    /** 
     * get user id Link 
     * 
     */
    public function getUserIdLink() 
    { 
        $url = Url::to(['user/update', 'id'=>$this->id]); 
        $opciones = []; 
        return Html::a($this->id, $url, $opciones); 
    }
    
    /** 
     * @get User Link 
     *
     */
    public function getUserLink() { 
        
        $url = Url::to(['user/view', 'id'=>$this->id]); 
        $opciones = []; 
        return Html::a($this->username, $url, $opciones); 
    }
    
    
} 
