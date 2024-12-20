//------------------------------------------------Setting all the listeners----------------------------------------------------------
//____________Listener For the "email already in use" attached to the email input_______________________
const inputEmail = "emailForm";
let paragraphWarning = "emailFormWarning";
let bodyPrefixName = "emailinsert=";
const listenerEmailSetting = new listenerObjectSetting( "Email already used", "red");

eventListenerFormInputWarning(inputEmail,paragraphWarning,bodyPrefixName,listenerEmailSetting);

//____________Listener For the "passwords don't match" attached to the password confirmation_______________________
const inputPassword = "passForm";
const inputPassword2 = "passForm2" 
const listenerPassSetting = new listenerObjectSetting( "Passwords don't match", "red");
eventListenerFormInputComparisonWarning(inputPassword,inputPassword2,paragraphWarning,listenerEmailSetting);


//____________Listener For the privacy policy not checked attached to the button Register_______________________
const buttonRegister= "registerButton"
const checkPrivacyPolicy = "privacyCheck";
paragraphWarning = "checkWarning";
const listenerCheckSetting = new listenerObjectSetting( "You must check the box to proceed", "red");
eventListenerButtonNotCheckedWarning(buttonRegister,checkPrivacyPolicy, paragraphWarning,listenerEmailSetting);