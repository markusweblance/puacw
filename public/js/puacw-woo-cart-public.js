(function( $ ) {
	'use strict';

	$(document).ready(function () {

		//modal
		$(document).on('click', '#puacw-target', function (e) {
			e.preventDefault();
			$('#puacw-pop-up').css({'visibility': 'visible', 'opacity': 1});
		});
		$(document).on('click', '.puacw-pop-up__btn-close', function () {
			$('#puacw-pop-up').css({'visibility': 'hidden', 'opacity': 0});
		});

		//ajax cart
		function preloader(){
			let items = $('.puacw-pop-up__items');
			items.css({'opacity': 0.3})
			items.after('<div class="puacw-preloader"></div>');
		}

		function response(data){
			let items = $('.puacw-pop-up__items');
			$('#puacw-pop-up').html(data.fragments['.puacw-pop-up']);
			$('.puacw__counter').remove();
			$('#puacw-target').after(data.fragments[".puacw__counter"]);
			items.css({'opacity': 1});
			$('.puacw-preloader').remove();
		}

		// del
		$(document).on('click', '.puacw-item__del span', function () {
			let data = {
				'action': 'puacw_del_item',
				'cart_item_key': $(this).data('itemkey'),
			};
			preloader();
			let scroll = $('.puacw-pop-up__content').scrollTop();
			$.ajax({
				url: puacw_ajax.ajaxurl, // обработчик
				data: data, // данные
				type: 'POST', // тип запроса
				success: function (data) {
					response(data);
					$('.puacw-pop-up__content').scrollTop(scroll);
				}
			});
		});

		//plus
		$(document).on('click', '.puacw-item__plus',function () {
			let val = $(this).prev().val();

			let data = {
				'action': 'puacw_item_quantity',
				'cart_item_key': $(this).data('itemkey'),
				'quantity': +val + 1,
			};
			preloader();
			let scroll = $('.puacw-pop-up__content').scrollTop();
			$.ajax({
				url: puacw_ajax.ajaxurl, // обработчик
				data: data, // данные
				type: 'POST', // тип запроса
				success: function (data) {
					if (data) {
						response(data);
						$('.puacw-pop-up__content').scrollTop(scroll);
					}
				}
			});
		});

		//minus
		$(document).on('click', '.puacw-item__minus',function () {
			let val = $(this).next().val();

			let data = {
				'action': 'puacw_item_quantity',
				'cart_item_key': $(this).data('itemkey'),
				'quantity': +val - 1,
			};
			preloader();
			$.ajax({
				url: puacw_ajax.ajaxurl, // обработчик
				data: data, // данные
				type: 'POST', // тип запроса
				success: function (data) {
					if (data) {
						response(data);
					}
				}
			});
		});
	})

})( jQuery );
