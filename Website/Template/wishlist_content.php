<h2>My Wishlist</h2>
<?php if(!empty($templateParams["wishlistItems"])): ?>
    <nav>
        <ul id="products-container">
        <<?php foreach($templateParams["wishlistItems"] as $item):?>
        <li class="product-card" data-product-id="<?php echo $item['ID_Prodotto'];?>">
        <a class="product-link" href="product.php?id=<?php echo htmlspecialchars($item["ID_Prodotto"]);?>">
            <img src="CSS/Images/Products/<?php echo htmlspecialchars($item["ID_Prodotto"]."_".$item["Genere"]."1");?>.webp" alt="<?php echo htmlspecialchars($item["Nome"]);?>">
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