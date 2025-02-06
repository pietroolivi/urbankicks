<?php
if(isset($_SESSION['error'])) {
    echo "<input type='hidden' id='error-message' value='" . htmlspecialchars($_SESSION['error']) . "'>";
    unset($_SESSION['error']);
}

// Gets the sort parameter from the URL if present, otherwise the default value is 'price-low-to-high'
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price-low-to-high';

// Get Genre and Type from URL parameters
$genre = isset($_GET['genre']) ? $_GET['genre'] : "";
$type = isset($_GET['type']) ? $_GET['type'] : "";
?>

<!-- Filter Menu -->
<aside class="filter-sidebar">

    <section>
        <h3>DESIGNERS</h3>
        <ul class="brand-options">
            <?php
            foreach($templateParams["brands"] as $brand): ?>
            <li class="brand-option">
                <input type="checkbox" id="filter-sidebar-<?php echo strtolower($brand); ?>" name="designers[]" value="<?php echo $brand; ?>" autocomplete="off" onchange="updateSelectedBorders()">
                <label for="filter-sidebar-<?php echo strtolower($brand); ?>"><?php echo strtoupper($brand); ?></label>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section>
        <h3>PRICE</h3>
        <div class="price-option">
            <label for="filter-sidebar-min-price">Minimum price that products in the list shown must cost.</label>
            <p>Min: €0</p>
            <input id="filter-sidebar-min-price" type="range" value="0" min="0" max="1000" name="filter-sidebar-min-price" oninput="displayPriceBounds()" onchange="correctPriceBounds()"/>
        </div>
        <div class="price-option">
            <label for="filter-sidebar-max-price">Maximum price that the products in the list shown can cost.</label>
            <p>Max: €1000</p>
            <input id="filter-sidebar-max-price" type="range" value="1000" min="0" max="1000" name="filter-sidebar-max-price" oninput="displayPriceBounds()" onchange="correctPriceBounds()"/>
        </div>
    </section>

    <section>
        <h3>SIZE</h3>
        <ul class="size-options">
            <?php for ($i = 28; $i <= 45; $i++) { ?>
            <li class="size-option">
                <input id="filter-sidebar-size<?php echo $i  ?>" type="checkbox" name="sizes[]" value="<?php echo $i ?>">
                <label for="filter-sidebar-size<?php echo $i  ?>"><?php echo $i ?></label>
            </li>
            <?php } ?>
        </ul>
    </section>

    <section>
        <h3>COLOR</h3>
        <ul class="color-options">
            <?php
            $colors = ["blue", "purple", "red", "green", "white", "black"];
            foreach($colors as $color): ?>
            <li class="color-option">
                <label><input type="checkbox" id="filter-sidebar-<?php echo $color; ?>" name="colors[]" value="<?php echo $color; ?>"><img src="CSS/Images/Icons/<?php echo $color; ?>.svg" alt=""></label>
                <label for="filter-sidebar-<?php echo $color; ?>"><?php echo ucfirst($color); ?></label>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    
    <footer class="filter-sidebar-buttons">
        <input id="filter-sidebar-reset" type="button" value="Reset" onclick="resetAllFilters()"/>
        <input id="filter-sidebar-done" type="button" value="Done" onclick="shiftFilterSidebar()"/>
        <label for="filter-sidebar-done">Closes the sidebar, leaving the applied filters unchanged.</label>
        <label for="filter-sidebar-reset">Undo selections made in this sidebar.</label>
    </footer>
</aside>

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
        <!-- We call the function when we intercept a change in the radio button related to categories, which does not necessarily occur in conjunction with the closing of the filter sidebar. -->
        <input onchange="updateURL()" type="radio" id="popular" name="category" value="popular" checked>
        <label for="popular">Popular</label>
        <input onchange="updateURL()" type="radio" id="discounted" name="category" value="discounted">
        <label for="discounted">Discounted</label>
        <input onchange="updateURL()" type="radio" id="novelties" name="category" value="novelties">
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

<div class="removable-dynamic-filters"></div>

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


