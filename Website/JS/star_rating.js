const ratingFromPoint = (evt) => {
    const el = evt.currentTarget;
    const pointerX = evt.pageX - el.offsetLeft;
    return Math.max(1, Math.min(5, Math.ceil(pointerX / el.offsetWidth * 5)));
};

const starRating = (el) => {
    const colorDefault = getComputedStyle(el).getPropertyValue("--color");
    const colorClick = "#f00";
    let ratingSelected = 0;
    
    el.addEventListener("pointerdown", (evt) => {
        ratingSelected = ratingFromPoint(evt);
        el.style.setProperty("--color", colorClick);
        el.style.setProperty("--rating", ratingSelected);
    });
    
    el.addEventListener("pointermove", (evt) => {
        evt.preventDefault();
        const ratingHover = ratingFromPoint(evt);
        el.style.setProperty("--rating", ratingHover);
    });
    
    el.addEventListener("pointerleave", (evt) => {
        el.style.setProperty("--color", colorDefault);
        el.style.setProperty("--rating", ratingSelected);
    });

    el.addEventListener("pointerup", (evt) => {
        ratingSelected = ratingFromPoint(evt);
        console.log(ratingSelected); // @TODO: Send ratingSelected value to server
    });
};

document.querySelectorAll('[style="--rating:0"]').forEach(starRating);