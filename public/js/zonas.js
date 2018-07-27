$(document).ready(function() {

    $('select[name="ciudad_id"]').on('change', function(){
        var ciudadId = $(this).val();
        if(ciudadId) {
            $.ajax({
                url: '/registro/registroPaquete/getZonas/'+ciudadId,
                type:"GET",
                dataType:"json",

                success:function(data) {

                    $('select[name="zona_id"]').empty();
                    $('select[name="zona_id"]').append('<option value=""> Seleccione una zona </option>');

                    $.each(data, function(key, value){

                        $('select[name="zona_id"]').append('<option value="'+ key +'">' + value + '</option>');

                    });
                }
            });
        } else {
            $('select[name="zona_id"]').empty();
        }

    });

});