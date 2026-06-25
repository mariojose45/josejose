$(document).ready(function(){

	var totalValue = 0;
	var quantity = 0;
	var restante;
	var docsArray = [];

		$('#btnguardar').click(function(){

			totalValue = $('#valueQ').val();
			$('#btnguardar').prop('disabled', true);
			$('#valueQ').prop('disabled', true);
			$("#inputValorInicial").val(totalValue);
			$('#btnguardarFactura').prop('disabled', false);





			return false;
		});


			$('#btnguardarFactura').click(function(){

        	var tipo = $('#selectDocType').val();
        	var valor = $('#valorDinero').val();
        	var nota = $('#notaDesc').val();
        	var myDate =  $('#documentDate').val();
        	var myDateMiliSeconds = new Date(document.getElementById("documentDate").value);

        	quantity = parseFloat(quantity) + parseFloat(valor);       

        	restante = parseFloat(totalValue) - parseFloat(quantity);



        	$("#inputFacturas").val(quantity);
        	$("#test3").val(restante);

        	if(restante===0)
        	{
        		$('#saveCaja').prop('disabled', false);
        		$('#btnguardarFactura').prop('disabled', true);
            }
        	else
        	{
        		$('#saveCaja').prop('disabled', true);
        	}


        	var tipoDocumentoTxt;

                var datos=$('#frmajaxfactura').serialize();
        		docsArray.push(datos);



			$("#mainFacturas ul").append('<li class="list-group-item">'+tipo+ ' - '+ myDate + ' - Q. ' +valor+ '</li>');

        	limpiar();




			return false;
		});

    $('#saveCaja').click(function(){

 

        var datos=$('#frmajax').serialize();
        // datos.push({name:"initialValue", value:totalValue});
        $.ajax({ 
            type:"POST",
            url:"../modelos/Cajachicainsertar.php",
            data:datos+'&'+$.param({ 'initialValue': totalValue }),
            success:function(r){
                    insertAllDocuments(r);
            }
        });

        return false;

    });


    function limpiar()
    {
        $("#valorDinero").val("");
        $("#notaDesc").val("");
        $("#documentDate").val("");
        return false;
    }



    function insertAllDocuments(id_caja) {
		var successResponse = true;
    	for(var i=0; i<docsArray.length;i++)
		{
            // alert(id_caja);
            var datos=docsArray[i];
            $.ajax({
                type:"POST",
                url:"../modelos/Cajachicainsertar_factura.php",
                data:datos+'&'+$.param({ 'id_caja_chica': id_caja }),
                success:function(r){
                    if(r!=1){
                        success = false;
                    }
                }
            });
		}

		if(successResponse)
		{
            bootbox.alert("La caja fue guardada con exito");
            mostrarform(false);
            tabla.ajax.reload();
            limpiar();
        }
        else {bootbox.alert("Falló el servidor en la inserción de datos.");}

		return false;

    }



	});