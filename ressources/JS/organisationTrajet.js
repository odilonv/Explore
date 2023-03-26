//à inserer dans le doc
//<div id="organisation">
// <div id="ajouterEtape"><img src="/plus"></div>
// </div>

let organisation = document.getElementById("organisation");
let bouttonAjouterEtape = document.getElementById("ajouterEtape");

let etapes;
let nbEtapes = 0;

bouttonAjouterEtape.addEventListener("click", ()=>{ajouterEtape()});


function ajouterEtape()
{
    let nouvelleEtape = document.createElement("div");
    nouvelleEtape.classList.add("etape");
    let iconeMoins = document.createElement("img");
    iconeMoins.src ="../ressources/img/moins.png";
    nouvelleEtape.appendChild(iconeMoins);
    nouvelleEtape.setAttribute("index",nbEtapes)
    nbEtapes++;

    iconeMoins.addEventListener("click",() => {retirerEtape(nouvelleEtape)})


    etapes =  document.getElementsByClassName("etape");

    organisation.appendChild(nouvelleEtape);
}

function retirerEtape(etapeARetirer)
{
    nbEtapes--;
    for(let etape of etapes)
    {
        if(etape.getAttribute("index") > etapeARetirer.getAttribute("index")){
            etape.setAttribute("index",etape.getAttribute("index")-1);
        }
    }

    etapeARetirer.remove();
}


