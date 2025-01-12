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
<h2>My Cart</h2>

<?php if (empty($cartItems)): ?>
    <img src="CSS/Images/Illustrations/empty_cart.svg" alt="Illustration of an empty shopping cart">
    <p>Hey, The cart feels light!</p>
    <p>Explore products and add your favorite items</p>
    <a href="home.php" class="explore-now">Explore Now</a>
<?php else: ?>
    <div class="warning-free-shipping">
        <img src="CSS/Images/Icons/information.svg" alt="Information symbol">
        <?php if ($cartTotal >= 100): ?>
            <p>Your order is eligible for FREE STANDARD SHIPPING!</p>
        <?php else: ?>
            <p>Just €<?= number_format(100 - $cartTotal, 2) ?> away from getting FREE STANDARD SHIPPING</p>
        <?php endif; ?>
    </div>
    <ul>
        <?php foreach ($cartItems as $item): ?>
            <li data-product-id="<?= htmlspecialchars($item['ID_Prodotto']) ?>"
                data-color="<?= htmlspecialchars($item['Colore']) ?>"
                data-size="<?= htmlspecialchars($item['Taglia']) ?>"
                data-cart-id="<?= htmlspecialchars($cartInfo['ID_Carrello']) ?>">
                <article>
                    <h3><?= htmlspecialchars($item['Nome']) ?></h3>
                    <div class="product-details">
                        <img src="CSS/Images/Products/<?php echo htmlspecialchars($item["ID_Prodotto"]. "_" . $item["Genere"] . "1"); ?>.webp" 
                            alt="<?php echo htmlspecialchars($item["Nome"]); ?>">
                        <div class="item-info">
                            <label for="size-selector-<?= $item['ID_Prodotto'] ?>">
                                Please select the size of <?= htmlspecialchars($item['Nome']) ?> you wish to purchase.
                            </label>
                            <select id="size-selector-<?= $item['ID_Prodotto'] ?>" class="size-selector">
                                <?php 
                                $sizes = $dbh->getProductSizes($item['ID_Prodotto'], $item['Colore']); 
                                foreach ($sizes as $sizeData): 
                                ?>
                                    <option value="<?= htmlspecialchars($sizeData['Taglia']) ?>" 
                                            <?= $sizeData['Taglia'] == $item['Taglia'] ? 'selected="selected"' : '' ?>>
                                        <?= htmlspecialchars($sizeData['Taglia']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

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

                            <label for="quantity-selector-<?= $item['ID_Prodotto'] ?>">
                                Please, select the quantity of <?= htmlspecialchars($item['Nome']) ?> 
                                in color <?= htmlspecialchars($item['Colore']) ?> and size <?= htmlspecialchars($item['Taglia']) ?> 
                                that you wish to purchase.
                            </label>
                            <input id="quantity-selector-<?= $item['ID_Prodotto'] ?>" 
                                   type="number" 
                                   value="<?= htmlspecialchars($item['Quantita']) ?>" 
                                   min="1" max="<?= $dbh->getProductMaxQuantity($item['ID_Prodotto'], $item['Colore'], $item['Taglia']) ?>"/>

                            <p class="price">
                                €<?= number_format($item['Prezzo'], 2) ?>
                                <?php if (isset($item['PrezzoOriginale']) && $item['PrezzoOriginale'] > $item['Prezzo']): ?>
                                    <s>€<?= number_format($item['PrezzoOriginale'], 2) ?></s>
                                <?php endif; ?>
                            </p>
                        </div>
                        <button class="move-to-wishlist">
                            Move to wishlist <img src="CSS/Images/Icons/heart_empty.svg" alt="">
                        </button>
                        <button class="remove-from-cart">
                            Remove from cart <img src="CSS/Images/Icons/bin.svg" alt="">
                        </button>
                    </div>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
    <p><?= count($cartItems) ?> ITEMS</p>
    <p>SUBTOTAL €<?= number_format($cartTotal, 2) ?></p>
    <button class="continue-shopping">Continue shopping</button>
    <button class="proceed-checkout">Proceed to checkout</button>
<?php endif; ?>