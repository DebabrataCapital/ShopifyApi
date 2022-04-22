<?php
  echo "Magento Login"."</br>";
?>



<?php


  $webhook_content = NULL;  

  // Get webhook content from the POST
  $webhook = fopen('php://input' , 'rb');
  $data = json_decode(file_get_contents('php://input'), true);

  //print_r($data);

  $log = fopen('ordernew.log', 'w') or die ('can not open the file');
  fwrite($log, print_r($data, true));
  fwrite($log, $id, true);
  fclose($log);

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
        //echo "<pre>";
        //print_r($data);
        //echo "</pre>";


        
        
        $message  = '<html><body>';
        $message .= '<table border="1" cellpadding="10">';
        $message .= "<tr>
                        <th><strong>Sr. No.:</strong> </th>
                        <th><strong>OrderId</strong> </th>
                        <th><strong>CheckoutId</strong> </th>
                        <th><strong>OrderNumber</strong> </th>
                    </tr>";
        
        $id = '';
        $checkout_id = '';
        $order_number = '';


        foreach($data as $val) {
          foreach($val as $key => $item) { //it may give warning because empty array (i.e items = [].
             //var_dump($item);
             
             $id = $item['id'];
             $checkout_id = $item['checkout_id'];
             $order_number = $item['order_number'];

             //$sql = "INSERT INTO `api_data`(`order_id`, `checkout_id`, `order_number`) VALUES ($id, $checkout_id, $order_number)";

            //$sql = "UPDATE `api_data` SET `order_id`=$id,`checkout_id`=$checkout_id WHERE 1";
            //$conn->query($sql);
            
            

             $message .= "<tr>
                            <td>".$key." </td>
                            <td>" .$item['id']. "</td>
                            <td>" .$item['checkout_id']. "</td>
                            <td>" .$item['order_number']. "</td>
                          </tr>";
            
             //$i++;
             break;
          }
        }

        






        //echo "lm.nerdydragon.com/customapi/getpostval.php?order_id=$id";
        $message .= "</table>";
        $message .= "</body></html>";
        echo $message;
        //$conn->close();
        
        
        
    ?>

