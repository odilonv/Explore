const nomCommuneArrivee = document.querySelector('.nomCommuneArrivee');
const insideDivide = document.querySelector('.insideDivide');
const contain = document.querySelector('.contain');
const searchButton = document.getElementById('searchButton');
const iconLocation = document.querySelector('.iconsLocation');

const lineTravel = document.createElement('div');
lineTravel.classList.add('underlineTravel');

const circleAdd = document.createElement('img');
circleAdd.src = '../ressources/img/icons/circle-regular.svg';
circleAdd.classList.add('iconsLocationStart');

const circles = document.createElement('div');
circles.classList.add('circles');

const circleTemplate = document.createElement('img');
circleTemplate.src = '../ressources/img/icons/circle-solid.svg';
circleTemplate.classList.add('circleTransition');

for (let i = 0; i < 3; i++) {
    const circle = circleTemplate.cloneNode(true);
    circles.appendChild(circle);
}

const addDest = document.createElement('button');
addDest.classList.add('addDest');
addDest.textContent = 'Ajouter une destination';

contain.style.height = '29%';

nomCommuneArrivee.addEventListener('click', addSearchInput);

function addSearchInput() {
    nomCommuneArrivee.removeEventListener('click', addSearchInput);

    insideDivide.appendChild(circles);
    lineTravel.appendChild(circleAdd);
    lineTravel.appendChild(addDest);
    insideDivide.appendChild(lineTravel);

    addDest.addEventListener('click', addInputDest);
}

function addInputDest() {
    nomCommuneArrivee.placeholder = 'Un point d\'arrêt ?';
    nomCommuneArrivee.classList.remove('nomCommuneArrivee');

    iconLocation.src = '../ressources/img/icons/circle-solid.svg';
    iconLocation.classList.add('iconsLocationStart');
    iconLocation.classList.remove('iconsLocation');

    circleAdd.classList.remove('iconsLocationStart');
    circleAdd.classList.add('iconsLocation');
    circleAdd.src = '../ressources/img/icons/location-dot-solid.svg';

    const input = document.createElement('input');
    input.classList.add('nomCommuneArrivee');
    input.placeholder = 'Où allons-nous ?';

    document.querySelector('.addDest').remove();
    searchButton.remove();

    lineTravel.appendChild(input);
    lineTravel.appendChild(searchButton);
}

function search(){
    let depart = document.getElementById('nomCommuneDepart_id').textContent;
    let arrivee = document.getElementById('nomCommuneArrivee_id').textContent;

    let requete = `http://localhost/devWeb/SAE/web/getPlusCourt/${depart}/${arrivee}`;
    fetch(requete).then(response => response.json()).then(data => addRoad(map, data.multiline));
}

document.getElementById('searchButton').addEventListener('click', search);