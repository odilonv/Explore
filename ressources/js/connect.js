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


    const connectForm = document.createElement('form');
    connectForm.method = "post";
    connectForm.action = "../web/connexion";

    connectForm.appendChild(addInput("text","Login"));
    connectForm.appendChild(addInput("password","********"));
    connectForm.appendChild(addConnectButton('se connecter'));

    newHeader.appendChild(connectForm)

    addRegisterButton('s\'inscrire');
    addExitButton();
}

// Fonction pour ajouter les champs de texte pour le nom et le mot de passe
function addInput(type ,placeholder) {
    const input = document.createElement('input');
    input.type = type;
    input.placeholder = placeholder;
    input.classList.add('lines');
    return input
}

// Fonction pour ajouter le bouton "se connecter"
function addConnectButton(text) {
    const connectButton = document.createElement('input');
    connectButton.type = "submit";

    connectButton.classList.add('connectButton');
    connectButton.textContent = text;


    return connectButton;
}

// Fonction pour ajouter le lien pour s'inscrire
function addRegisterButton(text) {
    const registerButton = document.createElement('a');
    registerButton.classList.add('registerButton');
    registerButton.textContent = text;
    registerButton.href="./inscription";
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
