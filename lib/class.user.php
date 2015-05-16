<?php
class user {
    
    
    
    
    public static function api_facebook_login($data){
        global $db;
        $sql = 'insert ignore into users(email,first_name,last_name,gender,fb_id,status)VALUES(:email,:first_name,:last_name,:gender,:fb_id,:status);';
        $gender = 0;
        if($data['gender']=='male')$gender=1;
        if($data['gender']=='female')$gender=2;
        $params = array(
            ':email' => $data['email'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':gender' => $gender,
            ':fb_id' => $data['fb_id'],
            ':status' => 1
        );
        $db->query($sql,$params,false);
        
        return array('1');   
    }
}