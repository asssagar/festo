

function apiCall(url,c,m,data){

    var request = {
        "c": c,
        "m": m,
        "data": JSON.stringify(data)
    }
    $.post( url, request, function( data ) {
        //$( ".result" ).html( data );
        //alert( "Load was performed." );
        return true;
    });
}