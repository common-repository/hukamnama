jQuery(function($) {


	var DisplayUpdater = {
		init: function() {
			var delay = 60 * 1000;
			var self = this;

			if ($('.hukamnama-display').length == 0) {
				return false;
			}

			setInterval(function() {
				self.check_updates();
			}, delay);

			this.download_hymn();
		},
		current_date: function() {

			var today = new Date(),
				dd = today.getDate(),
				mm = today.getMonth() + 1,
				yyyy = today.getFullYear();

			if ( dd < 10 ) {
				dd = '0' + dd
			}
			if ( mm < 10 ) {
				mm = '0' + mm
			}
			return yyyy + '-' + mm + '-' + dd;
		},
		check_updates: function() {
			var datestamp = this.current_date();
			var api_page_url = HukamnamaFinder.ajaxurl + "?action=hukamnama_finder&date=" + datestamp;
			$.getJSON( api_page_url, function( data ) {

				if( data.latest != HukamnamaFinder.latest ) {
					location.reload();
				}
			})
			.error(function() {
				// console.log("Finder failed!");
			});
		},
		get_hymn_url: function( hymn ) {
			var api_page_url = HukamnamaFinder.api;
			if (location.protocol == 'https:') {
				api_page_url = HukamnamaFinder.ajaxurl + '?action=hukamnama_api&route=';
			}

			api_page_url += '/hymn/' + hymn;
			return api_page_url;
		},
		download_hymn: function() {

			var self = this;

			for (var hymn in HukamnamaFinder.hymn) {
				if ( HukamnamaFinder.hymn.hasOwnProperty( hymn ) ) {

					var api_page_url = DisplayUpdater.get_hymn_url(hymn);
					var selectors = [ '.page-header h1', 'h1' ];

					$.getJSON( api_page_url, function( data ) {

						var items = [];
						for ( var i = 0; i < data.length; i++ ) {
							items.push(data[i]);
					 	}
					 	$( ".hukamnama-display" ).append('<ul>' + self.display_hymns(items) + '</ul>');

					 	for ( var i = 0; i < selectors.length; i++ ) {
					 		if ( $( selectors[i] ).length ) {
					 			$( selectors[i] ).html( 'Hukamnama ' + HukamnamaFinder.date_nice );
					 		}
					 	}
					})
					.error(function() {
						// console.log("Cannot get hymn: " + hymn);
					});

				}
			}
		},
		display_hymns: function( items ) {
			var html = '';

			var gurmukhi = '';
			var translation = '';
			for (var i = 0; i < items.length; i++) {
				hymn_cls = 'hymn-text hymn-text-' + items[i].hymn + '';
				gurmukhi += '<span class="' + hymn_cls + '">' + items[i].text + '</span> ';
				translation += '<span class="' + hymn_cls + '">' + items[i].translation.text + '</span> ';
			}

			html += "<li class='hymn text-center'>";

			html += "<div class='hymn-text-gurmukhi'><p>";
			html += gurmukhi;
			html += "</p></div>";

			html += "<div class='hymn-text-translation'><p>";
			html += translation;
			html += "</p></div>";

			html += "</li>";

			return html;
		}
	};

	DisplayUpdater.init();
});
