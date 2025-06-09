// frontend/js/services/AuthService.js

const AuthService = {
    login: function(email, password, success, error) {
        $.ajax({
            url: "/EmirGasi/Project-Introduction-to-Web-Programming/backend/auth/login",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ email, password }),
            success: success,
            error: error
        });
    },
    register: function(username, email, password, success, error) {
        $.ajax({
            url: "/EmirGasi/Project-Introduction-to-Web-Programming/backend/auth/register",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ username, email, password }),
            success: success,
            error: error
        });
    }
};

window.AuthService = AuthService;
