<?php

class form{
    private $form_id;
    private $html;
    private $method;
    private $data;
    private $hasmap=false;
    private $map_lat=0;
    private $map_lng=0;
    
    
    function __construct($title,$subtitle,$action,$method = 'POST',$size=12,$id='form') {
        $this->form_id = $id;
        $this->data = ($method=='POST')?$_POST:$_GET;
        $this->html = '
            <div class="row">
            <div class="col-lg-'.$size.'">
            <h1>'.$title.' <small>'.$subtitle.'</small></h1>
            </div>
            </div>
             <form id='.$id.' name='.$id.' role="form" action="'.$action.'" method="'.$method.'">
                 <input type="hidden" id="formKey" name="formKey" value="'.md5($_SERVER['REMOTE_ADDR'].date('d')).'">
           ';
        $this->method = $method;
    }
    
    function get_data($key){
        
        if(@$this->data['formKey']==md5($_SERVER['REMOTE_ADDR'].date('d'))){
            return $this->data[$key];            
        }
        return false;
    }
    
    public function is_sent(){
        if($this->get_data('formKey')==md5($_SERVER['REMOTE_ADDR'].date('d'))){
            return true;
        }else{
            return false;
        }
    }
    
    
    function textbox($name,$label,$value,$placeholder=''){
        $this->html .= '<div class="form-group">
                        <label>'.$label.'</label>
                        <input type="text" id="'.$name.'" name="'.$name.'" value="'.$value.'" class="form-control" placeholder="'.$placeholder.'">
                      </div>';
    }
    
    function textarea($name,$label,$value=''){
        $this->html .= '<div class="form-group">
                        <label>'.$label.'</label>
                        <textarea id="'.$name.'" name="'.$name.'" class="form-control">'.$value.'</textarea>
                      </div>';
    }
    
    function number($name,$label,$value,$min=false,$max=false,$placeholder=''){
        $this->html .= '<div class="form-group">
                        <label>'.$label.'</label>
                        <input type="number" id="'.$name.'" name="'.$name.'" value="'.$value.'" class="form-control" placeholder="'.$placeholder.'" ';
                        if($min) $this->html.= 'min="'.$min.'" ';
                        if($max) $this->html.= 'max="'.$max.'" ';
        $this->html.= ' >
                      </div>';
    }
    
    function select($name,$label,$options,$value){
        $this->html .= '<div class="form-group">
                        <label>'.$label.'</label> 
                        <select id="'.$name.'" name="'.$name.'">
                            ';
                       foreach($options as $k=>$v){
                           $this->html.= '<option value="'.$k.'" ';
                           if($k==$value) $this->html.='SELECTED';
                           $this->html.= '>'.$v.'</option>';
                       } 
                       
        $this->html .='</select> </div>';
    }
    
    function hidden($name,$value){
        $this->html .= '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'">';
    }
    
    function passwordbox($name,$label,$value,$placeholder=''){
        $this->html .= '<div class="form-group">
                        <label>'.$label.'</label>
                        <input type="password" id="'.$name.'" name="'.$name.'" value="'.$value.'" class="form-control" placeholder="'.$placeholder.'">
                      </div>';
    }
    
    function submitbutton($label){
         $this->html.= '<button type="submit" class="btn btn-default">'.$label.'</button>';
    }
    
    
    function map($name,$label,$lat,$lng){
        $this->map_lat = $lat;
        $this->map_lng = $lng;
        $this->hasmap = true;
        $this->html .= '<div class="form-group">
                        <label>'.$label.'</label>
                        <div id="current_position"></div>
                        <input id="lat" type="hidden" name="lat" value="0"><input id="lng" type="hidden" name="lng" value="0">
                            
                        <div id="map-canvas" style="width:100%;height:400px;"></div>
                        </div>';
    }
    
    private function mapcode($name,$lat='-25.292288',$lng='-57.564346'){
        $this->html .= '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>';
        $this->html .= " 
                <script>
// The following example creates a marker in Stockholm, Sweden
// using a DROP animation. Clicking on the marker will toggle
// the animation between a BOUNCE animation and no animation.

var home = new google.maps.LatLng($lat, $lng);
var marker;
var map;

function initialize() {
  var mapOptions = {
    zoom: 17,
    center: home
  };

  map = new google.maps.Map(document.getElementById('map-canvas'),
          mapOptions);

  marker = new google.maps.Marker({
    map:map,
    draggable:true,
    animation: google.maps.Animation.DROP,
    position: home
  });
  google.maps.event.addListener(marker, 'drag', toggleMove);
  toggleMove();
}

function toggleMove() {


  document.getElementById('current_position').innerHTML = 'Posicion Actual: '+marker.position;
  document.getElementById('".$this->form_id."').lat.value=marker.position.lat();
  document.getElementById('".$this->form_id."').lng.value=marker.position.lng();
  
  
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
            ";
        
    }
    
    
    
    
    
    function deploy(){
        $this->html.= '</form>';
        if($this->hasmap) $this->mapcode('map',$this->map_lat,$this->map_lng);
        return $this->html;
    }
    
    
    
    
    
    
    
}