let url = $(location).attr('href').split("/").splice(0, 5).join("/");
let segments = url.split( '/' );
let lang = segments[3];
let two_segment = segments[4];
if('' === lang) {lang = 'ro';}

$(document).ready(function(e) {

    $(document).on("click", ".bi1_del", function (event) {
        event.preventDefault();
        let url = $(this).data('url');
        let key = $(this).data('key');
        $.post(url, {key:key}, function () {
            window.location.reload();
        });
    });

    /*Карусель*/
    $('.carousel').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        rows: 0,
        // autoplay: true,
        // autoplaySpeed: 5000,
        // arrows: false,
        responsive: [
            {
                breakpoint: 981,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 731,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 501,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '60px'
                }
            },
            {
                breakpoint: 376,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '50px'
                }
            }
        ]
    });

    /*Форма*/
    $('input, select').styler({
        selectPlaceholder: '',
        selectVisibleOptions: 5
    });

    $('.code-uds input').styler('destroy');

    /*Вкладки*/
    $('.tabs_wr').tabs();

    $('.close-know').click(function () {
        $('.know__block').slideUp()
    });

    $('.click-know').click(function () {
        $('.know__block').slideToggle()
    });

    $(document).on("click", "#find_uds", function (event){
        event.preventDefault();
        let form = $(this).closest("form");
        let code = form.find("input[name='code']").val();
        let total = form.find("input[name='total']").val();
        let action = form.attr("action");

        if(code!=='') {
            $.post(action, {code: code, total:total}, function (response) {
                let data = jQuery.parseJSON( response );
                if(data.status === 'success') {
                    form.find("input[name='code']").val('');
                    $(".find_uds").hide();
                    $(".dop-vigoda").append(data.view);
                } else {
                    $(".return__message").text(data.message);
                }
            });
        }
    });

    $(document).on("click", ".change_code", function (event){
        event.preventDefault();
        let action = $(this).attr("href");
        $.post(action, function (response) {
            $(".use_uds").remove();
            $(".find_uds").show();
            $(".basket__ajax").html("");
            $(".total").text(response);
        });
    });

    $(document).on("change", "input[name='points']", function(){
        let points = $(this).val();
        let max = $(this).attr("max");
        if(parseInt(points) > parseInt(max)) {
            $(this).val(max);
        }
    });

    $(document).on("click", "#set_uds", function (event){
        event.preventDefault();
        let form = $(this).closest("form");
        let points = form.find("input[name='points']").val();
        let action = form.attr("action");
        $.post(action, {points: points}, function (response) {
            let data = jQuery.parseJSON( response );
            if(data.status === 'success') {
                $('.uds_percent').text(data.message);
                $(".code-dus_used").html("");
                $(".c_wr2_col2").replaceWith(data.view);
                $(".total").text(data.total);
            }
        });
    });

    let deliveryView = function () {

        let city_id = $("#city_id");

        if(city_id.val() !== '') {
            let url = city_id.data("url");
            let value = city_id.val();
            setCity(url, value);
        }

        $(document).on("click", ".delivery__city-list li", function (e) {

            let url = city_id.data("url");
            let value = $(this).data("id");

            setCity(url, value);
        });

        $(document).on('click', ".tabs_wr > ul > li", function () {
            let id = $(this).data('id');
            $("input[name='order[delivery_type_id]']").val(id);
            if (id === 2) $("input[name='order[store_id]']").val('');
        });

        $(document).on('click', ".map_address .btn", function (event) {
            event.preventDefault();
            $(".map_address .btn").each(function () {
                $(this).removeClass("v1").addClass("v2");
            });
            let self = $(this);
            self.removeClass("v2").addClass("v1");
            let id = self.data('id');
            $("#store_id").val(id);
            $(".map_address_detail").hide();
            $(".map_address_detail_" + id).show();
        });

        $(document).on('click', ".map_address_detail .close", function (event) {
            $("#store_id").val('');
            $(".map_address_detail").hide();
        });

        $(document).on('change', "input[name='order[delivery_time]']", function () {
            let delivery_date = $(this).data('date');
            let delivery_key = $(this).data('key');
            $("input[name='order[delivery_date]']").val(delivery_date);
            $("input[name='order[delivery_key]']").val(delivery_key);
        });

        $(document).on('keyup', "input[name='order[name]']", function () {
            let val = $(this).val();
            if (val === 'test') {
                $("input[name='order[phone]']").val('79999999');
                $("input[name='order[email]']").val('bodarev@ilab.md');
            }
        });

        $(document).on("change", "#delivery_date", function () {
            let day = $(this).find('option:selected').data('day');
            if (day === 6) {
                $("#delivery_time").val("9:00 — 15:00");
            } else {
                $("#delivery_time").val("09:00 — 21:00");
            }
        });

        $(document).on('click', '#submitDeliveryBtn', function (event) {
            event.preventDefault();
            if (validate()) {
                save();
            }
        });

        $(document).on('click', '.f_input.v1', function () {
            $(this).removeClass('v1');
        });

        $(document).on('click', '.change-wr-delivery', function (event) {
            event.preventDefault();
            $(".btnVerify").hide();
            // delivery
            $('.delivery_wr').removeClass('order_wr-done');
            $(".f_cols_a").show();
            $(".ajax_delivery").show();
            $('.tabs_wr').tabs();
            $('input, select').styler({
                selectPlaceholder: '',
                selectVisibleOptions: 5
            });
            $(".time-interval input").styler('destroy');
            $(".point-delivery").empty();

            // payment
            $('.payment_wr').addClass('disabled-block').removeClass('order_wr-done');
            $('.ajax_payment').empty().show();
            $('.point-payment').empty();

            // contact
            $('.contact_wr').addClass('disabled-block').removeClass('order_wr-done');
            $('.ajax_contact').empty().show();
            $('.point-contact').empty();
        });

        function setCity(url, value) {
            $.post(url, {value: value}, function (data) {
                $(".city__list").html("");
                $(".city_title").html(data.city);
                $(".delivery__select-city input").val(data.city);
                city_id.val(value);
                $(".ajax_delivery").html(data.view);
                $('.tabs_wr').tabs({ active: data.active });
                $('input, select').styler({
                    selectPlaceholder: '',
                    selectVisibleOptions: 5
                });
                $(".time-interval input").styler('destroy');
            }, 'json');
        }

        function validate() {
            let error = 0;

            if ($('#delivery_type_id').val() === "2") {
                $('#tab_a1').find('.required').each(function () {
                    if (!$(this).val().length) {
                        $(this).addClass('v1').focus();
                        error = 1;
                    }
                });

                if ($("input[name='order[delivery_time]']").is(':radio') && !$("input[name='order[delivery_time]']").is(':checked')) {
                    $('.time-interval').css({"border": "1px solid #d00a10"});
                    error = 1;
                }

                if (error) {
                    new Noty({
                        text: window.translations.required,
                        theme: 'semanticui',
                        type: 'error',
                        timeout: 2500
                    }).show();
                }
            } else if ($('#delivery_type_id').val() === "4") {
                if ($('#store_id').val() === '') {
                    new Noty({
                        text: window.translations.selectStore,
                        theme: 'semanticui',
                        type: 'error',
                        timeout: 2500
                    }).show();

                    error = 1;
                }
            } else {
                error = 1;
            }

            return !error;
        }

        function save() {
            let data = $('.delivery_wr :input').serialize();
            $.post("/"+lang+"/ajax/set_delivery/", data, function (response) {

                let data = JSON.parse(response);

                $('.delivery_wr').addClass('order_wr-done');
                $(".ajax_delivery").hide();

                $(".point-delivery").html(data.text);
                $(".f_cols_a").hide();

                $('.payment_wr').removeClass('disabled-block');
                $('.ajax_payment').html(data.payment);

                $('input, select').styler({
                    selectPlaceholder: '',
                    selectVisibleOptions: 5
                });
            });
        }

        return {
            validate: validate,
            save: save,
        }
    }

    let paymentView = function() {
        $(document).on('click', '#submitPaymentBtn', function (event) {
            event.preventDefault();
            if (validate()) {
                save();
            }
        });

        $(document).on('click', '.change-wr-payment', function (event) {
            event.preventDefault();
            $(".btnVerify").hide();
            // paument
            $('.payment_wr').removeClass('order_wr-done');
            $(".ajax_payment").show();
            $(".point-payment").empty();

            // contact
            $('.contact_wr').addClass('disabled-block').removeClass('order_wr-done');
            $('.ajax_contact').empty().show();
            $(".point-contact").empty();
        });

        function validate() {
            let error = 0;

            if(!$("input[name='order[payment_type_id]']").val().length) error = 1;

            return !error;
        }

        function save() {
            let data = $('.payment_wr :input').serialize();
            $.post("/"+lang+"/ajax/set_payment/", data, function (response) {

                let data = JSON.parse(response);

                // payment
                $('.payment_wr').addClass('order_wr-done');
                $(".point-payment").html(data.text);
                $(".ajax_payment").hide();

                // contact
                $('.contact_wr').removeClass('disabled-block');
                $('.ajax_contact').html(data.payment);


                $(".tel").intlTelInput({
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
            });
        }
    }

    let contactView  = function() {
        $(document).on('click', '#submitContactBtn', function (event) {
            event.preventDefault();
            if (validate()) {
                save();
            }
        });

        $(document).on('change', '#is_gift', function () {
            let giftInput = $('.gift_row input');
            if ($(this).is(':checked')) {
                $('.gift_row').slideDown();
                giftInput.addClass("required").attr("required", true);
            }else{
                $('.gift_row').slideUp();
                giftInput.removeClass("required").attr("required", false).val("");
            }
        });

        $(document).on('click', '.change-wr-contact', function (event) {
            event.preventDefault();
            $(".btnVerify").hide();
            // contact
            $('.contact_wr').removeClass('order_wr-done');
            $(".ajax_contact").show();
            $(".point-contact").empty();
        });

        function validate() {

            let firstPhone = $('.required.firstTel');
            let secondPhone = $('.required.secondTel');
            let email = $('.email');
            let first = false;

            let messages = [];

            $('.contact_wr .required').removeClass('v1');

            $('.contact_wr').find('.required').each(function() {
                if (!$(this).val().length) {
                    if(!first) first = $(this);
                    $(this).addClass('v1');
                    messages[0] = window.translations.required;
                }
            });

            if (!firstPhone.intlTelInput("isValidNumber")) {
                if(!first) first = $(this);
                firstPhone.addClass('v1');
                messages[1] = window.translations.phoneInvalid;
            }

            if (!secondPhone.intlTelInput("isValidNumber")) {
                if(!first) first = $(this);
                secondPhone.addClass('v1');
                messages[1] = window.translations.phoneInvalid;
            }

            if(!isEmail(email.val())) {
                if(!first) first = $(this);
                email.addClass('v1');
                messages[2] = window.translations.emailInvalid;
            }

            $.each(messages, function(index, value) {
                if(value !== undefined) {
                    new Noty({
                        text: value,
                        theme: 'semanticui',
                        type: 'error',
                        timeout: 2500
                    }).show();
                }
            });

            if(first) first.focus();
            if(first) return false;

            if ($('#personal_data:checked').length == 0 || $('#terms:checked').length == 0) {
                new Noty({
                    text: window.translations.acceptTerms,
                    theme: 'semanticui',
                    type: 'error',
                    timeout: 2500
                }).show();

                return false;
            }

            return true;
        }

        function isEmail(email) {
            let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test( email );
        }

        function save() {
            let data = $('.contact_wr :input').serialize();

            $.post("/"+lang+"/ajax/set_contact/", data, function (response) {

                let data = JSON.parse(response);

                $(".btnVerify").show();
                $(".btnVerify form").submit();

                // contact
                $('.contact_wr').addClass('order_wr-done');
                $(".point-contact").html(data.text);
                $(".ajax_contact").hide();
            });
        }

        return {
            validate: validate,
            save: save,
        }
    }

    deliveryView();
    paymentView();
    contactView();
});
