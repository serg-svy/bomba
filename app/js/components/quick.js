import { errorValidation } from './utils';

let quickTelInput = $("#quick_phone");

quickTelInput.intlTelInput({
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
quickTelInput.focusout(function () {
    if ($.trim(quickTelInput.val())) {
        if (quickTelInput.intlTelInput("isValidNumber")) {
        } else {
            quickTelInput.addClass("error");
        }
    }
});

// on keydown: reset
quickTelInput.keydown(function () {
    quickTelInput.removeClass("error");
});


$.validator.addMethod('validatequickPhone', function () {
    if (quickTelInput.intlTelInput("isValidNumber")) {
        return true;
    } else {
        return false;
    }
}, window.translations.phoneInvalid);

(() => {
    const $form = $("form[name='quick']");
    $form.validate({
        ...errorValidation,
        errorClass: 'label-error',
        rules: {
            'quick[name]': {
                required: true,
            },
            'quick[phone]': {
                required: true,
                validatequickPhone: true
            },
            'quick[email]': {
                required: true,
                email: true,
            },
        },
        messages: {
            'quick[name]': {
                required: window.translations.required,
            },
            'quick[phone]': {
                required: window.translations.required,
                validatequickPhone: window.translations.phoneInvalid,
            },
            'quick[email]': {
                required: window.translations.required,
                email: window.translations.emailInvalid,
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

    $form.on("keyup click", "input", () => {
        if ($form.valid()) {
            $(".btn").removeAttr("disabled");
        } else {
            $(".btn").attr("disabled", "disabled");
        }
    });
})();
