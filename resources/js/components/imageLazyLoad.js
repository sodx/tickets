const lazyImages = document.querySelectorAll('.lazy-image');
if(lazyImages.length > 0) {
    const lazyImageObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                const lazyImage = entry.target;
                const lazyImageInner = lazyImage.querySelector('img');
                lazyImageInner.src = lazyImageInner.dataset.src;
                lazyImage.classList.remove('lazy-image');
                lazyImageObserver.unobserve(lazyImage);
            }
        });
    });
    lazyImages.forEach(function (lazyImage) {
        lazyImageObserver.observe(lazyImage);
    });
}
