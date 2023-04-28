using System.Security.Cryptography;
using System.Text;

namespace SDK
{
    public class PixxlesBase
    {
        public readonly string merchantID = "132779";
        public readonly string signatureKey = "gpfu2XDYLKWvbZi";
        public readonly string gatewayUrl = "https://qa-transactions.pixxlesportal.com";
        public readonly string directPath = "/api/Transactions/payment/direct";

        public string CreateSignature(SortedDictionary<string, string> parameters, string key)
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
