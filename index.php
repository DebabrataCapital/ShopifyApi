<?php

//echo "Shopify Order API"."</br>";

$webhook_content = NULL;

// Get webhook content from the POST
$webhook = fopen('php://input', 'rb');
$data = json_decode(file_get_contents('php://input'), true);
$lineItemVal1 = array();
$lineItemVal = $data['line_items'];

foreach ($lineItemVal as $key => $lineItem) {
  $measurement_arr = array();
  if ($lineItem['variant_title']) {
    $measurement = explode(" \/ ", $lineItem['variant_title']);
    foreach ($measurement as $k => $item) {
      $key = 'Measurement'.($k+1);
      $measurement_arr["$key"] = $item;
    }
  }
  $lineItems = array(
    'LineItemId'   => $lineItem['id'],
    'GarmentSku' => $lineItem['sku'],
    'IsHire' => true,
    'ItemPrice' => $lineItem['price'],
    'ItemQuantity' => $lineItem['quantity'],
    'ItemVariantId' => $lineItem['variant_id'],
    'Misc11' => null,
    "Misc12" => null,
    "Misc13" => null,
    "Misc14" => null,
    "Misc15" => null
  );

  if(sizeof($measurement_arr) != 0 ){
    $final = array_merge($lineItems, $measurement_arr);
  }else{
    $final = $lineItems;
  }
  $json[] = $final;
}

$id           = $data['id'];
$checkout_id  = $data['checkout_id'];
$order_number = $data['order_number'];
$email        = $data['email'];
$first_name   = $data['billing_address']['first_name'];
$last_name    = $data['billing_address']['last_name'];
$zip          = $data['shipping_address']['zip'];
$created_at   = $data['created_at'];
$city         = $data['shipping_address']['city'];
$country      = $data['shipping_address']['country'];
$address1     = $data['shipping_address']['address1'];
$address2     = $data['shipping_address']['address2'];
$phone        = $data['shipping_address']['phone'];

$deliveryService = "48";
$deliveryAgent = "RM";
$day = 2;

if (array_key_exists('code', $data['shipping_lines'])) {
  if (strstr($data['shipping_lines']['code'], "Standard")) {
    $deliveryService = "48";
    $deliveryAgent = "RM";
    $day = 2;
  } else if (strstr($data['shipping_lines']['code'], "XL")) {
    $deliveryService = "ND";
    $deliveryAgent = "DPD";
    $day = 1;
  }
}

// Add days 
$dispatchDate = date('Y-m-d', strtotime($date + $day . ' days'));
$deliveryDate = date('Y-m-d', strtotime($dispatchDate . ' + 2 days'));
$eventDate = date('Y-m-d', strtotime($dispatchDate . ' + 2 days'));
$warehouseReturnDate = date('Y-m-d', strtotime($dispatchDate . ' +100 years'));

$myObj = new stdClass();
$myObj->AccountCode = "TESTACS";
$myObj->OrderNumber = $order_number;
$myObj->OrderDate = date("Y-m-d", strtotime($created_at));
$myObj->DispatchDate = $dispatchDate;
$myObj->DeliveryDate = $deliveryDate;
$myObj->EventDate = $eventDate;
$myObj->WarehouseReturnDate = $warehouseReturnDate;

$myObj->FirstName = $first_name;
$myObj->LastName = $last_name;
$myObj->MobilePhone = $phone;
$myObj->Email = $email;
$myObj->Delivery_Address1 = $address1;
$myObj->Delivery_Address2 = $address2;
$myObj->Delivery_Address3 = "";
$myObj->Delivery_Address4 = null;
$myObj->Delivery_City = $city;
$myObj->Delivery_County = $country;
$myObj->Delivery_Postcode = $zip;
$myObj->DeliveryService = $deliveryService;
$myObj->DeliveryAgent = $deliveryAgent;
$myObj->DeliveryCharge = null;
$myObj->Comments = "";
$myObj->OrderCancelled = false;
$myObj->Misc1 = null;
$myObj->Misc2 = null;
$myObj->Misc3 = null;
$myObj->Misc4 = null;
$myObj->Misc5 = null;
$myObj->Misc6 = null;
$myObj->Misc7 = null;
$myObj->Misc8 = null;
$myObj->Misc9 = null;
$myObj->Misc10 = null;
$myObj->OrderItems = $json;

$myJSON = json_encode($myObj);

$log = fopen('ordernew.log', 'w') or die('can not open the file');
fwrite($log, print_r($myObj, true));
fclose($log);

if(!empty($order_number)){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "lm.nerdydragon.com/customapi/getpostval.php?order_number=$order_number",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURLOPT_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
    CURLOPT_POSTFIELDS => $myJSON,
  ));

  $resp = curl_exec($curl);
  curl_close($curl);
  
}else{
  echo "not call 2nd file";
}

?>