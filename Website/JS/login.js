

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



//questa funzione centralizza il comportamento di tutti quegli elementi che una volta interagiti
//possono causare degli errori
//TODO-- paragraph setting should be a object to contain all the things I need to set about the paragraph.
function eventListenerFormInputWarning(idElement, idParagraph,bodyPrefixName, listenerParagraphSetting){
    const element = document.getElementById(idElement);
    const error = document.getElementById(idParagraph);
    element.addEventListener("blur",
        async function() {
                const valueOfElement = element.Value;
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
                        body: bodyPrefixName + encodeURIComponent(email)
                    });
                    if (!response.ok) {
                        throw new Error("Errore nella risposta del server.");
                    }
                    const json = await response.json();

                    if (json.exists) {
                        errorParagraph.textContent = "";
                    } else {
                        // se non esiste settiamo il paragrafo
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

//------------------------------------------------Setting all the listeners------------------------------
const inputEmail = "emailForm";
let warningParagraph = "emailFormWarning";
let bodyPrefixName = "emailinsert=";
const listenerEmailSetting = new listenerObjectSetting( "No Account associated", "red");

eventListenerFormInputWarning(inputEmail,warningParagraph,bodyPrefixName,listenerEmailSetting);

//Here we need to set up the listener to make the insert code structure
//appear.


const inputCode = "codeForm";
warningParagraph = "codeFormWarning";
bodyPrefixName = "codeinsert=";
const listenerCodeErrorSetting = new listenerObjectSetting("Incorrect Code","red");
//in this case the server may not need to query the database(the password reset code may not be modeled in the db)
// but it should havein some associative array a cell that is created when the JS code tells it to load the insert code structure

const eventListenerFormInputWarningConfigured = () =>
    eventListenerFormInputWarning(inputCode, warningParagraph, bodyPrefixName, listenerCodeErrorSetting);

const buttonForgot = "forgotButton";
let idHTMLStructure=""; // see eventListenerAppendHTML comments ^
let apiPHPfile=""; // see eventListenerAppendHTML comments ^
let additionalListeners = [eventListenerFormInputWarningConfigured];
eventListenerAppendHTML(buttonForgot,idHTMLStructure,apiPHPfile,additionalListeners);