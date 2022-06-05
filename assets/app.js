/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
*/
console.log('coucou');
const formShow = document.getElementById('formShow');
const formHidden = document.getElementById('formHidden');

formShow.addEventListener('click', () => {
    formHidden.classList.remove('hidden');
});

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
require('bootstrap');