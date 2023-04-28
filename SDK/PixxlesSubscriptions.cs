using RestSharp;
using System.Web;

namespace SDK
{
    public class PixxlesSubscriptions : PixxlesBase
    {
        [Fact]
        public async void SCHEDULED_SUBSCRIPTION_PAYMENT()
        {
            var _restClient = new RestClient(new RestClientOptions(gatewayUrl));
            var request = new RestRequest(directPath, Method.Post);

            var scheduled = new Dictionary<string, string>
            {
                { "merchantID", $"{merchantID}" },
                { "xref", "XREF_HERE" }, // From a previous subscription payment
                { "amount", "3120" },
                { "action", "SALE" },
                { "type", "9" },
                { "rtAgreementType", "recurring" },
                { "avscv2CheckRequired", "N" },
            };

            request.AddHeader("Content-Type", "application/x-www-form-urlencoded");

            foreach ((string key, string value) in scheduled)
            {
                request.AddParameter(key, value);
            }

            var signature = CreateSignature(new SortedDictionary<string, string>(scheduled), signatureKey);
            request.AddParameter("signature", signature);

            var response = await _restClient.ExecuteAsync(request);

            Assert.NotNull(response.Content);

            var collection = HttpUtility.ParseQueryString(response.Content);

            var responseCode = collection["responseCode"];

            Assert.Equal("0", responseCode);
        }
    }
}
