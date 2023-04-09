const nomCommuneArrivee = document.querySelector('.nomCommuneArrivee');
const insideDivide = document.querySelector('.insideDivide');
const contain = document.querySelector('.contain');
const searchButton = document.getElementById('searchButton');
const iconLocation = document.querySelector('.iconsLocation');

const lineTravel = document.createElement('div');
lineTravel.classList.add('underlineTravel');

const circleAdd = document.createElement('img');
circleAdd.src = '../web/ressources/img/icons/circle-regular.svg';
circleAdd.classList.add('iconsLocationStart');

const circles = document.createElement('div');
circles.classList.add('circles');

const circleTemplate = document.createElement('img');
circleTemplate.src = '../web/ressources/img/icons/circle-solid.svg';
circleTemplate.classList.add('circleTransition');

for (let i = 0; i < 3; i++) {
    const circle = circleTemplate.cloneNode(true);
    circles.appendChild(circle);
}

const addDest = document.createElement('button');
addDest.classList.add('addDest');
addDest.textContent = 'Ajouter une destination';

contain.style.height = '29%';

/*
nomCommuneArrivee.addEventListener('click', addSearchInput);

function addSearchInput() {
    nomCommuneArrivee.removeEventListener('click', addSearchInput);

 */

    insideDivide.appendChild(circles);
    lineTravel.appendChild(circleAdd);
    lineTravel.appendChild(addDest);
    insideDivide.appendChild(lineTravel);

    addDest.addEventListener('click', addInputDest);


let oldLine = lineTravel;
let oldInput = nomCommuneArrivee;
let oldIconLocation = iconLocation;
let oldCircle = circleAdd;
let indexInput = 3
function addInputDest() {

    if(indexInput <= 10)
    {
        oldInput.placeholder = 'Un point d\'arrêt ?';
        oldInput.classList.remove('nomCommuneArrivee');

        oldIconLocation.src = '../web/ressources/img/icons/circle-solid.svg';
        oldIconLocation.classList.add('iconsLocationStart');
        oldIconLocation.classList.remove('iconsLocation');

        oldCircle.classList.remove('iconsLocationStart');
        oldCircle.classList.add('iconsLocation');
        oldCircle.src = '../web/ressources/img/icons/location-dot-solid.svg';

        const input = document.createElement('input');
        input.classList.add('nomCommuneArrivee');
        input.placeholder = 'Où allons-nous ?';



        const autocompletion = document.createElement('div');
        autocompletion.classList.add("autocompletion");
        autocompletion.id = "autocompletion"+indexInput;
        //indexInput = index de la barre de recherche
        input.id = "ville"+indexInput;


        document.querySelector('.addDest').remove();

        const newButton = document.createElement('button');
        newButton.classList.add('addDest');
        newButton.textContent = 'Ajouter une destination';
        newButton.addEventListener('click', addInputDest);

        searchButton.remove();

        oldLine.appendChild(autocompletion);
        oldLine.appendChild(input);
        oldLine.appendChild(searchButton);

        const newCircles = document.createElement('div');
        newCircles.classList.add('circles');

        for (let i = 0; i < 3; i++) {
            const newCircle = circleTemplate.cloneNode(true);
            newCircles.appendChild(newCircle);
        }

        const newCircleAdd = document.createElement('img');
        newCircleAdd.src = '../web/ressources/img/icons/circle-regular.svg';
        newCircleAdd.classList.add('iconsLocationStart');


        insideDivide.appendChild(newCircles);

        const newLineTravel = document.createElement('div');
        newLineTravel.classList.add('underlineTravel');

        newLineTravel.appendChild(newCircleAdd);

        newLineTravel.appendChild(newButton);
        insideDivide.appendChild(newLineTravel);

        oldLine = newLineTravel;
        oldInput = input;
        oldIconLocation = oldCircle;
        oldCircle = newCircleAdd;




        let i = indexInput;
        input.addEventListener("input",()=>{autoCompletion(input,i)});
        indexInput++;
        if(indexInput > 10)
        {
            newCircleAdd.remove()
            newButton.remove()
            newCircles.remove()
            newLineTravel.remove()
        }
    }


}






