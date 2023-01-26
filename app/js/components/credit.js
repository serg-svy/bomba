import { errorValidation } from './utils';

let creditTelInput = $("#credit_phone");

creditTelInput.intlTelInput({
    initialCountry: 'md',
    placeholderNumberType: 'aggressive',
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js",
    separateDialCode: true,
    preferredCountries: ["md"],
    onlyCountries: ["ro", "ua", "ru", "de", "us", "md"]
}).inputmask({
    mask: "V9999999",
    definitions: {
        "V": {
            validator: "^[1-9][0-9]*$",
        }
    }
});

// on blur: validate
creditTelInput.focusout(function () {
    if ($.trim(creditTelInput.val())) {
        if (creditTelInput.intlTelInput("isValidNumber")) {
        } else {
           creditTelInput.addClass("error");
        }
    }
});

// on keydown: reset
creditTelInput.keydown(function () {
   creditTelInput.removeClass("error");
});


$.validator.addMethod('validateCreditPhone', function () {
    if (creditTelInput.intlTelInput("isValidNumber")) {
        return true;
    } else {
        return false;
    }
}, window.translations.phoneInvalid);

(() => {
    const $form = $("form[name='credit']");
    $form.validate({
        ...errorValidation,
        errorClass: 'label-error',
        rules: {
            'credit[phone]': {
                required: true,
                validateCreditPhone: true
            },
            'credit[month]': {
                required: true,
            },
        },
        messages: {
            'credit[phone]': {
                required: window.translations.required,
                validateCreditPhone: window.translations.phoneInvalid,
            },
            'credit[month]': {
                required: window.translations.required,
            },
        },
        submitHandler: (form) => {
            form.submit();
        },
        success: function (label) {
            let name = label.attr('for');
            label.text('');
        },
    });

    $form.on("keyup click change", "input", () => {
        if ($form.valid()) {
            $(".btn").removeAttr("disabled");
        } else {
            $(".btn").attr("disabled", "disabled");
        }
    });
})();
