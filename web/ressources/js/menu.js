let menu = document.getElementById('logo');
let sousmenu =document.getElementById('sousmenu');
let iconmenu =document.getElementById('iconmenu');
let compteurClics = 0;

menu.addEventListener('click', function() {
    compteurClics++;

    if (compteurClics === 1) {
        sousmenu.classList.remove("sousmenu-hidden");
        sousmenu.classList.add("sousmenu-show");
        menu.style.borderRadius="0.5em 0.5em 0 0";
        iconmenu.src="../web/ressources/img/icons/caret-up-solid.svg"

    }
    else if (compteurClics === 2) {
        sousmenu.classList.remove("sousmenu-show");
        sousmenu.classList.add("sousmenu-hidden");
        menu.style.borderRadius="0.5em";
        iconmenu.src="../web/ressources/img/icons/caret-down-solid.svg"

        compteurClics = 0;
    }
});