//autocompletion
function afficheVilles(tableau, i) {


    let autoCompletion = document.getElementById("autocompletion"+i)

    let z = 1;
    for(let ville of tableau)
    {
        let p = document.createElement("p");
        p.innerHTML =ville
        p.classList.add(z%2 === 1 ? "odd" : "even");
        autoCompletion.appendChild(p);
        p.addEventListener("click", ()=>(completeInput(p,document.getElementById("ville"+i),i)))


        z++;
    }

    /*
    if(tableau.length >0)
    {
        autoCompletion.style.border="1px solid grey";
    }
    */

    let tabLen = tableau.length
    autoCompletion.style.transform = "translateY("+((25*tabLen)/2+(15))+"px) translateX(5%)";


}

function videVille(i){
    let autoCompletion = document.getElementById("autocompletion"+i);

    autoCompletion.innerHTML = "";
    autoCompletion.style.border="0px";
}


let newRequest = false;
function requeteAJAX(stringVille,callback,startLoadingAction,endLoadingAction,i) {
    newRequest = true;
    startLoadingAction();

    //changer url
    let url = "../web/requeteVille/"+encodeURIComponent(stringVille);
    //let url = "Explore/web/requeteVille/" + encodeURIComponent(stringVille);

    let requete = new XMLHttpRequest();
    requete.open("GET", url, true);
    requete.addEventListener("load", function () {
        callback(requete,i);
        endLoadingAction();
    });
    newRequest = false;

    requete.send(null);
    if(newRequest)
    {
        requete.abort();
    }

}



function callback_4(req,i){

    videVille(i);
    let villes = [];

    for(let ville of JSON.parse(req.responseText)){
        villes.push(ville['nom_comm']);
    }
    afficheVilles(villes,i);
}

document.getElementById("ville1").addEventListener("input",()=>{autoCompletion(document.getElementById("ville1"),1)});
document.getElementById("ville2").addEventListener("input",()=>{autoCompletion(document.getElementById("ville2"),2)});

function autoCompletion(element,i){
    if(element.value.length >2)
    {
        maRequeteAJAX(element.value,i);
    }
    else {
        videVille(i);
    }
}

//met une majuscule au debut
function maRequeteAJAX(ville,i){
    ville.toLowerCase();
    ville = ville.charAt(0).toUpperCase()+ville.slice(1);


    /* y'a pas de gif loading donc j'ai mit des fonctions vides
    requeteAJAX(ville,callback_4,
        ()=>{document.getElementById("loading").style.visibility="visible"},
        ()=>{document.getElementById("loading").style.visibility="hidden"});
        */
    requeteAJAX(ville,callback_4,
        ()=>{},
        ()=>{},i);
}

document.onkeydown = checkKey;
let currentIndex = -1;
function checkKey(e) {

    e = e || window.event;

    if (e.keyCode === '38') {
        highlightDown();
    }
    else if (e.keyCode === '40') {
        highlightUp();
    }
    else if(e.keyCode === '13'){
        select();
    }
}

function highlightUp()
{
    let villes = document.getElementById("autocompletion").children;

    if(villes.length >0)
    {
        if(currentIndex === -1 || currentIndex+1 >= villes.length)
        {
            villes[villes.length-1].classList.remove("selected");
            currentIndex = 0;
        }
        else
        {
            for(let ville of villes)
            {
                ville.classList.remove("selected");
            }

            currentIndex++;

        }
        villes[currentIndex].classList.add("selected");
    }
    else
    {
        currentIndex = -1;
    }
}

function highlightDown()
{
    let villes = document.getElementById("autocompletion").children;
    if(villes.length >0)
    {
        if(currentIndex === -1 || currentIndex-1 < 0)
        {
            villes[0].classList.remove("selected");
            currentIndex = villes.length-1;
        }
        else
        {
            for(let ville of villes)
            {
                ville.classList.remove("selected");
            }
            currentIndex--;

        }
        villes[currentIndex].classList.add("selected");
    }
    else
    {
        currentIndex = -1;
    }
}

function select(element){
    if(currentIndex !== -1)
    {
        element.value = document.getElementById("autocompletion").children[currentIndex];
    }
}

function completeInput(element,barre,i)
{
    barre.value = element.innerText;
    videVille(i)
}

document.getElementById("recherche").addEventListener("click",()=>{for(let i=1;i<=document.getElementsByClassName("autocompletion").length;i++){
    videVille(i)
}})

document.getElementById("mapContainer").addEventListener("click",()=>{for(let i=1;i<=document.getElementsByClassName("autocompletion").length;i++){
    videVille(i)
}})


