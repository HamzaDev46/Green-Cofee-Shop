
// JavaScript to handle menu and profile dropdown
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('menu-btn');
    const userBtn = document.getElementById('user-btn');
    const navbar = document.getElementById('navbar');
    const profileDetail = document.getElementById('profile-detail');
    
    // Toggle mobile menu
    menuBtn.addEventListener('click', function() {
        navbar.classList.toggle('active');
        profileDetail.classList.remove('active');
        
        // Change menu icon
        if (navbar.classList.contains('active')) {
            menuBtn.classList.replace('bx-menu', 'bx-x');
        } else {
            menuBtn.classList.replace('bx-x', 'bx-menu');
        }
    });
    
    // Toggle profile dropdown
    userBtn.addEventListener('click', function() {
        profileDetail.classList.toggle('active');
        navbar.classList.remove('active');
        menuBtn.classList.replace('bx-x', 'bx-menu');
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.icons') && !event.target.closest('.profile-detail')) {
            profileDetail.classList.remove('active');
        }
        if (!event.target.closest('#menu-btn') && !event.target.closest('.navbar')) {
            navbar.classList.remove('active');
            menuBtn.classList.replace('bx-x', 'bx-menu');
        }
    });
    
    // Add scroll effect to header
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});
