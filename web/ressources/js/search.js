const nomCommuneArrivee = document.querySelector('.nomCommuneArrivee');
const insideDivide = document.querySelector('.insideDivide');
const contain = document.querySelector('.contain');
const searchButton = document.getElementById('searchButton');
const iconLocation = document.querySelector('.iconsLocation');
iconLocation.id = 'iconTrajet2';
const lineTravel = document.createElement('div');
lineTravel.classList.add('underlineTravel');
lineTravel.id = 'lineTravel3';

const circleAdd = document.createElement('img');
circleAdd.src = '../web/ressources/img/icons/circle-regular.svg';
circleAdd.classList.add('iconsLocationStart');

const cross = document.createElement('img');
cross.src = '../ressources/img/icons/xmark-solid.svg';
cross.classList.add('deleteDestination');

const circles = document.createElement('div');
circles.classList.add('circles');
circles.id = 'circles2';

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
        let oldIconTemp = oldCircle;
        let indexTemp = indexInput;
        oldIconTemp.addEventListener("mouseover",()=>{overCircle(oldIconTemp)});
        oldIconTemp.addEventListener("mouseout",()=>{outCircle(oldIconTemp)});
        oldIconTemp.addEventListener("click",()=>{deleteLocation(oldIconTemp)});
        oldIconTemp.id = "iconTrajet"+indexTemp;

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

        oldLine.id = "lineTravel"+indexInput;
        oldLine.appendChild(autocompletion);
        oldLine.appendChild(input);
        oldLine.appendChild(searchButton);

        const newCircles = document.createElement('div');
        newCircles.classList.add('circles');
        newCircles.id = 'circles'+indexInput;

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
        newLineTravel.id = 'lineTravel'+(indexInput+1)

        newLineTravel.appendChild(newCircleAdd);
        newLineTravel.appendChild(newButton);


        insideDivide.appendChild(newLineTravel);

        oldLine = newLineTravel;
        oldInput = input;
        oldIconLocation = oldCircle;
        oldCircle = newCircleAdd;




        let i = indexInput;
        document.getElementById("ville"+i).addEventListener('input', ()=>{autoCompletion(document.getElementById("ville"+i),i)});
        document.getElementById("ville"+i).addEventListener('click', ()=>{showOldAutocompletion(document.getElementById("ville"+i),i)});
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

let oldIconSrc;
function overCircle(element){

    oldIconSrc = element.src;
    element.src = '../ressources/img/icons/xmark-solid.svg';
    element.classList.add("crossIcon");
}


function outCircle(element){
    element.src = oldIconSrc;
    element.classList.remove("crossIcon");
    oldIconSrc = undefined;
}

function deleteLocation(element){

    let i = parseInt(element.id.slice(-1));


    if(element.contains( event.target )){
        if(i === indexInput-1)
        {
            console.log(i+" "+indexInput)
            document.getElementById("lineTravel"+(i-1)).remove();
            document.getElementById("circles"+(i-1)).remove();

            document.getElementById("ville"+i).id = "ville"+(i-1);
            document.getElementById("autocompletion"+i).id = "autocompletion"+(i-1);
            document.getElementById("iconTrajet"+(i)).id = "iconTrajet"+(i-1);
            document.getElementById("lineTravel"+(i)).id = "lineTravel"+(i-1);

            document.getElementById("circles"+(i)).id = "circles"+(i-1);
        }
        else
        {
            document.getElementById("lineTravel"+(i)).remove()

            for(let j = i+1; j<indexInput;j++)
            {
                document.getElementById("ville"+j).id = "ville"+(j-1);
                document.getElementById("autocompletion"+j).id = "autocompletion"+(j-1);
                document.getElementById("iconTrajet"+(j)).id = "iconTrajet"+(j-1);
                document.getElementById("lineTravel"+(j)).id = "lineTravel"+(j-1);
            }


        }

        if(i === 11)
        {
            const newCircleAdd = document.createElement('img');
            newCircleAdd.src = '../ressources/img/icons/circle-regular.svg';
            newCircleAdd.classList.add('iconsLocationStart');

            const newButton = document.createElement('button');
            newButton.classList.add('addDest');
            newButton.textContent = 'Ajouter une destination';
            newButton.addEventListener('click', addInputDest);


            const newCircles = document.createElement('div');
            newCircles.classList.add('circles');
            newCircles.id = 'circles'+indexInput;

            for (let i = 0; i < 3; i++) {
                const newCircle = circleTemplate.cloneNode(true);
                newCircles.appendChild(newCircle);
            }

            const newLineTravel = document.createElement('div');
            newLineTravel.classList.add('underlineTravel');
            newLineTravel.id = 'lineTravel'+i
            newLineTravel.appendChild(newCircleAdd);
            newLineTravel.appendChild(newButton);

            insideDivide.appendChild(newLineTravel);
        }
        else if(i !== indexInput-1)
        {
            document.getElementById("circles"+i).remove()
        }


        indexInput--;
    }
}





