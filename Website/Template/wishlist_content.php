<h2>My Wishlist</h2>
<?php if(!empty($templateParams["wishlistItems"])): ?>
    <nav>
        <ul>
            <?php foreach($templateParams["wishlistItems"] as $item): ?>
                <li>
                    <article>
                        <div class="product-details">
                            <a href="product.php?id=<?php echo htmlspecialchars($item["ID_Prodotto"]); ?>">
                                <img src="CSS/Images/Products/<?php echo htmlspecialchars($item["ID_Prodotto"]. "_" . $item["Genere"] . "1"); ?>.webp" 
                                     alt="<?php echo htmlspecialchars($item["Nome"]); ?>">
                            </a>
                            <h3><?php echo htmlspecialchars($item["Nome"]); ?></h3>
                            <div class="item-info">
                                <p class="price">€<?php echo number_format($item["Prezzo"], 2); ?></p>
                            </div>
                            <span>
                                <img id="heart-icon-<?php echo $item['ID_Prodotto']; ?>" 
                                    class="wishlist-heart"
                                    data-product-id="<?php echo $item['ID_Prodotto']; ?>"
                                    src="CSS/Images/Icons/heart_filled.svg" 
                                    alt="Click to remove from wishlist.">
                            </span>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>