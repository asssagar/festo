<?php

include('../config/config.php');

function __autoload($className) {
        #$className = substr($className,strrpos($className, '\\'));
	if (file_exists('..'.DS.'lib'.DS.'class.'. strtolower($className) . '.php')) {
		require_once('..'.DS.'lib'.DS.'class.'. strtolower($className) . '.php');
	} else {
		die('Could not load class: '.$className.' from: lib'.DS. 'class.'.strtolower($className) . '.php');
	}
}



function json2array($json){
	if(get_magic_quotes_gpc()==1)$json = stripslashes ($json);
    $array = json_decode($json,true,10);
    
	$result = array();
    if(is_array($array)){
        foreach($array as $index=>$element){
            $result[$element['key']]=@$element['value'];
            if(@$element['checked'])$result[$element['key']]=$element['checked'];
        }
    }
	return $result;
}


function exception_handler($exception) {

     // these are our templates
     $traceline = "#%s %s(%s): %s(%s)";
     $msg = "PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

     // alter your trace as you please, here
     $trace = $exception->getTrace();
     foreach ($trace as $key => $stackPoint) {
         // I'm converting arguments to their type
         // (prevents passwords from ever getting logged as anything other than 'string')
         $trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
     }

     // build your tracelines
     $result = array();
     foreach ($trace as $key => $stackPoint) {
         $result[] = sprintf(
             $traceline,
             $key,
             $stackPoint['file'],
             $stackPoint['line'],
             $stackPoint['function'],
             implode(', ', $stackPoint['args'])
         );
     }
     // trace always ends with {main}
     $result[] = '#' . ++$key . ' {main}';

     // write tracelines into main template
     $msg = sprintf(
         $msg,
         get_class($exception),
         $exception->getMessage(),
         $exception->getFile(),
         $exception->getLine(),
         implode("\n", $result),
         $exception->getFile(),
         $exception->getLine()
     );

     // log or echo as you please
     $_SESSION['error_message'] = $msg;
     die('<meta http-equiv="refresh" content="0; url=?p=error" />');
     
 }

#set_exception_handler('exception_handler');


function encode($str){
    return (DEBUG)?$str:base64_encode($str);
}

function decode($str){
    return (DEBUG)?$str:base64_decode($str);
}

function debug($str){
    if(DEBUG){
        echo '<pre>';
        print_r($str);
        echo '</pre>';
    }
}

$db = new db();