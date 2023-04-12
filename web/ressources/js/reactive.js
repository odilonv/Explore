let objectByName = new Map();
let registeredEffects = []; // l'ensemble des effets "réactifs"


function startReactiveDom(){
    for (let elementClickable of document.querySelectorAll("[data-onclick]")){
        const [nomObjet, methode, argument] = elementClickable.dataset.onclick.split(/[.()]+/);
        elementClickable.addEventListener('click', (event) => {
            const objet = objectByName.get(nomObjet);
            objet[methode](argument);
        })
    }
    for (let rel of document.querySelectorAll("[data-textfun]")){
        const [obj, fun, arg] = rel.dataset.textfun.split(/[.()]+/);
        reactive(objectByName.get(obj), obj);
        applyAndRegister(()=>{rel.textContent = objectByName.get(obj)[fun](arg)});
    }
    for (let rel of document.querySelectorAll("[data-textvar]")){
        const [obj, prop] = rel.dataset.textvar.split('.');
        applyAndRegister(()=>{rel.textContent = objectByName.get(obj)[prop]});
    }

}


function applyAndRegister(effect){
    effect();
    registeredEffects.push(effect);
}

function trigger(){
    for (let effect of registeredEffects){
        effect();
    }
}
window.trigger = trigger;

function reactive(passiveObject, name) {
    // 1. crée un reactiveObject à partir de passiveObject: son rôle est d'appeler trigger pour
    //    appliquer tous les effets réactifs enregistrés dès qu'il est modifié ;
    // 2. enregistre reactiveObject sous le nom name dans objectByName ;
    // 3. renvoie reactiveObject.

    const handler ={
        set(target, key, value){
            target[key] = value;
            trigger();
            return true;
        },
    };

    let reactiveObject = new Proxy(passiveObject, handler);
    objectByName.set(name, reactiveObject);

    return reactiveObject;
}

export {applyAndRegister, reactive, startReactiveDom};
