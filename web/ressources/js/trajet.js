const buttonSearch = document.getElementById('searchButton');
const loader = document.getElementById('loader');

buttonSearch.addEventListener('click', search2);

let URLs = [];
async function combineRoads(){
    let points = [];
    try {
        let distance = 0;
        for (let i in URLs) {
            const u = URLs[i];
            const reponse = await fetch(u);
            let data = await reponse.json();

            if(!reponse.ok){
                console.log(reponse);
                throw new Error(reponse.status + ": " + data.error);
            }

            distance += data.distance;
            const lines = data.multiline;
            points = lines.concat(points);
            addMarker(map, points[0].lat, points[0].lng);
        }
        addMarker(map, points[points.length - 1].lat, points[points.length - 1].lng);
        points.pop();
        addRoad(map, points);
        notif('success', `La distance pour ce trajet est de ${distance}km.`);
    }
    catch (error) {
        notif('danger', error.message);
    }
    loader.style.display = 'none';
}

function search2(){
    // Suppression de tous les objets de la carte
    URLs = [];
    map.removeObjects(map.getObjects());

    loader.style.display = 'block'; // Afficher le loader au début de la recherche

    let villes = document.querySelectorAll(".inputVille");
    for(let i=1; i<villes.length; i++) {
        let depart = villes[i - 1].value;
        let arrivee = villes[i].value;


        let requete = new URL(`api/getPlusCourt/${depart}/${arrivee}`, document.baseURI);
        URLs.push(requete.href);
    }
    combineRoads();
}

function addMarker(map, lat, lng){
    const mark = new H.map.Marker({lat: lat, lng:lng});
    map.addObject(mark);
}

function addRoad(map, points){
    let lineString = new H.geo.LineString();

    points.forEach(point => {
        lineString.pushPoint(point);
    });

    let polyline = new H.map.Polyline(lineString, { style: {lineWidth: 5}});
    map.addObject(polyline);

    map.getViewModel().setLookAtData({bounds: polyline.getBoundingBox()});
}