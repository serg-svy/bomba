import "./components";

let url = $(location).attr('href').split("/").splice(0, 5).join("/");
let segments = url.split( '/' );
let lang = segments[3];
let two_segment = segments[4];
if('' === lang) {lang = 'ro';}

$(document).ready(function (e) {
	$('.preloader').fadeOut(100, function () {
		$('.preloader').remove();
	});

	$('.cl-pop').click(function () {
		$(this).find('.hint').toggle();
		$(this).closest('.detail').toggleClass('active__detail');
	});

	$('.it').find('span span').click(function () {
		let it = $(this).parent().parent();
		it.find('.it_popup').toggle();
		let itIndex = it.index();

		$(".it").each(function (index){
			if(index !== itIndex) {
				$(this).find('.it_popup').hide();
			}
		});
	});

	$(document).click(function (e) {
		if ($(e.target).parents(".it").length === 0) {
			$('.it_popup').hide();
		}
	});

	$('.breadcrumbs-mob-down').click(function (e) {
		setTimeout(function () {
			$('.breadcrumbs-mob-down').find('.bd-bck').toggle();
			$('.breadcrumbs-mob-down').toggleClass('actv');

		}, 10)
	});

	$(document).scroll(function (e) {
		if($('.simile').length) {
			if ($(window).scrollTop() > $('.simile').offset().top) {
				$('.simile__top-block').addClass('simile__top-block_fix')
				$('.simile').addClass('simile-fix')
			} else {
				$('.simile__top-block').removeClass('simile__top-block_fix')
				$('.simile').removeClass('simile-fix')
			}
		}
	});

	$('.count-item').find('span').click(function (e) {
		$(this).parent().find('ul').toggle()
		$(this).parent().toggleClass('rt')
	});

	$('.popup__arrow__close').click(function (e) {
		e.preventDefault();
		$('.popup__city').removeClass('popup__open')
	});

	$('.write-review').click(function (e) {
		e.preventDefault();
		$('.popup__reviews').addClass('popup__open')
	});

	$('.uslov').click(function (e) {
		e.preventDefault();
		$('.popup__text').addClass('popup__open')
	});

	$('.quick_order').click(function (e) {
		e.preventDefault();
		$('.popup__quick').addClass('popup__open')
	});

	$('.buy_in_credit').click(function (e) {
		e.preventDefault();
		$('.popup__credit').addClass('popup__open')
	});

	$('.preorder').click(function (e) {
		e.preventDefault();
		$('.popup__preorder').addClass('popup__open')
	});

	$(document).on("click", ".reviews__center .toggle_button", function () {
		$(this).closest('.reviews__center').find('.review__item').show();
		$(this).remove();
	});

	$(document).on("click", ".personal_data", function (event) {
		event.preventDefault();
		$('.popup__personal').addClass('popup__open');
	});

	$(document).on("click", '.terms', function (event) {
		event.preventDefault();
		$('.popup__terms').addClass('popup__open');
	});

	$('.product__func').find('.table').click(function (e) {
		e.preventDefault();
		$('.popup__table').addClass('popup__open');
	});

	$('.popup__close, .btn5').click(function (e) {
		$('.popup').removeClass('popup__open')
	});

	$('.catalog__popup').find('.lft__li').click(function (e) {
		$('.catalog__popup').find('.lft__li').removeClass('lft__li_active')
		$(this).addClass('lft__li_active')
		var indexThis = $(this).index()
		$('.catalog__popup').find('.rht__li-item').each(function (e) {
			if ($(this).index() == indexThis){
				$('.catalog__popup').find('.rht__li-item').hide()
				$(this).show()
			}else{
				$(this).hide()
			}
		});
	});

	$('.filter__btn').click(function (e) {
		$('.category').show()
	});

	$('.category__item .cath4').click(function (e) {
		$(this).toggleClass('h4-op')
	});

	$('.settings__block .cath4').click(function (e) {
		$(this).parent().find('.settings__body').toggle()
		$(this).toggleClass('h4-op')
	});

	$('.category__close').click(function (e) {
		$('.category').hide()
	});

	$('.sort__block').find('.block__head').click(function (e) {
		$(this).parent().find('.block__body').toggle()
		$(this).toggleClass('h4-op')
	});

	$(document).on("mouseleave", ".sort__block", function() {
		$(this).find('.block__body').hide();
		$(this).find('.block__head').removeClass('h4-op');
	});

	$('.header__catalog_mob').click(function (e) {
		$('.menu__bgd').show();
		$('body').css('overflow-y', 'hidden');
	});

	$(document).mouseup(function (e) {
	    let tooltip = $('.hint');
		if (!tooltip.is(e.target) && tooltip.has(e.target).length === 0)
		{
			tooltip.hide();
			tooltip.closest('.detail').removeClass('active__detail');
		}
	});

	$('.menu__close').click(function (e) {
		$('.menu__bgd').hide();
		$('body').css('overflow-y', 'initial');
	});

	let timer = 0;

	$(document).on("keyup click", ".search_form input", function (event){

		let _self = $(this);
		clearTimeout(timer);
		let restRequest = 0;

		timer = setTimeout(function() {

			let query = _self.val();
			let action = _self.closest("form").data("keyup");
			if(query.length > 3) {
				restRequest++;
				$('.dropdown__img').addClass('loading');
				if(event.type === "keyup") {
					$.get(action, {query: query}, function (result) {
						restRequest--;
						$('.dropdown__img').removeClass('loading');
						let data = JSON.parse(result);
						$(".popup__input").html(data.products).show();
						$(".arrow__block").html(data.categories).show();
						$(".close__search").show();
					});
					rrApiOnReady.push(
						function() {
							try {
								rrApi.search(query);
							}
							catch(e) {}
						}
					);
				} else {
					if($.trim($(".popup__input").html()).length !== 0 ) {
						$(".popup__input").show();
					}
				}
			} else {
				if(query.length === 0) {
					let previous = _self.closest("form").data("previous");
					$.get(previous, function (result) {
						let data = JSON.parse(result);
						if(data.count > 0) {
							$(".popup__input").html(data.products).show();
						} else {
							$(".popup__input").hide();
							$(".arrow__block").hide();
						}
					});
				} else {
					$(".popup__input").hide();
					$(".arrow__block").hide();
				}
			}

		}, 1500);
	});

	$(document).on("submit", ".search_form", function(event) {
		let _self = $(this);
		let action = _self.attr('action');
		let query = action.split( '=' )[1];
		if(_self.find("input").val().length < 3 || query === '') {
			event.preventDefault();
		}
	});

	$(document).on("click", ".index-popup__list li", function (e) {
		$('.index-popup__list').find('li').removeClass('li_active');
		$(this).addClass('li_active');

		let value = $(this).data("id");
		let url = $(this).closest(".city__list").data("url");
		let popup = $(this).closest(".popup");

		$.post(url,{value: value}, function(data){
			popup.removeClass("popup__open");
			$(".city_not_found").text('');
			$(".index-popup__input input").val('');
			$(".city_title").html(data.city);
		}, 'json');
	});

	$(document).on("click", ".delivery__city-list li", function (e) {

		let value = $(this).data("id");
		let url = $(this).closest(".city__list").data("url");

		$.post(url,{value: value}, function(data){
			$(".city__list").html("");
			$(".city_title").html(data.city);
			$(".delivery__select-city input").val(data.city);
			$(".delivery__text-courier").html(data.text_courier);
			$(".delivery__text-pickup").html(data.text_pickup);
		}, 'json');
	});

	$(document).on("click", ".set_city_automatically", function () {
		let url = $(this).data("url");
		let popup = $(this).closest(".popup");
		$.post(url, function(data){
			if(data.response) {
				popup.removeClass("popup__open");
				$(".city_not_found").text('');
				$(".index-popup__input input").val('');
				$(".city_title").html(data.city);
			} else {
				$(".city_not_found").text(data.message);
			}
		}, 'json');
	});

	$(document).mouseup(function (e) {
	    var container = $('.popup__input');
	    if (container.has(e.target).length === 0){
	        $('.popup__input').hide()
	    }
	});

	$('.dropdown__img').click(function (e) {
		$(this).toggleClass('dropdown__img_active')
		$('.popup__arrow').toggle()
	});

	$(document).mouseup(function (e) {
	    var container = $('.popup__arrow');
	    if (container.has(e.target).length === 0){
	        $('.popup__arrow').hide()
	        $('.dropdown__img').removeClass('dropdown__img_active')
	    }
	});

	$('.header__catalog').click(function (e) {
		$(this).find('img').toggle();
		$(this).toggleClass('header__catalog_active');
		$('.catalog__popup').toggle();
	});

	$(document).click(function (e) {
		if ($(e.target).parents(".header__catalog").length === 0 && $(e.target).parents(".catalog__popup").length === 0) {
			if($('.header__catalog').hasClass('header__catalog_active')) {
				$('.header__catalog').find('img').toggle();
				$('.header__catalog').toggleClass('header__catalog_active');
				$('.catalog__popup').toggle();
			}
		}
	});

	$(document).click(function (e) {
		if ($(e.target).parents(".popup__cart").length === 0) {
			if($('.popup__cart').hasClass('popup__open')) {
				$('.popup').removeClass('popup__open');
			}
		}
	});

	$('.footer__item').not('.footer__item_last').find('.footer-h5').click(function () {
		$(this).parent().find('ul').slideToggle()
		$(this).toggleClass('footer-h5_active')
	});

	$('.simile__list').find('li').click(function (e) {
		$('.simile__list').find('li').removeClass('li_active')
		$(this).addClass('li_active')
	});

	$('.color__head').click(function (e) {
		$(this).parent().find('.color__body').toggle()
	});

	$('.product__func').find('ul li').click(function (e) {
		$('.product__func').find('ul li').removeClass('li__active')
		$(this).addClass('li__active')
	});

	$('.toggle_button').click(function (e) {
		e.preventDefault();
		$(this).parent().find('.toggle_text').toggleClass('text-open');
		let text = $(this).text();
		let newText = $(this).data('new');
		$(this).text(newText).data('new', text);
	});

	$('.shares-head').click(function() {
		let current = $(this).parent().index();
		$(".shares").each(function(e) {
			if($(this).index() !== current) $(this).removeClass('shares__open');
		});
		if($(this).parent().hasClass('shares__open')) {
			$(this).parent().removeClass('shares__open');
		} else {
			$('.shares-head').parent().removeClass('shares__open');
			$(this).parent().addClass('shares__open');
		}
		$(this).parent().find(".list-select li a").first().trigger("click");
	});

	$('.credit .shares-head').click(function() {
		$('html, body').animate({ scrollTop: ( $(this).offset().top - $('.header').height())  }, 1000);
	});

	$(document).on("click", ".harak", function (){
		$('html, body').animate({ scrollTop: ( $("#specifications").offset().top - $('.header').height())  }, 500);
	});

	$(document).on("click", ".allrev", function (){
		$('html, body').animate({ scrollTop: ( $(".reviews").offset().top - $('.header').height())  }, 500);
	});

	$(document).on("click", ".list-select li", function () {
		$(this).closest("ul").find("li").removeClass("list_active");
		$(this).addClass("list_active");
	});

	$('.content-rht-item').click(function (e) {
		$('.content-rht-item').removeClass('content-rht-active');
		$(this).addClass('content-rht-active');
	});

	$('.left__banner').on('init', function (event, slick, direction) {
		if (($('.left__banner .slick-slide').length === 1)) {
			$('.left__banner .slick-dots').hide();
		}
	});

	$('.right__banner').on('init', function (event, slick, direction) {
		if (($('.right__banner .slick-slide').length === 1)) {
			$('.right__banner .slick-dots').hide();
		}
	});

	$('.left__banner').slick({
		autoplay: true,
		dots: true,
		arrows: false,
	});

	$('.right__banner').slick({
		dots: true,
		arrows: false,
	});

	$('.slider-for').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: false,
	  fade: true,
	  asNavFor: '.slider-nav'
	});
	$('.slider-nav').slick({
	  slidesToShow: 5,
	  slidesToScroll: 1,
	  asNavFor: '.slider-for',
	  focusOnSelect: true,
	  dots: false,
	  swipeToSlide: true,
	  responsive: [
	  	{
	  		breakpoint: 768,
	  		settings: {
	  			arrows: false
	  		}
	  	}
	  ]
	});

	$('.slider-for-1').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: false,
	  fade: true,
	  asNavFor: '.slider-nav-1'
	});
	$('.slider-nav-1').slick({
	  slidesToShow: 4,
	  slidesToScroll: 1,
	  asNavFor: '.slider-for-1',
	  focusOnSelect: true,
	  dots: false,
	  swipeToSlide: true,
	  responsive: [
	  	{
	  		breakpoint: 768,
	  		settings: {
	  			arrows: false
	  		}
	  	}
	  ]
	});

	function sliderLftPhoto() {

		let sliderLftPhoto = $('.slider__lft-photo');
		if(sliderLftPhoto.hasClass('slick-slider')) sliderLftPhoto.slick('unslick');

		sliderLftPhoto.slick({
			dots: false,
			arrows: true,
			slidesToShow: 4,
			swipeToSlide: true,
			responsive: [
				{
					breakpoint: 1300,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 1026,
					settings: {
						arrows: false,
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 992,
					settings: {
						arrows: false,
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 768,
					settings: "unslick"
				},
			]
		});
	}

	sliderLftPhoto();

	let width = $(window).width();
	$(window).on('resize', function() {
		if ($(this).width() !== width) {
			width = $(this).width();
			sliderLftPhoto();
		}
	});

	$('.slider__tovars121').slick({
		dots: false,
		arrows: true,
		slidesToShow: 4,
		swipeToSlide: true,
		responsive:[
		{
			breakpoint: 1300,
			settings: {
				slidesToShow: 3,
			}
		},
		{
			breakpoint: 992,
			settings: "unslick"
		},
		]
	});

	$('.round-blocks').slick({
		slidesToShow: 10,
		infinite: false,
		swipeToSlide: true,
		responsive:[
		{
			breakpoint: 1300,
			settings: {
				slidesToShow: 7,
			}
		},
		{
			breakpoint: 992,
			settings: 'unslick'
		}
		]
	});

	$('.slider__tovars').slick({
		dots: false,
		arrows: true,
		slidesToShow: 5,
		swipeToSlide: true,
		responsive:[
		{
			breakpoint: 1300,
			settings: {
				slidesToShow: 4,
			}
		},
		{
			breakpoint: 1200,
			settings: {
				slidesToShow: 3,
				// arrows: false,
			}
		},
		{
			breakpoint: 992,
			settings: "unslick"
		},
		]
	});

	$(document).on("submit", "#subscribe", function(event) {
		event.preventDefault();
		let _self = $(this);
		let email = _self.find("input[type='email']").val();
		let url = _self.attr("action");
		$.post(url,{email: email}, function(data){
			_self.find("input[type='email']").val("");
			_self.closest(".subscribe__block").find(".index-h3").html(data.message);
		}, 'json');

		(window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
			try { rrApi.setEmail(email); } catch(e) {}
		})
	});

	$('.banner__slider').slick({
		dots: false,
		arrows: false,
		autoplay: true,
	});

	$(document).on("click", ".select_locality", function(event) {
		event.preventDefault();
		$('.popup__city').addClass('popup__open')

		let input = $('.index-popup__input input');
		let value = input.val();
		let url = input.data("url");

		$.post(url,{value: value}, function(data){
			$(".city__list").html(data.html);
		}, 'json');
	});

	$(document).on("keyup", ".index-popup__input input, .delivery__select-city input", function(event) {
		event.preventDefault();
		let value = $(this).val();
		let url = $(this).data("url");

		$.post(url,{value: value}, function(data){
			$(".city__list").html(data.html);
		}, 'json');

	});

	$(".content-top p iframe").parents("p").css({'text-align':'center', 'position':'relative', 'padding-bottom' : '56.25%', 'height' : '0'});

	$(document).on("click", ".respond-vacancy", function (event) {
		event.preventDefault();
		let title = $(this).data("title");
		$(".block-vacancy h3").text(title);
		let id = $(this).data("id");
		$("input[name='vacancy[id]']").val(id);
		$(".popup__vacancy").find("option").each(function() {
			if($(this).hasClass('vacancy-'+id)) {
				$(this).show();
			} else {
				$(this).hide();
				if($(this).is(":selected")) {
					$(".magazin-head").val('');
				}
			}
		});
		$(".popup__vacancy").addClass("popup__open");
	});

	$(document).on("click", ".remove_popup", function (event) {
		event.preventDefault();
		$(this).closest(".popup").remove();
	});

	$(document).on('change', 'input[name="color"]', function () {
		const box = $(this).closest('.bv_data');
		const articol = box.data('articol');
		const size = box.data('size');
		const color = $(this).val();

		box.data('color', color);
		get_disponible_sizes(articol, color, size);
	});

	$(document).on('change', 'input[name="size"]', function () {
		const box = $(this).closest('.bv_data');
		const articol = box.data('articol');
		const color = box.data('color');
		const size = $(this).val();

		box.data('size', size);
		get_disponible_colors(articol, color, size);
	});

	$(document).on('click', '.add_to_cart', function(event){
		event.preventDefault();
		const box = $(this).closest('.bv_data');
		const articol = box.data('articol');
		const id = box.data('id');
		const color = box.data('color');
		const size = box.data('size');
		const choose_color = $('.prod_color');
		const choose_size = $('.prod_size');

		if (color == null) {
			choose_color.addClass('animated shake');
			setTimeout(function () {
				choose_color.removeClass('animated shake');
			}, 1000);
		}

		if (size == null) {
			choose_size.addClass('animated shake');
			setTimeout(function () {
				choose_size.removeClass('animated shake');
			}, 1000);
		}

		if (color == null || size == null) {
			return false;
		}

		myAddToCart(articol, id, color, size, 1);
	});

	$(document).on('click', '.check_color_and_size', function(event){
		event.preventDefault();
		let _self = $(this);
		let _alternative = _self.data('alternative');
		const box = $(this).closest('.bv_data');
		const articol = box.data('articol');
		const id = box.data('id');

		$.post('/'+lang+'/ajax/check_color_and_size/', {articol: articol, id: id}, function (result) {
			let data = JSON.parse(result);

			if(data.status) {
				_self.removeClass('btn').addClass('btn4').val(_alternative);
				myAddToCart(articol, id, data.color, data.size, 1);
			}
		});
	});

	$(document).on("click", ".minus, .plus", function() {
		const box = $(this).closest('.bv_data');
		const articol = box.data('articol');
		const id = box.data('id');
		const color = box.data('color');
		const size = box.data('size');
		const qty = box.find("input").val();

		myAddToCart(articol, id, color, size, qty, true);
	});

	$(document).on("click", ".add_to_favorite, .delete_favorite", function(event) {
		event.preventDefault();
		const _self = $(this);
		const box = _self.closest('.bv_data');
		const id = box.data('id');

		$.post('/'+lang+'/ajax/add_to_favorite/', {id: id}, function (result) {
			let data = JSON.parse(result);
			if(data.action === 'add') {
				_self.addClass('active');
			} else {
				_self.removeClass('active');
				if(_self.hasClass('delete_favorite')) {
					window.location.reload();
				}
			}
			$(".favorite_count p").attr("data-count", data.count).css('z-index', 99);
		});
	});

	$(document).on("mouseenter", ".bottom__li", function() {
		$(".popup__search").hide();
	});

	$(document).on("click", ".add_to_compare, .delete_compare", function(event) {
		event.preventDefault();
		const _self = $(this);
		const box = _self.closest('.bv_data');
		const id = box.data('id');

		$.post('/'+lang+'/ajax/add_to_compare/', {id: id}, function (result) {
			let data = JSON.parse(result);
			if(data.action === 'add') {
				_self.addClass('active');
				_self.closest('div').find(".info").css('visibility', 'visible');
				setTimeout(function(){
					_self.closest('div').find(".info").css('visibility', 'hidden');
				}, 2000);
			} else {
				_self.removeClass('active');
				_self.closest('div').find(".info").css('visibility', 'hidden');
				if(_self.hasClass('delete_compare')) {
					window.location.reload();
				}
			}
			$(".compare_count p").attr("data-count", data.count).css('z-index', 99);
		});
	});

	$(document).on("click", ".list_clean", function(event) {
		event.preventDefault();

		if (confirm(window.translations.sure) === true) {
			const _self = $(this);
			const ids = _self.data('ids');

			$.post('/' + lang + '/ajax/list_clean/', {ids: ids}, function (result) {
				window.location.reload();
			});
		}
	});

	$(document).on("change", "#sr1", function () {
		if($(this).is(":checked")) {
			$(".same").css("display", "none");
			$(".settings__block").each(function() {
				let cnt1 = $(this).find(".settings__body").length;
				let cnt2 = $(this).find(".settings__body:hidden").length;

				if(cnt1 === cnt2) {
					$(this).find('h4').hide();
				}
			});
		} else {
			$(".same").css("display", "flex");
			$(".settings__block").each(function() {
				$(this).find('h4').show();
			});
		}
	});

	$(".simile-slider").slick({
		dots: false,
		arrows: false,
		slidesToShow: 4,
		speed: 1,
		responsive: [
			{
				breakpoint: 1300,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 1026,
				settings: {
					slidesToShow: 2,
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
				}
			}
		]
	});

	$(".simile-slider-settings").slick({
		dots: false,
		arrows: false,
		slidesToShow: 4,
		speed: 1,
		responsive: [
			{
				breakpoint: 1300,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 1026,
				settings: {
					slidesToShow: 2,
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
				}
			}
		]
	});

	$(".simile-slider-settings").slick("slickSetOption", "draggable", false);
	$(".simile-slider-settings").slick("slickSetOption", "swipe", false);

	$(document).on("click", ".arrow-rht", function (){
		$(".simile-slider").slick("slickNext");
	});

	$(document).on("click", ".arrow-lft", function (){
		$(".simile-slider").slick("slickPrev");
	});

	$(document).on("click", ".header__menu > ul > li", function (){
		$(this).toggleClass("active");
	});

	$(document).on("change", ".credit_month", function() {
		let cType = $(this).data('type');
		$(".credit_type").val(cType);
	});

	$(document).on("click", ".close__search", function (){
		$(this).hide();
		$(".search_form").find("input").val('');
	});
});

