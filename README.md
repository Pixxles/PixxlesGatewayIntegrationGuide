
## PIXXLES GATEWAY INTEGRATION GUIDE  

## Sale Transaction (with 3-D Secure) Direct


The Direct Integration works by allowing you to keep the Customer on your system throughout the checkout process, collecting the Customer’s payment details on your own secure server before sending the collected data to our Gateway for processing. This allows you to provide a smoother, more complete checkout process to the Customer.

In addition to basic sales processing, the Direct Integration can be used to perform other actions such as refunds and cancellations.

  ![Untitled Diagram drawio](https://user-images.githubusercontent.com/72015387/200515722-6d3ad3ae-06b7-41a1-9adf-53ac87a3fcb9.png)



  

## Request Flow


1. You should send an initial request, as described in *InitialRequest.json or  Pixxles.postman_collection.json* file , to the Pixxles containing the payment details, device details and any required threeDSOptions. This request must include your callback page, as described above, in the **threeDSRedirectURL** field. 
2. If you receive a responseCode of **65802** then go to step 3 else display the results of the finished transaction as indicated by the responseCode and responseStatus as normal. 
3. You must store the received threeDSRef value as it will be needed by the continuation request in step 6. 
4. You should then send the contents of the received threeDSRequest to the 3-D Secure Access Control Server (ACS) at the received threeDSURL as described in section **1.2**. 
5. Once the ACS has completed the challenge then it will return the outcome back to your callback page as originally provided in step 1, as described in section 1.3.
6. You must then send a continuation request, as described in section 1.3, to the Pixxles containing the threeDSRef as stored in step 3 and a threeDSResponse containing the data received from the ACS in step 5. 
7.  As there can be multiple challenges you must now repeat the sequence from step 2


1.1 Initial Request

A request can be sent to the Pixxlex by submitting a HTTP POST request to the integration URL provided.
  
The request should have a Content-Type: application/x-www-form-urlencoded HTTP header and the request should be name, value pairs URL encoded as per RFC 1738

When configured, each request will need to be ‘signed’ by providing a signature field containing a hash generated from the combination of the serialised request and this signing secret phrase. On receipt, the Pixxles will then re-generate the hash and compare it with the one sent. If the two hashes are different, then the request received must not be the same as that sent and so the contents must have been tampered with and the transaction will be aborted, and an error response is returned. The Pixxles will also return hash of the response message in the returned signature field, allowing you to create your own hash of the response (minus the signature field) and verify that the hashes match

Sample request:
```plaintext
action=SALE&merchantID=132779&amount=200&currencyCode=GBP&transactionUnique=wc_order_BCJ725d5WNqFl-63699036a0a74&orderRef=15&customerName=John+Smith&customerCountryCode=GB&customerAddress=Flat+6+Primrose+Rise+347+Lavender+Road+Northampton+GB&customerCounty=&customerTown=Northampton&customerPostCode=NN17+8YG&customerEmail=test%40test.com&customerPhone=%2B442081264154&walletStore=Y&walletEnabled=Y&walletRequired=Y&deviceChannel=browser&deviceIdentity=Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F107.0.0.0+Safari%2F537.36&deviceTimeZone=-120&deviceCapabilities=javascript&deviceScreenResolution=2752x1152x24&deviceAcceptContent=%2A%2F%2A&deviceAcceptEncoding=gzip%2C+deflate%2C+br&deviceAcceptLanguage=en-US&deviceAcceptCharset=&type=1&cardNumber=4929+4212+3460+0821&cardExpiryMonth=12&cardExpiryYear=22&cardCVV=356&remoteAddress=%3A%3A1&threeDSRedirectURL=http%3A%2F%2Flocalhost%2Fwordpress%2F%3Fwc-api%3Dwc_creditordebitcard&signature=d1a5ef83e5772e90a01fab07f0f7ca50a68a39e81c21e4fbeb91267867fc9368042410f59b68402cec4864b6f28708e2d6b6c06acaa9b1bfc69dc613344e7be0
```
Response
```plaintext
responseCode=65802&responseMessage=3DS+authentication+required&responseStatus=2&merchantCategoryCode=5965&merchantID=132779&caEnabled=N&rtsEnabled=Y&cftEnabled=N&cardCVVMandatory=Y&threeDSEnabled=Y&threeDSCheckPref=authenticated&threeDSPolicy=1&riskCheckEnabled=N&avscv2CheckEnabled=Y&addressCheckPref=matched&postcodeCheckPref=matched&cv2CheckPref=matched&surchargeEnabled=N&notifyEmailRequired=Y&customerReceiptsRequired=N&eReceiptsEnabled=N&processorType=acquirer&__wafRequestID=2022-11-07T23%3A20%3A13Z%7C7d2f9fe709%7C185.16.138.249%7CylF2PEWNTB&action=SALE&amount=200&currencyCode=826&transactionUnique=wc_order_BCJ725d5WNqFl-636992a67f02e&orderRef=15&customerName=John+Smith&customerCountryCode=GB&customerAddress=Flat+6+Primrose+Rise+347+Lavender+Road+Northampton+GB&customerTown=Northampton&customerPostCode=NN17+8YG&customerEmail=test%40test.com&customerPhone=%2B442081264154&walletStore=Y&walletEnabled=Y&walletRequired=Y&deviceChannel=browser&deviceIdentity=Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F107.0.0.0+Safari%2F537.36&deviceTimeZone=-120&deviceCapabilities=javascript&deviceScreenResolution=2752x1152x24&deviceAcceptContent=%2A%2F%2A&deviceAcceptEncoding=gzip%2C+deflate%2C+br&deviceAcceptLanguage=en-US&type=1&cardExpiryMonth=12&cardExpiryYear=22&remoteAddress=20.61.129.8&threeDSRedirectURL=http%3A%2F%2Flocalhost%2Fwordpress%2F%3Fwc-api%3Dwc_creditordebitcard&merchantCity=London&subMerchantID=262960000011819&countryCode=826&requestorChallengeIndicator=04&facilitatorName=PIX&facilitatorID=10084515&requestID=636992ad0b96f&customerPostcode=NN17+8YG&initiator=consumer&state=received&requestMerchantID=132779&processMerchantID=132779&paymentMethod=card&cardType=Visa+Credit&cardTypeCode=VC&cardScheme=Visa&cardSchemeCode=VC&cardIssuer=BARCLAYS+BANK+UK+PLC&cardIssuerCountry=United+Kingdom&cardIssuerCountryCode=GBR&cardFlags=71237632&cardNumberMask=492942%2A%2A%2A%2A%2A%2A0821&cardNumberValid=Y&xref=22110723XY20RN13LH76KJG&cardExpiryDate=1222&threeDSVersion=2.1.0&threeDSEnrolled=Y&threeDSXID=422751f0-f909-496a-bfe3-f25d111aff0a&threeDSRef=UDNLRVk6dHJhbnNhY3Rpb25JRD0yMDIzNjE0NjAmbWVyY2hhbnRJRD0xMzI3NzkmX19saWZlX189MTY2Nzg2NTAxMw%3D%3D&transactionID=202361460&threeDSResponseCode=65802&threeDSResponseMessage=3DS+authentication+required&threeDSVETimestamp=2022-11-07+23%3A20%3A13&threeDSCheck=not+checked&threeDSURL=https%3A%2F%2Facs.3ds-pit.com%2F%3Fmethod&threeDSRequest%5BthreeDSMethodData%5D=eyJ0aHJlZURTTWV0aG9kTm90aWZpY2F0aW9uVVJMIjoiaHR0cDovL2xvY2FsaG9zdC93b3JkcHJlc3MvP3djLWFwaT13Y19jcmVkaXRvcmRlYml0Y2FyZCZ0aHJlZURTQWNzUmVzcG9uc2U9bWV0aG9kIiwidGhyZWVEU1NlcnZlclRyYW5zSUQiOiI0MjI3NTFmMC1mOTA5LTQ5NmEtYmZlMy1mMjVkMTExYWZmMGEifQ&threeDSDetails%5BtransID%5D=422751f0-f909-496a-bfe3-f25d111aff0a&threeDSDetails%5Bversion%5D=2.1.0&threeDSDetails%5Bversions%5D=2.1.0&threeDSDetails%5Bfallback%5D=N&threeDSDetails%5BissuerCountryCode%5D=826&threeDSDetails%5BacquirerCountryCode%5D=826&threeDSDetails%5Bpsd2Region%5D=Y&vcsResponseCode=0&vcsResponseMessage=Success+-+no+velocity+check+rules+applied&currencyExponent=2&amountRetained=0&timestamp=2022-11-07+23%3A20%3A13&merchantName=Pixxles+-+3DS&signature=a7218ab58d3ce80ec0b936b375b19d896b0a07198cd10aaae0a1000bdf91753e7089bdcf6ee0c9a2dbf13893c2c53b398ef9162b4809e0267c058c476e737905
```
You will need to implement a callback page on your web server which the ACS can redirect the Cardholder’s browser to on completion of any challenges. You will need to provide the address of this page to the Pixxles in your initial payment request via the **threeDSRedirectURL** field.

The direct integration uses two complex fields to pass data between the 3-D Secure Access Control Server (ACS) and the Pixxles. The **threeDSRequest** is a record whose name/value properties must be sent via a HTTP POST request to the ACS. The corresponding threeDSResponse field should be returned to the Pixxles and must be a record containing name/value properties taken from the HTTP POST received from the ACS when it redirects the Cardholder’s browser back to your callback page on completion of any challenge.

1.2 Verify Enrolment

If the Gateway determines that the transaction is eligible, it will respond with a responseCode of **65802** (3DS AUTHENTICATION REQUIRED) and included in the response will be a threeDSRef field, a threeDSReqest field and a threeDSURL field. The threeDSRequest field is a record whose name/value properties must be sent, using a HTTP POST request, to the 3-D Secure Access Control Server (ACS) at the URL provided by the 
threeDSURL field. This is usually achieved via means of a hidden HTML input fields in a form rendered within an IFRAME displayed on the Cardholder’s browser and then submitted using 
JavaScript. The IFRAME must be of sufficient size to display the challenge screen, however, if the threeDSRequest contains a threeDSMethodData component, then the challenge is invisible, and a small hidden IFRAME can be used instead. You must store the value of the threeDSRef field for use in the continuation request

1.3 Continuation Request (Check Authentication and Authorise)
On completion of the 3-D Secure authentication the ACS will send the challenge results to you callback page, as originally specified using the threeDSRedirectURL field in the initial request. The data will be received via means of a HTTP POST request and the contents of this POST request should be returned to the Pixxles unmodified as name/value properties in the threeDSResponse field together with the threeDSRef received in the initial response.This new request will check the authentication results and either respond with the details for a further challenge, send the transaction to the Acquirer for approval, or abort the transaction, depending on the authentication result and your preferences, either sent in the threeDSCheckPref field  or set in the Pixxles.

ACS example

Request with data from ACS

```plaintext
merchantID=132779&action=SALE&acs=1&threeDSRef=UDNLRVk6dHJhbnNhY3Rpb25JRD0yMDI5NTgzODImbWVyY2hhbnRJRD0xMzI3NzkmX19saWZlX189MTY2ODE3NjI0Nw%3D%3D&threeDSResponse%5BthreeDSMethodData%5D=eyJ0aHJlZURTTWV0aG9kTm90aWZpY2F0aW9uVVJMIjoiaHR0cDovL2xvY2FsaG9zdC93b3JkcHJlc3MvP3djLWFwaT13Y19jcmVkaXRvcmRlYml0Y2FyZCZ0aHJlZURTQWNzUmVzcG9uc2U9bWV0aG9kIiwidGhyZWVEU1NlcnZlclRyYW5zSUQiOiI3ZmE3MjA0Ni1mOTU0LTQ5MjEtODM2OS1lZjIyNzQ1ZGI2MDUifQ&signature=14c41ecb303d48917f306d354a4ed69578ac48113f4cecfd1e6472698c1568bc1377445f2236be107a2266156a9ee02a9db7ab73c9c47473e0501f9d3b3d727e
```

Response

```plaintext
responseCode=65803&responseMessage=3DS+DECLINED&responseStatus=2&cardCVVMandatory=Y&threeDSPolicy=1&threeDSVersion=2.1.0&merchantCategoryCode=5965&customerReceiptsRequired=N&cv2CheckPref=matched&addressCheckPref=matched&postcodeCheckPref=matched&threeDSCheckPref=authenticated&merchantID=132779&caEnabled=N&rtsEnabled=Y&cftEnabled=N&threeDSEnabled=Y&riskCheckEnabled=N&avscv2CheckEnabled=Y&surchargeEnabled=N&notifyEmailRequired=Y&eReceiptsEnabled=N&processorType=acquirer&deviceChannel=browser&deviceIdentity=Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F107.0.0.0+Safari%2F537.36&deviceTimeZone=-120&deviceCapabilities=javascript&deviceScreenResolution=2752x1152x24&deviceAcceptContent=%2A%2F%2A&deviceAcceptEncoding=gzip%2C+deflate%2C+br&deviceAcceptLanguage=en-US&threeDSRedirectURL=http%3A%2F%2Flocalhost%2Fwordpress%2F%3Fwc-api%3Dwc_creditordebitcard&subMerchantID=262960000011819&facilitatorName=PIX&facilitatorID=10084515&initiator=consumer&requestMerchantID=132779&processMerchantID=132779&paymentMethod=card&cardType=Visa+Credit&cardTypeCode=VC&cardScheme=Visa&cardSchemeCode=VC&cardIssuer=BARCLAYS+BANK+UK+PLC&cardIssuerCountry=United+Kingdom&cardIssuerCountryCode=GBR&cardFlags=71237632&cardNumberValid=Y&threeDSXID=7fa72046-f954-4921-8369-ef22745db605&threeDSRequest%5BthreeDSMethodData%5D=eyJ0aHJlZURTTWV0aG9kTm90aWZpY2F0aW9uVVJMIjoiaHR0cDovL2xvY2FsaG9zdC93b3JkcHJlc3MvP3djLWFwaT13Y19jcmVkaXRvcmRlYml0Y2FyZCZ0aHJlZURTQWNzUmVzcG9uc2U9bWV0aG9kIiwidGhyZWVEU1NlcnZlclRyYW5zSUQiOiI3ZmE3MjA0Ni1mOTU0LTQ5MjEtODM2OS1lZjIyNzQ1ZGI2MDUifQ&threeDSDetails%5BtransID%5D=7fa72046-f954-4921-8369-ef22745db605&threeDSDetails%5Bversion%5D=2.1.0&threeDSDetails%5Bversions%5D=2.1.0&threeDSDetails%5Bfallback%5D=N&threeDSDetails%5BissuerCountryCode%5D=826&threeDSDetails%5BacquirerCountryCode%5D=826&threeDSDetails%5Bpsd2Region%5D=Y&threeDSDetails%5BtransactionStatus%5D=E&threeDSDetails%5BtransactionStatusReason%5D=203%3A+Data+element+is+invalid.+notificationURL&vcsResponseCode=0&vcsResponseMessage=Success+-+no+velocity+check+rules+applied&transactionID=202958382&xref=22111113HS47FF27BP15TXH&state=finished&remoteAddress=109.237.2.126&action=SALE&type=1&countryCode=826&currencyCode=826&currencyExponent=2&currencySymbol=%C2%A3&amount=100&orderRef=25&transactionUnique=wc_order_OANPWnhF0g6wb-636e525d8140e&cardNumberMask=492942%2A%2A%2A%2A%2A%2A0821&cardExpiryDate=1222&cardExpiryMonth=12&cardExpiryYear=22&customerName=John+Smith&customerAddress=Flat+6+Primrose+Rise+347+Lavender+Road+Northampton+GB&customerTown=Northampton&customerPostcode=NN17+8YG&customerCountryCode=GB&customerPhone=%2B442081264154&customerEmail=test%40test.com&threeDSEnrolled=Y&threeDSURL=https%3A%2F%2Facs.3ds-pit.com%2F%3Fmethod&threeDSResponseCode=65800&threeDSResponseMessage=3DS+authentication+error&threeDSVETimestamp=2022-11-11+13%3A47%3A27&threeDSCheck=not+known&threeDSResponse%5BthreeDSMethodData%5D=eyJ0aHJlZURTTWV0aG9kTm90aWZpY2F0aW9uVVJMIjoiaHR0cDovL2xvY2FsaG9zdC93b3JkcHJlc3MvP3djLWFwaT13Y19jcmVkaXRvcmRlYml0Y2FyZCZ0aHJlZURTQWNzUmVzcG9uc2U9bWV0aG9kIiwidGhyZWVEU1NlcnZlclRyYW5zSUQiOiI3ZmE3MjA0Ni1mOTU0LTQ5MjEtODM2OS1lZjIyNzQ1ZGI2MDUifQ&requestID=636e528b95e2a&threeDSAuthenticated=E&threeDSCATimestamp=2022-11-11+13%3A47%3A56&amountRetained=0&timestamp=2022-11-11+13%3A47%3A57&__wafRequestID=2022-11-11T13%3A47%3A54Z%7C2c8827a31f%7C185.16.137.242%7ChWplCKEzs2&acs=1&signature=39b9949833eb690297f3506ae5d7c4a2b9a2b7b538378422cfaad9c4708b3b27303db498074cfc9a4f3fc48022174f236f32b11b1b87a35ce35cc00ff4707bf5
```


## Files' descriptions  

-  `Direct/InitialRequest.json` – Initial Request

-  `Direct/InitialResponse.json` – Initial Response

-  `Direct/3DSTester.php` – Payment request 1

-  `Direct/3DSv2-test-101.php` – Payment request 2

-  `Direct/step1.html` – ACS request 1

-  `Direct/step2.html` – ACS request 2

