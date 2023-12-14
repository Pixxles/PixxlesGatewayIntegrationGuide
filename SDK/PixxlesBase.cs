using System.Net;
using System.Security.Cryptography;
using System.Text;

namespace SDK
{
    public class PixxlesBase
    {
        public readonly string merchantID = "MERCHANT_ID_HERE";
        public readonly string signatureKey = "SIGNATURE_KEY_HERE";
        public readonly string gatewayUrl = "https://qa-transactions.pixxlesportal.com";
        public readonly string directPath = "/api/Transactions/payment/direct";

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
    }
}
