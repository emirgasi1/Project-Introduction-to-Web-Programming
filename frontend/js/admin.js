// Kad se promijeni hash (SPA navigacija)
window.addEventListener("hashchange", function() {
  let page = window.location.hash.substring(1); // uklanja #

  if (page === "admin") {
    // Pozovi s malim delay-em da budeš siguran da je HTML ubačen
    setTimeout(initAdminDashboard, 50);
  }
});

// Kad se stranica učita (prvi put, hard reload)
document.addEventListener('DOMContentLoaded', function() {
  console.log("DOMContentLoaded event fired");

  // Popuni user info u navbaru/dashboardu (ako treba)
  initUserInfo();

  // Ako je odmah na admin hash (reload na #admin)
  let currentPage = window.location.hash.substring(1);
  if (currentPage === "admin") {
    setTimeout(initAdminDashboard, 50);
  }
});

// Inicijalizacija admin panela i statičkih podataka
function initAdminDashboard() {
  console.log("initAdminDashboard called");

  initUserInfo(); // Popuni ime u admin panelu
  fetchStats();   // Učitaj statistiku

  // Logout handler (ukloni stare evente da ne dodaješ višestruko)
  $(".logout-link").off('click').on("click", function() {
    localStorage.clear();
    window.location.href = "#login";
  });
}

// Inicijalizacija korisničkih podataka (ime, role itd.)
function initUserInfo() {
  let userName = localStorage.getItem('user_name') || 'Admin';
  let userRole = localStorage.getItem('user_role') || '';
  console.log("initUserInfo called with userName:", userName, "and userRole:", userRole);

  if (!userName || !userRole) {
    // Ako nema username ili role u localStorage, redirektuj na login
    window.location.href = "#login";
    return;
  }

  if ($("#admin-name").length === 0) {
    console.warn("#admin-name element NOT found in DOM!");
  } else {
    $("#admin-name").text(userName); // Samo jednom!
  }
}

// Funkcija za dohvat statistike i prikaz u admin panelu
function fetchStats() {
  console.log("fetchStats called");

  RestClient.get('users', function(users) {
    console.log('Users:', users);
    $("#stat-users").text(users.length);
  }, function() {
    console.log('Failed to load users');
    $("#stat-users").text('N/A');
  });

  RestClient.get('orders', function(orders) {
    console.log('Orders:', orders);
    $("#stat-orders").text(orders.length);
  }, function() {
    console.log('Failed to load orders');
    $("#stat-orders").text('N/A');
  });

  RestClient.get('products', function(products) {
    console.log('Products:', products);
    $("#stat-products").text(products.length);
  }, function() {
    console.log('Failed to load products');
    $("#stat-products").text('N/A');
  });
}