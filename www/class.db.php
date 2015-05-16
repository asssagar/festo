<?php

class db{
    private $conn;
    
    function __construct(){

       try{
           $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET, DB_USER, DB_PASS);
           $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       } catch (Exception $ex) {
           throw new Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
       }
            
        
    }
    
    function last_id(){
        return $this->conn->lastInsertId();
    }
    
    function clear($data){
        return $this->conn->quote($data);
    }
    
     function query($sql,$params=array(),$return=true){
        try{
            
           
            $s = $this->conn->prepare($sql);
           
              
            
            foreach($params as $pname=>$pvalue){
                $type = PDO::PARAM_STR;
                if(is_numeric($pvalue)) $type = PDO::PARAM_INT;
                
                
                $s->bindValue($pname,$pvalue,$type);
            }
            $s->execute();
            #echo 'Executed: '.$sql;
            if($return){
                $r = $s->fetchAll(PDO::FETCH_ASSOC);            
            }
            $s = null;

            if($return)return $r;
            return true;
        } catch (Exception $ex) {
            
            echo $sql.'<pre>';
            print_r($params);
            echo '</pre>';
            throw new Exception($ex->getMessage());
            
        }
        
    }
    
    static function close(){
        
    }
    
    
    
}