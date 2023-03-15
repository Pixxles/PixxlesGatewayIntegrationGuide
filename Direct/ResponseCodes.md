# Response Codes

The Gateway will always issue a numeric responseCode to report the status of the transaction.
These codes should be used rather than the responseMessage field to determine the outcome
of a transaction.

Response codes are grouped; however, the groupings are for informational purposes only and not
all codes in a group are used and some codes may exist for completeness or future use.

A zero responseCode always indicates a successful outcome.


# Authorisation Response Codes

The Gateway uses a set of standard response codes to indicate the status of an authorisation
request to the Acquirer. These response codes are based on the 2-character ISO 8583 response
codes.

The full set of ISO 8583 codes used are given in the table below, however not all are applicable to
transactions currently supported by the Gateway and therefore not used and documented for
reference purposes only.

Some ISO-8583 codes are not numeric and therefore to ensure all Gateway response codes are
numeric these codes are mapped to an equivalent numeric code greater than 99. This equivalent
numeric code is shown in the table below along with the original 2 letter code in brackets.

Not all ISO-8583 codes are applicable to the types of transactions currently available via the
Gateway and therefore unapplicable codes, although documented, may not currently be returned.

If the authorising Acquirer does not return a suitable ISO 8583 code, then the Gateway will attempt
to map the Acquirers response to a suitable code.

The original Acquirer authorisation response code and response message will always be returned
in the acquirerResponseCode and acquirerResponseMessage fields as detailed in section
18.

The original Acquirer authorisation response code may not be numeric and information on these
codes will need to be requested from the Acquirer.


