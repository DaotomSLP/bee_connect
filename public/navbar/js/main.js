(function ($) {

	"use strict";

	$('nav .dropdown').hover(function () {
		var $this = $(this);
		$this.addClass('show');
		$this.find('> a').attr('aria-expanded', true);
		$this.find('.dropdown-menu').addClass('show');
	}, function () {
		var $this = $(this);
		$this.removeClass('show');
		$this.find('> a').attr('aria-expanded', false);
		$this.find('.dropdown-menu').removeClass('show');
	});

	$('#ftco-category-navbar .dropdown-category').hover(function () {
		var $this = $(this);
		$this.addClass('show');
		$this.find('> a').attr('aria-expanded', true);
		$this.find('.dropdown-menu-1').addClass('show');
	}, function () {
		var $this = $(this);
		$this.removeClass('show');
		$this.find('> a').attr('aria-expanded', false);
		$this.find('.dropdown-menu-1').removeClass('show');
	});

	$('#ftco-category-navbar .dropdown-category-2').hover(function () {
		var $this = $(this);
		// $this.addClass('show');
		$this.find('> a').attr('aria-expanded', true);
		$this.find('.dropdown-menu-2').addClass('show');
	}, function () {
		var $this = $(this);
		// $this.removeClass('show');
		$this.find('> a').attr('aria-expanded', false);
		$this.find('.dropdown-menu-2').removeClass('show');
	});

	// $(window).resize(function () {
	// 	console.log($('#ftco-category-navbar').width())
	// });
	$(document).ready(function () {
		$('.dropdown-category-menu').css('left', ` ${$('#ftco-category-navbar').width()}px`)
		$('.dropdown-menu-2').css('left', `295px`)
	});

})(jQuery);
