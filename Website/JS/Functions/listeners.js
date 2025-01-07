//this script needs to be imported in the base.php file so that is accessible
//from all the other js scripts.


/**
 * This function adds an eventListener to handle multi-step form navigation
 * @param {string} idElement - Button ID that triggers the transition
 * @param {string} idHTMLStructure - ID of section to show
 * @param {Function} [optionalAsyncCallback] - Optional async callback
 * @param {Array} [additionalListeners] - Array of additional listener functions
 * @param {string} [idPrevHTMLStructure] - ID of section to hide
 */
function eventListenerAppendHTML(idElement,idHTMLStructure,optionalAsyncCallback,additionalListeners,idPrevHTMLStructure){
    const button = document.getElementById(idElement);
    const HTMLStructure = document.getElementById(idHTMLStructure);
    button.addEventListener("click", async function(event) {
        event.preventDefault();

        // Handle previous section
        if (idPrevHTMLStructure) {
            const prevSection = document.getElementById(idPrevHTMLStructure);
            if (prevSection) {
                prevSection.style.display = 'none';
            }
        }

        // Show current section
        HTMLStructure.style.display = 'block';
        HTMLStructure.classList.remove("hidden");

        // Handle async callback if provided
        if (optionalAsyncCallback && typeof optionalAsyncCallback === 'function') {
            try {
                await optionalAsyncCallback();
            } catch (error) {
                console.error('Async callback error:', error);
            }
        }

        // Execute additional listeners if provided
        if (Array.isArray(additionalListeners)) {
            additionalListeners.forEach(listener => {
                if (typeof listener === 'function') {
                    try {
                        listener();
                    } catch (error) {
                        console.error('Listener execution error:', error);
                    }
                }
            });
        }
    });
}

/**
 * 
 * @param {*} idElement the html element that both fires the event and has the value to check
 * @param {*} idParagraph the paragraph where to show the result of the check to the user
 * @param {*} typeOfEvent the type of the event to put in the addEventListener like: "blur", "click"
 * @param {*} apiPHPfile the php file that is used as server for this check request
 * @param {*} bodyPrefixName the field that will be passed in the $_POST super global variable, example: "emailinsert"
 * @param {*} listenerParagraphSetting how the paragraph content and style is set
 * @param {*} listenerJsonDataExpected the expected values for the json response
 * @returns 
 */
function eventListenerFormLoginWarning(idElement, idParagraph, typeOfEvent, apiPHPfile, bodyPrefixName, 
    listenerParagraphSetting, listenerJsonDataExpected){

    const element = document.getElementById(idElement);
    const error = document.getElementById(idParagraph);
    element.addEventListener(typeOfEvent,
        async function() {
                const valueOfElement = element.value;
                //if there's something in the input field that has been written, only then I want to execute the logic
                // (you can't have done something wrong if you have done nothing)
                if(valueOfElement.length!==0){
                    const bodyMessage=`${bodyPrefixName}=${encodeURIComponent(valueOfElement)}&check_email_only=true`;
                    try{
                        //the callback is a fetch with POST because we want to send to the server
                        //what is inside the element.
                        const response = await fetch(apiPHPfile, { 
                            method: "POST",
                            //parameter to use to have the content of this message inside $_POST[bodyPrefixName]
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            //nel body c'è il valore da passare e siccome la mail contiene caratteri speciali come @ è saggio usare
                            //questo metodo encodeURIComponent
                            body: bodyMessage
                        });
                        if (!response.ok) {
                            throw new Error("Errore nella risposta del server.");
                        }
                        const json = await response.json();

                        if (json.exists) {
                            error.textContent = listenerJsonDataExpected.jsonExpectedValue;
                            error.style.color = "green";
                        } else {
                            error.style.display = 'block';
                            error.textContent = listenerParagraphSetting.textContent;
                            error.style.color = listenerParagraphSetting.textColor;
                        }
                    } catch (errorEx) {
                        console.error("Errore:", errorEx);
                    }
                }
    });
    return null;
}

