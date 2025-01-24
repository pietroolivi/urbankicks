<?php
if(!isset($templateParams["productData"])) {
    header("Location: home.php");
    exit();
}
$product = $templateParams["productData"]["product"];
$variants = $templateParams["productData"]["variants"];
$reviews = $templateParams["productData"]["reviews"];

$hasAvailableVariants = false; // Controlla se esiste almeno una variante disponibile
foreach ($variants as $variant) {
    if ($variant['Quantita'] > 0) {
        $hasAvailableVariants = true;
        break;
    }
}

?>

<h4><a href="#"><?php echo htmlspecialchars($product['Marca']); ?></a></h4>
<h2><?php echo htmlspecialchars($product['Nome']); ?></h2>
<h3>€<?php echo number_format($product['Prezzo'], 2); ?></h3>

<?php if(!empty($reviews)): 
    $avgRating = array_sum(array_column($reviews, 'Punteggio')) / count($reviews);
?>
    <p>(<?php echo count($reviews); ?>)</p>
    <span class="star-reviews" style="--rating:<?php echo $avgRating; ?>"></span>
    <p><a class="number-reviews" href="#reviews"><?php echo number_format($avgRating, 1); ?></a></p>
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

<?php if (!$hasAvailableVariants): // Mostra il messaggio se non ci sono varianti disponibili ?>
    <section id="product-unavailable">
        <h3>Product not currently available</h3>
        <button id="wishlist-unavailable" data-product-id="<?php echo htmlspecialchars($product['ID_Prodotto']); ?>"
        data-in-wishlist="<?php echo $templateParams["productData"]["inWishlist"] ? 'true' : 'false'; ?>">Add to wishlist to track it</button>
    </section>
<?php endif; ?>

<?php if ($hasAvailableVariants): ?>
<section id="size">
    <h3>Size</h3>
    <?php 
    $availableSizes = array_unique(array_column($variants, 'Taglia'));
    $anySizeAvailable = false; // Variabile per tracciare se esiste almeno una taglia disponibile
    foreach($availableSizes as $size): 
        $available = false;
        foreach($variants as $variant) {
            if($variant['Taglia'] == $size && $variant['Quantita'] > 0) {
                $available = true;
                $anySizeAvailable = true; // Almeno una taglia è disponibile
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
    <?php if (!$anySizeAvailable): // Aggiungi un avviso se nessuna taglia è disponibile ?>
        <p class="no-sizes-available">No sizes available</p>
    <?php endif; ?>
    
    <p id="size-error"></p>
</section>

<section id="color">
    <h3>Color</h3>
    <?php 
    $availableColors = array_unique(array_column($variants, 'Colore'));
    $anyColorAvailable = false; // Variabile per tracciare se esiste almeno un colore disponibile
    foreach($availableColors as $color): 
        $available = false;
        foreach($variants as $variant) {
            if($variant['Colore'] == $color && $variant['Quantita'] > 0) {
                $available = true;
                $anyColorAvailable = true; // Almeno un colore è disponibile
                break;
            }
        }
    ?>
        <label for="color<?php echo $color; ?>" 
               class="<?php echo !$available ? 'unavailable' : ''; ?>">
            <?php echo $color; ?>
        </label>
        <input id="color<?php echo $color; ?>" 
               type="radio" 
               name="color" 
               value="<?php echo $color; ?>"
               <?php echo !$available ? 'disabled' : ''; ?>>
    <?php endforeach; ?>
    
    <?php if (!$anyColorAvailable): // Aggiungi un avviso se nessun colore è disponibile ?>
        <p class="no-colors-available">No colors available</p>
    <?php endif; ?>
</section>
<p id="wishlist-cart-error"></p>
<button id="addToCartButton" data-product-id="<?php echo htmlspecialchars($product['ID_Prodotto']); ?>">
    Add to cart
</button>

<button id="wishlistButton" 
       data-product-id="<?php echo htmlspecialchars($product['ID_Prodotto']); ?>"
        data-in-wishlist="<?php echo $templateParams["productData"]["inWishlist"] ? 'true' : 'false'; ?>">
    <?php 
    echo $templateParams["productData"]["inWishlist"] ? 'Remove from' : 'Add to'; ?> wishlist
</button>
<?php endif; ?>
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



<template id="review-template">
        <article class="review">
            <!-- Imposteremo la variabile --rating con JS -->
            <span class="review-rating" style="--rating:0"></span>
            <p class="review-description"></p>
            <small class="review-details"></small>
        </article>
 </template>



<script>
function linkToClipboard() {
    navigator.clipboard.writeText(window.location.href);
    alert("Page link copied into clipboard!");
}
</script>