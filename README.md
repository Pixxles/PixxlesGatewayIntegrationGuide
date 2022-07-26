## PIXXLES GATEWAY INTEGRATION GUIDE



## Sale Transaction (with 3-D Secure) Direct 

The Direct Integration works by allowing you to keep the Customer on your system throughout the checkout process, collecting the Customer’s payment details on your own secure server before sending the collected data to our Gateway for processing. This allows you to provide a smoother, more complete checkout process to the Customer.  
In addition to basic sales processing, the Direct Integration can be used to perform other actions such as refunds and cancellations.

![Untitled Diagram drawio (2)](https://user-images.githubusercontent.com/72015387/180981113-2dad9ffa-f365-4831-bbbd-64a942d3d5b8.png)

## Files' descriptions

- `Direct/TransactionCreateRequest.json` – Payment request
- `Direct/TransactionResponse.json` – Payment response
- `Direct/3DSTester.php` –  Payment request 1
- `Direct/3DSv2-test-101.php` – Payment request 2
- `Direct/step1.html` –  ACS request 1
- `Direct/step2.html` – ACS request 2

## Sale Transaction (with 3-D Secure) Hosted

The Hosted Integration method makes it easy to add secure payment processing to your ecommerce business, using our Hosted Payment Pages (HPP). The Hosted Integration method works by redirecting the Customer to our Pixxles Hosted Payment Page, which will collect the Customer’s payment details and process the payment before redirecting the Customer back to a page on your website, letting you know the payment outcome. These fully customised pages are usually provided using a browser redirect, displaying full-page in the browser, or can be displayed embedded in an iframe on your website.

![Untitled Diagram drawio (1)](https://user-images.githubusercontent.com/72015387/180844098-f3b91301-bbeb-4ff4-b37f-b9ff8a524ad7.png)
![Screenshot_1](https://user-images.githubusercontent.com/72015387/180844236-2da9f4c6-e747-415d-bfad-5f545c42a621.png)

Send a POST request. The value of the data input is encoded with a AES-128 mechanism. There, the first 16 characters is the client_secret of your website, which serves as a key to the decoding process.

#### Payment request:


```plaintext
{
  "storeId": 1000,
  "lang": "en",
  "returnUrl": "https://test.store.com/custompayment/1000?orderId=XJ12H",
  "merchantSettings": {
    "merchantId": 12345678
  },
  "cart": {
    "currency": "USD",
    "order": {
      "id": "XJ12H",
      "totalAmount": 10.10,
      "paymentModule": "",
      "paymentMethod": "Credit or debit card (Mollie)",
      "ipAddress": "195.151.247.241",
      "orderComments": "comment",
      "customerId": 123123123,
      "globalReferer": "https://test.store.com/",
      "billingPerson": {
        "name": "",
        "companyName": "",
        "street": "",
        "city": "",
        "countryCode": "",
        "countryName": "",
        "postalCode": "",
        "stateOrProvinceCode": "",
        "stateOrProvinceName": "",
        "email": "t",
        "phone": ""
      },
      "additionalInfo": {
        "some_description": "123123"
      },
      "referenceTransactionId": "transaction_1234567890"
    }
  },
  "token": "abcdefghijklmnopqrstuv1234567890"
}
```

#### Return customer to store

When a customer is finished making their payment for an order, pixxles return them back to the store.

`returnUrl` is a field provided in a request. Its value is a destination, where your store should return the customer to after the payment process is complete.

## Files' descriptions

- `Hosted/model.json` – Payment request
