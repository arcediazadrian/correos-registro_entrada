$(document).ready(function() {

    $('select[name="pais_id_dest"]').on('change', function(){
        var paisId = $(this).val();
        if(paisId) {
            $.ajax({
                url: '/registro/registroPaquete/getCiudades/'+paisId,
                type:"GET",
                dataType:"json",

                success:function(data) {

                    $('select[name="ciudad_id"]').empty();
                    $('select[name="ciudad_id"]').append('<option value=""> Seleccione una ciudad </option>');

                    $.each(data, function(key, value){

                        $('select[name="ciudad_id"]').append('<option value="'+ key +'">' + value + '</option>');

                    });
                }
            });
        } else {
            $('select[name="ciudad_id"]').empty();
        }

    });

});