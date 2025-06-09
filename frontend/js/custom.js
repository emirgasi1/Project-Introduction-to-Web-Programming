$(document).ready(function () {
  console.log("Custom JS script is running");

    $(document).on("spapp:afterLoad", function(e, page) {
    console.log("spapp:afterLoad event fired, page argument:", page);
    });


    $("main#spapp > section").height($(document).height() - 60);

    var app = $.spapp({ pageNotFound: 'error_404' });
    app.run();

    //LOGIN HANDLER 
    // LOGIN HANDLER (frontend validacija)
$(document).on("submit", "#loginForm", function (event) {
    event.preventDefault();

    let email = $("#login-email").val().trim();
    let password = $("#login-password").val();

    // Email regex
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Frontend validacija
    if (!email || !emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }
    if (!password || password.length < 6) {
        alert("Password is required (min 6 characters).");
        return;
    }

    $.ajax({
        url: 'http://localhost/EmirGasi/Project-Introduction-to-Web-Programming/backend/auth/login',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ email: email, password: password }),
        success: function (response) {
            if (response.data && response.data.token) {
                localStorage.setItem('user_token', response.data.token);
                localStorage.setItem('user_role', response.data.role || response.data.user?.role); 
                localStorage.setItem('user_name', response.data.username || response.data.user?.username);
                localStorage.setItem('user_email', response.data.email || response.data.user?.email);
                localStorage.setItem('user_id', response.data.user_id);

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

// SIGNUP HANDLER (frontend validacija)
$(document).on("submit", "#signupForm", function(e) {
    e.preventDefault();
    let username = $("#signup-username").val().trim();
    let email = $("#signup-email").val().trim();
    let password = $("#signup-password").val();
    let confirmPassword = $("#signup-confirm-password").val();

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!username || username.length < 3) {
        alert("Username is required (min 3 characters).");
        return;
    }
    if (!email || !emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }
    if (!password || password.length < 6) {
        alert("Password is required (min 6 characters).");
        return;
    }
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




    //ROLE-BASED NAVBAR UPDATE
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


    //LOGOUT HANDLER 
    $(document).on('click', '.logout-link', function(e) {
        localStorage.clear();
        e.preventDefault();
        updateNavbar();
        window.location.href = "#login";
    });

    updateNavbar();
});

//SPA VIEW USER INFO HANDLER
function fillUserInfoOnSPAView(viewName) {
    let username = localStorage.getItem('user_name');
    let email = localStorage.getItem('user_email');
    let role = localStorage.getItem('user_role');

    if (viewName === "dashboard") {
        $("#welcome-message").text("Welcome, " + (username || "User") + "!");
        $("#user-email").text(email || "");
        $("#user-role").text(role || "");
    }
    if (viewName === "profile") {
        $("#profile-name").text(username || "");
        $("#profile-email").text(email || "");
        $("#profile-role").text(role || "");
    }
    if (viewName === "admin") {
        $("#admin-name").text(username || "");
        $("#admin-email").text(email || "");
    }
}

const firstHash = window.location.hash.replace('#', '');
fillUserInfoOnSPAView(firstHash);

$(window).on("hashchange", function() {
    const hash = window.location.hash.replace('#', '');
    fillUserInfoOnSPAView(hash);
});



