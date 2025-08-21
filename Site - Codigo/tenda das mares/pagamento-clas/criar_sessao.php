<?php
header('Content-Type: application/json');

// Seus dados sandbox
$email = 'osz.oliveiraa@gmail.com';
$token = '634ff005-dfe0-4a34-9bf4-612c2a63556395f5e00640bf98ab45830274ac3e2f8bdbf7-958d-4ff2-85d1-94b4cd0b71f8';

// URL do PagSeguro Sandbox
$url = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions";

// Inicia cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'email' => $email,
    'token' => $token
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"
]);

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo json_encode(['error' => curl_error($ch)]);
    exit;
}

curl_close($ch);

// Converte XML para objeto
$xml = simplexml_load_string($response);

if($xml === false || !isset($xml->id)){
    echo json_encode(['error' => 'Não foi possível criar sessão']);
    exit;
}

// Retorna sessionId
echo json_encode(['sessionId' => (string)$xml->id]);
