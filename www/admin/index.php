<?php
chdir('..');
include('../lib/init.php');
adminsession::start();
$db = new db();

$inner_page = 'page.home.php';

if(@$_GET['p']){
    $ip = $_GET['p'];
    
    if(file_exists('admin/pages/page.'.$ip.'.php')) $inner_page = 'page.'.$ip.'.php';
}

if(!adminsession::admin_logged()) $inner_page = 'page.login.php';

include('inc/inc.header.php');
?>
<body>

    <div id="wrapper">

      <?php include('inc/inc.nav.php'); ?>
        <div id="page-wrapper">
          <?php include('pages/'.$inner_page);?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
   

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
<?php db::close();?>
