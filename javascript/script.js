// script.js
const loginToggle = document.getElementById('loginToggle');
const registerToggle = document.getElementById('registerToggle');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');

loginToggle.addEventListener('click', () => {
    loginToggle.classList.add('active');
    registerToggle.classList.remove('active');
    loginForm.classList.add('active');
    registerForm.classList.remove('active');
});
registerToggle.addEventListener('click', () => {
    registerToggle.classList.add('active');
    loginToggle.classList.remove('active');
    registerForm.classList.add('active');
    loginForm.classList.remove('active');
});
