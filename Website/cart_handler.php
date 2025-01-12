<?php
require_once("bootstrap.php");
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

// Initialize response array
$response = ["success" => false, "message" => ""];

try {
    if (!isset($_POST['action'])) {
        throw new Exception('Action not specified');
    }

    switch ($_POST['action']) {
        case 'getAvailableSizes':
            if (!isset($_POST['productId']) || !isset($_POST['color'])) {
                throw new Exception('Missing required parameters');
            }
            $result = $dbh->getProductSizes($_POST['productId'], $_POST['color']);
            echo json_encode($result['Taglia']);
            break;

        case 'updateColor':
            if (!isset($_POST['productId']) || !isset($_POST['newColor']) || !isset($_POST['size'])) {
                throw new Exception('Missing required parameters');
            }
            $email = $_SESSION['user_email'] ?? null;
            if (!$email) {
                throw new Exception('User not logged in');
            }
            $cartId = getCartId($dbh, $email);
            $success = $dbh->updateCartItemColor($cartId, $_POST['productId'], $_POST['newColor'], $_POST['size']);
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Color updated successfully' : 'Failed to update color'
            ]);
            break;

        case 'moveToWishlist':
            $productId = $_POST['id'];
            $command = $_POST['command'];
            $email = $_SESSION['user_email'] ?? null;
            
            if (!$email) {
                throw new Exception('User not logged in');
            }

            $size = $_POST['size'] ?? null;
            $color = $_POST['color'] ?? null;
            
            if (!$size || !$color) {
                throw new Exception('Size and color are required');
            }
            
            if ($dbh->addToWishlist($email, $productId, $color, $size)) {
                // Remove from cart after successful addition to wishlist
                $cartId = getCartId($dbh, $email);
                $dbh->removeFromCart($cartId, $productId, $color, $size);
                $response['success'] = true;
                $response['message'] = 'Item moved to wishlist';
            }
            break;

        default:
            throw new Exception('Invalid action');
    }
    if (isset($_POST['removeItem'])) {
        $productId = $_POST['removeItem'];
        $size = $_POST['size'] ?? null;
        $color = $_POST['color'] ?? null;
        $email = $_SESSION['user_email'] ?? null;
        
        if (!$email || !$size || !$color) {
            throw new Exception('Missing required parameters');
        }
        
        if ($dbh->removeFromCart($email, $productId, $color, $size)) {
            $response['success'] = true;
            $response['message'] = 'Item removed from cart';
        }
    } else if (isset($_POST['adjustQuantity'])) {
        $productId = $_POST['adjustQuantity'];
        $size = $_POST['size'] ?? null;
        $color = $_POST['color'] ?? null;
        $adjustment = $_POST['adjustment'] ?? 0; // +1 or -1
        $email = $_SESSION['user_email'] ?? null;
        
        if (!$email || !$size || !$color) {
            throw new Exception('Missing required parameters');
        }
        
        $cartId = getCartId($dbh, $email);
        if ($dbh->adjustCartQuantity($cartId, $productId, $color, $size, $adjustment)) {
            $response['success'] = true;
            $response['message'] = 'Quantity adjusted';
            $response['newTotal'] = calculateCartTotal($dbh, $cartId);
        }
    } else if (isset($_POST['updateSize'])) {
        $productId = $_POST['updateSize'];
        $newSize = $_POST['newSize'] ?? null;
        $color = $_POST['color'] ?? null;
        $email = $_SESSION['user_email'] ?? null;
        
        if (!$email || !$newSize || !$color) {
            throw new Exception('Missing required parameters');
        }
        
        $cartId = getCartId($dbh, $email);
        if ($dbh->updateCartItemSize($cartId, $productId, $color, $newSize)) {
            $response['success'] = true;
            $response['message'] = 'Size updated';
        }
    } else if (isset($_POST['updateColor'])) {
        $productId = $_POST['updateColor'];
        $size = $_POST['size'] ?? null;
        $newColor = $_POST['newColor'] ?? null;
        $email = $_SESSION['user_email'] ?? null;
        
        if (!$email || !$size || !$newColor) {
            throw new Exception('Missing required parameters');
        }
        
        $cartId = getCartId($dbh, $email);
        if ($dbh->updateCartItemColor($cartId, $productId, $newColor, $size)) {
            $response['success'] = true;
            $response['message'] = 'Color updated';
        }
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit();

// Helper Functions
function getCartId(DatabaseHelper $dbh, string $email): int {
    $cartInfo = $dbh->getCartByEmail($email);
    if (!$cartInfo) {
        throw new Exception('Cart not found');
    }
    return $cartInfo['ID_Carrello'];
}

function calculateCartTotal(DatabaseHelper $dbh, int $cartId): float {
    $items = $dbh->getCartItems($cartId);
    $total = 0.0;
    foreach ($items as $item) {
        $total += $item['Prezzo'] * $item['Quantita'];
    }
    return round($total, 2);
}


?>