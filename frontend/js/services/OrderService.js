// frontend/js/services/OrderService.js

const OrderService = {
    createOrder: function(orderData, success, error) {
        $.ajax({
            url: "/EmirGasi/Project-Introduction-to-Web-Programming/backend/orders",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(orderData),
            headers: {
                "Authentication": localStorage.getItem("user_token")
            },
            success: success,
            error: error
        });
    },
    getMyOrders: function(success, error) {
        $.ajax({
            url: "/EmirGasi/Project-Introduction-to-Web-Programming/backend/orders",
            type: "GET",
            headers: {
                "Authentication": localStorage.getItem("user_token")
            },
            success: success,
            error: error
        });
    }
};

window.OrderService = OrderService;
