/**
 *
 * @param  {H.Map} map      A HERE Map instance within the application
 */
function moveMapToFrance(map){
    map.setCenter({lat: 46.71109, lng: 1.7191036});
    map.setZoom(6.5);
}

/**
 * Boilerplate map initialization code starts below:
 */

//Step 1: initialize communication with the platform
const platform = new H.service.Platform({
    'apikey': 'xT0meObIDwRziElCoHGOgcuY1RT0AVfJQGG-HF8ZtDk'
});


let defaultLayers = platform.createDefaultLayers();

//Step 2: initialize a map - this map is centered over Europe
let map = new H.Map(document.getElementById('mapContainer'),
    defaultLayers.vector.normal.map,{
        center: {lat:50, lng:5},
        zoom: 4,
        pixelRatio: window.devicePixelRatio || 1
    });
// add a resize listener to make sure that the map occupies the whole container
window.addEventListener('resize', () => map.getViewPort().resize());


// Crée un événement pour le clic droit
map.addEventListener('rightclick', function (evt) {
    evt.preventDefault();
});

//Step 3: make the map interactive
// MapEvents enables the event system
// Behavior implements default interactions for pan/zoom (also on mobile touch environments)
let behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

// Create the default UI components
let ui = H.ui.UI.createDefault(map, defaultLayers, "fr-FR");
let mapsettings = ui.getControl('mapsettings');
let menuEntries = mapsettings.getChildren()[1].getChildren();
menuEntries[0].getElement().style.borderBottom = 'none';
for (let i=1; i<menuEntries.length; i++)
    menuEntries[i].setVisibility(false);
// Now use the map as required...
window.onload = function () {
    moveMapToFrance(map);
}