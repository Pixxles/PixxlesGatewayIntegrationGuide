## Hosted Integration
The Hosted Integration method makes it easy to add secure payment processing to your ecommerce business. You can use this method if 3-D Direct integration does not work for you due to compliance reasons.
The Hosted Integration method works by redirecting the Customer to our Gateway’s Hosted Payment Page, which will collect the Customer’s payment details and process the payment before
redirecting the Customer back to a page on your website, letting you know the payment outcome.
This allows you the quickest path to integrating with the Gateway.

![hosted](https://github.com/Pixxles/PixxlesGatewayIntegrationGuide/assets/72015387/23778638-4420-4ec8-8820-af71fe86e99b)


## Hosted HTTP Requests
When using the Hosted Integration, the request must be sent from the Customer’s web browser as
the response will be a HTML Hosted Payment Page, used to collect the Customer’s details.
The format of the request is designed so that it can be sent using a standard HTML form with the
data in hidden form fields. The browser will then automatically encode the request correctly
according to application/x-www-form-urlencoded format.

When the Hosted Payment Page has been completed and the payment processed, the
Customer’s browser will be automatically redirected to the URL provided via the redirectURL
field. The response will be returned to this page in application/x-www-form-urlencoded
format, using a HTTP POST request.

1.1 Initial Request

A request can be sent to the Pixxlex by submitting a HTTP POST request to the Hosted integration URL provided.

The request should have a Content-Type: application/x-www-form-urlencoded HTTP header and the request should be name, value pairs URL encoded as per RFC 1738.

When configured, each request will need to be ‘signed’ by providing a signature field containing a hash generated from the combination of the serialised request and this signing secret phrase. On receipt, Pixxles will then re-generate the hash and compare it with the one sent. If the two hashes are different, then the request received must not be the same as that sent and so the contents must have been tampered with and the transaction will be aborted, and an error response is returned. Pixxles will also return hash of the response message in the returned signature field, allowing you to create your own hash of the response (minus the signature field) and verify that the hashes match.


## Redirect URL
The redirectURL request field is used to provide the URL of a webpage on your server.
For the Hosted Integration, the Customers browser will load this URL when the Hosted Payment
Page has completed and can be used to continue the payment journey on your website. The URL
will be loaded using a HTTP POST request containing transaction response data allowing you to
tailor the journey depending on the outcome of the transaction.

## Sample Signature Calculation

Example Transaction:

            var key = "DontTellAnyone";
            var transaction = new Dictionary<string, string>()
            {
                { "action", "SALE" },
                { "amount", "1000" },
                { "currencyCode", "GBP" },
                { "customerAddress", "" },
                { "customerCountryCode", "GB" },
                { "customerCounty", "" },
                { "customerEmail", "examle@email.com" },
                { "customerName", "" },
                { "customerPhone", "" },
                { "customerPostCode", "" },                
                { "merchantID", "" },
                { "orderRef", "55f025addd3c2" },
                { "redirectURL", "https://example.com" },
                { "transactionUnique", "55f025addd3c2" },
                { "type", "1" },
            };
	    
**The transaction used for signature calculation must not include any 'signature' field as this will be added after signing when its value is known.**

Step 1 - Sort transaction values by their field name. Transaction fields must be in ascending field name order according to their numeric ASCII value.

Step 2 - Create url encoded string from sorted fields. Use RFC 1738 and the application/x-www-form-urlencoded media type, which implies that spaces are encoded as plus (+) signs.

Step 3 - Normalise all line endings in the URL encoded string. Convert all CR NL, NL CR, CR character sequences to a single NL character.

Step 4 - Append your signature key to the normalised string. The signature key is appended to the normalised string with no separator characters.

Step 5 - Hash the string using the SHA-512 algorithm. The normalised string is hashed to a more compact value using the secure SHA-512 hashing algorithm.

Step 6 - Add the signature to the transaction form or post data. The signature should be sent as part of the transaction in a field called 'signature'.

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

-  `Hosted/hosted.html` – Hosted form

