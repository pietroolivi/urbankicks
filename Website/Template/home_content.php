<h2>Man's Sneakers</h2>
<div class="filters-and-sorters">
    <!-- 
    <label class="filter-menu"><input type="checkbox"></label>
    <aside class="filter-sidebar">
        <fieldset class="filter-switch">
            <legend>Please select the category of articles to be displayed:</legend>
            <input type="radio" id="switch3-radio1" name="radio" checked="checked"/><label for="switch3-radio1">PRICE LOW TO HIGH</label><input type="radio" id="switch3-radio2" name="radio"/><label for="switch3-radio2">PRICE HIGH TO LOW</label><input type="radio" id="switch3-radio3" name="radio"/><label for="switch3-radio3">ALPHABETICAL</label>
        </fieldset>
            <li><a href="#">REVIEWS LOW TO HIGH</a></li>
            <li><a href="#">REVIEWS HIGH TO LOW</a></li>
    </aside>
    -->
    <fieldset class="category-switch">
        <legend>Please select the category of articles to be displayed:</legend>
        <input type="radio" id="popular" name="category" value="popular" checked="checked"/><label for="popular">Popular</label><input type="radio" id="discounted" name="category" value="discounted"/><label for="discounted">Discounted</label><input type="radio" id="novelties" name="category" value="novelties"/><label for="novelties">Novelties</label>
    </fieldset>
    <label for="sort-icon">Open/close menu with available product sorting criteria.</label>
    <label class="sort-menu"><input id="sort-icon" type="checkbox"></label>
    <aside class="sort-sidebar">
        <fieldset class="sort-switch">
            <legend>Please select the order in which to display the items:</legend>
            <input type="radio" id="price-low-to-high" name="sort" value="price-low-to-high" checked="checked"/><label for="price-low-to-high">PRICE LOW TO HIGH</label><input type="radio" id="price-high-to-low" name="sort" value="price-high-to-low"/><label for="price-high-to-low">PRICE HIGH TO LOW</label><input type="radio" id="alphabetical" name="sort" value="alphabetical"/><label for="alphabetical">ALPHABETICAL</label><input type="radio" id="reviews-low-to-high" name="sort" value="reviews-low-to-high"/><label for="reviews-low-to-high">REVIEWS LOW TO HIGH</label><input type="radio" id="reviews-high-to-low" name="sort" value="reviews-high-to-low"/><label for="reviews-high-to-low">REVIEWS HIGH TO LOW</label>
        </fieldset>
    </aside>
</div>
<!-- We used the aria-label and aria-current attributes to help assistive technology users understand what this navigation is and where the current page is in the structure -->
<nav aria-label="Breadcrumb" class="breadcrumb">
    <ol>
        <li><a href="#">Home</a></li>
        <li><a href="#">Man</a></li>
        <li><a href="#">Sneakers</a></li>
        <li><span aria-current="page">Man's Sneakers</span></li>
    </ol>
</nav>