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
}

?>