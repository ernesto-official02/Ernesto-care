//   navbar function
$(document).ready(function () {
  $(".fa-bars").click(function () {
    $(this).toggleClass("fa-times");
    $(".navbar").toggleClass("nav-toggle");
  });

  $(window).on("scroll load", function () {
    $(".fa-bars").removeClass("fa-times");
    $(".navbar").removeClass("nav-toggle");

    if ($(window).scrollTop() > 30) {
      $("header").addClass("header-active");
    } else {
      $("header").removeClass("header-active");
    }

    // Active section highlighting
    $('section').each(function() {
      let top = $(window).scrollTop();
      let offset = $(this).offset().top - 200;
      let height = $(this).height();
      let id = $(this).attr('id');

      if (top >= offset && top < offset + height) {
        $('.navbar ul li a').removeClass('active');
        $('.navbar').find(`[href="#${id}"]`).addClass('active');
      }
    });
  });

  // Smooth scroll for navigation links
  $('.navbar a').on('click', function(e) {
    e.preventDefault();
    let target = $(this).attr('href');
    $('html, body').animate({
      scrollTop: $(target).offset().top
    }, 500);

    // Close mobile menu after clicking
    $(".fa-bars").removeClass("fa-times");
    $(".navbar").removeClass("nav-toggle");
  });
});

// Read more button toggle functionality
$(document).ready(function() {
  $('.read-more-btn').click(function() {
    const toggleContent = $(this).closest('.content').find('.toggle-content');
    const buttonText = $(this).text();
    
    toggleContent.slideToggle(300);
    $(this).text(buttonText === 'read more' ? 'read less' : 'read more');
  });
});

// Learn more button toggle functionality
$(document).ready(function() {
  $('.learn-more-btn').click(function() {
    const toggleContent = $(this).prev('.toggle-content');
    const buttonText = $(this).text();
    
    toggleContent.slideToggle(300);
    $(this).text(buttonText === 'learn more' ? 'learn less' : 'learn more');
  });
});

// Appointment Dialog Functionality
function openAppointmentDialog() {
  document.getElementById('appointmentDialog').showModal();
}

function closeAppointmentDialog() {
  document.getElementById('appointmentDialog').close();
}

// Login Dialog Functionality
const loginBtn = document.querySelector('.login-btn');
const loginDialog = document.querySelector('#loginDialog');
const closeLoginBtn = document.querySelector('#loginDialog .close-btn');
const cancelLoginBtn = document.querySelector('#loginDialog .cancel-btn');
const registerLink = document.querySelector('.register-link');

// Register Dialog Functionality
const registerDialog = document.querySelector('#registerDialog');
const closeRegisterBtn = document.querySelector('#registerDialog .close-btn');
const cancelRegisterBtn = document.querySelector('#registerDialog .cancel-btn');
const loginLink = document.querySelector('.login-link');

if (loginBtn && loginDialog) {
  loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    loginDialog.showModal();
  });

  closeLoginBtn.addEventListener('click', () => {
    loginDialog.close();
  });

  cancelLoginBtn.addEventListener('click', () => {
    loginDialog.close();
  });

  // Switch to register form
  registerLink.addEventListener('click', (e) => {
    e.preventDefault();
    loginDialog.close();
    registerDialog.showModal();
  });
}

if (registerDialog) {
  closeRegisterBtn.addEventListener('click', () => {
    registerDialog.close();
  });

  cancelRegisterBtn.addEventListener('click', () => {
    registerDialog.close();
  });

  // Switch to login form
  loginLink.addEventListener('click', (e) => {
    e.preventDefault();
    registerDialog.close();
    loginDialog.showModal();
  });
}

// Close dialog when clicking outside
document.addEventListener('DOMContentLoaded', function() {
  const dialogs = document.querySelectorAll('dialog');
  
  dialogs.forEach(dialog => {
    dialog.addEventListener('click', function(event) {
      const rect = dialog.getBoundingClientRect();
      const isInDialog = (rect.top <= event.clientY && event.clientY <= rect.top + rect.height
        && rect.left <= event.clientX && event.clientX <= rect.left + rect.width);
      
      if (!isInDialog) {
        dialog.close();
      }
    });
  });
});

// Popup Notification Function
function showNotification(message, type = 'success') {
  // Remove any existing notifications
  const existingNotification = document.querySelector('.popup-notification');
  if (existingNotification) {
    existingNotification.remove();
  }

  // Create notification element
  const notification = document.createElement('div');
  notification.className = `popup-notification ${type}`;
  notification.innerHTML = `
    ${message}
    <button class="close-popup">&times;</button>
  `;

  // Add to document
  document.body.appendChild(notification);

  // Show notification
  setTimeout(() => {
    notification.classList.add('show');
  }, 100);

  // Add close button functionality
  const closeButton = notification.querySelector('.close-popup');
  closeButton.addEventListener('click', () => {
    notification.classList.remove('show');
    setTimeout(() => {
      notification.remove();
    }, 300);
  });

  // Auto remove after 5 seconds
  setTimeout(() => {
    if (notification.parentElement) {
      notification.classList.remove('show');
      setTimeout(() => {
        notification.remove();
      }, 300);
    }
  }, 5000);
}

