<?php if(!isset($templateParams["tracking"])): ?>
    <p>Order tracking information not available.</p>
<?php else: ?>
    <section class="tracking-section">
        <header>
            <a href="javascript:history.back()" class="back"><img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page." /></a>
            <h2>Track Order</h2>
        </header>

        <div class="order-info">
            <h3>#<?php echo htmlspecialchars($_GET["order"]); ?></h3>
        </div>

        <ul class="products-overview">
            <?php foreach($templateParams["tracking"] as $key => $item): 
                if(!is_array($item) || in_array($key, ['status', 'order_date', 'location', 'timestamp'])) continue; ?>
                <li>
                    <article>
                        <img src="CSS/Images/Products/<?php echo htmlspecialchars($item['image']); ?>.webp" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" />
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p>Size: <?php echo htmlspecialchars($item['size']); ?> | Qty: <?php echo htmlspecialchars($item['quantity']); ?></p>
                        <p>Color: <?php echo htmlspecialchars($item['color']); ?></p>
                        <p>€<?php echo number_format($item['price'], 2); ?>
                        </p>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>

        <section>
            <h3>ORDER DETAILS</h3>
            <p>Expected delivery date</p>
            <p>03 Sep 2024</p>
            <p>Tracking ID</p>
            <p><a href="#order-status">TRK452126542</a></p>
        </section>
        <section id="order-status">                
            <h3>ORDER STATUS</h3>
            <div>
                <img src="CSS/Images/Illustrations/tracking_step2.svg" alt="The second of four steps to complete the order was completed.">
                <ol>
                    <li>
                        <em>Order placed</em>
                        <p>23 Aug 2024, 04:25 PM</p>
                    </li>
                    <li>
                        <em>In progress</em>
                        <p>23 Aug 2024, 05:25 PM</p>
                    </li>
                    <li>
                        <p>Shipped</p>
                        <p>Expected 24 Aug 2024</p>
                    </li>
                    <li>
                        <p>Delivered</p>
                        <p>Expected 03 Sep 2024</p>
                    </li>
                </ol>
            </div>
        </section>
        <section>
            <h3>PARCEL LOCATION</h3>
            <img src="CSS/Images/Illustrations/map.svg" alt="">
        </section>
    </section>
<?php endif; ?>