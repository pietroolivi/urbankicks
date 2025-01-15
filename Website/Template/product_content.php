<?php
if(!isset($templateParams["productData"])) {
    header("Location: home.php");
    exit();
}
$product = $templateParams["productData"]["product"];
$variants = $templateParams["productData"]["variants"];
$reviews = $templateParams["productData"]["reviews"];
?>

<h4><a href="#"><?php echo htmlspecialchars($product['Marca']); ?></a></h4>
<h2><?php echo htmlspecialchars($product['Nome']); ?></h2>
<h3>€<?php echo number_format($product['Prezzo'], 2); ?></h3>

<?php if(!empty($reviews)): 
    $avgRating = array_sum(array_column($reviews, 'Punteggio')) / count($reviews);
?>
    <p>(<?php echo count($reviews); ?>)</p>
    <span style="--rating:<?php echo $avgRating; ?>"></span>
    <p><a href="#reviews"><?php echo number_format($avgRating, 1); ?></a></p>
<?php endif; ?>

<button id="share-page" onclick="linkToClipboard()">SHARE</button>

<div class="product-image">    
    <div class="track">
        <ul>
            <?php for($i = 1; $i <= 4; $i++): ?>
                <li id="slide<?php echo $i; ?>">
                    <img src="CSS/Images/Products/<?php echo htmlspecialchars($product['ID_Prodotto']. '_' . $product['Genere'] . $i); ?>.webp" 
                            alt="<?php echo htmlspecialchars($product['Nome']); ?> - View <?php echo $i; ?>">
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <div class="slides">
        <?php for($i = 1; $i <= 4; $i++): ?>
            <a href="#slide<?php echo $i; ?>">
                <img src="CSS/Images/Products/<?php echo htmlspecialchars($product['ID_Prodotto']. '_' . $product['Genere'] . $i); ?>.webp" 
                        alt="<?php echo htmlspecialchars($product['Nome']); ?> - Thumbnail <?php echo $i; ?>">
            </a>
        <?php endfor; ?>
    </div>
</div>

<section id="description">
    <h3>Description</h3>
    <p><?php echo htmlspecialchars($product['Descrizione']); ?></p>
</section>

<section id="size">
    <h3>Size</h3>
    <?php 
    $availableSizes = array_unique(array_column($variants, 'Taglia'));
    foreach($availableSizes as $size): 
        $available = false;
        foreach($variants as $variant) {
            if($variant['Taglia'] == $size && $variant['Quantita'] > 0) {
                $available = true;
                break;
            }
        }
    ?>
        <label for="size<?php echo $size; ?>" 
               class="<?php echo !$available ? 'unavailable' : ''; ?>">
            <?php echo $size; ?>
        </label>
        <input id="size<?php echo $size; ?>" 
               type="radio" 
               name="size" 
               value="<?php echo $size; ?>"
               <?php echo !$available ? 'disabled' : ''; ?>>
    <?php endforeach; ?>
    <p id="sizeParagraphWarning"></p>
</section>

<section id="color">
    <h3>Color</h3>
    <?php 
    $availableColors = array_unique(array_column($variants, 'Colore'));
    foreach($availableColors as $color): ?>
        <label for="<?php echo $color; ?>"><?php echo htmlspecialchars($color); ?></label>
        <input id="<?php echo $color; ?>" 
               type="radio" 
               name="color" 
               value="<?php echo $color; ?>">
    <?php endforeach; ?>
    <p id="colorParagraphWarning"></p>
</section>
<button id="addToCartButton" data-product-id="<?php echo htmlspecialchars($product['ID_Prodotto']); ?>"
        <?php echo $product['Sta_Tipo'] !== 'disponibile' ? 'disabled' : ''; ?>>
    Add to cart
</button>

<button id="wishlistButton" 
       data-product-id="<?php echo htmlspecialchars($product['ID_Prodotto']); ?>"
        data-in-wishlist="<?php echo $templateParams["productData"]["inWishlist"] ? 'true' : 'false'; ?>">
    <?php 
    echo $templateParams["productData"]["inWishlist"] ? 'Remove from' : 'Add to'; ?> wishlist
</button>

<aside id="reviews">
    <h3>Reviews</h3>
    <p id="review-error"></p>
            <form id="add-a-review">
            <textarea id="comment-review" placeholder="Add your review (max 160 characters)" maxlength="234" rows="4" cols="40"></textarea>
            <span style="--rating:0"></span>
            <button id="review-submit" type="submit">Send</button>
        </form>
    <?php foreach($reviews as $review): ?>
        <article class="review">
            <span style="--rating:<?php echo $review['Punteggio']; ?>"></span>
            <p><?php echo htmlspecialchars($review['Descrizione']); ?></p>
            <small>
                Posted on <?php echo date('d/m/Y', strtotime($review['Data_Recensione'])); ?>
                by <?php echo htmlspecialchars($review['Email']); ?>
            </small>
        </article>
    <?php endforeach; ?>
</aside>

<script>
function linkToClipboard() {
    navigator.clipboard.writeText(window.location.href);
    alert("Page link copied into clipboard!");
}
</script>