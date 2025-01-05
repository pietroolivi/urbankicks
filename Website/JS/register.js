//------------------------------------------------Setting all the listeners----------------------------------------------------------
//____________Listener For the "email already in use" attached to the email input_______________________
let inputEmail = "email-register"; //from register1.html
let paragraphWarning = "email-register-error"; //where it is?
let apiPHPfile="register_handler.php";
let typeOfEvent="blur";
let bodyPrefixName = "emailinsert"; //PHP: given this in the $_POST return me a boolean if it exist in the emails
const listenerEmailSetting = new listenerObjectSetting( "Email already in use", "red");
eventListenerFormRegisterWarning(inputEmail,paragraphWarning,typeOfEvent,apiPHPfile,bodyPrefixName,
    listenerEmailSetting);

//___________Listener for the passwords rules_______________________________________________________
let inputPassword="password-register";
const warningListClass="passRules";
const listenerPassSetting = new listenerObjectSetting(  [
                                                            "8 to 20 characters long",
                                                            "Contains both letters and numbers",
                                                            "One symbols !\"#$%&'()*+,-./:;<=>?"
                                                        ], 
                                                        "red");
eventListenerPasswordRules(inputPasswords,warningListClass,listenerPassSetting);


//____________Listener For the "passwords don't match" attached to the password confirmation_______________________
inputPassword = "password-register"; //from register2.html
const inputPassword2 = "confirm-password-register"; //from register2.html
paragraphWarning = "passWarning";
const listenerPassMatch = new listenerObjectSetting( "Passwords don't match", "red");
eventListenerFormInputComparisonWarning(inputPassword,inputPassword2,paragraphWarning,listenerPassMatch);

//____________Listener For the "emails don't match" attached to the email confirmation_______________________
inputEmail = "email"; //from register1.html
const inputEmail2 = "emailForm2"; //where it is?
paragraphWarning="emailDontMatch"; 
const listenerEmailMatch = new listenerObjectSetting( "Emails don't match", "red");
eventListenerFormInputComparisonWarning(inputEmail,inputEmail2,paragraphWarning,listenerEmailMatch);

//____________Listener For the privacy policy not checked attached to the button Register_______________________
const buttonRegister= "register-button"; //from register3.html
const checkPrivacyPolicy = "terms-and-privacy"; //from register3.html
paragraphWarning = "checkWarning"; 
const listenerCheckSetting = new listenerObjectSetting( "You must check the box to proceed", "red");
eventListenerButtonNotCheckedWarning(buttonRegister,checkPrivacyPolicy, paragraphWarning,listenerCheckSetting);

//___________Lister for section "display=block" in register step______________________________________________
let buttonNext = "submit-1";
let idHTMLStructure2="id-section-2";
let idHTMLStructure1="id-section-1";
eventListenerAppendHTML(buttonNext,idHTMLStructure2,null,null,idHTMLStructure1);

buttonNext = "submit-2";
let idHTMLStructure3="id-section-3";
eventListenerAppendHTML(buttonNext,idHTMLStructure3,null,null,idHTMLStructure2);


//____________Listener final submit to send all the register data to server___________________________________
inputFirstName="first-name";
inputLastName="last-name";
eventListenerRegisterButton(buttonRegister,inputEmail,inputFirstName,inputLastName,inputPassword,"click",apiPHPfile);