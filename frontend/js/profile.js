console.log("Profile JS loaded");

function initProfile() {
  let userName = localStorage.getItem('user_name') || 'User';
  let userEmail = localStorage.getItem('user_email') || '';
  let userRole = localStorage.getItem('user_role') || '';

  $("#profile-name").text(userName);
  $("#profile-email").text(userEmail);
  $("#profile-role").text(userRole);

  // Hide form, show display
  $("#profile-edit-form").hide();
  $("#profile-display").show();

  $(".logout-link").off('click').on("click", function() {
    localStorage.clear();
    window.location.href = "#login";
  });
}

// Event for "Edit Profile" button
$(document).on("click", "#edit-profile-btn", function() {
  // Popuni formu postojećim podacima
  $("#edit-profile-name").val(localStorage.getItem('user_name'));
  $("#edit-profile-email").val(localStorage.getItem('user_email'));

  $("#profile-display").hide();
  $("#profile-edit-form").show();
});

// Event for "Cancel" button
$(document).on("click", "#cancel-edit-btn", function() {
  $("#profile-edit-form").hide();
  $("#profile-display").show();
});

// Submit edit forme
$(document).on("submit", "#profile-edit-form", function(e) {
  e.preventDefault();
  let newName = $("#edit-profile-name").val();
  let newEmail = $("#edit-profile-email").val();

  // Pozovi API za update user-a
  // Pretpostavka: user_id je spremljen u localStorage
  let userId = localStorage.getItem('user_id');
  if (!userId) { alert("No user ID!"); return; }

  RestClient.put("users/" + userId, { username: newName, email: newEmail }, function(response) {
    // Refresh localStorage i profil
    localStorage.setItem('user_name', newName);
    localStorage.setItem('user_email', newEmail);
    initProfile();
    alert("Profile updated!");
  }, function(xhr) {
    alert("Failed to update profile: " + (xhr.responseJSON?.error || xhr.responseText));
  });
});

// Event za hashchange/profile reload
window.addEventListener("hashchange", function() {
  let page = window.location.hash.substring(1);
  if (page === "profile") {
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
