<?php

$url =
    "http://localhost:3000/api/products";


$ch =
    curl_init($url);


curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);


curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    [
        "Accept: application/json"
    ]
);


$response =
    curl_exec($ch);


if ($response === false) {

    die(
        "cURL Error: " .
        curl_error($ch)
    );

}


curl_close($ch);


$data =
    json_decode(
        $response,
        true
    );


echo "<h2>Express API Response</h2>";

echo "<pre>";

print_r($data);

echo "</pre>";

?>