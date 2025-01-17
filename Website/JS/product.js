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
            const cartError=document.getElementById('wishlist-cart-error');
            cartError.textContent=data.message;
          //  alert(data.message || "Failed to add product to cart.");
           /* if (selectedSize===undefined)
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
async function updateDisabledSizes(productId, color) {
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

        const data = await response.json();

        if (data.success) {
            const disabledSizes = data.disabledSizes;
            //increaseChoosen();
            sizeOptions.forEach(input => {
                input.disabled = disabledSizes.includes(input.value);
            });
            if(choosenTimesColor==1 && choosenTimesColor==1){
                Array.from(sizeOptions).filter(input => input.checked).forEach(inputcheck=>inputcheck.checked=false);
                choosenTimesSize=0;
            }
            choosenTimesColor++;
            if(choosenTimesSize>0 && choosenTimesColor>0){
                colorOptions.forEach(input => input.disabled=false);

            }
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
        submitReviewButton.getAttribute('data-product-id'),
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
        if(data.success){
            console.log("review aggiunta con successo");
        }
        if(!data.success){
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

        const data = await response.json();

       if (data.success) {
            const disabledColors = data.disabledColors;
            colorOptions.forEach(input => {
                input.disabled = disabledColors.includes(input.value)
            });
            if(choosenTimesColor==1 && choosenTimesColor==1){
                Array.from(colorOptions).filter(input => input.checked).forEach(inputcheck=>inputcheck.checked=false);
                choosenTimesColor=0;
            }
            choosenTimesSize++;
            if(choosenTimesColor>0 && choosenTimesSize>0){
                sizeOptions.forEach(input => input.disabled=false);

            }

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
                p.textContent='';
            } else {
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
});



updateButtonState();
