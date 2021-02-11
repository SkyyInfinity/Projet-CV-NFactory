$(document).ready(function() {

    const pagesList = $('.menu');
    const accountList = $('#js_account');
    const hamburger = $('#js_burger');
    const header = $('.site-header');
    const logo = $('#js_logo');

    // Navigation Burger (au click sur le bouton burger, le menu s'ouvre)
    hamburger.on('click', function() {
        pagesList.toggleClass('active');
        accountList.toggleClass('active');
        hamburger.toggleClass('active');
    });
    // Navigation Fixed Shrink (au scroll, le menu reduit de taille)
    $(window).on('scroll touchmove', function () {
        header.toggleClass('sticky', $(document).scrollTop() > 100);
        if($(document).scrollTop() > 100) {
            logo.css('transform', 'scale(0.7)');
            pagesList.css('top', '320px');
            accountList.css('top', '80px');
        } else {
            logo.css('transform', 'scale(1)');
            pagesList.css('top', '370px');
            accountList.css('top', '121px');
        }
    });
});