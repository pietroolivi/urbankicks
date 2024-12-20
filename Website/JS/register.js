//------------------------------------------------Setting all the listeners----------------------------------------------------------
//____________Listener For the "no account associated" attached to the email input_______________________
const inputEmail = "emailForm";
let paragraphWarning = "emailFormWarning";
let bodyPrefixName = "emailinsert=";
const listenerEmailSetting = new listenerObjectSetting( "Email already used", "red");

eventListenerFormInputWarning(inputEmail,paragraphWarning,bodyPrefixName,listenerEmailSetting);

const inputPassword = "passForm";
const inputPassword2 = "passForm2" 
const listenerPassSetting = new listenerObjectSetting( "Passwords don't match", "red");
eventListenerFormInputComparisonWarning(inputPassword,inputPassword2,paragraphWarning,listenerEmailSetting);