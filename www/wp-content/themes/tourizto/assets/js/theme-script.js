/* UItoTop jQuery Plugin | Matt Varone | http://www.mattvarone.com/web-design/uitotop-jquery-plugin */
!function(n){n.fn.UItoTop=function(o){var e={text:"To Top",min:200,inDelay:600,outDelay:400,containerID:"toTop",containerHoverID:"toTopHover",scrollSpeed:1200,easingType:"linear"},t=n.extend(e,o),i="#"+t.containerID,a="#"+t.containerHoverID;n("body").append('<a href="#" id="'+t.containerID+'"><span>'+t.text+"</span></a>"),n(i).hide().on("click.UItoTop",function(){return n("html, body").animate({scrollTop:0},t.scrollSpeed,t.easingType),n("#"+t.containerHoverID,this).stop().animate({opacity:0},t.inDelay,t.easingType),!1}).hover(function(){n(a,this).stop().animate({opacity:1},600,"linear")},function(){n(a,this).stop().animate({opacity:0},700,"linear")}),n(window).scroll(function(){var o=n(window).scrollTop();"undefined"==typeof document.body.style.maxHeight&&n(i).css({position:"absolute",top:o+n(window).height()-50}),o>t.min?n(i).fadeIn(t.inDelay):n(i).fadeOut(t.Outdelay)})}}(jQuery);
/* jquery.stickup.min.js */
!function(t){t.fn.stickUp=function(e){function o(){a=parseInt(n.offset().top),d=parseInt(n.css("margin-top")),u=parseInt(n.outerHeight(!0)),c.pseudo&&(t('<div class="pseudoStickyBlock"></div>').insertAfter(n),i=t(".pseudoStickyBlock"),i.css({position:"relative",display:"block"})),c.active&&s(),n.addClass("stuckMenu")}function s(){p.on("scroll.stickUp",function(){r=t(this).scrollTop(),S=r>k?"down":"up",k=r,0!=h.length?correctionValue=h.outerHeight(!0):correctionValue=0,f=parseInt(l.scrollTop()),a+u-correctionValue<f?(n.addClass("isStuck"),v.addClass("isStuck"),c.pseudo?(n.css({position:"fixed",top:correctionValue}),i.css({height:u})):n.css({position:"fixed",top:correctionValue})):(n.removeClass("isStuck"),v.removeClass("isStuck"),c.pseudo?(n.css({position:"relative",top:0}),i.css({height:0})):n.css({position:"absolute",top:0}))}).trigger("scroll.stickUp"),p.on("resize",function(){n.hasClass("isStuck")?a!=parseInt(i.offset().top)&&(a=parseInt(i.offset().top)):a!=parseInt(n.offset().top)&&(a=parseInt(n.offset().top))})}var c={correctionSelector:".correctionSelector",listenSelector:".listenSelector",active:!1,pseudo:!0};t.extend(c,e);var i,r,n=t(this),l=t(window),p=t(document),a=0,u=0,d=0,f=0,k=0,S="",h=t(c.correctionSelector),v=t(c.listenSelector);0!=n.length&&o()}}(jQuery);
/* super-guacamole.min.js */
!function(e,t){function n(t){var n,i,r=this;n={id:"",href:"",title:"&middot;&middot;&middot;",children:{},templates:{},container:null},i=e.extend(n,t),r.id=i.id,r.href=i.href,r.title=i.title,r.children=i.children,r.templates=i.templates,r.$container=i.container,r.node=null,r.attachedNode=null,r.options={},r.visible=!0}var i={menu:'<li class="%1$s"><a href="%2$s">%3$s</a>%4$s</li>',child_wrap:"<ul>%s</ul>",child:'<li class="%1$s" id="%5$s"><a href="%2$s">%3$s</a><ul class="sub-menu">%4$s</ul></li>'};n.prototype.set=function(e){if(!1==e instanceof n)throw new Error("Invalid argument type");return this.children[e.id]=e,this},n.prototype.push=function(e){return this.set(e)},n.prototype.get=function(e){var t=null;return this.map(e,function(e,n){return t=n,n}),t},n.prototype.map=function(e,t,n){var i={id:e},r=this;return"string"!=typeof e&&(i=e),n=n||this.children,0>=n.length?i:(Object.keys(n).forEach(function(e){child=n[e],i.id===child.id?n[i.id]=t(child):child.children&&0<Object.keys(child.children).length&&(i=r.map(i,t,child.children))}),i)},n.prototype.has=function(e){return t!==this.children[e]},n.prototype.isVisible=function(){return this.visible},n.prototype.forEach=function(e){return this.children.forEach(e)},n.prototype.countVisibleAttachedNodes=function(){var t=this,n=-1;return Object.keys(t.children).forEach(function(i){e(t.children[i].getAttachedNode()).attr("hidden")||n++}),n},n.prototype.countVisibleNodes=function(){var t,n=this,i=0;return Object.keys(n.children).forEach(function(r){t=n.children[r],e(t.getNode()).attr("hidden")||i++}),i},n.prototype.countVisible=function(){var e=this,t=0;return Object.keys(e.children).forEach(function(n){e.children[n].isVisible()&&t++}),t},n.prototype.getNode=function(){return this.node},n.prototype.getAttachedNode=function(){return this.attachedNode},n.prototype.setNode=function(e){this.node=e},n.prototype.attachNode=function(e){this.attachedNode=e},n.prototype.setOptions=function(e){return this.options=e,this},n.prototype.getOptions=function(){return this.options},n.prototype.render=function(){function t(e,n,i){var r=e.replace(new RegExp("\\%"+n+"\\$s","g"),i);return pipes={replace:function(e,n){return t(r,e,n),pipes},get:function(){return r}},pipes}function n(e,i,r){var o="",a=Object.keys(i.children);return r=r||!1,a.forEach(function(e){o+=n("super-guacamole__menu__child",i.children[e])}),t(r?d:c,1,e+" menu-item"+(0<a.length?" menu-item-has-children":"")).replace(2,i.href).replace(3,i.title).replace(4,0<a.length?o:"").replace(5,i.id).get().replace('<ul class="sub-menu"></ul>',"")}function i(e){var t="";return Object.keys(e).forEach(function(i){t+=n("super-guacamole__menu",e[i])}),t}var r,o=this,c=o.templates.menu,d=o.templates.child,a=o.$container;o.options.$menu;return 0<a.length&&(a.append(i([o])),a.find(".super-guacamole__menu__child").each(function(){$current_el=e(this),id=e(this).attr("id"),r=a.find("#"+id.replace("sg-","")),0===r.length&&(r=a.find("."+id.replace("sg-",""))),0<r.length&&o.map(id,function(e){return e.attachNode(r),e.setNode($current_el),e})})),this},n.extract=function(t){function i(e){var t="",n=null,i=/menu\-item\-[0-9]+/i;return e.attr("class").split(" ").forEach(function(e){n=i.exec(e),null!==n&&(t=n[0])}),t}var r,o,c,d,a={};return t.each(function(t,u){r=e(u),o=r.find("a:first"),menuId=r.attr("id"),"undefined"==typeof menuId&&(menuId=i(r)),c=new n({id:"sg-"+menuId,href:o.attr("href"),title:o.get(0).childNodes[0].data}),c.attachNode(r),-1<r.children(".sub-menu").length&&(subMenu=n.extract(r.children(".sub-menu").children(".menu-item")),Object.keys(subMenu).forEach(function(e){d=subMenu[e],c.set(d)})),a[c.id]=c}),a},n.prototype.attachedNodesFit=function(){var t,n,i=this,r=0,o=0,c=e(e(".header-container > .container").length>0?".header-container > .container":".header-container > div"),d=i.$container.outerWidth(!0)-i.$container.find(".super-guacamole__menu").outerWidth(!0)-(parseInt(c.css("padding-left"),10)+parseInt(c.css("padding-right"),10))/2;return Object.keys(i.children).forEach(function(r){n=i.children[r],$attachedNode=e(n.getAttachedNode()),t=e(n.getNode()),$attachedNode.removeAttr("hidden"),t.attr("hidden",!0)}),Object.keys(i.children).forEach(function(c){n=n=i.children[c],$attachedNode=e(n.getAttachedNode()),t=e(n.getNode()),o=$attachedNode.outerWidth(!0),o>0&&$attachedNode.data("width",o),r+=$attachedNode.data("width"),r>d&&($attachedNode.attr("hidden",!0),t.removeAttr("hidden"))}),!0},n.prototype.menuFit=function(t){var n,i=this,r={removeAttr:function(e,t){return e.removeAttr(t)},attr:function(e,t){return e.attr(t,!0)}},o="removeAttr",c=i.options.threshold||768;return t=t||!1,0===i.countVisibleNodes()&&(o="attr"),e(window).width()<=c-1&&(o="attr",Object.keys(i.children).forEach(function(t){n=i.children[t],$attachedNode=e(n.getAttachedNode()),$node=e(n.getNode()),$attachedNode.removeAttr("hidden"),$node.attr("hidden",!0)})),t||r[o](i.$container.find(".super-guacamole__menu"),"hidden"),"removeAttr"===o},n.prototype.watch=function(t){function n(){r.attachedNodesFit(),r.menuFit()}function i(e){var t;return function(i){function r(){n(),timeout=null}t&&clearTimeout(t),t=setTimeout(r,e)}}var r=this;return(t=t||!1)?(n(),r):(e(window).on("resize",i(10)),e(window).on("orientationchange",i(10)),r)},e.fn.superGuacamole=function(t){var r,o,c,d,a=e(this),u=a.find("#main-menu");r={threshold:544,minChildren:3,childrenFilter:"li",menuTitle:"&middot;&middot;&middot;",menuUrl:"#",templates:i},o=e.extend(r,t),c=u.children(o.childrenFilter+":not(.super-guacamole__menu):not(.super-guacamole__menu__child)"),d=new n({title:o.menuTitle,href:o.menuUrl,templates:o.templates,children:n.extract(c),container:u}),o.$menu=u,d.setOptions(o).render().watch(!0).watch()}}(jQuery);

