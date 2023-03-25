/**
 * Moves the map to display over Berlin
 *
 * @param  {H.Map} map      A HERE Map instance within the application
 */
function moveMapToMontpellier(map){
    map.setCenter({lat:43.610769, lng:3.876716});
    map.setZoom(14);
}

/**
 * Boilerplate map initialization code starts below:
 */

//Step 1: initialize communication with the platform
// In your own code, replace variable window.apikey with your own apikey
var platform = new H.service.Platform({
    'apikey': 'xT0meObIDwRziElCoHGOgcuY1RT0AVfJQGG-HF8ZtDk'
});
var defaultLayers = platform.createDefaultLayers();

//Step 2: initialize a map - this map is centered over Europe
var map = new H.Map(document.getElementById('mapContainer'),
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
var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

// Create the default UI components
var ui = H.ui.UI.createDefault(map, defaultLayers);

// Now use the map as required...
window.onload = function () {
    moveMapToMontpellier(map);
}