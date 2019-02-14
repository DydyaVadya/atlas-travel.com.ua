/* global cherryTrendPosts */
(function( $ ) {
	$.fn.TrendPostsView = function() {
		var $item   = $( this ),
			dataObj = {};

		$item.each( function() {
			var postId       = $( this ).data( 'id' ),
				propertyName = 'postID_' + postId;

			dataObj[propertyName] = postId;
		});

		$.ajax({
			url: cherryTrendPosts.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action: 'cherry_trend_posts_check_views',
				trend_data: dataObj,
				nonce: cherryTrendPosts.nonce
			}
		}).done( function( response ) {
			if ( ! response.success ) {
				return;
			}

			$item.each( function() {
				var postId = $( this ).data( 'id' );

				if ( ! response.data.hasOwnProperty( postId ) ) {
					return true;
				}

				$( this ).html( response.data[ postId ] );
			});
		});
	};

	$.fn.TrendPostsRating = function() {
		var $item      = $( this ),
			preffix    = 'ftype',
			formatArr  = [ $( this ).first().data( 'format' ) ],
			dataObj    = {},
			formatObj  = {},
			key, i;

		$item.each( function() {
			var postId = $( this ).data( 'post' ),
				postFormat = $( this ).data( 'format' ),
				find, ids, key, len;

			find = $.inArray( postFormat, formatArr );
			len  = find;

			if ( find === -1 ) {
				formatArr.push( postFormat );
				len = Object.keys( formatArr ).length;
				len--;
			}

			key = preffix + len;

			if ( key in dataObj ) {
				ids = dataObj[ key ];
				ids.push( postId );
				dataObj[ key ] = ids;
			} else {
				dataObj[ preffix + len ] = [ postId ];
			}
		});

		for ( i = 0; i < formatArr.length; i++ ) {
			key = preffix + i;
			formatObj[key] = formatArr[i];
		}

		$.ajax({
			url: cherryTrendPosts.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action: 'cherry_trend_posts_check_rating',
				trend_format: formatObj,
				trend_data: dataObj,
				nonce: cherryTrendPosts.nonce
			}
		}).done( function( response ) {
			if ( ! response.success ) {
				return;
			}

			$item.each( function() {
				var postId = $( this ).data( 'post' ),
					postFormat = $( this ).data( 'format' ),
					property, i;

				for ( i in formatObj ) {
					if ( postFormat === formatObj[i] ) {
						property = i;
					}
				}

				if ( ! response.data.hasOwnProperty( property ) ) {
					return true;
				}

				if ( ! response.data[property].hasOwnProperty( postId ) ) {
					return true;
				}

				$( this ).parent().html( response.data[property][postId] );
			});
		});
	};
})( jQuery );

jQuery( document ).ready(function( $ ) {

	/**
	 * Process rating.
	 */
	$( document ).on( 'click', '.cherry-trend-stars__item', function( event ) {
		var $item      = $( this ),
			$container = $item.parent(),
			rate       = $item.data( 'rate' ),
			post       = $container.data( 'post' ),
			format     = $container.data( 'format' );

		event.preventDefault();

		if ( $container.hasClass( 'cherry-trend-stars--processing' ) || $container.hasClass( 'cherry-trend-stars--rate-disabled' ) ) {
			return ! 1;
		}

		$container.find( '.cherry-trend-stars__item' ).removeClass( 'cherry-trend-star--active' );
		$item.addClass( 'cherry-trend-star--active' );
		$container.addClass( 'cherry-trend-stars--processing' );

		$.ajax({
			url: cherryTrendPosts.ajaxurl,
			type: 'post',
			dataType: 'html',
			data: {
				action: 'cherry_trend_posts_handle_rating',
				rate: rate,
				post: post,
				format: format,
				nonce: cherryTrendPosts.nonce
			}
		}).done( function( response ) {
			$container.removeClass( 'cherry-trend-stars--processing' );
			$container.parent().html( response );
		});
	});

	/**
	 * Process views, rating.
	 */
	if ( $( '.cherry-trend-views__count' ).length > 0 && 0 !== parseInt( cherryTrendPosts.cache, 10 ) ) {
		$( '.cherry-trend-views__count' ).TrendPostsView();
	}

	if ( $( '.cherry-trend-rating__stars' ).length > 0 && 0 !== parseInt( cherryTrendPosts.cache, 10 ) ) {
		$( '.cherry-trend-rating__stars' ).TrendPostsRating();
	}

});
