// Sélectionner les éléments HTML nécessaires
const connect = document.querySelector('.connectFooter');
const header = document.querySelector('.connectHeader');
const imgPP = header.querySelector('img');

const newHeader = document.createElement('div');

let initialContent = connect.innerHTML;

header.addEventListener('click', addForm);

// Fonction pour ajouter le formulaire
function addForm() {
    document.querySelector('.connectHeader').remove();

    connect.classList.add('formConnect');
    connect.classList.remove('clickable');
    newHeader.innerHTML='';
    newHeader.appendChild(imgPP);
    connect.appendChild(newHeader);



    addInput('Ex: Bernard', '*******');

    addConnectButton('se connecter');

    addRegisterButton('s\'inscrire');

    addExitButton();
}

// Fonction pour ajouter les champs de texte pour le nom et le mot de passe
function addInput(placeholderName, placeholderMdp) {
    const inputName = document.createElement('input');
    const inputMdp = document.createElement('input');

    inputName.placeholder = placeholderName;
    inputMdp.placeholder = placeholderMdp;

    inputName.classList.add('lines');
    inputMdp.classList.add('lines');

    connect.appendChild(inputName);
    connect.appendChild(inputMdp);
}

// Fonction pour ajouter le bouton "se connecter"
function addConnectButton(text) {
    const connectButton = document.createElement('button');
    connectButton.classList.add('connectButton');
    connectButton.textContent = text;

    connect.appendChild(connectButton);
}

// Fonction pour ajouter le lien pour s'inscrire
function addRegisterButton(text) {
    const registerButton = document.createElement('a');
    registerButton.classList.add('registerButton');
    registerButton.textContent = text;
    newHeader.appendChild(registerButton);
}

// Fonction pour ajouter le bouton de sortie
function addExitButton() {
    const exitButton = document.createElement('a');
    const cross = document.createElement('img');

    cross.src = '../ressources/img/icons/xmark-solid.svg';
    cross.classList.add('icons');

    exitButton.classList.add('exitButton');
    exitButton.appendChild(cross);
    newHeader.appendChild(exitButton);

    exitButton.addEventListener('click', removeForm);
}

// Fonction pour supprimer le formulaire
function removeForm() {
    connect.classList.add('clickable');
    connect.classList.remove('formConnect');

    connect.innerHTML = initialContent;

    // Ajouter un écouteur d'événement sur le header
    document.querySelector('.connectHeader').addEventListener('click', addForm);
}
