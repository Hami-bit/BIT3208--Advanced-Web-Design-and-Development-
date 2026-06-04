// Week 2 - main.js - NexaBank Basic Scripts
// GUI layout and basic interactivity only (Week 3 adds full validation)

console.log("NexaBank JS loaded - Week 2");

// Highlight active nav link
document.addEventListener("DOMContentLoaded", function () {
    const currentPage = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll(".nav-links a");
    navLinks.forEach(function (link) {
        if (link.getAttribute("href") === currentPage) {
            link.style.background = "rgba(255,255,255,0.2)";
            link.style.color = "#fff";
        }
    });
});
