# Pixxles Recurring API
## Create an Agreement
In order to create an RT agreement, **Direct Integration** is required first: https://github.com/Pixxles/PixxlesGatewayIntegrationGuide

The initial request is similar to the one in the documentation, with a few differences: "action" should always be "VERIFY", "amount" should always be "0", and "rt*" type of fields need to be passed. Below is an example of an initial request that creates an RT agreement.

### Request Example
```json
{
"action": "VERIFY",  //should be always VERIFY
"amount": "0", // should be always 0
"cardCVV": "356",
"cardExpiryMonth": "12",
"cardExpiryYear": "24",
"cardNumber": "4929 4212 3460 0821",
"currencyCode": "GBP",
"customerAddress": "Flat 6 Primrose Rise 347 Lavender Road Northampton GB",
"customerCountryCode": "GB",
"customerEmail": "test@test.com",
"customerName": "John Smith",
"customerPhone": "442081264154",
"customerPostcode": "NN17 8YG",
"customerTown": "Northampton",
"deviceAcceptCharset": "",
"deviceAcceptContent":
"text/html:application/xhtml+xml:application/xml;q=0.9:image/avif:image/webp:image/apng:*/*;q=0.8:application/signed-exchange;v=b3;q=0.9",
"deviceAcceptEncoding": "gzip: deflate: br",
"deviceAcceptLanguage": "en-US",
"deviceCapabilities": "javascript",
"deviceChannel": "browser",
"deviceIdentity": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML: like Gecko) Chrome/95.0.4638.54 Safari/537.36",
"deviceScreenResolution": "1920x1080x24",
"deviceTimeZone": "-120",
"merchantID": "100001",
"orderRef": "43",
"remoteAddress": "192.168.1.1",
"threeDSRedirectURL": "https://localhost/acs",
"transactionUnique": "Guid.NewGuid().ToString()",
"type": "1",
"rtCycleAmount":"200", // required, repeated transaction amount, the last two digits are always decimal places, so in this example it is 2.00
"rtAgreementType":"recurring", // required, should be always recurring
"rtCycleDuration":"1", // required, number of duration units
"rtCycleDurationUnit":"day", // required, accepted values: day, week, month, year
"rtCycleCount":"5", //required, denotes the number of durationUnits until the agremeent is completed; put 0 if Agreement should never end
"rtName": "agreementName", //optional, but should be unique. Will be generated automatically if doesn't contain a value
"rtDescription": "Order for##", //optional. Will be generated automaticly if doesn't contain a value
"rtPolicyRef":"ORD_POL_REF12”, //optional, but should be unique. Will be generated automaticly if doesn't contain a value
"rtStartDate":"04/18/2023 06:50:13" // required
}
```

In the example above, GBP 2.00 will be debited every 1 day for 5 days., starting on 04/18/2023 06:50:13

## RT Agreements API

#### Authentication
Authentication requires Bearer token passed via Authorization Header. The token can be retrieved in Pixxles360 by clicking your username and selecting API Connections.
Example : “Bearer {token}"

#### Headers
Content-Type: application/json
Authorization: Bearer {token}

#### Base URL
https://pixxlesportal.com

#### HTTP Response Codes
- 200 – Succeeded
- 400 - Bad Request
- 401 – Unauthorized
- 500 - Internal Server Error

### Get Agreements
**POST** {{baseURL}}/api/Agreement/get
#### REQUEST Example
Query parameters are passed in Body as JSON
```json
{
  "Id ": "string", // optional. Search is performed by AgreementId field, using Contains method   
  "sort": "string", // optional, must be used in pair with "sortOrder". Pass a field name to be used for sorting
  "sortOrder": "string", // optional, must   be used in pair with "sort" parameter. Must be “asc” or “desc”.
  "page": 0,  //required, must be greater than 0
  "pageSize": 0  // required, must be greater than 0
}
```
#### RESPONSE example
totalCount and a list of agreements are returned
```json
{
      "merchantEntityId": "132779",
      "mrpn": "b18d-c815-420f-0755",
      "Id": 25,
      "name": "RTA: b18d-c815-420f-0755",
      "description": null,
      "policyReference": "b18d-c815-420f-0755",
      "agreementType": "recurring",
      "startDate": "2023-01-16T00:00:00Z",
      "initialPayment": 1,
      "initialPaymentDate": "2023-01-16T00:00:00Z",
      "currency": "GBP",
      "paymentCount": 3,
      "cycleAmount": 1,
      "cycleDuration": 2,
      "cycleDurationUnit": "day",
      "cycleCount": 3,
      "lastTransactionId": null,
      "finalPayment": 1,
      "finalPaymentDate": "2023-01-20T00:00:00Z",
      "nextPayment": null,
      "balance": 3,
      "state": "expired"
    }
```

### Stop Agreement
**PUT** {{baseURL}}/api/Agreement/{id}/stop

You can stop any agreement that has been started and has not expired yet.
#### RESPONSE example
```json
{
  "successfully": false,
  "text": "Failed to stop agreement"
}
```

### Delete Agreement
**DELETE** {{baseURL}}/api/Agreement/{id}

You can delete any agreement that has never been started. 
#### RESPONSE example
```json
{
  "successfully": false,
  "text": "Failed to delete agreement"
}
```
