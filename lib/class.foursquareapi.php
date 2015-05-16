<?php
class foursquareapi{
    private $client_id, $client_secret,$client_locale;
    private $base_url = 'https://api.foursquare.com/v2/';
    
    
    function __construct($client_id,$client_secret,$client_locale='es') {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->client_locale = $client_locale;
    }
    
    /**
     * Explores for venues around a point
     * @param type $lat
     * @param type $lng
     * @return type
     */
    function venues_explore($lat,$lng,$section=false){
        $url = 'venues/explore?ll='.$lat.','.$lng;
        if($section) $url = $url.'&section='.$section;
        $url = $this->base_url.$url;
        $request =  $this->request($url);
        $venues = array();
        foreach($request->response->groups as $groups){
            
                foreach($groups->items as $items){
                    
                        $venues[] = $items->venue;
                    
                }
            
        }
        return $venues;
    }
    
    /**
     * Searches for venues around a point
     * @param type $lat
     * @param type $lng
     * @return type
     */
    function venues_search($lat,$lng,$section=false){
        $url = 'venues/search?ll='.$lat.','.$lng;
        $url = $this->base_url.$url;
        $request =  $this->request($url);
        return $request->response->venues;
    }
    
    
    function get_venue_data($venue){
        $data = array();
        $data['id']     = $venue->id;
        $data['name']   = $venue->name;
        $data['contact']= self::get_group_data($venue->contact);
        @$data['address']= $venue->location->address;
        @$data['zipcode']= $venue->location->zipcode;
        @$data['city']= $venue->location->city;
        @$data['state']= $venue->location->state;
        $data['country']= $venue->location->country;
        $data['lat']    = $venue->location->lat;
        $data['lng']    = $venue->location->lng;
        $data['categories'] = $this->get_venue_categories($venue->categories);
        
        return $data;
    }
    
    function get_group_data($obj){
        $r = '';
        $comma = '';
        foreach($obj as $k => $v){
            $r.= $comma."$k:$v";
            $comma = ' ,';
        }
        return $r;
    }
    
    function get_venue_categories($categories){
        $r = array();
        foreach($categories as $cat){
            $r[] = $cat->name;
        }
        return $r;
    }
    
    
    function save_venue($fsqr_id,$name,$contact,$address,$zipcode,$city,$state,$country,$lat,$lng){
        return venue::add(1, $fsqr_id, $name, $contact, $address, $zipcode, $city, $state, $country, $lat, $lng);
    }
    
    
    
    
    

    private function request($url,$method='GET'){
        $url.='&client_id='.$this->client_id;
        $url.='&client_secret='.$this->client_secret;
        $url.='&locale='.$this->client_locale;
        $url.='&v='.date('Ymd');
        
        
        $curl = curl_init($url); 
        curl_setopt($curl, CURLOPT_FAILONERROR, true); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   
        $result = curl_exec($curl); 
        $result = json_decode($result);
        return $result;
    }
}


?>