<?php

class venue{
    private $id;
    private $venue_record = array();    
    private $direct_fields = array('name','description','contact','address','zipcode','lat','lng');
    private $managed_fields = array('source','source_id', 'city','state','country');
    private $contact_fields = array('phone','twitter','facebook','email');
    
    function load($venue_id){
        global $db;
        $this->id = $venue_id;
        $r = $db->query('select * from venues where id = :id',array(':id'=>$this->id));
        if(count($r)>0){
            $this->venue_record = $r[0];
        }
        
        $this->venue_record = array_merge($this->venue_record, $this->contact_extract($this->venue_record['contact']));
        
    }
    
    private function contact_extract($contact){
        $c = array();
        $list = explode(',',$contact);
        foreach($list as $item){
            $r = explode(':',$item);
            if(count($r)>1)$c[trim($r[0])]=trim($r[1]);
        }
        return $c;
    }
    
    function get($key){
        if(in_array($key, $this->managed_fields)){
            $function_name = 'get_'.$key;
            $this->$function_name();
        }else{
            return @$this->venue_record[$key];
        }
    }
    
    function set($key,$value){
        if(in_array($key, $this->managed_fields)){
            $function_name = 'set_'.$key;
            $this->$function_name($value);
        }elseif(in_array($key, $this->contact_fields)){
            $this->set_contact_field($key, $value);
        }else{
            $this->venue_record[$key] = $value;
        }
    }
    
    private function set_contact_field($key,$value){
        if(in_array($key,$this->contact_fields)) $this->venue_record[$key] = $value;
    }
    
    
    
