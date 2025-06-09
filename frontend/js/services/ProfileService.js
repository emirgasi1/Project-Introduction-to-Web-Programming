// frontend/js/services/ProfileService.js

const ProfileService = {
    getProfile: function(success, error) {
        $.ajax({
            url: "/EmirGasi/Project-Introduction-to-Web-Programming/backend/users/profile",
            type: "GET",
            headers: {
                "Authentication": localStorage.getItem("user_token")
            },
            success: success,
            error: error
        });
    },
    updateProfile: function(data, success, error) {
        $.ajax({
            url: "/EmirGasi/Project-Introduction-to-Web-Programming/backend/users/profile",
            type: "PUT",
            contentType: "application/json",
            headers: {
                "Authentication": localStorage.getItem("user_token")
            },
            data: JSON.stringify(data),
            success: success,
            error: error
        });
    }
};

window.ProfileService = ProfileService;
