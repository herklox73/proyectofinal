document.addEventListener("DOMContentLoaded", function () {
  // Manejar el cambio de color de la navbar al hacer scroll
  window.addEventListener("scroll", function () {
    var navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
      navbar.classList.add("navbar-scrolled");
    } else {
      navbar.classList.remove("navbar-scrolled");
    }

    // Mostrar elementos con clase 'fade-in' al hacer scroll
    let fadeInElements = document.querySelectorAll(".fade-in");
    let screenHeight = window.innerHeight;

    fadeInElements.forEach((element) => {
      let position = element.getBoundingClientRect().top;
      if (position < screenHeight * 0.9) {
        element.classList.add("show");
      }
    });

    // Mostrar elementos con clase 'fade-in-left' y 'fade-in-right' al hacer scroll
    let fadeInSides = document.querySelectorAll(
      ".fade-in-left, .fade-in-right"
    );

    fadeInSides.forEach((element) => {
      let position = element.getBoundingClientRect().top;
      if (position < screenHeight - 100) {
        element.classList.add("show");
      }
    });
  });
});
