(function($) {

	/**
	 *	Place vote via AJAX
	 *
	 *	@param (int) Battle ID
	 *	@param (string) Design A or B
	 *	@param (callback) call back function (optional)
	 */
	$.fn.placeVote = function( battle, design, cb ) 
	{
		if( battle == 'undefined' || design == 'undefined' )
			return false;

		var data = {
			action: 'place_vote',
			battle: battle,
			design: design
		};

		$.ajax({
			url: V2W.ajaxurl,
			type: 'post',
			data: data,
			error: function(xhr, status, error) 
			{
				//console.log('Error');
				//console.log(xhr);
				//console.log(status);
				//console.log(error);
			},
			success: function(result, status) 
			{
				if( cb != 'undefined' ) {
					cb(result);
				}else {

					console.log(result);

				}
			}
		});
	}

	/**
	 *	Login via AJAX
	 *
	 *	@param (string) username of user
	 *	@param (string) password of user
	 *	@param (bool) remember user creds?
	 *	@param (callback) function to pass result
	 *	@return null
	 */
	$.fn.v2wLogin = function( username, password, remember, cb ) 
	{

		var data = {
			action: 'vlogin',
			log: username,
			pwd: password,
			remember: (remember === true) ? 1 : 0
		};

		$.ajax({
			url: V2W.ajaxurl,
			type: 'post',
			data: data,
			error: function(xhr, status, error) 
			{
				console.log('Error');
				console.log(xhr);
				console.log(status);
				console.log(error);
			},
			success: function(result, status) 
			{
				cb(result);
			}
		});
	};

	/**
	 *	Register via AJAX
	 *
	 *	@param (array|object) data required for registration
	 *	@param (callback) function to pass result
	 *	@return null
	 */
	$.fn.v2wRegister = function( data, cb ) 
	{

		//default params
		var params = {
			action: 'vregister'
		};

		$.extend(params, data);

		//submit
		$.ajax({
			url: V2W.ajaxurl,
			type: 'post',
			data: params,
			error: function() 
			{
				console.log('Error');
				console.log(xhr);
				console.log(status);
				console.log(error);
			},
			success: function(result, status) 
			{
				cb(result);
			}
		});
	}

}(jQuery));

/**
 *	Custom Select Boxes
 *	Used with product variants
 *	on single product pages
 */
(function($) {

	/**
	 *	Custom Select box
	 *
	 *	@param (callback) function to use for updating label view. Default is none
	 *	@return self
	 */
	$.fn.customSelect = function( cb ) 
	{
		return this.each(function() {

			var e = $(this),
				l = e.find('label'),
				s = e.find('select');

			//init
			l.attr('data-label', l.text());

			//select menu change
			s.on('change', function(evt) {
				evt.preventDefault();
				var sel = $(this).find('option:selected');
				//var v = $(this).val();
				var v = sel.text();
				var text = l.data('label') + ': ' + v;
				updateLabel(l, text, cb);
			});

			//init
			s.trigger('change');

		});
	}

	/**
	 *	Update Label: Helper function
	 *	Uses callback if it was supplied
	 *
	 *	@param (jQuery) dom element to update
	 *	@param (string) new label
	 *	@param (function) callback to use for updating label
	 *	@return (jQuery) dom element
	 */
	function updateLabel( ele, label, cb ) 
	{
		if( typeof cb === 'undefined' ) 
		{
			//update label
			ele.html(label);
		} else 
		{
			//send params to callback
			cb(ele, label);
		}

		return ele;
	}

	/**
	 *	Countdown Timer
	 *	Counts down based on the number
	 *	of seconds remaining in the battle
	 *	
	 *	@param (callback)
	 *	@return self
	 */
	$.fn.countdown = function( cb ) 
	{
		return this.each(function() {

			var wrap = $(this),
				ui = wrap.find('.time-left .line2'),
				start = wrap.data('time'),
				left = start;

			//ignore if already 0
			if( left <= 0 )
				return;

			var timer = setInterval(function() {

				left--;	//lose a second

				if( left <= 0 ) {
					clearInterval( timer );

					if( cb !== undefined )
						cb(wrap)

				}

				var h = Math.floor( (left/60)/60 ),
					m = Math.floor( (left/60) - (h*60) ),
					s = Math.floor( left - ( ((h*60)*60) + (m*60) ) );

				//console.log( h + ' hours ' + m + ' minutes ' + s + ' seconds' );
				ui.html( h+' hrs '+m+' mins '+s+' secs' );

			}, 1000);

		});
	};

}(jQuery));



/**
 *	Stuff
 */
jQuery(document).ready(function($) {

	//mobile menu icon
	$('#mobile-menu-trigger').on('click', function(evt) {
		evt.preventDefault();
		$('#mobile-open').toggleClass('closed');
		return false;
	});
	
	/**
	 *	Place a vote
	 */
	$('.content').on('click', '.place-vote', function(evt) {

		if( $(this).hasClass('disabled') )
			return false;

		var battle = $(this).data('battle'),
			design = $(this).data('design');
			
		$(this).placeVote(battle, design, function(result) {
			$(V2W).trigger('vote:placed', result);
		});

		return false;
	});

	//search
	$(function() {
		var form = $('#global-search-form');
		$('#topbar .search .search-trigger').on('click', function(evt) {
			evt.preventDefault();
			form.toggleClass('active');
		});
	});

	//timers
	$('.timer-wrap').countdown(function(timer) {
		$(V2W).trigger('battle:ended');
		$('.place-vote').remove();
		window.location.reload();
	});

	//vote circle animation
	$(function() {
		var newsize = $('.circle-animate').height(),
			g = "#98ff98", r = "#d23f3b",
			dur = 1500;

		$('.circle-animate').each(function() {
			var votes = +$(this).data('votes'),
				leader = $(this).hasClass('leader');

			$(this).circleProgress({
				value: votes*.01,
				size: newsize,
				fill: {
					color: (leader) ? g : r
				},
				animation: {
					duration: dur	
				}
			});

		});
	});

	//fix battle titles
	$(function() {

		var fixTitleHeight = function(evt) {
			$('.battle .battle-detail-wrap').each(function() {
				var h = 0,
					grp = $(this).find('.title-wrap h3');

				grp.css('min-height', 'auto');	//reset

				grp.each(function() {
					if( $(this).height() > h )
						h = $(this).height();
				});

				grp.css('min-height', h);
			});
		};

		$(window).on('resize', fixTitleHeight());
		fixTitleHeight(null);	//init
	});
	
});