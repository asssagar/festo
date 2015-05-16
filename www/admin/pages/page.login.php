<?php
$show_login_form = true;
$show_try_limit_error = false;


if(!@$_SESSION['admin_login_try'])$_SESSION['admin_login_try']=1;
$_SESSION['admin_login_try']=1;



if(@$_POST['action']=='login'){
    
    if(@$_SESSION['admin_login_try']){
        $_SESSION['admin_login_try']++;
    }
    
    if($_SESSION['admin_login_try']>3){
        $show_try_limit_error = true;
        $show_login_form = false;
        
    }else{
        $login = adminsession::admin_login($_POST['user_name'],$_POST['password']);
    
        if($login){
            base::redir("./");
            
        }else{
            $show_login_form=true;
           
        }
    }
    
    
}





if($show_login_form){
?>
    <div class="container">
        
        <div class="row" >
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Admin Login</h3>
                    </div>
                    <div class="panel-body">
                        <?php if($show_login_form){ ?>
                        <form role="form" action="?p=login" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input type="hidden" name="action" value="login">
                                    <input class="form-control" placeholder="Username" name="user_name" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                
                                <!-- Change this to a button or input when using this as a form -->
                                <input id="btn_login" type="submit" class="btn btn-lg btn-success btn-block" value="Ingresar (<?php echo $_SESSION['admin_login_try']; ?> de 3)">
                            </fieldset>
                        </form>
                        <?php }?>
                        <?php if($show_try_limit_error){ ?>
                            <h4 class="panel-title">Ya no puede seguir intentando ingresar</h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
 