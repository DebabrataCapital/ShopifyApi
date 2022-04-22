<?php

  echo "Magento Login"."</br>";

?>

<?php



 ?>

<!--
key = 47e35c736a6e35721afced8e77d0c84b
secret = 1f99a16a112aa373bf3af6d24a812690
token = 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

Private applications authenticate with Shopify through basic HTTP authentication, using the URL format
 https://{apikey}:{password}@{hostname}/admin/api/{version}/{resource}.json 


 https://47e35c736a6e35721afced8e77d0c84b:{password}@{hostname}/admin/api/{version}/{resource}.json 


 https://466d9e3095d01cf9926271dfe981eb02:f46553416d5e3e99c3e8b41f55e59694@humidors-direct.myshopify.com/admin/api/2020-01/products.json
 https://53d8b292ac04af188fe0e5042f590e2a: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@humidors-direct.myshopify.com/admin/api/2022-04/orders.json

-->


<?php
  //echo "test";
  /*

  $url = "https://53d8b292ac04af188fe0e5042f590e2a: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@humidors-direct.myshopify.com/admin/api/2022-04/orders.json";

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $headers = array(
    "Accept: application/json",
  );
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  //for debug only!
  //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $resp = curl_exec($curl);
  curl_close($curl);

  //var_dump($resp);
  //print_r($resp['id']);
  */

?>



<?php
/*
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "apitest";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO `api_data`(`id`, `order_id`, `checkout_id`, `order_details`, `order_data`) VALUES ('','','','','')";


if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
*/
?> 


  <!-- 'host' => 'localhost',
  'dbname' => 'honeystore',
  'username' => 'user_honeystore',
  'password' => 'WW4)ski$@@12py', -->



<?php

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

        /*
        if($id){

          $url = "lm.nerdydragon.com/customapi/getpostval.php?order_id=$id"; // Specify your url
          $data = array('id'=>$id,'checkout_id'=> $checkout_id, 'order_number' => $order_number); // Add parameters in key value
          $ch = curl_init(); // Initialize cURL
          curl_setopt($ch, CURLOPT_URL,$url);
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_exec($ch);
          curl_close($ch);
        }else{
          echo "not call the file";
        }
        */

        if(!empty($id)){
          $curl = curl_init();
          $data = array('id'=>$id,'checkout_id'=> $checkout_id, 'order_number' => $order_number); // Add parameters in key value
          //$data = array("id" => $id, "name" => $name, "address" => $address,"phone"=>$phone);
          //$payload = json_encode( array( "customer"=> $data ) );

          curl_setopt_array($curl, array(
            CURLOPT_URL => "lm.nerdydragon.com/customapi/getpostval.php?order_id=$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURLOPT_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS, http_build_query($data),
            //CURLOPT_POSTFIELDS, $payload,
            //curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
          ));

          $resp = curl_exec($curl);
          curl_close($curl);

          echo "<pre>" . $resp. "</pre>";

        }else{
          echo "not call 2nd file";
        }






        //echo "lm.nerdydragon.com/customapi/getpostval.php?order_id=$id";
        $message .= "</table>";
        $message .= "</body></html>";
        echo $message;
        $conn->close();
        
        
        
    ?>


<!--

/*
            if ($conn->query($sql) === TRUE) {
              echo "New record created successfully"."</br>";
            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
            }
            */

/*
  if($varified){
    $response = $data;
  }else{
    $response = "Something wrong";
  }

  $log = fopen('ordernew.json', 'w') or die ('can not open the file');
  fwrite($log, $response);
  fclose($log);
  */
  -->