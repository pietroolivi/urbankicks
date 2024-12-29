<?php

class DatabaseHelper {
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Failed to connect to the database");
        }
    }

    /*******************
     * PRODUCT QUERIES *
     *******************/

    // Returns filtered products
    public function getFilteredProducts($brand = null, $type = null, $size = null, $color = null, $minPrice = null, $maxPrice = null) {
        $query = "SELECT DISTINCT p.* FROM PRODOTTO p 
                  LEFT JOIN VARIANTE v ON p.ID_Prodotto = v.ID_Prodotto 
                  WHERE 1=1";
        $params = [];
        $types = "";
        
        if ($brand) {
            $query .= " AND p.Marca = ?";
            $params[] = $brand;
            $types .= "s";
        }
        if ($type) {
            $query .= " AND p.Tipo = ?";
            $params[] = $type;
            $types .= "s";
        }
        if ($size) {
            $query .= " AND v.Taglia = ?";
            $params[] = $size;
            $types .= "d";
        }
        if ($color) {
            $query .= " AND v.Colore = ?";
            $params[] = $color;
            $types .= "s";
        }
        if ($minPrice) {
            $query .= " AND p.Prezzo >= ?";
            $params[] = $minPrice;
            $types .= "d";
        }
        if ($maxPrice) {
            $query .= " AND p.Prezzo <= ?";
            $params[] = $maxPrice;
            $types .= "d";
        }

        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Admin: Add new product
    public function addProduct($productId, $name, $description, $brand, $type, $price) {
        $query = "INSERT INTO PRODOTTO (ID_Prodotto, Nome, Descrizione, Marca, Tipo, Prezzo, Data_Aggiunta, Sta_Tipo) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW(), 'disponibile')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssssd", $productId, $name, $description, $brand, $type, $price);
        return $stmt->execute();
    }

    // Admin: Update product price
    public function updateProductPrice($productId, $newPrice) {
        // First, store the old price in history
        $query = "INSERT INTO PRODOTTO_STORICO (ID_Prodotto, Prezzo, Data_Modifica) 
                  SELECT ID_Prodotto, Prezzo, NOW() FROM PRODOTTO WHERE ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $productId);
        $stmt->execute();

        // Then update the current price
        $query = "UPDATE PRODOTTO SET Prezzo = ? WHERE ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ds", $newPrice, $productId);
        return $stmt->execute();
    }

    /*******************
     * MESSAGE QUERIES *
     *******************/

    // User: Send message to admin
    public function sendMessage($email, $subject, $body) {
        $query = "INSERT INTO MESSAGGIO (Email, Oggetto, Corpo, Timestamp_Invio) 
                  VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $email, $subject, $body);
        return $stmt->execute();
    }

    // Admin: Get all messages
    public function getAllMessages() {
        $query = "SELECT m.*, u.Nome, u.Cognome 
                  FROM MESSAGGIO m 
                  JOIN UTENTE u ON m.Email = u.Email 
                  ORDER BY m.Timestamp_Invio DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*******************
     * TRACKING QUERIES *
     *******************/

    public function getOrderTracking($orderId) {
        $query = "SELECT o.*, t.Posizione, t.Timestamp_Aggiornamento 
                  FROM ORDINE o 
                  LEFT JOIN Tracking_Spedizione t ON o.ID_Ordine = t.ID_Ordine 
                  WHERE o.ID_Ordine = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateOrderStatus($orderId, $newStatus, $location) {
        // Update order status
        $query = "UPDATE ORDINE SET Tipo = ? WHERE ID_Ordine = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $newStatus, $orderId);
        $stmt->execute();

        // Update tracking
        $query = "INSERT INTO Tracking_Spedizione (ID_Ordine, Posizione, Timestamp_Aggiornamento) 
                  VALUES (?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $orderId, $location);
        return $stmt->execute();
    }

    /*******************
     * DISCOUNT QUERIES *
     *******************/

    // Admin: Create discount code
    public function createDiscount($discountId, $description, $type, $value, $startDate, $endDate) {
        $query = "INSERT INTO SCONTO (ID_Sconto, Descrizione, TipoSconto, Valore, Data_Inizio, Data_Fine) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssdss", $discountId, $description, $type, $value, $startDate, $endDate);
        return $stmt->execute();
    }

    // Check if discount is valid
    public function validateDiscount($discountId) {
        $query = "SELECT * FROM SCONTO 
                  WHERE ID_Sconto = ? 
                  AND Data_Inizio <= NOW() 
                  AND Data_Fine >= NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $discountId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /*******************
     * NOTIFICATION QUERIES *
     *******************/

    // Create notification
    public function createNotification($notificationId, $type, $message, $email) {
        $query = "INSERT INTO NOTIFICA (ID_Notifica, TipoNotifica, Messaggio, Timestamp_Invio, Tipo, Email) 
                  VALUES (?, ?, ?, NOW(), ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssss", $notificationId, $type, $message, $type, $email);
        return $stmt->execute();
    }

    // Get user notifications
    public function getUserNotifications($email) {
        $query = "SELECT * FROM NOTIFICA WHERE Email = ? ORDER BY Timestamp_Invio DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*******************
     * STATISTICS QUERIES *
     *******************/

    // Admin: Get revenue statistics
    public function getRevenueStats($startDate, $endDate) {
        $query = "SELECT DATE(Data_Ordine) as date, SUM(Costo_Totale) as revenue, COUNT(*) as orders 
                  FROM ORDINE 
                  WHERE Data_Ordine BETWEEN ? AND ? 
                  GROUP BY DATE(Data_Ordine) 
                  ORDER BY date";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Admin: Get product statistics
    public function getProductStats() {
        $query = "SELECT p.ID_Prodotto, p.Nome, p.Marca, 
                         COUNT(DISTINCT po.ID_Ordine) as total_orders,
                         SUM(po.Quantita) as total_quantity,
                         AVG(r.Punteggio) as avg_rating
                  FROM PRODOTTO p
                  LEFT JOIN PRODOTTO_ORDINE po ON p.ID_Prodotto = po.ID_Prodotto
                  LEFT JOIN RECENSIONE r ON p.ID_Prodotto = r.ID_Prodotto
                  GROUP BY p.ID_Prodotto
                  ORDER BY total_orders DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*******************
     * NEWSLETTER QUERIES *
     *******************/

    public function updateNewsletterPreference($email, $preference) {
        $query = "UPDATE UTENTE SET Preferenze_Newsletter = ? WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $preference, $email);
        return $stmt->execute();
    }

    public function getNewsletterSubscribers() {
        $query = "SELECT Email, Nome, Cognome FROM UTENTE WHERE Preferenze_Newsletter = 'Y'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /********************
     * AUTH QUERIES *
     ********************/

    // Check if user exists
    public function isUserRegistered($email) {
        $query = "SELECT Email FROM UTENTE WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // User registration
    public function registerUser($email, $firstName, $lastName, $password, $phone = null) {
        // Check if user already exists
        if (isUserRegistered($email)) {
            return false; // User already exists
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $query = "INSERT INTO UTENTE (Email, Nome, Cognome, Password, Telefono, Data_Registrazione, Preferenze_Newsletter, Ruolo) 
                VALUES (?, ?, ?, ?, ?, NOW(), 'N', 'customer')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssss", $email, $firstName, $lastName, $hashedPassword, $phone);
        
        if ($stmt->execute()) {
            // Create cart for new user
            $cartQuery = "INSERT INTO CARRELLO (Email, Data_Creazione, Data_Modifica, Valore_Totale) 
                        VALUES (?, NOW(), NOW(), 0)";
            $cartStmt = $this->db->prepare($cartQuery);
            $cartStmt->bind_param("s", $email);
            $cartStmt->execute();

            // Create wishlist for new user
            $wishlistQuery = "INSERT INTO WISHLIST (Email, Data_Creazione) VALUES (?, NOW())";
            $wishlistStmt = $this->db->prepare($wishlistQuery);
            $wishlistStmt->bind_param("s", $email);
            $wishlistStmt->execute();

            return true;
        }
        return false;
    }

    // User login
    public function loginUser($email, $password) {
        $query = "SELECT Email, Password, Nome, Cognome, Ruolo FROM UTENTE WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['Password'])) {
                // Remove password from array before returning
                unset($user['Password']);
                return $user;
            }
        }
        return false;
    }

    // Change password
    public function changePassword($email, $currentPassword, $newPassword) {
        // First verify current password
        $query = "SELECT Password FROM UTENTE WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($currentPassword, $user['Password'])) {
                // Update to new password
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE UTENTE SET Password = ? WHERE Email = ?";
                $updateStmt = $this->db->prepare($updateQuery);
                $updateStmt->bind_param("ss", $hashedNewPassword, $email);
                return $updateStmt->execute();
            }
        }
        return false;
    }

    // Get user profile
    public function getUserProfile($email) {
        $query = "SELECT Email, Nome, Cognome, Telefono, Data_Registrazione, Preferenze_Newsletter, Ruolo 
                FROM UTENTE 
                WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /*******************
     * WISHLIST QUERIES *
     *******************/

    // Get user's wishlist items
    public function getWishlistItems($email) {
        $query = "SELECT p.*, v.Colore, v.Taglia, v.Quantita 
                  FROM PRODOTTO p
                  JOIN aggiungere a ON p.ID_Prodotto = a.ID_Prodotto
                  JOIN VARIANTE v ON (a.ID_Prodotto = v.ID_Prodotto 
                                    AND a.Colore = v.Colore 
                                    AND a.Taglia = v.Taglia)
                  WHERE a.Email = ?
                  ORDER BY p.Nome";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add item to wishlist
    public function addToWishlist($email, $productId, $color, $size) {
        // Check if item already exists in wishlist
        $checkQuery = "SELECT 1 FROM aggiungere 
                      WHERE Email = ? AND ID_Prodotto = ? 
                      AND Colore = ? AND Taglia = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bind_param("sssd", $email, $productId, $color, $size);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            return false; // Item already in wishlist
        }

        // Add item to wishlist
        $query = "INSERT INTO aggiungere (Email, ID_Prodotto, Colore, Taglia) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssd", $email, $productId, $color, $size);
        return $stmt->execute();
    }

    // Remove item from wishlist
    public function removeFromWishlist($email, $productId, $color, $size) {
        $query = "DELETE FROM aggiungere 
                  WHERE Email = ? AND ID_Prodotto = ? 
                  AND Colore = ? AND Taglia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssd", $email, $productId, $color, $size);
        return $stmt->execute();
    }

    // Clear entire wishlist
    public function clearWishlist($email) {
        $query = "DELETE FROM aggiungere WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        return $stmt->execute();
    }

    // Get wishlist count
    public function getWishlistCount($email) {
        $query = "SELECT COUNT(*) as count FROM aggiungere WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    // Check if item is in wishlist
    public function isInWishlist($email, $productId, $color, $size) {
        $query = "SELECT 1 FROM aggiungere 
                  WHERE Email = ? AND ID_Prodotto = ? 
                  AND Colore = ? AND Taglia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssd", $email, $productId, $color, $size);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Move item from wishlist to cart
    public function moveToCart($email, $productId, $color, $size, $quantity = 1) {
        $this->db->begin_transaction();
        try {
            // Get user's cart ID
            $cartQuery = "SELECT ID_Carrello FROM CARRELLO WHERE Email = ?";
            $cartStmt = $this->db->prepare($cartQuery);
            $cartStmt->bind_param("s", $email);
            $cartStmt->execute();
            $cartId = $cartStmt->get_result()->fetch_assoc()['ID_Carrello'];

            // Add to cart
            $addToCartQuery = "INSERT INTO comprendere (ID_Carrello, ID_Prodotto, Colore, Taglia, Quantita) 
                              VALUES (?, ?, ?, ?, ?)";
            $addToCartStmt = $this->db->prepare($addToCartQuery);
            $addToCartStmt->bind_param("issdi", $cartId, $productId, $color, $size, $quantity);
            $addToCartStmt->execute();

            // Remove from wishlist
            $this->removeFromWishlist($email, $productId, $color, $size);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    /*******************
     * CART QUERIES *
     *******************/

    // Remove item from cart
    public function removeFromCart($cartId, $productId, $color, $size) {
        $query = "DELETE FROM comprendere 
                  WHERE ID_Carrello = ? AND ID_Prodotto = ? 
                  AND Colore = ? AND Taglia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issd", $cartId, $productId, $color, $size);
        return $stmt->execute();
    }

    // Adjust item quantity in cart
    public function adjustCartQuantity($cartId, $productId, $color, $size, $adjustment) {
        $query = "UPDATE comprendere 
                  SET Quantita = GREATEST(1, Quantita + ?) 
                  WHERE ID_Carrello = ? AND ID_Prodotto = ? 
                  AND Colore = ? AND Taglia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iissd", $adjustment, $cartId, $productId, $color, $size);
        return $stmt->execute();
    }

    // Update item size in cart
    public function updateCartItemSize($cartId, $productId, $color, $newSize) {
        $query = "UPDATE comprendere 
                  SET Taglia = ? 
                  WHERE ID_Carrello = ? AND ID_Prodotto = ? 
                  AND Colore = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("disc", $newSize, $cartId, $productId, $color);
        return $stmt->execute();
    }

    // Update item color in cart
    public function updateCartItemColor($cartId, $productId, $newColor, $size) {
        $query = "UPDATE comprendere 
                  SET Colore = ? 
                  WHERE ID_Carrello = ? AND ID_Prodotto = ? 
                  AND Taglia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sisd", $newColor, $cartId, $productId, $size);
        return $stmt->execute();
    }

    // Get all items in cart
    public function getCartItems($cartId) {
        $query = "SELECT c.*, p.Prezzo, p.Nome 
                  FROM comprendere c 
                  JOIN PRODOTTO p ON c.ID_Prodotto = p.ID_Prodotto 
                  WHERE c.ID_Carrello = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $cartId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get cart by user email
    public function getCartByEmail($email) {
        $query = "SELECT * FROM CARRELLO WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

?>