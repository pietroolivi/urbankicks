//------------------------------------------------Setting all the listeners----------------------------------------------------------
//___________________

/*
const sizeOptions = document.querySelectorAll('input[name="size"]');
const colorOptions = document.querySelectorAll('input[name="color"]');
//adds event listener when either one of the size or color radio buttons change
sizeOptions.forEach(input => input.addEventListener('change', updateButtonState));
colorOptions.forEach(input => input.addEventListener('change', updateButtonState));

function updateButtonState() {
    const button=document.getElementById("addToCartButton");
    
    //checks if any of the size or color are selected.
    const sizeSelected = Array.from(sizeOptions).some(input => input.checked);
    const colorSelected = Array.from(colorOptions).some(input => input.checked);

    button.disabled = !(sizeSelected && colorSelected);
    if(!button.disabled){
        console.log("non dovresti veder qua");
    }
}
*/

const sizeOptions = document.querySelectorAll('input[name="size"]');
const colorOptions = document.querySelectorAll('input[name="color"]');
const addToCartButton = document.getElementById('addToCartButton');
let choosenTimesSize=0;
let choosenTimesColor=0;

if(addToCartButton!=null){
addToCartButton.addEventListener('click', async () => {
 
    const productId = addToCartButton.getAttribute('data-product-id');


    const selectedSize = Array.from(sizeOptions).find(input => input.checked)?.value;

 
    const selectedColor = Array.from(colorOptions).find(input => input.checked)?.value;
    console.log("this is the idprod: "+productId+" this is the size of added: "+selectedSize+" and this the color: "+selectedColor);

    if (!selectedSize || !selectedColor) {
        const sizeParagraph=document.getElementById("size-error");
        sizeParagraph.textContent="missing size";
        const colorParagraph=document.getElementById("color-error");
        colorParagraph.textContent="missing color";
        return
    }


    //const userEmail = "user@example.com"; 

    try {
        const response = await fetch('product_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'add_to_cart',
                product_id: productId,
                size: selectedSize,
                color: selectedColor,
              //  email: userEmail,
                quantity: 1 
            })
        });

        const data = await response.json();
  /*     const rawText = await response.text();
       console.log(rawText);*/
        if (data.success) {
           // alert(data.message || "Product added to cart successfully!");
           wishlistButton.textContent="";
        } else {
            if(data.message==="You need to be logged in"){
                window.location.href = "login.php";
            }
        /*    const cartError=document.getElementById('wishlist-cart-error');
            cartError.textContent=data.message;
          //  alert(data.message || "Failed to add product to cart.");
            if (selectedSize===undefined)
            {
                let sizeParagraph=document.getElementById("sizeParagraphWarning");
                sizeParagraph.value.textContent="missing size";
            }
            if(selectedColor=== ndefined){
                let colorParagraph=document.getElementById("colorParagraphWarning");
                colorParagraph.value.textContent="missing color";
            }*/
        }
    } catch (error) {
        console.error("Errore nella richiesta:", error);
    }
});
}




colorOptions.forEach(input => {
    input.addEventListener('change', async () => {
        if (input.checked) {
            const color = input.value;
            const productId = addToCartButton.dataset.productId; 
            await updateDisabledSizes(productId, color); 
            updateButtonState(); 
        }
    });
});


sizeOptions.forEach(input => {
    input.addEventListener('change', async () => {
        if (input.checked) {
            const size = input.value;
            const productId = addToCartButton.dataset.productId; 
            await updateDisabledColors(productId, size); 
            updateButtonState(); 
        }
    });
});


function updateButtonState() {
    // Checks if a color and a size are selected
   /* const sizeSelected = Array.from(sizeOptions).some(input => input.checked);
    const colorSelected = Array.from(colorOptions).some(input => input.checked);

    //disable or enable the cart button
    addToCartButton.disabled = !(sizeSelected && colorSelected);*/
}