function eventListenerFormRegisterWarning(idElement, idParagraph, typeOfEvent, apiPHPfile, bodyPrefixName, 
    listenerParagraphSetting, listenerJsonDataExpected){
        
    const element = document.getElementById(idElement);
    const error = document.getElementById(idParagraph);
    element.addEventListener(typeOfEvent,
        async function() {
                console.log("BLURBLURBLURBLURBLURBLURBULRBRLUBRLU");
                const valueOfElement = element.value;
                //if there's something in the input field that has been written, only then I want to execute the logic
                // (you can't have done something wrong if you have done nothing)
                if(valueOfElement.length!==0){
                    const bodyMessage = `${bodyPrefixName}=${encodeURIComponent(valueOfElement)}&check_email_only=true`;
                    try{
                        //the callback is a fetch with POST because we want to send to the server
                        //what is inside the element.
                        const response = await fetch(apiPHPfile, { 
                            method: "POST",
                            //parameter to use to have the content of this message inside $_POST[bodyPrefixName]
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            //nel body c'è il valore da passare e siccome la mail contiene caratteri speciali come @ è saggio usare
                            //questo metodo encodeURIComponent
                            body: bodyMessage
                        });
                        if (!response.ok) {
                            throw new Error("Errore nella risposta del server.");
                        }
                        const json = await response.json();

                        if (json.success) {
                            document.getElementById("register-button").disabled = false;
                            //error.textContent = listenerJsonDataExpected.jsonExpectedValue;
                            //error.style.color = "green";
                        } else {
                            document.getElementById("register-button").disabled = true;
                            error.style.display = 'block';
                            error.textContent = json.message;
                            error.style.color = listenerParagraphSetting.textColor;
                        }
                    } catch (errorEx) {
                        console.error("Errore:", errorEx);
                    }
                }
                else{
                    error.innerHTML = "STRINGA VUOTAAAAAAAAAA",
                    error.style.display = 'block';
                    error.style.color = listenerParagraphSetting.textColor;
                }
    });
    return null;
}

function eventListenerRegisterButton(idSubmit,idEmail,idName,idLastName,idPassword,typeOfEvent,apiPHPfile){

    const button=document.getElementById(idSubmit);
    const email=document.getElementById(idEmail);
    const name=document.getElementById(idName);
    const lastName=document.getElementById(idLastName);
    const password=document.getElementById(idPassword);

    button.addEventListener(typeOfEvent,
        async function(){
            const valueOfEmail = email.value;
            const valueOfName = name.value;
            const valueOfLastName = lastName.value;
            const valuePassword = password.value;
            const bodyMessage=`${bodyPrefixName}=${encodeURIComponent(valueOfElement)}
                                &${"first-name"}=${encodeURIComponent(valueOfName)}
                                &${"last-name"}=${encodeURIComponent(valueOfLastName)}
                                &${"password"}=${encodeURIComponent(valueOfPassword)}`;
            try{
                const response = await fetch(apiPHPfile,{
                    method: "POST",
                    //parameter to use to have the content of this message inside $_POST[bodyPrefixName]
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    //nel body c'è il valore da passare e siccome la mail contiene caratteri speciali come @ è saggio usare
                    //questo metodo encodeURIComponent
                    body: bodyMessage
                })
                const json = await response.json();

                if (json.success) 
                    console.log("successo registrazione");
            }
            catch(errorEx){
                console.error("Errore:", errorEx);
            }
        }

    )
}

//the same logic of eventListenerFormInputWarning but the event and the value to be checked
//are into two different HTML element (used for example to check a input field of a form, after the pressing of a button.)
function eventListenerFormInputButtonWarning(idElement, idForward, idParagraph, typeOfEvent, apiPHPfile, bodyPrefixName, 
    listenerParagraphSetting, listenerJsonDataExpected){
    const element = document.getElementById(idElement);
    const elementForward= document.getElementById(idForward);
    const error = document.getElementById(idParagraph);
    elementForward.addEventListener(typeOfEvent,
        async function() {
                const valueOfElement = element.value;
                //if there's something in the input field that has been written, only then I want to execute the logic
                // (you can't have done something wrong if you have done nothing)
                if(valueOfElement){
                    try{
                        //the callback is a fetch with POST because we want to send to the server
                        //what is inside the element.
                        const bodyMessage=`${bodyPrefixName}=${encodeURIComponent(valueOfElement)}&${"check_email_only"}=${encodeURIComponent("true")}`;
                    const response = await fetch(apiPHPfile, { //richiesta POST HTTP alla pagina login-api.php
                        method: "POST",
                        //parameter to use to have the content of this message inside $_POST[bodyPrefixName]
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        //nel body c'è il valore da passare e siccome la mail contiene caratteri speciali come @ è saggio usare
                        //questo metodo encodeURIComponent
                        body: bodyMessage
                    });
                    if (!response.ok) {
                        throw new Error("Errore nella risposta del server.");
                    }
                    const json = await response.json();

                
                    if (json.exists) {
                        error.textContent = listenerJsonDataExpected.jsonExpectedValue;
                        error.style.color = "green";
                    } else {
                        error.style.display = 'block';
                        error.textContent = listenerParagraphSetting.textContent;
                        error.style.color = listenerParagraphSetting.textColor;
                    }
                    } catch (errorEx) {
                        console.error("Errore:", errorEx);
                    }
                }
    });
}


