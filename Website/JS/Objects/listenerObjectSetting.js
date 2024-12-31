
//needs to be imported in the base.php file so that is accessible
//from all the other js scripts.

//____________________Object used for paragraphs_________________________________________________________
function listenerObjectSetting(textContent,textColor) {
    this.textContent = textContent;
    this.textColor=textColor;
  }

  //__________________Object used for the field and response I expect from a JSON response____________
function ListenerJsonDataExpected(jsonField, jsonExpectedValue){
  this.jsonField=jsonField;
  this.jsonExpectedValue=jsonExpectedValue;
}

//__________________Object used for caching the category filter that is exclusive_________________________
  function exclusiveCategoryObjectFilter(){
    this.target="";
    this.shoeType="";
  }

  
  //__________________Object used for for filter caching and evaluation_________________________________
  function ListenerObjectFilters(){
    this.designers =[];
    this.size=[];
    this.color=[];
    this.priceMin=0;
    this.priceMax=Infinity;
  }

  ListenerObjectFilters.prototype.addCategoryFilterToCategoryName = function (categoryName,categoryFilter){
    switch (categoryName){ 
      case "designers":
        if (!this.designers.includes(categoryFilter)) {
          this.designers.push(categoryFilter);
        }
        break;
      case "size":
        if (!this.size.includes(categoryFilter)) {
          this.size.push(categoryFilter);
        }
        break;
      case "color":
        if (!this.color.includes(categoryFilter)) {
          this.color.push(categoryFilter);
        }
        break;
      case "priceMin":
        this.priceMin=categoryFilter;
        break;
      case "priceMax":
        this.priceMax=categoryFilter;
        break;
      default:
        console.log(`this category doesn't exists.`);
    }
  }

  ListenerObjectFilters.prototype.removeCategoryFilterToCategoryName = function (categoryName,categoryFilter){
    switch (categoryName){ 
      case "designers":
        removeFromArray(this.designers,categoryFilter);
        break;
      case "size":
        removeFromArray(this.size,categoryFilter);
        break;
      case "color":
        removeFromArray(this.color,categoryFilter);
        break;
      case "priceMin":
        this.priceMin=0;
        break;
      case "priceMax":
        this.priceMax=0;
        break;
      default:
        console.log(`this category doesn't exists.`);
    }
  }



  ListenerObjectFilters.prototype.areFilterEmpty = function () {
    return (
      this.designers.length === 0 &&
      this.size.length === 0 &&
      this.color.length === 0 &&
      this.priceMin === 0 &&
      this.priceMax === Infinity
    );
  };

  ListenerObjectFilters.prototype.doesElementPassFilters = function (product) {
    return (
      //DISCUSS: I need an object with backend data for all products shown in the home.
      //how can I have the colors, size and designer??
      (this.designers.length === 0 || this.designers.includes(product.dataset.designers)) &&
      (this.size.length === 0 || this.size.includes(product.dataset.size)) &&
      (this.color.length === 0 || this.color.includes(product.dataset.color)) &&
      this.priceMin === 0 &&
      this.priceMax === Infinity
    );
  };


  //this is called in home right after the constructor, so that if the web page was changed
  //it stills recover the filters.
  ListenerObjectFilters.prototype.loadFromSession = function () {
    fetch("home.php")
        .then((response) => response.json())
        .then((data) => {
            //updates the object with the new filters from the server.
            this.designers = data.designers || [];
            this.size = data.size || [];
            this.color = data.color || [];
            this.priceMin = data.priceMin || 0;
            this.priceMax = data.priceMax || Infinity;
            console.log("Filtri caricati dalla sessione:", this);
        })
        .catch((error) => console.error("Errore durante il caricamento dei filtri:", error));
};


//___________Utility to remove elements from array______________________________________________________
function removeFromArray(array,element){
  if (array.includes(element)) {
    const index = array.indexOf(element);
    if (index > -1) { // only splice array when item is found
      array.splice(index, 1); // 2nd parameter means remove one item only
    }
  }
}