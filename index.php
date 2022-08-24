<?php

    //echo "Shopify Order API"."</br>";

    $webhook_content = NULL;  

    // Get webhook content from the POST
    $webhook = fopen('php://input' , 'rb');
    $data = json_decode(file_get_contents('php://input'), true);

    //print_r($data);
    $lineItemVal1 = array();
    $log = fopen('fulfilment.log', 'w') or die ('can not open the file');

    $lineItemVal = $data['line_items'];
    foreach($lineItemVal as $key => $lineItem){

      $lineItemVal1[$key]['LineItemId']   = $lineItem['id'];
      $lineItemVal1[$key]['GarmentSku'] = $lineItem['sku'];
      $lineItemVal1[$key]['IsHire'] = true;
      $lineItemVal1[$key]['ItemPrice'] = $lineItem['price'];
      $lineItemVal1[$key]['ItemQuantity'] = $lineItem['quantity'];
      $lineItemVal1[$key]['ItemVariantId'] = $lineItem['variant_id'];
      $lineItemVal1[$key]['Misc11'] = null;
      $lineItemVal1[$key]['Measurement1'] = 44;
      $lineItemVal1[$key]['Measurement2'] = "S";

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


    if(array_key_exists('code',$data['shipping_lines'])){
      if (strstr($data['shipping_lines']['code'], "Standard")){
        $deliveryService = "RM";
      }else if (strstr($data['shipping_lines']['code'], "XL")){
        $deliveryService = "ND";
      }else{
        $deliveryService = "Default";
      }
    }else{
      $deliveryService = "Default";
    }


    //$myJSON = json_encode($lineItemVal1);
    //fwrite($log, $myJSON);
    //fwrite($log, print_r($data, true));
    
    fwrite($log, $order_number);
    fwrite($log, $first_name);
    fwrite($log, $last_name);
    fclose($log);


    // Date + 3 days
    $date = $created_at;
    // Add days 
    $dispatchDate = date('Y-m-d', strtotime($date. ' + 3 days')); 
    $deliveryDate = date('Y-m-d', strtotime($date. ' + 4 days')); 
    $eventDate = date('Y-m-d', strtotime($date. ' + 5 days')); 
    $warehouseReturnDate = date('Y-m-d', strtotime($date. ' + 10 days')); 

    $myObj = new stdClass();
    $myObj->AccountCode = "TESTACS";
    $myObj->OrderNumber = $order_number;
    $myObj->OrderDate = $created_at;
    $myObj->DispatchDate = "2022-04-28";
    $myObj->DeliveryDate = "2022-04-29";
    $myObj->EventDate = "2022-04-30";
    $myObj->WarehouseReturnDate = "2022-05-05";

    $myObj->FirstName = $first_name;
    $myObj->LastName = $last_name;
    $myObj->MobilePhone = $phone;
    $myObj->Email = $email;
    $myObj->Delivery_Address1 = $address1;
    $myObj->Delivery_Address2 = $address2;
    $myObj->Delivery_City = $city;
    $myObj->Delivery_County = $country;
    $myObj->Delivery_Postcode = $zip;
    $myObj->DeliveryService = $deliveryService;
    $myObj->DeliveryAgent = "DPD";
    $myObj->DeliveryCharge = null;
    $myObj->Comments = "";
    $myObj->OrderCancelled = false;
    $myObj->OrderItems = $lineItemVal1;

    $myJSON = json_encode($myObj);


?>



<?php 

  /*
    $servername = "localhost";
    $username = "root";
    $password = "123";
    //$dbname = "apitest";
    $dbname = "apitest";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    */

      /*
        echo "Shopify Order Details</br>";

        $url = 'https://53d8b292ac04af188fe0e5042f590e2a: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@humidors-direct.myshopify.com/admin/api/2022-04/orders.json';
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
          "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $resp = curl_exec($curl);
        curl_close($curl);

        //var_dump($r);

        $data = json_decode($resp, true);
        $i = 0;
        echo "<pre>";
        print_r($data);
        echo "</pre>";
      


        
        
        $message  = '<html><body>';
        $message .= '<table border="1" cellpadding="10">';
        $message .= "<tr>
                        <th><strong>Sr. No.:</strong> </th>
                        <th><strong>OrderId</strong> </th>
                        <th><strong>CheckoutId</strong> </th>
                        <th><strong>OrderNumber</strong> </th>

                        <th><strong>CreatedAt</strong> </th>
                        <th><strong>Email</strong> </th>
                        <th><strong>FirstName</strong> </th>
                        <th><strong>LlastName</strong> </th>
                        <th><strong>Zip</strong> </th>
                        <th><strong>City</strong> </th>
                        <th><strong>Country</strong> </th>
                        <th><strong>Address</strong> </th>
                        <th><strong>Phone</strong> </th>
                        
                    </tr>";
        
        $id = '';
        $checkout_id = '';
        $order_number = '';
        $email = '';
        $first_name = '';
        $last_name = '';
        $zip = '';

        $lineItemVal = array();
        foreach($data as $val) {
          foreach($val as $key => $item) { //it may give warning because empty array (i.e items = [].

            foreach($item as $key => $lineItem){

              $lineItemVal[$key]['id'] = $lineItem['id'];
              $lineItemVal[$key]['sku'] = $lineItem['sku'];
              $lineItemVal[$key]['price'] = $lineItem['price'];

              echo "hhhh...";
            }
             //var_dump($item);
             
             $id = $item['id'];
             $checkout_id = $item['checkout_id'];
             $order_number = $item['order_number'];
             $created_at = $item['created_at'];
             $email = $item['email'];
             $first_name = $item['billing_address']['first_name'];
             $last_name = $item['billing_address']['last_name'];

             $zip = $item['shipping_address']['zip'];
             $city = $item['shipping_address']['city'];
             $country = $item['shipping_address']['country'];
             $address1 = $item['shipping_address']['address1'];
             $address2 = $item['shipping_address']['address2'];
             $phone = $item['shipping_address']['phone'];
             

             //$sql = "INSERT INTO `api_data`(`order_id`, `checkout_id`, `order_number`) VALUES ($id, $checkout_id, $order_number)";

            //$sql = "UPDATE `api_data` SET `order_id`=$id,`checkout_id`=$checkout_id WHERE 1";
            //$conn->query($sql);
            
            

             $message .= "<tr>
                            <td>".$key." </td>
                            <td>" .$item['id']. "</td>
                            <td>" .$item['checkout_id']. "</td>
                            <td>" .$item['order_number']. "</td>

                            <td>" .$created_at. "</td>
                            <td>" .$email. "</td>
                            <td>" .$first_name. "</td>
                            <td>" .$last_name. "</td>
                            <td>" .$zip. "</td>
                            <td>" .$city. "</td>
                            <td>" .$country. "</td>
                            <td>" .$address1. $address2. "</td>
                            <td>" .$phone. "</td>
                          </tr>";
            
             //$i++;
             break;
          }
        }


        $message .= "</table>";
        $message .= "</body></html>";
        echo $message;

        print_r($lineItemVal);
        //$conn->close();
        
        //echo "hhhhh ";
        //echo $id."</br>";
        //echo $checkout_id."</br>";
        //echo $order_number."</br>";
        //echo $email."</br>";
        //echo $first_name."</br>";
        //echo $last_name."</br>";
        //echo $zip."</br>";

       
        $myObj = new stdClass();
        $myObj->AccountCode = "TESTACS";
        $myObj->OrderNumber = $order_number;
        $myObj->OrderDate = "John";
        $myObj->DispatchDate = "2022-04-28";
        $myObj->DeliveryDate = "2022-04-29";
        $myObj->EventDate = "2022-04-30";
        $myObj->WarehouseReturnDate = "2022-05-05";        

        $myObj->FirstName = $first_name;
        $myObj->LastName = $last_name;
        $myObj->MobilePhone = $phone;
        $myObj->Email = $email;
        $myObj->Delivery_Address1 = $address1;
        $myObj->Delivery_Address2 = $address2;
        $myObj->Delivery_City = $city;
        $myObj->Delivery_County = $country;
        $myObj->Delivery_Postcode = $zip;
        $myObj->DeliveryService = "ND";
        $myObj->DeliveryAgent = "DPD";
        $myObj->DeliveryCharge = null;
        $myObj->Comments = "";
        $myObj->OrderCancelled = false;

        $myJSON = json_encode($myObj);

        echo $myJSON;
        */
        /*
        "AccountCode": "TESTACS",
    "OrderNumber": "ZZ-220425-01",
    "OrderDate": "2022-04-27",
    "DispatchDate": "2022-04-28",
    "DeliveryDate": "2022-04-29",
    "EventDate": "2022-04-30",
    "WarehouseReturnDate": "2022-05-05",
    "FirstName": "ACS",
    "LastName": "CLOTHING",
    "MobilePhone": "07111111111",
    "Email": "test@Acsclothing.co.uk",
    "Delivery_Address1": "6 Dovecote Rd",
    "Delivery_Address2": "",
    "Delivery_Address3": "",
    "Delivery_Address4": null,
    "Delivery_City": "HOLYTOWN",
    "Delivery_County": null,
    "Delivery_Postcode": "ML1 4GP",
    "DeliveryService": "ND",
    "DeliveryAgent": "DPD",
    "DeliveryCharge": null,
    "Comments": "",
    "OrderCancelled": false,
    "Misc1": null,
    "Misc2": null,
    "OrderItems": [
      {
          "LineItemId": "001",
          "GarmentSku": "SFSJNAVY",
          "IsHire": true,
          "ItemPrice": 0,
          "Misc11": null,
          "Misc12": null,
          "Misc13": null,
          "Misc14": null,
          "Misc15": null,
          "Measurement1": "44",
          "Measurement2": "S"
      },
      {
          "LineItemId": "002",
          "GarmentSku": "SFSTRSNAVY",
          "IsHire": true,
          "ItemPrice": 0,
          "Misc11": null,
          "Misc12": null,
          "Misc13": null,
          "Misc14": null,
          "Misc15": null,
          "Measurement1": "32",
          "Measurement2": "34"
      }
  ]
        //echo "lm.nerdydragon.com/customapi/getpostval.php?order_id=$id";
        
        */
        
    ?>
