<html>
 <head>
  <title>Тестируем PHP</title>
 </head>
 <body>
<?php 
  $ch = curl_init(); 
 // rate % = ((v2-v1)/v1)*100
 curl_setopt_array($ch, array(
  CURLOPT_URL => "https://api.fixer.io/latest",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));
//Execute the request.
$data = curl_exec($ch);
  //echo '<p>'.$data.'</p>';
  
  if ($data === FALSE) {  
    //Тут-то мы о ней и скажем  
    echo "cURL Error: " . curl_error($ch);  
    return;  
} 
curl_close($ch); 
$result=json_decode($data, true);
 ?>
 <table>
<tr>
<th>Name</th>
<th>Value today</th>
</tr>
<?php
$rates = $result["rates"];
arsort($rates);
 foreach($rates as $currency => $val) {
    
    echo '<tr><td>'.$currency.'</td><td>'.$val.'</td></tr>';
    
}
?>

 </table>
  <!--  echo '<p> Result: '.$result["base"].'</p>';
?> --> 
 </body>
</html>
