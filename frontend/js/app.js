$(document).ready(function () {
$(document).on("spapp:afterLoad", function(e, page) {
  console.log("spapp:afterLoad event fired, page argument:", page);
  if (page === "admin") {
    initAdminDashboard();
  }
});
    var app = $.spapp({
        defaultView: "#home",
        templateDir: "/EmirGasi/Project-Introduction-to-Web-Programming/frontend/html/"
    });
    app.run();

    

});