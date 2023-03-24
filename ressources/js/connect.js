let footer = document.querySelector('footer');
let connect = document.querySelector('.connectFooter');

connect.addEventListener('click',() => {
    connect.classList.add('formConnect');
    let lines = document.createElement('input');
    let lines1 = document.createElement('input');

    lines.placeholder="Ex: Bernard";
    lines1.placeholder='*******';
    lines.classList.add('lines');
    lines1.classList.add('lines');

    connect.appendChild(lines);
    connect.appendChild(lines1);


})