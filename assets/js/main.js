
/**
 * Main JavaScript for Rakth Setu
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize navbar scroll effect
  initNavbar();
  
  // Initialize mobile menu
  initMobileMenu();
  
  // Initialize form validation
  initFormValidation();
  
  // Initialize toast notifications
  initToastNotifications();
  
  // Initialize FAQ accordion
  initFaqAccordion();
  
  // Initialize blood type selector
  initBloodTypeSelector();
});

/**
 * Initialize navbar scroll effect
 */
function initNavbar() {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;
  
  window.addEventListener('scroll', function() {
    if (window.scrollY > 10) {
      navbar.classList.add('navbar-scrolled');
    } else {
      navbar.classList.remove('navbar-scrolled');
    }
  });
  
  // Trigger scroll event once to set initial state
  window.dispatchEvent(new Event('scroll'));
}

/**
 * Initialize mobile menu
 */
function initMobileMenu() {
  const menuButton = document.querySelector('.navbar-mobile-menu');
  const closeButton = document.querySelector('.mobile-menu-close');
  const mobileMenu = document.querySelector('.mobile-menu');
  const body = document.body;
  
  if (!menuButton || !mobileMenu) return;
  
  menuButton.addEventListener('click', function() {
    mobileMenu.classList.add('open');
    body.style.overflow = 'hidden';
  });
  
  if (closeButton) {
    closeButton.addEventListener('click', function() {
      mobileMenu.classList.remove('open');
      body.style.overflow = '';
    });
  }
  
  // Close mobile menu when clicking on a link
  const mobileLinks = document.querySelectorAll('.mobile-menu-link');
  mobileLinks.forEach(link => {
    link.addEventListener('click', function() {
      mobileMenu.classList.remove('open');
      body.style.overflow = '';
    });
  });
}

/**
 * Initialize form validation
 */
function initFormValidation() {
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(event) {
      // Skip validation if form has data-novalidate attribute
      if (form.getAttribute('data-novalidate') !== null) return;
      
      event.preventDefault();
      
      // Reset previous validation states
      const errorMessages = form.querySelectorAll('.invalid-feedback');
      errorMessages.forEach(msg => msg.remove());
      
      const formControls = form.querySelectorAll('.form-control');
      formControls.forEach(field => {
        field.classList.remove('is-invalid');
      });
      
      const requiredFields = form.querySelectorAll('[required]');
      let isValid = true;
      
      requiredFields.forEach(field => {
        const value = field.value.trim();
        
        if (!value) {
          isValid = false;
          field.classList.add('is-invalid');
          
          const errorMessage = document.createElement('div');
          errorMessage.className = 'invalid-feedback';
          errorMessage.textContent = 'This field is required';
          field.parentNode.appendChild(errorMessage);
        }
        
        // Email validation
        if (field.type === 'email' && value) {
          const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailPattern.test(value)) {
            isValid = false;
            field.classList.add('is-invalid');
            
            const errorMessage = document.createElement('div');
            errorMessage.className = 'invalid-feedback';
            errorMessage.textContent = 'Please enter a valid email address';
            field.parentNode.appendChild(errorMessage);
          }
        }
        
        // Password validation (if it's a registration form)
        if (field.type === 'password' && field.id === 'password' && value) {
          if (value.length < 8) {
            isValid = false;
            field.classList.add('is-invalid');
            
            const errorMessage = document.createElement('div');
            errorMessage.className = 'invalid-feedback';
            errorMessage.textContent = 'Password must be at least 8 characters long';
            field.parentNode.appendChild(errorMessage);
          }
        }
        
        // Confirm password validation
        if (field.id === 'confirm_password') {
          const password = document.getElementById('password');
          if (password && field.value !== password.value) {
            isValid = false;
            field.classList.add('is-invalid');
            
            const errorMessage = document.createElement('div');
            errorMessage.className = 'invalid-feedback';
            errorMessage.textContent = 'Passwords do not match';
            field.parentNode.appendChild(errorMessage);
          }
        }
        
        // Phone number validation
        if (field.id === 'phone' && value) {
          const phonePattern = /^\d{10}$/;
          if (!phonePattern.test(value.replace(/\D/g, ''))) {
            isValid = false;
            field.classList.add('is-invalid');
            
            const errorMessage = document.createElement('div');
            errorMessage.className = 'invalid-feedback';
            errorMessage.textContent = 'Please enter a valid 10-digit phone number';
            field.parentNode.appendChild(errorMessage);
          }
        }
      });
      
      if (isValid) {
        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
          const originalText = submitButton.innerHTML;
          submitButton.disabled = true;
          submitButton.innerHTML = '<span class="spinner mr-2"></span>Processing...';
          
          // Submit the form
          form.submit();
        }
      }
    });
  });
}

/**
 * Initialize toast notifications
 */
function initToastNotifications() {
  // Create toast container if it doesn't exist
  let toastContainer = document.querySelector('.toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    document.body.appendChild(toastContainer);
  }
}

/**
 * Show a toast notification
 * @param {string} message - Toast message
 * @param {string} type - Toast type (success, error, warning, info)
 * @param {string} title - Toast title (optional)
 */
