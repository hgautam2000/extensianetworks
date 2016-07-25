<?php


namespace PayWithAmazon;

require_once './PayWithAmazon/Client.php';

session_start();

// Create the parameters array
$requestParameters = array();

// Refer to GetDetails.php where the Amazon Order Reference ID was set
$requestParameters['amazon_order_reference_id'] = 'AMAZON_ORDER_REFERENCE_ID';

// Confirm the order by making the ConfirmOrderReference API call
$response = $client->confirmOrderReference($requestParameters);

$responsearray['confirm'] = json_decode($response->toJson());

// If the API call was a success make the Authorize API call
if($client->success)
{
    $requestParameters['authorization_amount'] = '175.00';
    $requestParameters['authorization_reference_id'] = 'Your Unique Reference Id';
    $requestParameters['seller_authorization_note'] = 'Authorizing payment';
    $requestParameters['transaction_timeout'] = 0;

    $response = $client->authorize($requestParameters);
    $responsearray['authorize'] = json_decode($response->toJson());
}

// If the Authorize API call was a success, make the Capture API call when you are ready to capture for the order (for example when the order has been dispatched)
if($client->success)
{
    $requestParameters['amazon_authorization_id'] = 'Parse the Authorize Response for this id';
    $requestParameters['capture_amount'] = '175.00';
    $requestParameters['currency_code'] = 'USD';
    $requestParameters['capture_reference_id'] = 'Your Unique Reference Id';

    $response = $client->capture($requestParameters);
    $responsearray['capture'] = json_decode($response->toJson());
}

// Echo the Json encoded array for the Ajax success
echo json_encode($responsearray);

?>