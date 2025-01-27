<?php
if(isset($_SESSION['error'])) {
    echo "<input type='hidden' id='error-message' value='" . htmlspecialchars($_SESSION['error']) . "'>";
    unset($_SESSION['error']);
}

// Get category from URL parameter, default to 'popular'
$category = isset($_GET['category']) ? $_GET['category'] : 'popular';

// Get sort parameter from URL, default to 'price-low-to-high'
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price-low-to-high';

// Get designers from URL parameters
$brand = isset($_GET['brand']) ? $_GET['brand'] : "";

// Get Genre and Type from URL parameters
$genre = isset($_GET['genre']) ? $_GET['genre'] : "";
$type = isset($_GET['type']) ? $_GET['type'] : "";

// get colors and sizes from URL parameters AUTHOR: SIMONE

?>

<!-- Filter Menu -->
<aside class="filter-sidebar">
    <h3>DESIGNERS</h3>
    <?php
    $brands = ["Adidas", "Nike", "New Balance", "Converse"];
    foreach($brands as $brand): ?>
        <div class="brand-option">
            <input type="checkbox" id="filter-sidebar-<?php echo strtolower($brand); ?>" name="designers[]" value="<?php echo $brand; ?>" autocomplete="off" onchange="updateSelectedBorders()">
            <label for="filter-sidebar-<?php echo strtolower($brand); ?>"><?php echo strtoupper($brand); ?></label>
        </div>
    <?php endforeach; ?>

    <section>
        <h3>PRICE</h3>
    </section>

    <section class="size-options">
        <h3>SIZE</h3>
        <?php for ($i = 36; $i <= 47; $i++) { ?>
            <div class="size-option">
                <input id="filter-sidebar-size<?php echo $i  ?>" type="checkbox" value="<?php echo $i; ?>" name="sizes[]">
                <label for="filter-sidebar-size<?php echo $i  ?>"><?php echo $i ?></label>
            </div>
        <?php } ?>
    </section>

    <h3>COLOR</h3>
    <?php
    $colors = ["Blue", "Purple", "Red", "Green", "White", "Black"];
    foreach($colors as $color): ?>
        <div class="color-option">
            <label><input type="checkbox" id="filter-sidebar-<?php echo $color; ?>" name="colors[]" value="<?php echo $color; ?>"><img src="CSS/Images/Icons/<?php echo $color; ?>.svg" alt=""></label>
            <label for="filter-sidebar-<?php echo $color; ?>"><?php echo ucfirst($color); ?></label>
        </div>
    <?php endforeach; ?>
    <footer class="filter-sidebar-buttons">
        <input id="filter-sidebar-reset" type="button" value="Reset"/>
        <input id="filter-sidebar-done" type="button" value="Done" onclick="shiftFilterSidebar()"/>
        <label for="filter-sidebar-done">Closes the sidebar, leaving the applied filters unchanged.</label>
        <label for="filter-sidebar-reset">Undo selections made in this sidebar.</label>
    </footer>
</aside>
<script>
    document.getElementsByClassName("filter-sidebar")[0].style.translate = "-100%";
    window.onload = updateSelectedBorders();

    function shiftFilterSidebar() {
        if (document.getElementsByClassName("filter-sidebar")[0].style.translate == "-100%") {
            document.getElementsByClassName("filter-sidebar")[0].style.translate = 0;
            disableScrollOnBG();
            document.getElementsByClassName("filter-sidebar")[0].style.overflow = "auto";
        } else {
            document.getElementsByClassName("filter-sidebar")[0].style.translate = "-100%";
            enableScrollOnBG();
        }
    }

    document.body.addEventListener('click', function() {
        let myElementToCheckIfClicksAreInsideOf = document.getElementsByClassName("filter-sidebar")[0];
        if (document.getElementsByClassName("filter-sidebar")[0].style.translate == "0px" && !myElementToCheckIfClicksAreInsideOf.contains(event.target)) {
            document.getElementById("filter-icon").checked = false;
            shiftFilterSidebar();
            event.preventDefault();
        }
    });

    /* ************************************************************************************************************* */
    /* To avoid the effect of the doubled border it would not be sufficient to check at the time of selection        */
    /* whether or not the top and bottom borders of the brands contiguous to the one just selected are already       */
    /* present and if so not to insert them for the new brand, because I could for example deselect ‘Adidas’ after   */
    /* clicking on ‘Nike’, to which I did not add the top border counting on the fact that Adidas had the bottom     */
    /* border set and would therefore remain uncovered. The only solution I can think of is, each time the selection */
    /* is changed, to redo the border to all the entries by halving the thickness of the neighbouring border pairs.  */
    /* ************************************************************************************************************* */
    function updateSelectedBorders() {
        let brandInputs = document.querySelectorAll(".brand-option > input");
        let brandLabels = document.querySelectorAll(".brand-option > label");
        for (let i = 0; i < brandInputs.length; i++) {
            if (brandInputs[i].checked && i != (brandInputs.length - 1) && brandInputs[i + 1].checked) {
                brandLabels[i].style["border-bottom"] = "0.5px solid black";
                brandLabels[i + 1].style["border-top"] = "0.5px solid black";
            }   
            if ((brandInputs[i].checked && i != (brandInputs.length - 1) && !brandInputs[i + 1].checked)
            ||  (brandInputs[i].checked && i == (brandInputs.length - 1))) {
                brandLabels[i].style["border-bottom"] = "1px solid black";
            }
            if ((brandInputs[i].checked && i != 0 && !brandInputs[i - 1].checked)
            ||  (brandInputs[i].checked && i == 0)) {
                brandLabels[i].style["border-top"] = "1px solid black";
            }
            if (!brandInputs[i].checked) {
                brandLabels[i].style["border-top"] = "none";
                brandLabels[i].style["border-bottom"] = "none";
            }
        }
    }
