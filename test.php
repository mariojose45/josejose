<?php
        //Enter your code here, enjoy!
$json = '{
  "tipoDocumento": "FACT",
  "numeroTransaccion": "3434544",
  "fechaTransaccion": "2020-05-19",
  "tipoMoneda": "GTQ",
  "nitCliente": "58690085",
  "codigoCliente": "2343",
  "nombreCliente": "Mario de Leon",
  "direccionCliente": "Guatemala",
  "observacion": "",
  "correoCliente": "",
  "detallesDocumento":[
		{
  "numeroLinea": "1",
  "codigoArticulo": "A3343",
  "nombreArticulo": "Dildo",
  "cantidadArticulo": "1",
  "valorUnitario": "100",
  "unidadMedida": "Unidad",
  "valorDescuento": "0",
  "tipoItem": "B",
  "impuestoAdicional": "0",
  "adicionalGrabable": "0",
  "impuestoMontoAdicional": "0"
}
	]
}';

echo callAPI("POST", "http://facturacionelecprod.daocastro.com/api/EcoFactura/generarDocumento", $json);

function callAPI($method, $url, $data){
   $curl = curl_init();
   switch ($method){
      case "POST":
           curl_setopt($curl, CURLOPT_POST, 1);
           curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}
     
