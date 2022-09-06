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
    'LineItemId'   => strval($lineItem['id']),
    'GarmentSKU' => strval($lineItem['sku']),
    'IsHire' => 1
    'ItemPrice' => $lineItem['price'],
    'ItemQuantity' => $lineItem['quantity']
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
  } else if (strstr($data['shipping_lines']['code'], "XL") || strstr($data['shipping_lines']['code'], "Large")) {
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
$myObj->Delivery_City = $city;
$myObj->Delivery_Postcode = $zip;
$myObj->DeliveryService = $deliveryService;
$myObj->DeliveryAgent = $deliveryAgent;
$myObj->OrderItems = $json;

$myJSON = json_encode($myObj);

$log = fopen('ordernew.log', 'w') or die('can not open the file');
fwrite($log, print_r($myObj, true));
fclose($log);

if(!empty($order_number)){

  //$url = "lm.nerdydragon.com/customapi/getpostval.php?order_number=$order_number";
  //$url = "https://uat-partnerapis.acsclothing.co.uk/api/v1/orders/$order_number";
  $url = "https://uat-apis.acsclothing.co.uk/api/partner/v1/orders/$order_number.json";
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($ch, CURLOPT_POSTFIELDS,$myJSON);
  //curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Ocp-Apim-Subscription-Key:01c2b8321fc54e349a9f2ce55c2e15db'));
  # Return response instead of printing.
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  $result = curl_exec($ch);
  curl_close($ch);

  $log = fopen('response.log', 'w') or die('can not open the file');
  fwrite($log, print_r($result, true));
  fclose($log);

}else{
  echo "not call 2nd file";
}

?>
