<header>
    <a href="admin_home.php" class="back"><img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page."></a>
    <h2>Published Products</h2>
</header>

<a href="admin_add_product.php">Add New Product</a>

<form>
    <div class="search">
        <span class="search-icon"><img src="CSS/Images/Icons/search.svg" alt="Execute search." /></span>
        <label for="search">Please, type the string that will be searched among the product names and IDs.</label>
        <input id="search" class="search-input" type="search" placeholder="Search for a published item"/>
    </div>
</form>

<?php foreach($templateParams["products"] as $product): ?>
    <article class="product-admin">
        <?php 
            
            // Stock status determination
            $totalStock = 0;
            $variants = $dbh->getProductVariants($product["ID_Prodotto"]);
            foreach($variants as $variant) {
                $totalStock += $variant["Quantita"];
            }
            $stockStatus = "high_stocks";
            if($totalStock == 0) {
                $stockStatus = "out_of_stock";
            } elseif($totalStock < 20) {
                $stockStatus = "low_stocks"; 
            }
        ?>
          <?php
            $nomeFile = str_replace(' ', '', $product['Nome']) . '_1.webp';
            $percorsoFile ='CSS/Images/Products/' . $nomeFile;
            if (file_exists($percorsoFile)) {
                echo '<img src="CSS/Images/Products/' . htmlspecialchars(str_replace(' ', '', $product['Nome']) . '_' . '1') . '.webp" alt="' . htmlspecialchars(str_replace(' ', '', $product['Nome'])) . '">';
            } else {
                // Il file non esiste, ad esempio mostra un'immagine di default oppure un messaggio di errore
                echo '<img src="CSS/Images/Products/default_shoe.webp" alt="Immagine non disponibile">';
            }
        ?>
        <div class="product-textual-info-admin">
            <h3>#<?php echo $product["ID_Prodotto"]; ?></h3>
            <p><?php echo $product["Marca"] . " " . $product["Nome"] . " - " . $product["Genere"]; ?></p>
        </div>
        <img src="CSS/Images/Icons/<?php echo $stockStatus; ?>.svg" alt="<?php echo ucfirst(str_replace('_', ' ', $stockStatus)); ?>"/>
        <a href="admin_edit_product.php?id=<?php echo $product["ID_Prodotto"]; ?>">
            <img src="CSS/Images/Icons/edit.svg" alt="Edits one or more fields of the article or its variants."/>
        </a>
        <a href="admin_delete_product.php?id=<?php echo $product["ID_Prodotto"]; ?>">
            <img src="CSS/Images/Icons/bin.svg" alt="Permanently deletes the product from the list."/>
        </a>
        <a href="admin_view_product.php?id=<?php echo $product["ID_Prodotto"]; ?>">
            <img src="CSS/Images/Icons/eye_open.svg" alt="View product details, including its inventory.">
        </a>
    </article>
<?php endforeach; ?>