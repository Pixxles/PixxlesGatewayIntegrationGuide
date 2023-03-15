using RestSharp;
using System.Security.Cryptography;
using System.Text;
using System.Web;

namespace SDK
{
    public class Pixxles
    {
        private readonly string merchantID = "MERCHANT_ID_HERE";
        private readonly string signatureKey = "SIGNATURE_KEY_HERE";
        private readonly string gatewayUrl = "https://qa-transactions.pixxlesportal.com";
        private readonly string directPath = "/api/Transactions/payment/direct";


        /// <summary>
        /// Send initial request to gateway
        /// </summary>
        [Fact]
        public async void Initial_Request_Test()
        {
            var _restClient = new RestClient(new RestClientOptions(gatewayUrl));
            var request = new RestRequest(directPath, Method.Post);

            var initialRequest = GetSaleTransaction();

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

        /// <summary>
        /// ACS Step 1
        /// </summary>
        [Fact]

        public async void ACS_STEP_1_TEST()
        {
            var _restClient = new RestClient(new RestClientOptions(gatewayUrl));
            var request = new RestRequest(directPath, Method.Post);

            var acs1 = new Dictionary<string, string>
            {
                { "action", "SALE" },
                { "merchantID", merchantID },
                { "threeDSRef","THREEDSREF_HERE" },
                { "threeDSResponse[threeDSMethodData]", "THREEDSMETHODDATA_HERE" },
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

        /// <summary>
        /// ACS Step 2
        /// </summary>
        [Fact]
        public async void ACS_STEP_2_TEST()
        {
            var _restClient = new RestClient(new RestClientOptions(gatewayUrl));
            var request = new RestRequest(directPath, Method.Post);

            var acs1 = new Dictionary<string, string>
            {
                { "action", "SALE" },
                { "merchantID", merchantID },
                { "threeDSRef","THREEDSREF_HERE" },
                { "threeDSResponse[cres]", "CRES_HERE" },
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

        private Dictionary<string, string> GetSaleTransaction()
        {
            return new Dictionary<string, string>
            {
                { "action", "SALE" },
                { "amount", "2294" },
                { "cardCVV","356" },
                { "cardExpiryMonth", "12" },
                { "cardExpiryYear", "24" },
                { "cardNumber", "  4929421234600821" },
                { "currencyCode", "GBP" },
                { "customerAddress", "Flat 6 Primrose Rise 347 Lavender Road Northampton United Kingdom" },
                { "customerCountryCode", "GB" },
                { "customerEmail", "test@test.ru" },
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
                { "orderRef", "123123" },
                { "remoteAddress", "192.168.0.1" },
                { "threeDSRedirectURL", "https://localhost/testsite" },
                { "transactionUnique", Guid.NewGuid().ToString() },
                { "type", "1"}
            };
        }

        private string CreateSignature(SortedDictionary<string, string> parameters, string key)
        {
            string signature = string.Join('&',
                parameters.Select(x => $"{Escape(x.Key)}={Escape(x.Value)}"));

            signature += key;

            signature = signature.Replace("\r\n", "\n")
                .Replace("\n\r", "\n").Replace("\r", "\n");

            signature = Sha512(signature);

            return signature.ToLower();
        }

        private string Escape(string str)
        {
            return string.IsNullOrEmpty(str)
                ? "" : Uri.EscapeDataString(str).Replace("%20", "+");
        }

        private string Sha512(string input)
        {
            byte[] bytes = Encoding.UTF8.GetBytes(input);
            using SHA512 hash = SHA512.Create();
            byte[] hashedInputBytes = hash.ComputeHash(bytes);

            // Convert to text
            // StringBuilder Capacity is 128, because 512 bits / 8 bits in byte * 2 symbols for byte 
            StringBuilder hashedInputStringBuilder = new(128);
            foreach (byte b in hashedInputBytes)
                hashedInputStringBuilder.Append(b.ToString("X2"));
            return hashedInputStringBuilder.ToString();
        }
    }
}