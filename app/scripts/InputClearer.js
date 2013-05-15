/*
* 
* @author: Amy Varga
* 
* @param arrControls
* 	'control' : control
* 
*/

function InputClearer (control) {
	if (control != null) {
		this.control=control;
	}
	this.getDefaultValue();
}

InputClearer.prototype.getDefaultValue = function () {
		if (this.control != undefined) {
			var controlType = this.control.type;
			var controlName = this.control.name;
			var controlDefaultValue = this.control.value;
			var arrTextInputs = document.getElementsByName(controlName);
			var input = arrTextInputs[0];
			switch (controlType) {
			case 'text' :		
				thisObj = this;
				thisObj.control.onfocus = function () {
					if (input.value == controlDefaultValue) {
						thisObj.clearDefaultValue(input);
						thisObj.removeErrorBorder(input.parentNode);
						thisObj.removeErrors(input.parentNode);
					} else {
						thisObj.removeErrorBorder(input.parentNode);
						thisObj.removeErrors(input.parentNode);
					}
				};
				input.onblur = function () {
					if (input.value == '') {
						thisObj.returnDefaultValue(input, controlDefaultValue);
					}
				};
			break;
			case 'password' :
				thisObj = this;
				thisObj.changeInputType('text', input);
				input.onfocus = function () {
					if (input.value == controlDefaultValue) {
						thisObj.changeInputType('password', input);
						thisObj.clearDefaultValue(input);
						thisObj.removeErrorBorder(input.parentNode);
						thisObj.removeErrors(input.parentNode);
					} else {
						thisObj.removeErrorBorder(input.parentNode);
						thisObj.removeErrors(input.parentNode);
					}					
				};
				input.onblur = function () {
					if (input.value == '') {
						thisObj.changeInputType('text', input);
						thisObj.returnDefaultValue(input, 'password');
					}
				};
			break;
			}
		}
};

InputClearer.prototype.clearDefaultValue = function (input) {
	input.value = '';
};

InputClearer.prototype.returnDefaultValue = function (input, controlDefaultValue) {
	input.value = controlDefaultValue;
};

InputClearer.prototype.changeInputType = function (type, input) {
	input.type = type;
};

InputClearer.prototype.addDefaultValue = function (value, input) {
	input.value = value;
};

InputClearer.prototype.removeErrorBorder = function (controlGroup) {
	utils.removeClass(controlGroup, 'error');
};

InputClearer.prototype.removeErrors = function (controlGroup) {
	$(controlGroup).find('span.help-inline').html('');
}