
const notification = document.getElementById("notif");
let exitCrossNotif = document.getElementById("icon-exit-notif")

exitCrossNotif.addEventListener('click', () =>{
    notification.classList.add('notification-hidden');
    notification.classList.remove('notification-show');
})

// searchButton.addEventListener('click',notif);
function notif(type, message){
    notification.classList.remove("notification-hidden");
    document.querySelector('.messageFlash div > p').textContent=message;
    document.getElementById("imgnotif").src='../web/ressources/img/icons/'+ type +'-solid.svg';
    notification.classList.add("notification-show");
    // On l'efface 20 secondes plus tard
    setTimeout(() => {
        notification.classList.add('notification-hidden');
        notification.classList.remove('notification-show');

    },20000);
}

