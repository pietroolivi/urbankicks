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

    // Returns product information by ID
    public function getProductById($productId) {
        $query = "SELECT * FROM PRODOTTO WHERE ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Returns all available product variants
    public function getProductVariants($productId) {
        $query = "SELECT * FROM VARIANTE WHERE ID_Prodotto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Returns price history for a product
    public function getProductPriceHistory($productId) {
        $query = "SELECT * FROM PRODOTTO_STORICO WHERE ID_Prodotto = ? ORDER BY Data_Modifica DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*******************
     * USER QUERIES *
     *******************/

    // Returns user information
    public function getUserByEmail($email) {
        $query = "SELECT * FROM UTENTE WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Register new user
    public function registerUser($email, $nome, $cognome, $password, $telefono, $newsletter) {
        $query = "INSERT INTO UTENTE (Email, Nome, Cognome, Password, Telefono, Data_Registrazione, Preferenze_Newsletter, Ruolo) 
                  VALUES (?, ?, ?, ?, ?, NOW(), ?, 'user')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssssss", $email, $nome, $cognome, $password, $telefono, $newsletter);
        return $stmt->execute();
    }

    /*******************
     * CART QUERIES *
     *******************/

    // Get user's cart
    public function getUserCart($email) {
        $query = "SELECT * FROM CARRELLO WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get products in cart
    public function getCartProducts($cartId) {
        $query = "SELECT p.*, v.*, c.Quantita 
                  FROM comprendere c 
                  JOIN PRODOTTO p ON c.ID_Prodotto = p.ID_Prodotto 
                  JOIN VARIANTE v ON c.ID_Prodotto = v.ID_Prodotto 
                     AND c.Colore = v.Colore 
                     AND c.Taglia = v.Taglia 
                  WHERE c.ID_Carrello = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $cartId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add product to cart
    public function addToCart($cartId, $productId, $color, $size, $quantity) {
        $query = "INSERT INTO comprendere (ID_Carrello, ID_Prodotto, Colore, Taglia, Quantita) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssdi", $cartId, $productId, $color, $size, $quantity);
        return $stmt->execute();
    }

    /*******************
     * ORDER QUERIES *
     *******************/

    // Create new order
    public function createOrder($email, $paymentMethod, $shippingType, $paymentId) {
        $query = "INSERT INTO ORDINE (ID_Ordine, Data_Ordine, Costo_Totale, Metodo_Pagamento, Tipo, Email, IDPagamento) 
                  VALUES (UUID(), NOW(), 0, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $paymentMethod, $shippingType, $email, $paymentId);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Get user's orders
    public function getUserOrders($email) {
        $query = "SELECT * FROM ORDINE WHERE Email = ? ORDER BY Data_Ordine DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get order details
    public function getOrderProducts($orderId) {
        $query = "SELECT p.*, po.* 
                  FROM PRODOTTO_ORDINE po 
                  JOIN PRODOTTO p ON po.ID_Prodotto = p.ID_Prodotto 
                  WHERE po.ID_Ordine = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*******************
     * WISHLIST QUERIES *
     *******************/

    // Get user's wishlist products
    public function getWishlistProducts($email) {
        $query = "SELECT p.*, v.* 
                  FROM aggiungere a 
                  JOIN PRODOTTO p ON a.ID_Prodotto = p.ID_Prodotto 
                  JOIN VARIANTE v ON a.ID_Prodotto = v.ID_Prodotto 
                     AND a.Colore = v.Colore 
                     AND a.Taglia = v.Taglia 
                  WHERE a.Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add product to wishlist
    public function addToWishlist($email, $productId, $color, $size) {
        $query = "INSERT INTO aggiungere (Email, ID_Prodotto, Colore, Taglia) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssd", $email, $productId, $color, $size);
        return $stmt->execute();
    }

    /*******************
     * REVIEW QUERIES *
     *******************/

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
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add product review
    public function addReview($productId, $email, $rating, $description) {
        $query = "INSERT INTO RECENSIONE (ID_Prodotto, Email, Punteggio, Descrizione, Data_Recensione) 
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssds", $productId, $email, $rating, $description);
        return $stmt->execute();
    }

    /*******************
     * ADDRESS QUERIES *
     *******************/

    // Get user addresses
    public function getUserAddresses($email) {
        $query = "SELECT * FROM INDIRIZZO WHERE Email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add new address
    public function addAddress($email, $street, $number, $zip, $city, $province, $country, $isDefault) {
        $query = "INSERT INTO INDIRIZZO (Email, Via, NumeroCivico, CAP, Citta, Provincia, Nazione, Predefinito) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssisssss", $email, $street, $number, $zip, $city, $province, $country, $isDefault);
        return $stmt->execute();
    }
}
?>