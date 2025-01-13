<?php
require_once("bootstrap.php");
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

$response = ["success" => false, "message" => ""];

try {
    $email = $_SESSION['user_email'] ?? null;
    if (!$email) {
        throw new Exception('User not logged in');
    }

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'getAvailableSizes':
                if (!isset($_POST['productId']) || !isset($_POST['color'])) {
                    throw new Exception('Missing required parameters');
                }
                $sizes = $dbh->getProductSizes($_POST['productId'], $_POST['color']);
                echo json_encode($sizes);
                exit();

            case 'moveToWishlist':
                if (!isset($_POST['productId']) || !isset($_POST['color']) || !isset($_POST['size'])) {
                    throw new Exception('Missing required parameters');
                }
                
                $cart = $dbh->getCartByEmail($_SESSION["user_email"]);
                if ($dbh->addToWishlist($email, $_POST['productId'])) {
                    $dbh->removeFromCart($email, $_POST['productId'], $_POST['color'], $_POST['size']);
                    $cartInfo = calculateCartInfo($dbh, $cart['ID_Carrello']);
                    $dbh->modifyCartTotalValue($cart['ID_Carrello'], $cartInfo['total']);
                    $response = [
                        'success' => true,
                        'message' => 'Item moved to wishlist',
                        'itemCount' => $cartInfo['itemCount'],
                        'cartTotal' => $cartInfo['total']
                    ];
                    echo json_encode($response);
                    exit();
                }
        }
    }

    if (isset($_POST['removeItem'])) {
        $cart = $dbh->getCartByEmail($_SESSION["user_email"]);
        if ($dbh->removeFromCart($email, $_POST['removeItem'], $_POST['color'], $_POST['size'])) {
            $cartInfo = calculateCartInfo($dbh, $cart['ID_Carrello']);
            $dbh->modifyCartTotalValue($cart['ID_Carrello'], $cartInfo['total']);
            $response = [
                'success' => true,
                'message' => 'Item removed',
                'itemCount' => $cartInfo['itemCount'],
                'cartTotal' => $cartInfo['total']
            ];
        }
    }

    if (isset($_POST['adjustQuantity'])) {
        $cart = $dbh->getCartByEmail($_SESSION["user_email"]);
        if ($dbh->updateCartQuantity($cart['ID_Carrello'], $_POST['adjustQuantity'], $_POST['color'], 
                                   $_POST['size'], $_POST['quantity'])) {
            $cartInfo = calculateCartInfo($dbh, $cart['ID_Carrello']);
            $response = [
                'success' => true,
                'message' => 'Quantity updated',
                'itemCount' => $cartInfo['itemCount'],
                'cartTotal' => $cartInfo['total']
            ];
        }
    }

    if (isset($_POST['updateColor'])) {
        $cart = $dbh->getCartByEmail($_SESSION["user_email"]);
        if ($dbh->updateCartItemColor(
            $cart['ID_Carrello'], 
            $_POST['productId'],
            $_POST['oldColor'],
            $_POST['newColor'],
            $_POST['size']
        )) {
            $cartInfo = calculateCartInfo($dbh, $cart['ID_Carrello']);
            $response = [
                'success' => true,
                'message' => 'Color updated successfully',
                'itemCount' => $cartInfo['itemCount'],
                'cartTotal' => $cartInfo['total']
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to update color'
            ];
        }
    }
    
    if (isset($_POST['updateSize'])) {
        if ($dbh->updateCartItemColor(
            $cart['ID_Carrello'], 
            $_POST['productId'],
            $_POST['oldColor'],
            $_POST['color'],
            $_POST['oldSize']
        )) {
            $cart = $dbh->getCartByEmail($_SESSION["user_email"]);
            if ($dbh->updateCartItemSize(
                $cart['ID_Carrello'], 
                $_POST['productId'],
                $_POST['oldSize'],
                $_POST['color'],
                $_POST['newSize']
            )) {
                $cartInfo = calculateCartInfo($dbh, $cart['ID_Carrello']);
                $response = [
                    'success' => true,
                    'message' => 'Size updated successfully',
                    'itemCount' => $cartInfo['itemCount'],
                    'cartTotal' => $cartInfo['total']
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to update size'
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to update color'
            ];
        }
    }

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
exit();

function getCartId(DatabaseHelper $dbh, string $email): int {
    $cartInfo = $dbh->getCartByEmail($email);
    if (!$cartInfo) {
        throw new Exception('Cart not found');
    }
    return $cartInfo['ID_Carrello'];
}

function calculateCartInfo(DatabaseHelper $dbh, int $cartId): array {
    $items = $dbh->getCartItems($cartId);
    $total = 0.0;
    $itemCount = 0;
    
    foreach ($items as $item) {
        $total += $item['Prezzo'] * $item['Quantita'];
        $itemCount += $item['Quantita'];
    }
    
    return [
        'total' => round($total, 2),
        'itemCount' => $itemCount
    ];
}
?>