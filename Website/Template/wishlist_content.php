<h2>My Wishlist</h2>
<?php if(empty($templateParams["wishlistItems"])): ?>
    <p>Your wishlist is empty. Browse our products to add items!</p>
<?php else: ?>
    <nav>
        <ul>
            <?php foreach($templateParams["wishlistItems"] as $item): ?>
                <li>
                    <article>
                        <h3><?php echo htmlspecialchars($item["Nome"]); ?></h3>
                        <div class="product-details">
                            <img src="CSS/Images/Products/<?php echo htmlspecialchars($item["ID_Prodotto"]); ?>.jpg" 
                                    alt="<?php echo htmlspecialchars($item["Nome"]); ?>">
                            <div class="item-info">
                                <p class="price">€<?php echo number_format($item["Prezzo"], 2); ?></p>
                            </div>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>