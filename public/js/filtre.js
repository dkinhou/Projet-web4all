const modal = document.getElementById("filterModal");
const btn = document.getElementById("openFilter");
const span = document.getElementsByClassName("close")[0];

if (modal && btn && span) {
    btn.onclick = function() {
        modal.style.display = "block";
    };

    span.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
}

const hamburgerBtn = document.getElementById("hamburgerBtn");
const mainNavLinks = document.getElementById("mainNavLinks");

if (hamburgerBtn && mainNavLinks) {
    hamburgerBtn.addEventListener("click", function() {
        mainNavLinks.classList.toggle("is-open");
        hamburgerBtn.classList.toggle("is-open");
    });
}