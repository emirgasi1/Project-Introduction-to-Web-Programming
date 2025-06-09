const MenuService = {
    getAll: function(success, error) {
        $.ajax({
            url: '/EmirGasi/Project-Introduction-to-Web-Programming/backend/products',
            method: 'GET',
            headers: {
                "Authorization": localStorage.getItem("user_token")
            },
            success, error
        });
    },
    getById: function(id, success, error) {
        $.ajax({
            url: '/EmirGasi/Project-Introduction-to-Web-Programming/backend/products/' + id,
            method: 'GET',
            headers: {
                "Authorization": localStorage.getItem("user_token")
            },
            success, error
        });
    }
}
window.MenuService = MenuService;
