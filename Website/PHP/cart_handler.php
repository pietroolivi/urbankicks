<?php
require_once("bootstrap.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = ['success' => false, 'message' => ''];
    
    try {
        if (isset($_POST['id']) && isset($_POST['command'])) {
            $productId = $_POST['id'];
            $command = $_POST['command'];
            $email = $_SESSION['user_email'] ?? null;
            
            if (!$email) {
                throw new Exception('User not logged in');
            }
            
            // Handle wishlist operations
            if ($command === 'moveToWishlist') {
                $size = $_POST['size'] ?? null;
                $color = $_POST['color'] ?? null;
                
                if (!$size || !$color) {
                    throw new Exception('Size and color are required');
                }
                
                if ($db->addToWishlist($email, $productId, $color, $size)) {
                    // Remove from cart after successful addition to wishlist
                    $cartId = getCartId($db, $email);
                    $db->removeFromCart($cartId, $productId, $color, $size);
                    $response['success'] = true;
                    $response['message'] = 'Item moved to wishlist';
                }
            }
        } else if (isset($_POST['removeItem'])) {
            $productId = $_POST['removeItem'];
            $size = $_POST['size'] ?? null;
            $color = $_POST['color'] ?? null;
            $email = $_SESSION['user_email'] ?? null;
            
            if (!$email || !$size || !$color) {
                throw new Exception('Missing required parameters');
            }
            
            $cartId = getCartId($db, $email);
            if ($db->removeFromCart($cartId, $productId, $color, $size)) {
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
            
            $cartId = getCartId($db, $email);
            if ($db->adjustCartQuantity($cartId, $productId, $color, $size, $adjustment)) {
                $response['success'] = true;
                $response['message'] = 'Quantity adjusted';
                $response['newTotal'] = calculateCartTotal($db, $cartId);
            }
        } else if (isset($_POST['updateSize'])) {
            $productId = $_POST['updateSize'];
            $newSize = $_POST['newSize'] ?? null;
            $color = $_POST['color'] ?? null;
            $email = $_SESSION['user_email'] ?? null;
            
            if (!$email || !$newSize || !$color) {
                throw new Exception('Missing required parameters');
            }
            
            $cartId = getCartId($db, $email);
            if ($db->updateCartItemSize($cartId, $productId, $color, $newSize)) {
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
            
            $cartId = getCartId($db, $email);
            if ($db->updateCartItemColor($cartId, $productId, $newColor, $size)) {
                $response['success'] = true;
                $response['message'] = 'Color updated';
            }
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Helper Functions
function getCartId(DatabaseHelper $db, string $email): int {
    $cartInfo = $db->getCartByEmail($email);
    if (!$cartInfo) {
        throw new Exception('Cart not found');
    }
    return $cartInfo['ID_Carrello'];
}

function calculateCartTotal(DatabaseHelper $db, int $cartId): float {
    $items = $db->getCartItems($cartId);
    $total = 0.0;
    foreach ($items as $item) {
        $total += $item['Prezzo'] * $item['Quantita'];
    }
    return round($total, 2);
}


?>