//this is used for elements that when clicked give warning if a checkbox is not checked
function eventListenerButtonNotCheckedWarning(idElement, idCheckbox,idParagraph, listenerParagraphSetting){
    const button = document.getElementById(idElement);
    const checkbox=document.getElementById(idCheckbox);
    const error = document.getElementById(idParagraph);
    button.addEventListener("click",
        async function() {
                const valueOfElement = checkbox.checked;
                //if there's something in the input field that has been written, only then I want to execute the logic
                // (you can't have done something wrong if you have done nothing)
                if(!valueOfElement){
                    error.textContent = listenerParagraphSetting.textContent;
                    error.style.color = listenerParagraphSetting.textColor;
                }
    });
    return null;
}




function eventListenerFormInputComparisonWarning(input1Id, input2Id, paragraphWarningId, listenerSetting) {
    const input1 = document.getElementById(input1Id);
    const input2 = document.getElementById(input2Id);
    const submitButton = document.getElementById('submit-2');
    
    function checkMatch() {
        if (input1.value === '' || input2.value === '') {
            submitButton.disabled = true;
            return;
        }
        
        if (input1.value === input2.value) {
            document.querySelectorAll('.pswd-format-error').forEach(p => {
                p.style.color = 'green';
                p.style.display = 'none';
            });
            submitButton.disabled = false;
        } else {
            document.querySelectorAll('.pswd-format-error').forEach(p => {
                p.style.color = listenerSetting.color;
                p.style.display = 'block';
            });
            submitButton.disabled = true;
        }
    }

    input1.addEventListener('input', checkMatch);
    input2.addEventListener('input', checkMatch);
}


