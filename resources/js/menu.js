document.addEventListener('DOMContentLoaded', function () {
    const menuIcon = document.querySelector('.menu-icon');
    const nav = document.querySelector('nav');

    if (menuIcon && nav) {
        menuIcon.addEventListener('click', function () {
            nav.classList.toggle('menu-visible');
        });
    }
});