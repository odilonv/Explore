const buttonSearch = document.getElementById('searchButton');
const loader = document.getElementById('loader');

buttonSearch.addEventListener('click', search);


function search() {
    // Suppression de tous les objets de la carte
    map.removeObjects(map.getObjects());

    loader.style.display = 'block'; // Afficher le loader au début de la recherche
    let cptvilles=0;
    let distanceMsg=0;
    let departMsg = document.getElementById('ville1').value;
    let villes = document.querySelectorAll(".inputVille");
    let arriveeMsg = document.getElementById('ville'+villes.length).value;


    for(let i=1; i<villes.length; i++){
        let depart = villes[i-1].value;
        let arrivee = villes[i].value;


        let requete = new URL(`api/getPlusCourt/${depart}/${arrivee}`, document.baseURI);
        fetch(requete.href)
            .then(response => response.json())
            .then(data => {
                addRoad(map, data.multiline);
                cptvilles++;

                if(cptvilles===villes.length-1){
                    distanceMsg+=data.distance;
                    notif('success',"Le plus court chemin entre "+departMsg+" et "+arriveeMsg+" mesure "+ Math.round(distanceMsg) +" km.");
                    loader.style.display = 'none'; // Masquer le loader une fois que addRoad est terminée
                }
                else {
                    distanceMsg+=data.distance;
                }
            })
            .catch(error => {
                notif('danger',"Veuillez renseigner les champs avec des données valides.");
                loader.style.display = 'none'; // Masquer le loader en cas d'erreur
            });
    }
}

function addRoad(map, points){
    let lineString = new H.geo.LineString();

    points.forEach(point => {
        lineString.pushPoint(point);
    });

    let polyline = new H.map.Polyline(lineString, { style: {lineWidth: 5}});
    map.addObject(polyline);

    let startMarker = new H.map.Marker({lat: points[0].lat, lng: points[0].lng});
    let endMarker = new H.map.Marker({lat: points[points.length - 1].lat, lng: points[points.length - 1].lng});
    map.addObjects([startMarker, endMarker]);

    map.getViewModel().setLookAtData({bounds: polyline.getBoundingBox()});
}


