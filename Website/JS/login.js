

//this can be generalized into a utility script (for instance listenerObjectSetting I consider it a Utility script)
function evenListenerAppendHTML(idElement,idHTMLStructure,apiPHPfile){
    const element = document.getElementById(idElement);
    const idHTMLStructure =document.getElementById(idHTMLStructure);
    element.addEventListener("click",
        async function(){
            const response= await fetch(apiPHPfile)
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
const warningParagraph = "emailFormWarning";
const emailInput = "emailForm";
const bodyPrefixName = "emailinsert=";
const listenerEmailSetting = new listenerObjectSetting( "No Account associated", "red");

eventListenerFormInputWarning(warningParagraph,emailInput,bodyPrefixName,listenerEmailSetting);

//Here we need to set up the listener to make the insert code structure
//appear.



warningParagraph = "codeFormError";
emailInput = "codeFormError";
bodyPrefixName = "codeinsert=";
const listenerCodeErrorSetting = new listenerObjectSetting("Incorrect Code","red");
//in this case the server may not need to query the database(the password reset code may not be modeled in the db)
// but it should havein some associative array a cell that is created when the JS code tells it to load the insert code structure
eventListenerFormInputWarning(warningParagraph,emailInput,bodyPrefixName,listenerCodeErrorSetting);