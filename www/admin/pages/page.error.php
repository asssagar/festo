<div class="panel panel-default">
                        <div class="panel-heading">
                            Hemos detectado un error!
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="alert alert-success">
                                <?php echo $_SESSION['error_message'];?> <a href="./" class="alert-link">Volver</a>.
                            </div>
                            
                        </div>
                        <!-- .panel-body -->
                    </div>

<?php @unlink($_SESSION['error_message']); ?>