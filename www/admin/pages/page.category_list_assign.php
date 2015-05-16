<?php

$sql = "SELECT id, category_name FROM venue_categories WHERE festo_category = 0 limit 100;";
$list = $db->query($sql,array());

$cat_list = $db->query('select id,category_name from festo_categories;',array());



?>
<h2>Asignar categorias a categorías</h2>

<table style="column-gap: 10; ">
    <?php foreach($list as $row){ ?>
    <tr>
        <td><?php echo $row['category_name'];?></td>
        <td>
            <select name="select_category_<?php echo $row['id'];?>" onchange="set_category(<?php echo $row['id']?>,this.value);">
                <option value="0">Seleccionar</option>
                <?php foreach($cat_list as $cat){?>
                <option value="<?php echo $cat['id']?>"><?php echo $cat['category_name']?></option>
                <?php } ?>
            </select>
        </td>
            
    </tr>
    
    <?php } ?>
</table>
<script type="text/javascript">
    function set_category(venue_cat,festo_cat){
        var url = '<?php echo API_URL; ?>';
        
        var json = [
            {
                "key": "venue_category",
                "value": venue_cat
            },
            {
                "key": "festo_category",
                "value": festo_cat
            }
        ]
        
        var request = {
            "c": "venuecategory",
            "m": "set_festo_category",
            "data": JSON.stringify(json)
        }
        
        
        
        $.post( url, request, function( data ) {
            //$( ".result" ).html( data );
            //alert( "Load was performed." );
        });
    }
</script>