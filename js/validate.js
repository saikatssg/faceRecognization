
	  document.addEventListener('DOMContentLoaded', () => {
    // 1. Target the form element
    const form = document.querySelector('form');

    // 2. Helper function to show dynamic error messages
    const showError = (element, message) => {
        // Find the closest parent with class 'mb-3' to append the error at the bottom
        const parent = element.closest('.mb-3');
        let errorDisplay = parent.querySelector('.text-danger');
        
        // If an error message doesn't exist, create it dynamically
        if (!errorDisplay) {
            errorDisplay = document.createElement('div');
            errorDisplay.className = 'text-danger small mt-2 fw-bold';
            parent.appendChild(errorDisplay);
        }
        
        // Set the error text
        errorDisplay.textContent = message;
        
        // Add visual red border to text inputs and selects
        if(element.classList.contains('form-control') || element.classList.contains('form-select')) {
            element.style.borderColor = 'red';
        }
    };

    // 3. Helper function to remove dynamic error messages
    const removeError = (element) => {
        const parent = element.closest('.mb-3');
        const errorDisplay = parent.querySelector('.text-danger');
        
        // If an error exists, remove it
        if (errorDisplay) {
            errorDisplay.remove();
        }
        
        // Reset the border color
        if(element.classList.contains('form-control') || element.classList.contains('form-select')) {
            element.style.borderColor = ''; // Reverts to CSS default
        }
    };

    // 4. Main Validation Logic per Field
    const validateField = (field) => {
        const id = field.id;
        const value = field.value.trim();
        let field_name = field.id.name

        // Validate text/password/date fields
        
        if (['fname', 'lname', 'user', 'pwd', 'dob'].includes(id)) {
            if (value === '') {
                showError(field, `field cannot be empty.`);
                return false;
            } else {
                removeError(field);
                return true;
            }
        }

        // Validate Email
        if (id === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value === '') {
                showError(field, 'Email address is required.');
                return false;
            } else if (!emailRegex.test(value)) {
                showError(field, 'Please enter a valid email address.');
                return false;
            } else {
                removeError(field);
                return true;
            }
        }

        // Validate City Select Dropdown
        if (id === 'city') {
            if (value === '0') {
                showError(field, 'Please select a valid city.');
                return false;
            } else {
                removeError(field);
                return true;
            }
        }

        // Validate File Upload
        if (id === 'upload') {
            if (value === '') {
                showError(field, 'Please upload your photo.');
                return false;
            } else {
                removeError(field);
                return true;
            }
        }

        // Validate Checkbox
        if (id === 'checkPointer') {
            if (!field.checked) {
                showError(field, 'You must agree to the terms to proceed.');
                return false;
            } else {
                removeError(field);
                return true;
            }
        }
        
        // Validate Radio Buttons (Gender)
        if (field.name === 'gender') {
            const genders = document.querySelectorAll('input[name="gender"]');
            let isChecked = false;
            genders.forEach(radio => { 
                if (radio.checked) isChecked = true; 
            });
            
            if (!isChecked) {
                showError(field, 'Please select your gender.');
                return false;
            } else {
                removeError(field);
                return true;
            }
        }
        return true; 
    };

    // 5. Attach Dynamic Event Listeners (Input/Change triggers)
    // Grabs all form controls like fname, email, city, upload, etc.
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        // Use 'change' for checkboxes, radios, selects, and files. Use 'input' for typing.
        const eventType = (input.type === 'checkbox' || input.type === 'radio' || input.tagName === 'SELECT' || input.type === 'file') ? 'change' : 'input';
        
        input.addEventListener(eventType, () => {
            validateField(input);
        });
    });

    // 6. Handle Form Submission
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Stop standard form submission
        let isFormValid = true;

        inputs.forEach(input => {
            // To prevent duplicate messages on the gender container, only run it once using the 'male' ID
            if (input.name === 'gender' && input.id !== 'male') return;
            
            const isValid = validateField(input);
            if (!isValid) {
                isFormValid = false;
            }
        });

        // Final Check
        if (isFormValid) {
            // alert('Validation successful! Submitting form data...');
            // In a real application, you'd use form.submit() or a fetch() request here
            form.submit(); 
        }
    });
    
    // 7. Handle Reset Button to clear custom error styles
    form.addEventListener('reset', () => {
        inputs.forEach(input => {
            removeError(input);
        });
    });
});