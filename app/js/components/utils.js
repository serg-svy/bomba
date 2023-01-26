
/**
 * Transliterate text
 * @param {*} string
 */
export const  translitraite = (string) => {
  return string.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

/**
 * Export validation
 */
export const errorValidation = {
	errorPlacement: function(error, element) {
		if (element.is(":checkbox")) {
			error.insertAfter(element.closest(".checkbox__item").find("label"));
		} else if (element.is(":radio")) {
			error.insertAfter(element.closest(".radio__inputs"));
		} else if (element.is(":file")) {
			error.insertAfter(element);
		} else {
			error.insertAfter(element);
		}
	},
	  highlight: (element) => {
			const $el = $(element).closest('.form-group > .field');
			if (!$el.hasClass('error')) {
				$el.addClass('error');
			}
		  	$el.find(".msg-label").removeClass('validation-success').addClass('sylius-validation-error');
		},
	unhighlight: (element) => {
		const $el = $(element).closest('.form-group > .field');
		if ($el.hasClass('error')) {
			$el.removeClass('error');
		}
		$el.find(".msg-label").removeClass('sylius-validation-error').addClass('validation-success');
	},
	invalidHandler: function(form, validator) {

		if (!validator.numberOfInvalids())
			return;

		/*$('html, body').animate({
			scrollTop: $(validator.errorList[0].element).offset().top - 80
		}, 1000);*/

	}
};
