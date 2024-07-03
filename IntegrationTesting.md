# Integration Testing

You will be provided with the test Merchant Account ID during the onboarding process.

Test Merchant Accounts are connected to our Simulator and not to an actual Acquirer. The Simulator will emulate the function of an Acquirer and provide simulated responses and authorisation codes.

## Test Amounts
When testing the transaction amount can be used to trigger different authorisation and settlement outcomes as follows:

|Min.Amount|	Max.Amount|	Authorisation|	Settlement outcome|
|-|-|-|-|
|100 (1.00)| 2499 (24.99)|(0) AUTH CODE: XXXXXX|ACCEPTED|
|2500 (25.00)|4999 (49.99)|(0) AUTH CODE: XXXXXX|REJECTED|
|5000 (50.00)|7499 (74.99)|(1) CARD REFERRED (0) AUTH CODE: XXXXXX| ACCEPTED|
|7500 (75.00)|9999 (99.99)|(1) CARD REFERRED (0) AUTH CODE: XXXXXX|REJECTED|
|10000 (100.00)|14999 (149.99)|(5) CARD DECLINED|N/A|
|15000 (150.00)|19999 (199.99)|(4) CARD DECLINED – KEEP CARD|N/A|
|20000 (200.00)|24999 (249.99)|(65) CARD DECLINED - SCA REQUIRED (0) AUTH CODE: XXXXXX|ACCEPTED|
|25000 (250.00)|29999 (299.99)|(65) CARD DECLINED – SCA REQUIRED (5) CARD DECLINED|N/A|

Any other amount will return a responseCode of 66311 (Invalid Test Amount).

The settlement outcome only applies to transactions which reach settlement due to being successfully authorised and captured and not cancelled. The amount captured is used when determining the settlement outcome rather than the amount authorised.

The range 20000 to 29999 can be used to test SCA soft declines. If the transaction is eligible[1] to request SCA then the Simulator will return a responseCode of 65 (SCA REQUIRED). If not, then it will return a responseCode of 0 (SUCCESS) for the range 20000 to 24999 or 5 (DO NOT HONOR) for the range 25000 to 29999. Successful transactions will be approved at settlement.

[1] A cardholder-initiated e-commerce sale or verify transaction that is enabled for 3-D Secure but is not already authenticated. SCA exemptions are not supported by the simulator and so cannot be used to request that SCA is not required.

## Test Cards
The test accounts will only accept card numbers that are designated for test purposes. These test cards cannot be used on production accounts.

To test AVS and CV2 verification then the associated CVV and billing addresses are provided for each card. If a different value is used, then the Simulator will mark the responses as ‘not matched’.

Unless stated otherwise an expiry date sometime in the near future should be used.

### Visa Credit
|Card Number|	CVV|	Address|
|-|-|-|
|4929421234600821|356|Flat 6 Primrose Rise 347 Lavender Road Northampton NN17 8YG|
|4543059999999982|110|76 Roseby Avenue Manchester M63X 7TH|
|4543059999999990|689|23 Rogerham Mansions 4578 Ermine Street Borehamwood WD54 8TH|

### Visa Debit
|Card Number|	CVV	|Address|
|-|-|-|
|4539791001730106|289| Unit 5 Pickwick Walk 120 Uxbridge Road Hatch End Middlesex HA6 7HJ|
|4462000000000003|672| Mews 57 Ladybird Drive Denmark 65890|

### Electron
|Card Number|	CVV|	Address|
|-|-|-|
|4917480000000008|009| 5-6 Ross Avenue Birmingham B67 8UJ|

### Mastercard Credit
|Card Number|	CVV|	Address|
|-|-|-|
|5301250070000191|419| 25 The Larches Narborough Leicester LE10 2RT|
|5413339000001000|304| Pear Tree Cottage The Green Milton Keynes MK11 7UY|
|5434849999999951|470| 34a Rubbery Close Cloisters Run Rugby CV21 8JT|
|5434849999999993|557| 4-7 The Hay Market Grantham NG32 4HG|

### Mastercard Debit
|Card Number|	CVV|	Address|
|-|-|-|
|5573 4712 3456 7898|159| Merevale Avenue Leicester LE10 2BU|

### UK Maestro
|Card Number|	CVV|	Address|
|-|-|-|
|6759 0150 5012 3445 002|309| The Parkway 5258 Larches Approach Hull North Humberside HU10 5OP|
|6759 0168 0000 0120 097|701| The Manor Wolvey Road Middlesex TW7 9FF|
### JCB
|Card Number|	CVV|	Address|
|-|-|-|
|3540599999991047|209| 2 Middle Wallop Merideth-in-the-Wolds Lincolnshire LN2 8HG|

### American Express
|Card Number|	CVV|	Address|
|-|-|-|
|374245455400001|4887| The Hunts Way Southampton SO18 1GW|
### Diners Club[2]
|Card Number|	CVV Number|	Address|
|-|-|-|
|36432685260294|111|N/A|

[2] Diners Club do not support the Address Verification Service (AVS). For testing purposes, we advise that a separate Merchant Account is used with AVS is turned off.

## 3-D Secure Testing
Your test account is connected to our 3-D Secure Product Integration Testing (PIT) system rather than to the production 3-D Secure servers.

You can use any of the [test cards](#test-cards) with this PIT system, and the authentication status returned by the Directory Server (for frictionless flow simulation) can be selected using the value of the card expiry month as follows:

|Card Expiry Month|	Auth Status|	Simulation (Frictionless)|
|-|-|-|
|01 - Jan|Y|Fully authenticated|
|02 - February|N|Not authenticated|
|03 - March|U|Unknown authentication status|
|04 - April|A|Attempted authentication|
|05 - May|D|Decoupled authentication|
|06 - June|R|Transaction rejected (do not attempt to send for authorisation)|
|07 – July|E|Unknown error performing 3-D Secure checks|
|08 - August|E|Error due to timeout communicating with the Directory Server|
|09 – September|E|Error due to corrupt response from the Directory Server|
|10 – October|I|Information only|
|11 – November|U|Unknown authentication due to Cardholder not enrolled (error 13)|
|12 - December|C|Frictionless not possible, challenge Cardholder|

An expiry month of 12 will simulate the non frictionless flow and desired authentication status (threeDSAuthenticated) can be selected on the challenge dialog shown by the PIT Access Control Server.

When using an expiry month from the above table please use a suitable expiry year to ensure the date is sometime in the near future.