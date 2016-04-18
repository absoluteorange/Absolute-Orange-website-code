define(['Lang'], function(Lang){
	var Validation = function () {
        var errors = {};
		function validate (formData) {
            this.errors = {};
            if (!this.formEmpty(formData)) {
                if ( !this.validateEmail(formData['email']) ) {
                    this.errors.email = Lang['email_invalid'];
                }
            }
            return this.errors;
		}
        function formEmpty (formData) {
			for (var field in formData) {
				if (formData[field].toLowerCase() === '' || formData[field].toLowerCase().indexOf('your') !== -1) {
					this.errors[field] = Lang['default_value'] + field;
				}
			}
            return !_.isEmpty(this.errors)
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
            validateEmail: validateEmail, 
            displayErrors: displayErrors,
            removeErrors: removeErrors,
            disableButton: disableButton,
            enableButton: enableButton, 
            serialiseFormData: serialiseFormData
        }
	};
    return Validation();
});
