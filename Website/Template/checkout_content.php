<!-- Step indicators will always be visible -->
<header>
    <a href="#" onclick="goBack(); return false;" class="back">
        <img src="CSS/Images/Icons/back.svg" alt="Go back">
    </a>
    <h2>Checkout</h2>
    <img id="progress-bar" src="CSS/Images/Illustrations/progress_bar1.svg" alt="Checkout progress">
    <h3 id="step-title">ORDER SUMMARY</h3>
</header>

<!-- Summary Step -->
<div id="summary-step" class="checkout-step active">
    <div>
        <ul class="products-overview" data-total="<?php echo $templateParams["cart"][0]["Valore_Totale"]; ?>">
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
            <input id="first-name-shipping" type="text" required/>
            <label for="last-name-shipping">Last Name</label>
            <input id="last-name-shipping" type="text" required/>
            <div id="invariable-part-address">
                <p>Cesena Campus - UniBo</p>
                <p>Piazza Aldo Moro, 90</p>
                <p>Cesena (FC), 47521</p>
            </div>
            <script>
                document.getElementById('invariable-part-address').onclick = function() {
                    alert("It is not possible to change the position.");
                }
            </script>
        </fieldset>
        <fieldset>
            <legend>DELIVERY OPTIONS</legend>
            <div id="standard-paid-option">
                <input id="shipping-standard-paid" type="radio" name="delivery-options" value="Standard" data-cost="5.00"/>
                <label for="shipping-standard-paid"><img src="CSS/Images/Icons/shipping_standard.svg" alt="" />Standard</label>
                <p>5-7 weekdays</p>
                <p>+€5.00</p>
            </div>
            <div id="standard-free-option">
                <input id="shipping-standard-free" type="radio" name="delivery-options" value="Standard" data-cost="0.00"/>
                <label for="shipping-standard-free"><img src="CSS/Images/Icons/shipping_standard.svg" alt="">Standard</label>
                <p>orders over €200</p>
                <p>FREE</p>
            </div>
            <div>
                <input id="shipping-express" type="radio" name="delivery-options" value="Express" data-cost="10.00"/>
                <label for="shipping-express"><img src="CSS/Images/Icons/shipping_express.svg" alt="" />Express</label>
                <p>2-4 weekdays</p>
                <p>+€10.00</p>
            </div>
        </fieldset>
    </form>
    <footer>
        <p>SUBTOTAL €<span id="shipping-subtotal">0</span></p>
        <p>TOTAL €<span id="shipping-total">0</span></p>
        <button onclick="nextStep('payment')">Next<img src="CSS/Images/Icons/next.svg" alt=""></button>
    </footer>
</div>

<!-- Payment Step -->
<div id="payment-step" class="checkout-step">
    <form id="payment-form">
        <fieldset>
            <legend>Please, enter the details of a payment method to complete the purchase.</legend>
            
            <div class="payment-method-selection">
                <div onclick="changePaymentMethod('credit-card')">
                    <input id="credit-card" type="radio" name="payment-method" value="credit-card" checked>
                    <label for="credit-card">Credit Card</label>
                    <img src="CSS/Images/Icons/visa.svg" alt="Visa">
                    <img src="CSS/Images/Icons/mastercard.svg" alt="Mastercard">
                    <img src="CSS/Images/Icons/maestro.svg" alt="Maestro">
                    <img src="CSS/Images/Icons/amex.svg" alt="American Express">
                    <img src="CSS/Images/Icons/diners_club.svg" alt="Diners Club">
                </div>
                
                <div onclick="changePaymentMethod('paypal')">
                    <input id="paypal" type="radio" name="payment-method" value="Paypal">
                    <label for="paypal">PayPal</label>
                    <img src="CSS/Images/Icons/paypal.svg" alt="PayPal">
                </div>
            </div>

            <div class="credit-card-fields">
                <label for="card-holder">Card Holder</label>
                <input type="text" id="card-holder" name="card-holder" required>
                
                <label for="card-number">Card Number</label>
                <input type="text" id="card-number" name="card-number" pattern="[0-9]{16}" maxlength="16" required>
                
                <label for="expiration-date">Expiration Date</label>
                <input type="month" id="expiration-date" name="expiration-date" required>
                
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" pattern="[0-9]{3}" maxlength="3" required>
            </div>

            <div class="paypal-fields" style="display: none;">
                <label for="email-paypal">PayPal Email</label>
                <input type="email" id="email-paypal" name="email-paypal">
            </div>
        </fieldset>
        <footer>
            <button type="submit">Place Order (€<span id="payment-total">0.00</span>)</button>
        </footer>
    </form>
</div>
