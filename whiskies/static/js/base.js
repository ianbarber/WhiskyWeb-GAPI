var whisky = {
	init: function(apiRoot) {
		// API name, version, callback function, root for endpoint
		gapi.client.load('wweb', 'v1', whisky.showlist, apiRoot);
	},
	showlist: function() {
		var request = gapi.client.wweb.whisky.list();
		request.execute(function(whiskies) {
			$('#whiskies').empty();
			for(var wIndex in whiskies.items) {
				w = whiskies.items[wIndex];
				$('#whiskies').append('<li class="whisky" data-wid="'+ w['id'] + '">' 
					+ w['title'] + '</li>' );
			}
			$('.whisky').click(function() { whisky.details($(this).data('wid')) });
		});
	},
	details: function(whisky_id) {
		$('#whisky_details').empty();
		var request = gapi.client.wweb.whisky.get({
			"id": whisky_id
		});
		request.execute(function(whisky) {
			$('#whisky_details').append('<img src="' + whisky['image_url'] + '" />');
			$('#whisky_details').append('<p><strong>Title:</strong> ' + whisky['title'] + '</p>');
			$('#whisky_details').append('<p><strong>Distillery:</strong> ' + whisky['distillery'] + '</p>');
			$('#whisky_details').append('<p><strong>Region:</strong> ' + whisky['region'] + '</p>');
		});
	}
};