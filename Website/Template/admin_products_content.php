<header class="back-button-and-title">
    <a href="javascript:history.back()" class="back"><img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page."></a>
    <h2>Published Products</h2>
</header>

<button id="add-product-button" class="full-button-white" onclick="location.href='admin_add_product.php'">&#43; Add New Product</button>

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
            // First image is used as thumbnail
            $image = "CSS/Images/Products/" . $product["Nome"] . "_" . "1.webp";
            
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
       
        <h3>#<?php echo $product["ID_Prodotto"]; ?></h3>
        <?php
            $nomeFile = str_replace(' ', '', $product['Nome']) . '_1.webp';
            $percorsoFile ='CSS/Images/Products/' . $nomeFile;
            if (file_exists($percorsoFile)) {
                echo '<img src="CSS/Images/Products/' . htmlspecialchars(str_replace(' ', '', $product['Nome']) . '_' . '1') . '.webp" alt="' . htmlspecialchars(str_replace(' ', '', $product['Nome'])) . '"  class="admin-product-thumbnail">';
            } else {
                // Il file non esiste, ad esempio mostra un'immagine di default oppure un messaggio di errore
                echo '<img src="CSS/Images/Products/default_shoe.webp" alt="Immagine non disponibile"  class="admin-product-thumbnail">';
            }
        ?>
        <div class="product-textual-info-admin">
            <p><?php echo $product["Marca"]; ?></p>
            <p><?php echo $product["Nome"];?></p>
            <p><?php echo $product["Genere"];?></p>
        </div>
        <div class="product-icons">
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
        </div>
    </article>
<?php endforeach; ?>
<script>
    adjustsOpenEyeHeight();
    window.addEventListener("resize", adjustsOpenEyeHeight);

    /* ******************************************************** */
    /* We make the image element for the product display square */
    /* so that it is vertically aligned with the other icons.   */
    /* ******************************************************** */
    function adjustsOpenEyeHeight() {
        document.querySelector("img[src='CSS/Images/Icons/eye_open.svg']").style.height = document.querySelector("img[src='CSS/Images/Icons/eye_open.svg']").clientWidth + "px";
    }
</script>