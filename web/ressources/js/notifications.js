const msgButton = document.getElementById('form')
const notification = document.querySelector(".messageFlash");

msgButton.addEventListener("submit", () => {
    notification.classList.remove("notification-hidden");
    notification.classList.add("notification-show");
});


// On l'efface 10 secondes plus tard
setTimeout(() => {
    notification.classList.remove('notification-show');
    notification.classList.add('notification-hidden');
},10000);
