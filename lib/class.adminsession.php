<?php
class adminsession{
    
    
    
    static function start() {
        session_name('festoAdmin');
        session_start();
        
    }
    
    static function admin_check(){
        if(!self::admin_logged()){
           die('<meta http-equiv="refresh" content="0; url=?p=login" />');
        }
    }
    
    static function admin_logged(){
        
        if(@$_SESSION['festo_admin_logged']==1){
           return true;
        }else{
           return false;
        }
    }
    
    static function admin_login($user,$pass){
        global $db;
        $sql = 'select * from admin_users where user_name=:user and pass_word = :pass and status = 1;';
        $params = array(
            ':user'     => $user,
            ':pass'     => $pass
        );
        $l = $db->query($sql,$params);
        if(count($l)>0){
            $_SESSION['festo_admin_logged']=1;
            $_SESSION['festo_admin_user'] = $l[0];
            return true;
        }else{
            return false;
        }
    }
    
    static function admin_logout(){
        unset($_SESSION['festo_admin_logged'],$_SESSION['festo_admin_user'],$_SESSION['admin_login_try']);
        session_destroy();
        die('<meta http-equiv="refresh" content="0; url=?p=login" />');
    }
    
    
    
}