<script>
    let rawProducts = '';
    // Both allProducts and filteredProducts to mantain both states of products
    let allProducts = '';
    let filteredProducts = '';
    let wishlistItems = new Set();
    let urlParams = '';
    let filters='';
    /**All settings of global variables inside listener for complete document loading. */
    document.addEventListener('DOMContentLoaded', async() => {
        rawProducts=JSON.parse(document.getElementById('products-container').dataset.products);
        urlParams = new URLSearchParams(window.location.search);
        initializeProducts(rawProducts);
        loadWishlistItems();
        /* Filters structure */
        filters = {
            brand:    urlParams.get('brand')     ? urlParams.get('brand').split(',') : [],
            color:    urlParams.get('color')     ? urlParams.get('color').split(',') : [],
            size:     urlParams.get('size')      ? urlParams.get('size').split(',')  : [],
            category: urlParams.get('category')  || 'popular',
            genre:    urlParams.get('genre')     || '',
            minPrice: urlParams.get('min-price') || '0',
            maxPrice: urlParams.get('max-price') || '1000',
            type:     urlParams.get('type')      || '',
            sort:     urlParams.get('sort')      || 'price-low-to-high'
        };
        document.getElementsByClassName("sort-sidebar")[0].style.translate = "0px 300%";
        document.body.addEventListener('click', function() {
            let myElementToCheckIfClicksAreInsideOf = document.getElementsByClassName("sort-sidebar")[0];
            if (document.getElementsByClassName("sort-sidebar")[0].style.translate == "0px" && !myElementToCheckIfClicksAreInsideOf.contains(event.target)) {
                document.getElementById("sort-icon").checked = false;
                shiftSortSidebar('body');
                event.preventDefault();
            }
        });
        document.addEventListener('change', (event) => {
            if (event.target.classList.contains('wishlist-checkbox')) {
                handleWishlistToggle(event.target);
            }
        });
    });



    //const prodManager = new ProductManager();
    window.addEventListener('resize', updateFiltersSidebarHeight);
    document.getElementsByClassName("filter-sidebar")[0].style.translate = "-100%";
    window.addEventListener('load', initializeInputTags);
    window.addEventListener('load', updateFiltersSidebarHeight);
    window.addEventListener('load', updateHomePageFilters);

    function initializeProducts(rawProducts) {
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search')?.toLowerCase();

        const groupedProducts = new Map();
        rawProducts.forEach(product => {
            if (searchQuery && 
                !product.Nome.toLowerCase().includes(searchQuery) && 
                !product.Descrizione.toLowerCase().includes(searchQuery) &&
                !product.Marca.toLowerCase().includes(searchQuery)) {
                return;
            }

            const modelKey = product.Nome.toLowerCase();            
            if (!groupedProducts.has(modelKey)) {
                // Create new product entry
                groupedProducts.set(modelKey, {
                    id:           product.ID_Prodotto,
                    name:         product.Nome,
                    brand:        product.Marca,
                    price:        parseFloat(product.Prezzo),
                    type:         product.Tipo,
                    genre:        product.Genere,
                    description:  product.Descrizione,
                    state:        product.Sta_Tipo,
                    //added for discounted logic v
                    isDiscounted: product.isDiscounted === 1,
                    //discounted logic           ^
                    created_at:   product.Data_Aggiunta,
                    variants:     [],
                    meanReviws: product.mediaRecensioni,
                    baseProduct:  product

                });
            }
            // Add variant information
            groupedProducts.get(modelKey).variants.push({
                id:    product.ID_Prodotto,
                size:  product.Taglia,
                color: product.Colore,
                price: product.Prezzo,
                state: product.Sta_Tipo
            });
        });
        
        // Convert Map to array and store
        allProducts = Array.from(groupedProducts.values());
        filteredProducts = [...allProducts];

        const container = document.getElementById('products-container');

        // Display search results count if search was performed
        if (searchQuery) {
            container.innerHTML = ''; // Clear container first
            
            const searchInfo = document.createElement('div');
            searchInfo.className = 'search-results-info';
            
            if (filteredProducts.length === 0) {
                searchInfo.innerHTML = `
                    <p>No results found for "${searchQuery}"</p>
                    <p>Try:</p>
                    <ul>
                        <li>Checking your spelling</li>
                        <li>Using more general terms</li>
                        <li>Using fewer terms</li>
                    </ul>
                    <a href="home.php" class="reset-search">Clear search</a>
                `;
            }
            container.appendChild(searchInfo); // Append inside products container
        }
    }

    async function handleWishlistToggle(checkbox) {
        const productCard = checkbox.closest('.product-card');
        const productId = productCard.dataset.productId;
        const isAdd = checkbox.checked;
        const wishlistText = checkbox.nextElementSibling;
        const imgHeart = productCard.querySelector('img.wishlist-checkbox');
        try {/*
            const formData = new FormData();
            formData.append('action', 'toggleWishlist');
            formData.append('productId', productId);
            formData.append('isAdd', isAdd);*/
            const response = await fetch('home_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body:new URLSearchParams({
                    action: 'toggleWishlist',
                    productId: productId,
                    isAdd: isAdd 
                })
            });/*
            const data= await response.text();
            console.log(data);*/
            const data = await response.json();
            if (data.success) {
                // Update UI
                if(checkbox.checked) {
                    imgHeart.src="CSS/Images/Icons/heart_filled.svg";
                    wishlistText.textContent=' Remove from Wishlist';
                } else {
                    imgHeart.src="CSS/Images/Icons/heart_empty.svg";
                    wishlistText.textContent='Add to Wishlist';
                } 
            } else {
                /* Revert checkbox state on error */
                checkbox.checked = !isAdd;
                wishlistText.textContent = !isAdd ? 'Remove from Wishlist' : 'Add to Wishlist';
                /* Show error message */
                if (data.message === 'Please login first') {
                    window.location.href = 'login.php';
                } else {
                    alert(data.message);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            /* Revert checkbox state on error */
            checkbox.checked = !isAdd;
            wishlistText.textContent = !isAdd ? 'Remove from Wishlist' : 'Add to Wishlist';
            alert('An error occurred. Please try again.');
        }
    }

    async function loadWishlistItems() {
        try {
            const response = await fetch('home_handler.php', {
                method:  'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body:    new URLSearchParams({action: 'getWishlistItems'})
            });
            const data = await response.json();
            if (data.success) {
                // data.wishlistItems è un array di prodotti (con le colonne di PRODOTTO)
                const wishlistIds = data.wishlistItems.map(item => item.ID_Prodotto.toString());
                wishlistItems = new Set(wishlistIds);
                console.log(wishlistItems.size);
            } else {
                console.warn('Could not load wishlist items:', data.message);
            }
        } catch (error) {
            console.error('Error loading wishlist items:', error);
        }  
        //this.setupEventListeners();
        applyFilters();
    }
    
    function updateHomePageFilters() {
        let params = new URLSearchParams(window.location.search);
        /* ******************************************************************************************************************* */
        /* We want the order of appearance of the filters to be the chronological order in which they were selected, and this  */
        /* would not be possible to deduce by looking only at the URL as the filters are grouped by type (e.g. ‘brand=A,B,C    */
        /* &colour=1,2,3’) so the new filters are not appended to the query-string already present, something that would have  */
        /* been handy. One way we can do this is to iterate the filter values in the query-string and for each one we check    */
        /* whether there is already the corresponding tag, and if not, we add it at the end (to maintain chronological order). */
        /* ******************************************************************************************************************* */
        for (let [key, value] of params) {
            if (key == "brand" || (key == "min-price" && value != "0") || (key == "max-price" && value != "1000") || key == "size" || key == "color") {
                /* For each parameter we are interested in in this case (thus excluding those related to sorting, */
                /* type and gender), we split the associated string whenever we encounter a comma, excluding the  */
                /* separator from every substring: the function String.prototype.split() does exactly that.       */
                for (let i = 0; i < value.split(",").length; i ++) {
                    let idFilterHome = "filter-home-" + key + "-" + value.split(",")[i].replaceAll(" ", "."); /* "New Balance" ==> "New.Balance" */
                    if (key == "min-price") {
                        value = "&#8805;€" + value;
                    } else if (key == "max-price") {
                        value = "&#8804;€" + value;
                    }
                    if (key == "color") {
                        /* We want to keep the 1st letter of the color values in the      */
                        /* URL lowercase, but change it to uppercase in the home filters. */
                        values = value.split(",");
                        values = values.map(color => color[0].toUpperCase() + color.slice(1));
                        value  = values.join(",");
                    }
                    if (!document.querySelector(".removable-dynamic-filters").innerHTML.includes(idFilterHome)) {
                        document.querySelector(".removable-dynamic-filters").innerHTML += `
                            <div id="${idFilterHome}" class="home-filter">
                                <button onclick="removeHomePageFilter()">X</button><p>${value.split(",")[i]}</p>
                            </div>
                        `;
                    }
                    if (key == "color") {
                        /* We want the id of the <div> related to each color to be lowercase when the initial letter  */
                        /* of the color is formed, while we want it to be uppercase inside the <p>, so we revert      */
                        /* the string back to all lowercase for proper id creation at the beginning of the next loop. */
                        values = value.split(",");
                        values = values.map(color => color[0].toLowerCase() + color.slice(1));
                        value  = values.join(",");
                    }
                }
            }
        }
    }

    /* *********************************************************************************************** */ 
    /* We remove the value corresponding to the filter from those assigned to the parameter            */
    /* representing the category to which the former belongs. Changing the URL will automatically      */
    /* call the functions initializeInputTags(), updateFiltersSidebarHeight(), updateHomePageFilters() */
    /* which will take care of the graphics in both the sidebar and the home page.                     */
    /* *********************************************************************************************** */
    function removeHomePageFilter() {
        /* We obtain the value of the filter and the parameter it refers to from the id of the <div> tag */ 
        /* containing the X button that was clicked and from which the call to this function originated. */
        let filterValueParam = event.target.parentNode.id;
        filterValueParam = filterValueParam.replace("filter-home-", "");
        /* There are cases where there is more than one ‘-’, in particular when the parameters    */
        /* in concern are ‘min-price’ and ‘max-price’, so we start the search for hyphene after   */
        /* the position in which we would find it in the two aforementioned cases (having already */
        /* ensured that there is no parameter with a name consisting of only 3 letters).          */
        let indexOfHyphen = filterValueParam.indexOf("-", 4);
        let filterValue = filterValueParam.slice(indexOfHyphen + 1);
        let filterParam = filterValueParam.slice(0, indexOfHyphen);
        /* Let's remove the HTML code of the <div> related to the filter */
        document.querySelector(".removable-dynamic-filters").removeChild(event.target.parentNode);
        /*
        Since the URLSearchParams's delete() method in its delete(name, value) form does not work as we would expect in the 
        case where in the query-string several values associated with a single parameter are separated by commas and the 
        parameter name is not repeated forming key-value pairs, we have to remove the single value by string manipulation.
        POSSIBLE SCENARIOS:
        (1) %2C filterValue &    last position.
        (2) %2C filterValue %2C  neither first nor last position.
        (3)   = filterValue &    first and last position.
        (4)   = filterValue %2C  first (not last) position.
        */
        let params = new URLSearchParams(window.location.search);
        let paramsString = params.toString();
        console.log(params.toString());
        filterValue = filterValue.replace(".", "+"); /* "New.Balance" ==> "New Balance" */
        if (paramsString.includes("%2C" + filterValue + "&")) {
            paramsString = paramsString.replace("%2C" + filterValue + "&", "&");
        } else if(paramsString.includes("%2C" + filterValue + "%2C")) {
            paramsString = paramsString.replace("%2C" + filterValue + "%2C", "%2C");
        } else if(paramsString.includes("=" + filterValue + "&")) {
            params.delete(filterParam);
            window.location.search = params.toString();
            return;
        } else if (paramsString.includes("=" + filterValue + "%2C")) {
            paramsString = paramsString.replace("=" + filterValue + "%2C", "=");
        } // ASSICURATI CHE I NUOVI TIPI DI FILTRO VENGANO ANTEPOSTI ALL'ORDINAMENTO, GENERE ETC. PER EVITARE DI DOVER GESTIRE ANCHE IL CASO DI FINE STRINGA !!!
        params = new URLSearchParams(paramsString);
        window.location.search = params.toString();
    }

    /* ********************************************************************************************************************* */
    /* Our goal is to make the sidebar scrollable, and the least hassle-free way to do that is to set a value in pixels      */
    /* as the height of the menu, since otherwise we would need to have height instructions all the way though. If there     */
    /* is a height instruction missing on one of the parent elements up to and including the body and root elements (html),  */
    /* then the x% won't mean anything to the <aside>. Since we don't want to access all the elements down the tree, then    */
    /* we will need a parent element with a fixed height, or the element itself. Whenever the window is resized we calculate */
    /* the sum of the heights of <main>, <footer> and the dynamically stretchable <div> that prevents the page content from  */
    /* ending vertically before the viewport. We then assign this amount in pixels to the height of the filter sidebar.      */
    /* ********************************************************************************************************************* */
    function updateFiltersSidebarHeight() {
        let updatedHeight = document.querySelector("body > main").clientHeight 
                          + document.querySelector("body > footer").clientHeight
                          + document.getElementById("fill-height").clientHeight;
        let updatedHeightPx = updatedHeight.toString().concat("px");
        document.getElementsByClassName("filter-sidebar")[0].style.height = updatedHeightPx;
    }

    /* ********************************************************************************************** */
    /* Shows the value of the range on which the ball is currently placed, which does not necessarily */
    /* represent the value of the <input> tag at that instant, unless the mouse button is released.   */
    /* ********************************************************************************************** */
    function displayPriceBounds() {
        let minPriceTag = document.getElementById("filter-sidebar-min-price");
        let maxPriceTag = document.getElementById("filter-sidebar-max-price");
        document.querySelector("label[for='filter-sidebar-min-price'] + p").innerText = "Min: €" + minPriceTag.value;
        document.querySelector("label[for='filter-sidebar-max-price'] + p").innerText = "Max: €" + maxPriceTag.value;    
    }

    /* We prevent the insertion of incompatible minimum and maximum price values. */
    function correctPriceBounds() {
        let minPriceTag = document.getElementById("filter-sidebar-min-price");
        let maxPriceTag = document.getElementById("filter-sidebar-max-price");
        if (Number(minPriceTag.value) > Number(maxPriceTag.value)) { // Beware of the conversion, otherwise it would compare the strings, with misleading outcomes from those we would expect.
            minPriceTag.value = maxPriceTag.value;
        }
        document.querySelector("label[for='filter-sidebar-min-price'] + p").innerText = "Min: €" + minPriceTag.value;
        document.querySelector("label[for='filter-sidebar-max-price'] + p").innerText = "Max: €" + maxPriceTag.value;   
    }

    function shiftFilterSidebar() {
        if (document.getElementsByClassName("filter-sidebar")[0].style.translate == "-100%") {
            document.getElementsByClassName("filter-sidebar")[0].style.translate = 0;
            disableScrollOnBG();
            document.getElementsByClassName("filter-sidebar")[0].style.overflow = "auto";
        } else {
            document.getElementsByClassName("filter-sidebar")[0].style.translate = "-100%";
            enableScrollOnBG();
            updateURL();
            applyFilters();
            //location.reload();
        }
    }

    /* If the filter sidebar is currently being displayed (in the viewport) and we intercept a click outside it then we close it. */
    document.body.addEventListener('click', function() {
        let myElementToCheckIfClicksAreInsideOf = document.getElementsByClassName("filter-sidebar")[0];
        if (document.getElementsByClassName("filter-sidebar")[0].style.translate == "0px" && !myElementToCheckIfClicksAreInsideOf.contains(event.target)) {
            document.getElementById("filter-icon").checked = false;
            shiftFilterSidebar();
            event.preventDefault();
        }
    });

    /* ******************************************************************************************************************** */
    /* Lets now implement the function associated with the pressure of the ‘Reset’ button in the <footer> of the sidebar    */
    /* related to filters. To clear the selections made we will first have to remove from the current URL the substrings    */
    /* containing the name of the parameter for which the user provided input and the values associated with it. After      */
    /* that we simply call the previously implemented function initializeInputTags() which will update the status of the    */
    /* <input> tags according to what is specified in the current query-string and, as nothing is specified, it will reset  */
    /* them to their default values. Finally, the latter function will in turn call updateSelectedBorders() which will      */
    /* take care of updating the sidebar graphics by removing the visual feedback of the selected elements where necessary. */
    /* ******************************************************************************************************************** */
    function resetAllFilters() {
        // Step #1
        let params = new URLSearchParams(window.location.search);
        console.log(params.toString());
        params.delete('brand');
        params.delete('size');
        params.delete('color');
        params.set('min-price', document.getElementById("filter-sidebar-min-price").min);
        params.set('max-price', document.getElementById("filter-sidebar-max-price").max);
        console.log(params.toString());
        /* Step #2 & #3 */
        /* Changing the current URL in this way automatically reloads the page, triggering the window's ‘load’ */
        /* event, hence the call to the initializeInputTags() function, which we will not need to make here.   */
        window.location.search = params.toString();
    }

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
        let queryStringBrands = new URLSearchParams(window.location.search).get('brand');
        //console.log(queryStringBrands);
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

    /* ********************************************************************************************************* */
    /* After selecting an arbitrary number of checkboxes in the filter sidebar, particularly those related       */
    /* to brands, the selections we have made are queued to the values assigned to the “brand” parameter         */ 
    /* of the query string contained in the current URL. The problem arises when we refresh the page, since      */ 
    /* although the address remains unchanged (thus retaining the above values) the <input> tags lose the        */ 
    /* checked attribute, so we would be in an inconsistent state. We solve this by assigning the “checked”      */ 
    /* attribute to checkboxes whose label text is contained in the query string whenever the page is refreshed. */
    /* ********************************************************************************************************* */ 
    function initializeInputTags() {
        // Brands.
        let brandInputs = document.querySelectorAll(".brand-option > input");
        let brandLabels = document.querySelectorAll(".brand-option > label");
        let queryStringBrands = new URLSearchParams(window.location.search).get('brand');
        for (let i = 0; i < brandInputs.length; i++) {
            if (queryStringBrands != null && (queryStringBrands.toUpperCase()).includes(brandLabels[i].textContent)) {
                brandInputs[i].checked = true;
            } else {
                brandInputs[i].checked = false;
            }
        }
        // Sizes.
        let sizeInputs = document.querySelectorAll(".size-option > input");
        let sizeLabels = document.querySelectorAll(".size-option > label");
        let queryStringSizes = new URLSearchParams(window.location.search).get('size');
        for (let i = 0; i < sizeInputs.length; i++) {
            if (queryStringSizes != null && queryStringSizes.includes(sizeLabels[i].textContent)) {
                sizeInputs[i].checked = true;
            } else {
                sizeInputs[i].checked = false;
            }
        }
        // Colors.
        let colorInputs = document.querySelectorAll(".color-option > label > input");
        let colorLabels = document.querySelectorAll(".color-option > label:nth-child(2)");
        let queryStringColors = new URLSearchParams(window.location.search).get('color');
        for (let i = 0; i < colorInputs.length; i++) {
            if (queryStringColors != null && (queryStringColors.toUpperCase()).includes((colorLabels[i].textContent).toUpperCase())) {
                colorInputs[i].checked = true;
            } else {
                colorInputs[i].checked = false;
            }
        }
        // Prices.
        let minPriceTag = document.getElementById("filter-sidebar-min-price");
        let maxPriceTag = document.getElementById("filter-sidebar-max-price");
        let queryStringMinPrice = new URLSearchParams(window.location.search).get('min-price');
        let queryStringMaxPrice = new URLSearchParams(window.location.search).get('max-price');
        if (queryStringMinPrice != null) {
            minPriceTag.value = queryStringMinPrice;
        }
        if(queryStringMaxPrice != null) {
            maxPriceTag.value = queryStringMaxPrice;
        }

        let categoryInputs = document.querySelectorAll('input[name="category"]');
        let queryStringCategory = new URLSearchParams(window.location.search).get('category') || 'popular';
        categoryInputs.forEach(input => {
            input.checked = (input.value === queryStringCategory);
        });

        correctPriceBounds();
        /* We curate the style of the selections deduced from the URL, since if we did not do this now the next         */
        /* call to the updateSelectedBorders() function would occur at the first state change of one of the checkboxes. */
        updateSelectedBorders();
    }
    
    function applyFilters() {
        filteredProducts = allProducts.filter(product => {
              /* Checks if any variant matches the filters (colors and sizes) */
            const hasMatchingVariant = !filters.color.length && !filters.size.length ? true :
            product.variants.some(variant => {
                const matchesColor = !filters.color.length || filters.color.includes(variant.color);
                const matchesSize = !filters.size.length    || filters.size.includes(variant.size);
                return matchesColor && matchesSize;
            });
            /* Brand filter */
            const brandMatch = filters.brand.length === 0 || filters.brand.includes(product.brand);
            /* Genre filter */
            const genreMatch = !filters.genre || (product.genre && product.genre.toLowerCase() === filters.genre.toLowerCase());
            /* Type filter */
            const typeMatch = !filters.type || (product.type && product.type.toLowerCase() === filters.type.toLowerCase());
            /* Category filter */
            let categoryMatch = true;
            switch(filters.category) {
                case 'discounted':
                    //categoryMatch = product.variants.some(v => v.discount > 0);
                    categoryMatch = product.isDiscounted;
                    break;
                case 'novelties':
                    const oneMonthAgo = new Date();
                    oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
                    categoryMatch = new Date(product.created_at) > oneMonthAgo;
                    break;
            }
            /* Min price filter */
            const minPriceMatch = Number(filters.minPrice) <= Number(product.price);
            /* Max price filter */
            const maxPriceMatch = Number(filters.maxPrice) >= Number(product.price);
            return brandMatch && genreMatch && typeMatch && categoryMatch 
                        && hasMatchingVariant && minPriceMatch && maxPriceMatch;
        });
        sortProducts();
        renderProducts();
        updateBreadcrumb();
    }

    function sortProducts() {
        filteredProducts.sort((a, b) => {
            switch(filters.sort) {
                case 'price-low-to-high':
                    return a.price - b.price;
                case 'price-high-to-low':
                    return b.price - a.price;
                case 'alphabetical':
                    return a.name.localeCompare(b.name);
                case 'reviews-low-to-high':
                    return a.meanReviws - b.meanReviws;
                case 'reviews-high-to-low':
                    return b.meanReviws - a.meanReviws;
                default:
                    return 0;
            }
        });
    }

    function renderProducts() {
        const container = document.getElementById('products-container');
        container.innerHTML = '';
        //debug to see if there's a filed name mismatch
        console.log(allProducts);
        if (filteredProducts.length === 0) {
            container.innerHTML = '<p>No products found matching your criteria.</p>';
            console.log("nessun prodotto super ai filtri");
            return;
        }
        filteredProducts.forEach(product => {
            const productElement = createProductElement(product);
            container.appendChild(productElement);
        });
    }

    function createProductElement(product) {
        const template = document.getElementById('product-template');
        const productCard = template.content.cloneNode(true);
        const card = productCard.querySelector('.product-card');
        card.dataset.productId = product.id;
        const link = card.querySelector('.product-link');
        link.href = `product.php?id=${product.id}`;
        /*<img src="CSS/Images/Products/<?php echo htmlspecialchars($product['Nome']. '_' . $i); ?>.webp" 
        alt="<?php echo htmlspecialchars($product['Nome']); ?> - View <?php echo $i; ?>"> */
        const img = card.querySelector('img');
        img.src = `CSS/Images/Products/${product.name}_1.webp`;
        //fallback image if image fetch fails.
        img.onerror = function() {
            img.src = `CSS/Images/Products/default_shoe.webp`;
        };
        console.log(`CSS/Images/Products/${product.name}_1.webp`);
        img.name = `${product.Nome} - View ${1}`;
        card.querySelector('.product-name').textContent = product.name;
        // Shows price range if variants have different prices
        const prices = product.variants.map(v => v.price);
        const minPrice = Math.min(...prices);
        const maxPrice = Math.max(...prices);
        const priceElement = card.querySelector('.product-price');
        if (minPrice === maxPrice) {
            priceElement.textContent = `€${minPrice.toFixed(2)}`;
        } else {
            priceElement.textContent = `€${minPrice.toFixed(2)} - €${maxPrice.toFixed(2)}`;
        }
        const wishlistCheckbox = card.querySelector('.wishlist-checkbox');
        const imgheart= productCard.querySelector('img.wishlist-checkbox');
        if (wishlistItems.has(product.id.toString())) {
            console.log("disabilita check");
            wishlistCheckbox.checked = true;
            imgheart.src="CSS/Images/Icons/heart_filled.svg";
         // wishlistCheckbox.nextElementSibling.textContent = 'Remove from Wishlist';
        }
        return card;
    }
    function capitalizeFirstLetter(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }


    function updateBreadcrumb() {
        const breadcrumbNav = document.querySelector('.breadcrumb');
        if (!breadcrumbNav) return;

        if (!filters.genre && !filters.type) {
            breadcrumbNav.style.display = 'none';
            return;
        }

        breadcrumbNav.style.display = 'block';
        const ol = breadcrumbNav.querySelector('ol');
        ol.innerHTML = `
            <li><a href="home.php">Home</a></li>
            ${filters.genre ? `<li><a href="home.php?genre=${encodeURIComponent(filters.genre)}">${capitalizeFirstLetter(filters.genre)}</a></li>` : ''}
            ${filters.type ? `<li><span aria-current="page">${capitalizeFirstLetter(filters.type)}</span></li>` : ''}
        `;
    }

    /* *************************************************************************************************************************** */
    /* Our primary source of information for understanding selected filters and sorting will be the URL just as we already do      */
    /* (separately) for sorting type or genre and type of products. This is because the input tags lose their memory following a   */
    /* simple refresh so we need a more stable resource. We will call this function, which appends the selected filters to the     */
    /* query string (thus taking them to a safe place) whenever the user changes the filters, and this can happen on 2 occasions:  */
    /* (1) By modifying the filter sidebar, so we should trigger the call on pressing “Done”, “Reset” or in general on closing the */
    /* sidebar, which can also happen by clicking outside the sidebar.                                                             */
    /* (2) Removing individual filters from the home page with the <button> X, so in this case we should be listening for clicks.  */
    /* *************************************************************************************************************************** */
    function updateURL() {
        let categoryInputs = document.querySelectorAll('fieldset input[name="category"]');
        let brandInputs    = document.querySelectorAll(".filter-sidebar .brand-options .brand-option input");
        let minPriceInput  = document.querySelector('.filter-sidebar .price-option input[name="filter-sidebar-min-price"]');
        let maxPriceInput  = document.querySelector('.filter-sidebar .price-option input[name="filter-sidebar-max-price"]');
        let sizeInputs     = document.querySelectorAll('.size-options .size-option input');
        let colorInputs    = document.querySelectorAll('.color-options .color-option input');
        let currentSorting = document.querySelector(".sort-sidebar .sort-switch input:checked").value;
        
        const newParams = new URLSearchParams();

        /* Genre */
        newParams.set("genre",filters.genre);

        /*Type */
        newParams.set("type",filters.type);

        /*Categories*/
        for (let category of categoryInputs) {
            if (category.checked) {
                newParams.set("category", category.value);
            }
        }
        /* Brands. */
        let brandQueryString = "";
        for (let brandInput of brandInputs) {
            if (brandInput.checked) {
                brandQueryString += brandInput.value + ",";
            }
        }
        if (brandQueryString != "") { /* To avoid adding to the URL the parameter with an empty string on the RHS */
            brandQueryString = brandQueryString.slice(0, -1); /* We remove the additional comma ',' at the end of the string. */
            newParams.set("brand", brandQueryString);
        }
        /* Sizes. */
        let sizeQueryString = "";
        for (let sizeInput of sizeInputs) {
            if (sizeInput.checked) {
                sizeQueryString += sizeInput.value + ",";    
            }
        }
        if (sizeQueryString != "") {
            sizeQueryString = sizeQueryString.slice(0, -1);
            newParams.set("size", sizeQueryString);
        }
        /* Colors. */
        let colorQueryString = "";
        for (let colorInput of colorInputs) {
            if (colorInput.checked) {
                colorQueryString += colorInput.value.charAt(0).toUpperCase() + String(colorInput.value).slice(1) + ",";
            }
        }
        if (colorQueryString != "") {
            colorQueryString = colorQueryString.slice(0, -1);
            newParams.set("color", colorQueryString);
        }
        /* Min price. */
        newParams.set("min-price", minPriceInput.value);
        /* Max price. */
        newParams.set("max-price", maxPriceInput.value);
        /* Product sorting. */
        newParams.set("sort", currentSorting);
        /* Category */
        window.location.search = newParams.toString();
    }

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
            updateURL();
            applyFilters();
         //   applyFilters();
        }
    }

</script>

