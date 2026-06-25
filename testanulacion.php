<?php
        //Enter your code here, enjoy!
        $JsonAnulacionFactrua='{
         "numeroAutorizacionUUID": "CD473519-4FF3-4EE6-B3FB-17175F0C3F0A",
         "motivoAnulacion": "Anulacion De Factura"
     }';

     $url = 'http://daocastro-001-site8.itempurl.com/api/EcoFactura/anularDocumento';
     $resultado=callAPI("POST", $url, $JsonAnulacionFactrua);
     $ArrayResultado=json_decode($resultado, true);

     echo $resultado;

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
     
