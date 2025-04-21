$(document).ready(function() {
    // Inicijalizacija korpe iz localStorage
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // Funkcija za dodavanje proizvoda u korpu
    function addToCart(productId, productName, productPrice) {
        let existingProduct = cart.find(item => item.id === productId);

        if (existingProduct) {
            existingProduct.quantity++;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: 1
            });
        }

        // Immediately update the cart after adding an item
        updateCart(); // Ažuriraj prikaz korpe
        localStorage.setItem("cart", JSON.stringify(cart)); // Spasi u localStorage
    }

    // Funkcija za ažuriranje prikaza korpe
    function updateCart() {
        $('#cart-count').text(`(${cart.length})`);  // Ažuriranje broja proizvoda u korpi
      
        let cartItemsContainer = $('#cart-items');
        cartItemsContainer.empty(); // Očisti trenutni sadržaj
      
        if (cart.length === 0) {
          cartItemsContainer.append('<p>Your cart is empty.</p>');
        } else {
          cart.forEach(item => {
            // Add product items with the increase and decrease buttons
            cartItemsContainer.append(`
              <div class="cart-item">
                <span class="cart-item-name">${item.name}</span>
                <span class="cart-item-quantity">
                  x${item.quantity}
                  <button class="increase-quantity" data-id="${item.id}">+</button>
                  <button class="decrease-quantity" data-id="${item.id}">-</button>
                </span>
                <span class="cart-item-price">$${(item.price * item.quantity).toFixed(2)}</span>
              </div>
            `);
          });
      
          let totalPrice = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
          $('#cart-total').text(`$${totalPrice.toFixed(2)}`);
        }
      
        // Delegate event for increase quantity button
        $(document).on('click', '.increase-quantity', function() {
          let productId = $(this).data('id');
          let product = cart.find(item => item.id === productId);
          if (product) {
            product.quantity++;
            updateCart();  // Re-render cart after update
            localStorage.setItem("cart", JSON.stringify(cart));  // Save updated cart to localStorage
          }
        });
      
        // Delegate event for decrease quantity button
        $(document).on('click', '.decrease-quantity', function() {
          let productId = $(this).data('id');
          let product = cart.find(item => item.id === productId);
          if (product && product.quantity > 1) {
            product.quantity--;
            updateCart();  // Re-render cart after update
            localStorage.setItem("cart", JSON.stringify(cart));  // Save updated cart to localStorage
          }
        });
      }

    // Funkcija za dohvat proizvoda
    function fetchProducts() {
        $.ajax({
            url: 'http://localhost/EmirGasi/Project-Introduction-to-Web-Programming/backend/products',  // Backend URL za proizvode
            method: 'GET',
            success: function(response) {
                displayProducts(response); // Prikazivanje proizvoda
            },
            error: function(error) {
                console.error("Error fetching products", error);
            }
        });
    }

    // Funkcija za prikaz proizvoda na stranici
    function displayProducts(products) {
        let productList = $('#product-list');
        productList.empty(); // Očisti trenutni sadržaj

        products.forEach(product => {
            let productHtml = `
                <div class="menu-category">
                    <h2>${product.product_name}</h2>
                    <div class="dishes">
                        <div class="dish-box">
                            <h3>${product.product_name}</h3>
                            <p class="dish-description">${product.description}</p>
                            <p class="dish-price">$${product.price}</p>
                            <!-- Dodaj dugme za dodavanje u korpu (samo na order.html stranici) -->
                            <button class="Cart-Button" data-id="${product.product_id}" data-name="${product.product_name}" data-price="${product.price}">Put in Cart</button>
                        </div>
                    </div>
                </div>
            `;
            productList.append(productHtml);  // Dodaj proizvod u DOM
        });

        // Ponovno inicijaliziraj funkcionalnost za dodavanje u korpu
        $('.Cart-Button').on('click', function() {
            let productId = $(this).data('id');
            let productName = $(this).data('name');
            let productPrice = $(this).data('price');
            addToCart(productId, productName, productPrice); // Pozivaj funkciju za dodavanje u korpu
        });
    }

    // Učitavanje proizvoda
    fetchProducts();

    // Ažuriraj korpu odmah nakon učitavanja stranice
    updateCart(); 

});
