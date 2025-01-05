//------------------------------------------------Setting all the listeners----------------------------------------------------------
//____________Listener For the "email already in use" attached to the email input_______________________
let inputEmail = "email-register";
let paragraphWarning = "email-register-error"; //where it is?
let apiPHPfile="register_handler.php";
let typeOfEvent="blur";
let bodyPrefixName = "emailinsert"; //PHP: given this in the $_POST return me a boolean if it exist in the emails
const listenerEmailSetting = new listenerObjectSetting( "Email already in use", "red");
eventListenerFormRegisterWarning(inputEmail,paragraphWarning,typeOfEvent,apiPHPfile,bodyPrefixName,
    listenerEmailSetting);

//___________Listener for the passwords rules_______________________________________________________
let inputPassword="password-register";
const warningListClass="pswd-format-error";
const listenerPassSetting = new listenerCheckSetting(  [
                                                            "8 to 20 characters long",
                                                            "Contains both letters and numbers",
                                                            "One symbols !\"#$%&'()*+,-./:;<=>?"
                                                        ], 
                                                        "red");
eventListenerPasswordRules(inputPassword,warningListClass,listenerPassSetting);


//____________Listener For the "passwords don't match" attached to the password confirmation_______________________
inputPassword = "password-register"; //from register2.html
const inputPassword2 = "confirm-password-register"; //from register2.html
paragraphWarning = "pswd-format-error";
const listenerPassMatch = new listenerObjectSetting( "Passwords don't match", "red");
eventListenerFormInputComparisonWarning(inputPassword,inputPassword2,paragraphWarning,listenerPassMatch);

//____________Listener For the privacy policy not checked attached to the button Register_______________________
const buttonRegister= "register-button"; //from register3.html
const checkPrivacyPolicy = "terms-and-privacy"; //from register3.html
paragraphWarning = "checkWarning"; 
const listenerCheckSetting = new listenerObjectSetting( "You must check the box to proceed", "red");
eventListenerButtonNotCheckedWarning(buttonRegister,checkPrivacyPolicy, paragraphWarning,listenerCheckSetting);


//____________Listener final submit to send all the register data to server___________________________________
inputFirstName="firstname";
inputLastName="lastname";
eventListenerRegisterButton(buttonRegister,inputEmail,inputFirstName,inputLastName,inputPassword,"click",apiPHPfile);