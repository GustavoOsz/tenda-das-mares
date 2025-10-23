<?php
session_start();
if(!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])){
    header("Location: carrinho.php");
    exit;
}

$total = 0;
foreach($_SESSION['carrinho'] as $item){
    $total += $item['preco'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Checkout - PagSeguro Sandbox</title>
<script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<script>
let sessionId = '';

async function criarSessao(){
    try{
        const res = await fetch('criar_sessao.php');
        const data = await res.json();
        if(data.error){
            alert('Erro ao criar sessão: '+data.error);
            return;
        }
        sessionId = data.sessionId;
        PagSeguroDirectPayment.setSessionId(sessionId);
        console.log('Sessão criada:', sessionId);
    }catch(err){
        console.error('Erro ao criar sessão:', err);
    }
}

window.onload = criarSessao;

async function pagar(){
    const form = document.getElementById('formPagamento');

    const nome = form.nome.value;
    const email = form.email.value;
    const cpf = form.cpf.value.replace(/\D/g,'');
    const telefone = form.telefone.value.replace(/\D/g,'');
    const cardNumber = form.cardNumber.value.replace(/\D/g,'');
    const cardHolder = form.cardHolder.value;
    const expirationMonth = form.expirationMonth.value;
    const expirationYear = form.expirationYear.value;
    const cvv = form.cvv.value;

    if(!sessionId){
        alert('Sessão PagSeguro não criada.');
        return;
    }

    try{
        const senderHash = PagSeguroDirectPayment.getSenderHash();

        const brand = await new Promise((resolve, reject)=>{
            PagSeguroDirectPayment.getBrand({
                cardBin: cardNumber.substring(0,6),
                success: function(res){ resolve(res.brand.name); },
                error: function(err){ reject(err); }
            });
        });

        const cardToken = await new Promise((resolve,reject)=>{
            PagSeguroDirectPayment.createCardToken({
                cardNumber, brand, cvv,
                expirationMonth, expirationYear,
                success: function(res){ resolve(res.card.token); },
                error: function(err){ reject(err); }
            });
        });

        const formData = new FormData();
        formData.append('senderHash', senderHash);
        formData.append('cardToken', cardToken);
        formData.append('nome', nome);
        formData.append('email', email);
        formData.append('cpf', cpf);
        formData.append('telefone', telefone);
        formData.append('total', <?php echo $total; ?>);

        const res = await fetch('processa_pagamento.php',{
            method:'POST',
            body: formData
        });
        const data = await res.json();

        if(data.success){
            alert('Pagamento realizado com sucesso! Código: '+data.transactionCode);
            window.location.href = 'obrigado.php';
        }else{
            alert('Erro no pagamento: '+data.message);
        }

    }catch(err){
        console.error(err);
        alert('Erro ao processar pagamento.');
    }
}
</script>
</head>
<body class="bg-white text-[#4f2905] font-sans">
<div class="max-w-4xl mx-auto mt-10 p-8 bg-[#fde9c7] rounded-2xl shadow">
<h1 class="text-2xl font-bold mb-6">💳 Finalizar Compra</h1>

<form id="formPagamento" class="space-y-4 bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Dados do Comprador</h2>
    <input type="text" name="nome" placeholder="Nome Completo" class="w-full p-2 border rounded" required>
    <input type="email" name="email" placeholder="E-mail" class="w-full p-2 border rounded" required>
    <input type="text" name="cpf" placeholder="CPF" class="w-full p-2 border rounded" required>
    <input type="text" name="telefone" placeholder="Telefone" class="w-full p-2 border rounded" required>

    <h2 class="text-xl font-semibold mb-4 mt-6">Cartão de Crédito (Sandbox)</h2>
    <input type="text" name="cardNumber" placeholder="Número do Cartão" class="w-full p-2 border rounded" required>
    <input type="text" name="cardHolder" placeholder="Nome do Titular" class="w-full p-2 border rounded" required>
    <input type="text" name="expirationMonth" placeholder="MM" class="w-1/2 p-2 border rounded" required>
    <input type="text" name="expirationYear" placeholder="AAAA" class="w-1/2 p-2 border rounded" required>
    <input type="text" name="cvv" placeholder="CVV" class="w-1/4 p-2 border rounded" required>

    <button type="button" onclick="pagar()" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 w-full">
        Pagar R$ <?php echo number_format($total,2,',','.'); ?>
    </button>
</form>
</div>
</body>
</html>