//function to disable sizes
async function updateDisabledSizes(productId, color) { //quando è cliccato un colore
    try {
        const response = await fetch('product_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_disabled_sizes',
                product_id: productId,
                color: color
            })
        });
        console.log("BchoosenTimesColor: "+choosenTimesColor+" BchoosenTimeSize: "+choosenTimesSize);
        const data = await response.json();

        if (data.success) {
            const disabledSizes = data.disabledSizes;
            //increaseChoosen();
            console.log(disabledSizes);
           /* sizeOptions.forEach(input => {
                input.disabled = disabledSizes.includes(input.value);
            });*/
           /* if(choosenTimesColor>=1 && choosenTimesSize>=1){
                choosenTimesColor=0;
                choosenTimesSize=0;
            }
            choosenTimesColor++;*/
     /*       if(choosenTimesColor==1 && choo){
                Array.from(sizeOptions).filter(input => input.checked).forEach(inputcheck=>inputcheck.checked=false);
                choosenTimesSize=0;
            }
            choosenTimesColor++;
            if(choosenTimesSize>0 && choosenTimesColor>0){
                colorOptions.forEach(input => input.disabled=false);

            }*/
         /*  if(choosenTimesColor>=1 && choosenTimesSize==0){
            sizeOptions.forEach(input => {
                input.disabled = disabledSizes.includes(input.value);
            });
           }*/
           //else if(choosenTimesColor>=1 && choosenTimesSize==1){
          //
          //   Array.from(sizeOptions).filter(input => input.checked).forEach(inputcheck=>inputcheck.checked=false);
           // Array.from(colorOptions).filter(input => input.checked).forEach(inputcheck=>inputcheck.checked=false);
           choosenTimesColor++;
            if(choosenTimesColor>=1 && choosenTimesSize==0){
                sizeOptions.forEach(input => {
                    input.disabled = disabledSizes.includes(input.value);
                });
            }
            //sono stati scelti molteplici colori e ora è stato scelto il colore
            if(choosenTimesColor==1 && choosenTimesSize>=1){
                sizeOptions.forEach(input => {
                    input.disabled = false;
                });
                colorOptions.forEach(input=>{
                    input.disabled= false;
                });
            }

            if (choosenTimesColor>1 && choosenTimesSize>=1){
                Array.from(sizeOptions).filter(input => input.checked).forEach(inputcheck=>inputcheck.checked=false);
                choosenTimesColor=1;
                sizeOptions.forEach(input => {
                    input.disabled = disabledSizes.includes(input.value);
                });
                choosenTimesSize=0;
            }

            console.log("choosenTimesColor: "+choosenTimesColor+" choosenTimeSize: "+choosenTimesSize);
        } else {
            console.error("Errore nel recuperare le taglie:", data.message);
        }
    } catch (error) {
        console.error("Errore nella richiesta:", error);
    }
}

//________________________REVIEW_______________________________________-



const submitReviewButton=document.getElementById("review-submit");
const comment=document.getElementById("comment-review");
let ratingSelected = 0;


const ratingFromPoint = (evt) => {
    const el = evt.currentTarget;
    const pointerX = evt.pageX - el.offsetLeft;
    return Math.max(1, Math.min(5, Math.ceil(pointerX / el.offsetWidth * 5)));
};
const starRating = (el) => {
    const colorDefault = getComputedStyle(el).getPropertyValue("--color");
    const colorClick = "#f00";
    
    el.addEventListener("pointerdown", (evt) => {
        ratingSelected = ratingFromPoint(evt);
        el.style.setProperty("--color", colorClick);
        el.style.setProperty("--rating", ratingSelected);
    });
    
    el.addEventListener("pointermove", (evt) => {
        evt.preventDefault();
        const ratingHover = ratingFromPoint(evt);
        el.style.setProperty("--rating", ratingHover);
    });
    
    el.addEventListener("pointerleave", (evt) => {
        el.style.setProperty("--color", colorDefault);
        el.style.setProperty("--rating", ratingSelected);
    });
    el.addEventListener("pointerup", (evt) => {
        ratingSelected = ratingFromPoint(evt);
        console.log(ratingSelected); // @TODO: Send ratingSelected value to server
    });
};
document.querySelectorAll('[style="--rating:0"]').forEach(starRating);


submitReviewButton.addEventListener("click",  (event) => {
    event.preventDefault(); // Previene il comportamento predefinito (invio del form)
    addReview(
        addToCartButton.getAttribute('data-product-id'),
        ratingSelected,
        comment.value
    )}
);

async function addReview(productId,ratingSelected,comment){
    try{
        const response = await fetch('product_handler.php',{
            method:'POST',
            headers:{
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'add_review',
                product_id: productId,
                rating: ratingSelected,
                comment: comment
            })
        });
        const data = await response.json();

        if (data.success) {
            console.log("Review aggiunta con successo");
            
            const reviewsContainer = document.getElementById('reviews');
            const allReviews = reviewsContainer.querySelectorAll('article.review');
              // 1) Ottengo i dati della nuova recensione dal JSON
            const newReview = data.newReview;
            
            // 3) Per ciascun article, controlla se la <small> contiene "by email"
            allReviews.forEach(article => {
                const smallTag = article.querySelector('small');
                if (smallTag && smallTag.textContent.includes(`by ${newReview.Email}`)) {
                    // 4) Rimuove l'articolo se corrisponde
                    article.remove();
                }
            });
            const starReview=document.querySelector(".star-reviews");
            const numberReview=document.querySelector(".number-reviews");
            console.log("punteggio medio ora: "+newReview.PunteggioAVG);
            starReview.style.setProperty('--rating', newReview.PunteggioAVG);
            numberReview.innerText=newReview.PunteggioAVG.toFixed(1);
            // 2) Seleziono il template
            const template = document.getElementById('review-template');
            
            // 3) Clono il contenuto del template (true = cloniamo anche i nodi figli)
            const clone = template.content.cloneNode(true);
            
            // 4) Popolo il clone con i dati
            //    a) rating nello <span>
            clone.querySelector('span').style.setProperty('--rating', newReview.Punteggio);
            
            //    b) descrizione nel <p>
            clone.querySelector('p').textContent = newReview.Descrizione;
            
            //    c) small: "Posted on dd/mm/yyyy by email"
            clone.querySelector('small').textContent = 
                `Posted on ${newReview.Data_Recensione} by ${newReview.Email}`;
            
            // 5) Aggiungo il clone in coda all'aside
            reviewsContainer.appendChild(clone);
        }
        if(!data.success){
            console.log("l'id prodotto è:"+productId);
            console.error(data.message);
            document.getElementById("review-error").innerText=data.message;
            
        }
    }catch(error){

    }

}

