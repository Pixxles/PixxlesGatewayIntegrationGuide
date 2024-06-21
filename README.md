
## PIXXLES GATEWAY INTEGRATION GUIDE  

## Sale Transaction (with 3-D Secure) Direct


The Direct Integration works by allowing you to keep the Customer on your system throughout the checkout process, collecting the Customer’s payment details on your own secure server before sending the collected data to our Gateway for processing. This allows you to provide a smoother, more complete checkout process to the Customer.

In addition to basic sales processing, the Direct Integration can be used to perform other actions such as refunds and cancellations.


![Untitled Diagram drawio](https://user-images.githubusercontent.com/72015387/202174410-e6c2dfea-ffc7-49ab-b241-7cc6d7ea02f1.png)


  

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

 (1) Sample request:
```plaintext
action=SALE&merchantID=132779&amount=100&currencyCode=GBP&transactionUnique=wc_order_r54Vt5XpTfird-6374caad1a571&orderRef=36&customerName=John+Smith&customerCountryCode=GB&customerAddress=Flat+6+Primrose+Rise+347+Lavender+Road+Northampton+GB&customerCounty=&customerTown=Northampton&customerPostCode=NN17+8YG&customerEmail=test%40test.com&customerPhone=%2B442081264154&deviceChannel=browser&deviceIdentity=Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F107.0.0.0+Safari%2F537.36&deviceTimeZone=-120&deviceCapabilities=javascript&deviceScreenResolution=2752x1152x24&deviceAcceptContent=%2A%2F%2A&deviceAcceptEncoding=gzip%2C+deflate%2C+br&deviceAcceptLanguage=en-US&deviceAcceptCharset=&type=1&cardNumber=4929+4212+3460+0821&cardExpiryMonth=12&cardExpiryYear=22&cardCVV=356&remoteAddress=20.61.129.8&threeDSRedirectURL=https%3A%2F%2Flocalhost%2Fwordpress%2F%3Fwc-api%3Dwc_creditordebitcard&signature=69432830485aacef47f0bc6fb6ee39346e63af95648075af6863efcf2401eaa294730071e215208ac514426df52a8c2b119628d68bc87db1ac130dae51b74a63
```
Response
```plaintext
responseCode=65802&responseMessage=3DS+authentication+required&responseStatus=2&merchantCategoryCode=5965&merchantID=132779&caEnabled=N&rtsEnabled=Y&cftEnabled=N&cardCVVMandatory=Y&threeDSEnabled=Y&threeDSCheckPref=authenticated&threeDSPolicy=1&riskCheckEnabled=N&avscv2CheckEnabled=Y&addressCheckPref=matched&postcodeCheckPref=matched&cv2CheckPref=matched&surchargeEnabled=N&notifyEmailRequired=Y&customerReceiptsRequired=N&eReceiptsEnabled=N&processorType=acquirer&__wafRequestID=2022-11-16T11%3A34%3A20Z%7C9b1a06d269%7C185.16.137.242%7CtbXP2eQQGr&action=SALE&amount=100&currencyCode=826&transactionUnique=wc_order_r54Vt5XpTfird-6374caad1a571&orderRef=36&customerName=John+Smith&customerCountryCode=GB&customerAddress=Flat+6+Primrose+Rise+347+Lavender+Road+Northampton+GB&customerTown=Northampton&customerPostCode=NN17+8YG&customerEmail=test%40test.com&customerPhone=%2B442081264154&deviceChannel=browser&deviceIdentity=Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F107.0.0.0+Safari%2F537.36&deviceTimeZone=-120&deviceCapabilities=javascript&deviceScreenResolution=2752x1152x24&deviceAcceptContent=%2A%2F%2A&deviceAcceptEncoding=gzip%2C+deflate%2C+br&deviceAcceptLanguage=en-US&type=1&cardExpiryMonth=12&cardExpiryYear=22&remoteAddress=20.61.129.8&threeDSRedirectURL=https%3A%2F%2Flocalhost%2Fwordpress%2F%3Fwc-api%3Dwc_creditordebitcard&merchantCity=London&subMerchantID=262960000011819&countryCode=826&requestorChallengeIndicator=04&facilitatorName=PIX&facilitatorID=10084515&requestID=6374cabc457a9&customerPostcode=NN17+8YG&initiator=consumer&state=received&requestMerchantID=132779&processMerchantID=132779&paymentMethod=card&cardType=Visa+Credit&cardTypeCode=VC&cardScheme=Visa&cardSchemeCode=VC&cardIssuer=BARCLAYS+BANK+UK+PLC&cardIssuerCountry=United+Kingdom&cardIssuerCountryCode=GBR&cardFlags=71237632&cardNumberMask=492942%2A%2A%2A%2A%2A%2A0821&cardNumberValid=Y&xref=22111611HD34XM21XN03MZD&cardExpiryDate=1222&threeDSVersion=2.1.0&threeDSEnrolled=Y&threeDSXID=7a89449a-76ad-474b-ba87-bb3c20c557ab&threeDSRef=UDNLRVk6dHJhbnNhY3Rpb25JRD0yMDM5MjcyNDkmbWVyY2hhbnRJRD0xMzI3NzkmX19saWZlX189MTY2ODYwMDI2MQ%3D%3D&transactionID=203927249&threeDSResponseCode=65802&threeDSResponseMessage=3DS+authentication+required&threeDSVETimestamp=2022-11-16+11%3A34%3A21&threeDSCheck=not+checked&threeDSURL=https%3A%2F%2Facs.3ds-pit.com%2F%3Fmethod&threeDSRequest%5BthreeDSMethodData%5D=eyJ0aHJlZURTTWV0aG9kTm90aWZpY2F0aW9uVVJMIjoiaHR0cHM6Ly9sb2NhbGhvc3Qvd29yZHByZXNzLz93Yy1hcGk9d2NfY3JlZGl0b3JkZWJpdGNhcmQmdGhyZWVEU0Fjc1Jlc3BvbnNlPW1ldGhvZCIsInRocmVlRFNTZXJ2ZXJUcmFuc0lEIjoiN2E4OTQ0OWEtNzZhZC00NzRiLWJhODctYmIzYzIwYzU1N2FiIn0&threeDSDetails%5BtransID%5D=7a89449a-76ad-474b-ba87-bb3c20c557ab&threeDSDetails%5Bversion%5D=2.1.0&threeDSDetails%5Bversions%5D=2.1.0&threeDSDetails%5Bfallback%5D=N&threeDSDetails%5BissuerCountryCode%5D=826&threeDSDetails%5BacquirerCountryCode%5D=826&threeDSDetails%5Bpsd2Region%5D=Y&vcsResponseCode=0&vcsResponseMessage=Success+-+no+velocity+check+rules+applied&currencyExponent=2&amountRetained=0&timestamp=2022-11-16+11%3A34%3A21&merchantName=Pixxles+-+3DS&signature=3740fe070662a4d414c689afd42ae4772159bb0fe68a835d8774161651697686c8bfbae603f18237d8026ae7bec1fdd75b07232823a5808dd11a98ff390f464d
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

(2) Request with data from ACS

```plaintext
merchantID=132779&action=SALE&threeDSRef=UDNLRVk6dHJhbnNhY3Rpb25JRD0yMDM5MjcyNDkmbWVyY2hhbnRJRD0xMzI3NzkmX19saWZlX189MTY2ODYwMDI2MQ%3D%3D&threeDSResponse%5BthreeDSMethodData%5D=eyJ0aHJlZURTTWV0aG9kTm90aWZpY2F0aW9uVVJMIjoiaHR0cHM6Ly9sb2NhbGhvc3Qvd29yZHByZXNzLz93Yy1hcGk9d2NfY3JlZGl0b3JkZWJpdGNhcmQmdGhyZWVEU0Fjc1Jlc3BvbnNlPW1ldGhvZCIsInRocmVlRFNTZXJ2ZXJUcmFuc0lEIjoiN2E4OTQ0OWEtNzZhZC00NzRiLWJhODctYmIzYzIwYzU1N2FiIn0&signature=63a85d4b9a38e89f62e0bdc36d1ab8aa07128778e19a70281f1a3f3f2e6a62479cf40beded225c2792b13ca9e38882f273b35e2493b2d5ad32dfa59b8477f251
```

Response

```plaintext
responseCode=65802&responseMessage=3DS+authentication+required&responseStatus=2&cardCVVMandatory=Y&threeDSPolicy=1&threeDSVersion=2.1.0&merchantCategoryCode=5965&customerReceiptsRequired=N&cv2CheckPref=matched&addressCheckPref=matched&postcodeCheckPref=matched&threeDSCheckPref=authenticated&merchantID=132779&caEnabled=N&rtsEnabled=Y&cftEnabled=N&threeDSEnabled=Y&riskCheckEnabled=N&avscv2CheckEnabled=Y&surchargeEnabled=N&notifyEmailRequired=Y&eReceiptsEnabled=N&processorType=acquirer&deviceChannel=browser&deviceIdentity=Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F107.0.0.0+Safari%2F537.36&deviceTimeZone=-120&deviceCapabilities=javascript&deviceScreenResolution=2752x1152x24&deviceAcceptContent=%2A%2F%2A&deviceAcceptEncoding=gzip%2C+deflate%2C+br&deviceAcceptLanguage=en-US&threeDSRedirectURL=https%3A%2F%2Flocalhost%2Fwordpress%2F%3Fwc-api%3Dwc_creditordebitcard&subMerchantID=262960000011819&facilitatorName=PIX&facilitatorID=10084515&initiator=consumer&requestMerchantID=132779&processMerchantID=132779&paymentMethod=card&cardType=Visa+Credit&cardTypeCode=VC&cardScheme=Visa&cardSchemeCode=VC&cardIssuer=BARCLAYS+BANK+UK+PLC&cardIssuerCountry=United+Kingdom&cardIssuerCountryCode=GBR&cardFlags=71237632&cardNumberValid=Y&threeDSXID=79065d19-d996-4d12-909a-6b356ef26a7e&threeDSRequest%5Bcreq%5D=eyJtZXNzYWdlVHlwZSI6IkNSZXEiLCJtZXNzYWdlVmVyc2lvbiI6IjIuMS4wIiwidGhyZWVEU1NlcnZlclRyYW5zSUQiOiI3YTg5NDQ5YS03NmFkLTQ3NGItYmE4Ny1iYjNjMjBjNTU3YWIiLCJhY3NUcmFuc0lEIjoiZTdhZWViNWEtMjVkZi00MzUzLWJiY2UtYjgxOTQ0ZGMzNTY4IiwibWVzc2FnZUV4dGVuc2lvbiI6W3sibmFtZSI6InAzLTNkcy1jb250ZXh0IiwiaWQiOiJwMy0zZHMtY29udGV4dCIsImNyaXRpY2FsaXR5SW5kaWNhdG9yIjpmYWxzZSwiZGF0YSI6eyJhcmVxIjp7InRocmVlRFNSZXF1ZXN0b3JJRCI6IjEyMzQ1Njc4IiwidGhyZWVEU1JlcXVlc3Rvck5hbWUiOiJQaXh4bGVzIC0gM0RTIiwidGhyZWVEU1JlcXVlc3RvclVSTCI6Imh0dHBzOi8vd3d3LnBpeHhsZXMuY29tLyIsInRocmVlRFNTZXJ2ZXJSZWZOdW1iZXIiOiIzRFNfTE9BX1NFUl9OU09GXzAyMDIwMF8wMDIwMyIsInRocmVlRFNTZXJ2ZXJUcmFuc0lEIjoiN2E4OTQ0OWEtNzZhZC00NzRiLWJhODctYmIzYzIwYzU1N2FiIiwiYWNjdE51bWJlciI6IjQ5Mjk0MjEyMzQ2MDA4MjEiLCJjYXJkRXhwaXJ5RGF0ZSI6IjIyMTIiLCJ0aHJlZURTU2VydmVyT3BlcmF0b3JJRCI6IlRFU1QtT1BFUkFUT1IiLCJhY3F1aXJlckJJTiI6IjEyMzQ1NiIsImFjcXVpcmVyTWVyY2hhbnRJRCI6IjAwMDAwMDAwMDAwMDAwMCIsIm1lcmNoYW50TmFtZSI6IlBpeHhsZXMgLSAzRFMiLCJiaWxsQWRkckNvdW50cnkiOiI4MjYiLCJiaWxsQWRkckxpbmUxIjoiRmxhdCA2IFByaW1yb3NlIFJpc2UgMzQ3IExhdmVuZGVyIFJvYWQgTm9ydGhhbXB0b24iLCJiaWxsQWRkckxpbmUzIjoiTm9ydGhhbXB0b24iLCJiaWxsQWRkclBvc3RDb2RlIjoiTk4xNyA4WUciLCJlbWFpbCI6InRlc3RAdGVzdC5jb20iLCJob21lUGhvbmUiOnsiY2MiOiI0NCIsInN1YnNjcmliZXIiOiIyMDgxMjY0MTU0In0sImNhcmRob2xkZXJOYW1lIjoiSm9obiBTbWl0aCIsIm1jYyI6IjU5NjUiLCJtZXJjaGFudENvdW50cnlDb2RlIjoiODI2IiwibWVzc2FnZUNhdGVnb3J5IjoiMDEiLCJtZXNzYWdlVHlwZSI6IkFSZXEiLCJtZXNzYWdlVmVyc2lvbiI6IjIuMS4wIiwidGhyZWVEU1NlcnZlclVSTCI6Imh0dHBzOi8vZ2F0ZXdheS5waXh4bGVzY29ubmVjdC5jb20vcHVzaC9ub3RpZmljYXRpb24vP19xc189eEMyeXNzZkdZdWhpIiwidGhyZWVEU1JlcXVlc3RvckF1dGhlbnRpY2F0aW9uSW5kIjoiMDEiLCJwdXJjaGFzZUFtb3VudCI6IjEwMCIsInB1cmNoYXNlQ3VycmVuY3kiOiI4MjYiLCJwdXJjaGFzZUV4cG9uZW50IjoiMiIsInB1cmNoYXNlRGF0ZSI6IjIwMjIxMTE2MTEzNDQ3IiwidHJhbnNUeXBlIjoiMDEiLCJ0aHJlZURTQ29tcEluZCI6IlkiLCJicm93c2VyQWNjZXB0SGVhZGVyIjoiKi8qIiwiYnJvd3NlcklQIjoiMjAuNjEuMTI5LjgiLCJkZXZpY2VDaGFubmVsIjoiMDIiLCJub3RpZmljYXRpb25VUkwiOiJodHRwczovL2xvY2FsaG9zdC93b3JkcHJlc3MvP3djLWFwaT13Y19jcmVkaXRvcmRlYml0Y2FyZCZ0aHJlZURTQWNzUmVzcG9uc2U9Y2hhbGxlbmdlIiwiYnJvd3Nlckxhbmd1YWdlIjoiZW4tVVMiLCJicm93c2VySmF2YUVuYWJsZWQiOmZhbHNlLCJicm93c2VyQ29sb3JEZXB0aCI6IjI0IiwiYnJvd3NlclNjcmVlbkhlaWdodCI6IjExNTIiLCJicm93c2VyU2NyZWVuV2lkdGgiOiIyNzUyIiwiYnJvd3NlclRaIjoiLTEyMCIsImJyb3dzZXJVc2VyQWdlbnQiOiJNb3ppbGxhLzUuMCAoV2luZG93cyBOVCAxMC4wOyBXaW42NDsgeDY0KSBBcHBsZVdlYktpdC81MzcuMzYgKEtIVE1MLCBsaWtlIEdlY2tvKSBDaHJvbWUvMTA3LjAuMC4wIFNhZmFyaS81MzcuMzYifSwiYXJlcyI6eyJ0aHJlZURTU2VydmVyVHJhbnNJRCI6IjdhODk0NDlhLTc2YWQtNDc0Yi1iYTg3LWJiM2MyMGM1NTdhYiIsImFjc0NoYWxsZW5nZU1hbmRhdGVkIjoiTiIsImFjc0RlY0NvbkluZCI6Ik4iLCJhY3NPcGVyYXRvcklEIjoiQWNzT3BJZCA2Mzc0Y2FkODg2OTQ3IiwiYWNzUmVmZXJlbmNlTnVtYmVyIjoiM0RTX0xPQV9TRVJfTlNPRl8wMjAxMDBfMDAwNTEiLCJhY3NSZW5kZXJpbmdUeXBlIjp7ImFjc0ludGVyZmFjZSI6IjAyIiwiYWNzVWlUZW1wbGF0ZSI6IjA1In0sImFjc1RyYW5zSUQiOiJlN2FlZWI1YS0yNWRmLTQzNTMtYmJjZS1iODE5NDRkYzM1NjgiLCJhY3NVUkwiOiJodHRwczovL2Fjcy4zZHMtcGl0LmNvbS8iLCJhdXRoZW50aWNhdGlvblR5cGUiOiIwMSIsImRzUmVmZXJlbmNlTnVtYmVyIjoiM0RTX0xPQV9TRVJfTlNPRl8wMjAxMDBfMDAwNTEiLCJkc1RyYW5zSUQiOiI3OTA2NWQxOS1kOTk2LTRkMTItOTA5YS02YjM1NmVmMjZhN2UiLCJtZXNzYWdlVHlwZSI6IkFSZXMiLCJtZXNzYWdlVmVyc2lvbiI6IjIuMS4wIiwidHJhbnNTdGF0dXMiOiJDIiwibWVzc2FnZUV4dGVuc2lvbiI6W3sibmFtZSI6IkFDUyBEYXRhIiwiaWQiOiJBMDAwMDAwMDA0LWFjc0RhdGEiLCJjcml0aWNhbGl0eUluZGljYXRvciI6ZmFsc2UsImRhdGEiOnsiQTAwMDAwMDAwNC1hY3NEYXRhIjp7IndoaXRlbGlzdFN0YXR1cyI6IlkifX19XX19fV0sImNoYWxsZW5nZVdpbmRvd1NpemUiOiIwMiJ9&threeDSDetails%5BtransID%5D=79065d19-d996-4d12-909a-6b356ef26a7e&threeDSDetails%5BdsTransID%5D=79065d19-d996-4d12-909a-6b356ef26a7e&threeDSDetails%5BacsTransID%5D=e7aeeb5a-25df-4353-bbce-b81944dc3568&threeDSDetails%5BtransactionStatus%5D=C&threeDSDetails%5BtransactionStatusReason%5D=&threeDSDetails%5BauthenticationType%5D=01&threeDSDetails%5Beci%5D=&threeDSDetails%5BinteractionCounter%5D=&threeDSDetails%5BchallengeCancelationIndicator%5D=&threeDSDetails%5BchallengeMandatedIndicator%5D=N&threeDSDetails%5BcardholderInformation%5D=&threeDSDetails%5BwhitelistStatus%5D=Y&threeDSDetails%5BwhitelistStatusSource%5D=&threeDSDetails%5Bversion%5D=2.1.0&threeDSDetails%5Bversions%5D=2.1.0&threeDSDetails%5Bfallback%5D=N&threeDSDetails%5BissuerCountryCode%5D=826&threeDSDetails%5BacquirerCountryCode%5D=826&threeDSDetails%5Bpsd2Region%5D=Y&vcsResponseCode=0&vcsResponseMessage=Success+-+no+velocity+check+rules+applied&transactionID=203927249&xref=22111611HD34XM21XN03MZD&state=received&remoteAddress=20.61.129.8&action=SALE&type=1&countryCode=826&currencyCode=826&currencyExponent=2&currencySymbol=%C2%A3&amount=100&orderRef=36&transactionUnique=wc_order_r54Vt5XpTfird-6374caad1a571&cardNumberMask=492942%2A%2A%2A%2A%2A%2A0821&cardExpiryDate=1222&cardExpiryMonth=12&cardExpiryYear=22&customerName=John+Smith&customerAddress=Flat+6+Primrose+Rise+347+Lavender+Road+Northampton+GB&customerTown=Northampton&customerPostcode=NN17+8YG&customerCountryCode=GB&customerPhone=%2B442081264154&customerEmail=test%40test.com&threeDSEnrolled=Y&threeDSURL=https%3A%2F%2Facs.3ds-pit.com%2F&threeDSResponseCode=65802&threeDSResponseMessage=3DS+authentication+required&threeDSVETimestamp=2022-11-16+11%3A34%3A21&threeDSCheck=not+checked&threeDSRef=UDNLRVk6dHJhbnNhY3Rpb25JRD0yMDM5MjcyNDkmbWVyY2hhbnRJRD0xMzI3NzkmX19saWZlX189MTY2ODYwMDI4OA%3D%3D&requestID=6374cad70e7f3&threeDSCATimestamp=2022-11-16+11%3A34%3A47&amountRetained=0&timestamp=2022-11-16+11%3A34%3A48&__wafRequestID=2022-11-16T11%3A34%3A47Z%7C789c1e63b8%7C185.16.137.242%7Cwj3pvDPXMM&threeDSResponse%5BthreeDSMethodData%5D=eyJ0aHJlZURTTWV0aG9kTm90aWZpY2F0aW9uVVJMIjoiaHR0cHM6Ly9sb2NhbGhvc3Qvd29yZHByZXNzLz93Yy1hcGk9d2NfY3JlZGl0b3JkZWJpdGNhcmQmdGhyZWVEU0Fjc1Jlc3BvbnNlPW1ldGhvZCIsInRocmVlRFNTZXJ2ZXJUcmFuc0lEIjoiN2E4OTQ0OWEtNzZhZC00NzRiLWJhODctYmIzYzIwYzU1N2FiIn0&signature=9381d213e093b694aa9b1787ba596da298698cb4c49cda39ba2b32bd299a4228d282b3b7ffb2cf39903647b131b32534891a2351515be87b36ae8af2c8b9243a
```

(3) Request after 3DS challenge

```plaintext
merchantID=132779&action=SALE&threeDSRef=UDNLRVk6dHJhbnNhY3Rpb25JRD0yMDM5MjcyNDkmbWVyY2hhbnRJRD0xMzI3NzkmX19saWZlX189MTY2ODYwMDI4OA%3D%3D&threeDSResponse%5Bcres%5D=eyJ0aHJlZURTU2VydmVyVHJhbnNJRCI6IjdhODk0NDlhLTc2YWQtNDc0Yi1iYTg3LWJiM2MyMGM1NTdhYiIsImFjc1RyYW5zSUQiOiI2OWUyZjBiYy1hN2M2LTQwY2YtYjMxZi04MDIzN2JkMDc5N2QiLCJjaGFsbGVuZ2VDb21wbGV0aW9uSW5kIjoiWSIsIm1lc3NhZ2VUeXBlIjoiQ1JlcyIsIm1lc3NhZ2VWZXJzaW9uIjoiMi4xLjAiLCJ0cmFuc1N0YXR1cyI6IlkifQ&signature=e0826cedbe13ca29646bb3d073df6e5088278269f07c1e52f75e97c50c49d6467383e340db85fdf1218d5e91ac11bbb86230687d52adcd82f5d95ab2cfd33e05
```

Response

```plaintext
cardCVVMandatory=Y&threeDSPolicy=1&threeDSVersion=2.1.0&merchantCategoryCode=5965&customerReceiptsRequired=N&cv2CheckPref=matched&addressCheckPref=matched&postcodeCheckPref=matched&threeDSCheckPref=authenticated&merchantID=132779&caEnabled=N&rtsEnabled=Y&cftEnabled=N&threeDSEnabled=Y&riskCheckEnabled=N&avscv2CheckEnabled=Y&surchargeEnabled=N&notifyEmailRequired=Y&eReceiptsEnabled=N&processorType=acquirer&deviceChannel=browser&deviceIdentity=Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F107.0.0.0+Safari%2F537.36&deviceTimeZone=-120&deviceCapabilities=javascript&deviceScreenResolution=2752x1152x24&deviceAcceptContent=%2A%2F%2A&deviceAcceptEncoding=gzip%2C+deflate%2C+br&deviceAcceptLanguage=en-US&threeDSRedirectURL=https%3A%2F%2Flocalhost%2Fwordpress%2F%3Fwc-api%3Dwc_creditordebitcard&subMerchantID=262960000011819&facilitatorName=PIX&facilitatorID=10084515&initiator=consumer&requestMerchantID=132779&processMerchantID=132779&paymentMethod=card&cardType=Visa+Credit&cardTypeCode=VC&cardScheme=Visa&cardSchemeCode=VC&cardIssuer=BARCLAYS+BANK+UK+PLC&cardIssuerCountry=United+Kingdom&cardIssuerCountryCode=GBR&cardFlags=71237632&cardNumberValid=Y&threeDSXID=79065d19-d996-4d12-909a-6b356ef26a7e&threeDSRequest%5Bcreq%5D=eyJtZXNzYWdlVHlwZSI6IkNSZXEiLCJtZXNzYWdlVmVyc2lvbiI6IjIuMS4wIiwidGhyZWVEU1NlcnZlclRyYW5zSUQiOiI3YTg5NDQ5YS03NmFkLTQ3NGItYmE4Ny1iYjNjMjBjNTU3YWIiLCJhY3NUcmFuc0lEIjoiZTdhZWViNWEtMjVkZi00MzUzLWJiY2UtYjgxOTQ0ZGMzNTY4IiwibWVzc2FnZUV4dGVuc2lvbiI6W3sibmFtZSI6InAzLTNkcy1jb250ZXh0IiwiaWQiOiJwMy0zZHMtY29udGV4dCIsImNyaXRpY2FsaXR5SW5kaWNhdG9yIjpmYWxzZSwiZGF0YSI6eyJhcmVxIjp7InRocmVlRFNSZXF1ZXN0b3JJRCI6IjEyMzQ1Njc4IiwidGhyZWVEU1JlcXVlc3Rvck5hbWUiOiJQaXh4bGVzIC0gM0RTIiwidGhyZWVEU1JlcXVlc3RvclVSTCI6Imh0dHBzOi8vd3d3LnBpeHhsZXMuY29tLyIsInRocmVlRFNTZXJ2ZXJSZWZOdW1iZXIiOiIzRFNfTE9BX1NFUl9OU09GXzAyMDIwMF8wMDIwMyIsInRocmVlRFNTZXJ2ZXJUcmFuc0lEIjoiN2E4OTQ0OWEtNzZhZC00NzRiLWJhODctYmIzYzIwYzU1N2FiIiwiYWNjdE51bWJlciI6IjQ5Mjk0MjEyMzQ2MDA4MjEiLCJjYXJkRXhwaXJ5RGF0ZSI6IjIyMTIiLCJ0aHJlZURTU2VydmVyT3BlcmF0b3JJRCI6IlRFU1QtT1BFUkFUT1IiLCJhY3F1aXJlckJJTiI6IjEyMzQ1NiIsImFjcXVpcmVyTWVyY2hhbnRJRCI6IjAwMDAwMDAwMDAwMDAwMCIsIm1lcmNoYW50TmFtZSI6IlBpeHhsZXMgLSAzRFMiLCJiaWxsQWRkckNvdW50cnkiOiI4MjYiLCJiaWxsQWRkckxpbmUxIjoiRmxhdCA2IFByaW1yb3NlIFJpc2UgMzQ3IExhdmVuZGVyIFJvYWQgTm9ydGhhbXB0b24iLCJiaWxsQWRkckxpbmUzIjoiTm9ydGhhbXB0b24iLCJiaWxsQWRkclBvc3RDb2RlIjoiTk4xNyA4WUciLCJlbWFpbCI6InRlc3RAdGVzdC5jb20iLCJob21lUGhvbmUiOnsiY2MiOiI0NCIsInN1YnNjcmliZXIiOiIyMDgxMjY0MTU0In0sImNhcmRob2xkZXJOYW1lIjoiSm9obiBTbWl0aCIsIm1jYyI6IjU5NjUiLCJtZXJjaGFudENvdW50cnlDb2RlIjoiODI2IiwibWVzc2FnZUNhdGVnb3J5IjoiMDEiLCJtZXNzYWdlVHlwZSI6IkFSZXEiLCJtZXNzYWdlVmVyc2lvbiI6IjIuMS4wIiwidGhyZWVEU1NlcnZlclVSTCI6Imh0dHBzOi8vZ2F0ZXdheS5waXh4bGVzY29ubmVjdC5jb20vcHVzaC9ub3RpZmljYXRpb24vP19xc189eEMyeXNzZkdZdWhpIiwidGhyZWVEU1JlcXVlc3RvckF1dGhlbnRpY2F0aW9uSW5kIjoiMDEiLCJwdXJjaGFzZUFtb3VudCI6IjEwMCIsInB1cmNoYXNlQ3VycmVuY3kiOiI4MjYiLCJwdXJjaGFzZUV4cG9uZW50IjoiMiIsInB1cmNoYXNlRGF0ZSI6IjIwMjIxMTE2MTEzNDQ3IiwidHJhbnNUeXBlIjoiMDEiLCJ0aHJlZURTQ29tcEluZCI6IlkiLCJicm93c2VyQWNjZXB0SGVhZGVyIjoiKi8qIiwiYnJvd3NlcklQIjoiMjAuNjEuMTI5LjgiLCJkZXZpY2VDaGFubmVsIjoiMDIiLCJub3RpZmljYXRpb25VUkwiOiJodHRwczovL2xvY2FsaG9zdC93b3JkcHJlc3MvP3djLWFwaT13Y19jcmVkaXRvcmRlYml0Y2FyZCZ0aHJlZURTQWNzUmVzcG9uc2U9Y2hhbGxlbmdlIiwiYnJvd3Nlckxhbmd1YWdlIjoiZW4tVVMiLCJicm93c2VySmF2YUVuYWJsZWQiOmZhbHNlLCJicm93c2VyQ29sb3JEZXB0aCI6IjI0IiwiYnJvd3NlclNjcmVlbkhlaWdodCI6IjExNTIiLCJicm93c2VyU2NyZWVuV2lkdGgiOiIyNzUyIiwiYnJvd3NlclRaIjoiLTEyMCIsImJyb3dzZXJVc2VyQWdlbnQiOiJNb3ppbGxhLzUuMCAoV2luZG93cyBOVCAxMC4wOyBXaW42NDsgeDY0KSBBcHBsZVdlYktpdC81MzcuMzYgKEtIVE1MLCBsaWtlIEdlY2tvKSBDaHJvbWUvMTA3LjAuMC4wIFNhZmFyaS81MzcuMzYifSwiYXJlcyI6eyJ0aHJlZURTU2VydmVyVHJhbnNJRCI6IjdhODk0NDlhLTc2YWQtNDc0Yi1iYTg3LWJiM2MyMGM1NTdhYiIsImFjc0NoYWxsZW5nZU1hbmRhdGVkIjoiTiIsImFjc0RlY0NvbkluZCI6Ik4iLCJhY3NPcGVyYXRvcklEIjoiQWNzT3BJZCA2Mzc0Y2FkODg2OTQ3IiwiYWNzUmVmZXJlbmNlTnVtYmVyIjoiM0RTX0xPQV9TRVJfTlNPRl8wMjAxMDBfMDAwNTEiLCJhY3NSZW5kZXJpbmdUeXBlIjp7ImFjc0ludGVyZmFjZSI6IjAyIiwiYWNzVWlUZW1wbGF0ZSI6IjA1In0sImFjc1RyYW5zSUQiOiJlN2FlZWI1YS0yNWRmLTQzNTMtYmJjZS1iODE5NDRkYzM1NjgiLCJhY3NVUkwiOiJodHRwczovL2Fjcy4zZHMtcGl0LmNvbS8iLCJhdXRoZW50aWNhdGlvblR5cGUiOiIwMSIsImRzUmVmZXJlbmNlTnVtYmVyIjoiM0RTX0xPQV9TRVJfTlNPRl8wMjAxMDBfMDAwNTEiLCJkc1RyYW5zSUQiOiI3OTA2NWQxOS1kOTk2LTRkMTItOTA5YS02YjM1NmVmMjZhN2UiLCJtZXNzYWdlVHlwZSI6IkFSZXMiLCJtZXNzYWdlVmVyc2lvbiI6IjIuMS4wIiwidHJhbnNTdGF0dXMiOiJDIiwibWVzc2FnZUV4dGVuc2lvbiI6W3sibmFtZSI6IkFDUyBEYXRhIiwiaWQiOiJBMDAwMDAwMDA0LWFjc0RhdGEiLCJjcml0aWNhbGl0eUluZGljYXRvciI6ZmFsc2UsImRhdGEiOnsiQTAwMDAwMDAwNC1hY3NEYXRhIjp7IndoaXRlbGlzdFN0YXR1cyI6IlkifX19XX19fV0sImNoYWxsZW5nZVdpbmRvd1NpemUiOiIwMiJ9&threeDSDetails%5BtransID%5D=79065d19-d996-4d12-909a-6b356ef26a7e&threeDSDetails%5BdsTransID%5D=79065d19-d996-4d12-909a-6b356ef26a7e&threeDSDetails%5BacsTransID%5D=69e2f0bc-a7c6-40cf-b31f-80237bd0797d&threeDSDetails%5BtransactionStatus%5D=Y&threeDSDetails%5BtransactionStatusReason%5D=&threeDSDetails%5BauthenticationType%5D=01&threeDSDetails%5BauthenticationValue%5D=AAABB5NWYSAFKRAhQlZhAAAAAAA%3D&threeDSDetails%5Beci%5D=05&threeDSDetails%5BinteractionCounter%5D=01&threeDSDetails%5BchallengeCancelationIndicator%5D=&threeDSDetails%5BchallengeMandatedIndicator%5D=&threeDSDetails%5BcardholderInformation%5D=&threeDSDetails%5BwhitelistStatus%5D=N&threeDSDetails%5BwhitelistStatusSource%5D=&threeDSDetails%5Bversion%5D=2.1.0&threeDSDetails%5Bversions%5D=2.1.0&threeDSDetails%5Bfallback%5D=N&threeDSDetails%5BissuerCountryCode%5D=826&threeDSDetails%5BacquirerCountryCode%5D=826&threeDSDetails%5Bpsd2Region%5D=Y&vcsResponseCode=0&vcsResponseMessage=Success+-+no+velocity+check+rules+applied&transactionID=203927249&xref=22111611HD34XM21XN03MZD&state=captured&remoteAddress=20.61.129.8&action=SALE&type=1&countryCode=826&currencyCode=826&currencyExponent=2&currencySymbol=%C2%A3&amount=100&orderRef=36&transactionUnique=wc_order_r54Vt5XpTfird-6374caad1a571&cardNumberMask=492942%2A%2A%2A%2A%2A%2A0821&cardExpiryDate=1222&cardExpiryMonth=12&cardExpiryYear=22&customerName=John+Smith&customerAddress=Flat+6+Primrose+Rise+347+Lavender+Road+Northampton+GB&customerTown=Northampton&customerPostcode=NN17+8YG&customerCountryCode=GB&customerPhone=%2B442081264154&customerEmail=test%40test.com&threeDSEnrolled=Y&threeDSURL=https%3A%2F%2Facs.3ds-pit.com%2F&threeDSResponseCode=0&threeDSResponseMessage=Successfully+authenticated&threeDSVETimestamp=2022-11-16+11%3A34%3A21&threeDSCATimestamp=2022-11-16+11%3A35%3A22&threeDSCheck=authenticated&responseCode=0&responseMessage=AUTHCODE%3A117207&threeDSResponse%5Bcres%5D=eyJ0aHJlZURTU2VydmVyVHJhbnNJRCI6IjdhODk0NDlhLTc2YWQtNDc0Yi1iYTg3LWJiM2MyMGM1NTdhYiIsImFjc1RyYW5zSUQiOiI2OWUyZjBiYy1hN2M2LTQwY2YtYjMxZi04MDIzN2JkMDc5N2QiLCJjaGFsbGVuZ2VDb21wbGV0aW9uSW5kIjoiWSIsIm1lc3NhZ2VUeXBlIjoiQ1JlcyIsIm1lc3NhZ2VWZXJzaW9uIjoiMi4xLjAiLCJ0cmFuc1N0YXR1cyI6IlkifQ&requestID=6374caf9597e7&threeDSAuthenticated=Y&threeDSECI=05&threeDSCAVV=AAABB5NWYSAFKRAhQlZhAAAAAAA%3D&authorisationCode=117207&responseStatus=0&amountApproved=100&amountReceived=100&amountRetained=100&avscv2ResponseCode=222100&avscv2ResponseMessage=ALL+MATCH&avscv2AuthEntity=merchant+host&cv2Check=matched&addressCheck=matched&postcodeCheck=matched&acquirerTransactionID=6374cafa3d48d&acquirerResponseCode=0&acquirerResponseMessage=AUTHCODE%3A117207&timestamp=2022-11-16+11%3A35%3A22&__wafRequestID=2022-11-16T11%3A35%3A21Z%7C3c076f5731%7C185.16.137.242%7CpkljCrpbi8&signature=92eb7fe7dea1a4d2fe1814966292baaaf0297a95369ffe2418110173bc13f9a93ac77d6a2858007d513ea86b3d3205d5e253cf0bf119707e5102b07e57cde435
```

## Sample Signature Calculation

Example Transaction:

            var key = "DontTellAnyone";
            var transaction = new Dictionary<string, string>()
            {
                { "merchantID", "100001" },
                { "action", "SALE" },
                { "type", "1" },
                { "currencyCode", "826" },
                { "amount", "2691" },
                { "transactionUnique", "55f025addd3c2" },
                { "orderRef", "Signature Test" },
                { "cardNumber", "4929 4212 3460 0821" },
                { "cardExpiryDate", "1213" },
            };
	    
**The transaction used for signature calculation must not include any 'signature' field as this will be added after signing when its value is known.**

Step 1 - Sort transaction values by their field name Transaction fields must be in ascending field name order according to their numeric ASCII value.

Step 2 - Create url encoded string from sorted fields Use RFC 1738 and the application/x-www-form-urlencoded media type, which implies that spaces are encoded as plus (+) signs.

Step 3 - Normalise all line endings in the url encoded string Convert all CR NL, NL CR, CR character sequences to a single NL character.

Step 4 - Append your signature key to the normalised string The signature key is appended to the normalised string with no separator characters

Step 5 - Hash the string using the SHA-512 algorithm The normalised string is hashed to a more compact value using the secure SHA-512 hashing algorithm

Step 6 - Add the signature to the transaction form or post data The signature should be sent as part of the transaction in a field called 'signature'.

	request += $"{signature}={hash}"

Example code (C#)

```cs
 ///<summary>
 /// Sign the given array of data.
 /// 
 /// This method will return the correct signature for the dictionary
 /// </summary>
 /// <param name="fields"> Dictionary<string, string> containing the fields to be signed. </params>
 /// <param name="secret"> secret used to create the signature </params>
 /// <returns>
 /// Signature calculated from the provided fields.
 /// </returns>
 public string Sign(IDictionary<string, string> fields, string secret)
 {
     // HttpUtility returns UPPERCASE percent encoded characeters
     var encodedFields = fields.OrderBy(f => (
         f.Key.Contains("[") ? f.Key.Replace("[", "0").Substring(0, f.Key.IndexOf("[")) : f.Key),
         StringComparer.Ordinal);
     var encodedBody = GetUrlEncodedBody(encodedFields);

     encodedBody = encodedBody.Replace("%0D", "");

     var bytes = Encoding.UTF8.GetBytes(encodedBody + secret);

     string signature;

     using (var hash = SHA512.Create())
     {
         var hashedInputBytes = hash.ComputeHash(bytes);

         signature = BitConverter.ToString(hashedInputBytes).Replace("-", "").ToLower();
     }

     return signature;
 }

 private string GetUrlEncodedBody(IEnumerable<KeyValuePair<string, string>> fields)
 {
     var rtn = string.Join("&",
         fields.Select(f => string.Format("{0}={1}", WebUtility.UrlEncode(f.Key), WebUtility.UrlEncode(f.Value))));

     return rtn.Replace("!", "%21").Replace("*", "%2A").Replace("(", "%28").Replace(")", "%29");
 }
```


## Files' descriptions  

-  `Direct/InitialRequest.md` – Initial Request

-  `Direct/InitialResponse.md` – Initial Response

-  `Direct/3DS-initial.php` – Initial request

-  `Direct/3DSTester.php` – Continue request

-  `Direct/step1.html` – ACS request 1

-  `Direct/step2.html` – ACS request 2

