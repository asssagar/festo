<?php
include('../lib/init.php');
header('Access-Control-Allow-Origin: *');  


#initialize connection

$db = new db();

$ws_return = array();

/**
 * Genera la respuesta para el cliente
 */
function ws_response(){
	global $ws_return;
	header('Content-type: text/json');
	header('Content-type: application/json');
        $return = encode(json_encode($ws_return));

	die(json_encode($return));
}




if(@!$_POST['c'] or @!$_POST['m']){
	$ws_return['status']	='error';
	$ws_return['message']	='Incorrect Parameters ';
	ws_response();
}



if(!class_exists($_POST['c'])){
	$ws_return['status']	='error';
	$ws_return['message']	='Class not found:'.$request['c'];
	ws_response();
}

//Cargamos la clase y creamos el objeto
$obj = new $_POST['c'];

if(!method_exists($obj, 'api_'.$_POST['m'])){
	$ws_return = array(
		'status'	=> 'error',
		'message'	=> 'Method not found:'.$_POST['m'].' On class: '.$_POST['c']
	);
	ws_response();
}
$method = 'api_'.$_POST['m'];

$request = json2array(decode(@$_POST['data']));
if(!is_array($request)){
    	$ws_return = array(
		'status'	=> 'error',
		'data'		=> 'Incorrect request format: '.stripslashes($_POST['data'])
	);
	ws_response();
}
$response = $obj->$method($request);
if(is_array($response)){
	$ws_return = array(
		'status'	=> 'success',
		'data'		=> $response
	);
	ws_response();
}else{
	$ws_return = array(
		'status'	=> 'error',
		'message'	=> $response
	);
}


ws_response();