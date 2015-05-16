<?php

class venuecategory{
    static function set_category($category_name){
         global $db;
        $sql = 'INSERT IGNORE INTO venue_categories(category_name)VALUES(:category_name);';
        $params = array(
            ':category_name' => $category_name
        );
        $db->query($sql,$params,false);
        return self::get_category($category_name);
        
    }
    
    static function get_category($category_name){
       global $db; 
        $sql = 'SELECT id FROM venue_categories WHERE category_name = :category_name;';
        $params = array(
            ':category_name' => $category_name
        );
        $r = $db->query($sql,$params,true);
        
        if(count($r)>0){
            return $r[0]['id'];
        }else{
            return false;
        }
    }
    
    static function assign($venue_id,$category_id){
      
        global $db;
        $sql = 'INSERT IGNORE INTO join_venues_categories(venue_id,category_id) VALUES (:venue_id,:category_id);';
        $params = array(
            ':venue_id' => $venue_id,
            ':category_id' => $category_id
        );
        $db->query($sql,$params,false);
        
    }
    
    function api_set_festo_category($data){
        global $db;
        $sql = 'update venue_categories set festo_category = :festo_category where id = :venue_category';
        $params = array(
            ':festo_category' => $data['festo_category'],
            ':venue_category' => $data['venue_category']
        );
        $db->query($sql,$params,false);
        $sql = 'update venues v, join_venues_categories jvc set v.festo_category = :festo_category where v.id = jvc.venue_id and jvc.category_id = :venue_category;';
        $db->query($sql,$params,false);
        return array('1');          
    }
}