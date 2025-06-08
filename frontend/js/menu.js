console.log("user_token in menu.js", localStorage.getItem("user_token"));

function loadMenuProducts() {
    // 1. Prvo provjeri i ispiši token
    const token = localStorage.getItem("user_token");

    // 2. Ako token ne postoji, ispiši poruku i prekini
    if (!token) {
        $("#product-list").html('<p style="color:red;">You are not logged in! (No token)</p>');
        return;
    }

    // 3. Tek sad zovi backend!
    MenuService.getAll(
        function(products) {
            renderProducts(products);
        },
        function(err) {
            $("#product-list").html('<p style="color:red;">Failed to load products.</p>');
            console.error("PRODUCT ERROR:", err);
        }
    );
}


// Kada god se promijeni hash (SPA navigacija)
window.addEventListener("hashchange", function() {
    let page = window.location.hash.substring(1);
    if (page === "menu") {
        // Kratki delay je često potreban da DOM zaista bude ubačen!
        setTimeout(loadMenuProducts, 50);
    }
});

// Prvi put kad se stranica učita (hard reload)
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = window.location.hash.substring(1);
    if (currentPage === "menu") {
        setTimeout(loadMenuProducts, 50);
    }
});

// Render funkcija ista kao prije:
function renderProducts(products) {
    let html = '';
    products.forEach(product => {
        html += `
        <div class="product-card">
            <h3>${product.product_name}</h3>
            <p>${product.description || ''}</p>
            <p><strong>Price:</strong> $${product.price}</p>
            ${product.image_url ? `<img src="${product.image_url}" alt="product image" style="max-width:150px;">` : ''}
        </div>
        `;
    });
    $("#product-list").html(html);
}
