
//------------------------------------------------Setting all the listeners----------------------------------------------------------
//____________Listener For the "no account associated" attached to the email input_______________________
const inputEmail = "email-login"; //from login.html
let paragraphWarning = "email-error-login"; //where it is?
let apiPHPfile="login_handler.php";
let bodyPrefixName = "emailinsert";// PHP: given this in the $_POST return me a boolean if it exist in the emails
let typeOfEvent="blur";
const listenerJsonEmailWarning = new ListenerJsonDataExpected("exists", true);
const listenerEmailSetting = new listenerObjectSetting( "No Account associated", "red");

eventListenerFormLoginWarning(inputEmail,paragraphWarning,typeOfEvent,apiPHPfile,bodyPrefixName,
    listenerEmailSetting,listenerJsonEmailWarning);
/*
//____________Listener For the "no account associated" attached to the password input_____________________
const inputPassword = "password-login"; //from login.html
paragraphWarning = "pswd-error-login"; //where it is?
bodyPrefixName = "passwordinsert";// PHP: given this in the $_POST return me a boolean if it exist in the emails
typeOfEvent="blur";
const listenerJsonPassWarning = new ListenerJsonDataExpected("exists", true);
const listenerPasswordSetting = new listenerObjectSetting( "No Account associated", "red");

eventListenerFormLoginWarning(inputEmail,paragraphWarning,typeOfEvent,apiPHPfile,bodyPrefixName,
    listenerEmailSetting,listenerJsonEmailWarning);
*/


/*************************** */
/*NOT NEEDED FROM JAVASCRIPT**
/*********************** */
/*
//____________Listener for the incorrect code in the paragraph attached to the recover code input_______________________
const inputCode = "codeForm"; // not needed, html should already send the code with the submit, need to fix the function I use to
                            //not send the code wrote by the user
const buttonVerify="submit-code"; //from password-forgotten2.html
paragraphWarning = "codeFormWarning";
bodyPrefixName = "reset_code = ";
typeOfEvent="click";
const listenerJsonCodeWarning = new ListenerJsonDataExpected("message", "Password updated successfully");
const listenerCodeErrorSetting = new listenerObjectSetting("Incorrect Code","red");
//in this case the server may not need to query the database(the password reset code may not be modeled in the db)
// but it should havein some associative array a cell that is created when the JS code tells it to load the insert code structure
const preloadEventListenerFormInputWarning = () =>{  //TODO: valutare come gestire il feedback di codice inserito correttamente
                                                    //potrebbe aver senso non mostrarlo per non chiara specifica dai mockup.
    eventListenerFormInputButtonWarning(inputCode,buttonVerify, paragraphWarning,typeOfEvent, apiPHPfile, bodyPrefixName,
        listenerCodeErrorSetting,listenerJsonCodeWarning); 
}

//__________Async function added to the recover password button to make the server generate a reset code.
const listenerJsonCodeReceivedErrorSetting = new ListenerJsonDataExpected("Error while sending the code","red");
const optionalAsyncCallback = preloadSendAppendedHTMLIsReady(
            apiPHPfile,
            true,
            "generate_reset_code = ",
            paragraphWarning,
            listenerJsonCodeSetting,
            null
          );

//____________Listener for the recover password button________________________________________________

//this listener has as the parameter additionalListener the array defined on the previous istener definition
//since the incorrect code listener is added only when the incorrect code html and logic is enabled in thje document. 
//the idHTMLStructure It's hidden until forgot button is clicked.
const buttonForgot = "forgot-password"; //from login.html
let idHTMLStructure="pswd-recovery-mail"; // from password_forgotten.html. see eventListenerAppendHTML comments in JS/Functions/listeners.js
let additionalListeners = [preloadEventListenerFormInputWarning];
eventListenerAppendHTML(buttonForgot,idHTMLStructure,additionalListeners,optionalAsyncCallback);
*/

/*
//____________Listener For the "passwords don't match" attached to the new password confirmation_______________________
inputPassword = "password"; //from register2.html
const inputPassword2 = "confirm-password"; //from register2.html
paragraphWarning = "passWarning";
const listenerPassMatch = new listenerObjectSetting( "Passwords don't match", "red");
eventListenerFormInputComparisonWarning(inputPassword,inputPassword2,paragraphWarning,listenerPassMatch);*/