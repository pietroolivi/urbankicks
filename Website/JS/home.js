//-------------------------------------------Objects used by this script------------------------------------------------------
//________________Object for the filters______________________________________________________________________________________
const objectFilters= new ListenerObjectFilters();
const objectExclusiveCategoryFilter = new exclusiveCategoryObjectFilter();
objectFilters.loadFromSession();

//------------------------------------------------Setting all the listeners----------------------------------------------------
//______________Listener for the button of exclusive category filters___________________________________________
const idNavigationParagraph="navigationPar"
eventListenerExclusiveCategoryFilter(idNavigationParagraph);

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

