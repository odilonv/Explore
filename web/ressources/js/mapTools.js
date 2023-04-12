import {applyAndRegister, reactive, startReactiveDom} from "./reactive.js";

let mapTools = reactive({
    clickedCity:"",
    mousePos:[0,0],
    mouseCoord:async function () {
        var coord = map.screenToGeo(this.mousePos[0], this.mousePos[1]);

        let requete = new URL(`api/getNear/${coord.lat}/${coord.lng}`, document.baseURI);
        let data = await( await fetch(requete.href)).json();//).then(data => console.log(data));

        this.clickedCity = data.nomCommune;
    },
    registerToClipboard:function (){
        navigator.clipboard.writeText(this.clickedCity);
    }
}, "mapTools");

window.addEventListener("mousemove", function(event) {
    mapTools.mousePos = [event.pageX, event.pageY];
})

startReactiveDom();