
// document.addEventListener("DOMContentLoaded", function () {
//     const navLinks = document.querySelectorAll("#sidebar .nav-link");
//     const currentPath = window.location.pathname;

//     navLinks.forEach(link => {
//         if (link.getAttribute("href") === currentPath) {
//             link.classList.add("active");
//         }

//         link.addEventListener("click", function (e) {
//             navLinks.forEach(l => l.classList.remove("active"));
//             this.classList.add("active");
//         });
//     });
// });


// document.addEventListener("DOMContentLoaded", function () {

//     const dropdownButtons = document.querySelectorAll(".sidebar-dropdown-btn");

//     dropdownButtons.forEach(btn => {
//         btn.addEventListener("click", function (e) {
//             e.preventDefault();

//             const parentLi = this.closest("li");
//             const dropdownMenu = parentLi.querySelector(".sidebar-dropdown-menu");

//             // Close other open dropdowns
//             document.querySelectorAll(".sidebar-dropdown-menu").forEach(menu => {
//                 if (menu !== dropdownMenu) {
//                     menu.style.display = "none";
//                 }
//             });

//             // Toggle current dropdown
//             if (dropdownMenu.style.display === "block") {
//                 dropdownMenu.style.display = "none";
//             } else {
//                 dropdownMenu.style.display = "block";
//             }
//         });
//     });

// });

document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname;

    // HANDLE ACTIVE LINKS AND KEEP DROPDOWNS OPEN ON LOAD
    const allLinks = document.querySelectorAll("#sidebar a");

    allLinks.forEach(link => {
        const linkHref = link.getAttribute("href");

        if (linkHref && currentPath.includes(linkHref)) {
            // Remove 'active' from any default link and add to current
            document.querySelectorAll("#sidebar .nav-link").forEach(l => l.classList.remove("active"));

            if (link.classList.contains('nav-link')) {
                link.classList.add("active");
            } else if (link.classList.contains('dropdown-item')) {
                link.classList.add("active");

                // Travel up to find the parent dropdown and force it open
                const parentDropdown = link.closest(".sidebar-dropdown-menu");
                if (parentDropdown) {
                    parentDropdown.style.display = "block";

                    // Also make the parent main button look active
                    const parentBtn = parentDropdown.closest(".sidebar-item").querySelector(".sidebar-dropdown-btn");
                    if (parentBtn) parentBtn.classList.add("active");
                }
            }
        }
    });

    // TOGGLE DROPDOWNS ON CLICK
    const dropdownButtons = document.querySelectorAll(".sidebar-dropdown-btn");

    dropdownButtons.forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            const parentLi = this.closest("li");
            const dropdownMenu = parentLi.querySelector(".sidebar-dropdown-menu");

            // Close other open dropdowns
            document.querySelectorAll(".sidebar-dropdown-menu").forEach(menu => {
                if (menu !== dropdownMenu) {
                    menu.style.display = "none";
                }
            });

            // Toggle current dropdown
            if (dropdownMenu.style.display === "block") {
                dropdownMenu.style.display = "none";
            } else {
                dropdownMenu.style.display = "block";
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {

    const btn = document.getElementById("notificationBtn");
    const panel = document.getElementById("notificationPanel");

    btn.addEventListener("click", (e) => {

        e.preventDefault();

        panel.style.display =
            panel.style.display === "block"
                ? "none"
                : "block";
    });

    document.addEventListener("click", (e) => {

        if (
            !btn.contains(e.target) &&
            !panel.contains(e.target)
        ) {
            panel.style.display = "none";
        }

    });

});

document.addEventListener("click", function(e) {

    if (e.target.classList.contains("view-notification")) {

        e.preventDefault();

        const id = e.target.dataset.id;

        const modal =
            document.getElementById("notificationModal");

        const content =
            document.getElementById("notificationContent");

        modal.style.display = "block";

        content.innerHTML =
            "<p class='text-center'>Loading...</p>";

        fetch(
            `/decorluxe/presentation/admin/notifications/view_notification.php?id=${id}`
        )
        .then(res => res.text())
        .then(html => {

            content.innerHTML = html;

        })
        .catch(() => {

            content.innerHTML =
                "<p class='text-danger'>Failed to load details.</p>";

        });
    }

});
