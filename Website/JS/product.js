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
        alert("Please select both a size and a color before adding to cart.");
        return;
    }


    const userEmail = "user@example.com"; 

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
                email: userEmail,
                quantity: 1 
            })
        });

       // const data = await response.json();
       const rawText = await response.text();
       console.log(rawText);
      /*  if (data.success) {
            alert(data.message || "Product added to cart successfully!");
        } else {
            alert(data.message || "Failed to add product to cart.");
        }*/
    } catch (error) {
        console.error("Errore nella richiesta:", error);
        alert("An error occurred while adding the product to cart.");
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
    const sizeSelected = Array.from(sizeOptions).some(input => input.checked);
    const colorSelected = Array.from(colorOptions).some(input => input.checked);

    //disable or enable the cart button
    addToCartButton.disabled = !(sizeSelected && colorSelected);
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

updateButtonState();
