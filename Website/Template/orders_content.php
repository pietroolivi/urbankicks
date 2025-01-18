<section class="orders-section">
    <h2>My Orders</h2>
    <?php if(empty($templateParams["orders"])): ?>
        <p>You haven't placed any orders yet.</p>
    <?php else: ?>
        <!-- Ongoing Orders Section -->
        <h3>Ongoing Orders</h3>
        <div class="orders-container">
            <?php foreach($templateParams["orders"] as $order): ?>
                <?php if($order["Tipo"] !== "Delivered"): ?>
                    <article class="order-card">
                        <header>
                            <p>Order #<?php echo $order["ID_Ordine"]; ?></p>
                            <p class="order-date"> <?php echo date("F j, Y", strtotime($order["Data_Ordine"])); ?></p>
                            <a href="tracking.php?order=<?php echo $order["ID_Ordine"]; ?>" class="track-link">Track Order</a>
                        </header>
                        <div class="order-details">
                            <div class="order-items">
                                <?php foreach($order["products"] as $product): ?>
                                    <div class="order-item">
                                    <img src="CSS/Images/Products/<?php echo htmlspecialchars($product['ID_Prodotto']. '_' . $product['Genere'] . "1"); ?>.webp" 
                                        alt="<?php echo htmlspecialchars($product['Nome']); ?>">
                                        <div class="item-info">
                                            <h4><?php echo $product["Nome"]; ?></h4>
                                            <p>Size: <?php echo (int)$product["Taglia"]; ?> | Qty: <?php echo $product["Quantita"]; ?></p>
                                            <p>Color: <?php echo $product["Colore"]; ?></p>
                                            <p>Price: €<?php echo number_format($product["Prezzo"], 2); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="order-summary">
                                <p>Paid with: <?php echo $order["Metodo_Pagamento"]; ?></p>
                                <p>Total: €<?php echo number_format($order["Costo_Totale"], 2); ?></p>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <!-- Completed Orders Section -->
        <h3>Completed Orders</h3>
        <div class="orders-container">
            <?php foreach($templateParams["orders"] as $order): ?>
                <?php if($order["Tipo"] === "Delivered"): ?>
                    <article class="order-card">
                        <header>
                            <p>Order #<?php echo $order["ID_Ordine"]; ?></p>
                            <p class="order-date"> <?php echo date("F j, Y", strtotime($order["Data_Ordine"])); ?></p>
                            <p> <?php echo($order["Tipo"]) ?></p>
                        </header>
                        <div class="order-details">
                            <div class="order-items">
                                <?php foreach($order["products"] as $product): ?>
                                    <div class="order-item">
                                        <img src="<?php echo $product["Immagine"]; ?>" alt="<?php echo $product["Nome"]; ?>">
                                        <div class="item-info">
                                            <h4><?php echo $product["Nome"]; ?></h4>
                                            <p>Size: <?php echo (int)$product["Taglia"]; ?> | Qty: <?php echo $product["Quantita"]; ?></p>
                                            <p>Color: <?php echo $product["Colore"]; ?></p>
                                            <p>Price: €<?php echo number_format($product["Prezzo"], 2); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="order-summary">
                                <p>Paid with: <?php echo $order["Metodo_Pagamento"]; ?></p>
                                <p>Total: €<?php echo number_format($order["Costo_Totale"], 2); ?></p>
                                <a href="tracking.php?order=<?php echo $order["ID_Ordine"]; ?>" class="track-link">Track Order</a>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>