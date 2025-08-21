<?php
session_start();
header('Content-Type: application/json');

$email = 'osz.oliveiraa@gmail.com';
$token = '634ff005-dfe0-4a34-9bf4-612c2a63556395f5e00640bf98ab45830274ac3e2f8bdbf7-958d-4ff2-85d1-94b4cd0b71f8';

// Dados do frontend
$senderHash = $_POST['senderHash'];
$cardToken = $_POST['cardToken'];
$nome = $_POST['nome'];
$emailComprador = $_POST['email'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$total = number_format($_POST['total'],2,'.','');

$data = [
    'email'=>$email,
    'token'=>$token,
    'paymentMode'=>'default',
    'paymentMethod'=>'creditCard',
    'receiverEmail'=>$email,
    'currency'=>'BRL',
    'itemId1'=>'0001',
    'itemDescription1'=>'Compra Loja',
    'itemAmount1'=>$total,
    'itemQuantity1'=>'1',
    'reference'=>'REF123',
    'senderName'=>$nome,
    'senderCPF'=>$cpf,
    'senderAreaCode'=>substr($telefone,0,2),
    'senderPhone'=>substr($telefone,2),
    'senderEmail'=>$emailComprador,
    'senderHash'=>$senderHash,
    'creditCardToken'=>$cardToken,
    'installmentQuantity'=>'1',
    'installmentValue'=>$total,
    'creditCardHolderName'=>$nome,
    'creditCardHolderCPF'=>$cpf,
    'creditCardHolderBirthDate'=>'01/01/1990',
    'creditCardHolderAreaCode'=>substr($telefone,0,2),
    'creditCardHolderPhone'=>substr($telefone,2),
    'billingAddressStreet'=>'Rua Teste',
    'billingAddressNumber'=>'123',
    'billingAddressDistrict'=>'Bairro',
    'billingAddressPostalCode'=>'12345678',
    'billingAddressCity'=>'Cidade',
    'billingAddressState'=>'SP',
    'billingAddressCountry'=>'BRA',
];

$ch = curl_init('https://ws.sandbox.pagseguro.uol.com.br/v2/transactions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if($err){
    echo json_encode(['success'=>false,'message'=>$err]);
    exit;
}

$xml = simplexml_load_string($response);
if(isset($xml->error)){
    $msg='';
    foreach($xml->error as $error){ $msg .= (string)$error->message.' '; }
    echo json_encode(['success'=>false,'message'=>$msg]);
    exit;
}

$transactionCode = (string)$xml->code;
echo json_encode(['success'=>true,'transactionCode'=>$transactionCode]);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pagamento Realizado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-[#4f2905] font-sans">

<div class="max-w-2xl mx-auto mt-20 p-8 bg-[#fde9c7] rounded-2xl shadow text-center">
    <h1 class="text-3xl font-bold mb-4">✅ Pagamento Realizado!</h1>
    <p class="mb-6">Obrigado! Seu pagamento foi processado com sucesso (sandbox).</p>
    <a href="../index.php" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600">Voltar à Loja</a>
</div>

</body>
</html>
