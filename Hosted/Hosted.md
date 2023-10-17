## Hosted Integration
The Hosted Integration method makes it easy to add secure payment processing to your ecommerce business, using our Hosted Payment Pages (HPP). You can use this method if you do
not want to collect and store Cardholder data.
The Hosted Integration method works by redirecting the Customer to our Gateway’s Hosted

Payment Page, which will collect the Customer’s payment details and process the payment before
redirecting the Customer back to a page on your website, letting you know the payment outcome.
This allows you the quickest path to integrating with the Gateway.


## Hosted HTTP Requests
When using the Hosted Integration, the request must be sent from the Customer’s web browser as
the response will be a HTML Hosted Payment Page (HPP), used to collect the Customer’s details.
The format of the request is designed so that it can be sent using a standard HTML form with the
data in hidden form fields. The browser will then automatically encode the request correctly
according to application/x-www-form-urlencoded format

When the Hosted Payment Page has been completed and the payment processed, the
Customer’s browser will be automatically redirected to the URL provided via the redirectURL
field. The response will be returned to this page in application/x-www-form-urlencoded
format, using a HTTP POST request.


## Redirect URL
The redirectURL request field is used to provide the URL of a webpage on your server.
For the Hosted Integration, the Customers browser will load this URL when the Hosted Payment
Page has completed and can be used to continue the payment journey on your website. The URL
will be loaded using a HTTP POST request containing transaction response data allowing you to
tailor the journey depending on the outcome of the transaction.

