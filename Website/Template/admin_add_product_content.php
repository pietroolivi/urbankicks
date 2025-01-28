<header>
    <a href="javascript:history.back()" class="back">
        <img src="CSS/Images/Icons/back.svg" alt="Back">
    </a>
    <h2>Add New Product</h2>
</header>

<?php if (isset($error)): ?>
<div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form class="fields-product-admin" method="POST" action="admin_add_product.php" enctype="multipart/form-data">
    <!-- Brand -->
    <label for="brand-product-admin">Brand</label>
    <select name="brand-product-admin" id="brand-product-admin" required>
        <option disabled selected value> -- Choose an option -- </option>
        <?php
        $brands = ["adidas", "new-balance", "sergio-tacchini", "crocs", "fila", "converse", 
                  "asics", "hoka", "lacoste", "on", "puma", "reebok", "salomon", "ugg", 
                  "saucony", "vans", "havaianas", "kappa", "under-armour", "diadora"];
        foreach($brands as $brand):
        ?>
        <option value="<?php echo $brand; ?>"><?php echo ucfirst($brand); ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Model -->
    <label for="model-product-admin">Model</label>
    <input type="text" name="model-product-admin" id="model-product-admin" maxlength="100" required>

    <!-- Genre -->
    <fieldset>
        <legend>Genre</legend>
        <?php foreach(['man', 'woman', 'kids'] as $genre): ?>
        <label for="<?php echo $genre; ?>-product-admin"><?php echo ucfirst($genre); ?></label>
        <input id="<?php echo $genre; ?>-product-admin" name="genre[]" class="genre-product-admin" 
               type="checkbox" value="<?php echo $genre; ?>"/>
        <?php endforeach; ?>
    </fieldset>

    <!-- Category -->
    <fieldset>
        <legend>Category</legend>
        <?php foreach(['sneakers', 'sandals', 'sliders'] as $category): ?>
        <label for="<?php echo $category; ?>-product-admin"><?php echo ucfirst($category); ?></label>
        <input id="<?php echo $category; ?>-product-admin" type="radio" 
               name="category-product-admin" value="<?php echo $category; ?>" required>
        <?php endforeach; ?>
    </fieldset>

    <!-- Images -->
    <label for="images-product-admin">Images</label>
    <input type="file" id="images-product-admin" name="product_images[]" accept=".webp" multiple required>
    <p id="error-images-product-admin">Please, upload exactly 4 pictures.</p>
    <div class="images-product-to-add"></div>
    <script>
        /* ********************************************************************* */
        /* We make sure that the admin uploads exactly 4 images for the product. */
        /* ********************************************************************* */
        var uploader = document.getElementById('images-product-admin');
        var error = document.getElementById('error-images-product-admin');
        uploader.addEventListener("change", function(){
            if(uploader.files.length != 4){
                error.style.display='block';
                document.querySelector(".fields-product-admin > button[type='submit']").disabled = true;
            } else {
                document.querySelector(".fields-product-admin > button[type='submit']").disabled = false;
                error.style.display='none';
            }
            // We update the preview of presently uploaded images.
            document.getElementsByClassName("images-product-to-add")[0].innerHTML = "";
            for (image of uploader.files) {
                console.log(image);
                document.getElementsByClassName("images-product-to-add")[0].innerHTML += `
                    <img src="${URL.createObjectURL(image)}" alt="">
                `
            }
        })
    </script>

    <!-- Description -->
    <label for="description-product-admin">Description</label>
    <textarea name="description-product-admin" id="description-product-admin" cols="30" rows="10" 
        placeholder="Product description..." required></textarea>

    <!-- Price -->
    <label for="price-product-admin">Price</label>
    <span>€<input id="price-product-admin" name="price-product-admin" type="number" 
        placeholder="0.01" min="0.01" max="999.99" step="0.01" onblur="standardisePrice()" required></span>
    <script>
        function standardisePrice() {
            inputTag = event.currentTarget;
            // We must isolate case ‘0’ otherwise it would be eaten in the next cycle.
            if (parseFloat(inputTag.value) == 0) {
                inputTag.value = 0.01;
                return;
            }     
            // We remove the non-significant zeros (left) in the decimal part, except for the zero before the decimal point.
            while (inputTag.value[0] == 0 && inputTag.value.indexOf(".") != 1) {
                inputTag.value = inputTag.value.substring(1);
            }
            // If the number has more than three integer digits, we cut what comes after the second (excluded) and before the dot (excluded).
            if (inputTag.value.indexOf('.') != -1 && (inputTag.value.length - inputTag.value.substring(inputTag.value.indexOf('.')).length) > 3) {
                decimalPart = inputTag.value.substring(0, inputTag.value.indexOf('.'));
                inputTag.value = decimalPart.substring(0, 3) + inputTag.value.substring(inputTag.value.indexOf('.')); 
            } else if (inputTag.value.indexOf('.') == -1 && inputTag.value.length > 3) {
                inputTag.value = inputTag.value.substring(0, 3);
            }
            // We put an external if to avoid side-effects in case there is no decimal point.
            if (inputTag.value.indexOf(".") != -1) {
                // If the number has more than two decimal digits, we cut off what comes after the second one (excluded).
                if ((inputTag.value.length - 1) - inputTag.value.indexOf(".") > 2) {
                    inputTag.value = inputTag.value.substring(0, inputTag.value.indexOf(".") + 3);
                } 
                // If there is only one decimal digit, we concatenate a zero.
                if ((inputTag.value.length - 1) - inputTag.value.indexOf(".") == 1) {
                    inputTag.value = inputTag.value + "0";
                }
                // If there is a dot but no decimal digit we add 2 zeros.
                if ((inputTag.value.length - 1) - inputTag.value.indexOf(".") == 0) {
                    inputTag.value = inputTag.value + "00";
                }
            }
            // If there is no dot we add it followed by 2 zeros.
            if (inputTag.value.indexOf(".") == -1) {
                inputTag.value = inputTag.value + ".00";
            }
            // If there is a dot but no integer we add 1 initial zero.
            if (inputTag.value[0] == ".") {
                inputTag.value = "0" + inputTag.value;
            }
        } 
    </script>

    <!-- Size/Color/Quantity Matrix -->
    <div id="variants-matrix"></div>

    <button type="reset">Reset</button>
    <button type="submit">Publish</button>
    <script>
        /* ************************************************************************* */
        /* Since there are no out-of-the-box methods to ensure that at least one of  */
        /* a group of checkboxes is selected, we use a handler on the submit button. */
        /* ************************************************************************* */
        submitButton = document.querySelector(".fields-product-admin > button[type='submit']");
        submitButton.addEventListener("click", function() {
            genreCheckboxes = document.getElementsByClassName("genre-product-admin");
            for (genreCheckbox of genreCheckboxes) {
                if (genreCheckbox.checked) {                                
                    return;
                }
            }
            alert("Please select at least one genre.");
            // We neutralise the effect of the button click.
            event.preventDefault();
        })
    </script>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const genreCheckboxes = document.querySelectorAll('.genre-product-admin');
    const variantsMatrix = document.getElementById('variants-matrix');
    
    function updateSizeMatrix() {
        let html = '<table id="quantity-size-color"><caption>Product availability</caption><tr><td></td>';
        const colors = ['black', 'blue', 'green', 'purple', 'red', 'white'];
        
        // Add color headers
        colors.forEach(color => {
            html += `<th id="col-${color}" scope="col">
                        <img src="CSS/Images/Icons/${color}.svg" alt="${color}">
                    </th>`;
        });
        html += '</tr>';
        
        // Get selected genres and their size ranges
        const selectedGenres = Array.from(genreCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
            
        const sizesRanges = {
            'kids': Array.from({length: 9}, (_, i) => i + 28),
            'man': Array.from({length: 9}, (_, i) => i + 37),
            'woman': Array.from({length: 9}, (_, i) => i + 37)
        };
        
        // Create rows for each size
        const allSizes = new Set();
        selectedGenres.forEach(genre => {
            sizesRanges[genre].forEach(size => allSizes.add(size));
        });
        
        Array.from(allSizes).sort((a,b) => a-b).forEach(size => {
            html += `<tr><th id="row-${size}" scope="row">${size}</th>`;
            colors.forEach(color => {
                html += `<td headers="row-${size} col-${color}">
                    <label for="quantity-${size}-${color}">Quantity for size ${size} and color ${color}</label>
                    <input id="quantity-${size}-${color}" 
                           name="quantity-${size}-${color}" 
                           type="number" 
                           value="0" 
                           min="0" 
                           max="99"/>
                </td>`;
            });
            html += '</tr>';
        });
        
        html += '</table>';
        variantsMatrix.innerHTML = html;
    }
    
    genreCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSizeMatrix);
    });
    
    // Initial matrix update
    updateSizeMatrix();
});
</script>