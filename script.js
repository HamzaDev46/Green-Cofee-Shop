const header= document.querySelector('header');
function fixedNavbar(){
    header.classList.toggle('scroll', window.pageYOffset > 0);
}
fixedNavbar();
window.addEventListener('scroll', fixedNavbar);

let menu = document.querySelector('#menu-btn');
let userBtn = document.querySelector('#user-btn');

menu.addEventListener ('click', function(){
    let navbar = document.querySelector('.navbar');
    navbar.classList.toggle('active');
});

userBtn.addEventListener ('click', function(){
    let userBox = document.querySelector('.user-box');
    userBox.classList.toggle('active');
}); 
"use strict";

// Select DOM elements
const leftArrow = document.querySelector('.left-arrow .bxs-left-arrow');
const rightArrow = document.querySelector('.right-arrow .bxs-right-arrow');
const slider = document.querySelector('.slider');

// Scroll to the right
function scrollRight() {
  if (slider.scrollWidth - slider.clientWidth === slider.scrollLeft) {
    slider.scrollTo({
      left: 0,
      behavior: "smooth"
    });
  } else {
    slider.scrollBy({
      left: window.innerWidth,
      behavior: "smooth"
    });
  }
}

// Scroll to the left
function scrollLeft() {
  slider.scrollBy({
    left: -window.innerWidth,
    behavior: "smooth"
  });
}

// Auto-scroll every 7 seconds
let timerId = setInterval(scrollRight, 7000);

// Reset timer
function resetTimer() {
  clearInterval(timerId);
  timerId = setInterval(scrollRight, 7000);
}

// Scroll event - left and right arrow
slider.addEventListener('click', function (ev) {
  if (ev.target === leftArrow) {
    scrollLeft();
    resetTimer();
  } else if (ev.target === rightArrow) {
    scrollRight();
    resetTimer();
  }
});

//testimonial slider
