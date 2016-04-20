jQuery(document).ready(function($) {
	
	//show and hide the different forms
	$(function() {
		var selectors = $('.form-choice'),
			forms = $('.form'),
			speed = 200;
			
		selectors.on('click', function() {
			
			var thecurrent = $(this);
			var current = $(this).index();
			var theform = forms.eq(current);
			
			if(theform.is(':visible')) {
				// do nothing
			} else {
				selectors.removeClass('active');
				thecurrent.addClass('active');
				forms.stop().slideUp(200);
				theform.stop().slideDown(200);	
			}
			
		});
	});
	
	// process the form
	$('.form1').submit(function(event) {

		$('#name-group1').removeClass('has-error'); // remove the error class
                $('#email-group1').removeClass('has-error'); // remove the error class
		$('.help-block1').remove(); // remove the error text

		// get the form data
		// there are many ways to get this data using jQuery (you can use the class or id also)
		var formData = {
			'name1' 				: $('input[name=name1]').val(),
			'email1' 			: $('input[name=email1]').val(),
			'message1' 			: $('textarea[name=message1]').val()
		};

		// Process the General Form
		$.ajax({
			type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
			url 		: '../wp-content/themes/v2w/process-general.php', // the url where we want to POST
			data 		: formData, // our data object
			dataType 	: 'json', // what type of data do we expect back from the server
			encode 		: true
		})
			// using the done promise callback
			.done(function(data) {
			
				// here we will handle errors and validation messages
				if ( ! data.success) {
					
					// handle errors for name ---------------
					if (data.errors.name) {
						$('#name-group1').addClass('has-error'); // add the error class to show red input
						$('#name-group1').append('<div class="text-part help-block1">' + data.errors.name + '</div>'); // add the actual error message under our input
					}

					// handle errors for email ---------------
					if (data.errors.email) {
						$('#email-group1').addClass('has-error'); // add the error class to show red input
						$('#email-group1').append('<div class="text-part help-block1">' + data.errors.email + '</div>'); // add the actual error message under our input
					}

					// handle errors for superhero alias ---------------
					if (data.errors.message) {
						$('#message-group1').addClass('has-error'); // add the error class to show red input
						$('#message-group1').append('<div class="text-part help-block1">' + data.errors.message + '</div>'); // add the actual error message under our input
					}

				} else {

				        $('name').val('');
				        $('email').val('');
				        $('message').val('');
				        
					// ALL GOOD! just show the success message!
					$('.form1').append('<div class="text-part help-block1">' + data.message + '</div>');
				}
			})

			// using the fail promise callback
			.fail(function(data) {

				// Show the error message
				$('.form1').append('<div class="text-part help-block1">' + "Oops! Something went wrong and we couldn't send your message. Please verify your information and try again." + '</div>');

			});

		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	
	// Process the Legal Form
	$('.form2').submit(function(event) {

		$('#name-group2').removeClass('has-error'); // remove the error class
                $('#email-group2').removeClass('has-error'); // remove the error class
		$('.help-block2').remove(); // remove the error text

		// get the form data
		// there are many ways to get this data using jQuery (you can use the class or id also)
		var formData = {
			'name2' 				: $('input[name=name2]').val(),
			'email2' 			: $('input[name=email2]').val(),
			'message2' 			: $('textarea[name=message2]').val()
		};

		// process the form
		$.ajax({
			type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
			url 		: '../wp-content/themes/v2w/process-legal.php', // the url where we want to POST
			data 		: formData, // our data object
			dataType 	: 'json', // what type of data do we expect back from the server
			encode 		: true
		})
			// using the done promise callback
			.done(function(data) {
			
				// here we will handle errors and validation messages
				if ( ! data.success) {
					
					// handle errors for name ---------------
					if (data.errors.name) {
						$('#name-group2').addClass('has-error'); // add the error class to show red input
						$('#name-group2').append('<div class="text-part help-block2">' + data.errors.name + '</div>'); // add the actual error message under our input
					}

					// handle errors for email ---------------
					if (data.errors.email) {
						$('#email-group2').addClass('has-error'); // add the error class to show red input
						$('#email-group2').append('<div class="text-part help-block2">' + data.errors.email + '</div>'); // add the actual error message under our input
					}

					// handle errors for superhero alias ---------------
					if (data.errors.message) {
						$('#message-group2').addClass('has-error'); // add the error class to show red input
						$('#message-group2').append('<div class="text-part help-block2">' + data.errors.message + '</div>'); // add the actual error message under our input
					}

				} else {

				        $('name').val('');
				        $('email').val('');
				        $('message').val('');
				        
					// ALL GOOD! just show the success message!
					$('.form2').append('<div class="text-part help-block2">' + data.message + '</div>');
				}
			})

			// using the fail promise callback
			.fail(function(data) {

				// Show the error message
				$('.form2').append('<div class="text-part help-block1">' + "Oops! Something went wrong and we couldn't send your message. Please verify your information and try again." + '</div>');
			});

		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	
	//contact page google map
	function initMap() {

		// Specify features and elements to define styles.
		var styleArray = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#FFFFFF"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#424a5b"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#545b6a"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"color":"#41495a"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#2e3546"},{"visibility":"on"}]}];
		
		// Create a map object and specify the DOM element for display.
		var map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 28.5466834, lng: -81.2038225},
			disableDefaultUI: true,
			scrollwheel: false,
			// Apply the map style array to the map.
			styles: styleArray,
			zoom: 11
		});
		
		var image = 'https://www.vote2wear.com/wp-content/themes/v2w/library/images/green-pin.png';
		var v2wMarker = new google.maps.Marker({
			position: {lat: 28.5466834, lng: -81.2038225},
			map: map,
			icon: image
		});
		
	};
	
	initMap();
	
});