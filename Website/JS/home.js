//-------------------------------------------Objects used by this script------------------------------------------------------
//________________Object for the filters______________________________________________________________________________________
const objectFilters = new ListenerObjectFilters();
const objectExclusiveCategoryFilter = new exclusiveCategoryObjectFilter();
objectFilters.loadFromSession();

//------------------------------------------------Setting all the listeners----------------------------------------------------
//______________Listener for the button of exclusive category filters___________________________________________
const idNavigationParagraph="navigationPar";
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

//_______________Update Breadcrumb__________________________________________________________________________________________
function updateBreadcrumb(genre, type) {
    const breadcrumb = document.querySelector('.breadcrumb ol');
    if (!breadcrumb) return;

    // Clear existing breadcrumb
    breadcrumb.innerHTML = '';

    // If no genre and type, hide breadcrumb
    if (!genre && !type) {
        document.querySelector('.breadcrumb').style.display = 'none';
        return;
    }

    // Show breadcrumb
    document.querySelector('.breadcrumb').style.display = 'block';

    // Add Home
    const homeItem = document.createElement('li');
    homeItem.innerHTML = '<a href="index.php">Home</a>';
    breadcrumb.appendChild(homeItem);

    // Add Genre if exists
    if (genre) {
        const genreItem = document.createElement('li');
        genreItem.innerHTML = `<a href="index.php?genre=${encodeURIComponent(genre)}">${genre.charAt(0).toUpperCase() + genre.slice(1)}</a>`;
        breadcrumb.appendChild(genreItem);
    }

    // Add Type if exists
    if (type) {
        const typeItem = document.createElement('li');
        typeItem.innerHTML = `<span aria-current="page">${type.charAt(0).toUpperCase() + type.slice(1)}</span>`;
        breadcrumb.appendChild(typeItem);
    }
}

//_______________Add listener for filter changes________________________________________________________________________________
function eventListenerFilterChange() {
    const genreFilters = document.querySelectorAll('[name="designers"]');
    const typeFilters = document.querySelectorAll('[name="size"]');

    const handleFilterChange = () => {
        const selectedGenre = Array.from(genreFilters)
            .find(filter => filter.checked)?.value || '';
        const selectedType = Array.from(typeFilters)
            .find(filter => filter.checked)?.value || '';
        
        updateBreadcrumb(selectedGenre, selectedType);
    };

    genreFilters.forEach(filter => 
        filter.addEventListener('change', handleFilterChange));
    typeFilters.forEach(filter => 
        filter.addEventListener('change', handleFilterChange));
}

//_______________Add to existing listeners_____________________________________________________________________________________
eventListenerFilterChange();