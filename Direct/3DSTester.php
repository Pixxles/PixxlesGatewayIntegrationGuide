<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Merchant details and URL to this sample code.
$key = '';
$merchantID = '';

// Gateway URL  
$gatewayURL = '{gatewayURL}/api/Transactions/payment/direct';


$threeDSRequest = array(
  'merchantID' => $merchantID,
  'action' => 'SALE',
  'threeDSRef' => $_COOKIE['threeDSRef'], // This is the threeDSref store in the cookie from the previous gateway response.
  'threeDSResponse' => $_POST, // <-- Note here no fields are hard coded. Whatever is POSTED from 3DS is returned.
);

$threeDSRequest['signature'] = createSignature($threeDSRequest, $key);  

 // Initiate and set curl options to post to the gateway  
 $ch = curl_init($gatewayURL);  
 curl_setopt($ch, CURLOPT_POST, true);  
 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($threeDSRequest));  
 curl_setopt($ch, CURLOPT_HEADER, false);  
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
 
 // Send the request and parse the response  
 parse_str(curl_exec($ch), $response);  
 
 // Close the connection to the gateway  
 curl_close($ch);  

 setcookie('threeDSRef', $response['threeDSRef'], time()+500);
 
 
  print_r($_POST);
  echo "<br>";     
  echo "<br>";  
  
  print_r($threeDSRequest);
  echo "<br>";     
  echo "<br>";  
  
// Sign the request

  echo "<br>";  
  print_r($threeDSRequest);
  echo "<br>";   
 
 print_r($threeDSRequest);
 print_r($response);
 // Check the response code for 3DS Authentication (65802). If 3DS authentication required
 // Build a HTML form to submit to the threeDSURL



 if ($response['responseCode'] == 65802) {  



 // Start of HTML form with URL
 echo "<p>Your transaction requires 3D Secure Authentication</p>  
         <form action=\"" . htmlentities($response['threeDSURL']) . "\"method=\"post\">";

 // Add threeDSRef from the gateway response
 echo '<input type="hidden" name="threeDSRef" value="'. $response['threeDSRef'] . '">';

 // For each of the fields in threeDSRequest output a hidden input field with it's key/value
 foreach($response['threeDSRequest'] as $key => $value) {
     echo '<input type="hidden" name="'. $key .'" value="'. $value. '">';
 }

 // End of html form with submit button.
 echo "<input type=\"submit\" value=\"Continue\">
         </form>";            
 

 // If 3DS authentication isn't required check gateway response.      
 } else if ($response['responseCode'] === "0") {  
     echo "<p>Thank you for your payment.</p>";        
     
 } else {  
     echo "<p>Failed to take payment: " . htmlentities($response['responseMessage']) .  "</p>";  
 }  

/**
 * Sign request
 * 
 * @param Array $data
 * @param String $key
 * 
 * @return String Hash
 */
function createSignature(array $data, $key) {  
  // Sort by field name  
  ksort($data);  
  
  // Create the URL encoded signature string  
  $ret = http_build_query($data, '', '&');  

  // Normalise all line endings (CRNL|NLCR|NL|CR) to just NL (%0A)  
  $ret = str_replace(array('%0D%0A', '%0A%0D', '%0D'), '%0A', $ret);  

  // Hash the signature string and the key together  
  return hash('SHA512', $ret . $key);  
}      
?>