import { errorValidation } from './utils';

(() => {
    const $form = $("form[name='contact']");
    $form.validate({
        ...errorValidation,
        errorClass: 'label-error',
        rules: {
            'contact[name]': {
                required: true,
            },
            'contact[email]': {
                required: true,
                email: true,
            },
            'contact[question]': {
                required: true,
            },
            'contact[personal_data]': {
                required: true,
            },
            'contact[terms]': {
                required: true,
            },
        },
        messages: {
            'contact[name]': {
                required: window.translations.required,
            },
            'contact[email]': {
                required: window.translations.required,
                email: window.translations.emailInvalid,
            },
            'contact[question]': {
                required: window.translations.required,
            },
            'contact[personal_data]': {
                required: window.translations.required,
            },
            'contact[terms]': {
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

    $form.on("keyup click", "input", () => {
        if ($form.valid()) {
            $(".btn").removeAttr("disabled");
        } else {
            $(".btn").attr("disabled", "disabled");
        }
    });
})();
