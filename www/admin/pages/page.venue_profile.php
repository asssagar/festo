<?php


$v = new venue();
$v->load($_GET['id']);

$form = new form('Perfil de Empresa','','',$method = 'POST',$size=12);



if($form->is_sent()){
    $v->set('name',         $form->get_data('name'));
    $v->set('address',      $form->get_data('address'));
    $v->set('lat',          $form->get_data('lat'));
    $v->set('lng',          $form->get_data('lng'));
    
    $v->set('zipcode',      $form->get_data('zipcode'));
    $v->set('description',  $form->get_data('description'));
    
    
    
    $v->set('email',    $form->get_data('email'));
    $v->set('phone',    $form->get_data('phone'));
    $v->set('twitter',    $form->get_data('twitter'));
    $v->set('facebook',    $form->get_data('facebook'));
    
    $v->update();
    echo '<h3>Registro Actualizado</h3>';
}
    
    $form->textbox('name', 'Nombre', $v->get('name'));
    $form->textarea('address', 'Direccion', $v->get('address'));
    
    $form->map('map','Ubicacion',$v->get('lat'),$v->get('lng'));
    
    $form->textbox('zipcode', 'Zipcode', $v->get('zipcode'));
    $form->textarea('description', 'Descripcion', $v->get('description'));
    
    $form->textbox('email', 'Email', $v->get('email'));
    $form->textbox('phone', 'Telefono', $v->get('phone'));
    $form->textbox('twitter', 'Twitter', $v->get('twitter'));
    $form->textbox('facebook', 'Facebook', $v->get('facebook'));
    
    
    
    
    $form->submitbutton('Guardar');
    echo $form->deploy();
