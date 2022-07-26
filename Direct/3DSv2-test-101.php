<?php	  
	
// Merchant details and URL to this sample code.
$key = '';
$merchantID = '';
$samplecodeURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Gateway URL  
$gatewayURL = ''; 

// If this is the first request
if (!isset($_GET['threeDSAcsResponse'])) {

    // Request  
    $req = array(
    'merchantID' => $merchantID,
	'subMerchantID' => '704202123456789',
    'action' => 'SALE',
    'amount' => 2199,
    'type' => 1,
    'countryCode' => '826',
    'currencyCode' => '826',
    'orderRef' => 'CC #112406',
    'transactionUnique' => uniqid(5),

    // Credit card details
    'cardNumber' => '4543059999999982',
    'cardExpiryDate' => '1221',
    'cardCVV' => '110',

    // Customer details
    'customerAddress' => '76 Test road',
    'customerPostCode' => 'TE548ST',
    'customerName' => 'Test',
 // 'threeDSVersion' => '2',          // Should not be needed.
 // 'merchantCategoryCode' => 5411,   // Only required if not setup on the merchant.

     // Three DS V2 fields required
    'remoteAddress'             => $_SERVER['REMOTE_ADDR'],
    'threeDSRedirectURL'        => 'https://transaction-test.azurewebsites.net/3DSTester.php' . '?threeDSAcsResponse',
    'deviceChannel'				=> 'browser',
    'deviceIdentity'			=> (isset($_SERVER['HTTP_USER_AGENT']) ? htmlentities($_SERVER['HTTP_USER_AGENT']) : null),
    'deviceTimeZone'			=> '0',
    'deviceCapabilities'		=> '',
    'deviceScreenResolution'	=> '1x1x1',
    'deviceAcceptContent'		=> (isset($_SERVER['HTTP_ACCEPT']) ? htmlentities($_SERVER['HTTP_ACCEPT']) : null),
    'deviceAcceptEncoding'		=> 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    'deviceAcceptLanguage'		=> (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? htmlentities($_SERVER['HTTP_ACCEPT_LANGUAGE']) : null),
    'deviceAcceptCharset'		=> (isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? htmlentities($_SERVER['HTTP_ACCEPT_CHARSET']) : null),
    );

    // Sign the request
    $req['signature'] = createSignature($req, $key);  

    // Send the request to the gateway and get back the response
    $gatewayResponse = sendRequest($req, $gatewayURL);     

    // Store the threeDSRef in a cookie for reuse.  (this is just one way of storeing it)
    setcookie('threeDSRef', $gatewayResponse['threeDSRef'], time()+500);

    // Output request
    echo '<h2>Request</h2>';
    echo "<pre>";
    print_r($req);
    echo "</pre>";

    echo http_build_query($req);

    // Output gateway response
    echo '<h2>Response</h2>';
    echo "<pre>";
    print_r($gatewayResponse);
    echo "</pre>";

// Else if this is a response from 3DS
}  elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['threeDSAcsResponse'])) {

    // Build the request containing the threeDSResponse with data from the 3DS page
    // and include the threeDSRef stored in the cookie.
    $threeDSRequest = array(
        'threeDSRef' => $_COOKIE['threeDSRef'], // This is the threeDSref store in the cookie from the previous gateway response.
        'threeDSResponse' => $_POST, // <-- Note here no fields are hard coded. Whatever is POSTED from 3DS is returned.
    );

    // Sign the request
    $threeDSRequest['signature'] = createSignature($threeDSRequest, $key);  

    // Send the 3DS response back to the gateway and get the response.
    $gatewayResponse = sendRequest($threeDSRequest, $gatewayURL);    
    
    // Store the new threeDSRef in the cookie again because it may change.
    setcookie('threeDSRef', $gatewayResponse['threeDSRef'], time()+500);

    // Output request
    echo '<h2>Request</h2>';
    echo "<pre>";
    print_r($threeDSRequest);
    echo "</pre>";

    echo http_build_query($threeDSRequest);

    // Output gateway response
    echo '<h2>Response</h2>';
    echo "<pre>";
    print_r($gatewayResponse);
    echo "</pre>";


    //This cycle continues until the response is 0 and the transaction is complete.

} 




/**
 * Send request
 * 
 * @param Array $request
 * @param String $gatewayURL
 * 
 * @return Array $responseponse
 */
function sendRequest($request, $gatewayURL) {
            
    // Send request to the gateway

    // Initiate and set curl options to post to the gateway  
    $ch = curl_init($gatewayURL);  
    curl_setopt($ch, CURLOPT_POST, true);  
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));  
    curl_setopt($ch, CURLOPT_HEADER, false);  
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    
    // Send the request and parse the response  
    parse_str(curl_exec($ch), $response);  
    
    // Close the connection to the gateway  
    curl_close($ch);  

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

    return $response;
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

