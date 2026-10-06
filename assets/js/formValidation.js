// function validateName(name) {
//     const regex = /^[A-Za-z\s]{2,50}$/;
//     return regex.test(name);
// }

// function validateEmail(email) {
//     if (email === '') return true;
//     const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     return regex.test(email);
// }

// function validatePhone(phone) {
//     if (phone === '') return true;
//     const regex = /^[0-9]{10}$/;
//     return regex.test(phone);
// }

// function validateAddress(address) {
//     return address.trim().length >= 5;
// }

// function validateForm(form) {

//     let valid = true;

//     // Validate Names (All name fields)
//     form.querySelectorAll(".validate-name").forEach(field => {

//         if (!validateName(field.value)) {
//             alert("Invalid Name");
//             field.focus();
//             valid = false;
//         }

//     });

//     // Validate Email
//     form.querySelectorAll(".validate-email").forEach(field => {

//         if (!validateEmail(field.value)) {
//             alert("Invalid Email");
//             field.focus();
//             valid = false;
//         }

//     });

//     // Validate Phone
//     form.querySelectorAll(".validate-phone").forEach(field => {

//         if (!validatePhone(field.value)) {
//             alert("Invalid Phone Number");
//             field.focus();
//             valid = false;
//         }

//     });

//     // Validate Address
//     form.querySelectorAll(".validate-address").forEach(field => {

//         if (!validateAddress(field.value)) {
//             alert("Invalid Address");
//             field.focus();
//             valid = false;
//         }

//     });

//     return valid;
// }


function validateName(name) {
    const regex = /^[A-Za-z\s]{2,50}$/;
    return regex.test(name.trim());
}
function validateCompanyName(name) {
    const regex = /^[A-Za-z\s()&.,'-]{2,50}$/;
    return regex.test(name.trim());
}

function validatePassword(password) {
    const regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=\[\]{};:'",.<>/?\\|`~]).{6,}$/;
    return regex.test(password);
}

function validateEmail(email) {
    if (email === '') return true;

    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validatePhone(phone) {
    if (phone === '') return true;

    const regex = /^[0-9]{10}$/;
    return regex.test(phone);
}

function validateAddress(address) {
    return address.trim().length >= 5;
}


function showError(field, message) {

    const error = field.parentElement.querySelector(".error-msg");

    if (error) {
        error.textContent = message;
    }

    field.classList.add("is-invalid");
}

function clearError(field) {

    const error = field.parentElement.querySelector(".error-msg");

    if (error) {
        error.textContent = "";
    }

    field.classList.remove("is-invalid");
}

function validateForm(form) {

    let valid = true;

    form.querySelectorAll(".validate-name").forEach(field => {

        if (!validateName(field.value)) {

            showError(
                field,
                "Only letters allowed"
            );

            valid = false;

        } else {

            clearError(field);

        }
    });
    form.querySelectorAll(".validate-companyname").forEach(field => {

        if (!validateCompanyName(field.value)) {

            showError(
                field,
                "Only letters, spaces and () allowed"
            );

            valid = false;

        } else {

            clearError(field);

        }
    });

    form.querySelectorAll(".validate-email").forEach(field => {

        if (!validateEmail(field.value)) {

            showError(
                field,
                "Enter a valid email address"
            );

            valid = false;

        } else {

            clearError(field);

        }
    });

    form.querySelectorAll(".validate-phone").forEach(field => {

        if (!validatePhone(field.value)) {

            showError(
                field,
                "Phone number must contain 10 digits"
            );

            valid = false;

        } else {

            clearError(field);

        }
    });

    form.querySelectorAll(".validate-address").forEach(field => {

        if (!validateAddress(field.value)) {

            showError(
                field,
                "Address must contain at least 5 characters"
            );

            valid = false;

        } else {

            clearError(field);

        }
    });

    form.querySelectorAll(".validate-password").forEach(field => {

        if (!validatePassword(field.value)) {

            showError(
                field,
                "Password must be at least 6 characters with a letter, number and symbol"
            );

            valid = false;

        } else {

            clearError(field);

        }
    });

    return valid;
}

function validateField(field) {

    if (field.classList.contains("validate-name")) {

        validateName(field.value)
            ? clearError(field)
            : showError(field,
                "Only letters allowed");
    }
    if (field.classList.contains("validate-companyname")) {

        validateCompanyName(field.value)
            ? clearError(field)
            : showError(field,
                "Only letters, spaces and () allowed");
    }

    if (field.classList.contains("validate-password")) {

        validatePassword(field.value)
            ? clearError(field)
            : showError(field,
                "Password must contain a letter, number and symbol");
    }

    if (field.classList.contains("validate-email")) {

        validateEmail(field.value)
            ? clearError(field)
            : showError(field,
                "Invalid email address");
    }

    if (field.classList.contains("validate-phone")) {

        validatePhone(field.value)
            ? clearError(field)
            : showError(field,
                "Phone number must contain 10 digits");
    }
}