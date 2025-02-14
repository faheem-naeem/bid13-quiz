<?php 

function isValidPhoneNumber($phone_number, $customer_id, $api_key) {
	// removed "-ww" fromt URL, "-ww" subdomain does not support the PhoneID API
    $api_url = "https://rest.telesign.com/v1/phoneid/$phone_number";

    $headers = [
        "Authorization: Basic " . base64_encode("$customer_id:$api_key"),
        "Content-Type: application/x-www-form-urlencoded"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // hitting POST request as GET method is not allowed for this PhoneID API
    curl_setopt($ch, CURLOPT_POST, 1);
    
    $response = curl_exec($ch);
    $http_code = 200;
    curl_close($ch);

    if ($http_code !== 200) {
        return false; // API request failed
    }
    
    $data = json_decode($response, true);
    if (!isset($data['numbering']['phone_type'])) {
        return false; // Unexpected API response
    }
    
    $valid_types = ["FIXED_LINE", "MOBILE", "VALID"];

    // phone_type" is on root level and "description" has valid type.
    return in_array(strtoupper($data['phone_type']['description']), $valid_types);
}

// Usage example
$phone_number = "12269703456"; // Replace with actual phone number
$customer_id = "488AF780-D8F2-491C-83FD-A1301E71DFA6";
$api_key = "93AWI2u1ecTOi3jq40aWn5Pr/qzL9VfPFRXsUmc7u6cf7Y6eq8peLh3eww7iFup6KHNI9/2mPX9hf3oqPmrz/g==";
$result = isValidPhoneNumber($phone_number, $customer_id, $api_key);
var_dump($result);
?>