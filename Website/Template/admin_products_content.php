<header>
    <a href="javascript:history.back()" class="back"><img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page."></a>
    <h2>Published Products</h2>
</header>

<a href="admin_add_product.html">Add New Product</a>

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
            $image = "CSS/Images/Products/" . $product["ID_Prodotto"] . "_" . strtolower($product["Genere"]) . "1.webp";
            
            // Stock status determination
            $totalStock = 0;
            $variants = $dbh->getProductVariants($product["ID_Prodotto"]);
            foreach($variants as $variant) {
                $totalStock += $variant["Quantita"];
            }
            $stockStatus = "high_stocks";
            if($totalStock == 0) {
                $stockStatus = "out_of_stock";
            } elseif($totalStock < 10) {
                $stockStatus = "low_stocks"; 
            }
        ?>
        <img src="<?php echo $image; ?>" alt=""/>
        <div class="product-textual-info-admin">
            <h3>#<?php echo $product["ID_Prodotto"]; ?></h3>
            <p><?php echo $product["Marca"] . " " . $product["Nome"]; ?></p>
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