//-------------------------------------------Objects used by this script------------------------------------------------------
//________________Object for the filters______________________________________________________________________________________
const objectFilters= new ListenerObjectFilters();
objectFilters.loadFromSession();

//------------------------------------------------Setting all the listeners----------------------------------------------------
//______________Listener for the home navigation tab___________________________________________
let idButton="idButton";
let apiPHPfile="home_handler";// see eventListenerAppendHTML comments in JS/Functions/listeners.js
let idHTMLStructure="";// see eventListenerAppendHTML comments in JS/Functions/listeners.js
eventListenerAppendHTML(idButton,idHTMLStructure,apiHome);

//_______________Listener for applying the filters____________________________________________________________________________
eventListenerApplyFilters();

//_______________Listener for checklist "designers" to set the filters_____________________________________________________
let category="designers";
eventListenerCheckListFilter(category);

//_______________Listener for checklist "size" to set the filters__________________________________________________________
category="size";
eventListenerCheckListFilter(category);

//_______________Listener for checklist "color" to set the filters__________________________________________________________
category="color";
eventListenerCheckListFilter(category);