function eventListenerPasswordRules(idPassword,warningListClass,listenerParagraphSetting){
    const passwordElement= document.getElementById(idPassword);
    const warningElements = document.getElementsByClassName(warningListClass);

    const validatePassword = (value) => {
        const rules = {
            length: value.length >= 8 && value.length <= 20,
            letters: /[a-zA-Z]/.test(value),
            numbers: /\d/.test(value),
            special: /[!"#$%&'()*+,-./:;<=>?]/.test(value)
        };

        return rules;
    };

    passwordElement.addEventListener("input", function() {
        const valueOfElement = passwordElement.value;
        const validation = validatePassword(valueOfElement);
        
        // Update warning messages
        Array.from(warningElements).forEach((element, index) => {
            if (index === 0 && !validation.length ||
                index === 1 && !(validation.letters && validation.numbers) ||
                index === 2 && !validation.special) {
                element.style.display = "block";
                element.style.color = "red";
            } else {
                element.style.display = "none";
                element.style.color = "green";
            }
        });
    });
}


function eventListenerSideBarFocus(){
    //document body
    const body = document.body;
    // this is the reference to the checkbox
    const hamburgerMenu = document.querySelector(".hamburger-menu input");
    //reference to the sidebar 
    const sidebar = document.querySelector(".sidebar");
    
    //if we want to remove the scroll from the body through javascript, then this function can be used
    const toggleSidebar = () => {
        if (hamburgerMenu.checked) {
            
            //you need to add a no-scroll class in the style.css:
            /*body.no-scroll {
                overflow: hidden;
            }*/

           //classlist is a property to get the collection of classes of a DOM element.
             body.classList.add("no-scroll");
        //if the checkbox of the hamburger menu is unchecked, it will remove the class
        } else {
            body.classList.remove("no-scroll");
        }
    };
    //when the checkbox change
    hamburgerMenu.addEventListener("change", toggleSidebar);

    //this was done using an event that uses the capturing phase, since
    //document has no parents it blocks the propagation immediately.
    //look into capturing and bubbling phases for events.
    document.addEventListener("click", 
        async function(event){
            if (hamburgerMenu.checked && !sidebar.contains(event.target)){
                event.stopPropagation(); 
                event.preventDefault();
                //behavior to consider 
                //hamburgerMenu.checked = false; 
                toggleSidebar(); 
            }
    }, true);
}

//used both for when a heart of the whishlist is clicked on the home,
//and for the star swipe
function toggleImage(idImageToggled, idImageNotToggled){

}

/**
 * id article is in the id of the <article>
 * @param {*} classHeartImg the class of all the <img> heart elements on the products in the home
 * @param {*} apiPHPfile the php file of the home. the PHP server should check if it was already in the wish list, if so, removes it.
 */
function eventListenerItemWish(classCheckboxHeart,apiPHPfile){

    const allCheckboxHeart=document.getElementsByClassName(classCheckboxHeart);
    allCheckboxHeart.forEach(heart=>{heart.addEventListener("click",
        async function(){
            try{
            //it gets the closest parent of the type article of the heart and get the id.
                const idProdotto= heart.closest("article").id;
                if(heart.checked){
                    const response= await fetch(apiPHPfile,{
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: bodyPrefixName + encodeURIComponent(id)
                    });

                    if (!response.ok) {
                        throw new Error("Errore nella risposta del server.");
                    }

                    const json = await response.json();

                    if (!json.added) {
                        //does this also disable the graphic effect? -> does the heart becomes empty?
                        heart.checked=false;
                    }
                }
            }catch(error){
                console.error(error);
            }
        }
    )});
}

/**
 * 
 * @param {*} idScrollElement element I need to scroll to
 * @param {*} idButton the button that triggers this listener
 * @param {*} idElement the element that is like the <select> having all the choices
 * @param {*} idParagraph the element that is the paragraph of the warning
 */
function eventListenerScrollToElementWarningEmpty(idScrollElement,idButton,idElement,idParagraph,listenerParagraphSetting) {

    const button=document.getElementById(idButton);

    button.addEventListener("click",
        ()=>{
            const scrollElement = document.getElementById(idScrollElement);
            const paragraph = document.getElementById(idParagraph);
            const element = document.getElementById(idElement);
           // const selectedValue=element.value;
            if(element.value===""){
                //if we want to scroll to the element while the user cannot scroll it's again useful to have a class like this:
                /*body.no-scroll {
                    overflow: hidden;
                }*/
                const body = document.body;
                body.classList.add("no-scroll");
                //this simulates the length of the animation (0.5 sec) at the end it will remove the class that disables the scroll.
                setTimeout(() => {
                    body.classList.remove("no-scroll");
                  }, 500);
                //it should scroll the element into view, behavior smooth for a smmoth animation, and block:center to have it centered.
                scrollElement.scrollIntoView({
                    behavior: 'smooth', 
                    block: 'center',   
                });
                paragraph.textContent=listenerParagraphSetting.textContent;
                paragraph.style.textColor=listenerParagraphSetting.textColor;

            }

        });
}



//there will be less than 50 products, I can set the filters of a single category from this function.
//I consider the products as inside an <article> and they have a class.

//the different filter names are get by the value attribute of the <input>, the category is contained in the name attribute.
//additional listener are the listener for each checkbox.
//I click on a checkbox of a category like designers and the listenerObjectFilters is updated.
function eventListenerCheckListFilter(category){
    const elementsCheckButton= document.getElementsByName(category);
    elementsCheckButton.array.forEach(element => {
        element.addEventListener("click",()=>{
           
            if(element.checked){
                objectFilters.addCategoryFilterToCategoryName(element.name,element.value);
            }
            else{
                objectFilters.removeCategoryFilterToCategoryName(element.name,element.value);
            }
        })
    });

}


//same logic of updating the objectFilters as eventListenerCheckListFilter, only that here
//we have a slider. is this too hard?
function eventListenerPriceFilter(){
    //TODO............
}


//I need to know how the article data is passed to me. I suppose since asking for a query for each product's
//size, color, designers is too computational heavy, in the HTML we have data attributes for them
function eventListenerApplyFilters(){
    const applyFiltersButton=document.getElementsById("applyFilterButton");
    
    applyFiltersButton.addEventListener("click",()=>{
        const allArticles=document.querySelectorAll("article");
        allArticles.forEach(element => {
            //non si può più fare con dataset bisogna avere un oggetto json passato da php
            //con tutte le informazioni sui prodotti caricati nella home, designer, le taglie possibili,
            //colori possibili. è impossibile usare data-size1, data-size2, data-size3 nel tag di ciascun prodotto.
            const size = element.dataset.size; 
            const color = element.dataset.color;
            const designers = element.dataset.designers;
            //TODO(?) price filter.

            if (objectFilters.areFilterEmpty || 
                (objectFilters.designers.includes() !== sizeFilter || color !== colorFilter) ) {
                //hides them from display
                element.style.display = 'none';
              } else {
                //return to default display
                element.style.display = '';
              }
        });
    })
}


//THIS IS NOT NEEDED LIKE THIS.
//for the category filter that is exclusive, example: MAN > Sandals or WOMAN > Sliders. only one of each of these filter
//can exists at the same time
/**
 * 
 * @param {*} idNavigationParagraph this is used for setting the text of the navigation, like to show that when MEN > Sneakers is
 *                            clicked, we are in Home > Products > Men > Sneakers 
 */
/*function eventListenerExclusiveCategoryFilter(idNavigationParagraph){
    const targets = document.getElementsByName("target");
    const shoeTypes = document.getElementsByName("shoeType");
    shoeTypes.forEach(shoeType => {
        shoeType.addEventListener("click",()=>{
            objectExclusiveCategoryFilter.shoeType=shoeType;
            if(objectExclusiveCategoryFilter.target)
                objectExclusiveCategoryFilter.target="man";

            //TODO: usa objectExclusiveCategory per filtrare solo i prodotti in allArticles che lo rispettano.

            const allArticles=document.querySelectorAll("article");
            allArticles.forEach(element => {/*do filter*/ /*});
           if (selectedNavigation) {
                const selectedTarget = selectedNavigation.value;
                idNavigationParagraph.textContent="Home > Products > "+selectedTarget+" > "+shoeType.value;
            }
        })
        
    });
}*/


//the different filter names are get by the value attribute of the <input>, the category is contained in the name attribute.
//additional listener are the listener for each checkbox.
//This function can be used for the filters in the home, the PHP server should have an array of the current value of
//each category of the filters. like array={arraymarca={"adidas","nike"}, "40-50 euro", "green"}, in this function when I POST my selection
//of the radio button to the server, the server should use that array to give me the right products.
/*function eventListenerCheckList(category,bodyPrefixName,additionalListener){
    const elementsCheckButton= document.getElementsByName(category);
    elementsCheckButton.array.forEach(element => {
        element.addEventListener("click",async function(){
            if (radio.checked) {
                try{
                    const response= await fetch("api-home",{
                        method:"POST",
                        body: bodyPrefixName + encodeURIComponent(valueOfElement)

                    }
                    );
                    if(!response.ok){
                        throw new Error("Errore nella risposta del server.");
                    }
                    const json = await response.json();

                    if (json.exists) {
                    }
                }
                catch(error){
                    console.error("Errore:", error);
                }
                //the server sends the json with the value of parameters to automatically append
                //some HTML code.
            }
        })
    });

}*/

function setupBackButtons() {
    // Back button from section 2 to 1
    document.querySelector('#section-register2 .back-btn').addEventListener('click', () => {
        // Show section 1
        document.getElementById('section-register2').style.display = 'none';
        document.getElementById('section-register1').style.display = 'block';

        // Restore section 1 data
        document.getElementById('first-name').value = sessionStorage.getItem('firstname') || '';
        document.getElementById('last-name').value = sessionStorage.getItem('lastname') || '';
        document.getElementById('email-register').value = sessionStorage.getItem('email') || '';
        
        history.pushState({}, '', '?step=1');
    });

    // Back button from section 3 to 2
    document.querySelector('#section-register3 .back-btn').addEventListener('click', () => {
        // Show section 2
        document.getElementById('section-register3').style.display = 'none';
        document.getElementById('section-register2').style.display = 'block';
        
        // Restore section 2 data
        document.getElementById('password-register').value = sessionStorage.getItem('password') || '';
        document.getElementById('phone-register').value = sessionStorage.getItem('phone') || '';
        
        history.pushState({}, '', '?step=2');
    });
}

// Initialize back buttons
document.addEventListener('DOMContentLoaded', setupBackButtons);