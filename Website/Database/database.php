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
        $query = "SELECT p.*, v.Colore, v.Quantita, v.Taglia 
            FROM PRODOTTO p 
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

    public function getProductData($productId, $userEmail = null) {
        $query = "SELECT 
            p.*,
            v.Colore,
            v.Taglia,
            v.Quantita,
            r.Punteggio,
            r.Descrizione as RecensioneDescrizione,
            r.Data_Recensione,
            r.Email as ReviewerEmail,
            CASE WHEN w.Email IS NOT NULL THEN 1 ELSE 0 END as InWishlist,
            CASE WHEN cart.ID_Prodotto IS NOT NULL THEN 1 ELSE 0 END as InCart,
            cart.Quantita as CartQuantity
        FROM PRODOTTO p
        LEFT JOIN VARIANTE v ON p.ID_Prodotto = v.ID_Prodotto
        LEFT JOIN RECENSIONE r ON p.ID_Prodotto = r.ID_Prodotto
        LEFT JOIN (
            SELECT a.ID_Prodotto, w.Email 
            FROM WISHLIST w 
            JOIN aggiungere a ON w.Email = a.Email 
            WHERE w.Email = ?
        ) w ON p.ID_Prodotto = w.ID_Prodotto
        LEFT JOIN (
            SELECT comp.ID_Prodotto, comp.Quantita 
            FROM CARRELLO c 
            JOIN comprendere comp ON c.ID_Carrello = comp.ID_Carrello 
            WHERE c.Email = ?
        ) cart ON p.ID_Prodotto = cart.ID_Prodotto
        WHERE p.ID_Prodotto = ?";
    
        try {
            $stmt = $this->db->prepare($query);
            if($stmt === false) {
                throw new Exception("Error preparing statement: " . $this->db->error);
            }
    
            $stmt->bind_param("ssi", $userEmail, $userEmail, $productId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $productData = [
                'product' => null,
                'variants' => [],
                'reviews' => [],
                'inWishlist' => false,
                'inCart' => false,
                'cartQuantity' => 0
            ];
    
            while($row = $result->fetch_assoc()) {
                if(!$productData['product']) {
                    $productData['product'] = [
                        'ID_Prodotto' => $row['ID_Prodotto'],
                        'Nome' => $row['Nome'],
                        'Descrizione' => $row['Descrizione'],
                        'Marca' => $row['Marca'],
                        'Tipo' => $row['Tipo'],
                        'Genere' => $row['Genere'],
                        'Prezzo' => $row['Prezzo'],
                        'Data_Aggiunta' => $row['Data_Aggiunta'],
                        'Sta_Tipo' => $row['Sta_Tipo']
                    ];
                }
    
                if($row['Colore'] && $row['Taglia']) {
                    $variantKey = $row['Colore'] . '_' . $row['Taglia'];
                    if(!isset($productData['variants'][$variantKey])) {
                        $productData['variants'][$variantKey] = [
                            'Colore' => $row['Colore'],
                            'Taglia' => $row['Taglia'],
                            'Quantita' => $row['Quantita']
                        ];
                    }
                }
    
                if($row['Punteggio']) {
                    $productData['reviews'][] = [
                        'Punteggio' => $row['Punteggio'],
                        'Descrizione' => $row['RecensioneDescrizione'],
                        'Data_Recensione' => $row['Data_Recensione'],
                        'Email' => $row['ReviewerEmail']
                    ];
                }
    
                $productData['inWishlist'] = (bool)$row['InWishlist'];
                $productData['inCart'] = (bool)$row['InCart'];
                $productData['cartQuantity'] = $row['CartQuantity'] ?? 0;
            }
    
            return $productData;
    
        } catch(Exception $e) {
            error_log("Error in getProductData: " . $e->getMessage());
            throw $e;
        }
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
    public function createNotification($type, $message, $email) {
        $query = "INSERT INTO NOTIFICA (TipoNotifica, Messaggio, Timestamp_Invio, Tipo, Email) 
                  VALUES (?, ?, NOW(), ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $type, $message, $type, $email);
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

    // Create a stock notification when a product is back in stock
    public function createStockNotification($productId) {
        $query = "SELECT p.Nome
                 FROM PRODOTTO p 
                 WHERE p.ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        
        // Get users who have this item in their wishlist
        $wishlistQuery = "SELECT DISTINCT a.Email 
                         FROM aggiungere a 
                         WHERE a.ID_Prodotto = ?";
        $wishlistStmt = $this->db->prepare($wishlistQuery);
        $wishlistStmt->bind_param("i", $productId);
        $wishlistStmt->execute();
        $users = $wishlistStmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($users as $user) {
            $message = "Great news! The product {$product['Nome']} [$productId] is back in stock";
            $this->createNotification(
                'Stock Product',
                $message,
                $user['Email']
            );
        }
    }

    // Create an order status notification
    public function createOrderNotification($orderId, $status) {
        $query = "SELECT o.Email, o.ID_Ordine 
                 FROM ORDINE o 
                 WHERE o.ID_Ordine = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();

        $messages = [
            'placed' => "Your payment has been successfully processed and we are starting to prepare your order [$orderId]",
            'shipped' => "Your order [$orderId] was handed over to the SDA express courier",
            'delivered' => "Your order [$orderId] has been delivered"
        ];

        if (isset($messages[$status])) {
            $this->createNotification(
                'Order Status',
                $messages[$status],
                $order['Email']
            );
        }
    }

    // Create a sale notification for specific products
    public function createSaleNotification($productId, $discountPercentage) {
        $query = "SELECT p.Nome, p.Marca 
                 FROM PRODOTTO p 
                 WHERE p.ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        // Get users who have this item in their wishlist
        $wishlistQuery = "SELECT DISTINCT a.Email 
                         FROM aggiungere a 
                         WHERE a.ID_Prodotto = ?";
        $wishlistStmt = $this->db->prepare($wishlistQuery);
        $wishlistStmt->bind_param("i", $productId);
        $wishlistStmt->execute();
        $users = $wishlistStmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($users as $user) {
            $message = "Your favorite product {$product['Nome']} [$productId] is now {$discountPercentage}% off for a short time";
            $this->createNotification(
                'Flash Sale',
                $message,
                $user['Email']
            );
        }
    }

    // Create a cart reminder notification
    public function createCartReminderNotification($email) {
        $query = "SELECT c.ID_Carrello, COUNT(*) as count 
                 FROM CARRELLO c 
                 JOIN comprendere co ON c.ID_Carrello = co.ID_Carrello 
                 WHERE c.Email = ?
                 GROUP BY c.ID_Carrello";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result && $result['count'] > 0) {
            $message = "You spend a lot of time browsing but, just spend a minute to make them yours!";
            $this->createNotification(
                'Cart Reminder',
                $message,
                $email
            );
        }
    }

    // Create a review request notification
    public function createReviewRequestNotification($orderId) {
        $query = "SELECT o.Email, p.Nome, p.ID_Prodotto 
                 FROM ORDINE o 
                 JOIN PRODOTTO_ORDINE po ON o.ID_Ordine = po.ID_Ordine 
                 JOIN PRODOTTO p ON po.ID_Prodotto = p.ID_Prodotto 
                 WHERE o.ID_Ordine = ? 
                 AND NOT EXISTS (
                     SELECT 1 FROM RECENSIONE r 
                     WHERE r.ID_Prodotto = po.ID_Prodotto 
                     AND r.Email = o.Email
                 )";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($items as $item) {
            $message = "How do you like your {$item['Nome']} [{$item['ID_Prodotto']}]? Share your experience!";
            $this->createNotification(
                'Review Request',
                $message,
                $item['Email']
            );
        }
    }

    public function createAdminMessageNotification($email, $message) {
        $this->createNotification(
            'Admin Message',
            $message,
            $email
        );
    }

    // Mark notifications as read
    public function markNotificationsAsRead($email, $notificationIds = null) {
        $query = "UPDATE NOTIFICA SET Tipo = 'Letta' WHERE Email = ?";
        $params = [$email];
        $types = "s";

        if ($notificationIds) {
            $query .= " AND ID_Notifica IN (" . str_repeat("?,", count($notificationIds) - 1) . "?)";
            $params = array_merge($params, $notificationIds);
            $types .= str_repeat("i", count($notificationIds));
        }

        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
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
    public function registerUser($email, $firstName, $lastName, $password, $newsletter, $phone = null) {
        try {
            $this->db->begin_transaction();
            
            /// Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $userQuery = "INSERT INTO UTENTE (Email, Nome, Cognome, Password, Telefono, Data_Registrazione, Preferenze_Newsletter, Ruolo) 
                        VALUES (?, ?, ?, ?, ?, NOW(), ?, 'Customer')";
            $userStmt = $this->db->prepare($userQuery);
            $userStmt->bind_param("sssssi", $email, $firstName, $lastName, $hashedPassword, $phone, $newsletter);
            $userStmt->execute();

            // Create cart for new user
            $cartQuery = "INSERT INTO CARRELLO (Email, Data_Modifica, Valore_Totale) 
                        VALUES (?, NOW(), 0)";
            $cartStmt = $this->db->prepare($cartQuery);
            $cartStmt->bind_param("s", $email);
            $cartStmt->execute();

            // Create wishlist for new user
            $wishlistQuery = "INSERT INTO WISHLIST (Email, Data_Modifica) VALUES (?, NOW())";
            $wishlistStmt = $this->db->prepare($wishlistQuery);
            $wishlistStmt->bind_param("s", $email);
            $wishlistStmt->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
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
    public function changePassword($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE UTENTE SET Password = ? WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $hashedPassword, $email);
        return $stmt->execute();
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
        $query = "SELECT p.* FROM PRODOTTO p
                  JOIN aggiungere a ON p.ID_Prodotto = a.ID_Prodotto
                  WHERE a.Email = ?
                  ORDER BY p.Nome";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add item to wishlist
    public function addToWishlist($email, $productId) {
        // Check if item already exists in wishlist
        $checkQuery = "SELECT 1 FROM aggiungere 
                      WHERE Email = ? AND ID_Prodotto = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bind_param("ss", $email, $productId);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            return false; // Item already in wishlist
        }

        // Add item to wishlist
        $query = "INSERT INTO aggiungere (Email, ID_Prodotto) 
                  VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $email, $productId);
        return $stmt->execute();
    }

    // Remove item from wishlist
    public function removeFromWishlist($email, $productId) {
        $query = "DELETE FROM aggiungere 
                  WHERE Email = ? AND ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $email, $productId);
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
    public function isInWishlist($email, $productId) {
        $query = "SELECT 1 FROM aggiungere 
                  WHERE Email = ? AND ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $email, $productId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    /*******************
     * CART QUERIES *
     *******************/

    // Add product to cart
    public function addToCart($email, $productId, $color, $size, $quantity = 1) {
        // First get the cart ID
        $cartInfo = $this->getCartByEmail($email);
        if (!$cartInfo) {
            return false;
        }
        $cartId = $cartInfo['ID_Carrello'];
        
        // Check if item already exists in cart
        $checkQuery = "SELECT Quantita FROM comprendere 
                    WHERE ID_Carrello = ? AND ID_Prodotto = ? 
                    AND Colore = ? AND Taglia = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bind_param("issd", $cartId, $productId, $color, $size);
        $checkStmt->execute();
        $existingItem = $checkStmt->get_result()->fetch_assoc();
        
        if ($existingItem) {
            // Update quantity if item exists
            $newQuantity = $existingItem['Quantita'] + $quantity;
            $updateQuery = "UPDATE comprendere 
                        SET Quantita = ? 
                        WHERE ID_Carrello = ? AND ID_Prodotto = ? 
                        AND Colore = ? AND Taglia = ?";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bind_param("iissd", $newQuantity, $cartId, $productId, $color, $size);
            return $updateStmt->execute();
        } else {
            // Add new item if it doesn't exist
            $insertQuery = "INSERT INTO comprendere (ID_Carrello, ID_Prodotto, Colore, Taglia, Quantita) 
                        VALUES (?, ?, ?, ?, ?)";
            $insertStmt = $this->db->prepare($insertQuery);
            $insertStmt->bind_param("issdi", $cartId, $productId, $color, $size, $quantity);
            return $insertStmt->execute();
        }
    }
    
     // Remove item from cart
    public function removeFromCart($email, $productId, $color, $size) {
        // First get the cart ID
        $cartInfo = $this->getCartByEmail($email);
        if (!$cartInfo) {
            return false;
        }
        $cartId = $cartInfo['ID_Carrello'];
        
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
    //returns true if the variant of that color and size exists
    public function getColorsBySize($productId, $size) {
        $query = "SELECT DISTINCT Colore 
                  FROM VARIANTE 
                  WHERE ID_Prodotto = ? 
                  AND Taglia = ? 
                  AND Quantita > 0 
                  ORDER BY Colore";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $productId, $size);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    // Get all Sizes of a product color
    public function getSizesByColor($productId, $color) {
        $query = "SELECT DISTINCT v.Taglia, v.Quantita 
                FROM VARIANTE v 
                WHERE v.ID_Prodotto = ? 
                AND v.Colore = ? 
                AND v.Quantita > 0 
                ORDER BY v.Taglia";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $productId, $color);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get all Colors of a product
    public function getProductColors($productId) {
        $query = "SELECT DISTINCT v.Colore 
                FROM VARIANTE v 
                WHERE v.ID_Prodotto = ? 
                AND v.Quantita > 0 
                ORDER BY v.Colore";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

        // Get all Sizes of a product
        public function getProductSizes($productId) {
            $query = "SELECT DISTINCT v.Taglia 
                    FROM VARIANTE v 
                    WHERE v.ID_Prodotto = ? 
                    AND v.Quantita > 0 
                    ORDER BY v.Taglia";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $productId);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    

    // Get max product quantity
    public function getProductMaxQuantity($productId, $color, $size) {
        $query = "SELECT Quantita FROM VARIANTE 
                  WHERE ID_Prodotto = ? AND Colore = ? AND Taglia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isd", $productId, $color, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['Quantita'];
    }

    // Get all items in cart
    public function getCartItems($cartId) {
        $query = "SELECT c.*, p.Prezzo, p.Nome, p.Genere 
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

    /*********************
     * REVIEW FUNCTIONS *
     *********************/

    // Add new review
    public function addReview($email, $productId, $rating, $comment) {
        // Check if user has already reviewed this product
        $checkQuery = "SELECT 1 FROM RECENSIONE 
                    WHERE Email = ? AND ID_Prodotto = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bind_param("ss", $email, $productId);
        $checkStmt->execute();
        
        if ($checkStmt->get_result()->num_rows > 0) {
            // Update existing review
            $query = "UPDATE RECENSIONE 
                    SET Punteggio = ?, Testo = ?, Data_Recensione = NOW() 
                    WHERE Email = ? AND ID_Prodotto = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("isss", $rating, $comment, $email, $productId);
        } else {
            // Add new review
            $query = "INSERT INTO RECENSIONE (Email, ID_Prodotto, Punteggio, Testo, Data_Recensione) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssis", $email, $productId, $rating, $comment);
        }
        
        return $stmt->execute();
    }

    // Get product reviews
    public function getProductReviews($productId) {
        $query = "SELECT r.*, u.Nome, u.Cognome 
                FROM RECENSIONE r 
                JOIN UTENTE u ON r.Email = u.Email 
                WHERE r.ID_Prodotto = ? 
                ORDER BY r.Data_Recensione DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Delete review
    public function deleteReview($email, $productId) {
        $query = "DELETE FROM RECENSIONE 
                WHERE Email = ? AND ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $email, $productId);
        return $stmt->execute();
    }

    // Get average product rating
    public function getProductRating($productId) {
        $query = "SELECT AVG(Punteggio) as avg_rating, COUNT(*) as review_count 
                FROM RECENSIONE 
                WHERE ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Check if user can review (has purchased the product)
    public function canUserReview($email, $productId) {
        $query = "SELECT 1 
                FROM ORDINE o 
                JOIN PRODOTTO_ORDINE po ON o.ID_Ordine = po.ID_Ordine 
                WHERE o.Email = ? AND po.ID_Prodotto = ? 
                AND o.Tipo = 'delivered'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $email, $productId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}

?>