function myAddToCart(articol, id, color, size, qty, change=false) {
	$.post('/'+lang+'/cart/add/', {articol: articol, id: id, color: color, size: size, qty: qty, change:change}, function (result) {
		let data = JSON.parse(result);
		//if(data.change==='false') $(".popup__cart").addClass("popup__open");
		$(".cart_num").attr("data-count", data.qty).css('z-index', 99);
		$(".qty").text(data.qty);
		$(".total").text(data.total);
		$(".prod_btn").html(data.productBtn);
		$('[data-key="'+data.idCrypted+'"]').text(data.itemTotal);

		(window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
			try { rrApi.addToBasket(data.id); } catch(e) {}
				})
	});
}

function get_disponible_sizes(articol, color, size) {
	$.post('/'+lang+'/ajax/get_disponible_sizes/', {articol: articol, color: color, size: size}, function (data) {

		let box = $('.prod_size');
		let res = jQuery.parseJSON( data );

		box.find( "label span").removeClass('disabled');
		box.find( "label").css("pointer-events", "auto");

		let array = $.map(res.ids, function(value, index) {
			return [value];
		});

		box.find( "input").each(function( index ) {
			let size = $(this).val();
			if(jQuery.inArray( size.toString(), array) === -1) {
				$(this).parents('label').find("span").addClass("disabled");
				$(this).parents('label').css("pointer-events", "none");
			}
		});

		$(".prod_btn").html(res.productBtn);
	});
}

function get_disponible_colors(articol, color, size) {
	$.post('/'+lang+'/ajax/get_disponible_colors/', {articol: articol, color:color, size: size}, function (data) {

		let box = $('.prod_color');
		let res = jQuery.parseJSON( data );

		box.find( "label span").removeClass('disabled');
		box.find( "label").css("pointer-events", "auto");

		let array = $.map(res.ids, function(value, index) {
			return [value];
		});

		box.find( "input").each(function( index ) {
			let color = $(this).val();
			if(jQuery.inArray( color.toString(), array) === -1) {
				$(this).parents('label').find("span").addClass("disabled");
				$(this).parents('label').css("pointer-events", "none");
			}
		});

		$(".prod_btn").html(res.productBtn);
	});
}
