/**
 * TM mega menu custom scripts
 *
 * Mega Menu jQuery Plugin.
 * based on Mega Menu plugin - http://www.maxmegamenu.com/
 */
( function( $ ) {
	'use strict';

	$.fn.megaMenu = function ( options ) {

		var panel = $( '<div/>' );

		panel.settings = $.extend( {
			cols:            6,
			menu_item_id:    0,
			menu_item_title: '',
			menu_item_depth: 0
		}, options );

		var popups_wrap     = $( '.popup-wrapper_' ),
			current_popup   = 'id-' + panel.settings.menu_item_id,
			widget_selector = false;

		if ( 0 === $( '#' + current_popup ).length ) {
			popups_wrap.append( '<div id="' + current_popup + '" class="white-popup-block tm-ui-core"><div class="popup-content_"><div class="popup-loading_"></div></div></div>' );
		}

		/*panel.log = function ( message ) {
			if ( window.console && console.log ) {
				console.log( message );
			}

			if ( -1 == message ) {
				alert( tm_mega_menu.nonce_check_failed );
			}
		};*/


		panel.init = function () {

			//panel.log( tm_mega_menu.debug_launched + ' ' + panel.settings.menu_item_id );

			$.magnificPopup.open( {
				items:     {
					src: '#' + current_popup
				},
				type:      'inline',
				callbacks: {
					open: function() {
						popup_request();
					}
				}
			} );

			$( document ).on( 'click', '.tm-mega-menu-save-settings', function( event ) {
				event.preventDefault();
				save_settings( $( this ) );
			} ).on( 'change', '.toggle_menu', function( event ) {
				save_settings( $( this ) );
			} );

		};

		var save_settings = function( element ) {

			var form = element.parents( 'form' ),
				data = form.serialize();

			start_saving();

			$.post( ajaxurl, data, function ( submit_response ) {
				end_saving();
				//panel.log( submit_response );
			} );

		};

		var popup_request = function() {

			var popup    = $( '#' + current_popup + ' .popup-content_' ),
				postdata = {
				action:          'tm_mega_menu_get_popup',
				_wpnonce:        tm_mega_menu.nonce,
				menu_item_id:    panel.settings.menu_item_id,
				menu_item_title: panel.settings.menu_item_title,
				menu_item_depth: panel.settings.menu_item_depth,
				total_cols:      panel.settings.cols
			};

			$.ajax( {
				type:       'POST',
				url:        ajaxurl,
				data:       postdata,
				cache:      false,
				beforeSend: function() {
					popup.html( '<div class="popup-loading_"></div>' );
				},
				complete:   function() {
					popup
						.find( '.popup-loading_' )
						.remove();
				},
				success:    function( response ) {

					var content = response.content;
					// init tabs
					popup.addClass( 'depth-' + panel.settings.menu_item_depth )
						.html( content )
						.on( 'click.tm_mega_menu_tabs', '.vertical-tabs_.vertical-tabs_width_small_ a', function( event ) {
						event.preventDefault();

						$( this )
							.parent()
							.addClass( 'active' )
							.siblings()
							.removeClass( 'active' );

						$( this )
							.parents( '.popup-content_' )
							.find( '.mega-menu-tabs-content_' )
							.find( '#' + $( this ).data( 'tab' ) )
							.css( 'visibility', 'visible' )
							.siblings()
							.css( 'visibility', 'hidden' );

						init_settings_scripts();
					} );
					$( '#' + current_popup + ' .popup-content_ .vertical-tabs_.vertical-tabs_width_small_active a:first' ).trigger( 'click.tm_mega_menu_tabs' );

					tabs_height();
					init_settings_scripts();

					// init
					if ( $( '#' + current_popup + ' #tm-mega-menu-tab-mega_menu' ).length ) {
						select_widgets();
						sort_widgets();
					}
				}
			} );

		};

		var tabs_height = function() {
			var height  = 0;

			$( '#' + current_popup + ' .mega-menu-tabs-content-item_' ).each( function() {
				var item_height = $( this ).outerHeight();
				if ( item_height > height ) {
					height = item_height;
				}
			} );

			$( '#' + current_popup + ' .mega-menu-tabs-content_' ).height( height );
		};

		var init_settings_scripts = function() {
			$( 'input[data-init-script="icon-picker"]' ).each( function() {
				$( this ).iconpicker({
					iconBaseClass: 'fa',
					iconClassPrefix: '',
					fullClassFormatter: function( e ) {
						return 'fa' + " " + e;
					}
				});
			} );
		};
		var start_saving = function() {
			$( '.popup-saving_' ).show();
		};

		var end_saving = function() {

			$( '.popup-saving_' ).hide();

			var timeout = false;

			if ( !timeout ) {
				$( '.popup-saved_' ).stop().fadeIn( 'fast' );
				timeout = setTimeout( function() {
					$( '.popup-saved_' ).stop().fadeOut( 'fast' );
				}, 1000 );
			}
		};

		var sort_widgets = function() {

			var widget_area = $( '#' + current_popup ).find( '#widgets' );

			widget_area.sortable( {
				forcePlaceholderSize: true,
				placeholder:         'drop-area',
				start: function( event, ui ) {
					$( '.widget' ).removeClass( 'open' );
					ui.item.data( 'start_pos', ui.item.index() );
				},
				stop:  function ( event, ui ) {
					// clean up
					ui.item.removeAttr( 'style' );

					if ( ui.item.data( 'start_pos' ) !== ui.item.index() ) {
						ui.item.trigger( 'on_drop' );
					}
				}
			} );

			$( '.widget', widget_area ).each( function() {
				add_events_to_widget( $( this ) );
			} );
		};

		var select_widgets = function() {

			widget_selector = $( '#' + current_popup ).find( '#widget_selector' );

			widget_selector.on( 'mouseup', function() {

				var selector = $( this );

				if ( 'disabled' != selector.val() && undefined !== selector.val() ) {

					start_saving();

					var postdata = {
						action:       'tm_mega_menu_add_widget',
						id_base:      selector.val(),
						menu_item_id: panel.settings.menu_item_id,
						total_cols:   panel.settings.cols,
						title:        selector.find( 'option:selected' ).text(),
						_wpnonce:     tm_mega_menu.nonce
					};
					$.post( ajaxurl, postdata, function( response ) {

						end_saving();

						$( '.no_widgets' ).hide();

						$( '#widgets' ).append( response.content );
						tabs_height();

						if ( 'success' == response.type ) {
							add_events_to_widget( $( '#' + current_popup + ' #' + response.id ) );
						}
						widget_selector.val( 'disabled' );
					} );
				}
			} );
		};

		var add_events_to_widget = function( widget ) {

			var widget_spinner = widget.find( '.spinner' ),
				widget_id      = widget.data( 'widget-id' ),
				_wpnonce       = tm_mega_menu.nonce;

			widget.on( 'on_drop', function() {

				start_saving();

				var postdata = {
					action:       'tm_mega_menu_move_widget',
					widget_id:    widget_id,
					position:     $( this ).index(),
					menu_item_id: panel.settings.menu_item_id,
					_wpnonce:     _wpnonce
				};

				$.post( ajaxurl, postdata, function ( move_response ) {
					end_saving();
					//panel.log( move_response );
				} );
			} );

			widget
				.find( '.widget-resize' )
				.on( 'click', function() {

				var cols     = widget.data( 'columns' ),
					_action  = $( this ).data( 'action' );

				if ( 'expand' == _action && cols < panel.settings.cols ) {
					cols++;
				}

				if ( 'contract' == _action && 1 < cols ) {
					cols--;
				}

				widget
					.data( 'columns', cols )
					.attr( 'data-columns', cols )
					.find( '.widget-cols-counter b' )
					.html( cols );

				start_saving();
				var postdata = {
						action:    'tm_mega_menu_update_columns',
						widget_id: widget_id,
						columns:   cols,
						_wpnonce:  _wpnonce
					};

				$.post( ajaxurl, postdata, function ( response ) {
					end_saving();
					//panel.log( response );
				} );
			} );

			widget.find( '.widget-edit' ).on( 'click', function() {


				if ( !widget.hasClass( 'open' ) && !widget.data( 'loaded' ) ) {

					widget_spinner.show();

					var postdata = {
						action:    'tm_mega_menu_edit_widget',
						widget_id: widget_id,
						_wpnonce:  _wpnonce
					};

					// retrieve the widget settings form
					$.post( ajaxurl, postdata, function ( form ) {

						var $form = $( form );

						// bind delete button action
						$( '.delete', $form ).on( 'click', function ( e ) {
							e.preventDefault();

							var data = {
								action:    'tm_mega_menu_delete_widget',
								widget_id: widget_id,
								_wpnonce:  tm_mega_menu.nonce
							};

							$.post( ajaxurl, data, function ( delete_response ) {
								widget.remove();
								//panel.log( delete_response );
							} );
						} );

						// bind close button action
						$( '.close', $form ).on( 'click', function ( e ) {
							e.preventDefault();

							widget.toggleClass( 'open' );
						} );

						// bind save button action
						$form.on( 'submit', function ( e ) {
							e.preventDefault();

							var data = $( this ).serialize();

							start_saving();

							$.post( ajaxurl, data, function ( submit_response ) {
								end_saving();
								//panel.log( submit_response );
							} );

						} );

						widget
							.data( 'loaded', true )
							.toggleClass( 'open' )
							.find( '.widget-inner' )
							.html( $form );

						widget_spinner.hide();

						$( document ).trigger( 'widget-added', [ widget ] );
					} );

				} else {
					widget.toggleClass( 'open' );
				}

				// close all other widgets
				$( '.widget' )
					.not( widget )
					.removeClass( 'open' );
			} );
			return widget;
		};
		panel.init();
	};

	$( '#menu-to-edit' ).on( 'sortstop', function( event, ui ) {
		setTimeout( function(){
			ui.item.find( '.tm-mega-menu-launch' ).data( 'depth', ui.item.menuItemDepth() );
		}, 100 );
	} );

	$( '.menu-item' ).each( function( e ) {

		var menu_item  = $( this );

		$( this ).find( '.tm-mega-menu-launch' ).on( 'click', function( e ) {
			$( this ).megaMenu( {
				cols:            tm_mega_menu.cols,
				menu_item_id:    $( this ).data( 'id' ),
				menu_item_title: $( this ).data( 'title' ),
				menu_item_depth: $( this ).data( 'depth' )
			} );
		} );
	} );
}( jQuery ) );
