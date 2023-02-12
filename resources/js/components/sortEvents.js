const sortableSelect = document.querySelectorAll('.sort');
sortableSelect.forEach((select) => {
    select.addEventListener('change', (e) => {
        const url = new URL(window.location.href);
        url.searchParams.set(select.name, e.target.value);
        window.location.href = url.href;
    });
});
