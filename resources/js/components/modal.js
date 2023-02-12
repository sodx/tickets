const modalBtn = document.querySelectorAll('.modal-btn');

const closeModal = () => {
    document.querySelectorAll('.modal').forEach(function (modal) {
        modal.classList.remove('show');
    });
    document.getElementsByTagName('body')[0].classList.remove('modal-open');
    document.querySelectorAll('.modal-overlay').forEach(function (modalOverlay) {
        modalOverlay.remove();
    });
}

modalBtn.forEach(function (btn) {
    btn.addEventListener('click', function (event) {
        event.preventDefault();
        let modal = btn.getAttribute('data-modal');
        document.getElementById(modal).classList.add('show');
        const body = document.getElementsByTagName('body');
        body[0].classList.add('modal-open');
        body[0].appendChild(document.createElement('div')).classList.add('modal-overlay');

        const modalOverlay = document.querySelectorAll('.modal-overlay');
        console.log(modalOverlay);
        modalOverlay[0].addEventListener('click', function (event) {
            closeModal();
        });
    });
});


const closeBtn = document.querySelectorAll('.modal-close');
closeBtn.forEach(function (btn) {
    btn.addEventListener('click', function (event) {
        event.preventDefault();
        closeModal();
    });
});
