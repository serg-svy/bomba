import { errorValidation } from './utils';

let preorderTelInput = $("#preorder_phone");

preorderTelInput.intlTelInput({
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
preorderTelInput.focusout(function () {
    if ($.trim(preorderTelInput.val())) {
        if (preorderTelInput.intlTelInput("isValidNumber")) {
        } else {
            preorderTelInput.addClass("error");
        }
    }
});

// on keydown: reset
preorderTelInput.keydown(function () {
    preorderTelInput.removeClass("error");
});


$.validator.addMethod('validatepreorderPhone', function () {
    if (preorderTelInput.intlTelInput("isValidNumber")) {
        return true;
    } else {
        return false;
    }
}, window.translations.phoneInvalid);

(() => {
    const $form = $("form[name='preorder']");
    $form.validate({
        ...errorValidation,
        errorClass: 'label-error',
        rules: {
            'preorder[name]': {
                required: true,
            },
            'preorder[phone]': {
                required: true,
                validatepreorderPhone: true
            },
            'preorder[email]': {
                required: true,
                email: true,
            },
        },
        messages: {
            'preorder[name]': {
                required: window.translations.required,
            },
            'preorder[phone]': {
                required: window.translations.required,
                validatepreorderPhone: window.translations.phoneInvalid,
            },
            'preorder[email]': {
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
