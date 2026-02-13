<?php
$data = json_encode([
    'name' => 'Api Test',
    'email' => 'apitest+1@example.com',
    'password' => 'Password123',
    'password_confirmation' => 'Password123',
]);
$opts = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content' => $data,
        'ignore_errors' => true,
    ],
];
$ctx = stream_context_create($opts);
$res = @file_get_contents('http://host.docker.internal:8080/api/auth/register', false, $ctx);
if (isset($http_response_header)) {
    echo implode("\n", $http_response_header) . "\n";
}
echo "BODY:\n";
echo $res;
