<?php
class base{
    
    
    public static function redir($url){
        echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
        exit();
    }
    
    
}
