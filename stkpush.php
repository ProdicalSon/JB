<?php
// Configuration
$consumerKey = 'YOUR_CONSUMER_KEY';
$consumerSecret = 'YOUR_CONSUMER_SECRET';
$BusinessShortCode = '174379'; 
$Passkey = 'YOUR_PASSKEY';
$PartyB = '174379'; // Same as BusinessShortCode
$CallbackURL = 'https://yourdomain.com/callback.php'; // Can be dummy for now

// Get form inputs
$phone = $_POST['phone'];
$amount = $_POST['amount'];

$timestamp = date('YmdHis');
$password = base64_encode($BusinessShortCode . $Passkey . $timestamp);

// Get access token
$credentials = base64_encode("$consumerKey:$consumerSecret");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$access_token = json_decode($response)->access_token;
curl_close($ch);

// STK Push
$stkheader = ['Content-Type: application/json', 'Authorization: Bearer ' . $access_token];
$postData = [
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $phone,
    'PartyB' => $PartyB,
    'PhoneNumber' => $phone,
    'CallBackURL' => $CallbackURL,
    'AccountReference' => 'JohnBrianBDay',
    'TransactionDesc' => 'Happy Birthday Gift'
];

$ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
curl_setopt($ch, CURLOPT_HTTPHEADER, $stkheader);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
$response = curl_exec($ch);
curl_close($ch);

echo "Payment request sent. Please check your phone to complete the transaction.";
?>
