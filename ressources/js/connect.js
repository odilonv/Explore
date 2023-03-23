let footer = document.querySelector('footer');
let connect = document.querySelector('.connectFooter');

connect.addEventListener('click',() => {
    connect.classList.add('formConnect')
    let lines = document.createElement('div');
    let lines1 = document.createElement('div');
    connect.appendChild(lines);
    connect.appendChild(lines1);

})