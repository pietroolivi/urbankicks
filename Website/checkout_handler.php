<?php
require_once("bootstrap.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promoCode = isset($_POST['code']) ? trim($_POST['code']) : '';
    
    if (empty($promoCode)) {
        echo json_encode(['valid' => false]);
        exit;
    }
    
    $discount = $dbh->checkPromoCode($promoCode);
    
    if ($discount) {
        $_SESSION['discount'] = $discount;
        echo json_encode([
            'valid' => true,
            'value' => $discount['Valore'],
            'type' => $discount['TipoSconto']
        ]);
    } else {
        echo json_encode(['valid' => false]);
    }
}
?>