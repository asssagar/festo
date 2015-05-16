<?php
include('../lib/init.php');
$cat_list = venue::get_categories(6);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Festo Rocks</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/style.css">

  <!-- GOOGLE FONTS -->
  <link href='http://fonts.googleapis.com/css?family=Raleway:400,700,600,800%7COpen+Sans:400italic,400,600,700' rel='stylesheet' type='text/css'>

  <!--[if IE 9]>
    <script src="js/media.match.min.js"></script>
  <![endif]-->

</head>

<body>

<div id="main-wrapper">
    <?php include('includes/header.inc.php');?>

  	<div class="company-heading-view">
        <div class="container">
          <div class="button-content">
            <button><a href="#"><i class="fa fa-facebook-square"></i></a><span>Facebook</span></button>
            <button><a href="#"><i class="fa fa-google-plus-square"></i><span>Google+</span></button>
            <button><a href="#"><i class="fa fa-twitter-square"></i></a><span>Twitter</span></button>
			<button><a href="#"><i class="fa fa-linkedin-square"></i></a><span>LinkedIN</span></button>
          </div>
        </div>

        <div class="company-slider-content">
          <div class="general-view">
            <span></span> <!-- for dark-overlay on the bg -->
            <div class="container">

              <div class="logo-image">
                <img src="img/content/company-logo.jpg" alt="">
              </div>

            </div>
          </div> <!-- END .general-view -->

          <div class="company-map-view">
            <div id="company_map_canvas"></div>
          </div> <!-- END .company-map-view -->

          <div class="company-map-street">
            <div id="company_map_canvas_street"></div>
          </div> <!-- END .company-map-view-street -->

        </div> <!-- END .company-slider-content -->
    </div> <!-- END .about-us-heading -->  
  
<!-- SECCION CATEGORIAS, LOS MAS RECOMENDADOS Y ADDS -->
<div id="page-content">
	<div class="container"> 
	  <div class="row">
			<div class="col-sm-3">
				<h3>CATEGORIAS</h3>
				<div id="categories">
                <div class="accordion">
                  <ul class="nav nav-tabs home-tab" role="tablist">
                    <li class="active">
                      <a href="#"  role="tab" data-toggle="tab">Recomendados</a>
                    </li>
                    <?php foreach($cat_list as $cat){ ?>
                    <li>
                        <a href='#' onclick="window.location='venue_list.php?category_id=<?php echo $cat['id'];?>';" ><?php echo $cat['category_name'];?>
                        <span></span>
                      </a>
                    </li>
                    <?php } ?>
                    
                    

                  </ul>
                </div> <!-- end .accordion -->
              </div> <!-- end #categories -->
			</div>


			<div class="col-sm-6">
				<h3>LOS MAS <span>RECOMENDADOS</span></h3>
				
				
				<div class="product-details">

                    <div class="row clearfix">
                     <?php 
                            $recommended_venues = venue::get_recommended(6);
                            foreach($recommended_venues as $item){       
                     ?>   
                      <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="category-item">

                            <a href="venue_details.php?id=<?php echo $item['id'];?>"><img src="<?php echo venue::get_image('assets/venues/logos/', $item['id']);?>" width="70%" /><br/><?php echo $item['name'];?></a>
                        </div>
                      </div>
                      <?php } ?>
                     
                    </div> <!-- end .row -->
                  

                  
                                   
                

              </div> <!-- end .product-details -->
				
				
				
				
			</div>	
			
			
			<div class="col-sm-3">
				<img src="images/banners/banner263x150.jpg" />
				<br/>
				<img src="images/banners/banner263x150.jpg" />
			</div>
	  </div>
	</div>
</div>
<!-- FIN DE SECCION PARA CATEGORIAS, LOS MAS RECOMENDADOS Y ADDS -->


  
  <div id="page-content">
    <div class="container">
      <div class="home-with-slide">
        <div class="row">

          <div class="col-md-9 col-md-push-3">
            <div class="page-content">


            </div> <!-- end .page-content -->
          </div>

          <div class="col-md-3 col-md-pull-9 category-toggle">
            <button><i class="fa fa-briefcase"></i></button>

            <div class="page-sidebar">

			<!-- Category accordion -->
              

            </div> <!-- end .page-sidebar -->
          </div> <!-- end grid layout-->
        </div> <!-- end .row -->
      </div> <!-- end .home-with-slide -->
    </div> <!-- end .container -->
  </div>  <!-- end #page-content -->
  
<!-- SECCION PARA NOVEDADES, TWITTER Y ADDS -->
<div id="page-content"> 
	<div class="container"> 
		<div class="row">
			  <div class="col-sm-4">
				<h3>NOVEDADES</h3>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
				  Maiores explicabo numquam accusamus voluptatibus odit! Assumenda,
				  tempore, dolorum explicabo iusto molestiae.</p>
			  </div>

			  <div class="col-sm-4">
				<h3>SEGUINOS!</h3>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
				  Maiores explicabo numquam accusamus voluptatibus odit! Assumenda,
				  tempore, dolorum explicabo iusto molestiae.</p>
			  </div>

			  <div class="col-sm-4">
				<img src="images/banners/banner360x200.jpg" />
				<br/>
				<img src="images/banners/banner360x200.jpg" />
				
			  </div>
		</div>
	</div>
</div>
<!-- FIN PARA SECCION PARA NOVEDADES, TWITTER Y ADDS -->



 <?php include('includes/footer.inc.php');?>


</div> <!-- end #main-wrapper -->

<!-- Scripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="js/jquery.ba-outside-events.min.js"></script>
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="js/gomap.js"></script>
<script type="text/javascript" src="js/gmaps.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.js"></script>
<script src="js/scripts.js"></script>

<script>
  // gmap for street view
  panorama = GMaps.createPanorama({
    el: '#company_map_canvas_street',
    lat : 37.7762546,
    lng : -122.43277669999998,
  });
</script>

</body>
</html>
