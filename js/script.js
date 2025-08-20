document.addEventListener("DOMContentLoaded", () => {
    // Navbar Toggle
    const navToggle = document.getElementById("nav-toggle");
    const navbar = document.querySelector(".navbar");

    if (navToggle && navbar) {
        navToggle.addEventListener("click", () => {
            navbar.classList.toggle("active");
        });
    }

    // Section Animation on Scroll
    const sections = document.querySelectorAll(".section");

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target); // Stop observing once it's visible
            }
        });
    }, { threshold: 0.2 });

    sections.forEach(section => {
        observer.observe(section);
    });
});

