<header class="back-button-and-title">
    <a href="javascript:history.back()" class="back"><img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page."></a>
    <h2>Orders Received</h2>
</header>
<form>
    <div class="search">
        <span class="search-icon"><img src="CSS/Images/Icons/search.svg" alt="Execute search." /></span>
        <label for="search">Please, type the string that will be searched among the product names and IDs.</label>
        <input id="search" class="search-input" type="search" placeholder="Search among all orders received"/>
    </div>
</form>

<section class="a-orders-section">
    <h3>ONGOING ORDERS</h3>
    <ol>
        <?php foreach($templateParams["ongoingOrders"] as $order): ?>
            <li>
                <header>
                    <p>Order #<?php echo $order["ID_Ordine"]; ?></p>
                    <p><?php echo date('d-m-Y', strtotime($order["Data_Ordine"])); ?></p>
                    <p><?php echo $order["Status"]; ?></p>
                </header>
                <ul class="products-overview">
                    <?php foreach($order["products"] as $product): ?>
                        <li>
                            <article>
                                <img src="CSS/Images/Products/<?php echo str_replace(' ', '', $product["Nome"] . '_'); ?>1.webp" alt="<?php echo htmlspecialchars($product["Nome"]); ?>" />
                                <h4><?php echo htmlspecialchars($product["Nome"]); ?></h4>
                                <p>Size: <?php echo $product["Taglia"]; ?> | Qty: <?php echo $product["Quantita"]; ?></p>
                                <p>Color: <?php echo $product["Colore"]; ?></p>
                                <p>€<?php echo number_format($product["Prezzo_Acquisto"], 2); ?>
                                    <?php if($product["Prezzo_Acquisto"] < $product["Prezzo_Attuale"]): ?>
                                        <s>€<?php echo number_format($product["Prezzo_Attuale"], 2); ?></s>
                                    <?php endif; ?>
                                </p>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="buyer-address">
                    <p>Mario Rossi</p>
                    <p>Cesena Campus - UniBo</p>
                    <p>Piazza Aldo Moro, 90</p>
                    <p>Cesena (FC), 47521</p>
                </div>
                <div class="payment-details">
                    <img src="CSS/Images/Icons/<?php echo strtolower($order["Metodo_Pagamento"]); ?>.svg" alt="">
                    <p>Paid with <?php echo $order["Metodo_Pagamento"]; ?></p>
                    <p>Total €<?php echo number_format($order["Costo_Totale"], 2); ?></p>
                </div>
                <?php if($order["Status"] !== "Delivered"): ?>
                    <footer>
                        <button>Mark as <?php 
                            echo $order["Status"] === "Placed" ? "In Progress" : 
                                ($order["Status"] === "In progress" ? "Shipped" : "Delivered"); 
                        ?></button>
                    </footer>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</section>

<section class="a-orders-section">
    <h3>COMPLETED ORDERS</h3>
    <ol>
        <?php foreach($templateParams["completedOrders"] as $order): ?>
            <li>
                <header>
                    <p>Order #<?php echo $order["ID_Ordine"]; ?></p>
                    <p><?php echo date('d-m-Y', strtotime($order["Data_Ordine"])); ?></p>
                    <p><?php echo $order["Status"]; ?></p>
                </header>
                <ul class="products-overview">
                    <?php foreach($order["products"] as $product): ?>
                        <li>
                            <article>
                        <?php
                            $nomeFile = $product['Nome'] . '_1.webp';
                            $percorsoFile ='CSS/Images/Products/' . $nomeFile;
                            if (file_exists($percorsoFile)) {
                                echo '<img src="CSS/Images/Products/' . htmlspecialchars($product['Nome'] . '_' . '1') . '.webp" alt="' . htmlspecialchars($product['Nome']) . '">';
                            } else {
                                // Il file non esiste, ad esempio mostra un'immagine di default oppure un messaggio di errore
                                echo '<img src="CSS/Images/Products/default_shoe.webp" alt="Immagine non disponibile">';
                            }
                        ?>
                                <h4><?php echo htmlspecialchars($product["Nome"]); ?></h4>
                                <p>Size: <?php echo $product["Taglia"]; ?> | Qty: <?php echo $product["Quantita"]; ?></p>
                                <p>Color: <?php echo $product["Colore"]; ?></p>
                                <p>€<?php echo number_format($product["Prezzo_Acquisto"], 2); ?>
                                    <?php if($product["Prezzo_Acquisto"] < $product["Prezzo_Attuale"]): ?>
                                        <s>€<?php echo number_format($product["Prezzo_Attuale"], 2); ?></s>
                                    <?php endif; ?>
                                </p>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="buyer-address">
                    <p>Mario Rossi</p>
                    <p>Cesena Campus - UniBo</p>
                    <p>Piazza Aldo Moro, 90</p>
                    <p>Cesena (FC), 47521</p>
                </div>
                <div class="payment-details">
                    <img src="CSS/Images/Icons/<?php echo strtolower($order["Metodo_Pagamento"]); ?>.svg" alt="">
                    <p>Paid with <?php echo $order["Metodo_Pagamento"]; ?></p>
                    <p>Total €<?php echo number_format($order["Costo_Totale"], 2); ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</section>