## Acquirer Authorisation Response codes: 0 – 9999
| Code | Description |
| --- | --- | 
| 0 | Successful approval/completion |
| 1 | Refer to card issuer |
| 2 | Refer to card issuer, special condition |
| 3 | Invalid merchant or service provider |
| 4 | Pickup card |
| 5 | Do not honor |
| 6 | Error |
| 7 | Pickup card, special condition (other than lost/stolen card) |
| 8 | Honor with identification |
| 9 | Request in progress |
| 10 | Approval for partial amount |
| 11 | Approved VIP |
| 12 | Invalid transaction |
| 13 | Invalid amount (currency conversion field overflow), or amount exceeds maximum for card program |
| 14 | Invalid card number/account number |
| 15 | No such issuer |
| 16 | Approved, Update Track 3 |
| 17 | Customer cancellation |
| 18 | Customer dispute |
| 19 | Re-enter transaction |
| 20 | Invalid response/Acquirer error |
| 21 | No action taken (unable to back out prior transaction) |
| 22 | Suspected malfunction |
| 23 | Unacceptable transaction |
| 24 | File update impossible |
| 25 | Reference number cannot be found. Unable to locate record in file, or account number is missing from the inquiry |
| 26 | Duplicate reference number |
| 27 | Error in reference number |
| 28 | File is temporarily unavailable for update |
| 29 | File action failed/Contact acquirer |
| 30 | Format error |
| 31 | Bank not supported by Switch/Unknown acquirer account code |
| 32 | Complete partially |
| 33 | Pickup card (expired) |
| 34 | Pickup card (suspected fraud) |
| 35 | Pickup card (acceptor contact acquirer) |
| 36 | Pickup card (restricted card) |
| 37 | Pickup card (acceptor call acquirer security) |
| 38 | Pickup card (PIN tries exceeded) |
| 39 | No credit account |
| 40 | Function not supported. |
| 41 | Pickup card (lost card) |
| 42 | No universal account |
| 43 | Pickup card (stolen card) |
| 44 | No investment account |
| 45 | Account closed |
| 46 | Identification required |
| 47 | Identification cross-check required |
| 48 | No from account |
| 49 | No to account |
| 50 | No account |
| 51 | Insufficient funds |
| 52 | No checking account |
| 53 | No savings account |
| 54 | Expired card |
| 55 | Incorrect PIN |
| 56 | Unknown card |
| 57 | Transaction not permitted to cardholder |
| 58 | Transaction not allowed at terminal |
| 59 | Suspected fraud |
| 60 | Contact acquirer |
| 61 | Exceeds withdrawal amount limit |
| 62 | Restricted card (for example, in Country Exclusion table) |
| 63 | Security violation |
| 64 | Amount higher than previous transaction |
| 65 | SCA Required (previously, Exceeds withdrawal limit) |
| 66 | Contact acquirer |
| 67 | Hard capture - ATM |
| 68 | Time out |
| 69 | Advice received too late. |
| 70 | Contact card issuer |
| 71 | Message flow error |
| 72 | Authorization centre not available for 60 seconds. |
| 73 | Authorization centre not available for 300 seconds. |
| 74 | PIN entry necessary |
| 75 | Allowable number of PIN tries exceeded |
| 76 | Unable to locate previous message (no match on Retrieval Reference number) |
| 77 | Previous message located for a repeat or reversal, but repeat or reversal data are inconsistent with original message |
| 78 | Blocked, first used. The transaction is from a new cardholder, and the card has not been unblocked |
| 79 | Already reversed |
| 80 | Visa transactions: credit issuer unavailable. Private label and check acceptance: Invalid date |
| 81 | PIN cryptographic error found (error found by VIC security module during PIN decryption) |
| 82 | Negative CAM, dCVV, iCVV, or CVV results |
| 83 | STIP cannot approve |
| 84 | Pre-auth time too great |
| 85 | No reason to decline a request for account number verification, address verification, CVV2 verification, or a credit voucher or merchandise return |
| 86 | Unable to verify PIN |
| 87 | Purchase amount only, no cash back allowed |
| 88 | Unable to authorise |
| 89 | Ineligible to receive |
| 90 | Cut-off in progress |
| 91 | Issuer unavailable or switch inoperative (STIP not applicable or available for this transaction) |
| 92 | Destination cannot be found for routing |
| 93 | Transaction cannot be completed, violation of law |
| 94 | Duplicate transaction |
| 95 | Reconcile error |
| 96 | System malfunction |
| 97 | Security Breach |
| 98 | Date and time not plausible |
| 99 | Error in PAC encryption detected. |
| 497 (B1) | Surcharge amount not permitted on Visa cards (U.S. acquirers only) |
| 498 (B2) | Surcharge not supported |
| 928 (N0) | Unable to authorise |
| 931 (N3) | Cash service not available |
| 932 (N4) | Cashback request exceeds issuer limit |
| 933 (N5) | Resubmitted transaction over max days limit |
| 935 (N7) | Decline for CVV2 failure |
| 936 (N8) | Transaction amount greater than pre-authorised approved amount |
| 1002 (P2) | Invalid biller information |
| 1005 (P5) | PIN Change/Unblock request declined |
| 1006 (P6) | Unsafe PIN |
| 1037 (Q1) | Card Authentication failed |
| 1072 (R0) | Stop Payment Order |
| 1073 (R1) | Revocation of Authorization Order |
| 1074 (R1) | Revocation of All Authorizations Order |
| 1144 (T0) | Approval, keep first check |
| 1145 (T1) | Check OK, no conversion |
| 1146 (T2) | Invalid RTTN |
| 1147 (T3) | Amount greater than limit |
| 1148 (T4) | Unpaid items, failed NEG |
| 1149 (T5) | Duplicate check number |
| 1150 (T6) | MICR error |
| 1151 (T7) | Too many checks |
| 1298 (XA) | Forward to issuer |
| 1301 (XD) | Forward to issuer |
| 1363 (Z3) | Unable to go online. |

# Gateway Response Codes
The Gateway uses a set of enhanced response codes to indicate if there is an issue with the
transaction which prevented any authorisation response being received from the Acquirer. These
response codes start at 65536.

