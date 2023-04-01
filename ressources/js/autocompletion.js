function afficheVilles(tableau) {
    let autoCompletion = document.getElementById("autocompletion")
    for(let ville of tableau)
    {
        let p = document.createElement("p");
        p.innerHTML =ville
        autoCompletion.appendChild(p);
        p.addEventListener("click", ()=>(completeInput(p)))
    }

    if(tableau.length >0)
    {
        document.getElementById("autocompletion").style.border="1px solid grey";
    }


}

function videVille(){
    let autoCompletion = document.getElementById("autocompletion");
    autoCompletion.innerHTML = "";
    document.getElementById("autocompletion").style.border="0px";
}


let newRequest = false;
function requeteAJAX(stringVille,callback,startLoadingAction,endLoadingAction) {
    newRequest = true;
    startLoadingAction();

    //changer url
    let url = "http://localhost/Explore/web/requeteVille/"+encodeURIComponent(stringVille);
    //let url = "Explore/web/requeteVille/" + encodeURIComponent(stringVille);

    let requete = new XMLHttpRequest();
    requete.open("GET", url, true);
    requete.addEventListener("load", function () {
        callback(requete);
        endLoadingAction();
    });
    newRequest = false;

    requete.send(null);
    if(newRequest)
    {
        requete.abort();
    }

}



function callback_4(req){
    videVille();
    let villes = [];
    console.log(req.responseText)
    for(let ville of JSON.parse(req.responseText)){
        villes.push(ville['nom_comm']);
    }
    afficheVilles(villes);
}

let barreDeRecherche= document.getElementById("nomCommuneDepart_id");
barreDeRecherche.addEventListener("input",()=>{autoCompletion()});
console.log(barreDeRecherche)
function autoCompletion(){

    if(barreDeRecherche.value.length >2)
    {
        maRequeteAJAX(barreDeRecherche.value);
    }
    else {

        videVille();
    }



}

//met une majuscule au debut
function maRequeteAJAX(ville){
    ville.toLowerCase();
    ville = ville.charAt(0).toUpperCase()+ville.slice(1);


    /* y'a pas de gif loading donc j'ai mit des fonctions vides
    requeteAJAX(ville,callback_4,
        ()=>{document.getElementById("loading").style.visibility="visible"},
        ()=>{document.getElementById("loading").style.visibility="hidden"});
        */


    requeteAJAX(ville,callback_4,
        ()=>{},
        ()=>{});
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
    console.log(villes);
    console.log(currentIndex);
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

function select(){
    if(currentIndex !== -1)
    {
        barreDeRecherche.value = document.getElementById("autocompletion").children[currentIndex];
    }
}

function completeInput(element)
{
    barreDeRecherche.value = element.innerText;
}



