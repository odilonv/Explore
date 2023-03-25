let footer = document.querySelector('footer');
let connect = document.querySelector('.connectFooter');
let connectDiv = document.querySelector('.connectFooter > div');

connect.addEventListener('click',() => {

    document.getElementById('h2Connexion').remove();
    connectDiv.style.padding='0'; // Changer ça plus tard
    connect.classList.add('formConnect');

    let inputName = document.createElement('input');
    let inputMdp = document.createElement('input');

    let connectButton = document.createElement('button');
    let registerButton = document.createElement('button');
    let exitButton = document.createElement('button');
    let cross = document.createElement('img');


    connect.classList.remove('clickable');
    inputName.placeholder="Ex: Bernard";
    inputMdp.placeholder='*******';
    inputName.classList.add('lines');
    inputMdp.classList.add('lines');
    registerButton.classList.add('registerButton');
    registerButton.textContent='s\'inscrire';
    cross.src='../ressources/img/icons/xmark-solid.svg';
    cross.classList.add('icons');
    connectButton.classList.add('connectButton');
    connectButton.textContent='se connecter';

    exitButton.classList.add('exitButton');
    exitButton.appendChild(cross);
    connect.appendChild(inputName);
    connect.appendChild(inputMdp);
    connect.appendChild(connectButton);
    connectDiv.appendChild(registerButton);
    connectDiv.appendChild(exitButton);

})

