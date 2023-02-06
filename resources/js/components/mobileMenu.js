const mobileMenuHandler = document.querySelector('.navigation-mobile-toggler');
const mobileNav = document.querySelector('.navigation-mobile');
if(mobileMenuHandler) {
    mobileMenuHandler.addEventListener('click', function() {
        mobileNav.classList.toggle('mobile-menu-open');
    });
}
