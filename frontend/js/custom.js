$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({pageNotFound : 'error_404'}); // initialize

  // define routes
  app.route({
    view: 'view_1',
    onCreate: function() { $("#view_1").append($.now()+': Written on create<br/>'); },
    onReady: function() { $("#view_1").append($.now()+': Written when ready<br/>'); }
  });
  app.route({view: 'view_2', load: 'view_2.html' });
  app.route({
    view: 'view_3', 
    onCreate: function() { $("#view_3").append("I'm the third view"); }
  });
  $(document).ready(function() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // Function to add product to the cart
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

        updateCart(); // Update the cart display
        localStorage.setItem("cart", JSON.stringify(cart)); // Save to localStorage
    }

    // Function to update the cart display
    function updateCart() {
        $('#cart-count').text(`(${cart.length})`);  // Update the number of products in the cart

        let cartItemsContainer = $('#cart-items');
        cartItemsContainer.empty(); // Clear current content

        if (cart.length === 0) {
            cartItemsContainer.append('<p>Your cart is empty.</p>');
        } else {
            cart.forEach(item => {
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

        // Event listener for increasing quantity
        $(document).on('click', '.increase-quantity', function() {
            let productId = $(this).data('id');
            let product = cart.find(item => item.id === productId);
            if (product) {
                product.quantity++;
                updateCart();
                localStorage.setItem("cart", JSON.stringify(cart));  // Save updated cart to localStorage
            }
        });

        // Event listener for decreasing quantity
        $(document).on('click', '.decrease-quantity', function() {
            let productId = $(this).data('id');
            let product = cart.find(item => item.id === productId);
            if (product && product.quantity > 1) {
                product.quantity--;
                updateCart();
                localStorage.setItem("cart", JSON.stringify(cart));  // Save updated cart to localStorage
            }
        });
    }

    // Fetch products from the backend
    function fetchProducts() {
        $.ajax({
            url: 'http://localhost/EmirGasi/Project-Introduction-to-Web-Programming/backend/products',
            method: 'GET',
            success: function(response) {
                displayProducts(response); // Display products in the menu
            },
            error: function(error) {
                console.error("Error fetching products", error);
            }
        });
    }

    // Function to display products in the menu
    function displayProducts(products) {
        let productList = $('#product-list');
        productList.empty(); // Clear current content

        products.forEach(product => {
            let productHtml = `
                <div class="menu-category">
                    <h2>${product.product_name}</h2>
                    <div class="dishes">
                        <div class="dish-box">
                            <h3>${product.product_name}</h3>
                            <p class="dish-description">${product.description}</p>
                            <p class="dish-price">$${product.price}</p>
                            <!-- Add to cart button -->
                            <button class="Cart-Button" data-id="${product.product_id}" data-name="${product.product_name}" data-price="${product.price}">Put in Cart</button>
                        </div>
                    </div>
                </div>
            `;
            productList.append(productHtml);  // Add product to the DOM
        });

        // Reinitialize functionality for adding to cart
        $('.Cart-Button').on('click', function() {
            let productId = $(this).data('id');
            let productName = $(this).data('name');
            let productPrice = $(this).data('price');
            addToCart(productId, productName, productPrice); // Call function to add to cart
        });
    }

    // Fetch products when the page loads
    fetchProducts();

    // Update the cart immediately on page load
    updateCart();
  
    // Run app
    app.run();
  });
  
});