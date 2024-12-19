

//quando il dom è completamente caricato
document.addEventListener("DOMContentLoaded",function () {
    //ottiene l'elemento html emailForm
    const emailInput = document.getElementById("emailForm");
    //ottiene l'elemento html emailFormError che immagino sarà un paragrafo
    const errorParagraph = document.getElementById("emailFormError");
    //blur è il tipo di evento da ascoltare quando si vuole ottenere
    //la perdita del focus, la callback di questo evento è una funzione di callback (la funzione asincrona)
    emailInput.addEventListener("blur", async function () {
        const email = emailInput.value;
        if (email) { //lo voglio eseguire solo se l'email non è vuota perchè in js se una stringa è vuota restituisce false
            try {
                const response = await fetch("api-login.php", { //richiesta POST HTTP alla pagina login-api.php
                    method: "POST",
                    //nel body c'è il valore da passare e siccome la mail contiene caratteri speciali come @ è saggio usare
                    //questo metodo encodeURIComponent
                    body: "emailinsert=" + encodeURIComponent(email)
                });
                
                if (!response.ok) {
                    throw new Error("Errore nella risposta del server.");
                }
                //ottiene la risposta in formato json
                const json = await response.json();

                //un campo che possiamo pensare il file api-login inserisca dentro il risultato che
                //viene "encodato" in json "exists" che è un booleano che indica se la query fatta sul database
                //ha restituito true.
                if (json.exists) {
                    errorParagraph.textContent = "";
                } else {
                    // se non esiste settiamo il paragrafo
                    errorParagraph.textContent = "Questa email non è presente";
                    errorParagraph.style.color = "red";
                    errorParagraph.textContent = "";
                }
            } catch (error) {
                console.error("Errore:", error);
                errorParagraph.textContent = "Errore, riprova";
            }
        }
    });
});

//questa funzione centralizza il comportamento di tutti quegli elementi che una volta interagiti
//possono causare degli errori
//TODO-- paragraph setting should be a struct or object to contain all the things I need to set about the paragraph.
function eventListenerFormInputWarning(idElement, idParagraph,bodyPrefixName, paragraphsetting){
    const element = document.getElementById(idElement);
    const error = document.getElementById(idParagraph);
    element.addEventListener("blur",
        async function() {
                const valueOfElement = element.Value;
                //if there's something in the input field that has been written, only then I want to execute the logic
                // (you can't have done something wrong if you have done nothing)
                if(valueOfElement){
                    try{
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
                        error.textContent = "Questa email non è presente";
                        error.style.color = "red";
                        error.textContent = "";
                    }
                    } catch (error) {
                        console.error("Errore:", error);
                        errorParagraph.textContent = "Errore, riprova";
                    }
                }
    });


    return null;
}