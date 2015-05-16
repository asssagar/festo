<?php

class venue_search{
    private $keywords = array();
    private $categories = array();
    private $lat,$lng;
    private $sql_data,$sql_count;
    private $sql_data_params = array();
    private $sql_count_params = array();
    private $ready = false;
    private $num_results = 10;
    private $num_pages = 0;
    private $page_number = 1;
    private $order_type = 'rank';
    private $radius = false;
    private $result_count = 0;
    
  
    
    
    
    
    public function __construct(){
        global $db;
        $this->db = $db;
        
    }
    
    public function num_results($s=0){
        if($s>0)$this->num_results = $s;
        return $this->num_results;
    }
    
    function parse_keyword_string($string,$delimiter='|'){
        $list = explode($delimiter,$string);
        foreach($list as $k) $this->add_keyword($k);
    }
    
        
    function add_keyword($kw){
        if(!in_array($kw, $this->keywords))$this->keywords[] = $kw;
    }
    
    function set_location($lat,$lng){
        $this->lat = $lat;
        $this->lng = $lng;
    }
    
    function set_radius($kilometers){
        if(!is_numeric($kilometers))return false;
        $this->radius = $kilometers;
    }
    
    function set_category($category_id){
        if(!in_array($category_id,$this->categories))$this->categories[] = $category_id;        
    }
    
    function clear_categories(){
        $this->categories = array();
    }
    
    function page($page_number=0){
        if($page_number>0 and is_numeric($page_number)) $this->page_number = $page_number;
        return $this->page_number;
    }
    
    function order($by){
        $this->order_type = $by;
    }
    
    function get_num_pages(){
        return $this->num_pages;
    }
    
    
    public function get_list(){
        global $db;
        if(!$this->ready)$this->build_query();     
        
        $r = $db->query($this->sql_data,$this->sql_data_params);     
        
        return $r;
    }
    
    public function get_result_count(){
         global $db;
        if(!$this->ready)$this->build_query();     
        
        $r = $db->query($this->sql_count,$this->sql_count_params);     
        $this->result_count =  $r[0]['c'];
        $this->num_pages = ceil($this->result_count/$this->num_results);
        return $this->result_count;
    }
    
    private function build_query(){
        global $db;
        $this->sql_count = 'select count(*) as c ';
        $this->sql_data = 'SELECT v.id, v.name,v.description, v.address,v.contact, c.city_name, v.lat, v.lng';
        
         if($this->lat and $this->lng){
            $this->sql_data .= ', geo_distance(v.lat,v.lng,:lat,:lng) as distance ';
            $this->add_param(':lat', $this->lat);
            $this->add_param(':lng', $this->lng);
        }else{
            $this->sql_data .= ', NULL as distance ';
        }
                
        $this->sql_data .= ' FROM venues v, cities c ';
        $this->sql_count .= ' FROM venues v, cities c ';
        
        if(count($this->categories)>0){
            #$this->sql_data .= ', join_venues_categories jvc ';
            #$this->sql_count .= ', join_venues_categories jvc ';
        }
        
        $this->sql_data .= ' WHERE v.city = c.id  ';
        $this->sql_count .= ' WHERE v.city = c.id  ';
        
        if(count($this->categories)>0){
            $festo_category = $this->categories[0];
            $this->sql_data .= ' AND v.festo_category = '.$festo_category;
            $this->sql_count .= ' AND v.festo_category = '.$festo_category;
        }
        
        foreach($this->keywords as $kindex => $keyword){
            $keyword = $db->clear('%'.$keyword.'%');
            $this->sql_data .= " AND v.name LIKE $keyword ";
            $this->sql_count .= " AND v.name LIKE $keyword  ";
            
            #$this->add_param(':keyword_'.$kindex,$keyword);
            
        }
        
        
        if($this->lat and $this->lng and $this->radius){
            $this->sql_data .= ' HAVING distance <= '.$this->radius;
            $this->sql_count .= ' AND geo_distance(v.lat,v.lng,:lat,:lng) <= '.$this->radius;
        }
        #Order by 
        switch($this->order_type){
            case 'name':
                $this->sql_data.= ' ORDER BY v.name ASC ';
                break;
            case 'distance':
                $this->sql_data.= ' ORDER BY distance ASC ';
                break;
            default:
                $this->sql_data.= ' ORDER BY v.rank DESC ';
        }
        
         #Limits
        
        $offset = $this->num_results * ($this->page_number-1 );
        $this->sql_data .= ' LIMIT :offset, :limit';
        $this->add_dataonly_param(':offset',$offset);
        $this->add_dataonly_param(':limit', $this->num_results);
           
        
        $this->ready = true;
        
    }
    
  
    
    private function add_param($key,$value){
        $this->sql_count_params[$key]=$value;
        $this->sql_data_params[$key]=$value;
    }
    
    private function add_dataonly_param($key,$value){
        $this->sql_data_params[$key]=$value;
    }
    
    
    
}
