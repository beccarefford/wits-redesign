/**
 * Unslider - http://unslider.com/
 * License: GPL Compatible (WTFPL)
 * Copyright: Visual Idiot, http://visualidiot.com/
 * Contributors: ShamoX, https://github.com/ShamoX
 *
 * We've modified this plugin to set all slides equal to the maximum height of the tallest slide
 * as well as a few other modifications.
 *
 * @see unslider.js for reference.
 */

(function($, f) {
	var Unslider = function() {
		//  Object clone
		var _ = this;

		//  Set some options
		_.o = {
			speed: 500,     // animation speed, false for no transition (integer or boolean)
			delay: 3000,    // delay between slides, false for no autoplay (integer or boolean)
			init: 0,        // init delay, false for no delay (integer or boolean)
			pause: !f,      // pause on hover (boolean)
			loop: !f,       // infinitely looping (boolean)
			keys: f,        // keyboard shortcuts (boolean)
			dots: f,        // display dots pagination (boolean)
			dots_padding: 50, // padding added to bottom of element container for dots
			arrows: f,      // display prev/next arrows (boolean)
			prev: '&larr;', // text or html inside prev button (string)
			next: '&rarr;', // same as for prev option
			fluid: f,       // is it a percentage width? (boolean)
			starting: f,    // invoke before animation (function with argument)
			complete: f,    // invoke after animation (function with argument)
			items: '>ul',   // slides container selector
			item: '>li',    // slidable items selector
			column_item: false, // if columns exist within item
			row_item: false, // if rows exist within item (or if false, the row item defaults to item)
			use_item_width_until: 768, // use the item width until this value, then use the column_item width
			easing: 'swing',// easing function to use for animation
			autoplay: true,  // enable autoplay on initialisation
			resize_timeout: 50, // timeout for the window resize event
			reinit: !f, // slider will re-initialize at use_item_width_until (boolean)
			max_slide_height: f // slideshow will inherit the maximum height of the tallest slide across all slides (boolean)
		};

		_.init = function(el, o, reinit) {
			// Is this a re-initialization?
			reinit = reinit || false;

			//  Check whether we're passing any options in to Unslider
			_.o = $.extend(_.o, o);

			// Default row item to item if not set
			if ( ! reinit && ! _.o.row_item ) {
				_.o.row_item = _.o.item;
			}

			// Cache original items
			_.orig_items = _.orig_items || {
				items: _.o.items,
				item: _.o.item,
				column_item: _.o.column_item,
				row_item: _.o.row_item
			};

			_.el = el;
			_.ul = el.find(_.o.items);
			_.max = [el.outerWidth() | 0, el.outerHeight() | 0, 0]; // Width, height, slide (item) height
			_.li = _.ul.find(_.o.item).each(function(index) {
				var me = $(this),
					width = me.outerWidth(),
					height = me.outerHeight();

				//  Set the max values
				if (width > _.max[0]) _.max[0] = width;
				if (height > _.max[1]) _.max[1] = height;
				if (height > _.max[2]) _.max[2] = height
			});


			//  Cached vars
			var o = _.o,
				ul = _.ul,
				li = _.li,
				column_items = ul.find( _.orig_items.column_item ),
				row_items = ul.find( _.orig_items.row_item ),
				len = li.length,
				$window = $( window ),
				true_column_index,
				true_row_index,
				styl;

			//  Current indeed
			_.i = ( reinit ) ? _.i : 0;

			// Set the true current value on responsive devices
			if ( reinit && _.orig_items.column_item && $window.width() < o.use_item_width_until ) {
				true_column_index = column_items.index( row_items.eq( _.i ).children() );

				// If the "true" slide doesn't match the current value
				if ( true_column_index >= 0 && true_column_index !== _.i ) {
					_.i = true_column_index;

					// Navigate _.to that slide
					_.to( _.i );
				}
			}
			// Otherwise on desktops we need to revert back to rows
			else if ( reinit ) {
				true_column_index = column_items.index( column_items.eq( _.i ) );
				true_row_index = ( true_column_index >= 0 ) ? row_items.index( column_items.eq( true_column_index ).parents( _.orig_items.row_item ) ) : 0;

				// If the "true" row slide doesn't match the current value
				if ( true_row_index >= 0 && true_row_index !== _.i ) {
					_.i = true_row_index;

					// Navigate _.to that slide
					_.to( _.i );
				}
			}

			//  Set the main element
			styl = {width: ( o.fluid ) ? '100%' : _.max[0], height: ( o.max_slide_height ) ? _.max[2] : li.first().outerHeight(), overflow: 'hidden'};

			if (o.dots) {
				styl.height += o.dots_padding;
			}

			el.css(styl);

			//  Set the relative widths
			ul.css(( reinit ) ? {position: 'relative', width: (len * 100) + '%'} : {position: 'relative', left: 0, width: (len * 100) + '%'});
			if(o.fluid) {
				li.css({'float': 'left', width: (100 / len) + '%'});

				// Remove CSS from "old" column_item on re-initialization
				if ( reinit && _.orig_items.column_item && $window.width() > _.o.use_item_width_until ) {
					el.find( _.orig_items.column_item ).css( {
						float: '',
						width: ''
					} );
				}
				// Otherwise if this is a re-initialization
				else if ( reinit && _.orig_items.row_item && $window.width() < _.o.use_item_width_until ) {
					li.css( { width: row_items.width() } );

					// We have to run this twice to ensure the heights are adjusted properly (this one uses outerWidth)
					setTimeout( function() {
						var styl;
						li.css( { width: el.width() } );

						if ( $window.width() < _.o.use_item_width_until ) {
							// Adjust the row width based on number of columns
							row_items.each( function () {
								var $li = $( this ),
									$children = $li.children(); // TODO: Test o.column_item as the selector

								$li.css({ width: ( el.width() * $children.length ) + 'px' });
							} );
						}

						// Reset max slider height
						_.max[2] = 0;

						_.ul.find( ( $window.width() > _.o.use_item_width_until ) && _.o.column_item || _.o.item ).each(function(e) {
							var $this = $(this),
								height = $this.outerHeight();
							if( height > _.max[2]) _.max[2] = height;
						});

						styl = {height: ( o.max_slide_height ) ? _.max[2] : li.eq(_.i).outerHeight() };

						_.ul.css( styl );

						if (o.dots) {
							styl.height += o.dots_padding;
						}

						el.css( styl );

					}, o.resize_timeout + 10 ); // After window resize event
				}
			} else {
				li.css({'float': 'left', width: (_.max[0]) + 'px'});

				// Remove CSS from column_item
				if ( reinit && _.orig_items.column_item && $window.width() > _.o.use_item_width_until ) {
					el.find( _.orig_items.column_item ).css( {
						float: '',
						width: ''
					} );
				}
			}

			//  Autoslide
			o.autoplay && setTimeout(function() {
				if (o.delay | 0) {
					_.play();

					if (o.pause) {
						el.on('mouseover mouseout', function(e) {
							_.stop();
							e.type === 'mouseout' && _.play();
						});
					};
				};
			}, o.init | 0);

			//  Keypresses
			if (o.keys) {
				$(document).keydown(function(e) {
					switch(e.which) {
						case 37:
							_.prev(); // Left
							break;
						case 39:
							_.next(); // Right
							break;
						case 27:
							_.stop(); // Esc
							break;
					};
				});
			};

			//  Dot pagination
			! reinit && o.dots && nav('dot');

			//  Dot pagination (reinit)
			reinit && o.dots && nav( 'dot', true );

			//  Arrows support
			! reinit && o.arrows && nav('arrow');

			//  Patch for fluid-width sliders. Screw those guys.
			! reinit && o.fluid && $window.resize(function() {
				// Clear the timeout
				_.r && clearTimeout(_.r);

				// Re-init with column item as item
				if ( o.reinit && _.orig_items.column_item && $window.width() < _.o.use_item_width_until ) {
					// If the item doesn't match the column item
					if ( _.o.item !== _.orig_items.column_item ) {
						_.o.item = _.orig_items.column_item;

						// Re-Init
						_.init( _.el, o, true );
					}
				}
				// Otherwise make sure we use the original item
				else if ( o.reinit && _.orig_items.column_item && $window.width() > _.o.use_item_width_until ) {
					// If the item matches the column item
					if ( _.o.item === _.orig_items.column_item ) {
						_.o.item = _.orig_items.item;

						// Re-Init
						_.init( _.el, o, true );
					}
				}

				_.r = setTimeout(function() {
					var bw = ( el[0] ) ? el[0].getBoundingClientRect().width : 0;

					// Reset max slider height
					_.max[2] = 0;
					_.ul.find( ( $window.width() > _.o.use_item_width_until ) && _.o.column_item || _.o.item).each(function(e) {
						var $this = $(this),
							height = $this.outerHeight();
						if( height > _.max[2]) _.max[2] = height;
					});

					var styl = {height: ( o.max_slide_height ) ? _.max[2] : li.eq(_.i).outerHeight() },
						width = ( bw > 0 && el.outerWidth() > bw ) ? bw: el.outerWidth();

					ul.css(styl);
					//styl['width'] = Math.min(Math.round((width / el.parent().width()) * 100), 100) + '%';
					styl['width'] = '100%';

					if (o.dots) {
						styl.height += o.dots_padding;
					}

					el.css(styl);
					li.css({ width: width + 'px' });

					if ( _.orig_items.row_item && $window.width() < _.o.use_item_width_until ) {
						_.li.css( { width: width } );

						if ( $window.width() < _.o.use_item_width_until ) {

							// Adjust the row width based on number of columns
							row_items.each( function () {
								var $li = $( this ),
									$children = $li.children(); // TODO: Test o.column_item as the selector

								$li.css({ width: ( width * $children.length ) + 'px' });
							} );
						}
					}

					if ( ! o.max_slide_height ) {
						setTimeout( function() {
							el.add(_.ul).css({height: ( o.dots ) ? ( li.eq(_.i).outerHeight() + o.dots_padding ): li.eq(_.i).outerHeight() });
						}, 5 ); // 5ms after window resize event logic
					}

					// Reset timer reference
					_.r = undefined;
				}, o.resize_timeout);

			}).resize();

			// Window load
			$window.load( function() {
				// Trigger a resize event
				$window.resize();
			} );

			//  Move support
			if ($.event.special['move'] || $.Event('move')) {
				el.on('movestart', function(e) {
					if ((e.distX > e.distY && e.distX < -e.distY) || (e.distX < e.distY && e.distX > -e.distY)) {
						e.preventDefault();
					}else{
						el.data("left", _.ul.offset().left / el.width() * 100);
					}
				}).on('move', function(e) {
					var left = 100 * e.distX / el.width();
					var leftDelta = 100 * e.deltaX / el.width();
					_.ul[0].style.left = parseInt(_.ul[0].style.left.replace("%", ""))+leftDelta+"%";

					_.ul.data("left", left);
				}).on('moveend', function(e) {
					var left = _.ul.data("left");
					if (Math.abs(left) > 30){
						var i = left > 0 ? _.i-1 : _.i+1;
						if (i < 0 || i >= len) i = _.i;
						_.to(i);
					}else{
						_.to(_.i);
					}
				});
			};

			return _;
		};

		//  Move Unslider to a slide index
		_.to = function(index, callback) {
			if (_.t) {
				_.stop();
				_.play();
			}
			var o = _.o,
				el = _.el,
				ul = _.ul,
				li = _.li,
				current = _.i,
				target = li.eq(index);

			$.isFunction(o.starting) && !callback && o.starting(el, li.eq(current));

			//  To slide or not to slide
			if ((!target.length || index < 0) && o.loop === f) return;

			//  Check if it's out of bounds
			if (!target.length) index = 0;
			if (index < 0) index = li.length - 1;
			target = li.eq(index);

			var speed = callback ? 5 : o.speed | 0,
				easing = o.easing,
				obj = ( ! o.max_slide_height ) ? {height: target.outerHeight()} : {}; //{height: target.outerHeight()};

			if ( ! o.max_slide_height && o.dots ) {
				obj.height += o.dots_padding;
			}

			if (!ul.queue('fx').length) {
				//  Handle those pesky dots
				el.find('.dot').eq(index).addClass('active').siblings().removeClass('active');

				el.animate(obj, speed, easing) && ul.animate($.extend({left: '-' + index + '00%'}, obj), speed, easing, function(data) {
					_.i = index;

					$.isFunction(o.complete) && !callback && o.complete(el, target);
				});
			};
		};

		//  Autoplay functionality
		_.play = function() {
			_.t = setInterval(function() {
				_.to(_.i + 1);
			}, _.o.delay | 0);
		};

		//  Stop autoplay
		_.stop = function() {
			_.t = clearInterval(_.t);
			return _;
		};

		//  Move to previous/next slide
		_.next = function() {
			return _.stop().to(_.i + 1);
		};

		_.prev = function() {
			return _.stop().to(_.i - 1);
		};

		//  Create dots and arrows
		function nav(name, reinit) {
			var html = '';

			reinit = reinit || false;

			if (name == 'dot') {
				// Initialization
				if ( ! reinit ) {
					html = '<ol class="dots">';
					$.each(_.li, function(index) {
						html += '<li class="' + (index === _.i ? name + ' active' : name) + '"><span class="dot-inner"></span></li>';
					});
					html += '</ol>';
				}
				// Re-initialization
				else {
					$.each(_.li, function(index) {
						html += '<li class="' + (index === _.i ? name + ' active' : name) + '"><span class="dot-inner"></span></li>';
					});

					_.el.find( '.dots' ).html( html ).find('.' + name).click(function() {
						var me = $(this);
						me.hasClass('dot') ? _.stop().to(me.index()) : me.hasClass('prev') ? _.prev() : _.next();
					});
				}
			} else {
				html = '<div class="';
				html = html + name + 's">' + html + name + ' prev">' + _.o.prev + '</div>' + html + name + ' next">' + _.o.next + '</div></div>';
			}

			if ( ! reinit ) {
				_.el.addClass('has-' + name + 's').append(html).find('.' + name).click(function() {
					var me = $(this);
					me.hasClass('dot') ? _.stop().to(me.index()) : me.hasClass('prev') ? _.prev() : _.next();
				});
			}
		};
	};

	//  Create a jQuery plugin
	$.fn.unslider = function(o) {
		var len = this.length;

		//  Enable multiple-slider support
		return this.each(function(index) {
			//  Cache a copy of $(this), so it
			var me = $(this),
				key = 'unslider' + (len > 1 ? '-' + ++index : ''),
				instance = (new Unslider).init(me, o);

			//  Invoke an Unslider instance
			me.data(key, instance).data('key', key);
		});
	};

	Unslider.version = "1.0.0";
})(jQuery, false);