## General Error Codes: 65536 - 65791
| Code | Description |
| --- | --- | 
| 65536 | Transaction in progress. Contact customer support if this error occurs. |
| 65537 | A general error has occurred. |
| 65538 | Reserved for future use. Contact customer support if this error occurs. |
| 65539 | Invalid Credentials: merchantID is unknown or the signature doesn’t match. |
| 65540 | Permission denied: caused by sending a request from an unauthorised IP address. |
| 65541 | Action not allowed: action is not supported by the Acquirer or allowed for the transaction. |
| 65542 | Request Mismatch: fields sent while completing a request do not match initially requested values. Usually due to sending different card details to those used to authorise the transaction when completing a 3-D Secure transaction or performing a REFUND_SALE transaction. |
| 65543 | Request Ambiguous: request could be misinterpreted due to inclusion of mutually exclusive fields.|
| 65544 | Request Malformed: could not parse the request data. |
| 65545 | Suspended Merchant account. |
| 65546 | Currency not supported by Merchant. |
| 65547 | Request Ambiguous, both taxValue and discountValue provided when should be one only. |
| 65548 | Database error. |
| 65549 | Payment processor communications error. |
| 65550 | Payment processor error. |
| 65551 | Internal Gateway communications error. |
| 65552 | Internal Gateway error. |
| 65553 | Encryption error. |
| 65554 | Duplicate request.|
| 65555 | Settlement error. |
| 65556 | AVS/CV2 Checks are not supported for this card (or Acquirer). |
| 65557 | IP Blocked: Request is from a banned IP address. |
| 65558 | Primary IP blocked: Request is not from one of the primary IP addresses configured for this Merchant Account. |
| 65559 | Secondary IP blocked: Request is not from one of the secondary IP addresses configured for this Merchant Account. |
| 65560 | Reserved for future use. Contact customer support if this error occurs. |
| 65561 | Unsupported Card Type: Request is for a card type that is not supported on this Merchant Account. |
| 65562 | Unsupported Authorisation: External authorisation code authorisationCode has been supplied and this is not supported for the transaction or by the Acquirer. |
| 65563 | Request not supported: The Gateway or Acquirer does not support the request. |
| 65564 | Request expired: The request cannot be completed as the information is too old. |
| 65565 | Request retry: The request can be retried later. |
| 65566 | Invalid card number: A test card was used on a live Merchant Account. Or Disallowed card number: A live card was used on a test Merchant Account. |
| 65567 | Unsupported card issuing country: Request is for a card issuing country that is not supported on this Merchant Account. |
| 65568 | Masterpass processing error. |
| 65569 | Masterpass not supported. |
| 65570 | Masterpass checkout failure. |
| 65571 | Masterpass checkout success. |
| 65572 | Masterpass checkout is required. |
| 65573 | Amounts error. Provided transaction amounts to not tally. |
| 65574 | Reserved for future use. Contact customer support if this error occurs. |
| 65575 | No data was found that match the selection criteria. |
| 65576 | Request cancelled. |
| 65792 | 3-D Secure processing in progress. |
| 65793 | 3-D Secure processing error. |
| 65794 | 3-D Secure processing is required. 3-D Secure ACS challenge must be displayed. |
| 65795 | 3-D Secure processing is not required. |
| 65796 | 3-D Secure is not supported for this request: No versions of 3DS are supported for this request. Or 3DS processing not supported: request has threeDSRequired=Y but the Merchant Account isn’t enabled for 3DS or required to do 3DS. |
| 65797 | Error occurred during 3-D Secure enrolment check. |
| 65798 | Reserved for future use. |
| 65799 | Reserved for future use. |
| 65800 | Error occurred during 3-D Secure authentication check. |
| 65801 | Reserved for future use. |
| 65802 | 3-D Secure authentication is required. |
| 65803 | 3-D Secure authentication results do not meet the Merchant’s preferences. |
| 65804 | 3-D Secure authentication was successful. |
