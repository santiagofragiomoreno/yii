<?php

namespace common\models;

use yii;
/* añadimos los modelos que vayamos a usar */

use common\models\Rol;
use common\models\Estado;
use common\models\TipoUsuario;
use common\models\User;


// CREAMOS LA CLASE VALORHELPERS

class ValorHelpers{
    
    /*
     * metodo que nos devuelve true o false
     * en funcion de si $rol_nombre (admin/usuario)
     * coincide con el del usuario actual
     *
     */
    public static function rolCoincide($rol_nombre){
        
        $userTieneRolNombre = Yii::$app->user->identity->rol->rol_nombre;
        return  $userTieneRolNombre == $rol_nombre ? true : false;
        
        //sería equivalente
        /*if($userTieneRolNombre != null){
            if($rol_nombre != null){
                if( $userTieneRolNombre == $rol_nombre){
                    return true;
                }
            }
        }
        else{
            return false;
        }
        */  
    }
    
    
    /*  metodo que nos devuelve el valor del campo rol
     *  de un usario en concreto
     * 
     */
    public static function getUsersRolValor($userId=null) { 
        
        // en caso de que un usario ya ha iniciado sesion (por que ya sabemos si es admin/usuario)
        if ($userId == null){ 
            
            $usersRolValor = Yii::$app->user->identity->rol->rol_valor; //userRolValor valdra....20 o 10...
            return isset($usersRolValor) ? $usersRolValor : false; 
        
        } 
        else { 
            
            //si no ha iniciado sesion....entonces si que necesitamos el id del usuario para poder
            //hacer luego la busqueda de $rol_valor
            $user = User::findOne($userId); 
            $usersRolValor = $user->rol->rol_valor; 
            
            return isset($usersRolValor) ? $usersRolValor : false; 
        } 
    }
    
    /*  metodo que nos devuelve el valor del campo rol
     *  a partir del nombre del rol ($rol_nombre)
     *
     */
    public static function getRolValor($rol_nombre) { 
        
        $rol = Rol::find('rol_valor') 
          ->where(['rol_nombre' => $rol_nombre]) 
          ->one(); 
        
        return isset($rol->rol_valor) ? $rol->rol_valor : false; 
    
    }
    
    /*  metodo que nos devuelve true o false
     *  dependiendo si el $rol_nombre es corrcto o no
     *
     */
    public static function esRolNombreValido($rol_nombre) { 
        
        $rol = Rol::find('rol_nombre') 
          ->where(['rol_nombre' => $rol_nombre]) 
          ->one(); 
        
        return isset($rol->rol_nombre) ? true : false; 
    }
    
    /*  metodo que nos devuelve true o false
     *  dependiendo del estado en que este el usuario (activo o pendiente)
     *
     */
    public static function estadoCoincide($estado_nombre) { 
        
        $userTieneEstadoName = Yii::$app->user->identity->estado->estado_nombre;
        
        return $userTieneEstadoName == $estado_nombre ? true : false; 
    
    }
    
    /*  metodo que nos devuelve true o false
     *  estamos ingresando el nombre de estado 
     *  para devolver la id a través de ActiveRecord.
     *
     */
    public static function getEstadoId($estado_nombre) { 
        
        $estado = Estado::find('id') 
          ->where(['estado_nombre' => $estado_nombre]) 
          ->one(); 
        
        return isset($estado->id) ? $estado->id : false; 
    
    }
    
    /*
     * simplemente devuelve verdadero o falso si el tipoUsuario del usuario 
     * coincide con el especificado en la firma.
     */
    public static function tipoUsuarioCoincide($tipo_usuario_nombre) { 
        
        $userTieneTipoUsurioName = Yii::$app->user->identity->tipoUsuario->tipo_usuario_nombre; 
        
        return $userTieneTipoUsurioName == $tipo_usuario_nombre ? true : false; 
    
    } 
}