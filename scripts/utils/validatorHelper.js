define(['lang'], function(lang){
	var validatorHelper = function () {
        var errors = {};
		function validate (formData) {
            this.errors = {};
            if (this.formEmpty(formData)) {
                this.setEmptyErrors(formData);
            } else {
                if ( !this.validateEmail(formData['email']) ) {
                    this.errors.email = lang['email_invalid'];
                }
            }
            return this.errors;
		}

        function formEmpty (formData) {
            var isEmpty = false;
			for (var field in formData) {
				if (formData[field].toLowerCase() === '' || formData[field].toLowerCase().indexOf('your') !== -1) {
                    return true;
				}
			}
            return isEmpty;
        }

        function setEmptyErrors (formData) {
			for (var field in formData) {
				if (formData[field].toLowerCase() === '' || formData[field].toLowerCase().indexOf('your') !== -1) {
					this.errors[field] = lang['default_value'] + field;
				}
			}
            return true;
        }

        function setPasswordError() {
            this.errors = {};
            this.errors['password'] = lang['password_incorrect'];
            return this.errors;
        }

        function setDoesNotExistError() {
            this.errors = {};
            this.errors['email'] = lang['email_not_recognised'];
            return this.errors;
        }

		function validateEmail (email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}

        function removeErrors (formData) {
            for (var field in formData) {
               $formGroup = $('#'+field);
               if ($formGroup.length !== 0) {
                   $formGroup.removeClass('has-error');
               }
            }
        }

        function displayErrors (errors) {
            for (var i in errors) {
               $formGroup = $('#'+ i);
               $formGroup.addClass('has-error');
            }
        }

        function disableButton ($button) {
             $button.html('Loading...').prop('disabled', true);
        }

        function enableButton ($button, action) {
             $button.html(action).prop('disabled', false);
        }

        function serialiseFormData ($form) {
             var formValues = $form.serializeArray();
			 var data = _(formValues).reduce(function(arrForm, field) {
			     arrForm[field.name] = field.value;
                 return arrForm;
			 });
        }

        return {
            errors: errors,
            validate: validate, 
            formEmpty: formEmpty,
            setEmptyErrors: setEmptyErrors,
            validateEmail: validateEmail, 
            setPasswordError: setPasswordError,
            setDoesNotExistError: setDoesNotExistError,
            displayErrors: displayErrors,
            removeErrors: removeErrors,
            disableButton: disableButton,
            enableButton: enableButton, 
            serialiseFormData: serialiseFormData
        }
	};
    return validatorHelper();
});
