console.log("Profile JS loaded");
function initProfile() {
  let userName = localStorage.getItem('user_name') || 'User';
  let userEmail = localStorage.getItem('user_email') || '';
  let userRole = localStorage.getItem('user_role') || '';

  $("#profile-name").text(userName);
  $("#profile-email").text(userEmail);
  $("#profile-role").text(userRole);

  $(".logout-link").off('click').on("click", function() {
    localStorage.clear();
    window.location.href = "#login";
  });
}

window.addEventListener("hashchange", function() {
  let page = window.location.hash.substring(1);
  if (page === "profile") {
    // čekaj dok element ne postoji, pa pozovi init
    let checkExist = setInterval(() => {
      if ($("#profile-name").length) {
        initProfile();
        clearInterval(checkExist);
      }
    }, 50);
  }
});


document.addEventListener('DOMContentLoaded', function() {
  if (window.location.hash.substring(1) === "profile") {
     setTimeout(initProfile, 100);
    initProfile();
  }
});
