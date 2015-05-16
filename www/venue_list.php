<?php
include('../lib/init.php');

 $s = new venue_search();
 $paging_base_url = '';
 $glue = '?';
 if(@$_GET['lat']&@$_GET['lng']){
     $s->set_location($_GET['lat'],$_GET['lng']);
     $paging_base_url.= $glue.'lat='.$_GET['lat'].'&lng='.$_GET['lng'];
     $glue = '&';
 }
 if(@$_GET['radio']){
     $s->set_radius ($_GET['radio']);
     $paging_base_url.=$glue.'radio='.$_GET['radio'];
     $glue = '&';
 }
 if(@$_GET['category_id']){
     $s->set_category ($_GET['category_id']);
     $paging_base_url.=$glue.'category_id='.$_GET['category_id'];
     $glue = '&';
 }
 
 if(@$_GET['q']){
     $s->add_keyword ($_GET['q']);
     $paging_base_url.=$glue.'q='.$_GET['q'];
     $glue = '&';
 }
 
 
 if(@$_GET['p']){
     $s->page($_GET['p']);
 }
 
 $count = $s->get_result_count();
 $list = $s->get_list();




?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Sample-Page</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/style.css">

  <!-- GOOGLE FONTS -->
  <link href='http://fonts.googleapis.com/css?family=Raleway:400,700,600,800%7COpen+Sans:400italic,400,600,700' rel='stylesheet' type='text/css'>


  <!--[if IE 9]>
    <script src="js/media.match.min.js"></script>
  <![endif]-->



<script type="text/javascript" src="js/jquery.js"></script>
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

	



	
	


<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">

var map;
var resultLocations = new Array(); 
var Markers = new Array();
var InfoWindows = new Array();
var InfoTexts = new Array();

  	function setPin(lat,lng,text,pos){
  		var infowindow = new google.maps.InfoWindow();
  		marker = new google.maps.Marker({
 	        position: new google.maps.LatLng(lat,lng),
 	        map: map
 	    });
  		
		
  		google.maps.event.addListener(marker, 'mouseover', (function(marker, pos) {
	        return function() {
	          infowindow.setContent(text);
	          infowindow.open(map, marker);
	        }
	      })(marker, pos));

  		google.maps.event.addListener(marker, 'mouseout', (function(marker, pos) {
	        return function() {
	          infowindow.close(map, marker);
	        }
	      })(marker, pos));
	      
	      
  		Markers[pos] = marker;
	    InfoWindows[pos] = infowindow;
	    InfoTexts[pos]	= text;
	}

	function showInfo(index){
		InfoWindows[index].setContent(InfoTexts[index]);
		InfoWindows[index].open(map, Markers[index]);
	}

	function hideInfo(index){
		InfoWindows[index].close(map, Markers[index]);
	}


  function initialize() {
	    var latlng = new google.maps.LatLng(38,8948, -77,033);
	    var myOptions = {
	      zoom: 12,
	      center: latlng,
	      mapTypeId: google.maps.MapTypeId.ROADMAP
	    };
	    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		var pinText = '';
                <?php
                $i = 0;
                foreach($list as $venue){
                    $i++;
                
                ?>
	    		
		  	  	resultLocations[<?php echo $i; ?>] = new google.maps.LatLng('<?php echo $venue['lat'];?>','<?php echo $venue['lng'];?>');
				pinText = "<?php echo $venue['name'];?>, <?php echo $venue['address'];?><br/><?php echo $venue['city_name'];?>";
		  	  	
		  	   	setPin('<?php echo $venue['lat'];?>','<?php echo $venue['lng'];?>',pinText,<?php echo $i;?>);
		  	   	if(1==1){
		  	   		map.setCenter(resultLocations[<?php echo $i;?>]);
			  	}	  	   	
                <?php } ?>
				
		  	  	
					  	   	
		  	  
		
		
    }


  		$(window).load(function()
		    {
		        var $scrollingDiv = $("#map_canvas");
				var $content =  $(".wrapmapa");
				$(window).scroll(function(){			
					
					
					var objscroll = $(window).scrollTop();
					var sizecont = $content.height();
					var newsizecont = sizecont;
					var threshold = 700;
					var space = 0;
						
					if(objscroll>threshold) { 
						if( objscroll>50) space = 300;
						
						space = $(window).scrollTop() - threshold;
						
						$scrollingDiv.stop().animate({"top": (space) + "px"}, "fast" ); 
					}else{
						$scrollingDiv.stop().animate({"top": (space) + "px"}, "fast" ); 
					}
					//console.log('ScrollTop:'+objscroll +  ' MapTop:' + space);
					
					
				});
		    });


  </script>

  
  
  
