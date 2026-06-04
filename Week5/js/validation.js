// Week 3 - validation.js - NexaBank
// Full form validation, DOM manipulation, and PHP syntax practice

"use strict";

// ========== UTILITY FUNCTIONS ==========

function showError(inputEl, message) {
    const group = inputEl.closest(".form-group");
    const errMsg = group.querySelector(".error-msg");
    group.classList.add("error");
    if (errMsg) errMsg.textContent = message;
}

function clearError(inputEl) {
    const group = inputEl.closest(".form-group");
    group.classList.remove("error");
}

function showAlert(message, type) {
    const existingAlert = document.querySelector(".alert");
    if (existingAlert) existingAlert.remove();

    const alert = document.createElement("div");
    alert.className = "alert alert-" + type;
    alert.textContent = message;

    const form = document.querySelector("form");
    if (form) form.insertBefore(alert, form.firstChild);

    setTimeout(() => alert.remove(), 4000);
}

// ========== FIELD VALIDATORS ==========

function validateRequired(input, label) {
    if (input.value.trim() === "") {
        showError(input, label + " is required");
        return false;
    }
    clearError(input);
    return true;
}

function validateEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(input.value.trim())) {
        showError(input, "Please enter a valid email address");
        return false;
    }
    clearError(input);
    return true;
}

function validatePassword(input) {
    if (input.value.length < 8) {
        showError(input, "Password must be at least 8 characters");
        return false;
    }
    clearError(input);
    return true;
}

// Password strength estimator: returns 0-4
function checkPasswordStrength(pw) {
    let score = 0;
    if (!pw) return score;
    if (pw.length >= 8) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    return score;
}

function updatePasswordStrengthUI(pw) {
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');
    if (!fill || !text) return;
    const score = checkPasswordStrength(pw);
    const percent = (score / 4) * 100;
    fill.style.width = percent + '%';
    let label = 'Very Weak';
    let color = '#e53e3e';
    if (score === 1) { label = 'Weak'; color = '#f6ad55'; }
    if (score === 2) { label = 'Medium'; color = '#ecc94b'; }
    if (score === 3) { label = 'Strong'; color = '#48bb78'; }
    if (score === 4) { label = 'Very Strong'; color = '#2f855a'; }
    if (score === 0) { label = 'N/A'; color = '#e2e8f0'; }
    fill.style.background = color;
    text.textContent = 'Password strength: ' + label;
}

function validateMatch(input1, input2) {
    if (input1.value !== input2.value) {
        showError(input2, "Passwords do not match");
        return false;
    }
    clearError(input2);
    return true;
}

function validateAmount(input) {
    const val = parseFloat(input.value);
    if (isNaN(val) || val <= 0) {
        showError(input, "Please enter a valid amount greater than 0");
        return false;
    }
    clearError(input);
    return true;
}

function validatePhone(input) {
    const phoneRegex = /^[\+]?[0-9\s\-]{7,15}$/;
    if (input.value.trim() !== "" && !phoneRegex.test(input.value.trim())) {
        showError(input, "Please enter a valid phone number");
        return false;
    }
    clearError(input);
    return true;
}

// ========== REGISTER FORM VALIDATION ==========

const registerForm = document.getElementById("registerForm");
if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
        e.preventDefault();
        let valid = true;

        const firstName = document.querySelector("[name='first_name']");
        const lastName = document.querySelector("[name='last_name']");
        const email = document.querySelector("[name='email']");
        const phone = document.querySelector("[name='phone']");
        const accountType = document.querySelector("[name='account_type']");
        const username = document.querySelector("[name='username']");
        const password = document.querySelector("[name='password']");
        const confirmPassword = document.querySelector("[name='confirm_password']");

        if (!validateRequired(firstName, "First name")) valid = false;
        if (!validateRequired(lastName, "Last name")) valid = false;
        if (!validateEmail(email)) valid = false;
        if (phone) validatePhone(phone);
        if (!validateRequired(username, "Username")) valid = false;
        if (!validatePassword(password)) valid = false;
        else {
            // require at least Medium strength (score >=2)
            const strength = checkPasswordStrength(password.value);
            if (strength < 2) {
                showError(password, 'Please choose a stronger password');
                valid = false;
            } else {
                clearError(password);
            }
        }
        if (!validateMatch(password, confirmPassword)) valid = false;

        if (accountType && accountType.value === "") {
            showError(accountType, "Please select an account type");
            valid = false;
        } else if (accountType) {
            clearError(accountType);
        }

        if (valid) {
            showAlert("Validation passed! Submitting to server...", "success");
            setTimeout(() => registerForm.submit(), 1000);
        }
    });

    // Live strength meter on password input
    const regPw = document.getElementById('regPassword') || document.querySelector("[name='password']");
    if (regPw) {
        regPw.addEventListener('input', function () {
            updatePasswordStrengthUI(this.value);
            if (this.value.trim() !== '') clearError(this);
        });
        // initialize UI
        updatePasswordStrengthUI(regPw.value);
    }

    // Live validation on blur
    const allInputs = registerForm.querySelectorAll("input");
    allInputs.forEach(input => {
        input.addEventListener("blur", function () {
            if (this.value.trim() !== "") clearError(this);
        });
    });
}

// ========== LOGIN FORM VALIDATION ==========

const loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
        let valid = true;

        const username = document.querySelector("[name='username']");
        const password = document.querySelector("[name='password']");

        if (!validateRequired(username, "Username")) valid = false;
        if (!validateRequired(password, "Password")) valid = false;

        if (valid) {
            loginForm.submit();
        }
    });
}

// ========== TRANSACTION FORMS VALIDATION ==========

const transactionForm = document.getElementById("transactionForm");
if (transactionForm) {
    transactionForm.addEventListener("submit", function (e) {
        e.preventDefault();
        let valid = true;

        const amount = document.querySelector("[name='amount']");
        if (amount && !validateAmount(amount)) valid = false;

        if (valid) {
            transactionForm.submit();
        }
    });
}

// ========== DOM MANIPULATION - Live Balance Preview ==========

const amountInput = document.getElementById("amountInput");
const livePreview = document.getElementById("livePreview");

if (amountInput && livePreview) {
    amountInput.addEventListener("input", function () {
        const val = parseFloat(this.value) || 0;
        livePreview.textContent = "KES " + val.toLocaleString("en-KE");
    });
}

// ========== Active Nav Link ==========

document.addEventListener("DOMContentLoaded", function () {
    const currentPage = window.location.pathname.split("/").pop();
    document.querySelectorAll(".nav-links a, .sidebar-nav a").forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active");
        }
    });
});

console.log("NexaBank JS - Week 3 validation loaded");
