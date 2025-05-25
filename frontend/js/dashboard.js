console.log("Dashboard JS loaded");

function initDashboard() {
  let userName = localStorage.getItem('user_name') || 'User';
  let userEmail = localStorage.getItem('user_email') || '';
  let userRole = localStorage.getItem('user_role') || '';

  // Prikaži korisničke informacije
  $("#welcome-message").text("Welcome, " + userName + "!");
  $("#user-email").text(userEmail);
  $("#user-role").text(userRole);

  // Dohvati i prikaži nedavne narudžbe
  fetchRecentOrders();

  // Logout handler
  $(".logout-link").off('click').on("click", function() {
    localStorage.clear();
    window.location.href = "#login";
  });
}

function fetchRecentOrders() {
  RestClient.get("orders", function(orders) {
    console.log("Orders response:", orders);  // Dodaj ovaj log

    if (!Array.isArray(orders)) {
      console.error("Orders response is not an array!", orders);
      $("#order-history").html('<p class="text-danger">Failed to load orders: unexpected response format</p>');
      return;
    }

    let container = $("#order-history");
    container.empty();

    if (orders.length === 0) {
      container.html('<p class="text-muted">No orders yet. <a href="#menu" class="text-primary">Order now!</a></p>');
      return;
    }

    orders.forEach(order => {
      let orderHTML = `<p>Order ID: ${order.order_id} - Status: ${order.status} - Date: ${order.order_date}</p>`;
      container.append(orderHTML);
    });
  }, function() {
    $("#order-history").html('<p class="text-danger">Failed to load orders</p>');
  });
}

// Event listener za promjenu URL hasha
window.addEventListener("hashchange", function() {
  let page = window.location.hash.substring(1);
  if (page === "dashboard") {
    // Malo kašnjenje da se content učita prije inicijalizacije
    setTimeout(initDashboard, 100);
  }
});

// Učitaj dashboard ako je hash #dashboard prilikom reload-a
document.addEventListener('DOMContentLoaded', function() {
  if (window.location.hash.substring(1) === "dashboard") {
    setTimeout(initDashboard, 100);
  }
});
