//------------------------------------------------Setting all the listeners----------------------------------------------------------
//____________Listener For the "email already in use" attached to the email input_______________________
let inputEmail = "emailForm";
let paragraphWarning = "emailFormWarning";
let apiPHPfile="register_handler";
let bodyPrefixName = "emailinsert="; //PHP: given this in the $_POST return me a boolean if it exist in the emails
const listenerEmailSetting = new listenerObjectSetting( "Email already used", "red");
eventListenerFormInputWarning(inputEmail,paragraphWarning,apiPHPfile,bodyPrefixName,listenerEmailSetting);

//____________Listener For the "passwords don't match" attached to the password confirmation_______________________
const inputPassword = "passForm";
const inputPassword2 = "passForm2"; 
paragraphWarning = "passDontMatch";
const listenerPassMatch = new listenerObjectSetting( "Passwords don't match", "red");
eventListenerFormInputComparisonWarning(inputPassword,inputPassword2,paragraphWarning,listenerPassMatch);

//____________Listener For the "emails don't match" attached to the email confirmation_______________________
inputEmail = "emailForm";
const inputEmail2 = "emailForm2";
paragraphWarning="emailDontMatch";
const listenerEmailMatch = new listenerObjectSetting( "Emails don't match", "red");
eventListenerFormInputComparisonWarning(inputEmail,inputEmail2,paragraphWarning,listenerEmailMatch);

//____________Listener For the privacy policy not checked attached to the button Register_______________________
const buttonRegister= "registerButton";
const checkPrivacyPolicy = "privacyCheck";
paragraphWarning = "checkWarning";
const listenerCheckSetting = new listenerObjectSetting( "You must check the box to proceed", "red");
eventListenerButtonNotCheckedWarning(buttonRegister,checkPrivacyPolicy, paragraphWarning,listenerCheckSetting);