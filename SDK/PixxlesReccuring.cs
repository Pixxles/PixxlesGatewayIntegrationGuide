using RestSharp;
using System.Web;

namespace SDK
{
    public class PixxlesReccuring : PixxlesBase
    {
        private readonly string merchantID = "132779";
        private readonly string signatureKey = "gpfu2XDYLKWvbZi";
        private readonly string gatewayUrl = "https://qa-transactions.pixxlesportal.com";
        private readonly string directPath = "/api/Transactions/payment/direct";

        [Fact]
        public async void Initial_Recurring_Test()
        {
            var _restClient = new RestClient(new RestClientOptions(gatewayUrl));
            var request = new RestRequest(directPath, Method.Post);

            var initialRequest = GetRecurringTransaction();

            request.AddHeader("Content-Type", "application/x-www-form-urlencoded");

            foreach ((string key, string value) in initialRequest)
            {
                request.AddParameter(key, value);
            }

            var signature = CreateSignature(new SortedDictionary<string, string>(initialRequest), signatureKey);
            request.AddParameter("signature", signature);

            var response = await _restClient.ExecuteAsync(request);

            Assert.NotNull(response.Content);

            var collection = HttpUtility.ParseQueryString(response.Content);

            var responseCode = collection["responseCode"];
            Assert.Equal("65802", responseCode);

            // send this data use file data/step1.html
            var threeDSRef = collection["threeDSRef"];
            var threeDSURL = collection["threeDSURL"];
            var methodData = collection["threeDSRequest[threeDSMethodData]"];

            // manual submit data/step1.html with data above 
        }

        [Fact]

        public async void ACS_RECURRING_STEP_1_TEST()
        {
            var _restClient = new RestClient(new RestClientOptions(gatewayUrl));
            var request = new RestRequest(directPath, Method.Post);

            var acs1 = new Dictionary<string, string>
            {
                { "action", "VERIFY" },
                { "merchantID", merchantID },
                { "threeDSRef","UDNLRVk6dHJhbnNhY3Rpb25JRD0yMzU2NDY2ODUmbWVyY2hhbnRJRD0xMzI3NzkmX19saWZlX189MTY4MjM1NDkyNQ==" },
                { "threeDSResponse[threeDSMethodData]", "eyJ0aHJlZURTTWV0aG9kTm90aWZpY2F0aW9uVVJMIjoiaHR0cHM6Ly9sb2NhbGhvc3QvdGVzdHNpdGU_dGhyZWVEU0Fjc1Jlc3BvbnNlPW1ldGhvZCIsInRocmVlRFNTZXJ2ZXJUcmFuc0lEIjoiMTIwNTkwNzUtMjg1Ni00ZGFkLWJhMWItNGY1N2ZiNzg3NTMyIn0" },
            };

            request.AddHeader("Content-Type", "application/x-www-form-urlencoded");

            foreach ((string key, string value) in acs1)
            {
                request.AddParameter(key, value);
            }

            var signature = CreateSignature(new SortedDictionary<string, string>(acs1), signatureKey);
            request.AddParameter("signature", signature);

            var response = await _restClient.ExecuteAsync(request);

            Assert.NotNull(response.Content);

            var collection = HttpUtility.ParseQueryString(response.Content);

            var responseCode = collection["responseCode"];
            Assert.Equal("65802", responseCode);

            // send this data use file data/step2.html
            var threeDSURL = collection["threeDSURL"]; // Updated threeDSURL
            var threeDSRef = collection["threeDSRef"]; // Updated threeDSRef
            var creq = collection["threeDSRequest[creq]"];

            // manual submit data/step2.html with data above 
        }

        [Fact]
        public async void ACS_RECURRING_STEP_2_TEST()
        {
            var _restClient = new RestClient(new RestClientOptions(gatewayUrl));
            var request = new RestRequest(directPath, Method.Post);

            var acs1 = new Dictionary<string, string>
            {
                { "action", "VERIFY" },
                { "merchantID", merchantID },
                { "threeDSRef","UDNLRVk6dHJhbnNhY3Rpb25JRD0yMzU2NDY2ODUmbWVyY2hhbnRJRD0xMzI3NzkmX19saWZlX189MTY4MjM1NTA2NA==" },
                { "threeDSResponse[cres]", "eyJ0aHJlZURTU2VydmVyVHJhbnNJRCI6IjEyMDU5MDc1LTI4NTYtNGRhZC1iYTFiLTRmNTdmYjc4NzUzMiIsImFjc1RyYW5zSUQiOiI5ODY1MDRlYS1lYjk0LTRlZGItYWZjOC0wMGFlNTljMWQxYWQiLCJjaGFsbGVuZ2VDb21wbGV0aW9uSW5kIjoiWSIsIm1lc3NhZ2VUeXBlIjoiQ1JlcyIsIm1lc3NhZ2VWZXJzaW9uIjoiMi4xLjAiLCJ0cmFuc1N0YXR1cyI6IlkifQ" },
            };

            request.AddHeader("Content-Type", "application/x-www-form-urlencoded");

            foreach ((string key, string value) in acs1)
            {
                request.AddParameter(key, value);
            }

            var signature = CreateSignature(new SortedDictionary<string, string>(acs1), signatureKey);
            request.AddParameter("signature", signature);

            var response = await _restClient.ExecuteAsync(request);

            Assert.NotNull(response.Content);

            var collection = HttpUtility.ParseQueryString(response.Content);

            var responseCode = collection["responseCode"];
            Assert.Equal("0", responseCode);
        }

        private Dictionary<string, string> GetRecurringTransaction()
        {
            return new Dictionary<string, string>
            {
                { "action", "VERIFY" },
                { "amount", "0" },
                { "cardCVV","356" },
                { "cardExpiryMonth", "12" },
                { "cardExpiryYear", "24" },
                { "cardNumber", "  4929421234600821" },
                { "currencyCode", "GBP" },
                { "customerAddress", "Flat 6 Primrose Rise 347 Lavender Road Northampton United Kingdom" },
                { "customerCountryCode", "GB" },
                { "customerEmail", "test@test.com" },
                { "customerName", "John Smith" },
                { "customerPhone", "1234567890" },
                { "customerPostcode", "NN17 8YG" },
                { "customerTown", "Northampton" },
                { "deviceAcceptCharset", "" },
                { "deviceAcceptContent", "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9" },
                { "deviceAcceptEncoding", "gzip, deflate, br" },
                { "deviceAcceptLanguage", "en-US" },
                { "deviceCapabilities", "javascript" },
                { "deviceChannel", "browser" },
                { "deviceIdentity", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.54 Safari/537.36" },
                { "deviceScreenResolution", "1920x1080x24" },
                { "deviceTimeZone", "-120" },
                { "merchantID", merchantID },
                { "orderRef", "n2pro2404" },
                { "remoteAddress", "192.168.0.1" },
                { "threeDSRedirectURL", "https://localhost/testsite" },
                { "transactionUnique", "n2pro240423"},
                { "type", "1"},
                { "rtCycleAmount", "310" },
                { "rtAgreementType", "recurring" },
                { "rtCycleDuration","1" },
                { "rtCycleDurationUnit","day" },
                { "rtCycleCount","3" },
                { "rtName", "n2pro240423" },
                { "rtDescription", "n2pro240423" },
                { "rtPolicyRef", "n2pro240423" },
                { "rtStartDate","04/25/2023 00:01:00"}
            };
        }
    }
}