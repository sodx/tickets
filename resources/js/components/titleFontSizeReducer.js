//reduce font size of title element if it more than providen max height
const titleFontSizeReducer = (eventPosterTitle, maxTitleHeight) => {
    const titleHeight = eventPosterTitle.offsetHeight;

    if (titleHeight > maxTitleHeight) {
        const fontSize = parseInt(window.getComputedStyle(eventPosterTitle).fontSize);
        eventPosterTitle.style.fontSize = (fontSize - 1) + 'px';
        titleFontSizeReducer(eventPosterTitle, maxTitleHeight);
    }
};

const eventPosterTitle = document.querySelector('.event-poster__title');
if (eventPosterTitle) {
    titleFontSizeReducer(eventPosterTitle, 200);
}

const attractionCardDescription = document.querySelectorAll('.attraction-card__name');

if (attractionCardDescription.length > 0) {
    attractionCardDescription.forEach((item) => {
        titleFontSizeReducer(item, 200);
    });
}

const eventCardTitle = document.querySelectorAll('.event-card__title');
if (eventCardTitle.length > 0) {
    eventCardTitle.forEach((item) => {
        titleFontSizeReducer(item, 150);
    });
}
