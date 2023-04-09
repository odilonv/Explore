
const notification = document.getElementById("notif");

searchButton.addEventListener('click',notif);
function notif(message){
    notification.classList.remove("notification-hidden");
    document.querySelector('.messageFlash > p').textContent=message;
    notification.classList.add("notification-show");
    // On l'efface 10 secondes plus tard
    setTimeout(() => {
        notification.classList.add('notification-hidden');
        notification.classList.remove('notification-show');

    },5000);

}

