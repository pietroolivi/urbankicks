
//------------------------------------------------Setting all the listeners----------------------------------------------------------
//____________Listener For the "no account associated" attached to the email input_______________________
const inputEmail = "emailForm";
let paragraphWarning = "emailFormWarning";
let bodyPrefixName = "emailinsert=";// PHP: given this in the $_POST return me a boolean if it exist in the emails
const listenerEmailSetting = new listenerObjectSetting( "No Account associated", "red");

eventListenerFormInputWarning(inputEmail,paragraphWarning,bodyPrefixName,listenerEmailSetting);



//____________Listener for the incorrect code attached to the recover code input_______________________
const inputCode = "codeForm";
paragraphWarning = "codeFormWarning";
bodyPrefixName = "codeinsert="; // PHP: we need to discuss this
const listenerCodeErrorSetting = new listenerObjectSetting("Incorrect Code","red");
//in this case the server may not need to query the database(the password reset code may not be modeled in the db)
// but it should havein some associative array a cell that is created when the JS code tells it to load the insert code structure
const eventListenerFormInputWarningConfigured = () =>
    eventListenerFormInputWarning(inputCode, paragraphWarning, bodyPrefixName, listenerCodeErrorSetting);


//____________Listener for the recover password button________________________________________________

//this listener has as the parameter additionalListener the array defined on the previous istener definition
//since the incorrect code listener is added only when the incorrect code html and logic is enabled in thje document. 
const buttonForgot = "forgotButton";
let idHTMLStructure=""; // see eventListenerAppendHTML comments in JS/Functions/listeners.js
let apiPHPfile=""; // PHP: see eventListenerAppendHTML comments in JS/Functions/listeners.js
let additionalListeners = [eventListenerFormInputWarningConfigured];
eventListenerAppendHTML(buttonForgot,idHTMLStructure,apiPHPfile,additionalListeners);