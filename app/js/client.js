/**
 * Example Duality Client
 */

// Do not call anything from outside!
'use strict';

// Create a client namespace
var Client = {};

// Define client routes configuration
Client.routes = function () {

	// Set jQuery alias
	var $ = Client.$;

	// Just call server at /example/json
	$.getJSON('example/json', {get: 'test'}, function (data) {

		// Add data to document
		$('#container').append('<p>' + data.msg + '</p>');
		$('#container').append('<table class="table col-md-12" />');
		$.each(data.items, function (i, item) {
			$('#container table').append('<tr><td>' + item.id + '</td><td>' + item.email + '</td></tr>');
		});
	});
}

// Define client initialization
Client.init = function () {

	// Check if we are on a browser environment
	if (typeof window === 'undefined' && typeof jQuery === 'undefined') {
		if (console) {
			console.log('Client should be used on browser!');
			return false;
		}
	}

	// Call jQuery on document ready
	jQuery(function($) {

		// add references to window and jQuery to client
		Client.window = window;
		Client.$ = $;

		// Call client routes configuration
		Client.routes();
	});

	return true;
};