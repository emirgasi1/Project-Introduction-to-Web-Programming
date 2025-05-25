let AdminService = {
  getStats: function(callback, error_callback) {
    $.when(
      RestClient.get("users"),
      RestClient.get("orders"),
      RestClient.get("products")
    ).done(function(usersResp, ordersResp, productsResp) {
      callback({
        usersCount: usersResp[0].length,
        ordersCount: ordersResp[0].length,
        productsCount: productsResp[0].length,
      });
    }).fail(function() {
      if (error_callback) error_callback();
    });
  },

  getUsers: function(callback, error_callback) {
    RestClient.get("users", callback, error_callback);
  },

  getOrders: function(callback, error_callback) {
    RestClient.get("orders", callback, error_callback);
  },

  getProducts: function(callback, error_callback) {
    RestClient.get("products", callback, error_callback);
  },

  // Implementiraj add/edit/delete po potrebi
};
