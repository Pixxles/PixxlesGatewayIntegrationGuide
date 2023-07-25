<?php	  


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Merchant details and URL to this sample code.
$key = '';
$merchantID = '';

$samplecodeURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Gateway URL  
$gatewayURL = '{gatewayURL}/api/Transactions/payment/direct';

// If this is the first request
if (!isset($_GET['threeDSAcsResponse'])) {
    // Request  
    $req = array(
    'merchantID' => $merchantID,
    'action' => 'SALE',
    'amount' => 888,
    'type' => 1,
    'currencyCode' => '826',
    'orderRef' => 'CC#77777',
    'transactionUnique' => uniqid(5),

    // Credit card details
    'cardNumber'         => '{cardNumber}',
    'cardExpiryMonth'    => '{cardExpiryMonth}',
    'cardExpiryYear'     => '{cardExpiryYear}',
    'cardCVV'            => '{cardCVV}',

    // Customer details
    'customerCountryCode' => '{customerCountryCode}',
    'customerAddress' => '{customerAddress}',
    'customerTown' => '{customerTown}',
    'customerPostCode' => '{customerPostCode}',
    'customerName' => '{customerName}',
    'customerPhone' => '{customerPhone}',

     // Three DS V2 fields required
    'remoteAddress'             => $_SERVER['REMOTE_ADDR'],
    // Change {myshop.com} to your own website
    'threeDSRedirectURL'        => 'https://{myshop.com}/3DSTester.php' . '?threeDSAcsResponse',
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

	// Check HERE!!!
	if (headers_sent($filename, $linenum)) {
		echo "Headers already sent in $filename on line $linenum\n";
		exit;
	}

	if (array_key_exists('form', $gatewayResponse)) {

		setcookie('threeDSRef', $gatewayResponse['threeDSRef'], time()+500, '/');
	    echo $gatewayResponse['form'];
		var_dump($gatewayResponse['threeDSRef']);
	  exit;
	} elseif (array_key_exists('successMessage', $gatewayResponse)) {
	  echo $gatewayResponse['successMessage'];
	  exit;
	} elseif (array_key_exists('errorMessage', $gatewayResponse)) {
	  echo $gatewayResponse['errorMessage'];
	  exit;
	}



// Else if this is a response from 3DS
}  elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['threeDSAcsResponse'])) {

    // Build the request containing the threeDSResponse with data from the 3DS page
    // and include the threeDSRef stored in the cookie.
    $threeDSRequest = array(
        'merchantID' => $merchantID,
        'action' => 'SALE',
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
    var_dump($threeDSRequest);
    echo "</pre>";

    echo http_build_query($threeDSRequest);

    // Output gateway response
    echo '<h2>Response</h2>';
    echo "<pre>";
    var_dump($gatewayResponse);
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
		$form = "<p>Your transaction requires 3D Secure Authentication</p>  
				<form action=\"" . htmlentities($response['threeDSURL']) . "\"method=\"post\">";

		// Add threeDSRef from the gateway response
		$form  .= '<input type="hidden" name="threeDSRef" value="'. $response['threeDSRef'] . '">';

		// For each of the fields in threeDSRequest output a hidden input field with it's key/value
		foreach($response['threeDSRequest'] as $key => $value) {
			$form .= '<input type="hidden" name="'. $key .'" value="'. $value. '">';
		}

		// End of html form with submit button.
		$form  .= "<input type=\"submit\" value=\"Continue\">
				</form>";            
		

		// If 3DS authentication isn't required check gateway response.      
	} else if ($response['responseCode'] === "0") {  
		$succesMessage = "<p>Thank you for your payment.</p>";        
    } else {  
		$errorMessage = "<p>Failed to take payment: " . htmlentities($response['responseMessage']) .  "</p>";  
	}  

	if (isset($form)) {
	  $response['form'] = $form;
	} elseif (isset($successMessage)) {
	  $response['succesMessage'] = $succesMessage ;

	} elseif (isset($errorMessage)) {
		$response['errorMessage'] = $errorMessage ;
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
