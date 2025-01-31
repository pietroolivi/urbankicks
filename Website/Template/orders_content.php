<section class="orders-section">
    <h2>My Orders</h2>
    <?php if(empty($templateParams["orders"])): ?>
        <p>You haven't placed any orders yet.</p>
    <?php else: ?>
        <!-- Ongoing Orders Section -->
        <div class="order-lifestate"> 
        <h3>Ongoing Orders</h3>
    </div>
        <div>
            <div class="orders-container">
                <?php foreach($templateParams["orders"] as $order): ?>
                    <?php if(!$order["tracking_delivered"]): ?>
                        <article class="order-card">
                            <table>
                                <tr class="order-header">
                                    <td>
                                        <p>Order #<?php echo $order["ID_Ordine"];?></p>
                                    </td>
                                    <td>
                                        <p class="order-date"> <?php echo date("F j, Y", strtotime($order["Data_Ordine"]));?></p>
                                    </td>
                                    <td>    
                                        <a href="tracking.php?order=<?php echo $order["ID_Ordine"]; ?>" class="track-link">Track Order</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <ul id="products-container" class="products-overview lateral-container">
                                            <?php foreach($order["products"] as $product):?>
                                                <li>
                                                    <div class="product-card">
                                                        <div class="product-details">
                                                            <a class="product-link">
                                                                <img src="CSS/Images/Products/<?php echo htmlspecialchars($product['ID_Prodotto']. '_' . $product['Genere'] . "1");?>.webp" 
                                                                alt="<?php echo htmlspecialchars($product['Nome']);?>"></a>
                                                            <div class="item-info">
                                                                <h4><?php echo $product["Nome"];?></h4>
                                                                <p>Size: <?php echo (int)$product["Taglia"]; ?> | Qty: <?php echo $product["Quantita"];?></p>
                                                                <p>Color: <?php echo $product["Colore"];?></p>
                                                                <p>Price: €<?php echo number_format($product["Prezzo"], 2);?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach;?>
                                        </ul>
                                    </td>
                                </tr>
                                <tr class="order-summary">
                                    <td colspan="2">
                                        <p>Paid with: <?php echo $order["Metodo_Pagamento"]; ?></p>
                                    </td>
                                    <td>
                                        <p>Total: €<?php echo number_format($order["Costo_Totale"], 2); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </article>

                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Completed Orders Section -->
        <div class="order-lifestate"> 
    <h3>Completed Orders</h3>
</div>
<div>
    <div class="orders-container">
        <?php foreach($templateParams["orders"] as $order): ?>
            <?php if($order["tracking_delivered"]): ?>
                <article class="order-card">
                    <table>
                        <tr class="order-header">
                            <td>
                                <p>Order #<?php echo $order["ID_Ordine"]; ?></p>
                            </td>
                            <td>
                                <p class="order-date"> <?php echo date("F j, Y", strtotime($order["Data_Ordine"])); ?></p>
                            </td>
                            <td>
                                <p><?php echo htmlspecialchars($order["Tipo"]); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <ul id="products-container" class="products-overview lateral-container">
                                    <?php foreach($order["products"] as $product): ?>
                                        <li>
                                            <div class="product-card">
                                                <div class="product-details">
                                                    <a class="product-link">
                                                        <img src="CSS/Images/Products/<?php echo htmlspecialchars($product['ID_Prodotto']. '_' . $product['Genere'] . "1");?>.webp" 
                                                        alt="<?php echo htmlspecialchars($product["Nome"]); ?>">
                                                    </a>
                                                    <div class="item-info">
                                                        <h4><?php echo htmlspecialchars($product["Nome"]); ?></h4>
                                                        <p>Size: <?php echo (int)$product["Taglia"]; ?> | Qty: <?php echo htmlspecialchars($product["Quantita"]); ?></p>
                                                        <p>Color: <?php echo htmlspecialchars($product["Colore"]); ?></p>
                                                        <p>Price: €<?php echo number_format($product["Prezzo"], 2); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>    
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                        <tr class="order-summary">
                            <td colspan="2">
                                <p>Paid with: <?php echo htmlspecialchars($order["Metodo_Pagamento"]); ?></p>
                            </td>
                            <td>
                                <p>Total: €<?php echo number_format($order["Costo_Totale"], 2); ?></p>
                            </td>
                        </tr>
                        <tr class="order-actions">
                            <td colspan="3">
                                <a href="tracking.php?order=<?php echo $order["ID_Ordine"]; ?>" class="track-link">Track Order</a>
                            </td>
                        </tr>
                    </table>
                </article>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
    <?php endif; ?>
</section>