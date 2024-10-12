document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.querySelectorAll('.toggle-password');
    togglePassword.forEach(item => {
        item.addEventListener('click', function () {
            const input = document.querySelector(this.getAttribute('toggle'));
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });

    const deleteButtons = document.querySelectorAll('.btn-delete, .btn-disapprove');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const confirmation = confirm('Are you sure you want to perform this action?');
            if (!confirmation) {
                e.preventDefault();
            }
        });
    });

    const checkoutForm = document.querySelector('#checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
            const cardNumber = document.querySelector('#cardNumber');
            const walletNumber = document.querySelector('#walletNumber');

            if (!paymentMethod) {
                alert('Please select a payment method.');
                e.preventDefault();
                return;
            }

            if (paymentMethod.value === 'credit-card' && cardNumber.value.length < 16) {
                alert('Please enter a valid credit card number.');
                e.preventDefault();
            } else if (paymentMethod.value === 'mobile-wallet' && walletNumber.value.length < 10) {
                alert('Please enter a valid mobile wallet number.');
                e.preventDefault();
            }
        });

        const paymentOptions = document.querySelectorAll('input[name="paymentMethod"]');
        paymentOptions.forEach(option => {
            option.addEventListener('change', function () {
                const cardSection = document.querySelector('#cardSection');
                const walletSection = document.querySelector('#walletSection');

                if (this.value === 'credit-card') {
                    cardSection.style.display = 'block';
                    walletSection.style.display = 'none';
                } else {
                    cardSection.style.display = 'none';
                    walletSection.style.display = 'block';
                }
            });
        });
    }

    const changeProfilePicButton = document.querySelector('#changeProfilePic');
    const profilePicInput = document.querySelector('#profilePicInput');
    if (changeProfilePicButton) {
        changeProfilePicButton.addEventListener('click', function () {
            profilePicInput.click();
        });
    }

    const newPasswordInput = document.querySelector('#newPassword');
    const confirmPasswordInput = document.querySelector('#confirmPassword');
    const passwordFeedback = document.querySelector('#passwordFeedback');
    
    if (newPasswordInput && confirmPasswordInput) {
        confirmPasswordInput.addEventListener('keyup', function () {
            if (newPasswordInput.value !== confirmPasswordInput.value) {
                passwordFeedback.textContent = 'Passwords do not match!';
                passwordFeedback.style.color = 'red';
            } else {
                passwordFeedback.textContent = 'Passwords match!';
                passwordFeedback.style.color = 'green';
            }
        });
    }
    
    const navbarToggle = document.querySelector('.navbar-toggle');
    const navbarLinks = document.querySelector('.navbar-links');
    
    if (navbarToggle && navbarLinks) {
        navbarToggle.addEventListener('click', function () {
            navbarLinks.classList.toggle('active');
        });
    }
});
