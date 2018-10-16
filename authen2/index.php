<?php
$store_url = 'http://18.188.41.55/';
$endpoint = '/wc-auth/v1/authorize';
$params = [
    'app_name' => 'My App Name',
    'scope' => 'write',
    'user_id' => 123,
    'return_url' => 'https://api.contaazul.com/auth/authorize?redirect_uri={REDIRECT_URI}&client_id={CLIENT_ID}&scope=sales&state={STATE}',
    'callback_url' => 'http://18.188.41.55/'
];
$query_string = http_build_query( $params );

echo $store_url . $endpoint . '?' . $query_string;
?>