function showToast(message, type = 'success', title = '') {
  const toastContainer = document.querySelector('.toast-container');
  if (!toastContainer) return;
  
  // Set default title based on type if not provided
  if (!title) {
    switch (type) {
      case 'success':
        title = 'Success!';
        break;
      case 'error':
        title = 'Error!';
        break;
      case 'warning':
        title = 'Warning!';
        break;
      case 'info':
        title = 'Information';
        break;
      default:
        title = 'Message';
    }
  }
  
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `
    <div class="toast-header">
      <span class="toast-title">${title}</span>
      <button class="toast-close">&times;</button>
    </div>
    <div class="toast-body">${message}</div>
  `;
  
  // Style based on type
  switch (type) {
    case 'error':
      toast.style.borderLeftColor = 'var(--danger)';
      break;
    case 'warning':
      toast.style.borderLeftColor = 'var(--warning)';
      break;
    case 'info':
      toast.style.borderLeftColor = 'var(--info)';
      break;
    default:
      toast.style.borderLeftColor = 'var(--success)';
  }
  
  // Add close button functionality
  const closeButton = toast.querySelector('.toast-close');
  closeButton.addEventListener('click', () => {
    toast.remove();
  });
  
  // Add to container
  toastContainer.appendChild(toast);
  
  // Auto remove after 5 seconds
  setTimeout(() => {
    toast.remove();
  }, 5000);
}

/**
 * Initialize FAQ accordion
 */
function initFaqAccordion() {
  const faqQuestions = document.querySelectorAll('.faq-question');
  
  faqQuestions.forEach(question => {
    question.addEventListener('click', function() {
      // Toggle active class
      this.classList.toggle('active');
      
      // Toggle answer visibility
      const answer = this.nextElementSibling;
      if (answer.style.maxHeight) {
        answer.style.maxHeight = null;
      } else {
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });
}

/**
 * Initialize blood type selector
 */
function initBloodTypeSelector() {
  const bloodTypeCards = document.querySelectorAll('.blood-type-card');
  const bloodTypeInput = document.querySelector('input[name="blood_type"]');
  
  bloodTypeCards.forEach(card => {
    card.addEventListener('click', function() {
      // Remove active class from all cards
      bloodTypeCards.forEach(c => c.classList.remove('active'));
      
      // Add active class to clicked card
      this.classList.add('active');
      
      // Update hidden input value
      if (bloodTypeInput) {
        bloodTypeInput.value = this.getAttribute('data-blood-type');
      }
      
      // Update blood compatibility information if function exists
      const bloodType = this.getAttribute('data-blood-type');
      if (typeof updateBloodCompatibility === 'function' && bloodType) {
        updateBloodCompatibility(bloodType);
      }
    });
  });
}

/**
 * Updates blood compatibility information
 * @param {string} bloodType - Selected blood type
 */
function updateBloodCompatibility(bloodType) {
  const compatibilityMap = {
    'A+': { canDonateTo: ['A+', 'AB+'], canReceiveFrom: ['A+', 'A-', 'O+', 'O-'] },
    'A-': { canDonateTo: ['A+', 'A-', 'AB+', 'AB-'], canReceiveFrom: ['A-', 'O-'] },
    'B+': { canDonateTo: ['B+', 'AB+'], canReceiveFrom: ['B+', 'B-', 'O+', 'O-'] },
    'B-': { canDonateTo: ['B+', 'B-', 'AB+', 'AB-'], canReceiveFrom: ['B-', 'O-'] },
    'AB+': { canDonateTo: ['AB+'], canReceiveFrom: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] },
    'AB-': { canDonateTo: ['AB+', 'AB-'], canReceiveFrom: ['A-', 'B-', 'AB-', 'O-'] },
    'O+': { canDonateTo: ['A+', 'B+', 'AB+', 'O+'], canReceiveFrom: ['O+', 'O-'] },
    'O-': { canDonateTo: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'], canReceiveFrom: ['O-'] }
  };
  
  const compatibility = compatibilityMap[bloodType];
  if (!compatibility) return;
  
  const donateToElement = document.getElementById('can-donate-to');
  const receiveFromElement = document.getElementById('can-receive-from');
  
  if (donateToElement) {
    donateToElement.innerHTML = compatibility.canDonateTo
      .map(type => `<span class="blood-type-pill">${type}</span>`)
      .join('');
  }
  
  if (receiveFromElement) {
    receiveFromElement.innerHTML = compatibility.canReceiveFrom
      .map(type => `<span class="blood-type-pill">${type}</span>`)
      .join('');
  }
}

/**
 * Set active navigation link based on current page
 */
function setActiveNavLink() {
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.navbar-link, .mobile-menu-link');
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPath) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
}

// Set active navigation link on page load
setActiveNavLink();

/**
 * Format a date string
 * @param {string} dateString - Date string to format
 * @param {boolean} includeTime - Whether to include time in the formatted string
 * @returns {string} Formatted date string
 */
function formatDate(dateString, includeTime = false) {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  const options = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric'
  };
  
  if (includeTime) {
    options.hour = '2-digit';
    options.minute = '2-digit';
  }
  
  return date.toLocaleDateString('en-US', options);
}

/**
 * Format a phone number
 * @param {string} phoneNumber - Phone number to format
 * @returns {string} Formatted phone number
 */
function formatPhoneNumber(phoneNumber) {
  if (!phoneNumber) return '';
  
  // Remove all non-numeric characters
  const cleaned = phoneNumber.replace(/\D/g, '');
  
  // Format: (XXX) XXX-XXXX
  const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
  if (match) {
    return '(' + match[1] + ') ' + match[2] + '-' + match[3];
  }
  
  return phoneNumber;
}
