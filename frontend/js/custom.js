$(document).ready(function () {
  console.log("Custom JS script is running");

    $(document).on("spapp:afterLoad", function(e, page) {
    console.log("spapp:afterLoad event fired, page argument:", page);
    });


    // ===== SPA INIT =====
    $("main#spapp > section").height($(document).height() - 60);

    var app = $.spapp({ pageNotFound: 'error_404' });
    app.run();

    // ===== LOGIN HANDLER (SPA FRIENDLY) =====
    $(document).on("submit", "#loginForm", function (event) {
        event.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();

        $.ajax({
            url: 'http://localhost/EmirGasi/Project-Introduction-to-Web-Programming/backend/auth/login',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ email: email, password: password }),
            success: function (response) {
                if (response.data && response.data.token) {
                    // Ako je podaci u response.data, koristi to!
                    localStorage.setItem('user_token', response.data.token);
                    localStorage.setItem('user_role', response.data.role || response.data.user?.role); // fallback ako promijeniš backend!
                    localStorage.setItem('user_name', response.data.username || response.data.user?.username);
                    localStorage.setItem('user_email', response.data.email || response.data.user?.email);

                    updateNavbar();
                    alert('Login successful!');
                    window.location.href = '#dashboard';
                } else {
                    alert('Login failed: ' + (response.error || response.message || "Unknown error"));
                }
            },
            error: function (xhr) {
                alert('Login failed: ' + (xhr.responseJSON?.error || xhr.responseText || "Unknown error"));
            }
        });
    });

    // ===== SIGNUP HANDLER (SPA FRIENDLY, DELEGATION) =====
    $(document).on("submit", "#signupForm", function(e) {
        e.preventDefault();

        let username = $("#username").val();
        let email = $("#email").val();
        let password = $("#password").val();
        let confirmPassword = $("#confirm-password").val();

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        $.ajax({
            url: "http://localhost/EmirGasi/Project-Introduction-to-Web-Programming/backend/auth/register",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({ username, email, password }),
            success: function(response) {
                alert("Registration successful! Please log in.");
                window.location.href = "#login";
            },
            error: function(xhr) {
                alert("Signup failed: " + (xhr.responseJSON?.error || xhr.responseText || "Unknown error"));
            }
        });
    });

    // ===== ROLE-BASED NAVBAR UPDATE =====
    function updateNavbar() {
        const role = localStorage.getItem('user_role');
        
        if (role === "admin") {
            $(".admin-link, .dashboard-link, .profile-link, .logout-link").show();
            $(".login-link, .signup-link").hide();
        } else if (role === "user" || role === "customer") {
            $(".dashboard-link, .profile-link, .logout-link").show();
            $(".admin-link").hide();
            $(".login-link, .signup-link").hide();
        } else {
            $(".admin-link, .dashboard-link, .profile-link, .logout-link").hide();
            $(".login-link, .signup-link").show();
        }
    }


$(document).on("spapp:afterLoad", function (e, page) {
    // Dashboard
   if (page === "dashboard" || page === "#dashboard") {
    let username = localStorage.getItem('user_name');
    $("#welcome-message").text("Welcome, " + username + "!");
    DashboardService.getRecentOrders(function(orders) {
      let orderHistory = $("#order-history");
      if (orders.length > 0) {
        orderHistory.html('');
        orders.forEach(order => {
          orderHistory.append(`<p>Order ID: ${order.order_id} - ${order.status} - ${order.order_date}</p>`);
        });
      } else {
        orderHistory.html('<p class="text-muted">No orders yet. <a href="#menu" class="text-primary">Order now!</a></p>');
      }
    }, function() {
      $("#order-history").html('<p class="text-danger">Failed to load orders</p>');
    });
  }

    // Profile
    if (page === "#profile" || page === "profile") {
        let username = localStorage.getItem('user_name');
        let email = localStorage.getItem('user_email');
        let role = localStorage.getItem('user_role');
        $("#profile-username").text("Profile – " + username);
        $("#profile-user-name").text(username);
        $("#profile-user-email").text(email);
        $("#profile-user-role").text(role);
    }

   

      
});


    // ==== LOGOUT HANDLER ====
    $(document).on('click', '.logout-link', function(e) {
        e.preventDefault();
        localStorage.clear();
        updateNavbar();
        window.location.href = "#login";
    });

    // Pozovi odmah na load i nakon logina/logouda
    updateNavbar();
    // (Pozivaj i na svaku SPA promjenu ili gdje trebaš)
});

// ===== SPA VIEW USER INFO HANDLER =====
function fillUserInfoOnSPAView(viewName) {
    let username = localStorage.getItem('user_name');
    let email = localStorage.getItem('user_email');
    let role = localStorage.getItem('user_role');

    // Dashboard
    if (viewName === "dashboard") {
        $("#welcome-message").text("Welcome, " + (username || "User") + "!");
        $("#user-email").text(email || "");
        $("#user-role").text(role || "");
    }
    // Profile
    if (viewName === "profile") {
        $("#profile-name").text(username || "");
        $("#profile-email").text(email || "");
        $("#profile-role").text(role || "");
    }
    // Admin
    if (viewName === "admin") {
        $("#admin-name").text(username || "");
        $("#admin-email").text(email || "");
    }
}

// Prvi load odmah popuni info ako je hash već postavljen
const firstHash = window.location.hash.replace('#', '');
fillUserInfoOnSPAView(firstHash);

// Svaki put kad user promijeni stranicu (hash), update user info!
$(window).on("hashchange", function() {
    const hash = window.location.hash.replace('#', '');
    fillUserInfoOnSPAView(hash);
});