    function update(){
        $sql = 'update venues set ';
        $params = array();
        $c = '';
        $fields = array_merge($this->direct_fields,$this->managed_fields);
        foreach($fields as $field){
            $sql .= $c." $field = :$field";
            $c = ',';
            $params[':'.$field] = $this->venue_record[$field];
        }
        $comma = '';
        $contact = '';
        foreach($this->contact_fields as $contact_field){
            $contact.=$comma.$contact_field.':'. @$this->venue_record[$contact_field];
            $comma = ',';
        }
        $sql.= ' ,contact=:contact';
        $params[':contact'] = $contact;
        
        $sql .= ' WHERE id = :id';
        $params[':id'] = $this->id;
        global $db;
        $db->query($sql,$params,false);
        $this->load($this->id);
        return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Retrieves most recommended venues by rank
     * @global type $db
     * @param type $num_results
     * @return type
     */
    static function get_recommended($num_results=10,$page=1){
        global $db;
        $offset = $num_results * ($page-1);
        $sql = 'SELECT id, name, rank FROM venues ORDER BY rank DESC limit :offset, :num_results ;';
        $params = array(
          ':offset'     => $offset,
          ':num_results'  => $num_results  
        );
        $r = $db->query($sql,$params);
        return $r;
    }
    
    /**
     * Gets category list
     * @global type $db
     * @param type $num_results
     * @param type $page
     * @return type
     */
    static function get_categories($num_results=10,$page=1){
        global $db;
        $offset = $num_results * ($page-1);
        $sql = 'SELECT * FROM festo_categories limit :offset, :num_results ;';
        $params = array(
          ':offset'     => $offset,
          ':num_results'  => $num_results  
        );
        $r = $db->query($sql,$params);
        return $r;
    }
    
    
    
    
    static function get_venues($cat,$num_results=10,$page=1){
        global $db;
        $offset = $num_results * ($page-1);
        $sql = 'SELECT * FROM venues WHERE id IN (SELECT venue_id FROM join_venues_categories WHERE category_id = :category_id) limit :offset, :num_results;';
        $params = array(
          ':category_id'=>$cat,
          ':offset'     => $offset,
          ':num_results'  => $num_results
        );
        $r = $db->query($sql,$params);
        return $r;
    }
    
    /**
     * Retrieves the country id for the name provided, inserts a new record if needed
     * @global type $db
     * @param type $country_name
     * @return type
     */
    static function set_country($country_name){
        global $db;
        $sql = 'INSERT IGNORE INTO countries(country_name)VALUES(:country_name);';
        $params = array(
            ':country_name' => $country_name
        );
        $db->query($sql,$params,false);
        return self::get_country($country_name);
    }
    
    /**
     * Retrieves the id of a given country name
     * @global type $db
     * @param type $country_name
     * @return type
     */
    static function get_country($country_name){
        global $db;
        $sql = 'SELECT id FROM countries WHERE country_name = :country_name;';
        $params = array(
            ':country_name' => $country_name
        );
        $r = $db->query($sql,$params);
        return $r[0]['id'];
    }
    
    static function set_state($state_name,$country_id){
        global $db;
        $sql = 'INSERT IGNORE INTO states(state_name,country_id)VALUES(:state_name,:country_id);';
        $params = array(
            ':state_name' => $state_name,
            ':country_id' => $country_id
        );
        $db->query($sql,$params,false);
        return self::get_state($state_name,$country_id);
    }
    
    static function get_state($state_name,$country_id){
        global $db;
        $sql = 'SELECT id FROM states WHERE state_name = :state_name and country_id = :country_id;';
        $params = array(
            ':state_name'   => $state_name,
            ':country_id'   => $country_id
        );
        $r = $db->query($sql,$params);
        if(count($r)>0){
            return $r[0]['id'];
        }else{
            return false;
        }    
       
    }
    
    
    static function set_city($city_name,$country_id){
        global $db;
        $sql = 'INSERT IGNORE INTO cities(city_name,country_id)VALUES(:city_name,:country_id);';
        $params = array(
            ':city_name'    => $city_name,
            ':country_id'   => $country_id
        );
        
        $db->query($sql,$params,false);
        return self::get_city($city_name,$country_id);
        
    }
    
    static function get_city($city_name,$country_id){
        global $db;
        $sql = 'SELECT id FROM cities WHERE city_name = :city_name and country_id = :country_id;';
        $params = array(
            ':city_name'    => $city_name,
            ':country_id'   => $country_id
        );
        
        $r = $db->query($sql,$params);
        if(count($r)>0){
            return $r[0]['id'];
        }else{
            return false;
        }
    }
    
    
 
    
    
    
   
    
    
    static function add($source,$source_id,$name,$contact,$address,$zipcode,$city,$state,$country,$lat,$lng){
        global $db;
        $country = self::set_country($country);
        $city = self::set_city($city, $country);
        $state = self::set_state($state,$country);
        
        
        
        $sql = 'INSERT INTO venues(source,source_id,name,contact,address,zipcode,city,state,country,lat,lng,status) values (:source,:source_id,:name,:contact,:address,:zipcode,:city,:state,:country,:lat,:lng,:status); ';
        $params = array(
            ':source'       => $source,
            ':source_id'    => $source_id,
            ':name'         => $name,
            ':contact'      => $contact,
            ':address'      => $address,
            ':zipcode'      => $zipcode,
            ':city'         => $city,
            ':state'        => $state,
            ':country'      => $country,
            ':lat'          => $lat,
            ':lng'          => $lng,
            ':status'       => 1            
        );
       
    
       $db->query($sql,$params,false);
       return $db->last_id(); 
    }
    
    /**
     * Retrieves the correct image url for a venue or a default image in case of not having the venue's image
     * @param type $path
     * @param type $venue_id
     * @return type
     */
    static function get_image($path,$venue_id){
        $file = md5($venue_id).'.png';
        if(file_exists($path.$file) and is_file($path.$file)){
            return $path.$file;
        }else{
            return $path.'default.png';
        }
    }
    
    static function parse_contact_data($contact_data){
        $list = explode(',',$contact_data); 
        $data = array();
        $return = array();
        foreach($list as $i){
            if(trim($i)=='')continue;
            $aux = explode(':',$i);
            if(count($aux)!=2) var_dump($aux);
            $data[$aux[0]] = $aux[1];
        }
        
        foreach($data as $key=>$value){
            switch($key){
                case 'phone':
                    $return['Telefono']=self::phone_link($data['phone'],$data['phone']);
                    break;
                case 'twitter': 
                    $return['Twitter']=self::twitter_link($value);
                    break;
                case 'facebook':
                    $return['Facebook']=self::facebook_link($value, $data['facebook']);
                    break;


            }   
        }
        return $return;
        
        
    }
    
    static function twitter_link($user){
        return '<a target="_blank" href="https://twitter.com/'.$user.'">@'.$user.'</a>';
    }
    
    static function facebook_link($user,$name){
        return '<a target="_blank" href="https://www.facebook.com/'.$user.'">'.$name.'</a>';
    }
    
    static function phone_link($phone,$formatted){        
        return '<a target="_blank" href="tel:'.$phone.'">'.$formatted.'</a>';
    }
    
    static function delete($venue_id){
        global $db;
        $sql = 'delete from venues where id = :venue_id;';
        $params = array(':venue_id'=>$venue_id);
        $db->query($sql,$params,false);
        $sql = 'delete from join_venues_categories where venue_id = :venue_id;';
        $db->query($sql,$params,false);
        
    }
    
}