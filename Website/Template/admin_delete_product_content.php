<header>
    <a href="javascript:history.back()" class="back"><img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page."></a>
    <h2>Delete Product</h2>
    <h3>#<?php echo $templateParams["product"]["product"]["ID_Prodotto"]; ?></h3>
</header>

<form class="fields-product-admin" method="POST" action="#" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo $templateParams["product"]["product"]["ID_Prodotto"]; ?>">

    <!--BRAND-->
    <label for="brand-product-admin">Brand</label>
    <select name="brand-product-admin" id="brand-product-admin" disabled>
        <option disabled value> -- Choose an option -- </option>
        <?php 
        $brands = ["Adidas", "New Balance", "Sergio Tacchini", "Crocs", "Fila", "Converse", "Asics", 
                  "Hoka", "Lacoste", "On", "Puma", "Reebok", "Salomon", "Ugg", "Saucony", "Vans", 
                  "Havaianas", "Kappa", "Under Armour", "Diadora"];
        foreach($brands as $brand): 
            $selected = ($brand === $templateParams["product"]["product"]["Marca"]) ? "selected" : "";
        ?>
            <option value="<?php echo strtolower($brand); ?>" <?php echo $selected; ?>><?php echo $brand; ?></option>
        <?php endforeach; ?>
    </select>

    <!--MODEL-->
    <label for="model-product-admin">Model</label>
    <input type="text" id="model-product-admin" name="model" maxlength="100" 
           value="<?php echo $templateParams["product"]["product"]["Nome"]; ?>" readonly>
    
    <!--GENRE-->
    <fieldset>
        <legend>Genre</legend>
        <?php
        $genres = ["Man", "Woman", "Kids"];
        foreach($genres as $genre):
            $checked = (strtolower($genre) === strtolower($templateParams["product"]["product"]["Genere"])) ? "checked" : "";
        ?>
            <label for="<?php echo strtolower($genre); ?>-product-admin"><?php echo $genre; ?></label>
            <input id="<?php echo strtolower($genre); ?>-product-admin" class="genre-product-admin" 
                   type="checkbox" value="<?php echo strtolower($genre); ?>" <?php echo $checked; ?> disabled/>
        <?php endforeach; ?>
    </fieldset>
    
    <!--CATEGORY-->
    <fieldset>
        <legend>Category</legend>
        <?php
        $categories = ["Sneakers", "Sandals", "Sliders"];
        foreach($categories as $category):
            $checked = (strtolower($category) === strtolower($templateParams["product"]["product"]["Tipo"])) ? "checked" : "";
        ?>
            <label for="<?php echo strtolower($category); ?>-product-admin"><?php echo $category; ?></label>
            <input id="<?php echo strtolower($category); ?>-product-admin" 
                   type="radio" name="category-product-admin" 
                   value="<?php echo strtolower($category); ?>" <?php echo $checked; ?> disabled>
        <?php endforeach; ?>
    </fieldset>
    
    <!--IMAGES-->
    <label for="images-product-admin">Images</label>
    <input type="file" id="images-product-admin" accept=".webp" disabled>
    <div class="images-product-to-delete">
        <?php
        $name = $templateParams["product"]["product"]["Nome"];
        $gender = strtolower($templateParams["product"]["product"]["Genere"]);
        for($i = 1; $i <= 4; $i++):
        ?>
            <img src="CSS/Images/Products/<?php echo $name . '_' . $i; ?>.webp" alt="">
        <?php endfor; ?>
    </div>
    
    <!--DESCRIPTION-->
    <label for="description-product-admin">Description</label>
    <textarea name="description" id="description-product-admin" cols="30" rows="10" readonly><?php 
        echo $templateParams["product"]["product"]["Descrizione"]; 
    ?></textarea>
    
    <!--PRICE-->
    <label for="price-product-admin">Price</label>
    <span>€<input id="price-product-admin" name="price" type="number" 
         value="<?php echo $templateParams["product"]["product"]["Prezzo"]; ?>" 
         min="0.01" max="999.99" step="0.01" onblur="standardisePrice()" readonly></span>
    
    <!--NUMBER SIZE COLOR-->
    <table id="quantity-size-color">
        <caption>Product availability for each size and colour.</caption>
        <tr>
            <td></td>
            <?php
            // Get unique colors from existing variants
            $existingColors = array_unique(array_column($templateParams["variants"], "Colore"));
            sort($existingColors);
            foreach($existingColors as $color):
            ?>
                <th id="col-<?php echo $color; ?>" scope="col">
                    <img src="CSS/Images/Icons/<?php echo $color; ?>.svg" alt="<?php echo ucfirst($color); ?>.">
                </th>
            <?php endforeach; ?>
        </tr>
        <?php
        // Get unique sizes from existing variants
        $existingSizes = array_unique(array_column($templateParams["variants"], "Taglia"));
        sort($existingSizes);
        foreach($existingSizes as $size):
            $sizeId = str_replace('.', '-half', $size);
        ?>
            <tr>
                <th id="row-<?php echo $sizeId; ?>" scope="row"><?php echo $size; ?></th>
                <?php foreach($existingColors as $color): 
                    // Find matching variant
                    $quantity = 0;
                    foreach($templateParams["variants"] as $variant) {
                        if($variant["Taglia"] == $size && $variant["Colore"] == $color) {
                            $quantity = $variant["Quantita"];
                            break;
                        }
                    }
                ?>
                    <td headers="row-<?php echo $sizeId; ?> col-<?php echo $color; ?>">
                        <label for="row-<?php echo $sizeId; ?>-col-<?php echo $color; ?>">
                            Quantity in size <?php echo $size; ?> and colour <?php echo $color; ?>.
                        </label>
                        <input id="row-<?php echo $sizeId; ?>-col-<?php echo $color; ?>" 
                            name="quantity[<?php echo $size; ?>][<?php echo $color; ?>]" 
                            type="number" onkeyup="standardiseQuantity()" 
                            value="<?php echo $quantity; ?>" readonly/>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>            
    
    <button type="submit">Remove</button>
</form>