</script>

<!-- Sort Menu -->
<aside class="sort-sidebar">
    <fieldset class="sort-switch" onchange="shiftSortSidebar('fieldset')">
        <legend>Please select the order in which to display the items:</legend>
        <input type="radio" id="price-low-to-high" name="sort" value="price-low-to-high" <?php echo $sort === 'price-low-to-high' ? 'checked' : ''; ?>>
        <label for="price-low-to-high">PRICE LOW TO HIGH</label>
        <input type="radio" id="price-high-to-low" name="sort" value="price-high-to-low" <?php echo $sort === 'price-high-to-low' ? 'checked' : ''; ?>>
        <label for="price-high-to-low">PRICE HIGH TO LOW</label>
        <input type="radio" id="alphabetical" name="sort" value="alphabetical" <?php echo $sort === 'alphabetical' ? 'checked' : ''; ?>>
        <label for="alphabetical">ALPHABETICAL</label>
        <input type="radio" id="reviews-low-to-high" name="sort" value="reviews-low-to-high" <?php echo $sort === 'reviews-low-to-high' ? 'checked' : ''; ?>>
        <label for="reviews-low-to-high">REVIEWS LOW TO HIGH</label>
        <input type="radio" id="reviews-high-to-low" name="sort" value="reviews-high-to-low" <?php echo $sort === 'reviews-high-to-low' ? 'checked' : ''; ?>>
        <label for="reviews-high-to-low">REVIEWS HIGH TO LOW</label>
    </fieldset>
</aside>
<script>
    document.getElementsByClassName("sort-sidebar")[0].style.translate = "0px 300%";

    function shiftSortSidebar(whoCalled) {/*
        if (whoCalled == 'icon') {
            console.log("-------------------------------------------------");
            console.log("func called by icon's onchange");
            console.log("-------------------------------------------------");
        } else if (whoCalled == 'body') {
            console.log("-------------------------------------------------");
            console.log("func called by body's listener");
            console.log("-------------------------------------------------");
        } else if (whoCalled == 'fieldset') {
            console.log("-------------------------------------------------");
            console.log("func called by fieldset's onchange");
            console.log("-------------------------------------------------");
        }*/
        if (document.getElementsByClassName("sort-sidebar")[0].style.translate != "0px") {
            document.getElementsByClassName("sort-sidebar")[0].style.translate = "0px";
            disableScrollOnBG();
            document.getElementsByClassName("sort-sidebar")[0].style.overflow = "auto";
        } else {
            document.getElementsByClassName("sort-sidebar")[0].style.translate = "0px 300%";
            enableScrollOnBG();
        }
    }

    document.body.addEventListener('click', function() {
        let myElementToCheckIfClicksAreInsideOf = document.getElementsByClassName("sort-sidebar")[0];
        if (document.getElementsByClassName("sort-sidebar")[0].style.translate == "0px" && !myElementToCheckIfClicksAreInsideOf.contains(event.target)) {
            document.getElementById("sort-icon").checked = false;
            shiftSortSidebar('body');
            event.preventDefault();
        }
    });
</script>
</script>

<h2>
    <?php 
    if ($genre || $type) {
        echo(trim(ucfirst($genre) . ' ' . ucfirst($type)));
    } else if ($genre) {
        echo(ucfirst($genre));
    } else {
        echo("All Products");
    }
    ?>
</h2>

<div class="filters-and-sorters-switches">
    <!-- Filter Icon -->
    <label for="filter-icon">Open/close menu with available product filters.</label>
    <label class="filter-menu"><img src="CSS/Images/Icons/filter.svg" alt=""><input id="filter-icon" type="checkbox" onchange="shiftFilterSidebar()"></label>

    <!-- Category Selection -->
    <fieldset class="category-switch">
        <legend>Please select the category of articles to be displayed:</legend>
        <input type="radio" id="popular" name="category" value="popular" checked>
        <label for="popular">Popular</label>
        <input type="radio" id="discounted" name="category" value="discounted">
        <label for="discounted">Discounted</label>
        <input type="radio" id="novelties" name="category" value="novelties">
        <label for="novelties">Novelties</label>
    </fieldset>

    <!-- Sort Icon -->
    <label for="sort-icon">Open/close menu with available product sorting criteria.</label>
    <label class="sort-menu"><img src="CSS/Images/Icons/sort.svg" alt=""><input id="sort-icon" type="checkbox" onchange="shiftSortSidebar('icon')"></label>
</div>

<?php if ($genre || $type): ?>
    <!-- Breadcrumb Navigation -->
    <nav aria-label="Breadcrumb" class="breadcrumb">
        <ol>
            <li><a href="home.php">Home</a></li>
            <?php if ($genre): ?>
                <li><a href="home.php?genre=<?php echo urlencode($genre); ?>"><?php echo ucfirst($genre); ?></a></li>
            <?php endif; ?>
            <?php if ($type): ?>
                <li><span aria-current="page"><?php echo ucfirst($type); ?></span></li>
            <?php endif; ?>
        </ol>
    </nav>
<?php endif; ?>

<div id="products-container" class="products-grid" 
     data-products='<?php echo htmlspecialchars(json_encode($templateParams["products"]), ENT_QUOTES, 'UTF-8'); ?>'>
</div>

<template id="product-template">
    <div class="product-card">
        <a href="" class="product-link">
            <img src="" alt="">
            <label class="wishlist-container">
            <input type="checkbox" class="wishlist-checkbox" hidden>
            <img src="CSS/Images/Icons/heart_empty.svg" alt="Add to Wishlist" class="wishlist-checkbox">
            </label></a>
        <h3 class="product-name"></h3>
        <p class="product-price"></p>
       
    </div>
</template>