// Function to disable colors
async function updateDisabledColors(productId, size) {
    try {
        const response = await fetch('product_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_disabled_colors',
                product_id: productId,
                size: size
            })
        });
        console.log("BchoosenTimesColor: "+choosenTimesColor+" BchoosenTimeSize: "+choosenTimesSize);
        const data = await response.json();

       if (data.success) {
            const disabledColors = data.disabledColors;
                choosenTimesSize++;
                if(choosenTimesSize>=1 && choosenTimesColor==0){
                    colorOptions.forEach(input => {
                        input.disabled = disabledColors.includes(input.value);
                    });
                }
                //sono stati scelti molteplici colori e ora è stato scelto il colore
                if(choosenTimesSize==1 && choosenTimesColor>=1){
                    sizeOptions.forEach(input => {
                        input.disabled = false;
                    });
                    colorOptions.forEach(input=>{
                        input.disabled= false;
                    });
                }
    
                if (choosenTimesSize>1 && choosenTimesColor>=1){
                    Array.from(colorOptions).filter(input => input.checked).forEach(inputcheck=>inputcheck.checked=false);
                    choosenTimesSize=1;
                    colorOptions.forEach(input => {
                        input.disabled = disabledColors.includes(input.value);
                    });
                    choosenTimesColor=0;
                }
    


            console.log("choosenTimesColor: "+choosenTimesColor+" choosenTimeSize: "+choosenTimesSize);
        } else {
            console.error("Errore nel recuperare i colori:", data.message);
        }
    } catch (error) {
        console.error("Errore nella richiesta:", error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const wishlistButton = document.getElementById('wishlistButton');
    const wishlistError=document.getElementById('wishlist-cart-error');
    if(wishlistButton!=null){
        wishlistButton.addEventListener('click', async () => {
            const inWishlist =wishlistButton.getAttribute('data-in-wishlist') === 'true'; // Stato attuale
            const productId = wishlistButton.getAttribute('data-product-id');

            try {
                const action = inWishlist ? "remove_from_wishlist" : "add_to_wishlist";

                const response = await fetch('product_handler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: action,
                        product_id: productId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Aggiorna il pulsante in base al nuovo stato
                    wishlistButton.dataset.inWishlist = (!inWishlist).toString();
                    wishlistButton.textContent = (!inWishlist ? 'Remove from' : 'Add to') + ' wishlist';
                    wishlistError.textContent='';
                } else {
                    if(data.message==="You need to be logged in"){
                        window.location.href = "login.php";
                    }
             //   alert(data.message || 'An error occurred while updating the wishlist.');
                    console.error(data.message);
                    wishlistError.textContent=data.message;
                }
          /* const data = await response.text();
           console.log(data);*/
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    }
    const wishlistButtonUnavailable = document.getElementById("wishlist-unavailable");
    if(wishlistButtonUnavailable!=null){
        wishlistButtonUnavailable.addEventListener('click', async () => {
            const inWishlist =wishlistButtonUnavailable.getAttribute('data-in-wishlist') === 'true'; // Stato attuale
            const productId = wishlistButtonUnavailable.getAttribute('data-product-id');
            try {
                const action = inWishlist ? "remove_from_wishlist" : "add_to_wishlist";
                const response = await fetch('product_handler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: action,
                        product_id: productId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    wishlistButtonUnavailable.dataset.inWishlist = (!inWishlist).toString();
                    wishlistButtonUnavailable.textContent = (!inWishlist ? 'Stop tracking' : 'Add to wishlist to track it');
                }
                else{
                    if(data.message==="You need to be logged in"){
                        window.location.href = "login.php";
                    }
             //   alert(data.message || 'An error occurred while updating the wishlist.');
                }
            }catch(error){
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const noSizesAvailable = document.querySelector('.no-sizes-available');
    const noColorsAvailable = document.querySelector('.no-colors-available');

    if (noSizesAvailable || noColorsAvailable || noSizesAvailable) {
        const main= document.querySelector('main');
        document.createElement()
        
    }

});

updateButtonState();
