$(document).ready(function () {
  var app = $.spapp({
      defaultView: "#home",
      templateDir: "/EmirGasi/Project-Introduction-to-Web-Programming/frontend/html/"
  });
  app.run();

  $(document).on('click', '.order-type-option', function() {
      $('.order-type-option').removeClass('active');
      $(this).addClass('active');
  });

  // **1. Učitaj stavke iz localStorage-a**
  let cartItems = JSON.parse(localStorage.getItem("cart")) || [];

  // **2. Selektujemo elemente**
  const cartButtons = document.querySelectorAll(".Cart-Button");
  const cartDropdown = document.querySelector("#cartDropdown + .dropdown-menu");
  const cartCount = document.querySelector("#cart-count"); // Broj stavki u korpi

  // **3. Funkcija za dodavanje proizvoda u korpu**
  function addToCart(event) {
      const dishBox = event.target.closest(".dish-box");
      const itemName = dishBox.querySelector("h3").textContent;
      const itemPrice = dishBox.querySelector(".dish-price").textContent;

      // **Ako proizvod već postoji, povećaj količinu**
      const existingItem = cartItems.find(item => item.name === itemName);
      if (existingItem) {
          existingItem.quantity++;
      } else {
          cartItems.push({ name: itemName, price: itemPrice, quantity: 1 });
      }

      // **Sačuvaj u localStorage**
      localStorage.setItem("cart", JSON.stringify(cartItems));

      updateCartDropdown();
  }

  // **4. Ažurira prikaz u cart dropdown meniju**
  function updateCartDropdown() {
      cartDropdown.innerHTML = ""; // Brišemo prethodne stavke

      if (cartItems.length === 0) {
          cartDropdown.innerHTML = `<li><span class="dropdown-item">Your Cart is Empty</span></li>`;
          cartCount.textContent = "(0)"; // Resetuje broj ako je prazno
          return;
      }

      let totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
      cartCount.textContent = `(${totalItems})`; // Ažurira broj proizvoda pored ikonice

      cartItems.forEach(item => {
          const cartItem = document.createElement("li");
          cartItem.innerHTML = `<span class="dropdown-item">${item.name} (${item.quantity}) - ${item.price}</span>`;
          cartDropdown.appendChild(cartItem);
      });
  }

  // **5. Dodaj event listener na dugmad "Put in Cart"**
  cartButtons.forEach(button => {
      button.addEventListener("click", addToCart);
  });

  // **6. Kada korisnik klikne na cart ikonu, ažuriraj dropdown**
  document.querySelector("#cartDropdown").addEventListener("click", updateCartDropdown);

  // **7. Inicijalno ažuriraj korpu pri učitavanju stranice**
  updateCartDropdown();
});
