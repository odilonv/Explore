//à inserer dans le doc
//<div id="organisation">
// <div id="ajouterEtape"><img src="/plus"></div>
// </div>

let organisation = document.getElementById("organisation");
let etapes = document.getElementById("ajouterEtape");


ajouterEtape.addEventListener("click", ajouterEtape());

let etapes;
let bouttonsRetirerEtape;
let nbEtapes = 0;
function ajouterEtape()
{
    let nouvelleEtape = document.createElement("div");
    nouvelleEtape.classList.add("etape");
    let bouttonMoins = document.createElement("div");
    let iconeMoins = document.createElement("img");
    iconeMoins.src = "src/";
    nouvelleEtape.appendChild(iconeMoins);
    bouttonMoins.index = nbEtapes;
    nbEtapes++;

    bouttonMoins.addEventListener("click",() => {retirerEtape(bouttonMoins)})


    etapes =  document.getElementsByClassName("etape");

    organisation.appendChild(nouvelleEtape);
}

function retirerEtape(etapeARetirer)
{
    let indexEtapeARetirer = etapeARetirer.index;
    for(let etape of etapes)
    {
        if(etape.index > indexEtapeARetirer){
            etape.index -= 1;
        }
    }
}