// Check for PHP success messages and show notifications
document.addEventListener('DOMContentLoaded', function() {
  // Check for registration success
  const registerSuccess = document.querySelector('#registerForm .success-message');
  if (registerSuccess) {
    showNotification('Registration successful! You can now login.', 'success');
  }

  // Check for login success
  const loginSuccess = document.querySelector('#loginForm .success-message');
  if (loginSuccess) {
    showNotification('Login successful! Welcome back.', 'success');
  }

  // Check for appointment success
  const appointmentSuccess = document.querySelector('#appointmentForm .success-message');
  if (appointmentSuccess) {
    showNotification('Appointment booked successfully!', 'success');
  }

  // Check for contact form success
  const contactSuccess = document.querySelector('.form-container .success-message');
  if (contactSuccess) {
    showNotification('Message sent successfully! We will get back to you soon.', 'success');
  }

  // Check for error messages
  const errorMessages = document.querySelectorAll('.error-message');
  errorMessages.forEach(error => {
    showNotification(error.textContent, 'error');
  });
});

// User Menu Functionality
function createUserMenu(username) {
  // Remove existing user menu if any
  const existingUserMenu = document.querySelector('.user-menu-container');
  if (existingUserMenu) {
    existingUserMenu.remove();
  }

  // Create user initial element
  const userInitial = document.createElement('div');
  userInitial.className = 'user-initial';
  userInitial.textContent = username.charAt(0).toUpperCase();

  // Create user menu
  const userMenu = document.createElement('div');
  userMenu.className = 'user-menu';
  userMenu.innerHTML = `
    <div class="user-name">${username}</div>
    <div class="logout-btn">
      <i class="fas fa-sign-out-alt"></i>
      <span>Logout</span>
    </div>
  `;

  // Create container for user menu
  const userMenuContainer = document.createElement('li');
  userMenuContainer.className = 'user-menu-container';
  userMenuContainer.appendChild(userInitial);
  userMenuContainer.appendChild(userMenu);

  // Add to navbar
  const navbar = document.querySelector('.navbar ul');
  if (navbar) {
    navbar.appendChild(userMenuContainer);

    // Toggle menu on click
    userInitial.addEventListener('click', (e) => {
      e.stopPropagation();
      userMenu.classList.toggle('show');
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!userMenuContainer.contains(e.target)) {
        userMenu.classList.remove('show');
      }
    });

    // Handle logout
    const logoutBtn = userMenu.querySelector('.logout-btn');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        showLogoutConfirmation();
      });
    }
  }
}

// Logout Confirmation Popup
function showLogoutConfirmation() {
  // Remove any existing confirmation dialog
  const existingDialog = document.querySelector('.confirmation-dialog');
  if (existingDialog) {
    existingDialog.remove();
  }

  // Create confirmation dialog
  const confirmationDialog = document.createElement('div');
  confirmationDialog.className = 'confirmation-dialog';
  confirmationDialog.innerHTML = `
    <div class="confirmation-content">
      <h3>Confirm Logout</h3>
      <p>Are you sure you want to logout?</p>
      <div class="confirmation-buttons">
        <button class="cancel-btn">Cancel</button>
        <button class="confirm-btn">Logout</button>
      </div>
    </div>
  `;

  // Add to document
  document.body.appendChild(confirmationDialog);

  // Show dialog with animation
  requestAnimationFrame(() => {
    confirmationDialog.classList.add('show');
  });

  // Handle cancel button
  const cancelBtn = confirmationDialog.querySelector('.cancel-btn');
  if (cancelBtn) {
    cancelBtn.addEventListener('click', () => {
      confirmationDialog.classList.remove('show');
      setTimeout(() => {
        confirmationDialog.remove();
      }, 300);
    });
  }

  // Handle confirm button
  const confirmBtn = confirmationDialog.querySelector('.confirm-btn');
  if (confirmBtn) {
    confirmBtn.addEventListener('click', () => {
      // Show logout notification
      showNotification('Logging out...', 'success');
      
      // Clear any local storage or session storage
      localStorage.clear();
      sessionStorage.clear();
      
      // Redirect to logout.php
      setTimeout(() => {
        window.location.href = 'logout.php';
      }, 1000);
    });
  }

  // Close dialog when clicking outside
  confirmationDialog.addEventListener('click', (e) => {
    if (e.target === confirmationDialog) {
      confirmationDialog.classList.remove('show');
      setTimeout(() => {
        confirmationDialog.remove();
      }, 300);
    }
  });
}

// Check for logout parameter in URL and initialize user menu
document.addEventListener('DOMContentLoaded', function() {
  // Check if this is a logout redirect
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('logout') === 'success') {
    showNotification('You have been successfully logged out.', 'success');
    
    // Remove the logout parameter from the URL without refreshing the page
    const newUrl = window.location.pathname;
    window.history.replaceState({}, document.title, newUrl);
  }
  
  // Check if user is logged in and create menu
  const isLoggedIn = document.body.classList.contains('logged-in');
  if (isLoggedIn) {
    const username = document.body.getAttribute('data-username');
    if (username) {
      createUserMenu(username);
    }
  }
});
