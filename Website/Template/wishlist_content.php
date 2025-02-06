<?php
if(isset($_SESSION['error'])) {
    echo "<input type='hidden' id='error-message' value='" . htmlspecialchars($_SESSION['error']) . "'>";
    unset($_SESSION['error']);
}
?>

<h2>My Wishlist</h2>
<?php if(!empty($templateParams["wishlistItems"])): ?>
    <nav>
        <ul class="products-container">
        <?php foreach($templateParams["wishlistItems"] as $item):?>
        <li class="product-card" data-product-id="<?php echo $item['ID_Prodotto'];?>">
        <a class="product-link" href="product.php?id=<?php echo htmlspecialchars($item["ID_Prodotto"]);?>">
        <?php
                    $nomeFile = str_replace(' ', '', $item['Nome']) . '_1.webp';
                    $percorsoFile ='CSS/Images/Products/' . $nomeFile;
                    if (file_exists($percorsoFile)) {
                        echo '<img src="CSS/Images/Products/' . htmlspecialchars(str_replace(' ', '', $item['Nome']) . '_' . '1') . '.webp" alt="' . htmlspecialchars(str_replace(' ', '', $item['Nome'])) . '">';
                    } else {
                        // Il file non esiste, ad esempio mostra un'immagine di default oppure un messaggio di errore
                        echo '<img src="CSS/Images/Products/default_shoe.webp" alt="Immagine non disponibile">';
                    }
                ?>
            <label class="wishlist-container">
            <input type="checkbox" class="wishlist-checkbox" hidden>
            <img id="heart-icon-<?php echo $item['ID_Prodotto'];?>" 
            class="wishlist-heart wishlist-checkbox"
            src="CSS/Images/Icons/heart_filled.svg" 
            alt="Click to remove from wishlist."></label></a>
         <h3 class="product-name"><?php echo htmlspecialchars($item["Nome"]);?></h3>
         <p class="product-price">€<?php echo number_format($item["Prezzo"],2);?></p>
        </li>
        <?php endforeach;?>
        </ul>
</nav>
<?php endif; ?>