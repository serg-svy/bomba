<script>
    $(function(){
        let data = '<?php echo json_encode($active_filters); ?>';
        let array;
        array = JSON.parse(data);

        displayRealCount(array);

        recalc_filters_counts();

        function displayRealCount(array) {
            if(!$.isEmptyObject(array)) {
                $("input[name^='filters']").each(function () {
                    let _self = $(this);
                    let val = $(this).val();
                    let attribute = $(this).data('attribute');

                    if (typeof array[attribute] !== 'undefined' && typeof array[attribute][val] !== 'undefined') {
                        //_self.closest(".checkbox__item").css({"display": "block"});
                        _self.closest(".checkbox__item").removeClass("countZero");
                        _self.closest(".checkbox__item").find("label .count").text('('+array[attribute][val]+')');
                    } else {
                        //_self.closest(".checkbox__item").css({"display": "none"});
                        _self.closest(".checkbox__item").addClass("countZero");
                        _self.closest(".checkbox__item").find("label .count").text('(0)');
                    }
                });

                $(".catalog__checkboxes").each(function () {
                    let _self = $(this);
                    let item = _self.find('.checkbox__item');
                    let all = item.length;
                    let visible = 0;
                    item.each(function () {
                        if($(this).css("display") === "block") visible++;
                    });

                    if(all !== 0 && visible === 0) {
                        _self.hide();
                    }
                });
            }
        }

        $('.filters input').change(function() {
            $('#page').val(1);
            reload_flt();
        });

        $(document).on("change", "input[name='store-temp']", function() {
            let val = $(this).val();
            let text = $(this).closest(".checkbox__item").find(".spans span:first").text();
            let head = $(this).closest(".sort__block").find(".block__head span");
            let body = $(this).closest(".block__body");
            head.text(text);
            $("#store").val(val);
            body.toggle();

            reload_flt();
        });

        $(document).on("click", ".body__li", function() {
            let text = $(this).text();
            let key = $(this).data('key');
            let name = $(this).data('name');
            let head = $(this).closest(".sort__block").find(".block__head");
            let body = $(this).closest(".block__body");
            head.find('span').text(text);
            head.removeClass('h4-op');
            body.toggle();
            $('#'+name).val(key);
            $('#page').val(1);

            reload_flt();
        });

        $(document).on("click", ".pagination a, .view-all", function(event){
            event.preventDefault();
            let page = $(this).data('page');
            $('#page').val(page);

            let flag = false;
            if($(this).hasClass('view-all')) flag = true;

            reload_flt(flag);
        });

        function reload_flt(flag=false) {
            let ajax_list = $('.ajax__list');
            let ajax_filters = $('.ajax__filters');
            let ajax_count = $('.product_count');
            let parameters = $('.filters').serialize();
            parameters = parameters.replace(/[^&]+=\.?(?:&|$)/g, '');
            if (parameters!=='') dopsign='?'; else dopsign='';
            history.pushState({}, null, window.location.pathname+dopsign+parameters);
            ajax_list.css({'opacity':0.3});

            let url = window.location.pathname.replace('category','ajax_category')+'?'+parameters;
            url = url.replace('promotions','ajax_promotions');
            url = url.replace('favorites','ajax_favorites');
            url = url.replace('search','ajax_search');
            $.get(url,function(response) {
                let data = JSON.parse(response);
                if(flag) {
                    ajax_list.find('.rht__products').append($(data.view).find('.rht__products').html());
                    ajax_list.find('.view-all').replaceWith($(data.view).find('.view-all'));
                    ajax_list.find('.pagination').replaceWith($(data.view).find('.pagination'));
                } else {
                    ajax_list.replaceWith(data.view);
                }
                ajax_filters.html(data.filters);
                ajax_count.html(data.count);
                ajax_list.css({'opacity':1});
                if(data.redirect) {
                    window.location.href = data.redirect + dopsign + parameters;
                }
                displayRealCount(data.active_filters);
                if(!flag) $('html, body').animate({ scrollTop: ( $('.listing').offset().top - $('.header').height())  }, 1000);

                recalc_filters_counts();

                return false;
            });
        }

        function recalc_filters_counts() {
            $('.category__item').each(function() {
                let count = 0;
                $(this).find("input").each(function (){
                    if ($(this).is(':checked')) {
                        count++;
                    }
                });
                $(this).find('.ft_num').html((count===0) ? '' : count);
            });
        }

        $(document).on("click", ".checkbox__more", function () {
            $(this).closest(".block__checkboxes").find(".checkbox__item").show();
            $(this).remove();
        });

        $(document).on("click", ".f_remove_brand, .f_remove_filter", function() {
            let id = $(this).data('id');
            $("#"+id).attr("checked", false).trigger("click");
        });

        $(document).on("click", ".f_remove_price", function() {
            $("input[name=min_price]").val("");
            $("input[name=max_price]").val("");
            reload_flt();
        });
    });
</script>
<style>
    .active_category_thumb {
        color: #d00a10;
    }
    .active_category {
        background: #d00a10;
        color: #fff !important;
    }
    .countZero {
        display: none !important;
    }
</style>
