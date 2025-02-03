<?php
$cartItems = [];
$cartTotal = 0;
if (isset($_SESSION["user_email"])) {
    $cartInfo = $dbh->getCartByEmail($_SESSION["user_email"]);
    if ($cartInfo) {
        $cartItems = $dbh->getCartItems($cartInfo['ID_Carrello']);
        $cartTotal = $cartInfo['Valore_Totale'];
    }
}
?>

<a href="javascript:history.back()" class="back">
    <img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page." />
</a>
<h2 id="cart-title">My Cart</h2>

<?php if (empty($cartItems)): ?>
    <img src="CSS/Images/Illustrations/empty_cart.svg" alt="Illustration of an empty shopping cart">
    <p>Hey, The cart feels light!</p>
    <p>Explore products and add your favorite items</p>
    <a href="home.php" class="explore-now">Explore Now</a>
<?php else: ?>
    <div class="warning-free-shipping">
        <img src="CSS/Images/Icons/information.svg" alt="Information symbol">
        <?php if ($cartTotal >= 100): ?>
            <p>You qualify for FREE STANDARD SHIPPING!</p>
        <?php else: ?>
            <p>Just €<?= number_format(100 - $cartTotal, 2) ?> away from getting FREE STANDARD SHIPPING</p>
        <?php endif; ?>
    </div>
    <ul id="products-container" class="lateral-container">
        <?php foreach ($cartItems as $item): ?>
            <li data-product-id="<?= htmlspecialchars($item['ID_Prodotto']) ?>"
                data-color="<?= htmlspecialchars($item['Colore']) ?>"
                data-size="<?= htmlspecialchars($item['Taglia']) ?>"
                data-cart-id="<?= htmlspecialchars($cartInfo['ID_Carrello']) ?>">
                <article class="product-card">
                    <h3><?= htmlspecialchars($item['Nome']) ?></h3>
                    <div class="product-details">
                        <a class="product-link">
                        <img src="CSS/Images/Products/<?php echo htmlspecialchars($item["Nome"]. "_" . "1"); ?>.webp" 
                            alt="<?php echo htmlspecialchars($item["Nome"]); ?>"> </a>
                        <div class="item-info">
                            <div class="selection-div">
                            <label for="size-selector-<?= $item['ID_Prodotto'] ?>">Size
                            </label>
                            <select id="size-selector-<?= $item['ID_Prodotto'] ?>" class="size-selector">
                                <?php 
                                $sizes = $dbh->getSizesByColor($item['ID_Prodotto'], $item['Colore']); 
                                foreach ($sizes as $sizeData): 
                                ?>
                                    <option value="<?= htmlspecialchars($sizeData['Taglia']) ?>" 
                                            <?= $sizeData['Taglia'] == $item['Taglia'] ? 'selected="selected"' : '' ?>>
                                        <?= htmlspecialchars($sizeData['Taglia']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                                </div>
                            <div class="selection-div">
                                <label for="color-selector-<?= $item['ID_Prodotto'] ?>">Color
                                </label>
                                <select id="color-selector-<?= $item['ID_Prodotto'] ?>" class="color-selector">
                                    <?php 
                                    $colors = $dbh->getProductColors($item['ID_Prodotto']);
                                    foreach ($colors as $colorData): 
                                    ?>
                                    <option value="<?= htmlspecialchars($colorData['Colore']) ?>"
                                            <?= $colorData['Colore'] == $item['Colore'] ? 'selected="selected"' : '' ?>>
                                            <?= htmlspecialchars($colorData['Colore']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                                </div>
                                <div class="selection-div">
                                    <!-- <label for="quantity-selector-<?= $item['ID_Prodotto'] ?>">
                                    Quantity
                                    </label> -->
                                    <div class="quantity-control">
                                        <button type="button" class="quantity-btn increment">+</button>
                                        <span class="quantity-display"><?= htmlspecialchars($item['Quantita']) ?></span>
                                        <button type="button" class="quantity-btn decrement">-</button>
                                    </div>
                                    <input id="quantity-selector-<?= $item['ID_Prodotto'] ?>" 
                                        type="hidden" 
                                        name="quantity" 
                                        value="<?= htmlspecialchars($item['Quantita']) ?>" />
                                </div>
                            <p class="price">
                                €<?= number_format($item['Prezzo'], 2) ?>
                                <?php if (isset($item['PrezzoOriginale']) && $item['PrezzoOriginale'] > $item['Prezzo']): ?>
                                    <s>€<?= number_format($item['PrezzoOriginale'], 2) ?></s>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button class="move-to-wishlist">
                            Move to wishlist <img src="CSS/Images/Icons/heart_empty.svg" alt="" style="display: inline  vertical-align: middle;">
                        </button>
                        <button class="remove-from-cart">
                            Remove from cart <img src="CSS/Images/Icons/bin.svg" alt="" style="display: inline  vertical-align: middle;">
                        </button>
                    </div>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="cart-total-container">
    <p><?= count($cartItems) ?> ITEMS</p>
        <span>Total:</span>
        <span class="cart-total">€<?= number_format($cartTotal, 2) ?></span>
    </div>
    <div class="last-cart-actions">
        <div class="cont-proc-buttons">
            <button class="continue-shopping" onclick="window.location.href='home.php'">Continue shopping</button>
            <button class="proceed-checkout" onclick="window.location.href='checkout.php'">Proceed to checkout</button>
        </div>
    </div>
    <?php endif; ?>