</head>

<body>
<div id="main-wrapper">

<?php include('includes/header.inc.php');?>



   <!-- HEADER SEARCH SECTION -->
    <div class="header-search price-header-height">

      <div class="sample-page-heading">
        <span></span> <!-- for dark-overlay on the bg -->

        <div class="container">

          <h1>Sample <span>Page</span></h1>

        </div> <!-- END .container-->
      </div> <!-- END .about-us-heading -->

    </div> <!-- END .SEARCH and slide-section -->





    
  <div id="page-content">
    <div class="container">
      <div class="page-content">
        <div class="sample-page">
          <div class="row">
            <div class="col-md-8">
 
 
 <div class="gb13_inhalte">

		
		<h2>Su busqueda tuvo <?php echo $count;?> resultados</h2>
 		<div class="gb13_spalte3">
                    
<?php 
$i = 0;
foreach($list as $entry){ 
    $i++;
?>
        <div class="gb13_entry special" id="result_<?php echo $entry['id'];?>" onmouseover="map.setCenter(resultLocations[<?php echo $i;?>]);showInfo(<?php echo $i;?>);" onmouseout="hideInfo(<?php echo $i;?>);">
            <div id="customer_details_4491987">
                <h2><?php echo $entry['name']; ?></h2>
                <h2><?php echo $entry['description']; ?></h2>
                <p><?php echo $entry['address']; ?>, <?php echo $entry['city_name']; ?><br>
                <?php 
                $contact = venue::parse_contact_data($entry['contact']); 
                foreach($contact as $key=>$value) echo '<br>'.$key.': '.$value;
                ?>
                <br>
                <a href="venue.php?id=<?php echo $entry['id'];?>">Detalles</a>
            </div>
        </div>
<?php } ?>           
                    


				            	
            </div>
             <div class="gb13_spalte3" style="margin-left:10px;">
	            







    		</div>
                    
            <div class="cf"></div>
            
            <div class="paging">
               <?php 
                    $pipe = '';
                    $num_pages = $s->get_num_pages();
                    
                    for($i=1;$i<=$num_pages;$i++){
                            
                            echo $pipe;
                            $pipe = '|';
                            
                            echo ' &nbsp;  <a href="'.$paging_base_url.$glue.'p='.$i.'">'.$i.'</a> &nbsp; ';
                            
                    }
               ?>
            </div>
                	</div>
 

 
 
 

            </div> <!-- end .grid-layout -->

            <div class="col-md-4">
              <div class="post-sidebar">
             
			 
                <div class="map">
                        <div id="map_canvas" class="map-canvas" style="position: relative; overflow: hidden; transform: translateZ(0px); background-color: rgb(229, 227, 223);">
                            <div class="gm-style" style="position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%; z-index: 0;">

                            </div>
                        </div>
                </div>
			 

              </div> <!-- end .post-sidebar -->
            </div> <!-- end .grid-layout -->

          </div> <!-- end .row -->
        </div> <!-- end .sample-page -->
      </div> <!-- end .page-content -->
    </div> <!-- end .container -->

  </div> <!-- end #page-content -->

<?php include('includes/footer.inc.php'); ?>
</div> <!-- end #main-wrapper -->

<!-- Scripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="js/jquery.ba-outside-events.min.js"></script>

<script type="text/javascript" src="js/gomap.js"></script>
<script type="text/javascript" src="js/gmaps.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.js"></script>
<script src="js/scripts.js"></script>
<script src="js/Placeholders.min.js"></script>



<script language="javascript" type="text/javascript">initialize();</script>

</body>
</html>
