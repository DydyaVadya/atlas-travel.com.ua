( function( $ ) {
	'use strict';

	/*
	 * debouncedresize: special jQuery event that happens once after a window resize
	 *
	 * latest version and complete README available on Github:
	 * https://github.com/louisremi/jquery-smartresize
	 *
	 * Copyright 2012 @louis_remi
	 * Licensed under the MIT license.
	 */
	var $event = $.event,
		$special,
		resizeTimeout;
	$special = $event.special.debouncedresize = {
		setup: function() {
			$( this ).on( 'resize', $special.handler );
		},
		teardown: function() {
			$( this ).off( 'resize', $special.handler );
		},
		handler: function( event, execAsap ) {
			// Save the context
			var context = this,
				args = arguments,
				dispatch = function() {
					// set correct event type
					event.type = 'debouncedresize';
					$event.dispatch.apply( context, args );
				};
			if ( resizeTimeout ) {
				clearTimeout( resizeTimeout );
			}
			if ( execAsap ) {
				dispatch();
			} else {
				resizeTimeout = setTimeout( dispatch, $special.threshold );
			}
		},
		threshold: 150
	};

	/**
	 * Mega Menu jQuery Plugin
	 */
	$.fn.megaMenu = function ( options ) {

		var menu = $( this ),
			durationTimeout,
			triggerFullscreen  = 1200,
			triggerDesktop     = 970,
			triggerTablet      = menu.data( 'mobile-trigger' ),
			subClass           = '.tm-mega-menu-sub',
			mobileOnClass      = 'mega-menu-mobile-on',
			mobileTriggerClass = '.tm-mega-menu-mobile-trigger',
			parentMegaClass    = '.tm-mega-menu-has-children',
			inTransitionClass  = 'tm-in-transition',
			forceHideClass     = 'tm-force-hide',
			hoverClass         = 'tm-mega-menu-hover',
			clickGoClass       = 'mega-click-click-go',
			noJsClass          = 'tm-mega-no-js',
			megaToggleOnClass  = 'mega-toggle-on',
			hideMobileClass    = 'item-hide-mobile',
			isTouchDevice      = 'ontouchstart' in window || 0 < navigator.maxTouchPoints || 0 < navigator.msMaxTouchPoints || window.innerWidth <= triggerTablet,
			isMobile;

		menu.settings = $.extend( {
			effect:       menu.data( 'effect' ),
			parent:       menu.data( 'parent-selector' ),
			direction:    menu.data( 'direction' ),
			duration:     menu.data( 'duration' ),
			mobileButton: menu.data( 'mobile-button' )
		}, options );

		var isInMegamenu = function( el ) {
			return !!el.parents( 'li.item-type-megamenu' ).length;
		};

		var switchMobile = function() {
			if ( window.innerWidth <= triggerTablet ) {
				mobileOn();
			} else {
				mobileOff();
			}
		};

		var mobileOn = function() {

			detachStyles();

			if( isMobile ) {
				return;
			}

			menu
				.addClass( mobileOnClass )
				.siblings( mobileTriggerClass )
				.addClass( mobileOnClass );

			isMobile = true;
		};

		var mobileOff = function() {

			appendStyles();

			if( !isMobile && undefined !== isMobile ) {
				return;
			}

			menu
				.removeClass( mobileOnClass )
				.siblings( mobileTriggerClass )
				.removeClass( mobileOnClass );

			isMobile = false;
		};

		var getMenuWidth = function ( string_width ) {
			var width = 0;
			if ( 0 <= string_width.indexOf( '%' ) ) {
				width = ( $( menu.settings.parent ).width() * parseInt( string_width ) ) / 100;
			}
			if ( 0 <= string_width.indexOf( 'px' ) ) {
				width = parseInt( string_width );
			}
			return width;
		};

		var detachStyles = function() {

			$( 'li' + parentMegaClass, menu ).each( function() {
				$( this ).children( 'a' ).removeAttr( 'style' );
			} );

		};

		var appendStyles = function() {

			$( 'li' + parentMegaClass, menu ).each( function() {

				// all open children of open siblings
				var item        = $( this ),
					anchor      = $( this ).children( 'a' ),
					menuWidth   = null,
					type        = item.data( 'sub-type' ),
					windowWidth = Math.min( $( document ).width(), window.innerWidth ),
					position    = item.data( 'sub-position' ),
					styles      = {},
					parent      = menu.settings.parent,
					width,
					left,
					right,
					top;

				if ( 'standard' !== type ) {
					menuWidth = '100%';
				}

				if ( 'fullwidth' != position ) {
					if ( windowWidth >= triggerFullscreen ) {
						menuWidth = item.data( 'width-fullscreen' );
					} else if ( windowWidth >= triggerDesktop && windowWidth < triggerFullscreen ) {
						menuWidth = item.data( 'width-desktop' );
					} else if ( windowWidth < triggerDesktop ) {
						menuWidth = item.data( 'width-tablet' );
					}
				}

				if ( 'megamenu' === type ) {
					switch ( position ) {
						case 'fullwidth' :
							menuWidth  = getMenuWidth( menuWidth );
							styles.left = $( parent ).offset().left - menu.offset().left + parseInt( $( parent ).css( 'padding-left' ) ) + parseInt( $( parent ).css( 'border-left-width' ) );
							break;

						case 'left-parent' :
							left         = item.offset().left - $( parent ).offset().left;
							styles.left  = left;
							styles.right = 'auto';

							if ( '100%' == menuWidth && 0 < left ) {
								menuWidth    = 'auto';
								styles.right = 0;
							}

							break;

					}
				}

				if ( null !== menuWidth ) {
					styles.width = menuWidth;
				}

				anchor
					.siblings( subClass )
					.css( styles );

			} );

		};

		var hidePanel = function( anchor ) {

			if ( isMobile ) {
				return;
			}

			anchor
				.parent()
				.addClass( inTransitionClass )
				.removeClass( hoverClass )
				.triggerHandler( 'closePanel' );

			clearTimeout( durationTimeout );
			durationTimeout = setTimeout(
				function() {
					anchor.closest( '.menu-item' ).removeClass( inTransitionClass );
				},
				menu.settings.duration
			);

		};

		var showPanel = function( anchor ) {

			// all open children of open siblings
			anchor
				.parent()
				.removeClass( inTransitionClass )
				.addClass( hoverClass )
				.triggerHandler( 'openPanel' );

			anchor
				.parent()
				.siblings()
				.removeClass( inTransitionClass );
		};

		var openOnClick = function() {

			var parents = 'li' + parentMegaClass,
				target  = 'a',
				items   = parents + ' > ' + target + ', ' + parents + ' > .menu-link-wrapper > ' + target + ', li.super-guacamole__menu' + ' > ' + target;

			$( document ).on( 'click.megaMenu', items, panelTriggerOnClick );
		};

		var closeAllPanels = function( event ) {

			if ( ! $( event.target ).closest( '.tm-mega-menu li' ).length ) {
				hidePanel( $( '.' + hoverClass ).children( 'a' ) );
			}
		};

		var panelTriggerOnClick = function( event ) {

			var $this  = $( event.target ),
				parent = $this.closest( 'li' ),
				anchor = $( '> a', parent );

			if ( $this.hasClass( 'mega-menu-arrow' ) || $this.hasClass( 'sub-menu-toggle' ) ) {

				event.stopPropagation();
				event.preventDefault();

				if ( parent.hasClass( hideMobileClass ) && menu.hasClass( mobileOnClass ) ) {
					return;
				}

				hidePanel( parent.siblings( '.' + hoverClass ).children( 'a' ) );
				showPanel( anchor );

			}
		};

		var openOnHover = function() {

			if ( isMobile ) {
				return;
			}

			$( 'li' + parentMegaClass, menu ).on( 'hover', panelTriggerOnHover );
		};

		var panelTriggerOnHover = function ( event ) {

			var item   = $( event.currentTarget ),
				anchor = item.children( 'a' );

			// check if is nested item in mega sub menu
			if ( isInMegamenu( item ) ) {
				return;
			}

			if ( 'mouseenter' == event.type ) {
				showPanel( anchor );
			}
			if ( 'mouseleave' == event.type ) {
				hidePanel( anchor );
			}
		};

		var mobileToggle = function() {

			if ( menu.settings.mobileButton ) {

				var checkbox = $( '.tm-mega-menu-mobile-trigger-box' );

				$( menu.settings.mobileButton ).click( function() {

					checkbox.prop( 'checked', ! checkbox.prop( 'checked' ) );
				} );
			}
		};

		var init = function() {

			menu.removeClass( noJsClass );

			$( window ).on( 'debouncedresize', function( event ) {

				switchMobile();

				if ( isTouchDevice ) {
					openOnClick();
					$( document ).on( 'click', 'body', closeAllPanels );
				} else {
					openOnHover();
				}

			} ).trigger( 'debouncedresize' );

			mobileToggle();
		};

		init();
	};

	$( '.tm-mega-menu' ).megaMenu();

} )( jQuery );