//autocompletion
function afficheVilles(tableau, i) {


    let autoCompletion = document.getElementById("autocompletion"+i)

    let z = 1;
    let newTab = [];
    for(let ville of tableau)
    {
        if(ville !==  document.getElementById("ville"+i).value)
        {
            newTab.push(ville);
            let p = document.createElement("p");
            p.innerHTML =ville
            p.classList.add(z%2 === 1 ? "odd" : "even");
            autoCompletion.appendChild(p);
            p.addEventListener("click", ()=>(completeInput(p,document.getElementById("ville"+i),i)))
            z++;
        }
    }


    let tabLen = newTab.length
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


document.getElementById("ville1").addEventListener('input', ()=>{autoCompletion(document.getElementById("ville1"),1)});
document.getElementById("ville1").addEventListener('click', ()=>{showOldAutocompletion(document.getElementById("ville1"),1)});

document.getElementById("ville2").addEventListener('input', ()=>{autoCompletion(document.getElementById("ville2"),2)});
document.getElementById("ville2").addEventListener('click', ()=>{showOldAutocompletion(document.getElementById("ville2"),2)});


function autoCompletion(element,i){


    if(element.value.length >2)
    {
        maRequeteAJAX(element.value,i);
    }
    else {

        videVille(i);
    }
}

function showOldAutocompletion(element,i){
    if( element.contains( event.target ) && element.value.length >2){

        maRequeteAJAX(element.value,i);
    }
    else
    {
        hide()
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


}

document.addEventListener('keydown', (event)=> {
    if(event.code === 'Tab' || event.code === 'Escape' )
    {
        hide();
    }
    else  if (event.code === 'ArrowUp') {
        highlightDown();
    }
    else if (event.code === 'ArrowDown') {
        highlightUp();
    }
    else if(event.code === 'Enter'){
        select();
    }
});


function highlightUp()
{
    let villes;
    for(let ac of document.getElementsByClassName("autocompletion"))
    {
        if(ac.children.length > 0)
        {
            villes = ac.children
        }
    }

    if(villes !== undefined)
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
    let villes;
    for(let ac of document.getElementsByClassName("autocompletion"))
    {
        if(ac.children.length > 0)
        {
            villes = ac.children
        }
    }

    if(villes !== undefined)
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

function select(){


    let autocompletion;
    for(let ac of document.getElementsByClassName("autocompletion"))
    {
        if(ac.children.length > 0)
        {
            autocompletion =ac;
        }
    }


    if(currentIndex !== -1 && autocompletion !== undefined)
    {
        document.getElementById("ville"+autocompletion.id.slice(-1)).value = autocompletion.children[currentIndex].innerText;
    }
}

function completeInput(element,barre,i)
{
    barre.value = element.innerText;
    videVille(i)
}

/*
document.getElementById("recherche").addEventListener("click",hide)

document.getElementById("mapContainer").addEventListener("click",hide)
*/


function hide()
{
    currentIndex = -1;
    for(let i=1;i<=document.getElementsByClassName("autocompletion").length;i++) {
        videVille(i)
    }
}


