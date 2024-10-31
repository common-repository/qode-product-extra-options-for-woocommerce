(function ( $ ) {
	'use strict';

	window.qodeProductExtraOptionsForWooCommerce = {};

	qodeProductExtraOptionsForWooCommerce.body         		  	= $( 'body' );
	qodeProductExtraOptionsForWooCommerce.html         		  	= $( 'html' );
	qodeProductExtraOptionsForWooCommerce.windowWidth  			= $( window ).width();
	qodeProductExtraOptionsForWooCommerce.windowHeight 			= $( window ).height();
	qodeProductExtraOptionsForWooCommerce.scroll       		    = 0;
	qodeProductExtraOptionsForWooCommerce.firstVariationLoading = false;
	qodeProductExtraOptionsForWooCommerce.qpeofwDOM 			= {
		editProductCartLink : '.qpeofw-edit-product-cart',
		individualAddons    : '.qpeofw-individual-addons',
		cartPopup           : '.qpeofw-popup',
		popupOverlay		: '.qpeofw-overlay',
		popupClose		    : '.qpeofw-close',
		popupWrapper		: '.qpeofw-wrapper',
		popupContent		: '.qpeofw-content',
		popupFooter		    : '.qpeofw-footer',
		addToCartButton		: '.qpeofw-popup .single_add_to_cart_button',
		popupForm		    : '.qpeofw-popup form.cart',
		variationForm		: '.qpeofw-popup form.variations_form',
		addonsContainer		: '.qpeofw-container',
		hiddenItemKey		: '.qpeofw-cart-item-key',
		addonImageInput     : '#qpeofw_product_img',
		formCart			: 'form.cart',
		totalPriceTable		: '#qpeofw-total-price-table',
		wcProductGallery    : '.woocommerce-product-gallery'
	};

	$( document ).ready(
		function () {
			qodeProductExtraOptionsForWooCommerce.scroll = $( window ).scrollTop();
			qodeProductExtraOptionsForWooCommerce.qodefToggleOptions.init();
			qodeProductExtraOptionsForWooCommerce.multipliedByValuePrice.init();
			qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
			qodeProductExtraOptionsForWooCommerce.totalAddonsPriceInitOn.init();
			qodeProductExtraOptionsForWooCommerce.qodefWooCommerceMeasurementCompatibility.init();
			qodeProductExtraOptionsForWooCommerce.productQty.init();
			qodeProductExtraOptionsForWooCommerce.qodefCompositeCompatibility.init();
		}
	);

	$( window ).resize(
		function () {
			qodeProductExtraOptionsForWooCommerce.windowWidth  = $( window ).width();
			qodeProductExtraOptionsForWooCommerce.windowHeight = $( window ).height();
		}
	);

	$( window ).scroll(
		function () {
			qodeProductExtraOptionsForWooCommerce.scroll = $( window ).scrollTop();
		}
	);

	$( window ).on(
		'load',
		function () {
			qodeProductExtraOptionsForWooCommerce.qodefAddonFieldsSelect2.init();
		}
	);

	/**
	 * Init animation on appear
	 */
	var qodeProductExtraOptionsForWooCommerceAppear = {
		init: function () {
			this.holder = $( '.qodeProductExtraOptionsForWooCommerce--has-appear:not(.qodeProductExtraOptionsForWooCommerce--appeared)' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						var $holder = $( this );

						qodeProductExtraOptionsForWooCommerce.qodeProductExtraOptionsForWooCommerceIsInViewport.check(
							$holder,
							() =>
							{
								qodeProductExtraOptionsForWooCommerce.qodeProductExtraOptionsForWooCommerceWaitForImages.check(
									$holder,
									function () {
										$holder.addClass( 'qodeProductExtraOptionsForWooCommerce--appeared' );
									}
								);
							}
						);
					}
				);
			}
		},
	};

	qodeProductExtraOptionsForWooCommerce.qodeProductExtraOptionsForWooCommerceAppear = qodeProductExtraOptionsForWooCommerceAppear;

	var qodeProductExtraOptionsForWooCommerceIsInViewport = {
		check: function ( $element, callback, onlyOnce, callbackOnExit ) {
			if ( $element.length ) {
				// When item is 15% in the viewport.
				var offset = typeof $element.data( 'viewport-offset' ) !== 'undefined' ? $element.data( 'viewport-offset' ) : 0.15;

				var observer = new IntersectionObserver(
					function ( entries ) {
						// isIntersecting is true when element and viewport are overlapping.
						// isIntersecting is false when element and viewport don't overlap.
						if ( entries[0].isIntersecting === true ) {
							callback.call( $element );

							// Stop watching the element when it's initialize.
							if ( onlyOnce !== false ) {
								observer.disconnect();
							}
						} else if ( callbackOnExit && onlyOnce === false ) {
							callbackOnExit.call( $element );
						}
					},
					{ threshold: [offset] }
				);

				observer.observe( $element[0] );
			}
		},
	};

	qodeProductExtraOptionsForWooCommerce.qodeProductExtraOptionsForWooCommerceIsInViewport = qodeProductExtraOptionsForWooCommerceIsInViewport;

	/**
	 * Check element images to loaded
	 */
	var qodeProductExtraOptionsForWooCommerceWaitForImages = {
		check: function ( $element, callback ) {
			if ( $element.length ) {
				var images = $element.find( 'img' );

				if ( images.length ) {
					var counter 	 = 0;
					var imagesLength = images.length;

					for ( var index = 0; index < imagesLength; index++ ) {
						var img = images[index];

						if ( img.complete ) {
							counter++;

							if ( counter === images.length ) {
								callback.call( $element );
							}
						} else {
							var image = new Image();

							image.addEventListener(
								'load',
								function () {
									counter++;
									if ( counter === images.length ) {
										callback.call( $element );
										return false;
									}
								},
								false
							);
							image.src = img.src;
						}
					}
				} else {
					callback.call( $element );
				}
			}
		},
	};

	qodeProductExtraOptionsForWooCommerce.qodeProductExtraOptionsForWooCommerceWaitForImages = qodeProductExtraOptionsForWooCommerceWaitForImages;

	var qodeProductExtraOptionsForWooCommerceScroll = {
		disable: function () {
			if ( window.addEventListener ) {
				window.addEventListener(
					'wheel',
					qodeProductExtraOptionsForWooCommerceScroll.preventDefaultValue,
					{ passive: false }
				);
			}

			document.onkeydown = qodeProductExtraOptionsForWooCommerceScroll.keyDown;
		},
		enable: function () {
			if ( window.removeEventListener ) {
				window.removeEventListener(
					'wheel',
					qodeProductExtraOptionsForWooCommerceScroll.preventDefaultValue,
					{ passive: false }
				);
			}
			window.onmousewheel = document.onmousewheel = document.onkeydown = null;
		},
		preventDefaultValue: function ( e ) {
			e = e || window.event;
			if ( e.preventDefault ) {
				e.preventDefault();
			}
			e.returnValue = false;
		},
		keyDown: function ( e ) {
			var keys = [37, 38, 39, 40];
			for ( var i = keys.length; i--; ) {
				if ( e.keyCode === keys[i] ) {
					qodeProductExtraOptionsForWooCommerceScroll.preventDefaultValue( e );
					return;
				}
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodeProductExtraOptionsForWooCommerceScroll = qodeProductExtraOptionsForWooCommerceScroll;

	// Check required fields before adding to cart( Required select and min/max values ).
	$( document ).on(
		'click',
		'form.cart button',
		function () {
			return qodeProductExtraOptionsForWooCommerce.checkAddonsRequirements.init();
		}
	).on(
		// min max rules.
		'change',
		'.qpeofw-addon-type-checkbox, .qpeofw-addon-type-color, .qpeofw-addon-type-label, .qpeofw-addon-type-product',
		function () {
			qodeProductExtraOptionsForWooCommerce.qodefCheckMinMax.init( $( this ) );
		}
	);
	// TODO request a quote plugin.

	var qodefCheckRequiredMinMax = {
		init: function ( action = '' ) {
			let canProceed = true;

			// Check force user selection for add-on type Select.
			if ( ! qodefCheckRequiredMinMax.checkRequiredSelect() ) {
				canProceed = false;
			}
			if ( ! qodefCheckRequiredMinMax.checkTextInputLimit() ) {
				canProceed = false;
			}

			// Required feature.
			if ( action !== 'hide' ) {
				let required = qodeProductExtraOptionsForWooCommerce.qodefCheckRequiredFields.init( 'highlight' );

				if ( ! required ) {
					canProceed = false;
				}
			}

			var requiredOptions = 0;
			var checkMinMax     = '';
			var apply_submit 	= action !== 'hide';

			$( 'form.cart .qpeofw-addon:not(.hidden)' ).each(
				function () {
					checkMinMax = qodeProductExtraOptionsForWooCommerce.qodefCheckMinMax.init( $( this ), apply_submit );
					if ( checkMinMax > 0 ) {
						requiredOptions += checkMinMax;
					}
				}
			);
			if ( action !== 'hide' ) {
				if ( requiredOptions > 0 ) {
					canProceed = false;
				}
			}

			return canProceed;
		},
		checkRequiredSelect: function () {
			var value = true;

			$( '.qpeofw-addon.qpeofw-addon-type-select select' ).each(
				function () {
					var currentSelect = $( this );

					if ( currentSelect.is( ':required' ) ) {
						var addon        = currentSelect.parents( '.qpeofw-addon' ),
							errorMessage = addon.find( '.qpeofw-min-error-message' ),
							addonTitle   = addon.find( '.qpeofw-addon-title' ),
							selectValue  = currentSelect.val();

						errorMessage.text( '' );
						addonTitle.removeClass( 'qpeofw-error' );
						addon.removeClass( 'qpeofw-required-min' );

						if ( 'default' === selectValue && ! addon.hasClass( 'hidden' ) ) {
							value = false;
							if ( ! value ) {
								var error_el           = addon.find( '.qpeofw-min-error' );
								var toggle_addon       = currentSelect.parents( 'div.qpeofw-addon.qpeofw--toggle' );
								var toggle_addon_title = toggle_addon.find( '.qpeofw-addon-title.qpeofw--toggle-closed' );
								addon.addClass( 'qpeofw-required-min' );

								if ( toggle_addon_title ) {
									toggle_addon_title.click();
								}
								addonTitle.addClass( 'qpeofw-error' );
								errorMessage.text( qpeofwFrontend.i18n.selectAnOption.replace( '%d', 1 ) );
								error_el.show();
							}
						}
					}
				}
			);

			return value;
		},
		checkTextInputLimit: function () {
			var valid = true;

			$( 'form.cart .qpeofw-addon.qpeofw-addon-type-text:not(.hidden) input' ).each(
				( index, input ) =>
				{
					var currentInput = $( input ),
						currentValue = currentInput.val(),
						minLength    = currentInput.attr( 'minlength' ),
						maxLength    = currentInput.attr( 'maxlength' );

					// first check if user have entered some text since it field might not be set as required.
					if ( currentValue.length !== 0 ) {
						if ( (minLength !== '' && currentValue.length < minLength) || (maxLength !== '' && currentValue.length > maxLength) ) {
							currentInput.addClass( 'qpeofw-length-error' );
							currentInput.parents( '.qpeofw-label' ).siblings( '.qpeofw-length-error-message' ).show();
							valid = false;
						} else {
							currentInput.parents( '.qpeofw-label' ).siblings( '.qpeofw-length-error-message' ).hide();
							currentInput.removeClass( 'qpeofw-length-error' );
						}
					}
				}
			);

			return valid;
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefCheckRequiredMinMax = qodefCheckRequiredMinMax;

	var checkAddonsRequirements = {
		init: function () {
			let numbersCheck = qodeProductExtraOptionsForWooCommerce.qodefcheckNumbersTotalValues.init(),
				minMaxResult = qodeProductExtraOptionsForWooCommerce.qodefCheckRequiredMinMax.init();

			// if it's not true, do not allow to add to cart.
			if ( ! numbersCheck ) {
				return false;
			}

			if ( ! minMaxResult && ! qpeofwFrontend.disable_scroll_on_required_mix_max ) {
				$( 'html, body' ).animate( { scrollTop: $( '#qpeofw-container' ).offset().top - 20 }, 500 );
			}

			return minMaxResult;
		}
	};

	qodeProductExtraOptionsForWooCommerce.checkAddonsRequirements = checkAddonsRequirements;

	/**
	 * Check min and max values for the sum of add-ons type Number.
	 */
	var qodefcheckNumbersTotalValues = {
		init: function () {
			var numberAddons = $( '#qpeofw-container .qpeofw-addon-type-number:not(.hidden).numbers-check' ),
				isError 	 = false;

			numberAddons.each(
				function ( index ) {
					var numberAddon    = $( this ),
						numberMin 	   = numberAddon.data( 'numbers-min' ),
						numberMax 	   = numberAddon.data( 'numbers-max' ),
						totalCount 	   = 0,
						errorCheck	   = false,
						errorMessage   = '',
						optionsElement = numberAddon.find( '.options' );

					// Reset.
					if ( optionsElement.hasClass( 'qpeofw-error-message' ) ) {
						optionsElement.removeClass( 'qpeofw-error-message' );
					}
					numberAddon.find( '.qpeofw-numbers-error-message' ).remove();

					numberAddon.find( 'input[type="number"]' ).each(
						function () {
							var number = $( this ).val();
							if ( 'undefined' === number || '' === number ) {
								// continue.
								return true;
							}
							totalCount += parseFloat( number );
						}
					);

					if ( 'undefined' !== typeof numberMin && totalCount < numberMin ) {
						errorCheck 	 = true;
						errorMessage = qpeofwFrontend.messages.minErrorMessage + ' ' + numberMin;
					}

					if ( 'undefined' !== typeof numberMax && totalCount > numberMax ) {
						errorCheck 	 = true;
						errorMessage = qpeofwFrontend.messages.maxErrorMessage + ' ' + numberMax;
					}

					if ( errorCheck ) {
						optionsElement.addClass( 'qpeofw-error-message' );
						numberAddon.append( $( '<small class="qpeofw-numbers-error-message">' + errorMessage + '</small>' ) );
						isError = true;
						$( 'html, body' ).animate( { scrollTop: numberAddon.offset().top - 50 }, 500 );
					}

				}
			);

			$( document ).trigger( 'qodef_check_number_total_values' );

			if ( isError ) {
				return false;
			}

			return true;
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefcheckNumbersTotalValues = qodefcheckNumbersTotalValues;

	// Check required options.
	var qodefCheckRequiredFields = {
		init: function ( action ) {
			var isRequired    = false;
			var hideButton    = false;
			var buttonClasses = qpeofwFrontend.dom.single_add_to_cart_button;

			$( 'form.cart .qpeofw-addon:not(.hidden):visible input, form.cart .qpeofw-addon:not(.hidden):visible select, form.cart .qpeofw-addon:not(.hidden):visible textarea' ).each(
				function () {
					let element            = $( this );
					let parent             = element.closest( '.qpeofw-option' );
					let addon              = element.closest( '.qpeofw-addon' );
					let toggle_addon       = element.closest( 'div.qpeofw-addon.qpeofw--toggle' );
					let toggle_addon_title = toggle_addon.find( 'h3.qpeofw-addon-title.qpeofw--toggle-closed' );
					let addonTitle         = addon.find( '.qpeofw-addon-title' );

					if ( 'file' === element.attr( 'type' ) || element.hasClass( 'qpeofw-product-qty' ) ) {
						return;
					}

					if ( element.attr( 'required' ) && ( 'checkbox' === element.attr( 'type' ) || 'radio' === element.attr( 'type' ) ) && ! element.closest( '.qpeofw-option' ).hasClass( 'qpeofw-selected' ) || element.attr( 'required' ) && ( element.val() == '' || element.val() == 'Required' ) ) {

						if ( action === 'highlight' ) {
							// Add required message.
							qodefCheckRequiredFields.showRequiredMessage( element );
							addonTitle.addClass( 'qpeofw-error' );

							// Open toggle.
							if ( toggle_addon_title ) {
								toggle_addon_title.click();
							}
						}

						hideButton = true;
						isRequired = true;
					} else {
						// Restart default required status.
						// TODO: check if this is working with sold individually option.
						qodefCheckRequiredFields.restartRequiredElement( element );
					}
				}
			);
			if ( action == 'hide' ) {
				if ( hideButton ) {
					$( buttonClasses ).hide();
				} else {
					$( buttonClasses ).fadeIn();
				}
			}

			return ! isRequired;
		},
		showRequiredMessage: function ( element ) {
			let option = element.closest( '.qpeofw-option' );

			if ( ! option.find( '.qpeofw-required-error' ).length ) {
				option.append( '<div class="qpeofw-required-error"><small class="qpeofw-required-message">' + qpeofwFrontend.messages.requiredMessage + '</small></div>' );

				option.addClass( 'qpeofw-required-color' );
			}
		},
		restartRequiredElement: function ( element ) {
			let option = element.closest( '.qpeofw-option' );

			element.closest( '.qpeofw-option' ).find( '.qpeofw-required-error' ).remove();
			option.removeClass( 'qpeofw-required-color' );
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefCheckRequiredFields = qodefCheckRequiredFields;

	var qodefCheckMinMax = {
		init: function ( addon, submit = false ) {

			var addonType       = addon.data( 'addon-type' );
			var minValue        = addon.data( 'min' );
			var maxValue        = addon.data( 'max' );
			var exaValue        = addon.data( 'exa' );
			var errorMessage    = addon.find( '.qpeofw-min-error-message' ),
				addonTitle      = addon.find( '.qpeofw-addon-title' ),
				numberOfChecked = 0;

			var toggle_addon_title = addon.find( '.qpeofw-addon-title.qpeofw--toggle-closed' );
			addonTitle.removeClass( 'qpeofw-error' );

			if ( 'select' === addonType || ( '' === minValue && '' === exaValue && '' === maxValue ) ) {
				return;
			}

			// Number / Text / TextArea.
			if ( 'number' === addonType || 'text' === addonType || 'textarea' === addonType ) {
				$( addon ).find( '.qpeofw-option-value' ).each(
					function ( index ) {
						var numberValue = $( this ).val();
						if ( numberValue.length ) {
							// Summing number of filled.
							numberOfChecked++;
						}
					}
				);

				if ( maxValue && numberOfChecked > maxValue ) {
					var optionsElement = $( addon ).find( '.options-container' );
					if ( ! optionsElement.find( '.qpeofw-max-selected-error' ).length ) {
						optionsElement.append( '<p class="qpeofw-max-selected-error">' + qpeofwFrontend.i18n.maxOptionsSelectedMessage + '</p>' );
						addonTitle.addClass( 'qpeofw-error' );
					}
					return 1;
				}

			} else {
				// Checkbox / Radio - Sum of number of checked.
				numberOfChecked = addon.find( 'input:checkbox:checked, input:radio:checked' ).length;
			}

			// Exactly Values.
			if ( exaValue > 0 ) {

				var optionsToSelect = 0;

				if ( exaValue == numberOfChecked ) {
					addon.removeClass( 'qpeofw-required-min' ).find( '.qpeofw-min-error' ).hide();
					addon.find( 'input:checkbox' ).not( ':checked' );
				} else {
					// If click on add to cart button.
					if ( submit ) {
						optionsToSelect = exaValue - numberOfChecked;
						addon.addClass( 'qpeofw-required-min' );
						addon.find( '.qpeofw-min-error' ).show();
						addonTitle.addClass( 'qpeofw-error' );

						var errorMessageText = qpeofwFrontend.i18n.selectOptions.replace( '%d', exaValue );
						if ( 1 === exaValue ) {
							errorMessageText = qpeofwFrontend.i18n.selectAnOption;
						}

						errorMessage.text( errorMessageText );

						if ( toggle_addon_title ) {
							toggle_addon_title.click();
						}
					}
					addon.find( '.qpeofw-option:not(.out-of-stock) input:checkbox' ).not( ':checked' ).attr( 'disabled', false );
				}

				return optionsToSelect;

			} else {

				// At least values.
				if ( minValue > 0 ) {
					var optionsToSelect = minValue - numberOfChecked;
					if ( minValue <= numberOfChecked ) {
						addon.removeClass( 'qpeofw-required-min' ).find( '.qpeofw-min-error' ).hide();
					} else {
						// If click on add to cart button.
						if ( submit ) {
							var minMessage = qpeofwFrontend.i18n.selectAnOption;
							if ( minValue > 1 ) {
								minMessage = qpeofwFrontend.i18n.selectAtLeast.replace( '%d', minValue );
							}

							addon.addClass( 'qpeofw-required-min' );
							addon.find( '.qpeofw-min-error' ).show();
							addonTitle.addClass( 'qpeofw-error' );
							errorMessage.text( minMessage );

							if ( toggle_addon_title ) {
								toggle_addon_title.click();
							}
						}
						return optionsToSelect;
					}
				}

				// Max values.
				if ( ! maxValue || maxValue >= numberOfChecked ) {
					addon.removeClass( 'qpeofw-required-min' ).find( '.max-selected-error' ).hide();
				} else {
					// If click on add to cart button.
					if ( submit ) {
						addon.addClass( 'qpeofw-required-min' );
						var optionsElement = $( addon ).find( '.options-container' );
						if ( ! optionsElement.find( '.max-selected-error' ).length ) {
							optionsElement.append( '<small class="max-selected-error">' + qpeofwFrontend.i18n.maxOptionsSelectedMessage + '</small>' );
							addonTitle.addClass( 'qpeofw-error' );
						}
					}
					return 1;
				}

			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefCheckMinMax = qodefCheckMinMax;

	/**
	 * Toggle options functionality.
	 */
	var qodefToggleOptions = {
		init: function () {

			var $holder = $( '.qpeofw-addon.qpeofw--toggle .qpeofw-addon-header' );

			if ( $holder.length ) {

				$holder.on(
					'click',
					function ( e ) {
						e.preventDefault();

						var $addonTitle   = $( this ).find( '.qpeofw-addon-title' ),
							$addonElement = $addonTitle.closest( '.qpeofw-addon' );

						if ( $addonElement.hasClass( 'qpeofw--toggle-open' ) ) {
							$addonElement.removeClass( 'qpeofw--toggle-open' ).addClass( 'qpeofw--toggle-closed' );
						} else {
							$addonElement.removeClass( 'qpeofw--toggle-closed' ).addClass( 'qpeofw--toggle-open' );
						}

						if ( $addonTitle.hasClass( 'qpeofw--toggle-open' ) ) {
							$addonTitle.removeClass( 'qpeofw--toggle-open' ).addClass( 'qpeofw--toggle-closed' );
						} else {
							$addonTitle.removeClass( 'qpeofw--toggle-closed' ).addClass( 'qpeofw--toggle-open' );
						}

						$addonElement.find( '.qpeofw-options-container' ).toggle( 300 );

						$( document ).trigger( 'qodef_initialize_toggle_elements' );
					}
				);
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefToggleOptions = qodefToggleOptions;

	// WooCommerce Measurement Price Calculator (compatibility).
	var qodefWooCommerceMeasurementCompatibility = {
		init: function () {
			var $holder = $( 'form.cart' );

			if ( $holder.length ) {

				$holder.on(
					'change',
					'#price_calculator.wc-measurement-price-calculator-price-table',
					function () {
						var price = $( '#price_calculator.wc-measurement-price-calculator-price-table .product_price .amount' ).text();
						price     = qodeProductExtraOptionsForWooCommerce.wcPriceToFloat.init( price );

						if ( ! isNaN( price ) ) {
							let container = $( '#qpeofw-container' );

							container.attr(
								'data-product-price',
								price
							);
							$( document ).trigger(
								'qpeofw-reload-addons',
								[price]
							);
						}
					}
				);
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefWooCommerceMeasurementCompatibility = qodefWooCommerceMeasurementCompatibility;

	// TODO: Composite compatibility.
	var qodefCompositeCompatibility = {
		init: function () {
			$( document ).on(
				'qpeofw_wcp_price_updated',
				function ( event, total ) {
					let global_qty      = parseFloat( $( 'form.cart.ywcp > div.quantity input.qty' ).val() );
					let price           = global_qty ? total / global_qty : total;
					let addonsContainer = $( '#qpeofw-container' );

					addonsContainer.attr(
						'data-product-price',
						price
					);
					qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
				}
			);
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefCompositeCompatibility = qodefCompositeCompatibility;

	var calculateAddonsPrice = {
		init: function () {
			var firstFreeOptions  = 0,
				currentAddonID    = 0,
				totalPrice        = 0,
				totalPriceDefault = 0,
				// Quantity of the Add to cart form.
				quantity = $( qpeofwFrontend.productQuantitySelector ).val();

			if ( ! quantity > 0 ) {
				quantity = 1;
			}

			$( 'form.cart .qpeofw-addon:not(.hidden):visible input, form.cart .qpeofw-addon:not(.hidden):visible select, form.cart .qpeofw-addon:not(.hidden):visible textarea' ).each(
				function () {

					let option              = $( this ),
						defaultProductPrice = parseFloat( $( '#qpeofw-container' ).attr( 'data-product-price' ) ),
						optionID            = option.data( 'addon-id' );

					if ( optionID ) {
						let optionType  	  = option.attr( 'type' ),
							priceMethod 	  = option.data( 'price-method' ),
							price       	  = 0,
							defaultTotalPrice = 0,
							priceType   	  = '',
							addon       	  = option.parents( '.qpeofw-addon' ),
							addonType   	  = addon.data( 'addon-type' ),
							addonQty    	  = 1;

						if ( 'number' === optionType && 0 == option.val() ) {
							return totalPrice;
						}

						if ( option.is( 'textarea' ) ) {
							optionType = 'textarea';
						}

						if ( option.is( ':checked' ) || option.find( ':selected' ).is( 'option' ) || (option.is( 'input:not([type=checkbox])' ) && option.is( 'input:not([type=radio])' ) && option.val() != '') || (option.is( 'textarea' ) && option.val() != '')
						) {

							if ( option.is( 'select' ) ) {
								option = option.find( ':selected' );
							}

							if ( 'number' === optionType ) {
								calculateAddonsPrice.checkMultipliedPrice( option );
							}

							if ( 'text' === optionType || 'textarea' === optionType ) {
								calculateAddonsPrice.checkMultipliedLength( option );
							}

							if ( currentAddonID != optionID ) {
								currentAddonID   = option.data( 'addon-id' );
								firstFreeOptions = option.data( 'first-free-options' );
							}

							if ( option.data( 'first-free-enabled' ) == 'yes' && firstFreeOptions > 0 ) {
								firstFreeOptions--;
							} else {
								if ( typeof option.data( 'price-type' ) != 'undefined' && '' !== option.data( 'price-type' ) ) {
									// Percentage or fixed.
									priceType = option.data( 'price-type' );
								}

								let dataPriceSale 	 = option.data( 'price-sale' ),
									dataPrice     	 = option.data( 'price' ),
									defaultSalePrice = option.data( 'default-sale-price' ),
									defaultPrice     = option.data( 'default-price' );

								if ( typeof dataPriceSale != 'undefined' && '' !== dataPriceSale && dataPriceSale >= 0 && 'multiplied' !== priceType ) {
									price 			  = parseFloat( dataPriceSale );
									defaultTotalPrice = parseFloat( defaultSalePrice );
								} else if ( typeof dataPrice != 'undefined' && '' !== dataPrice ) {
									price 			  = parseFloat( dataPrice );
									defaultTotalPrice = parseFloat( defaultPrice );
								}

								if ( 'percentage' === priceType && 'discount' !== priceMethod ) {
									price 			  = (price * defaultProductPrice) / 100;
									defaultTotalPrice = ( defaultTotalPrice * defaultProductPrice ) / 100;
								}

								if ( 'product' === addonType ) {
									if ( ! option.hasClass( '.qpeofw-option' ) ) {
										option   = option.parents( '.qpeofw-option' );
										addonQty = option.find( '.qpeofw-product-qty' );
										if ( addonQty ) {
											addonQty = addonQty.val();
											if ( addonQty > 1 ) {
												price 			  = price * addonQty;
												defaultTotalPrice = defaultTotalPrice * addonQty;
											}
										}
									}
								}

								// Multiply price by quantity. Not multiplied for Sell individually add-ons ( it will be 1 on cart ).
								if ( quantity > 1 && ! addon.hasClass( 'qpeofw_sell_individually' ) ) {
									price 			  = price * quantity;
									defaultTotalPrice = defaultTotalPrice * quantity;
								}

								totalPrice 		  += price;
								totalPriceDefault += defaultTotalPrice;
							}
						}
					}
				}
			);

			return { 'totalPrice' : totalPrice, 'totalPriceDefault' : totalPriceDefault };
		},
		checkMultipliedPrice: function ( addon ) {
			let price        = addon.data( 'price' );
			let sale_price   = addon.data( 'price-sale' );
			let defaultPrice = addon.data( 'default-price' );
			let priceType    = addon.data( 'price-type' );
			let priceMethod  = addon.data( 'price-method' );
			let default_attr = 'price';
			let final_price  = 0;
			let addon_value  = addon.val();

			if ( ! defaultPrice > 0 ) {
				if ( sale_price > 0 && ('number' !== addon.attr( 'type' ) && 'multiplied' === priceType) ) {
					price        = sale_price;
					default_attr = 'price-sale';
				}
				defaultPrice = price;
				addon.data(
					'default-price',
					defaultPrice
				);
			}
			if ( priceMethod == 'value_x_product' ) {
				var productPrice = parseFloat( $( '#qpeofw-container' ).attr( 'data-product-price' ) );
				final_price      = addon_value * productPrice;
			} else if ( priceType == 'multiplied' ) {
				final_price = addon_value * defaultPrice;
			}

			if ( final_price > 0 || priceMethod == 'decrease' ) {
				addon.data(
					default_attr,
					final_price
				);
			}
		},
		checkMultipliedLength: function ( addon ) {
			let price        = addon.data( 'price' );
			let defaultPrice = addon.data( 'default-price' );
			let priceType    = addon.data( 'price-type' );

			if ( ! defaultPrice > 0 ) {
				defaultPrice = price;
				addon.data(
					'default-price',
					defaultPrice
				);
			}
			if ( 'characters' === priceType ) {
				let remove_spaces = addon.data( 'remove-spaces' );
				let addonLength   = addon.val().length;
				if ( remove_spaces ) {
					addonLength = addon.val().replace(
						/\s+/g,
						''
					).length;
				}
				addon.data(
					'price',
					addonLength * defaultPrice
				);
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.calculateAddonsPrice = calculateAddonsPrice;

	var calculateTotalAddonsPrice = {
		init: function ( replacePrice = true ) {
			// Check logical conditions before calculate prices.
			qodeProductExtraOptionsForWooCommerce.conditionalLogicCheck.init();

			if ( 'yes' === qpeofwFrontend.hide_button_required ) {
				var buttonClasses = qpeofwFrontend.dom.single_add_to_cart_button;

				$( buttonClasses ).hide();

				var requiredFields = qodeProductExtraOptionsForWooCommerce.qodefCheckRequiredFields.init( 'hide' );
				var requiredMinMax = qodeProductExtraOptionsForWooCommerce.qodefCheckRequiredMinMax.init( 'hide' );

				if ( requiredFields && requiredMinMax ) {
					$( buttonClasses ).fadeIn();
				}
			}

			$( '#qpeofw-total-price-table' ).css(
				'opacity',
				'0.5'
			);
			var totalPrice          = 0;
			var totalDefaultPrice   = 0;
			var defaultProductPrice = parseFloat( $( '#qpeofw-container' ).attr( 'data-product-price' ) );
			var totalPriceBoxOption = qpeofwFrontend.total_price_box_option;

			// TODO integrate when we finish gift cards plugin.
			let selectedGifCardAmountButton = $( 'button.qode-gift-cards-amount-buttons.selected_button' );

			if ( selectedGifCardAmountButton.length > 0 ) {
				defaultProductPrice = selectedGifCardAmountButton.data( 'price' );
			}

			var pricesCalculated = qodeProductExtraOptionsForWooCommerce.calculateAddonsPrice.init();

			totalPrice 		  = pricesCalculated.totalPrice;
			totalDefaultPrice = pricesCalculated.totalPriceDefault;

			// Plugin option "Total price box".
			if ( 'hide_options' === totalPriceBoxOption ) {
				if ( 0 !== totalPrice ) {
					$( '#qpeofw-total-price-table .hide_options tr.qpeofw-total-options' ).fadeIn();
				} else {
					$( '#qpeofw-total-price-table .hide_options tr.qpeofw-total-options' ).hide();
				}
			}

			qodeProductExtraOptionsForWooCommerce.qodefSetTotalBoxPrices.init(
				defaultProductPrice,
				totalPrice,
				replacePrice,
				totalDefaultPrice
			);
		}
	};

	qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice = calculateTotalAddonsPrice;

	var productQty = {
		init: function () {
			var $holder = $( '.qpeofw-product-qty' );

			$holder.on(
				'keyup',
				function () {
					var productID  = $( this ).data( 'product-id' );
					var productQTY = $( this ).val();
					var productURL = '?add-to-cart=' + productID + '&quantity=' + productQTY;
					$( this ).parent().find( 'a' ).attr(
						'href',
						productURL
					);
				}
			);
		}
	};

	qodeProductExtraOptionsForWooCommerce.productQty = productQty;

	var productQuantityChange = {
		init: function () {
			let inputNumber     = $( this ),
				inputVal        = inputNumber.val(),
				productId       = inputNumber.closest( '.qpeofw-option' ).data( 'product-id' ),
				addToCartLink   = inputNumber.closest( '.qpeofw-option-add-to-cart' ).find( '.add_to_cart_button' ),
				productQuantity = 1,
				hrefCreated     = '';

			if ( addToCartLink.length && productId ) {
				if ( inputVal > 1 ) {
					productQuantity = inputVal;
				}

				hrefCreated = '?add-to-cart=' + productId + '&quantity=' + productQuantity;

				addToCartLink.attr(
					'href',
					hrefCreated
				);
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.productQuantityChange = productQuantityChange;

	var wcPriceToFloat = {
		init: function ( wc_price ) {
			let price = wc_price.replace(
				/(?![\.\,])\D/g,
				''
			)
			.replace(
				qpeofwFrontend.total_thousand_sep,
				''
			)
			.replace(
				qpeofwFrontend.decimal_sep,
				'.'
			);

			return parseFloat( price );
		}
	};

	qodeProductExtraOptionsForWooCommerce.wcPriceToFloat = wcPriceToFloat;

	var getDefaultProductPrice = {
		init: function () {
			if ( qpeofwFrontend.enableGetDefaultVariationPrice ) {
				let product_id = $( '.variations_form.cart' ).data( 'product_id' );
				let data       = {
					'action': 'get_default_variation_price',
					'product_id': parseInt( product_id ),
					'security': qpeofwFrontend.addons_nonce,
				};
				$.ajax(
					{
						url: qpeofwFrontend.ajaxurl,
						type: 'post',
						data: data,
						success: function ( response ) {
							if ( response ) {
								let defaultProductPrice = response['price_html'];
								let container           = $( '#qpeofw-container' );
								container.attr(
									'data-product-price',
									response['current_price']
								);
								container.attr(
									'data-product-id',
									product_id
								);

								if ( 'yes' === qpeofwFrontend.replace_product_price && container.find( '.qpeofw-block' ).length ) {
									$( qpeofwFrontend.replace_product_price_class ).html( defaultProductPrice );
								}

							}
						},
						complete: function () {
						}
					}
				);
			}
		}
	}

	qodeProductExtraOptionsForWooCommerce.getDefaultProductPrice = getDefaultProductPrice;

	// multiplied by value price.
	var multipliedByValuePrice = {
		init: function () {
			var $holder = $( '.qpeofw-addon-type-number input' );

			if ( $holder.length ) {

				$holder.on(
					'change keyup',
					function ( e ) {
						var inputElementValue = $( this ).val(),
							optionWrapper     = $( this ).closest( '.qpeofw-option' ),
							resetImage        = false;

						if ( '' == inputElementValue ) {
							resetImage = true;
						}
						qodeProductExtraOptionsForWooCommerce.replaceImage.init(
							optionWrapper,
							resetImage
						);
						qodeProductExtraOptionsForWooCommerce.calculateAddonsPrice.checkMultipliedPrice( $( this ) );
					}
				);
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.multipliedByValuePrice = multipliedByValuePrice;

	var conditionalLogicCheck = {
		init: function ( lastFinalConditions = {} ) {
			var finalConditions = {};

			$( 'form.cart .qpeofw-addon.conditional_logic' ).each(
				function () {

					var AddonConditionSection = false,
						AddonVariationSection = false;

					// show / hide.
					var logicDisplay = $( this ).data( 'conditional_logic_display' );

					// Applied to conditions - // all / any.
					var logicDisplayIf = $( this ).data( 'conditional_logic_display_if' );

					var ruleAddon     = String( $( this ).data( 'conditional_rule_addon' ) ),
						ruleAddonIs   = String( $( this ).data( 'conditional_rule_addon_is' ) ),
						ruleVariation = String( $( this ).data( 'conditional_rule_variations' ) );

					// addon number.
					ruleAddon = (typeof ruleAddon !== 'undefined' && ruleAddon !== '0' && ruleAddon !== '') ? ruleAddon.split( '|' ) : false;
					// selected / not-selected / empty / not-empty.
					ruleAddonIs = (typeof ruleAddonIs !== 'undefined' && ruleAddonIs !== '') ? ruleAddonIs.split( '|' ) : false;
					// variations number.
					ruleVariation = (typeof ruleVariation !== 'undefined' && ruleVariation !== '') ? ruleVariation.split( '|' ) : false;

					// Show addon if no variations conditions or addons conditions.
					if ( ! ruleVariation && ( ! ruleAddon || ! ruleAddonIs) ) {

						AddonConditionSection = true;
						AddonVariationSection = true;
						logicDisplay          = 'show';

					} else {

						// ConditionLogic.
						if ( ruleAddon && ruleAddonIs ) {

							switch (logicDisplayIf) {

								case 'all':
									AddonConditionSection = conditionalLogicCheck.conditionalLogicAllRules(
										ruleAddon,
										ruleAddonIs
									);
									break;

								case 'any':
									AddonConditionSection = conditionalLogicCheck.conditionalLogicAnyRules(
										ruleAddon,
										ruleAddonIs
									);
									break;

							}

						} else {
							AddonConditionSection = true;
						}

						// Prevent check variations if addons condition fails.
						if ( AddonConditionSection && ruleVariation ) {
							var variationProduct = $( '.variation_id' ).val();
							if ( -1 !== $.inArray(
								String( variationProduct ),
								ruleVariation
							) ) {
								AddonVariationSection = true;
							}

						} else {
							AddonVariationSection = true;
						}

					}

					switch (logicDisplay) {

						case 'show' :

							// Both conditions true --> apply logic Display.
							if ( AddonVariationSection && AddonConditionSection ) {
								finalConditions[$( this ).attr( 'id' )] = 'not-hidden';
							} else {
								finalConditions[$( this ).attr( 'id' )] = 'hidden';
							}
							break;

						case 'hide' :
							// Both conditions true --> apply logic Display.
							if ( AddonVariationSection && AddonConditionSection ) {
								finalConditions[$( this ).attr( 'id' )] = 'hidden';
							} else {
								finalConditions[$( this ).attr( 'id' )] = 'not-hidden';
							}
					}
				}
			);

			$.each(
				finalConditions,
				function ( id, mode ) {
					let element = $( '#' + id );

					if ( 'not-hidden' === mode ) {

						// Todo: We avoid out of stock to change disabled value.
						if ( qpeofwFrontend.conditionalDisplayEffect === 'slide' ) {
							element.slideDown().removeClass( 'hidden' ).find( '.qpeofw-option:not(.out-of-stock) .qpeofw-option-value' ).attr(
								'disabled',
								false
							);
						} else {
							element.fadeIn().removeClass( 'hidden' ).find( '.qpeofw-option:not(.out-of-stock) .qpeofw-option-value' ).attr(
								'disabled',
								false
							);
						}
						let selectedOption = element.find( '.qpeofw-option.qpeofw-selected' );
						qodeProductExtraOptionsForWooCommerce.replaceImage.init( selectedOption );

						// Re-enable select add-ons if it was hidden.
						if ( element.hasClass( 'qpeofw-addon-type-select' ) ) {
							element.find( '.qpeofw-option-value' ).attr(
								'disabled',
								false
							);
						}

						// Check the min_max after disable value.
						qodeProductExtraOptionsForWooCommerce.qodefCheckMinMax.init( element );
					} else if ( qpeofwFrontend.conditionalDisplayEffect === 'slide' ) {
						element.slideUp().addClass( 'hidden' ).find( '.qpeofw-option-value' ).attr(
							'disabled',
							true
						);
					} else {
						element.hide().addClass( 'hidden' ).find( '.qpeofw-option-value' ).attr(
							'disabled',
							true
						);
					}

				}
			);

			if ( JSON.stringify( finalConditions ) !== JSON.stringify( lastFinalConditions ) ) {
				qodeProductExtraOptionsForWooCommerce.conditionalLogicCheck.init( finalConditions );
			}
		},
		conditionalLogicAllRules: function ( ruleAddon, ruleAddonIs ) {
			var conditional 	= true;
			var ruleAddonLength = ruleAddon.length;

			for ( var x = 0; x < ruleAddonLength; x++ ) {

				if ( ruleAddon[x] == 0 || ! ruleAddon[x] ) {
					continue;
				}

				var ruleAddonSplit = ruleAddon[x].split( '-' );
				var AddonSelected  = false;
				var AddonNotEmpty  = false;

				// variation check.
				if ( typeof ruleAddonSplit[1] != 'undefined' ) {

					// Selector or checkbox.
					AddonSelected = (
						$( '#qpeofw-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).is( ':checked' )
						|| $( 'select#qpeofw-' + ruleAddonSplit[0] ).val() == ruleAddonSplit[1]
					) && ! $( '#qpeofw-addon-' + ruleAddonSplit[0] ).hasClass( 'hidden' );

					// text.
					var typeText = $( 'input#qpeofw-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).val();
					// textarea.
					var typeTextarea = $( 'textarea#qpeofw-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).val();
					AddonNotEmpty    = (
						(typeof typeText != 'undefined' && typeText !== '')
						|| (typeof typeTextarea != 'undefined' && typeTextarea !== '')
					) && ! $( '#qpeofw-addon-' + ruleAddonSplit[0] ).hasClass( 'hidden' );

					// addon check.
				} else {
					AddonSelected = (
						$( '#qpeofw-addon-' + ruleAddon[x] + ' input:checkbox:checked' ).length > 0
						|| $( '#qpeofw-addon-' + ruleAddon[x] + ' input:radio:checked' ).length > 0
						|| $( '#qpeofw-addon-' + ruleAddon[x] + ' option:selected' ).length > 0
						// Check if not default value of Select Add-on.
						&& 'default' != $( '#qpeofw-addon-' + ruleAddon[x] + ' option:selected' ).val()
					);
					AddonSelected = AddonSelected && ! $( '#qpeofw-addon-' + ruleAddon[x] ).hasClass( 'hidden' );

					var typeText = 'undefined';
					$( '#qpeofw-addon-' + ruleAddonSplit[0] + ' input, #qpeofw-addon-' + ruleAddonSplit[0] + ' textarea' ).each(
						function ( index ) {
							if ( $( this ).val() !== '' ) {
								typeText = true;
								return;
							}
						}
					);
					AddonNotEmpty = (
						( typeText !== 'undefined' && typeText !== '' )
					) && ! $( '#qpeofw-addon-' + ruleAddonSplit[0] ).hasClass( 'hidden' );
				}

				switch (ruleAddonIs[x]) {
					case 'selected' :
						if ( ! AddonSelected ) {
							conditional = false;
						}
						break;
					case 'not-selected':
						if ( AddonSelected ) {
							conditional = false;
						}
						break;

					case 'empty' :
						if ( AddonNotEmpty ) {
							conditional = false;
						}
						break;

					case 'not-empty' :
						if ( ! AddonNotEmpty ) {
							conditional = false;
						}

						break;
				}

				if ( ! conditional ) {
					break;
				}

			}

			return conditional;
		},
		conditionalLogicAnyRules: function ( ruleAddon, ruleAddonIs ) {
			var conditional 	= false;
			var ruleAddonLength = ruleAddon.length;

			for ( var x = 0; x < ruleAddonLength; x++ ) {

				if ( ruleAddon[x] == 0 || ! ruleAddon[x] ) {
					continue;
				}
				var ruleAddonSplit = ruleAddon[x].split( '-' );
				var AddonSelected  = false;
				var AddonNotEmpty  = false;

				// variation check.
				if ( typeof ruleAddonSplit[1] != 'undefined' ) {

					// Selector or checkbox.
					AddonSelected = (
						$( '#qpeofw-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).is( ':checked' )
						|| $( 'select#qpeofw-' + ruleAddonSplit[0] ).val() == ruleAddonSplit[1]
					) && ! $( '#qpeofw-addon-' + ruleAddonSplit[0] ).hasClass( 'hidden' );

					// text.
					var typeText = $( 'input#qpeofw-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).val();
					// textarea.
					var typeTextarea = $( 'textarea#qpeofw-' + ruleAddonSplit[0] + '-' + ruleAddonSplit[1] ).val();
					AddonNotEmpty    = (
						(typeof typeText != 'undefined' && typeText !== '')
						|| (typeof typeTextarea != 'undefined' && typeTextarea !== '')
					) && ! $( '#qpeofw-addon-' + ruleAddonSplit[0] ).hasClass( 'hidden' );

					// addon check.
				} else {
					AddonSelected = (
						$( '#qpeofw-addon-' + ruleAddon[x] + ' input:checkbox:checked' ).length > 0
						|| $( '#qpeofw-addon-' + ruleAddon[x] + ' input:radio:checked' ).length > 0
						|| $( '#qpeofw-addon-' + ruleAddon[x] + ' option:selected' ).length > 0
						// Check if not default value of Select Add-on.
						&& 'default' != $( '#qpeofw-addon-' + ruleAddon[x] + ' option:selected' ).val()
					);
					AddonSelected = AddonSelected && ! $( '#qpeofw-addon-' + ruleAddon[x] ).hasClass( 'hidden' );

					var typeText = 'undefined';
					$( '#qpeofw-addon-' + ruleAddonSplit[0] + ' input, #qpeofw-addon-' + ruleAddonSplit[0] + ' textarea' ).each(
						function ( index ) {
							if ( $( this ).val() !== '' ) {
								typeText = true;
								return;
							}
						}
					);
					AddonNotEmpty = (
						( typeText !== 'undefined' && typeText !== '' )
					) && ! $( '#qpeofw-addon-' + ruleAddonSplit[0] ).hasClass( 'hidden' );
				}

				switch (ruleAddonIs[x]) {
					case 'selected' :
						if ( AddonSelected ) {
							conditional = true;
						}
						break;
					case 'not-selected':
						if ( ! AddonSelected ) {
							conditional = true;
						}
						break;

					case 'empty' :
						if ( ! AddonNotEmpty ) {
							conditional = true;
						}
						break;

					case 'not-empty' :
						if ( AddonNotEmpty ) {
							conditional = true;
						}

						break;
				}
				if ( conditional ) {
					break;
				}
			}

			return conditional;
		}
	};

	qodeProductExtraOptionsForWooCommerce.conditionalLogicCheck = conditionalLogicCheck;

	var qodefSetTotalBoxPrices = {
		init: function ( defaultProductPrice, totalPrice, replacePrice = true, totalDefaultOptionsPrice = 0 ) {
			var quantity = $( qpeofwFrontend.productQuantitySelector ).val();

			if ( ! quantity > 0 ) {
				quantity = 1;
			}

			var totalProductPrice = defaultProductPrice * quantity,
				totalOptionsPrice = parseFloat( totalPrice ),
				totalOrderPrice   = parseFloat( totalPrice + totalProductPrice );

			// Price without formatting.
			var total_ProductPrice = totalProductPrice,
				total_OptionsPrice = totalOptionsPrice;

			var suffixData = {
				'product_id'        	: parseInt( $( '#qpeofw-container' ).attr( 'data-product-id' ) ),
				'options_price'     	: totalOptionsPrice,
				'options_default_price' : totalDefaultOptionsPrice,
				'total_order_price' 	: totalOrderPrice,
			};

			// Update totals and table.
			qodefSetTotalBoxPrices.calculateProductPrice( suffixData );

			$( '#qpeofw-total-price-table' ).css(
				'opacity',
				'1'
			);

			$( document ).trigger(
				'qodef_product_price_updated',
				[total_ProductPrice + total_OptionsPrice]
			);
		},
		calculateProductPrice: function ( suffixData ) {
			var data_price_suffix = {
				'action'	 : 'update_totals_with_suffix',
				'data'       : suffixData,
				'security'   : qpeofwFrontend.addons_nonce,
			};
			$.ajax(
				{
					url: qpeofwFrontend.ajaxurl,
					type: 'post',
					data: data_price_suffix,
					success: function ( response ) {
						if ( response ) {
							let totalProductPrice     = response['price_html'],
								totalOptionsPriceHTML = response['options_price_suffix'],
								totalOrderPriceHTML   = response['order_price_suffix'],
								totalOrderPrice       = suffixData.total_order_price;

							$( '#qpeofw-total-product-price' ).html( totalProductPrice );
							$( '#qpeofw-total-options-price' ).html( totalOptionsPriceHTML );
							$( '#qpeofw-total-order-price' ).html( totalOrderPriceHTML );

							qodefSetTotalBoxPrices.replaceProductPrice( totalOrderPrice, totalOrderPriceHTML );

						}
					}
				}
			);
		},
		replaceProductPrice: function ( totalOrderPrice, totalOrderPriceHtml ) {
			if ( 'yes' !== qpeofwFrontend.replace_price_in_product_without_addons && ( ! $( '#qpeofw-container' ).length || ! $( '#qpeofw-container' ).find( '.qpeofw-block' ).length) ) {
				return;
			}

			if ( parseInt( totalOrderPrice ) > 0 && 'yes' === qpeofwFrontend.replace_product_price && ! isNaN( parseFloat( totalOrderPrice ) ) && $( qpeofwFrontend.replace_product_price_class ).length > 0 ) {

				$( qpeofwFrontend.replace_product_price_class ).html( '<span class="woocommerce-Price-amount amount"><bdi>' + totalOrderPriceHtml + '</bdi></span>' );

				let productPrice = $( qpeofwFrontend.replace_product_price_class + ' bdi' ).text();
				if ( qodeProductExtraOptionsForWooCommerce.wcPriceToFloat.init( productPrice ) === 0 ) {
					$( qpeofwFrontend.replace_product_price_class ).find( 'bdi' ).remove();
				}
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefSetTotalBoxPrices = qodefSetTotalBoxPrices;

	/**
	 * Init Addons Price for different events
	 */
	var totalAddonsPriceInitOn = {
		init: function () {

			// Calculate Add-ons price triggers.
			$( document ).on(
				// TODO: gift cards.
				'qode-gift-cards-amount-changed',
				function ( e, button_amount ) {
					let price     = button_amount.data( 'price' );
					let container = $( '#qpeofw-container' );

					container.attr(
						'data-product-price',
						price
					);
					qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
				}
			).on(
				/**
				 * Allow external plugins the possibility to recalculate totals, after having changed the price.
				 *
				 * Price param is necessary.
				 */
				'qpeofw-product-price-updated',
				function ( e, price ) {
					if ( typeof price !== 'undefined' ) {
						$( '#qpeofw-container' ).attr(
							'data-product-price',
							price
						);
					}
					qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
				}
			).on(
				// TODO integrate when we finish gift cards plugin.
				'change',
				'.gift-cards-list .qode-gift-cards-manual-amount-container input.qode-gift-cards-manual-amount',
				function ( e ) {
					let t     = $( this ),
						price = t.val();

					let container = $( '#qpeofw-container' );

					container.attr(
						'data-product-price',
						price
					);
					qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
				}
			).on(
				// Dynamic compatibility.
				// TODO Dynamic Pricing and Discounts plugin if we crate it.
				'qode_dynamic_pricing_price_html_updated',
				function ( e, html_price ) {
					let price = $( html_price ).children( '.amount bdi' ).text();
					price     = qodeProductExtraOptionsForWooCommerce.wcPriceToFloat.init( price );

					if ( ! isNaN( price ) ) {
						let container = $( '#qpeofw-container' );

						container.attr(
							'data-product-price',
							price
						);
						qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
					}
				}
			).on(
				// QODE Product Bundles for WooCommerce.
				'qode_product_bundles_for_woocommerce_premium_updated_bundle_product_price',
				function ( e, response ) {
					// returned product bundles price.
					let price = response;

					if ( ! isNaN( price ) ) {
						$( '#qpeofw-container' ).attr( 'data-product-price', price );
						qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
					}
				}
			).on(
				'change',
				'form.cart div.qpeofw-addon, form.cart .quantity input[type=number]',
				function () {
					qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
				}
			).on(
				'keyup',
				'form.cart .qpeofw-addon-type-number input[type="number"], form.cart .qpeofw-addon-type-text input[type="text"], form.cart .qpeofw-addon-type-textarea textarea',
				function () {
					qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
				}
			).on(
				'click',
				'form.cart .qpeofw-addon-type-colorpicker .qpeofw-colorpicker-initialized input.wp-color-picker',
				function () {
					qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
				}
			).on(
				'qpeofw-colorpicker-change',
				function () {
					qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
				}
			).on(
				// addon type (checkbox).
				'change',
				'.qpeofw-addon-type-checkbox input',
				function () {
					let checkboxInput   = $( this );
					let checkboxButton  = checkboxInput.parents( '.qpeofw-checkbox-button' );
					let checkboxOption  = checkboxInput.parents( '.qpeofw-option' );
					let checkboxOptions = checkboxOption.parent();

					let isChecked = checkboxInput.attr( 'checked' );

					if ( 'checked' !== isChecked ) {

						// Single selection.
						if ( checkboxOption.hasClass( 'qpeofw-selection--single' ) ) {
							// Disable all.
							checkboxOptions.find( 'input' ).attr(
								'checked',
								false
							);
							checkboxOptions.find( 'input' ).prop(
								'checked',
								false
							);
							checkboxOptions.find( '.qpeofw-selected, .checked' ).removeClass( 'qpeofw-selected checked' );
						}

						// Enable only the current option.
						checkboxInput.attr(
							'checked',
							true
						);
						checkboxInput.prop(
							'checked',
							true
						);
						checkboxOption.addClass( 'qpeofw-selected' );
						checkboxButton.addClass( 'checked' );

						// Replace image.
						qodeProductExtraOptionsForWooCommerce.replaceImage.init( checkboxOption );

					} else {
						checkboxInput.attr(
							'checked',
							false
						);
						checkboxInput.prop(
							'checked',
							false
						);
						checkboxOption.removeClass( 'qpeofw-selected' );
						checkboxButton.removeClass( 'checked' );

						qodeProductExtraOptionsForWooCommerce.replaceImage.init(
							checkboxOption,
							true
						);
					}
				}
			).on(
				// addon type (radio).
				'click',
				'.qpeofw-addon-type-radio input',
				function () {
					var optionWrapper = $( this ).closest( '.qpeofw-option' );
					if ( ! optionWrapper.hasClass( 'qpeofw-option' ) ) {
						optionWrapper = optionWrapper.closest( '.qpeofw-option' );
					}
					if ( $( this ).is( ':checked' ) ) {
						optionWrapper.addClass( 'qpeofw-selected' );

						// Remove selected siblings.
						optionWrapper.siblings().removeClass( 'qpeofw-selected' );

						// Replace image.
						qodeProductExtraOptionsForWooCommerce.replaceImage.init( optionWrapper );

					} else {
						optionWrapper.removeClass( 'qpeofw-selected' );
					}

					optionWrapper.trigger( 'qpeofw_option_class_changed' );
				}
			).on(
				// addon type (select).
				'change',
				'.qpeofw-addon-type-select select',
				function () {
					let optionWrapper    = $( this ).parents( '.qpeofw-options' );
					let selectedOption   = $( this ).find( 'option:selected' );
					let optionImageBlock = optionWrapper.find( 'div.qpeofw-option-image' );
					let selectTag	 	 = $( this );

					if ( ! optionWrapper.hasClass( 'qpeofw-option' ) ) {
						optionWrapper = optionWrapper.parent();
					}

					// Description & Image.
					var optionImage       = selectedOption.data( 'image' );
					var optionDescription = selectedOption.data( 'description' );
					var option_desc       = optionWrapper.find( 'p.qpeofw-option-description' );

					if ( typeof optionImage !== 'undefined' && optionImage ) {
						optionImage = '<img src="' + optionImage + '" style="max-width: 100%">';
						optionImageBlock.html( optionImage );
					}

					if ( 'default' === selectedOption.val() || '' === optionImage ) {
						optionImageBlock.hide();
					} else {
						optionImageBlock.fadeIn();
					}

					if ( 'undefined' === typeof optionDescription || ! optionDescription ) {
						option_desc.empty();
						option_desc.hide();
					} else {
						option_desc.html( optionDescription );
						option_desc.show();
					}

					// prevent reseting image when replacing is disabled.
					if ( selectTag.hasClass( 'qpeofw-image-replacement--options' ) || selectTag.hasClass( 'qpeofw-image-replacement--addon' ) ) {

						// Replace image.
						if ( selectedOption.data( 'replace-image' ) ) {
							qodeProductExtraOptionsForWooCommerce.replaceImage.init( selectedOption );
						} else {
							qodeProductExtraOptionsForWooCommerce.replaceImage.init(
								selectedOption,
								true
							);
						}
					}
				}
			);

			$( '.qpeofw-addon-type-select select' ).trigger( 'change' );

			qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();
			totalAddonsPriceInitOn.checkDefaultOptionsOnLoad();

			$( document ).on(
				'change keyup',
				'.qpeofw-option .qpeofw-product-qty',
				function () {
					qodeProductExtraOptionsForWooCommerce.productQuantityChange.init();
				}
			);

			$( document ).on(
				'reset_data',
				function ( event ) {
					qodeProductExtraOptionsForWooCommerce.resetAddons.init( event );
				}
			);

			$( document ).on(
				'found_variation qode_variation_swatches_for_woocommerce_premium_trigger_variation_select_changed',
				function ( event, variation ) {
					qodeProductExtraOptionsForWooCommerce.foundVariation.init( event, variation );
				}
			);

			/* ajax reload addons **/
			$( document ).on(
				'qpeofw-reload-addons',
				function () {
					qodeProductExtraOptionsForWooCommerce.reloadAddons.init();
				}
			);
		},
		/**
		 * Check the default options selected on load page to replace the image.
		 */
		checkDefaultOptionsOnLoad: function () {
			let optionsSelected = $( '.qpeofw-addon:not(.conditional_logic) .qpeofw-option.qpeofw-selected' );
			$( optionsSelected ).each(
				function () {
					let option = $( this );
					qodeProductExtraOptionsForWooCommerce.replaceImage.init( option );
				}
			);
		}
	};

	qodeProductExtraOptionsForWooCommerce.totalAddonsPriceInitOn = totalAddonsPriceInitOn;

	var resetAddons = {
		init: function ( event ) {
			// QODE Variation Swatches for WooCommerce.
			if ( 'qodeVariationSwatchesForWooCommerceCustomProductAttributes' !== 'undefined' ) {
				return;
			}

			if ( ! qodeProductExtraOptionsForWooCommerce.firstVariationLoading ) {
				// initialized with window.qodeProductExtraOptionsForWooCommerce object.
				qodeProductExtraOptionsForWooCommerce.firstVariationLoading = true;

				return;
			}

			qodeProductExtraOptionsForWooCommerce.getDefaultProductPrice.init();

			$( document ).trigger( 'qpeofw-reset-addons' );
		}
	}

	qodeProductExtraOptionsForWooCommerce.resetAddons = resetAddons;

	var foundVariation = {
		init: function ( event, variation ) {
			qodeProductExtraOptionsForWooCommerce.updateContainerProductPrice.init( variation );

			$( document ).trigger( 'qpeofw-reload-addons' );
		}
	}

	qodeProductExtraOptionsForWooCommerce.foundVariation = foundVariation;

	var updateContainerProductPrice = {
		init: function ( variation ) {
			// Do not allow updating the price for variable product & product bundles.
			if ( $( '.cart .qpbfw-bundle-product-items' ).length || variation.variation_id !== parseInt( $( '.variation_id' ).val() ) ) {
				console.log( 'Price update intentionally prevented.' );
				return;
			}

			let container         = $( '#qpeofw-container' ),
				new_product_price = 0;
			if ( typeof (variation.display_price) !== 'undefined' ) {
				new_product_price = variation.display_price;
				// Check if variation price and price_html are different, use the last one.
				if ( 'yes' === qpeofwFrontend.use_price_html_on_variations && typeof (variation.price_html) !== 'undefined' ) {
					let new_product_price_html = $( variation.price_html ).find( '> .amount bdi' ).text();
					new_product_price_html     = qodeProductExtraOptionsForWooCommerce.wcPriceToFloat.init( new_product_price_html );
					if ( ! isNaN( new_product_price_html ) && new_product_price !== new_product_price_html ) {
						new_product_price = new_product_price_html;
					}
				}
			}

			container.attr(
				'data-product-price',
				new_product_price
			);
			container.attr(
				'data-product-id',
				variation.variation_id
			);
		}
	}

	qodeProductExtraOptionsForWooCommerce.updateContainerProductPrice = updateContainerProductPrice;

	var reloadAddons = {
		init: function ( event, productPrice = '' ) {
			var addons = $( 'form.cart' ).serializeArray(),
				// TODO: .ywcp - catalog mode plugin.
				container = $( 'form.cart:not(.ywcp) #qpeofw-container' ),
				data      = {
					'action': 'live_print_blocks',
					'addons': addons,
					'currency': qpeofwFrontend.woocommerce_currency,
					'current_language': qpeofwFrontend.currentLanguage,
					'security'        : qpeofwFrontend.addons_nonce,
			};

			if ( productPrice != '' ) {
				data.price = productPrice;
			}

			$.ajax(
				{
					url: qpeofwFrontend.ajaxurl,
					type: 'post',
					data: data,
					beforeSend: function () {
						$( '#qpeofw-container' ).css(
							'opacity',
							'0.5'
						);
					},
					error: function (xhr, status, error) {
						// Code to handle errors.
						console.error( 'AJAX request failed: ', status, error );
					},
					success: function ( response ) {
						let html       = response['html'] ? response['html'] : '';
						var quantities = response['quantities'] ? response['quantities'] : '';

						if (html !== '') {
							$( '.qpeofw-container' ).html( html );
						}

						let addonsSelected = response['addons'] ? response['addons'] : '';
						if ( addonsSelected !== '' ) {
							qodeProductExtraOptionsForWooCommerce.addonsSelection.init( addonsSelected );
						}
						if ( quantities ) {
							qodeProductExtraOptionsForWooCommerce.addonsQuantity.init( quantities );
						}

						$( 'form.cart' ).trigger( 'qpeofw-after-reload-addons' );
					},
					complete: function ( event ) {
						container.attr( 'data-order-price', 0 );
						qodeProductExtraOptionsForWooCommerce.calculateTotalAddonsPrice.init();

						container.css( 'opacity', '1' );

					}
				}
			);
		}
	};

	qodeProductExtraOptionsForWooCommerce.reloadAddons = reloadAddons;

	var addonsSelection = {
		init: function ( addons ) {
			var filesIndex = 0;
			for ( var i in addons ) {
				var addonObj = addons[i];

				for ( var addonIndex in addonObj ) {
					var addonVal  = addonObj[addonIndex];
					var addonOpts = addonIndex.split( '-' );

					let addonID 	= addonOpts[0] ? addonOpts[0] : '';
					let addonOption = addonOpts[1] ? addonOpts[1] : '';

					var addon     = $( '#qpeofw-addon-' + addonID ).get( 0 );
					var addonType = $( addon ).data( 'addon-type' );

					if ( '' === addonVal ) {
						continue;
					}

					var addonOpt = '';

					switch ( addonType ) {

						case 'checkbox':
						case 'label':
						case 'color':
						case 'product':
							addonOpt = $( addon ).find( '#qpeofw-' + addonID + '-' + addonOption );
							if ( ! addonOpt.is( ':checked' ) ) {
								$( addonOpt ).click();
							}
							break;
						case 'radio':
							addonOpt = $( addon ).find( '#qpeofw-' + addonID + '-' + addonVal );
							addonOpt.click();
							break;
						case 'text':
						case 'textarea':
						case 'number':
						case 'date':
							addonOpt = $( addon ).find( '#qpeofw-' + addonID + '-' + addonOption );
							addonOpt.val( addonVal );
							break;
						case 'colorpicker':
							addonOpt = $( addon ).find( '#qpeofw-' + addonID + '-' + addonOption );
							addonOpt.val( addonVal );
							addonOpt.trigger( 'change' );
							break;
						case 'select':
							addonOpt = addonOpt = $( addon ).find( '#qpeofw-' + addonID );
							addonOpt.val( addonVal );
							break;
						case 'file':
							addonOpt   = $( addon ).find( '#qpeofw-' + addonID + '-' + addonOption );
							var option = $( '#qpeofw-option-' + addonID + '-' + addonOption );

							qodeProductExtraOptionsForWooCommerce.loadUploadedFile.init(
								{
									'addon'      : $( addon ),
									'option'     : option,
									'addonVal'   : addonVal,
									'addonIndex' : addonIndex,
									'index'      : filesIndex,
									'fileSize'   : ''
								}
							);

							filesIndex++;
							break;
					}
				} // end second for loop.
			} // end first for loop.

			qodeProductExtraOptionsForWooCommerce.loadUploadedFile.maybeHideImageUploaded();
		}
	}

	qodeProductExtraOptionsForWooCommerce.addonsSelection = addonsSelection;

	var loadUploadedFile = {
		init: function ( data ) {

			let addon      = data.addon ? data.addon : '';
			let option     = data.option ? data.option : '';
			let addonVal   = data.addonVal ? data.addonVal : '';
			let addonIndex = data.addonIndex ? data.addonIndex : '';
			let index      = data.index ? data.index : '';
			let size       = data.fileSize ? data.fileSize : '';

			addonVal = $.isArray( addonVal ) ? addonVal[0] : addonVal;

			let name    = addonVal.split( '/' ).reverse()[0] ? addonVal.split( '/' ).reverse()[0] : '';
			let isImage = addonVal.endsWith( 'jpeg' ) || addonVal.endsWith( 'jpg' ) || addonVal.endsWith( 'png' );

			var fileTemplate          = wp.template( 'qpeofw-uploaded-file-template' );
			var uploadedFileContainer = $( '.qpeofw-uploaded-file' );

			var fileData = {
				'fileIndex': index,
				'fileName' : name,
				'fileSize' : size,
				'optionId' : addonIndex,
				'image'    : isImage ? addonVal : '',
				'addonVal' : addonVal,
			};

			uploadedFileContainer.append(
				fileTemplate( fileData )
			);

			uploadedFileContainer.show();
			loadUploadedFile.maybeHideUploadButton( option );
		},
		maybeHideImageUploaded: function () {

			$( '.qodef-addon-type-file .qpeofw-img-uploaded' ).each(
				function () {
					if ( ! $( this ).attr( 'src' ) ) {
						$( this ).hide();
					}
				}
			);
		},
		maybeHideUploadButton: function ( option ) {
			let maxMultiple = option.data( 'max-multiple' );

			if ( option.find( '.qpeofw-uploaded-file-element' ).length >= maxMultiple ) {
				option.find( '.qpeofw-ajax-uploader-container' ).hide();
			}
		}
	}

	qodeProductExtraOptionsForWooCommerce.loadUploadedFile = loadUploadedFile;

	var addonsQuantity = {
		init: function ( quantities ) {

			$.each(
				$( '.qpeofw-container' ).find( '.qpeofw-product-qty' ),
				function () {
					let separator 	  = 'qpeofw_product_qty';
					let qtyID 		  = $( this ).attr( 'id' );
					let identificator = qtyID.replace( separator, '' ).replace( '[', '' ).replace( ']', '' );

					if (identificator in quantities) {
						let qty = quantities[identificator];
						$( this ).val( qty );
					}
				}
			);
		}
	}

	qodeProductExtraOptionsForWooCommerce.addonsQuantity = addonsQuantity;

	var replaceImage = {
		init: function ( optionWrapper, reset = false ) {
			var defaultPath     = qpeofwFrontend.replace_image_path;
			var zoomMagnifier   = '.zoomWindowContainer .zoomWindow';
			var replaceImageURL = optionWrapper.data( 'replace-image' );

			if ( null === replaceImageURL || ! reset && $( defaultPath ).attr( 'src' ) === replaceImageURL ) {
				return;
			}

			if ( typeof optionWrapper.data( 'replace-image' ) !== 'undefined' && optionWrapper.data( 'replace-image' ) != '' ) {

				// save original image for the reset.
				if ( typeof ( $( defaultPath ).attr( 'qpeofw-original-img' ) ) == 'undefined' ) {
					$( defaultPath ).attr(
						'qpeofw-original-img',
						$( defaultPath ).attr( 'src' )
					);
					if ( $( zoomMagnifier ).length ) {
						$( zoomMagnifier ).attr(
							'qpeofw-original-img',
							$( zoomMagnifier ).css( 'background-image' ).slice(
								4,
								-1
							).replace(
								/"/g,
								''
							)
						);
					}
				}

				$( defaultPath ).attr(
					'src',
					replaceImageURL
				);
				$( defaultPath ).attr(
					'srcset',
					replaceImageURL
				);
				$( defaultPath ).attr(
					'data-src',
					replaceImageURL
				);
				$( zoomMagnifier ).css(
					'background-image',
					'url(' + replaceImageURL + ')'
				);
				$( '#qpeofw_product_img' ).val( replaceImageURL );
				$( defaultPath ).attr(
					'data-large_image',
					replaceImageURL
				);

				// Reset gallery position when add-on image change.
				if ( $( '.woocommerce-product-gallery .woocommerce-product-gallery__image' ).length > 0 ) {
					$( '.woocommerce-product-gallery' ).trigger( 'woocommerce_gallery_reset_slide_position' );
				}
				$( '.woocommerce-product-gallery' ).trigger( 'woocommerce_gallery_init_zoom' );
				$( document ).trigger( 'qode-after-replace-image' );
			}

			if ( reset && typeof ( $( defaultPath ).attr( 'qpeofw-original-img' ) ) != 'undefined' ) {
				let checkReset = true;

				var originalImage = $( defaultPath ).attr( 'qpeofw-original-img' );
				var originalZoom  = $( zoomMagnifier ).attr( 'qpeofw-original-img' );

				$( '#qpeofw-container .qpeofw-option' ).each(
					function ( index, element ) {
						let option = $( element );
						// Check if one option is still selected and has a image to replace, then do not change to default image.
						if ( option.data( 'replace-image' ) && option.hasClass( 'selected' ) ) {
							originalImage = option.data( 'replace-image' );
						}
					}
				);

				if ( checkReset ) {
					$( '#qpeofw_product_img' ).val( originalImage );

					$( defaultPath ).attr(
						'src',
						originalImage
					);
					$( defaultPath ).attr(
						'srcset',
						originalImage
					);
					$( defaultPath ).attr(
						'data-src',
						originalImage
					);
					$( defaultPath ).attr(
						'data-large_image',
						originalImage
					);
					$( zoomMagnifier ).css(
						'background-image',
						'url(' + originalZoom + ')'
					);

					// Reset gallery position when add-on image change.
					if ( $( '.woocommerce-product-gallery .woocommerce-product-gallery__image' ).length > 0 ) {
						$( '.woocommerce-product-gallery' ).trigger( 'woocommerce_gallery_reset_slide_position' );
					}
					$( '.woocommerce-product-gallery' ).trigger( 'woocommerce_gallery_init_zoom' );
					$( document ).trigger( 'qode-after-replace-image' );
				}
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.replaceImage = replaceImage;

	var qodefAddonFieldsSelect2 = {
		init: function ( settings ) {

			if ( qodeProductExtraOptionsForWooCommerce.pluginHasPredefinedStyle.init() ) {
				this.holder = [];
				this.holder.push(
					{
						holder : $( '.qpeofw-addon.qpeofw-addon-type-select .qpeofw-options select.qpeofw-option-value' ),
						options: {
							minimumResultsForSearch: -1,
							// add our dropdown class when option for only our fields is enabled.
							dropdownCssClass: qodeProductExtraOptionsForWooCommerce.body.hasClass( 'qode-product-extra-options-for-woocommerce-select2-style--plugin-fields' ) ? 'qpeofw-dropdown-select2' : '',
						}
					}
				);

				// Allow overriding the default config.
				$.extend( this.holder, settings );

				if (typeof this.holder === 'object') {
					$.each(
						this.holder,
						function (key, value) {
							qodefAddonFieldsSelect2.createSelect2( value.holder, value.options );
						}
					);
				}
			}
		},
		createSelect2: function ($holder, options) {
			if ( typeof $holder.select2 === 'function' ) {
				$holder.select2( options );
			}
		}
	};

	qodeProductExtraOptionsForWooCommerce.qodefAddonFieldsSelect2 = qodefAddonFieldsSelect2;

	var pluginHasPredefinedStyle = {
		init: function () {
			var $plugin_container 	= $( '#qpeofw-container' ),
				$hasPredefinedStyle = false;

			if ( $plugin_container.hasClass( 'qpeofw-form-style--predefined-style' ) ) {
				$hasPredefinedStyle = true;
			}

			return $hasPredefinedStyle;
		}
	};

	qodeProductExtraOptionsForWooCommerce.pluginHasPredefinedStyle = pluginHasPredefinedStyle;

})( jQuery );
