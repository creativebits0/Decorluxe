// Message alert
// Message alert auto fade
setTimeout(() => {
  let successMsg = document.getElementById('success-message');
  let alertMsg = document.getElementById('alert-message');

  function fadeOut(el) {
    if (!el) return;

    el.style.transition = "opacity 0.5s ease-out";
    el.style.opacity = "0";

    setTimeout(() => {
      el.remove();
    }, 500); 
  }

  if (successMsg) {
    fadeOut(successMsg);
  }

  if (alertMsg) {
    fadeOut(alertMsg);
  }

}, 3000);


// toggle password (visibility icon)
function togglePassword() {
  const passwordInput = document.getElementById("password");
  const toggleIcon = document.getElementById("toggleIcon");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIcon.innerHTML = "<i class='material-icons'>visibility_off</i>";
  } else {
    passwordInput.type = "password";
    toggleIcon.innerHTML = "<i class='material-icons'>visibility</i>";
  }
}
// ✅ Universal Close Function
function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

// ✅ Open Add User Modal
function openAddUser() {
  const modal = document.getElementById("addUserModal");
  const content = document.getElementById("addUserContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch("/decorluxe/presentation/admin/users/add_user_form.php")
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
}

// ✅ Open Edit User Modal
function editUser(id) {
  const modal = document.getElementById("editUserModal");
  const content = document.getElementById("editUserContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch(`/decorluxe/presentation/admin/users/edit_user_form.php?id=${id}`)
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
}

// ✅ Open View User Modal
function viewUser(id) {
  const modal = document.getElementById("viewUserModal");
  const content = document.getElementById("viewUserContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch(`/decorluxe/presentation/admin/users/view_user.php?id=${id}`)
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
}

// client Management --------------------------------

// ✅ Open Add Client Modal
function openAddClient() {
  const modal = document.getElementById("addClientModal");
  const content = document.getElementById("addClientContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch("/decorluxe/presentation/admin/clients/add_client_form.php")
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
}

// ✅ Open Edit Client Modal
function editClient(id) {
  const modal = document.getElementById("editClientModal");
  const content = document.getElementById("editClientContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch(`/decorluxe/presentation/admin/clients/edit_client_form.php?id=${id}`)
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
}

// ✅ Open View Client Modal
function viewClient(id) {
  const modal = document.getElementById("viewClientModal");
  const content = document.getElementById("viewClientContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch(`/decorluxe/presentation/admin/clients/view_client_form.php?id=${id}`)
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
}



// ✅ Open Add Service Modal
function openAddService() {
  const modal = document.getElementById("addServiceModal");
  const content = document.getElementById("addServiceContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch("/decorluxe/presentation/admin/services/add_service_form.php")
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
}

// INVENTORY MODAL-------------------------------
// ✅ Open Add Item Modal
function openAddItem() {
  const modal = document.getElementById("addItemModal");
  const content = document.getElementById("addItemContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch("/decorluxe/presentation/admin/inventory/add_item_form.php")
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
}

// ✅ Open Edit Item Modal
function editItem(id) {
  const modal = document.getElementById("editItemModal");
  const content = document.getElementById("editItemContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch(`/decorluxe/presentation/admin/inventory/edit_item_form.php?id=${id}`)
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
}

// ✅ Open View Item Modal
function viewItem(id) {
  const modal = document.getElementById("viewItemModal");
  const content = document.getElementById("viewItemContent");
  modal.style.display = "block";
  content.innerHTML = "<p class='text-center'>Loading...</p>";

  fetch(`/decorluxe/presentation/admin/inventory/view_item_form.php?id=${id}`)
    .then(res => res.text())
    .then(html => content.innerHTML = html)
    .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
}

function deleteItemImage(id) {
  if (!confirm("Are you sure you want to delete this image?")) return;

  fetch(`/decorluxe/business/admin/inventory/delete_item_image.php`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `id=${id}`
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.success) {
        // reload edit modal after deletion
        editItem(id);
      }
    })
    .catch(() => alert("Error deleting image"));
}

function openProfileEdit(id) {

  const modal =
    document.getElementById("editUserModal");

  const content =
    document.getElementById("editUserContent");

  modal.style.display = "block";

  content.innerHTML =
    "<p class='text-center'>Loading...</p>";

  fetch(`/decorluxe/presentation/admin/users/edit_user_form.php?id=${id}`)

    .then(res => res.text())

    .then(html => {
      content.innerHTML = html;
    })

    .catch(() => {

      content.innerHTML =
        "<p class='text-danger'>Failed to load user.</p>";
    });
}
