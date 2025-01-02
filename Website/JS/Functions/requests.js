

/**
 * 
 * @param {*} apiPHPfile the name of the pgp handler for this request
 * @param {*} bodyValue the value to pass to the handler in the http body
 * @param {*} bodyPrefixName the prefix containted in the $_POST[] array
 * @param {*} idParagraph the paragraph where to show the expected value of the response
 * @param {*} listenerParagraphSetting the paragraph settings, what to show if there's an error
 * @param {*} listenerJsonDataExpected the data expected to be returned.
 * @returns true if the request had success = true, false if the request had success = false.
 */
async function sendAppendedHTMLIsReady(apiPHPfile,bodyValue,bodyPrefixName,idParagraph,listenerParagraphSetting, listenerJsonDataExpected){
    const error=document.getElementById(idParagraph);
    const response = await fetch(apiPHPfile, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: bodyPrefixName + encodeURIComponent(bodyValue)
      });
      if (!response.ok) {
        throw new Error("Errore nella risposta del server.");
      }
      const data = await response.json();
      if (data.success) {
        error.textContent = data.message;
        error.style.color = "green";
        return true;
      }
      else{
        error.textContent = listenerParagraphSetting.textContent;
        error.style.color = listenerParagraphSetting.textColor;
        return false;
      }
}

//factory pattern function that remembers the values of the parameters of sendAppendHTMLIsReady
//used for example in login.js to preload the data needed for async communication with the server in
//eventListenerAppendHTML
function preloadSendAppendedHTMLIsReady(
    apiPHPfile,
    bodyValue,
    bodyPrefixName,
    idParagraph,
    listenerParagraphSetting,
    listenerJsonDataExpected
  ) {
    // Restituiamo una funzione asincrona
    return async function () {
      return sendAppendedHTMLIsReady(
        apiPHPfile,
        bodyValue,
        bodyPrefixName,
        idParagraph,
        listenerParagraphSetting,
        listenerJsonDataExpected
      );
    };
  }