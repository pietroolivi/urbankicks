//this script needs to be imported in the base.php file so that is accessible
//from all the other js scripts.


//this can be generalized into a utility script (for instance listenerObjectSetting I consider it a Utility script)
/**
 * This function add an eventListener to a button "idElement" and after the event fires off it appends
 * the HTML structure identified by "idHTMLStructure" then, if needed, it adds other listeners from "additionalListeners"
 * to some of the "new" HTML elements that now are visible/appended.
 *  
 * @param {*} idElement the html id of the element which listen
 * @param {*} idHTMLStructure the html id of the element to append/make visible (NEED TO DISCUSS THIS)
 * @param {*} apiPHPfile  this only if we decide to use the php to get the code
 * @param {*} additionalListeners these are additional listeners to the event
 */
function eventListenerAppendHTML(idElement,idHTMLStructure,apiPHPfile,additionalListeners){
    const button = document.getElementById(idElement);
    const idHTMLStructure = document.getElementById(idHTMLStructure);
    button.addEventListener("click",
        async function(){

            //TODO--- when we decide how to append HTML code that need to appear dynamically:
            //should we have it hidden inside the .php file of the template or should
            //we have it wrote here.
            //after the html structure is appended we use this parameter "additionalListerners"
            //to set all the listeners that depends on the click.
            additionalListeners.forEach(lis=>lis());
        });
}


function eventListenerFormInputWarning(idElement, idParagraph,bodyPrefixName, listenerParagraphSetting){
    const element = document.getElementById(idElement);
    const error = document.getElementById(idParagraph);
    element.addEventListener("blur",
        async function() {
                const valueOfElement = element.value;
                //if there's something in the input field that has been written, only then I want to execute the logic
                // (you can't have done something wrong if you have done nothing)
                if(valueOfElement){
                    try{
                        //the callback is a fetch with POST because we want to send to the server
                        //what is inside the element.
                    const response = await fetch("api-login.php", { //richiesta POST HTTP alla pagina login-api.php
                        method: "POST",
                        //nel body c'è il valore da passare e siccome la mail contiene caratteri speciali come @ è saggio usare
                        //questo metodo encodeURIComponent
                        body: bodyPrefixName + encodeURIComponent(valueOfElement)
                    });
                    if (!response.ok) {
                        throw new Error("Errore nella risposta del server.");
                    }
                    const json = await response.json();

                    if (json.exists) {
                        errorParagraph.textContent = "";
                    } else {
                        error.textContent = listenerParagraphSetting.textContent;
                        error.style.color = listenerParagraphSetting.textColor;
                    }
                    } catch (error) {
                        console.error("Errore:", error);
                        errorParagraph.textContent = "Errore, riprova";
                    }
                }
    });


    return null;
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




function eventListenerFormInputComparisonWarning(idFirstElement, idSecondElement, idParagraph, listenerParagraphSetting){
    const element1 = document.getElementById(idFirstElement);
    const element2 = document.getElementById(idSecondElement);
    const error = document.getElementById(idParagraph);
    element2.addEventListener("blur",
        async function() {
                const valueOfElement1 = element1.value;
                const valueOfElement2=element2.value;
                //if there's something in the first input field that has been written, and also something in the second input field
                //and the comparison fails, it adds the text to the error paragraph
                if(valueOfElement1 && valueOfElement2 && (valueOfElement1 !== valueOfElement2)){
                    error.textContent = listenerParagraphSetting.textContent;
                    error.style.color = listenerParagraphSetting.textColor;
                }
    });


    return null;
}


function eventListenerPasswordRules(idPassword, idParagraph, listenerParagraphSetting){
    const passwordElement= document.getElementById(idPassword);
    const error = document.getElementById(idParagraph);
    passwordElement.addEventListener("blur", 
        async function(){
            const valueOfElement=passwordElement.value;
            //regular expressions to check if the string contains letters and numbers and the length is correct
            const passwordHasLetters=/[a-zA-Z]/.test(valueOfElement);
            const emailHasNumber = /\d/.test(valueOfElement);
            const emailRightLength=(valueOfElement)=>{return (valueOfElement.length) >=8 && (valueOfElement.length<=20)};
            if(passwordHasLetters && emailHasNumber && emailRightLength)
            {
                return null;
            }
            else{
                error.textContent = listenerParagraphSetting.textContent;
                error.style.color = listenerParagraphSetting.textColor;
            }

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
                hamburgerMenu.checked = false; 
                toggleSidebar(); 
            }
    }, true);
}

//used both for when a heart of the whishlist is clicked on the home,
//and for the star swipe
function toggleImage(idImageToggled, idImageNotToggled){

}

/**
 * @param {*} classHeartImg the class of all the <img> heart elements on the products in the home
 */
function eventListenerItemWish(classCheckboxHeart){

    const allCheckboxHeart=document.getElementsByClassName(classCheckboxHeart);
    allCheckboxHeart.forEach(heart=>{heart.addEventListener("click",
        async function(){
            if(heart.checked){
                //TODO: understand where the id of the product is stored in the html.
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
            const selectedValue=element.value;
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


//the different filter names are get by the value attribute of the <input>, the category is contained in the name attribute.
//additional listener are the listener for each checkbox.
//This function can be used for the filters in the home, the PHP server should have an array of the current value of
//each category of the filters. like array={"adidas", "40-50 euro", "green"}, in this function when I POST my selection
//of the radio button to the server, the server should use that array to give me the right products.
function eventListenerCheckList(category,bodyPrefixName,additionalListener){
    const elementsRadioButton= document.getElementsByName(category);
    elementsRadioButton.array.forEach(element => {
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

}