(function( $ ) {
	"use strict";

	CherryJsCore.utilites.namespace( 'theme_script' );
	CherryJsCore.theme_script = {
		init: function() {
			// Document ready event check
			if ( CherryJsCore.status.is_ready ) {
				this.document_ready_render();
			} else {
				CherryJsCore.variable.$document.on( 'ready', this.document_ready_render.bind( this ) );
			}

			// Windows load event check
			if ( CherryJsCore.status.on_load ) {
				this.window_load_render();
			} else {
				CherryJsCore.variable.$window.on( 'load', this.window_load_render.bind( this ) );
			}
		},

		document_ready_render: function() {
			// post-formats
			this.post_formats_custom_init( this );
			// widgets
			this.smart_slider_init( this );
			this.swiper_carousel_init( this );
			this.subscribe_init( this );
			this.playlist_slider_widget_init( this );
			this.news_smart_box_init( this );
			// menus
			this.navbar_init( this );
			this.main_menu( this, $( '.main-navigation' ) );
			this.mega_menu( this );
			this.mobile_menu( this );
			this.vertical_menu_init( this );
			// plugins
			this.add_project_inline_style( this );
			this.single_team( this );
			this.elementor_open_first_toggle( this );
			// anchor-navigation
			this.anchor_navigation( this );
			this.anchor_scrolling_navigation( this );
			// misc
			this.to_top_init( this );
			this.header_search( this );
			this.big_image_class( this );
			this.ofi_init( this );
			//bags fix//; this.tilter( this );
		},

		window_load_render: function() {
			this.page_preloader_init( this );
		},

		mobile_breakpoint: 600,

		anchor_navigation: function ( self ) {
			$( '.page-template-landing div:not(.woocommerce-tabs) a[href*=#]:not([href=#])' ).on( 'click', function() {

				var $window = CherryJsCore.variable.$window,
					width = $window.outerWidth( true ),
					$menuToggle = $( '.main-menu-toggle[aria-controls="main-menu"]' );

				if ( self.mobile_breakpoint > width ) {
					$menuToggle.trigger( 'click.mobileMenuToggle' );
				}

				if ( location.pathname.replace( /^\//, '' ) === this.pathname.replace( /^\//, '' ) || location.hostname === this.hostname ) {
					var target     = $( this.hash ),
						targetLink = this.hash,
						$correctionSelector = ( self.mobile_breakpoint <= width ) ? $( '#main-menu' ) : $( '.mobile-panel' ),
						menuHeight = $correctionSelector.outerHeight(true);

					target = target.length ? target : $( '[name=' + this.hash.slice( 1 ) + ']' );

					if ( target.length ) {
						$( 'html, body' ).animate( {
							scrollTop: target.offset().top - menuHeight
						}, 1000 );

						return false;
					}
				}
			});
		},

		anchor_scrolling_navigation: function ( self ) {

			var $document   = $( document ),
				top         = null,
				changed     = false,
				currentHash = null,
				sections    = null,
				timeout     = null,
				interval    = null,
				menuHeight  = $( '#main-menu' ).outerHeight(true),
				$sections   = $( '.tm_pb_anchor' );

			sections = getSections();


			$( window ).on( 'scroll.cherry_anchor_navigation', function () {
				var newTop  = $document.scrollTop();

				changed = newTop != top;

				if ( changed ) {
					top = newTop;
				}
			});

			$( window ).on( 'resize.cherry_anchor_navigation', function () {
				sections = getSections();
			});

			function getSections() {
				sections = $.map( $sections, function ( event ) {
					var $event = $( event ),
						position = $event.position();

					return {
						top: position.top - menuHeight,
						bottom: position.top + $event.outerHeight( true ) - menuHeight,
						hash: $event.attr( 'id' )
					};
				});

				return sections;

			}

			function iteration() {

				var sectionsLength = sections.length,
					section,
					scrollTop;

				while ( section = sections[--sectionsLength] ) {

					if ( section.top >= top || section.bottom <= top ) {
						continue;
					}

					if ( currentHash == section.hash ) {
						break;
					}

					currentHash = section.hash;
					history.pushState( null, null, '#' + section.hash );
				}
			}

			timeout = setTimeout( function (){
				interval = setInterval( iteration, 250 );
			}, 250 );
		},

		smart_slider_init: function( self ) {
			$( '.tourizto-smartslider' ).each( function() {
				var slider = $( this ),
					sliderId = slider.data( 'id' ),
					sliderWidth = slider.data( 'width' ),
					sliderHeight = slider.data( 'height' ),
					sliderOrientation = slider.data( 'orientation' ),
					slideDistance = slider.data( 'slide-distance' ),
					slideDuration = slider.data( 'slide-duration' ),
					sliderFade = slider.data( 'slide-fade' ),
					sliderNavigation = slider.data( 'navigation' ),
					sliderFadeNavigation = slider.data( 'fade-navigation' ),
					sliderPagination = slider.data( 'pagination' ),
					sliderAutoplay = slider.data( 'autoplay' ),
					sliderFullScreen = slider.data( 'fullscreen' ),
					sliderShuffle = slider.data( 'shuffle' ),
					sliderLoop = slider.data( 'loop' ),
					sliderThumbnailsArrows = slider.data( 'thumbnails-arrows' ),
					sliderThumbnailsPosition = slider.data( 'thumbnails-position' ),
					sliderThumbnailsWidth = slider.data( 'thumbnails-width' ),
					sliderThumbnailsHeight = slider.data( 'thumbnails-height' );

				if ( $( '.smart-slider__items', '#' + sliderId ).length > 0 ) {
					$( '#' + sliderId ).sliderPro( {
						width: sliderWidth,
						height: sliderHeight,
						orientation: sliderOrientation,
						slideDistance: slideDistance,
						slideAnimationDuration: slideDuration,
						fade: sliderFade,
						arrows: sliderNavigation,
						fadeArrows: sliderFadeNavigation,
						buttons: sliderPagination,
						autoplay: sliderAutoplay,
						fullScreen: sliderFullScreen,
						shuffle: sliderShuffle,
						loop: sliderLoop,
						waitForLayers: false,
						thumbnailArrows: sliderThumbnailsArrows,
						thumbnailsPosition: sliderThumbnailsPosition,
						thumbnailWidth: sliderThumbnailsWidth,
						thumbnailHeight: sliderThumbnailsHeight,
						init: function() {
							$( this ).resize();
						},
						sliderResize: function( event ) {
							var thisSlider = $( '#' + sliderId ),
								slides = $( '.sp-slide', thisSlider );

							slides.each( function() {

								if ( $( '.sp-title a', this ).width() > $( this ).width() ) {
									$( this ).addClass( 'text-wrapped' );
								} else {
									$( this ).removeClass( 'text-wrapped' );
								}

							} );
						},
						breakpoints: {
							899: {
								height: parseFloat( sliderHeight ) * 0.75
							},
							599: {
								height: parseFloat( sliderHeight ) * 0.5,
								thumbnailsPosition: ( 'top' === this.thumbnailsPosition ) ? 'top' : 'bottom',
								thumbnailHeight: parseFloat( sliderThumbnailsHeight ) * 0.75,
								thumbnailWidth: parseFloat( sliderThumbnailsWidth ) * 0.75
							}
						}
					} );
				}
			} );//each end
		},

		swiper_carousel_init: function( self ) {

			$( '.tm-products-carousel-widget-container' ).each( function() {
				var swiper             = null,
					uniqId             = $( this ).data( 'uniq-id' ),
					slidesPerView      = parseFloat( $( this ).data( 'slides-per-view' ) ),
					slidesPerGroup     = parseFloat( $( this ).data( 'slides-per-group' ) ),
					slidesPerColumn    = parseFloat( $( this ).data( 'slides-per-column' ) ),
					spaceBetweenSlides = parseFloat( $( this ).data( 'space-between-slides' ) ),
					durationSpeed      = parseFloat( $( this ).data( 'duration-speed' ) ),
					swiperLoop         = $( this ).data( 'swiper-loop' ),
					freeMode           = $( this ).data( 'free-mode' ),
					grabCursor         = $( this ).data( 'grab-cursor' ),
					mouseWheel         = $( this ).data( 'mouse-wheel' ),
					breakpointsList    = {
						992: {
							slidesPerView: Math.ceil( slidesPerView * 0.75 ),
							spaceBetween:  Math.ceil( spaceBetweenSlides * 0.75 )
						},
						768: {
							slidesPerView: Math.ceil( slidesPerView * 0.5 ),
							spaceBetween:  Math.ceil( spaceBetweenSlides * 0.5 )
						},
						480: {
							slidesPerView: Math.ceil( slidesPerView * 0.25 ),
							spaceBetween:  Math.ceil( spaceBetweenSlides * 0.25 )
						}
					},
					customBreakpoints  = $( this ).data( 'custom-breakpoints' );

				if ( '' !== customBreakpoints && 'object' === typeof customBreakpoints ) {
					breakpointsList = customBreakpoints;
				}

				swiper = new Swiper( '#' + uniqId, {
						slidesPerView:       slidesPerView,
						slidesPerGroup:      slidesPerGroup,
						slidesPerColumn:     slidesPerColumn,
						spaceBetween:        spaceBetweenSlides,
						speed:               durationSpeed,
						loop:                swiperLoop,
						freeMode:            freeMode,
						grabCursor:          grabCursor,
						mousewheelControl:   mouseWheel,
						paginationClickable: true,
						nextButton:          '#' + uniqId + '-next',
						prevButton:          '#' + uniqId + '-prev',
						pagination:          '#' + uniqId + '-pagination',
						//threshold:           5,
						onInit:              function() {
							$( '#' + uniqId + '-next' ).css( {
								'display': 'block'
							} );
							$( '#' + uniqId + '-prev' ).css( {
								'display': 'block'
							} );
						},
						breakpoints: breakpointsList
					}
				);
			} );

			// Enable swiper carousels
			jQuery( '.tourizto-carousel' ).each( function() {
				var swiper = null,
					uniqId = jQuery( this ).data( 'uniq-id' ),
					slidesPerView = parseFloat( jQuery( this ).data( 'slides-per-view' ) ),
					slidesPerGroup = parseFloat( jQuery( this ).data( 'slides-per-group' ) ),
					slidesPerColumn = parseFloat( jQuery( this ).data( 'slides-per-column' ) ),
					spaceBetweenSlides = parseFloat( jQuery( this ).data( 'space-between-slides' ) ),
					durationSpeed = parseFloat( jQuery( this ).data( 'duration-speed' ) ),
					swiperLoop = jQuery( this ).data( 'swiper-loop' ),
					freeMode = jQuery( this ).data( 'free-mode' ),
					grabCursor = jQuery( this ).data( 'grab-cursor' ),
					mouseWheel = jQuery( this ).data( 'mouse-wheel' ),
					breakpointsSettings = {
						1199: {
							slidesPerView: Math.floor( slidesPerView * 0.75 ),
							spaceBetween: Math.floor( spaceBetweenSlides * 0.75 )
						},
						899: {
							slidesPerView: Math.floor( slidesPerView * 0.5 ),
							spaceBetween: Math.floor( spaceBetweenSlides * 0.5 )
						},
						599: {
							slidesPerView: ( 0 !== Math.floor( slidesPerView * 0.25 ) ) ? Math.floor( slidesPerView * 0.25 ) : 1
						}
					};

				if ( 1 == slidesPerView ) {
					breakpointsSettings = {}
				}

				var swiper = new Swiper( '#' + uniqId, {
						slidesPerView: slidesPerView,
						slidesPerGroup: slidesPerGroup,
						slidesPerColumn: slidesPerColumn,
						spaceBetween: spaceBetweenSlides,
						speed: durationSpeed,
						loop: swiperLoop,
						freeMode: freeMode,
						grabCursor: grabCursor,
						mousewheelControl: mouseWheel,
						paginationClickable: true,
						nextButton: '#' + uniqId + '-next',
						prevButton: '#' + uniqId + '-prev',
						pagination: '#' + uniqId + '-pagination',
						onInit: function() {
							$( '#' + uniqId + '-next' ).css( { 'display': 'block' } );
							$( '#' + uniqId + '-prev' ).css( { 'display': 'block' } );
						},
						breakpoints: breakpointsSettings
					}
				);
			} );
		},

		post_formats_custom_init: function( self ) {
			CherryJsCore.variable.$document.on( 'cherry-post-formats-custom-init', function( event ) {

				if ( 'slider' !== event.object ) {
					return;
				}

				var uniqId = '#' + event.item.attr( 'id' ),
					settings = event.item.data( 'init' ),
					swiper = new Swiper( uniqId, {
						pagination: uniqId + ' .swiper-pagination',
						paginationClickable: true,
						nextButton: uniqId + ' .swiper-button-next',
						prevButton: uniqId + ' .swiper-button-prev',
						spaceBetween: 0,
						loop: settings['loop'],
						onInit: function() {
							if ( settings['arrows'] ) {
								$( uniqId + ' .swiper-button-next' ).css( { 'display': 'block' } );
								$( uniqId + ' .swiper-button-prev' ).css( { 'display': 'block' } );
							}
							if ( settings['buttons'] ) {
								$( uniqId + ' .swiper-pagination' ).css( { 'display': 'block' } );
							}
						}
					} );

				event.item.data( 'initalized', true );
			} );
		},

		navbar_init: function( self ) {

			$( window ).load( function() {

				var $navbar = $( '.header-container' );

				if ( !$.isFunction( jQuery.fn.stickUp ) || undefined === window.tourizto.stickUp || !$navbar.length ) {
					return !1;
				}

				$navbar.stickUp( {
					correctionSelector: '#wpadminbar',
					listenSelector: '.listenSelector',
					pseudo: true,
					active: true
				} );
				CherryJsCore.variable.$document.trigger( 'scroll.stickUp' );

			} );
		},

		subscribe_init: function( self ) {
			CherryJsCore.variable.$document.on( 'click', '.subscribe-block__submit', function( event ) {

				event.preventDefault();

				var $this = $( this ),
					form = $this.parents( 'form' ),
					nonce = form.find( 'input[name="nonce"]' ).val(),
					mail_input = form.find( 'input[name="subscribe-mail"]' ),
					mail = mail_input.val(),
					error = form.find( '.subscribe-block__error' ),
					success = form.find( '.subscribe-block__success' ),
					hidden = 'hidden';

				if ( $this.hasClass( 'processing' ) ) {
					return !1;
				}

				$this.addClass( 'processing' );
				error.empty();
				mail_input.removeClass( 'error' );

				if ( !error.hasClass( hidden ) ) {
					error.addClass( hidden );
				}

				if ( !success.hasClass( hidden ) ) {
					success.addClass( hidden );
				}

				$.ajax( {
					url: tourizto.ajaxurl,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'tourizto_subscribe',
						mail: mail,
						nonce: nonce
					},
					error: function() {
						$this.removeClass( 'processing' );
					}
				} ).done( function( response ) {

					$this.removeClass( 'processing' );

					if ( true === response.success ) {
						success.removeClass( hidden );
						mail_input.val( '' );
						return 1;
					}

					error.removeClass( hidden ).html( response.data.message );
					mail_input.addClass( 'error' );
					return !1;

				} );

			} );
		},

		isMegaMenuEnabled: function() {

			if ( undefined === window.tourizto.megaMenu ) {
				return false;
			}

			if ( true === window.tourizto.megaMenu.isActive && 'main' == window.tourizto.megaMenu.location ) {
				return true;
			}

			return false;
		},

		main_menu: function( self, $mainNavigation ) {

			if ( self.isMegaMenuEnabled() ) {
				//return;
			}

			var transitionend = 'transitionend oTransitionEnd webkitTransitionEnd',
				moreMenuContent = '&middot;&middot;&middot;',
				imgurl = '',
				srcset = '',
				hasimg = false,
				hasicon = false,
				hasprop = Object.prototype.hasOwnProperty,
				$menuToggle = $( '.main-menu-toggle[aria-controls="main-menu"]', $mainNavigation ),
				liWithChildren = 'li.menu-item-has-children, li.page_item_has_children',
				$body = $( 'body' ),
				$parentNode,
				menuItem,
				subMenu,
				index = -1,
				$layout = window.tourizto.labels.header_layout;

			if ( hasprop.call( window, 'tourizto' ) &&
				hasprop.call( window.tourizto, 'more_button_options' ) &&
				hasprop.call( window.tourizto.more_button_options, 'more_button_type' ) ) {
				switch ( window.tourizto.more_button_options.more_button_type ) {
					case 'image':
						imgurl = window.tourizto.more_button_options.more_button_image_url;
						if ( window.tourizto.more_button_options.retina_more_button_image_url ) {
							srcset = ' srcset="' + window.tourizto.more_button_options.retina_more_button_image_url + ' 2x"';
						}
						moreMenuContent = '<img src="' + imgurl + '"' + srcset + ' alt="' + moreMenuContent + '">';
						hasimg = true;
						break;
					case 'icon':
						moreMenuContent = '<i class="nc-icon-mini ' + window.tourizto.more_button_options.more_button_icon + '"></i>';
						hasicon = true;
						break;
					case 'text':
					default:
						moreMenuContent = window.tourizto.more_button_options.more_button_text || moreMenuContent;
						hasimg = false;
						hasicon = false;
						break;
				}
			}

			if ( 'style-3' !== $layout && 'style-7' !== $layout ) {
				$mainNavigation.superGuacamole( {
					threshold: self.mobile_breakpoint, // Minimal menu width, when this plugin activates
					minChildren: 3, // Minimal visible children count
					childrenFilter: '.menu-item:not(.wpml-ls-item):not([class*="pll-"]):not(.lang-item)', // Child elements selector
					menuTitle: moreMenuContent, // Menu title
					menuUrl: '#',
					templates: {
						menu: '<li id="%5$s" class="%1$s' + ( hasimg ? ' super-guacamole__menu-with-image' : '' ) +
						( hasicon ? ' super-guacamole__menu-with-icon' : '' ) + '"><a href="%2$s">%3$s</a><ul class="sub-menu">%4$s</ul></li>',
						child_wrap: '<ul class="%1$s">%2$s</ul>',
						child: '<li id="%5$s" class="%1$s"><a href="%2$s">%3$s</a><ul class="sub-menu">%4$s</ul></li>'
					}
				} );
			}

			function hideSubMenu( menuItem, $event ) {
				var subMenus = menuItem.find( '.sub-menu' ),
					subMenu = menuItem.children( '.sub-menu' ).first();

				menuItem
					.removeData( 'index' )
					.removeClass( 'menu-hover' );

				subMenus.addClass( 'in-transition' );

				subMenus
					.one( transitionend, function() {
						subMenus.removeClass( 'in-transition' );
					} );
			}

			function handleMenuItemHover( $event ) {
				if ( $( 'html' ).hasClass( 'mobile-menu-active' ) ) {
					return;
				}
				menuItem = $( $event.target ).parents( '.menu-item' );
				subMenu = menuItem.children( '.sub-menu' ).first();

				var subMenus = menuItem.find( '.sub-menu' );

				if ( !menuItem.hasClass( 'menu-item-has-children' ) ) {
					menuItem = $event.target.tagName === 'LI' ?
						$( $event.target ) :
						$( $event.target ).parents().filter( '.menu-item' );
				}

				switch ( $event.type ) {
					case 'mouseenter':
					case 'mouseover':
						if ( 0 < subMenu.length ) {
							var maxWidth = $body.outerWidth( true ),
								subMenuOffset = subMenu.offset().left + subMenu.outerWidth( true );

							menuItem.addClass( 'menu-hover' );
							subMenus.addClass( 'in-transition' );

							if ( $( 'body' ).hasClass( 'rtl' ) ) {
								if ( 0 > subMenu.offset().left ) {
									subMenu.addClass( 'inverse-side' );
									subMenu.find( '.sub-menu' ).addClass( 'inverse-side' );
								} else if ( maxWidth <= subMenuOffset ) {
									subMenu.removeClass( 'inverse-side' );
									subMenu.find( '.sub-menu' ).removeClass( 'inverse-side' );
								}

							} else {
								if ( maxWidth <= subMenuOffset ) {
									subMenu.addClass( 'inverse-side' );
									subMenu.find( '.sub-menu' ).addClass( 'inverse-side' );
								} else if ( 0 > subMenu.offset().left ) {
									subMenu.removeClass( 'inverse-side' );
									subMenu.find( '.sub-menu' ).removeClass( 'inverse-side' );
								}
							}

							subMenus
								.one( transitionend, function() {
									subMenus.removeClass( 'in-transition' );
								} );
						}
						break;
					case 'mouseleave':
					case 'mouseout':
						hideSubMenu( menuItem, $event );
						break;
				}
			}

			CherryJsCore.variable.$window.on( 'orientationchange resize', function() {
				if ( $( 'html' ).hasClass( 'mobile-menu-active' ) ) {
					return;
				}
				$mainNavigation.find( '.menu-item' ).removeClass( 'menu-hover' );
				$mainNavigation.find( '.sub-menu.inverse-side' ).removeClass( 'inverse-side' );
			} );

			$( liWithChildren ).hoverIntent( {
				over: function() {
				},
				out: function() {
				},
				timeout: 300,
				selector: '.menu-item'
			} );

			$mainNavigation.on( 'mouseenter mouseover mouseleave mouseout', '.menu-item', handleMenuItemHover );

			function doubleClickMenu( $jqEvent ) {
				$parentNode = $( this );

				if ( $( 'html' ).hasClass( 'mobile-menu-active' ) ) {
					return true;
				}

				var menuIndex = $parentNode.index();

				if ( menuIndex !== parseInt( $parentNode.data( 'index' ), 10 ) ) {
					$jqEvent.preventDefault();
				}

				$parentNode.data( 'index', menuIndex );
			}

			// Check if touch events supported
			if ( 'ontouchend' in window ) {

				// Attach event listener for double click
				$( liWithChildren, $mainNavigation )
					.on( 'click', doubleClickMenu );

				// Reset index on touchend event
				CherryJsCore.variable.$document.on( 'touchend', function( $jqEvent ) {
					if ( !$( 'html' ).hasClass( 'mobile-menu-active' ) ) {
						$parentNode = $( $jqEvent.target ).parents().filter( '.menu-item:first' );

						if ( $parentNode.hasClass( 'menu-hover' ) === false ) {
							hideSubMenu( $parentNode, $jqEvent );

							index = $parentNode.data( 'index' );

							if ( index ) {
								$parentNode.data( 'index', parseInt( index, 10 ) - 1 );
							}
						}
					}
				} );
			}

			$menuToggle.on( 'click', function( $event ) {
				$event.preventDefault();
				$mainNavigation.toggleClass( 'toggled' );
			} );
		},

		mobile_menu: function( self ) {

			var $mainNavigation = $( '.main-navigation' ),
				$menuToggle = $( '.main-menu-toggle[aria-controls="main-menu"]' );

			$mainNavigation
				.find( 'li.menu-item-has-children > a' )
				.append( '<span class="sub-menu-toggle"></span>' );

			/**
			 * Debounce the function call
			 *
			 * @param  {number}   threshold The delay.
			 * @param  {Function} callback  The function.
			 */
			function debounce( threshold, callback ) {
				var timeout;

				return function debounced( $event ) {
					function delayed() {
						callback.call( this, $event );
						timeout = null;
					}

					if ( timeout ) {
						clearTimeout( timeout );
					}

					timeout = setTimeout( delayed, threshold );
				};
			}

			/**
			 * Resize event handler.
			 *
			 * @param {jqEvent} jQuery event.
			 */
			function resizeHandler( $event ) {
				var $window = CherryJsCore.variable.$window,
					width = $window.outerWidth( true );

				if ( self.mobile_breakpoint <= width ) {
					$mainNavigation.removeClass( 'mobile-menu' );
				} else {
					$mainNavigation.addClass( 'mobile-menu' );
				}
			}

			/**
			 * Toggle sub-menus.
			 *
			 * @param  {jqEvent} $event jQuery event.
			 */
			function toggleSubMenuHandler( $event ) {

				$event.preventDefault();

				$( this ).toggleClass( 'active' );
				$( this ).parents().filter( 'li:first' ).toggleClass( 'sub-menu-open' );
			}

			/**
			 * Toggle menu.
			 *
			 * @param  {jqEvent} $event jQuery event.
			 */
			function toggleMenuHandler( $event ) {
				var $toggle = $( '.sub-menu-toggle' );

				if ( !$event.isDefaultPrevented() ) {
					$event.preventDefault();
				}

				setTimeout( function() {
					if ( !$mainNavigation.hasClass( 'animate' ) ) {
						$mainNavigation.addClass( 'animate' );
					}
					$mainNavigation.toggleClass( 'show' );
					$( 'html' ).toggleClass( 'mobile-menu-active' );
				}, 10 );

				$menuToggle.toggleClass( 'toggled' );
				$menuToggle.attr( 'aria-expanded', !$menuToggle.hasClass( 'toggled' ) );

				if ( $toggle.hasClass( 'active' ) ) {
					$toggle.removeClass( 'active' );
					$mainNavigation.find( '.sub-menu-open' ).removeClass( 'sub-menu-open' );
				}
			}

			resizeHandler();
			CherryJsCore.variable.$window.on( 'resize orientationchange', debounce( 500, resizeHandler ) );
			$( '.sub-menu-toggle', $mainNavigation ).on( 'click', toggleSubMenuHandler );
			$menuToggle.on( 'click.mobileMenuToggle', toggleMenuHandler );
		},

		mega_menu: function( self ) {

			/**
			 * Mega-menu mobile SubMenu Toggled.
			 */
			function megaMenuSubMenuToggled() {
				$( this ).toggleClass( 'active' );
			}

			// ADD to Mega-menu sub-menu toggle active class.
			$( '.mega-menu-mobile-arrow' ).on( 'click', megaMenuSubMenuToggled );
		},

		page_preloader_init: function( self ) {

			if ( $( '.page-preloader-cover' )[0] ) {
				$( '.page-preloader-cover' ).delay( 500 ).fadeTo( 500, 0, function() {
					$( this ).remove();
				} );
			}
		},

		to_top_init: function( self ) {
			if ( $.isFunction( jQuery.fn.UItoTop ) && undefined !== window.tourizto.toTop ) {
				$().UItoTop( {
					text: tourizto.labels.totop_button,
					scrollSpeed: 600
				} );
			}
		},

		ofi_init: function( self ) {
			if ( $( 'body' ).hasClass( 'ie' ) && 'undefined' !== typeof objectFitImages ) {
				objectFitImages();
			}
		},

		playlist_slider_widget_init: function( self ) {
			$( '.widget-playlist-slider .playlist-slider' ).each( function() {
				var $this = $( this ),
					settings = $this.data( 'settings' ),
					breakpoints = JSON.parse( settings.breakpoints );

				$this.sliderPro( {
					autoplay: false,
					width: settings['width'],
					height: parseInt( settings['height'] ),
					orientation: 'vertical',
					waitForLayers: false,
					touchSwipe: false,
					updateHash: false,
					arrows: settings['arrows'],
					buttons: settings['buttons'],
					thumbnailArrows: settings['thumbnailArrows'],
					thumbnailsPosition: settings['thumbnailsPosition'],
					thumbnailPointer: true,
					thumbnailWidth: settings['thumbnailWidth'],
					thumbnailHeight: settings['thumbnailHeight'],
					breakpoints: breakpoints,
					init: function() {
						$this.resize().fadeTo( 0, 1 );
					},
					gotoSlideComplete: function( event ) {
						var prevSlide = $( '.sp-slide', $this ).eq( event.previousIndex ),
							iframe = prevSlide.find( 'iframe' ),
							html5Video = prevSlide.find( 'video' );

						if ( iframe[0] ) {
							var iframeSrc = iframe.attr( 'src' );

							iframe.attr( 'src', iframeSrc );
						} else if ( html5Video[0] ) {
							html5Video[0].stop();
						}
					}
				} );
			} );
		},

		news_smart_box_init: function( self ) {
			jQuery( '.news-smart-box__instance' ).each( function() {
				var uniqId = $( this ).data( 'uniq-id' ),
					instanceSettings = $( this ).data( 'instance-settings' ),
					instance = $( '#' + uniqId ),
					$termItem = $( '.news-smart-box__navigation-terms-list-item', instance ),
					$currentTerm = $( '.news-smart-box__navigation-title', instance ),
					$listContainer = $( '.news-smart-box__wrapper', instance ),
					$ajaxPreloader = $( '.nsb-spinner', instance ),
					$termsList = $( '.news-smart-box__navigation-terms-list', instance ),
					$menuToggle = $( '.menu-toggle', instance ),
					ajaxGetNewInstance = null,
					ajaxGetNewInstanceSuccess = true,
					showNewItems = null;

				$termItem.each( function() {
					var slug = $( this ).data( 'slug' );

					if ( 'category' === instanceSettings.terms_type ) {
						if ( slug === instanceSettings.current_category ) {
							$( this ).addClass( 'is-active' );
						}
					} else {
						if ( slug === instanceSettings.current_tag ) {
							$( this ).addClass( 'is-active' );
						}
					}
				} );

				$menuToggle.on( 'click', function( $jqEvent ) {
					$jqEvent.preventDefault();
					$menuToggle.toggleClass( 'news-smart-box__navigation-toggle--open' );
					$termsList.toggleClass( 'news-smart-box__navigation-terms-list--open' );
				} );

				$termItem.on( 'click', function() {
					var slug = $( this ).data( 'slug' ),
						data = {
							action: 'new_smart_box_instance',
							nonce: tourizto.new_smart_box_nonce,
							value_slug: slug,
							instance_settings: instanceSettings
						},
						currentTermName = $( this ).text(),
						counter = 0;

					if ( !$( this ).hasClass( 'is-active' ) ) {
						$termItem.removeClass( 'is-active' );
						$( this ).addClass( 'is-active' );
					}

					$currentTerm.html( currentTermName );

					if ( ajaxGetNewInstance !== null ) {
						ajaxGetNewInstance.abort();
					}
					ajaxGetNewInstance = $.ajax( {
						type: 'GET',
						url: tourizto.ajaxurl,
						data: data,
						cache: false,
						beforeSend: function() {
							ajaxGetNewInstanceSuccess = false;
							$ajaxPreloader.css( { 'display': 'block' } ).fadeTo( 300, 1 );
						},
						success: function( response ) {
							ajaxGetNewInstanceSuccess = true;

							$ajaxPreloader.fadeTo( 300, 0, function() {
								$( this ).css( { 'display': 'none' } );
							} );

							$( '.news-smart-box__listing', $listContainer ).html( response );

							counter = 0;
							$( '.news-smart-box__item-inner', $listContainer ).addClass( 'animate-cycle-show' );
							$( '.news-smart-box__item', $listContainer ).each( function() {
								showItem( $( this ), 100 * parseInt( counter ) + 200 );
								counter++;
							} )

						}
					} );

				} );

				var showItem = function( itemList, delay ) {
					var timeOutInterval = setTimeout( function() {
						$( '.news-smart-box__item-inner', itemList ).removeClass( 'animate-cycle-show' );
					}, delay );
				}
			} );
		},
		header_search: function( self ) {
			var $header = $( '.site-header' ),
				$searchToggle = $( '.search-form__toggle, .search-form__close', $header ),
				$headerSearch = $( '.header-search', $header ),
				$searchInput = $( '.search-form__field', $headerSearch ),
				searchHandler = function( event ) {
					$header.toggleClass( 'search-active' );
					if ( $header.hasClass( 'search-active' ) ) {
						$searchInput.focus();
					}
				},
				removeActiveClass = function( event ) {
					if ( $( event.target ).closest( $searchToggle ).length || $( event.target ).closest( $headerSearch ).length ) {
						return;
					}

					if ( $header.hasClass( 'search-active' ) ) {
						$header.removeClass( 'search-active' );
					}

					event.stopPropagation();
				};

			$searchToggle.on( 'click', searchHandler );
			CherryJsCore.variable.$document.on( 'click', removeActiveClass );

		},
        vertical_menu_init: function( self ) {
            var $mainNavigation = $( '.main-navigation.vertical-menu' ),
                $mainMenu = $( '.menu', $mainNavigation ),
                $back = $( '.back', $mainNavigation ),
                $close = $( '.close', $mainNavigation ),
                currentTranslate = parseInt( $mainMenu.css( 'transform' ) ),
                isAnimate = false,
                offset = 400;

            resizeHandler();
            CherryJsCore.variable.$window.on( 'resize', resizeHandler );

            function resizeHandler( event ) {
                var $window = CherryJsCore.variable.$window,
                    width = $window.outerWidth( true );

                if ( 425 > width ) {
                    $mainNavigation.removeClass( 'vertical-menu' );

                    $( '.vertical-menu-toggle' ).off( 'click.open', openHandler );
                    $( '.menu-item-has-children > a', $mainNavigation ).off( 'click.active', clickHandler );
                    $back.off( 'click.back', backHandler );
                    $close.off( 'click.close', closeHandler );
                    $mainMenu.removeClass( 'scroll' );

                    $( '.main-navigation .menu' ).css( 'transform', 'translateX(0)' );

                } else {
                    $mainNavigation.addClass( 'vertical-menu' );
                    $( '.vertical-menu-toggle' ).off( 'click.open', openHandler ).on( 'click.open', openHandler );
                    $( '.menu-item-has-children > a', $mainNavigation ).off( 'click.active', clickHandler ).on( 'click.active', clickHandler );
                    $back.on( 'click.back', backHandler );
                    $close.on( 'click.close', closeHandler );
                    $mainMenu.addClass( 'scroll' );
                }
            }

            $( '.menu-item-has-children', $mainNavigation ).each( function() {
                var $li = $( this ),
                    $link = $( '>a', $li ),
                    linkText = $link.html(),
                    linkHref = $link[0].cloneNode( true ),
                    $subMenu = $( '> .sub-menu', $li );
                $subMenu.prepend( '<li class="parent-title"><a href="' + linkHref + '">' + linkText + '</a></li>' );
            } );

            function openHandler( event ) {
                $( this ).toggleClass( 'toggled' );
                if ( $mainNavigation.hasClass( 'menu-open' ) ) {
                    closeHandler();
                    return false;
                }
                $mainNavigation.toggleClass( 'menu-open' );
            }

            function backHandler( event ) {
                var currentTranslate = parseInt( ($mainMenu.css( 'transform' ).replace( /,/g, "" )).split( " " )[4] ),
                    translate = currentTranslate + offset,
                    $active = $( '.active', $mainMenu ),
                    $lastActive = $( $active[$active.length - 1] );

                if ( isAnimate ) {
                    return false;
                }

                if ( currentTranslate < 0 ) {
                    isAnimate = true;
                    $mainMenu.css( 'transform', 'translateX(' + translate + 'px)' );

                    if ( translate >= 0 ) {
                        translate = 0;
                        $( this ).addClass( 'hide' );
                        $close.removeClass( 'hide' );
                    }

                    setTimeout( function() {
                        $lastActive.removeClass( 'active' );
                        $lastActive.siblings().toggleClass( 'hide' );
                        $( '>a', $lastActive ).removeClass( 'hide' );
                        $lastActive.parent().addClass( 'scroll' );
                        isAnimate = false;
                    }, 250 );

                }
                return false;
            }

            function closeHandler( event ) {
                if ( !isAnimate ) {
                    $( '.active', $mainMenu ).removeClass( 'active' );
                    $( '.hide', $mainMenu ).removeClass( 'hide' );
                    $( '.close.hide', $mainNavigation ).removeClass( 'hide' );
                    $back.addClass( 'hide' );
                    $mainNavigation.removeClass( 'menu-open' );
                    $mainMenu.css( 'transform', 'translateX(0)' );
                }

                $( '.vertical-menu-toggle' ).removeClass( 'toggled' );
                return false;
            }

            function clickHandler( event ) {
                var $_target = $( event.currentTarget ),
                    $mainMenu = $_target.closest( '.menu' ),
                    deep = $_target.parents( 'ul' ).length,
                    translate = deep * offset;

                $mainMenu.css( 'transform', 'translateX(' + -translate + 'px)' );

                setTimeout( function() {
                    $_target.parent().addClass( 'active' );
                    $_target.parent().siblings().toggleClass( 'hide' );
                    $_target.parents( '.active' ).find( '> a' ).addClass( 'hide' );
                    $_target.siblings( 'ul' ).addClass( 'scroll' );
                    $_target.parents( 'ul' ).removeClass( 'scroll' );
                }, 250 );

                $back.removeClass( 'hide' );
                $close.addClass( 'hide' );

                return false;
            }
        },

		big_image_class: function ( self ) {
			var $img = $( '.entry-content p img' );
			$img.each( function() {
				var $this = $( this ),
					$imgWidth = $this.attr( 'width' );

				if ( 1600 <= $imgWidth ) {
					$this.addClass( 'size-big' );
				}
			});
		},

		add_project_inline_style: function( self ) {
			var $projectGridContainer = $( '.projects-container.grid-layout' ),
				$projectJustifiedContainer = $( '.projects-container.justified-layout' ),
				$projectGridTermsContainer = $( '.projects-terms-container.grid-layout' ),
				$projectContainer = $( '.projects-container' ),

				addInlineStyle = function() {
					var $this = $( this ),
						$projectsSettings = $this.data( 'settings' ),
						$projectItemIndent = Math.ceil( +$projectsSettings['item-margin'] );

					$this.css( {
						'margin-left': -$projectItemIndent / 2 + 'px',
						'margin-right': -$projectItemIndent / 2 + 'px'
					} );
				},

				addTemplateClass = function() {
					var $this = $( this ),
						$projectsSettings = $this.data( 'settings' ),
						$projectsTemplate = $projectsSettings['template'];

					if ( $projectsTemplate ) {
						$this.addClass( $projectsTemplate.replace( '.', '-' ) );
					}
				};

			$projectGridContainer.each( addInlineStyle );
			$projectJustifiedContainer.each( addInlineStyle );
			$projectGridTermsContainer.each( addInlineStyle );

			$projectContainer.each( addTemplateClass );
		},

		single_team: function ( self ) {
			var avatar_style = function() {
				var $avatar = $( '.single-team .template-single .team-cover-image + .team-photo .avatar' ),
					$avatarWidth = $avatar.width();

				$avatar.css( {
					'marginTop': -$avatarWidth / 2
				} );
			};

			CherryJsCore.variable.$window.on( 'resize.singleTeam, load.singleTeam', avatar_style );
		},

		elementor_open_first_toggle: function ( self ) {
			if ( $( '.elementor-toggle' )[0] ) {
				$( '.elementor-toggle' ).each( function() {
					$(this).find( '.elementor-toggle-title:first-child' ).addClass( 'active' ).next().show();
				} )
			}
		}
		//bags fix//;
		// ,
		// tilter: function ( self ) {
		// 	[].slice.call(document.querySelectorAll('.template-media-icon-bg-2 .services-item')).forEach(function(el) {
		// 		new TiltFx(el);
		// 	});
		// }
	};
	CherryJsCore.theme_script.init();

}( jQuery ));


