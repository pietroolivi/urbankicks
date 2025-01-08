<?php
// Get category from URL parameter, default to 'popular'
$category = isset($_GET['category']) ? $_GET['category'] : 'popular';

// Get sort parameter from URL, default to 'price-low-to-high'
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price-low-to-high';

// Get Genre and Type from URL parameters
$genre = isset($_GET['genre']) ? $_GET['genre'] : "";
$type = isset($_GET['type']) ? $_GET['type'] : "";
?>

<h2>
    <?php 
    if ($genre || $type) {
        echo trim($genre . ' ' . $type);
    } else {
        echo "All Products";
    }
    ?>
</h2>

<?php if ($genre || $type): ?>
    <!-- Breadcrumb Navigation -->
    <nav aria-label="Breadcrumb" class="breadcrumb">
        <ol>
            <li>Home</li>
            <?php if ($genre): ?>
                <li><a href="index.php?genre=<?php echo urlencode($genre); ?>"><?php echo ucfirst($genre); ?></a></li>
            <?php endif; ?>
            <?php if ($type): ?>
                <li><span aria-current="page"><?php echo ucfirst($type); ?></span></li>
            <?php endif; ?>
        </ol>
    </nav>
<?php endif; ?>

<div class="filters-and-sorters">
    <!-- Category Selection -->
    <fieldset class="category-switch">
        <legend>Please select the category of articles to be displayed:</legend>
        <input type="radio" id="popular" name="category" value="popular" <?php echo $category === 'popular' ? 'checked' : ''; ?>>
        <label for="popular">Popular</label>
        <input type="radio" id="discounted" name="category" value="discounted" <?php echo $category === 'discounted' ? 'checked' : ''; ?>>
        <label for="discounted">Discounted</label>
        <input type="radio" id="novelties" name="category" value="novelties" <?php echo $category === 'novelties' ? 'checked' : ''; ?>>
        <label for="novelties">Novelties</label>
    </fieldset>

    <!-- Filter Menu -->
    <label for="filter-icon">Open/close menu with available product filters.</label>
    <label class="filter-menu"><input id="filter-icon" type="checkbox"></label>
    <aside class="filter-sidebar">
        <h3>DESIGNERS</h3>
        <?php
        $brands = ["ADIDAS", "NIKE", "NEW BALANCE", "CONVERSE"];
        foreach($brands as $brand): ?>
            <div>
                <input type="checkbox" id="<?php echo strtolower($brand); ?>" name="designers[]" value="<?php echo $brand; ?>" 
                    <?php echo isset($designers) && in_array($brand, $designers) ? 'checked' : ''; ?>>
                <label for="<?php echo strtolower($brand); ?>"><?php echo $brand; ?></label>
            </div>
        <?php endforeach; ?>

        <h3>COLOR</h3>
        <?php
        $colors = ["blue", "purple", "red", "green", "white", "yellow"];
        foreach($colors as $color): ?>
            <div class="color-option">
                <input type="checkbox" id="<?php echo $color; ?>" name="colors[]" value="<?php echo $color; ?>">
                <label for="<?php echo $color; ?>"><?php echo ucfirst($color); ?></label>
            </div>
        <?php endforeach; ?>
    </aside>

    <!-- Sort Menu -->
    <label for="sort-icon">Open/close menu with available product sorting criteria.</label>
    <label class="sort-menu"><input id="sort-icon" type="checkbox"></label>
    <aside class="sort-sidebar">
        <fieldset class="sort-switch">
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
</div>