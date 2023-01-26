import { errorValidation } from './utils';

let vacancyTelInput = $("#vacancy_phone");

vacancyTelInput.intlTelInput({
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
vacancyTelInput.focusout(function () {
    if ($.trim(vacancyTelInput.val())) {
        if (vacancyTelInput.intlTelInput("isValidNumber")) {
        } else {
            vacancyTelInput.addClass("error");
        }
    }
});

// on keydown: reset
vacancyTelInput.keydown(function () {
    vacancyTelInput.removeClass("error");
});


$.validator.addMethod('validateVacancyPhone', function () {
    if (vacancyTelInput.intlTelInput("isValidNumber")) {
        return true;
    } else {
        return false;
    }
}, window.translations.phoneInvalid);

(() => {
    const $form = $("form[name='vacancy']");
    $form.validate({
        ...errorValidation,
        errorClass: 'label-error',
        rules: {
            'vacancy[name]': {
                required: true,
            },
            'vacancy[surname]': {
                required: true,
            },
            'vacancy[phone]': {
                required: true,
                validateVacancyPhone: true
            },
            'vacancy[file]': {
                required: true,
                accept: ".pdf,.docx"
            },
            'vacancy[store_id]': {
                required: true,
            },
            'vacancy[personal_data]': {
                required: true,
            },
            'vacancy[terms]': {
                required: true,
            },
        },
        messages: {
            'vacancy[name]': {
                required: window.translations.required,
            },
            'vacancy[surname]': {
                required: window.translations.required,
            },
            'vacancy[phone]': {
                required: window.translations.required,
                validateVacancyPhone: window.translations.phoneInvalid,
            },
            'vacancy[file]': {
                required: window.translations.required,
                accept: "huinea"
            },
            'vacancy[store_id]': {
                required: window.translations.required,
            },
            'vacancy[personal_data]': {
                required: window.translations.required,
            },
            'vacancy[terms]': {
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
