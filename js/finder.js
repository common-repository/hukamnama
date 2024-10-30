jQuery(function($) {

	var date_timer, date_delay = 700;
	$( "#hukamnama_date" ).on( 'input', function() {
		var _this = $(this);
		clearTimeout(date_timer);
		timer = setTimeout(function() { update_page(_this) }, date_delay );
	});

	function update_page( _this ) {
		_this.next('.spinner').show();
		var api_page_url = HukamnamaFinder.ajaxurl + "?action=hukamnama_finder&date=" + $('#hukamnama_date').val();
		$.getJSON( api_page_url, function( data ) {

			HukamnamaFinder = data;
			$('#hukamnama_page').val(data.page ? data.page : '');

			var hymns = [];
			if( data.hymn ) {
				for( var hymn in data.hymn ) {
					if( data.hymn.hasOwnProperty( hymn ) ) {
						hymns.push(hymn);
					}
				}
			}
			$( '#hukamnama_hymn_display a' ).html(hymns.length ? hymns.join(', ') : '');
			$( '#hukamnama_hymn_display a' ).attr('href', HukamnamaFinder.display);
			$( "#hukamnama_page" ).trigger('input');
			$( '.spinner' ).hide();
		})
		.error(function() {
			alert("Finder failed! You may not be logged in!");
			// $('#hukamnama_page').val(HukamnamaFinder.page)
			$( '#hukamnama_page' ).trigger('input');
			$( '.spinner' ).hide();
		});
	}

	var timer, delay = 700;
	$( "#hukamnama_page" ).on( 'input', function() {
		var _this = $(this);
		clearTimeout(timer);
		timer = setTimeout(function() { update_hymns(_this) }, delay );
	});

	function get_page_url( page ) {
		var api_page_url = HukamnamaFinder.api;
		if (location.protocol == 'https:') {
			api_page_url = HukamnamaFinder.ajaxurl + '?action=hukamnama_api&route=';
		}

		api_page_url += '/page/' + page;
		return api_page_url;
	}

	function update_hymns( _this ) {

		if( _this.val().trim() == '' ) {
			$( ".hukamnama-finder" ).html('Please select a page first!');
			return false;
		}

		_this.next('.spinner').show();

		var api_page_url = get_page_url( _this.val() );
		$.getJSON( api_page_url, function( data ) {
			var items = {};
			for(var i = 0; i < data.length; i++) {
				if( ! ( data[i].hymn in items ) ) {
					items[data[i].hymn] = {
						'gurmukhi': '',
						'transliteration': '',
						'lines': []
					};
				}
				items[data[i].hymn].gurmukhi += data[i].text + "<br />";
				items[data[i].hymn].transliteration += data[i].transliteration.text + "<br />";
				items[data[i].hymn].lines.push(data[i]);
			}


			html = '';
			for (var hymn in items) {
				if (items.hasOwnProperty(hymn)) {

					html += "<div class='hymn hymn-" + hymn + "'>";

					html += "<div class='hymn-block hymn-input hymn-input-" + hymn + "'>";
					html += "<div class='hymn-input-control'><input type='checkbox' name='hukamnama_hymn[" + hymn + "]' value='true' id='hukamnama_hymn[" + hymn + "]' " + (HukamnamaFinder.hymn[hymn] ? "checked" : "") + " /></div>";
					html += "<div class='hymn-id'><label for='hukamnama_hymn[" + hymn + "]'>" + hymn + "</label></div>";
					html += "</div>";

					html += "<div class='hymn-block hymn-text hymn-text-" + hymn + " hymn-text-gurmukhi'><p><label for='hukamnama_hymn[" + hymn + "]'>";
					html += items[hymn].gurmukhi;
					html += "</label></p></div>";

					html += "<div class='hymn-block hymn-text hymn-text-" + hymn + " hymn-text-transliteration'><p><label for='hukamnama_hymn[" + hymn + "]'>";
					html += items[hymn].transliteration;
					html += "</label></p></div>";

					html += "</div><div clear='both'></div>";
				}
			}
			$( ".hukamnama-finder" ).html(html);
			$( '.spinner' ).hide();
		})
		.error(function() {
			alert("Invalid page: " + _this.val());
			// $('#hukamnama_page').val(HukamnamaFinder.page)
			// $('#hukamnama_page').trigger('input');
			$( '.spinner' ).hide();
		});
	}

	$( "#hukamnama_page" ).trigger('input');


});
