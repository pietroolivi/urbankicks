<!-- Step indicators will always be visible -->
<header>
        <a href="javascript:history.back()" class="back">
            <img src="CSS/Images/Icons/back.svg" alt="Go back">
        </a>
        <h2>Checkout</h2>
        <img id="progress-bar" src="CSS/Images/Illustrations/progress_bar1.svg" alt="Checkout progress">
        <h3 id="step-title">ORDER SUMMARY</h3>
    </header>

<!-- Summary Step -->
<div id="summary-step" class="checkout-step active">
        <div>
            <ul class="products-overview">
                <?php foreach($templateParams["cart"] as $product): ?>
                <li>
                    <article>
                        <img src="CSS/Images/Products/<?php echo htmlspecialchars($product['ID_Prodotto']. '_' . $product['Genere'] . "1"); ?>.webp" 
                            alt="<?php echo htmlspecialchars($product['Nome']); ?>">
                        <h4><?php echo $product["Nome"]; ?></h4>
                        <p>Size: <?php echo $product["Taglia"]; ?> | Qty: <?php echo $product["Quantita"]; ?></p>
                        <p>Color: <?php echo $product["Colore"]; ?></p>
                        <p>€<?php echo number_format($product["Prezzo"], 2); ?></p>
                    </article>
                </li>
                <?php endforeach; ?>
            </ul>

            <form class="promo-code" id="promo-form">
                <label for="promo-code-input">Please, enter a discount code if you have one.</label>
                <input id="promo-code-input" type="text" placeholder="Promo code">
                <button type="submit">Apply</button>
                <span id="promo-message"></span>
            </form>

            <div class="gift-wrap">
                <input id="gift-wrap-checkbox" type="checkbox">
                <label for="gift-wrap-checkbox">
                    <img src="CSS/Images/Icons/gift.svg" alt="">Gift Wrap this order?
                </label>
                <p>+€5.00</p>
            </div>
        </div>

        <footer>
            <p>SUBTOTAL €<span id="subtotal">0</span></p>
            <p id="discount-row" style="display:none">DISCOUNT -€<span id="discount">0.00</span></p>
            <p>NEW SUBTOTAL €<span id="new-subtotal">0</span></p>
            <button onclick="nextStep('shipping')">Next<img src="CSS/Images/Icons/next.svg" alt=""></button>
        </footer>
    </div>

<!-- Shipping Step -->
<div id="shipping-step" class="checkout-step">
    <form id="shipping-form">
        <fieldset>
            <legend>ADDRESS</legend>
            <label for="first-name-shipping">First Name</label>
            <input id="first-name-shipping" type="text" required />
            <!-- Rest of shipping form -->
        </fieldset>
    </form>
    <button onclick="nextStep('payment')">Next</button>
</div>

<!-- Payment Step -->
<div id="payment-step" class="checkout-step">
    <form id="payment-form" action="checkout_handler.php" method="POST">
        <fieldset>
            <legend>PAYMENT METHOD</legend>
            <!-- Payment form content -->
        </fieldset>
        <button type="submit">Place Order</button>
    </form>
</div>
