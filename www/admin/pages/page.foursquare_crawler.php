<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Foursquare data crawler</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
<?php if(@$_POST['action']=='crawl'){ 
    global $foursquare_sections;
    foreach($foursquare_sections as $sections){
            $f = new foursquareapi(FSQR_CLIENT_ID,FSQR_CLIENT_SECRET);
            $list = $f->venues_explore($_POST['lat'], $_POST['lng'],$sections);
            #echo '<pre>';
            #print_r($list);
            #echo '</pre>';
            #die();
            foreach($list as $venue){

                $v = $f->get_venue_data($venue);

                    $venue_id = venue::add(
                                            1, 
                                            $v['id'], 
                                            $v['name'], 
                                            $v['contact'], 
                                            $v['address'], 
                                            $v['zipcode'], 
                                            $v['city'], 
                                            $v['state'], 
                                            $v['country'], 
                                            $v['lat'], 
                                            $v['lng']
                                );
                    echo '<br> - '.$v['name'];
                    foreach($v['categories'] as $cat){
                        $cat_id = venuecategory::set_category($cat);
                        venuecategory::assign($venue_id, $cat_id);
                    }



            }
    }
            
    
    
   
    
            #var_dump($v);
           
            //
    
   
    
    
    ?>            
   
         
            
<?php }else{ ?>            
<div class="row">
    <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div id='current_position'></div>
                            <form id='dataform' name='dataform' method="post" action="?p=foursquare_crawler">
                                <input id='lat' type="hidden" name='lat' value='0'><input id='lng' type="hidden" name='lng' value='0'>
                                <input type='hidden' name='action' value='crawl'>
                               
                                <input type='submit' value='Leer datos!'>
                            </form>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="map-canvas" style="width:100%;height:800px;"></div>
                        </div>
                    </div>
    </div>
</div>



    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script>
// The following example creates a marker in Stockholm, Sweden
// using a DROP animation. Clicking on the marker will toggle
// the animation between a BOUNCE animation and no animation.

var home = new google.maps.LatLng(-25.292288, -57.564346);
var marker;
var map;

function initialize() {
  var mapOptions = {
    zoom: 13,
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
  document.getElementById('dataform').lat.value=marker.position.lat();
  document.getElementById('dataform').lng.value=marker.position.lng();
  
  
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
<?php
}
?>