//------------------------------------------------Setting all the listeners----------------------------------------------------------
//______________Listener for the home navigation tab___________________________________________
let idButton="idButton";
let apiHome="";// see eventListenerAppendHTML comments in JS/Functions/listeners.js
let idHTMLStructure="";// see eventListenerAppendHTML comments in JS/Functions/listeners.js
eventListenerAppendHTML(idButton,idHTMLStructure,apiHome);

//i bottoni con
let category="designers";
let bodyPrefixName="filtername";  //it depends on how
let additionalListeners= ""; //if additional listeners are needed.
eventListenerCheckList(category,bodyPrefixName,additionalListeners);