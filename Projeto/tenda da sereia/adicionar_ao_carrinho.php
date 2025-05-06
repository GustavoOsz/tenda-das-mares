<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
    $id = intval($_POST['id_produto']);

    // Inicializa o carrinho como array se não estiver ou estiver corrompido
    if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    $encontrado = false;

    // Verifica se já existe o produto no carrinho
    foreach ($_SESSION['carrinho'] as &$item) {
        if (is_array($item) && isset($item['id']) && $item['id'] == $id) {
            $item['quantidade'] += 1;
            $encontrado = true;
            break;
        }
    }

    // Se não encontrado, adiciona novo item
    if (!$encontrado) {
        $_SESSION['carrinho'][] = [
            'id' => $id,
            'quantidade' => 1
        ];
    }

    header('Location: carrinho.php'); // Certifique-se de que carrinho.php está no mesmo diretório ou altere o caminho
    exit();
}
?>
