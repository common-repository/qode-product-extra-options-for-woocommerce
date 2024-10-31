(function ( $ ) {
	'use strict';

	if ( typeof qodefFramework !== 'object' ) {
		window.qodefFramework = {};
	}

	qodefFramework.mainHolder = $( '.qodef-page-v4-qode-product-extra-options-for-woocommerce.qodef-blocks' );

	$( document ).ready(
		function () {
			qodefBlocksListTableActive.init();
			qodefBlocksListTableSortable.init();
			qodefAddonsTableActive.init();
			qodefAddonsListTableSortable.init();
			qodefAddonsOverlayActions.init();
			actionButtonConfirmation.init();
			addonTabsChange.init();
			saveBlockOptions.init();
			toggleOptions.init();
			addNewOption.init();
			updateOptionTitleTypingInLabel.init();
			addNewDateRule.init();
			deleteDateRule.init();
			addNewTimeSlot.init();
			deleteTimeSlot.init();
			addNewConditionalLogic.init();
			deleteConditionalLogic.init();
			deleteOption.init();
			selectedbyDefaultConditions.init();
			selectedbyDefaultChecks.init();
			selectedByDefaultRadio.init();
			sortableOptions.init();
			qodefInitOptionsUploader.init();
			qodefDimmensionsGroup.init();
			qodefReinitNewOptionFields.init();
			qodefReinitExistingOptionFields.init();
			qodefReinitNewRuleFields.init();
			qodefReinitNewTimeSlotFields.init();
			qodefReinitNewConditionalLogicFields.init();
		}
	);

	$( window ).load(
		function () {
			updateOptionTitleWhenProductSelected.init();
		}
	);

	// enable/disable block.
	var qodefBlocksListTableActive = {
		init: function () {
			this.holder = $( '.qodef-options-admin.qodef-blocks' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						qodefBlocksListTableActive.initBlockActive( $( this ) );
					}
				);
			}
		},
		initBlockActive: function ( row ) {
			var $blockUpdateMessage = row.find( '.qodef-block-update-message' );
			var $nonceHolder 		= row.find( '#qodef-block-update-nonce' );

			row.on(
				'change',
				'td.active .qodef-yesno input',
				function () {

					var blockID         = $( this ).closest( '.qodef-yesno' ).attr( 'data-id' );
					var blockVisibility = 0;

					if ( $( this ).is( ':checked' ) && 'yes' === $( this ).attr( 'value' ) ) {
						blockVisibility = 1;
					}

					// Ajax method.
					var ajaxData = {
						'action': 'qode_product_extra_options_for_woocommerce_action_enable_disable_block',
						'block_id': blockID,
						'block_vis': blockVisibility,
						'nonce': $nonceHolder.val(),
					};

					$.ajax(
						{
							type: 'POST',
							url: ajaxurl,
							cache: ! 1,
							data: ajaxData,
							success: function ( response ) {
								console.log( 'Block visibility updated.' );

								$blockUpdateMessage.fadeIn( 300 );
								setTimeout(
									function () {
										$blockUpdateMessage.fadeOut( 200 );
									},
									2000
								);
							}
						}
					);
				}
			);
		}
	};

	qodefFramework.qodefBlocksListTableActive = qodefBlocksListTableActive;

	// sorting blocks.
	var qodefBlocksListTableSortable = {
		init: function () {
			this.holder = $( '#qodef-page.qodef-options-admin.qodef-blocks .qode-product-extra-options-for-woocommerce-blocks' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						qodefBlocksListTableSortable.initBlockSorting( $( this ) );
					}
				);
			}
		},
		initBlockSorting: function ( table ) {
			var $sortableRow 		= table.find( '#the-list' );
			var $blockUpdateMessage = table.prev( '.tablenav' ).find( '.qodef-block-update-message' );
			var $nonceHolder 		= table.prev( '.tablenav' ).find( '#qodef-block-update-nonce' );

			$sortableRow.sortable(
				{
					containment: table,
					helper: qodefBlocksListTableSortable.fixRowWidth,
					revert: true,
					axis: 'y',
					delay: 300,
					update: function ( event, ui ) {
						var itemID 	  = $( ui.item ).data( 'id' ),
							movedItem = $( ui.item ).find( 'td.column-priority' ).text(),
							prevItem  = $( ui.item ).prev().find( 'td.column-priority' ).text(),
							nextItem  = $( ui.item ).next().find( 'td.column-priority' ).text();

						// Ajax method.
						var ajaxData = {
							'action':'qode_product_extra_options_for_woocommerce_action_sortable_blocks',
							'item_id': itemID,
							'moved_item': movedItem,
							'prev_item': prevItem,
							'next_item': nextItem,
							'nonce': $nonceHolder.val(),
						};

						$.ajax(
							{
								type: 'POST',
								url: ajaxurl,
								data: ajaxData,
								success: function ( response ) {
									console.log( 'Block order updated.' );

									var data 		  = response.data,
										itemID 		  = data.itemID,
										itemPR 		  = parseFloat( data.itemPriority ),
										blockSelected = $( '#the-list tr[data-id="' + itemID + '"]' );

									blockSelected.find( '.column-priority' ).text( itemPR );

									$blockUpdateMessage.fadeIn( 300 );
									setTimeout(
										function () {
											$blockUpdateMessage.fadeOut( 200 );
										},
										2000
									);
								}
							}
						);
					}
				}
			);
		},
		fixRowWidth: function ( e, ui ) {
			ui.children().each(
				function () {
					$( this ).width( $( this ).width() );
				}
			);
			return ui;
		},
	};

	qodefFramework.qodefBlocksListTableSortable = qodefBlocksListTableSortable;

	// sorting addons.
	var qodefAddonsListTableSortable = {
		init: function () {
			this.holder = $( '#qodef-page.qodef-options-admin.qodef-blocks #qodef-block-addons #qodef-block-addons-container' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						qodefAddonsListTableSortable.initAddonSorting( $( this ) );
					}
				);
			}
		},
		initAddonSorting: function ( table ) {
			var $sortableRow 		= table.find( '#qodef-sortable-addons-list' ),
				$addonUpdateMessage = table.closest( '#qodef-addons-tab' ).prev( '#qodef-addons-tabs' ).find( '.qodef-addon-update-message' ),
				$nonceHolder 		= table.closest( '#qodef-addons-tab' ).prev( '#qodef-addons-tabs' ).find( '#qodef-addon-update-nonce' );

			$sortableRow.sortable(
				{
					containment: table,
					revert: true,
					axis: 'y',
					delay: 300,
					update: function ( event, ui ) {
						var movedItem = $( ui.item ).data( 'id' ),
							prevItem  = $( ui.item ).prev().data( 'priority' ),
							nextItem  = $( ui.item ).next().data( 'priority' );

						// Ajax method.
						var ajaxData = {
							'action':'qode_product_extra_options_for_woocommerce_action_sortable_addons',
							'moved_item': movedItem,
							'prev_item': prevItem,
							'next_item': nextItem,
							'nonce': $nonceHolder.val(),
						};

						$.ajax(
							{
								type: 'POST',
								url: ajaxurl,
								data: ajaxData,
								success: function ( response ) {
									console.log( 'Addon order updated.' );

									var res    = response.split( '-' ),
										itemID = res[0],
										itemPR = parseFloat( res[1] );

									$( '#qodef-sortable-addons-list #qodef-addon-' + itemID ).attr( 'data-priority', itemPR );

									$addonUpdateMessage.fadeIn( 300 );

									setTimeout(
										function () {
											$addonUpdateMessage.fadeOut( 200 );
										},
										2000
									);
								}
							}
						);
					}
				}
			);
		},
	};

	qodefFramework.qodefAddonsListTableSortable = qodefAddonsListTableSortable;

	// disable/enable addon.
	var qodefAddonsTableActive = {
		init: function () {
			this.holder = $( '#qodef-block-addons-container #qodef-sortable-addons-list' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						qodefAddonsTableActive.initAddonActive( $( this ) );
					}
				);
			}
		},
		initAddonActive: function ( row ) {
			var $addonUpdateMessage = row.closest( '#qodef-addons-tab' ).prev( '#qodef-addons-tabs' ).find( '.qodef-addon-update-message' );
			var $nonceHolder 		= row.closest( '#qodef-addons-tab' ).prev( '#qodef-addons-tabs' ).find( '#qodef-addon-update-nonce' );

			row.on(
				'change',
				'.qodef-addon-onoff .qodef-yesno input',
				function () {

					var addonID         = $( this ).closest( '.qodef-addon-element' ).attr( 'data-id' );
					var addonVisibility = 0;

					if ( $( this ).is( ':checked' ) && 'yes' === $( this ).attr( 'value' ) ) {
						addonVisibility = 1;
					}

					// Ajax method.
					var ajaxData = {
						'action': 'qode_product_extra_options_for_woocommerce_action_enable_disable_addon',
						'addon_id': addonID,
						'addon_vis': addonVisibility,
						'nonce': $nonceHolder.val(),
					};

					$.ajax(
						{
							type: 'POST',
							url: ajaxurl,
							cache: ! 1,
							data: ajaxData,
							success: function ( response ) {
								console.log( 'Addon visibility updated.' );

								$addonUpdateMessage.fadeIn( 300 );
								setTimeout(
									function () {
										$addonUpdateMessage.fadeOut( 200 );
									},
									2000
								);
							}
						}
					);
				}
			);
		}
	};

	qodefFramework.qodefAddonsTableActive = qodefAddonsTableActive;

	// Add-ons overlay modal closing.
	var qodefAddonsOverlayActions = {
		init: function () {

			var $addonOverlay = $( '.qodef-addon-overlay-wrapper' ),
				$addonEditor  = $( '#qodef-addon-editor' );

			if ( $addonOverlay.length ) {

				// close popup menu.
				$( document ).on(
					'click',
					'.qodef-addon-overlay-wrapper',
					function ( e ) {

						if ( e.target !== this ) {
							return;
						}

						qodefAddonsOverlayActions.closePopup( $addonOverlay );
					}
				);

				$( document ).on(
					'click',
					'.qodef-addon-overlay-wrapper #qodef-close-popup, .qodef-addon-overlay-wrapper #qodef-addon-editor-buttons .qodef-button-cancel',
					function ( e ) {
						e.preventDefault();

						qodefAddonsOverlayActions.closePopup( $addonOverlay );
					}
				);
			}
		},
		closePopup: function ( $popup ) {
			$popup.fadeOut();
			qodefAddonsOverlayActions.closePopupAction();
		},
		closePopupAction: function () {
			var currentURL = window.location.href;
				currentURL = currentURL.split( '&addon_id' );

			window.history.pushState( '', '', currentURL[0] );
		},
	};

	qodefFramework.qodefAddonsOverlayActions = qodefAddonsOverlayActions;

	// button action confirm modal with spinner.
	var actionButtonConfirmation = {
		init: function () {
			this.holder = $( '.qodef-action-button--require-confirmation' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						actionButtonConfirmation.initConfimation( $( this ) );
					}
				);
			}
		},
		initConfimation: function ( link ) {
			link.on(
				'click',
				function ( e ) {
					var clickedLink 	 = $( this ),
						message			 = clickedLink.attr( 'data-message' ),
						spinnerPreloader = $( '.qodef-admin-spinner' );

					if ( ! message.length ) {
						message = qpeofwAdminGlobal.i18n.actionButtonConfirmFallback;
					}

					var confirm = window.confirm( message );

					if ( confirm ) {
						spinnerPreloader.show();

						// hide spinner when deleting option since page ins't reloading.
						$( document ).on(
							'qodef_delete_option_trigger',
							function () {
								spinnerPreloader.hide();
							}
						);
					} else {
						spinnerPreloader.hide();
						return false;
					}
				}
			);
		}
	};

	qodefFramework.actionButtonConfirmation = actionButtonConfirmation;

	// Change Add-ons active tab.
	var addonTabsChange = {
		init: function () {
			this.holder = $( '#qodef-addon-tabs' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						addonTabsChange.initChange( $( this ) );
					}
				);
			}
		},
		initChange: function ( tabsLinksHolder ) {
			var tabsLinks 	= tabsLinksHolder.find( 'a' ),
				tabsContent = tabsLinksHolder.next( '#qodef-addon-container' ).find( '> div' );

			tabsLinks.on(
				'click',
				function ( e ) {
					e.preventDefault();

					var activeTab = $( this ),
						tab_id 	  = activeTab.attr( 'id' );

					tabsLinks.removeClass( 'qodef-selected' );
					activeTab.addClass( 'qodef-selected' );
					tabsContent.hide();
					$( '#qodef-addon-container #' + tab_id + '-tab' ).show();
				}
			);
		}
	};

	qodefFramework.addonTabsChange = addonTabsChange;

	// Save block.
	var saveBlockOptions = {
		init: function () {
			this.holder = $( '#qodef-panel-block' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						saveBlockOptions.initChange( $( this ) );
					}
				);
			}
		},
		initChange: function ( block ) {
			var blockName  = block.find( '#block-name' ),
				blockError = block.find( '.qodef-block-error' );

			if ( 'new' === block.data( 'block-id' ) ) {
				return;
			}

			if ( blockError.length ) {
				blockError.remove();
			}

			block.on(
				'click',
				'.qodef-back-to-block-list, .qodef-save-button',
				function ( e ) {
					if ( '' === blockName.val() ) {
						blockName.addClass( 'qodef-input-error' );
						$( '<small class="qodef-block-error">' + qpeofwAdminGlobal.i18n.blockNameRequired + '</small>' ).insertAfter( blockName.closest( '.qodef-field-wrapper' ) );

						$( [document.documentElement, document.body] ).animate(
							{
								scrollTop: $( blockName ).offset().top - 50
							},
							1000
						);

						return false;
					}
				}
			);
		}
	};

	qodefFramework.saveBlockOptions = saveBlockOptions;

	// Toggle options.
	var toggleOptions = {
		init: function () {
			this.holder = $( '#qodef-options-list-tab' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						toggleOptions.initChange( $( this ) );
					}
				);
			}
		},
		initChange: function ( option ) {
			option.on(
				'click',
				'.qodef-option-item .qodef-title',
				function ( e ) {
					var fieldsContainer = $( this ).parent().find( '.qodef-fields' );

					fieldsContainer.toggle();
					if ( fieldsContainer.is( ':visible' ) ) {
						$( this ).parent().removeClass( 'qodef-close' ).addClass( 'qodef-open' );
					} else {
						$( this ).parent().removeClass( 'qodef-open' ).addClass( 'qodef-close' );
					}
				}
			);
		}
	};

	qodefFramework.toggleOptions = toggleOptions;

	// Add NEW options.
	var addNewOption = {
		init: function () {
			this.holder = $( '#qodef-add-new-option' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						addNewOption.initAddOption( $( this ) );
					}
				);
			}
		},
		initAddOption: function ( option ) {
			option.on(
				'click',
				function ( e ) {
					e.preventDefault();
					e.stopImmediatePropagation();

					var optionsHolder = $( this ).closest( '#qodef-options-list-tab' ).find( '#qodef-addon-options' ),
						optionID  	  = $( this ).closest( '#qodef-options-list-tab' ).find( '#qodef-addon-options .qodef-option-item' ).last().data( 'index' ),
						template 	  = wp.template( 'qodef-new-option-template' );

					if ( ! optionID ) {
						optionID = 0;
					}

					optionID++;

					optionsHolder.append(
						template(
							{
								option_index: parseInt( optionID ),
							}
						)
					);

					$( document ).trigger(
						'qodef_add_new_option_trigger',
						template
					);

					adaptAddonsIndex.init();
				}
			);
		}
	};

	qodefFramework.addNewOption = addNewOption;

	// Add new date rule.
	var addNewDateRule = {
		init: function () {
			this.holder = $( '#qodef-add-date-rule > a' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						addNewDateRule.initAddRule( $( this ) );
					}
				);
			}
		},
		initAddRule: function ( newRule ) {
			newRule.on(
				'click',
				function ( e ) {
					var ruleId     	 = $( this ).parents( '.qodef-option-item' ).data( 'index' ),
						ruleOptionId = $( this ).parents( '.qodef-date-rules' ).find( '.qodef-date-rules-container .qodef-rule' ).length,
						template     = wp.template( 'qodef-date-rule-template' ),
						lastRule     = $( this ).parents( '.qodef-date-rules' ).find( '.qodef-date-rules-container .qodef-rule' ).last();

					lastRule.after(
						template(
							{
								addon_id: ruleId,
								option_id: ruleOptionId,
							}
						)
					);

					$( document ).trigger(
						'qodef_add_new_rule_trigger',
						template
					);
				}
			);
		}
	};

	qodefFramework.addNewDateRule = addNewDateRule;

	// Delete date rule.
	var deleteDateRule = {
		init: function () {
			this.holder = $( '.qodef-disable-date-rules .qodef-delete-rule' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						deleteDateRule.initDeleteRule( $( this ) );
					}
				);
			}
		},
		initDeleteRule: function ( deleteRuleButton ) {
			deleteRuleButton.on(
				'click',
				function ( e ) {
					$( this ).parents( '.qodef-rule' ).remove();
				}
			);
		}
	};

	qodefFramework.deleteDateRule = deleteDateRule;

	// Add new time slot.
	var addNewTimeSlot = {
		init: function () {
			this.holder = $( '#qodef-add-time-slot > a' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						addNewTimeSlot.initTimeSlot( $( this ) );
					}
				);
			}
		},
		initTimeSlot: function ( newSlot ) {
			newSlot.on(
				'click',
				function ( e ) {
					var timeSlotsContainer = $( this ).closest( '.qodef-time-slots-container' ),
						slotTemplate 	   = timeSlotsContainer.find( '.qodef-slot:first-child' ),
						clonedSlot 		   = slotTemplate.clone();

					// remove select2 markup - it will be initialized after trigger.
					clonedSlot.find( 'span.select2.select2-container' ).remove();

					timeSlotsContainer.find( '.qodef-time-rules-container' ).append( clonedSlot );

					$( document ).trigger(
						'qodef_add_new_time_slot_trigger',
						clonedSlot
					);
				}
			);
		}
	};

	qodefFramework.addNewTimeSlot = addNewTimeSlot;

	// Delete time slot.
	var deleteTimeSlot = {
		init: function () {
			this.holder = $( '.qodef-delete-slot' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						deleteTimeSlot.initDeleteSlot( $( this ) );
					}
				);
			}
		},
		initDeleteSlot: function ( deleteSlotButton ) {
			deleteSlotButton.on(
				'click',
				function ( e ) {
					$( this ).parents( '.qodef-slot' ).remove();
				}
			);
		}
	};

	qodefFramework.deleteTimeSlot = deleteTimeSlot;

	// Add new conditional logic rule.
	var addNewConditionalLogic = {
		init: function () {
			this.holder = $( '#qodef-add-conditional-rule > a' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						addNewConditionalLogic.initAddConditionRule( $( this ) );

						var allRules     = $( this ).parent( '#qodef-add-conditional-rule' ).prev( '.qodef-display-rules-container-inner' ).find( '.qodef-rule' ),
							selectFields = allRules.find( '.qodef-select2:not(.select2-hidden-accessible)' );

						// fix - reinit existing rules select2 fields.
						selectFields.each(
							function () {
								var $thisSelect = $( this );
								var randID 		= Math.floor( Math.random() * 100 );

								$thisSelect.attr( 'data-select2-id', randID );

								window.qodefFramework.select2.initField( $thisSelect );
							}
						);
					}
				);
			}
		},
		initAddConditionRule: function ( newRule ) {
			newRule.on(
				'click',
				function ( e ) {
					e.preventDefault();

					var ruleTemplate  = $( '.qodef-display-rules .qodef-rule:first-child' ),
						clonedOption  = ruleTemplate.clone( false ),
						addon_options = JSON.parse( $( this ).closest( '.qodef-display-rules' ).attr( 'data-addon-options' ) ),
						select        = $( this ).closest( '.qodef-display-rules' ).find( 'select.addon-conditional-rule-addon' ),
						allRules 	  = $( '.qodef-display-rules .qodef-rule' );

					var selectedValues = $( select ).map(
						function () {
							return $( this ).val();
						}
					).get();

					var options  = addNewConditionalLogic.filterConditLogicOptions( addon_options, selectedValues );
					var selector = addNewConditionalLogic.createConditionalSelector( options );

					var parent_selector = $( '<div class="qodef-field-wrapper"></div>' );
					parent_selector.append( selector );

					// remove select2 markup - it will be initialized after trigger.
					clonedOption.find( 'span.select2.select2-container' ).remove();

					// fix for select2 reinit - data-select2-id has to be unique.
					var clonedSelectField = clonedOption.find( '.qodef-select2' );

					clonedSelectField.each(
						function () {
							var $thisSelect = $( this );
							var randID 		= Math.floor( Math.random() * 100 );

							$thisSelect.attr( 'data-select2-id', randID );
						}
					);

					var newOption = $( '.qodef-display-rules-container-inner' ).append( clonedOption );

					$( document ).trigger(
						'qodef_add_new_conditional_logic_trigger',
						clonedOption
					);
				}
			);
		},
		createConditionalSelector: function ( options ) {
			var selector = $( '<select>' ).attr( 'id', 'addon-conditional-rule-addon' ).attr( 'name', 'addon_conditional_rule_addon[]' ).addClass( 'qodef-select2 qodef-field qodef-dependency-option select2-hidden-accessible' );

			var emptyOption = $( '<option value="empty">' + qpeofwAdminGlobal.i18n.selectOption + '</option>' );
			selector.append( emptyOption );

			$.each(
				options,
				function ( i, item ) {
					if ( typeof item === 'string' ) {
						return false;
					}

					var optgroup = $( '<optgroup label="' + item.label + '">' );
					$.each(
						item.options,
						function ( opt_value, opt_label ) {
							var option = $( '<option value="' + opt_value + '">' + opt_label + '</option>' );
							optgroup.append( option );
						}
					);
					selector.append( optgroup );
				}
			);

			return selector;
		},
		filterConditLogicOptions: function ( options, selectedValues ) {
			for ( var i in options ) {
				if ( $.isNumeric( i ) ) {
					for ( var j in options[i].options ) {
						if ( ( $.inArray( j, selectedValues ) > -1  ) ) {
							delete options[i].options[j];
						}
					}
				}
			}

			return options;
		}
	};

	qodefFramework.addNewConditionalLogic = addNewConditionalLogic;

	// Delete conditional logic.
	var deleteConditionalLogic = {
		init: function () {
			this.holder = $( '.qodef-display-rules .qodef-delete-rule' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						deleteConditionalLogic.initDeleteCondition( $( this ) );
					}
				);
			}
		},
		initDeleteCondition: function ( deleteConditionButton ) {
			deleteConditionButton.on(
				'click',
				function ( e ) {
					$( this ).parents( '.qodef-rule' ).remove();

					/*var selectors = $( '#conditional-rules' ).find( 'select.addon-conditional-rule-addon' );

					 updateOtherCondSelector( selectors );*/
				}
			);
		}
	};

	qodefFramework.deleteConditionalLogic = deleteConditionalLogic;

	// Delete option.
	var deleteOption = {
		init: function () {
			this.holder = $( '#qodef-addon-options .qodef-option-item' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						deleteOption.initDeleteOption( $( this ) );
					}
				);
			}
		},
		initDeleteOption: function ( optionItem ) {
			var deleteButtonAction = optionItem.find( '.qodef-action-button--delete-action' );

			deleteButtonAction.on(
				'click',
				function ( e ) {
					e.preventDefault();
					e.stopImmediatePropagation();

					$( this ).closest( '.qodef-option-item' ).remove();

					// check first checkbox when removing option and there are none checked options.
					var optionsHolder = $( '#qodef-addon-options' ),
						radiosChecked = optionsHolder.find( '.qodef-field.selected-by-default-chbx.checkbox:checked' ).length,
						firstRadio 	  = optionsHolder.find( '.qodef-field.selected-by-default-chbx.checkbox.checkbox' ).first();

					if ( $( '#qodef-addon-editor-type' ).hasClass( 'qodef-addon-editor-type-radio' ) ) {

						if ( radiosChecked < 1 ) {
							firstRadio.prop( 'checked', true );
						}
					}

					adaptAddonsIndex.init();

					$( document ).trigger(
						'qodef_delete_option_trigger',
						optionItem
					);
				}
			);
		}
	};

	qodefFramework.deleteOption = deleteOption;

	// Adapt Addons indexes for each option.
	var adaptAddonsIndex = {
		init: function () {
			// single field index adj.
			const options_array = [ 'default' ];

			$.each(
				options_array,
				function ( index, value ) {
					var inputsSelected = $( 'input[name^="options[' + value + ']" ]' );
					inputsSelected.each(
						function ( index ) {
							$( this ).attr( 'name', 'options[' + value + '][' + index + ']' );
						}
					);
				}
			);

			// multiple fields index adj.
			const options_array_group = [ 'addon_enabled', 'show_image', 'required' ];

			$.each(
				options_array_group,
				function ( index, value ) {
					var groupOption = $( '[data-option-name^="options[' + value + ']"' );

					groupOption.each(
						function ( index ) {
							// adjust data-option-name.
							$( this ).attr( 'data-option-name', 'options[' + value + '][' + index + ']' );
							// adjust input option name.
							$( this ).find( $( 'input[name^="options[' + value + ']" ]' ) ).attr( 'name', 'options[' + value + '][' + index + ']' );
						}
					);
				}
			);
		},
	};

	qodefFramework.adaptAddonsIndex = adaptAddonsIndex;

	// Update option title while typing in label input field.
	var updateOptionTitleTypingInLabel = {
		init: function () {
			this.holder = $( '#qodef-addon-overlay #qodef-options-list-tab .qodef-additional-options > input' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						updateOptionTitleTypingInLabel.initTypingChange( $( this ) );
					}
				);
			}
		},
		initTypingChange: function ( inputField ) {
			inputField.on(
				'keyup',
				function ( e ) {
					var text 	  = $( this ),
						option 	  = text.closest( '.qodef-option-item.qodef-open' ),
						textLabel = option.find( '.qodef-addon-label-text' ),
						textValue = text.val();

					// update label text.
					textLabel.html( textValue );
				}
			);
		}
	};

	qodefFramework.updateOptionTitleTypingInLabel = updateOptionTitleTypingInLabel;

	// Update option title when product is selected.
	var updateOptionTitleWhenProductSelected = {
		init: function () {
			this.holder = $( '#qodef-addon-overlay #qodef-options-list-tab .qodef-addon-product-selection .qodef-field-wrapper > select' );

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						updateOptionTitleWhenProductSelected.changeTittle( $( this ) );
					}
				);
			}
		},
		changeTittle: function ( selectField ) {
			selectField.on(
				'change',
				function ( e ) {
					var productElement = $( this ),
						productTitle   = productElement.next( '.select2' ).find( '.select2-selection .select2-selection__rendered' ).attr( 'title' ),
						option 		   = productElement.closest( '.qodef-option-item.qodef-open' ),
						textLabel 	   = option.find( '.qodef-addon-label-text' );

					// update label text.
					textLabel.html( productTitle );
				}
			);
		}
	};

	qodefFramework.updateOptionTitleWhenProductSelected = updateOptionTitleWhenProductSelected;

	// Uncheck all Selected by default except one if is already checked if the selection type option is changed from multiple to single.
	var selectedbyDefaultConditions = {
		init: function () {
			$( document ).on(
				'change',
				'#addon-selection-type',
				function ( e ) {
					var selectedOption = $( this ).val();

					if ( selectedOption.length && 'single' === selectedOption ) {
						var one_checked = false;
						$( '#qodef-optipons-list-tab #qodef-addon-otions .qodef-selected-by-default' ).each(
							function ( index ) {
								if ( $( this ).find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
									if ( one_checked ) {
										$( this ).find( 'input[type="checkbox"]' ).prop(
											"checked",
											false
										);
									} else {
										one_checked = true;
									}
								}
							}
						);
					}
				}
			);
		}
	};

	qodefFramework.selectedbyDefaultConditions = selectedbyDefaultConditions;

	var selectedbyDefaultChecks = {
		init: function () {
			$( document ).on(
				'change',
				'#qodef-addon-options .qodef-selected-by-default input[type="checkbox"]',
				function ( e ) {
					var selectedCheckbox = $( this ),
						allCheckboxes    = $( this ).closest( '#qodef-addon-options' ).find( '.qodef-selected-by-default input[type="checkbox"]' ),
						selectionType    = $( this ).closest( '#qodef-addon-editor-type' ).find( '#qodef-advanced-settings-tab #addon-selection-type' ).val(),
						addonType        = $( this ).closest( '#qodef-addon-editor' ).data( 'addon-type' );

					if ( 'single' === selectionType || 'select' === addonType ) {
						var checkedCheckboxes = $( this ).closest( '#qodef-addon-options' ).find( '.qodef-selected-by-default input[type="checkbox"]:checked' ).length - 1;
						if ( checkedCheckboxes > 0 ) {
							allCheckboxes.prop( 'checked', false );
							selectedCheckbox.prop( 'checked', true );
						}
					}
				}
			);
		}
	};

	qodefFramework.selectedbyDefaultChecks = selectedbyDefaultChecks;

	/** Force the user to select always one radio */
	var selectedByDefaultRadio = {
		init: function () {
			$( document ).on(
				'click',
				'.qodef-addon-editor-type-radio .qodef-field.selected-by-default-chbx.checkbox',
				function ( e ) {
					var clickedRadio  = $( this ),
						radiosChecked = clickedRadio.closest( '#qodef-addon-options' ).find( '.qodef-field.selected-by-default-chbx.checkbox:checked' ),
						addonType 	  = $( this ).closest( '#qodef-addon-editor' ).data( 'addon-type' );

					if ( 'radio' === addonType ) {
						radiosChecked.prop( 'checked', false );
						clickedRadio.prop( 'checked', true );
					}
				}
			);
		}
	};

	qodefFramework.selectedByDefaultRadio = selectedByDefaultRadio;

	// option sorting.
	var sortableOptions = {
		init: function () {
			$( '#qodef-addon-options' ).sortable(
				{
					helper: sortableOptions.fixRowWidth,
					revert: true,
					axis: 'y',
					delay: 150,
					start: sortableOptions.initiallyChecked(),
					stop: function () {
						adaptAddonsIndex.init();
						sortableOptions.restoreChecked();
					}
				}
			);
		},
		// Store the checked radio properties.
		initiallyChecked: function () {
			$( '#qodef-addon-options' ).find( '.qodef-option-item input:radio' ).each(
				function () {
					if ( $( this ).prop( 'checked' ) ) {
						$( this ).attr( 'data-checked', 'true' );
					} else {
						$( this ).attr( 'data-checked', 'false' );
					}
				}
			);
		},
		// Restore the checked radio properties.
		restoreChecked: function () {
			$( '#qodef-addon-options' ).find( '.qodef-option-item input:radio' ).each(
				function () {
					if ( $( this ).attr( 'data-checked' ) === 'true' ) {
						$( this ).prop( 'checked', true );
					} else {
						$( this ).prop( 'checked', false );
					}
				}
			);
		},
		fixRowWidth: function ( e, ui ) {
			ui.children().each(
				function () {
					$( this ).width( $( this ).width() );
				}
			);
			return ui;
		},
	};

	qodefFramework.sortableOptions = sortableOptions;

	var qodefReinitRepeaterFields = {
		init: function () {
			$( document ).on(
				'qode_product_extra_options_for_woocommerce_add_new_row_trigger',
				function ( event, $row ) {
					window.qodefFramework.qodefSearchOptions.fieldHolder.push( $row );
					window.qodefFramework.qodefInitMediaUploader.reinit( qodefFramework.mainHolder );
					qodefInitOptionsUploader.reinit( $row );
					window.qodefFramework.qodefColorPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefDatePicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.select2.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefInitIconPicker.reinit( qodefFramework.mainHolder );
				}
			);
		}
	};

	var qodefReinitNewOptionFields = {
		init: function () {
			$( document ).on(
				'qodef_add_new_option_trigger',
				function ( event, $row ) {
					if ( typeof window.qodefFramework.qodefSearchOptions.fieldHolder !== 'undefined' ) {
						window.qodefFramework.qodefSearchOptions.fieldHolder.push( $row );
					}
					window.qodefFramework.qodefInitMediaUploader.reinit( qodefFramework.mainHolder );
					qodefInitOptionsUploader.reinit( $row );
					window.qodefFramework.qodefColorPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefDatePicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.select2.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefInitIconPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.actionButtonConfirmation.init();
					window.qodefFramework.qodefDependency.init( qodefFramework.mainHolder );
					window.qodefFramework.updateOptionTitleTypingInLabel.init();
					window.qodefFramework.updateOptionTitleWhenProductSelected.init();
					window.qodefFramework.deleteOption.init();
					window.qodefFramework.sortableOptions.init();
				}
			);
		}
	};

	var qodefReinitExistingOptionFields = {
		init: function () {
			$( document ).on(
				'qodef_delete_option_trigger',
				function ( event, $row ) {
					window.qodefFramework.addNewOption.init();
				}
			);
		}
	};

	var qodefReinitNewRuleFields = {
		init: function () {
			$( document ).on(
				'qodef_add_new_rule_trigger',
				function ( event, $row ) {
					window.qodefFramework.qodefSearchOptions.fieldHolder.push( $row );
					window.qodefFramework.qodefInitMediaUploader.reinit( qodefFramework.mainHolder );
					qodefInitOptionsUploader.reinit( $row );
					window.qodefFramework.qodefColorPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefDatePicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.select2.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefInitIconPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefDependency.init( qodefFramework.mainHolder );
					window.qodefFramework.deleteDateRule.init();
				}
			);
		}
	};

	var qodefReinitNewTimeSlotFields = {
		init: function () {
			$( document ).on(
				'qodef_add_new_time_slot_trigger',
				function ( event, $row ) {
					window.qodefFramework.qodefSearchOptions.fieldHolder.push( $row );
					window.qodefFramework.qodefInitMediaUploader.reinit( qodefFramework.mainHolder );
					qodefInitOptionsUploader.reinit( $row );
					window.qodefFramework.qodefColorPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefDatePicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.select2.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefInitIconPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefDependency.init( qodefFramework.mainHolder );
					window.qodefFramework.deleteTimeSlot.init();
				}
			);
		}
	};

	var qodefReinitNewConditionalLogicFields = {
		init: function () {
			$( document ).on(
				'qodef_add_new_conditional_logic_trigger',
				function ( event, $row ) {
					window.qodefFramework.qodefSearchOptions.fieldHolder.push( $row );
					window.qodefFramework.qodefInitMediaUploader.reinit( qodefFramework.mainHolder );
					qodefInitOptionsUploader.reinit( $row );
					window.qodefFramework.qodefColorPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefDatePicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.select2.reinit( $row );
					window.qodefFramework.qodefInitIconPicker.reinit( qodefFramework.mainHolder );
					window.qodefFramework.qodefDependency.init( qodefFramework.mainHolder );
					window.qodefFramework.deleteConditionalLogic.init();
				}
			);
		}
	};

	var qodefInitOptionsUploader = {
		init: function () {
			this.$holder = $( '.qodef-options-uploader' );

			if ( this.$holder.length ) {
				this.$holder.each(
					function () {
						qodefInitOptionsUploader.initField( $( this ) );
					}
				);
			}
		},
		reinit: function ( row ) {
			var $holder = $( row ).find( '.qodef-options-uploader' );

			if ( $holder.length ) {
				$holder.each(
					function () {
						qodefInitOptionsUploader.initField( $( this ) );
					}
				);
			}
		},
		initField: function ( thisHolder ) {
			var variables = {
				$multiple: '',
				$option: thisHolder.data( 'options' ) === 'yes',
				$optionMeta: thisHolder.find( '.qodef-options-meta-fields' ),
				$allowed_type: thisHolder.data( 'file' ) === 'yes' ? thisHolder.data( 'allowed-type' ) : 'image',
				$imageHolder: thisHolder,
				optionsFrame: thisHolder.parents( '.qodef-admin-page' ).find( '.qodef-block-additional-options-overlay' ),
				optionsFrameTypes: thisHolder.parents( '.qodef-admin-page' ).find( '.qodef-block-additional-options-overlay .qodef-type' ),
				optionsFrameClose: thisHolder.parents( '.qodef-admin-page' ).find( '.qodef-deactivation-modal-button' ),
				mediaFrame: '',
				attachment: '',
				$thumbImageHolder: thisHolder.find( '.qodef-option-thumb' ),
				$uploadId: thisHolder.find( '.qodef-option-config-init' ),
				$removeButton: thisHolder.find( '.qodef-image-remove-btn' ),
				$removeOption: thisHolder.find( '.qodef-option-remove' )
			};

			if ( variables.$thumbImageHolder.find( 'img' ).length ) {
				variables.$removeButton.show();
				qodefInitOptionsUploader.remove( variables.$removeButton );
			}

			if ( variables.$removeOption.length ) {
				variables.$removeOption.on(
					'tap click',
					function ( e ) {
						e.preventDefault();

						var optionsToRemove   = $( this ).parents( '.qodef-options-uploader' ).find( '.qodef-option-config' );
						var parentOptionIndex = $( this ).parent().attr( 'data-index' );

						optionsToRemove.each(
							function () {
								var $option = $( this );

								if ( $option.attr( 'data-index' ) === parentOptionIndex ) {
									$option.remove();
								}

							}
						);
					}
				);
			}

			variables.optionsFrameClose.on(
				'tap click',
				function ( e ) {
					e.preventDefault();

					if ( variables.optionsFrame ) {
						if ( variables.optionsFrame.hasClass( 'qodef--opened' ) ) {
							variables.optionsFrame.removeClass( 'qodef--opened' );
						}
					}
				}
			);

			variables.$imageHolder.on(
				'tap click',
				'.qodef-add-option-btn',
				function () {
					var addOptionButton = $( this );

					if ( variables.optionsFrame ) {
						if ( variables.optionsFrame.hasClass( 'qodef--opened' ) ) {
							variables.optionsFrame.removeClass( 'qodef--opened' );
						} else {
							variables.optionsFrame.addClass( 'qodef--opened' );

							if ( variables.optionsFrameTypes.length ) {
								variables.optionsFrameTypes.each(
									function () {
										$( this ).on(
											'tap click',
											function ( e ) {
												e.preventDefault();

												if ( $( this ).data() ) {
													/* hidden input */
													var counter = addOptionButton.parent( '.qodef-options-uploader' ).find( '.qodef-options-meta-fields .qodef-option-config' ).length;
													variables.$uploadId.val( $( this ).data().value.toLowerCase() );
													var metaClone = '';
													metaClone     = variables.$uploadId.clone();
													metaClone.attr(
														'class',
														'qodef-field qodef-option-config'
													);
													var metaCloneName = metaClone.attr( 'name' );
													metaClone.attr(
														'name',
														metaCloneName + '[' + counter + ']'
													);
													metaClone.attr(
														'data-index',
														counter
													);
													metaClone.appendTo( variables.$optionMeta );

													/* user visible input */
													var liOptions    = variables.$thumbImageHolder.find( 'ul' );
													var addMarkup    = '';
													var removeButton = '<a class="qodef-option-remove" href="#" rel="noopener noreferrer"><svg class="" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="92px" height="92px" viewBox="0 0 92 92" enable-background="new 0 0 92 92" xml:space="preserve"><path d="M78.4,30.4l-3.1,57.8c-0.1,2.1-1.9,3.8-4,3.8H20.7c-2.1,0-3.9-1.7-4-3.8l-3.1-57.8c-0.1-2.2,1.6-4.1,3.8-4.2c2.2-0.1,4.1,1.6,4.2,3.8l2.9,54h43.1l2.9-54c0.1-2.2,2-3.9,4.2-3.8C76.8,26.3,78.5,28.2,78.4,30.4zM89,17c0,2.2-1.8,4-4,4H7c-2.2,0-4-1.8-4-4s1.8-4,4-4h22V4c0-1.9,1.3-3,3.2-3h27.6C61.7,1,63,2.1,63,4v9h22C87.2,13,89,14.8,89,17zM36,13h20V8H36V13z M37.7,78C37.7,78,37.7,78,37.7,78c2,0,3.5-1.9,3.5-3.8l-1-43.2c0-1.9-1.6-3.5-3.6-3.5c-1.9,0-3.5,1.6-3.4,3.6l1,43.3C34.2,76.3,35.8,78,37.7,78z M54.2,78c1.9,0,3.5-1.6,3.5-3.5l1-43.2c0-1.9-1.5-3.6-3.4-3.6c-2,0-3.5,1.5-3.6,3.4l-1,43.2C50.6,76.3,52.2,78,54.2,78C54.1,78,54.1,78,54.2,78z"></path></svg></a>';
													addMarkup        = '<li class="qodef-field qodef-option-config" data-index="' + counter + '" name="' + metaClone[0].name + '"' + ' value="' + metaClone[0].defaultValue + '">' + metaClone[0].defaultValue + removeButton + '</li>';

													$( liOptions ).append( addMarkup );
												}
											}
										);
									}
								);
							}
						}
					}
				}
			);
		},
		multipleSelect: function ( variables ) {
			variables.mediaFrame.on(
				'select',
				function () {
					variables.attachment = variables.mediaFrame.state().get( 'selection' ).map(
						function ( attachment ) {
							attachment.toJSON();
							return attachment;
						}
					);

					variables.$removeButton.show().trigger( 'change' );
					qodefInitOptionsUploader.remove( variables.$removeButton );

					var ids = $.map(
						variables.attachment,
						function ( o ) {
							if ( o.attributes.type === 'image' ) {
								return o.id;
							}
						}
					);

					variables.$uploadId.val( ids );
					variables.$thumbImageHolder.find( 'ul' ).empty().trigger( 'change' );

					var variablesAttachmentLength = variables.attachment.length;
					// loop through the array and add image for each attachment.
					for ( var i = 0; i < variablesAttachmentLength; ++i ) {
						if ( variables.attachment[i].attributes.sizes.thumbnail !== undefined ) {
							variables.$thumbImageHolder.find( 'ul' ).append( '<li><img src="' + variables.attachment[i].attributes.sizes.thumbnail.url + '" alt="thumbnail" /></li>' );
						} else {
							variables.$thumbImageHolder.find( 'ul' ).append( '<li><img src="' + variables.attachment[i].attributes.sizes.full.url + '" alt="thumbnail" /></li>' );
						}
					}

					variables.$thumbImageHolder.show().trigger( 'change' );
				}
			);
		},
		singleSelect: function ( variables ) {
			variables.mediaFrame.on(
				'select',
				function () {
					variables.attachment = variables.mediaFrame.state().get( 'selection' ).first().toJSON();

					// write to url field and img tag.
					if ( variables.attachment.hasOwnProperty( 'url' ) && variables.attachment.type === 'image' ) {

						variables.$removeButton.show();
						qodefInitOptionsUploader.remove( variables.$removeButton );

						variables.$uploadId.val( variables.attachment.id );
						variables.$thumbImageHolder.empty();

						if ( variables.attachment.hasOwnProperty( 'sizes' ) && variables.attachment.sizes.thumbnail ) {
							variables.$thumbImageHolder.append( '<img class="qodef-single-image" src="' + variables.attachment.sizes.thumbnail.url + '" alt="thumbnail" />' );
						} else {
							variables.$thumbImageHolder.append( '<img class="qodef-single-image" src="' + variables.attachment.url + '" alt="thumbnail" />' );
						}
						variables.$thumbImageHolder.show().trigger( 'change' );
					}

				}
			);
		},
		fileSelect: function ( variables ) {

			variables.mediaFrame.on(
				'select',
				function () {
					variables.attachment = variables.mediaFrame.state().get( 'selection' ).first().toJSON();

					// write to url field and img tag.
					if ( variables.attachment.hasOwnProperty( 'url' ) && variables.$allowed_type.includes( variables.attachment.type ) ) {

						variables.$removeButton.show();
						qodefInitOptionsUploader.remove( variables.$removeButton );

						variables.$uploadId.val( variables.attachment.id );
						variables.$thumbImageHolder.empty();

						variables.$thumbImageHolder.append(
							'' +
							'<img class="qodef-file-image" src="' + variables.attachment.icon + '" alt="thumbnail" />' +
							'<div class="qodef-file-name">' + variables.attachment.filename + '</div>' +
							''
						);

						variables.$thumbImageHolder.show().trigger( 'change' );
					}

				}
			);
		},
		remove: function ( button ) {
			button.on(
				'tap click',
				function () {
					// remove images and hide it's holder.
					button.siblings( '.qodef-option-thumb' ).hide();
					button.siblings( '.qodef-option-thumb' ).find( 'img' ).attr(
						'src',
						''
					);
					button.siblings( '.qodef-option-thumb' ).find( 'li' ).remove();

					// reset meta fields.
					button.siblings( '.qodef-image-meta-fields' ).find( 'input[type="hidden"]' ).each(
						function () {
							$( this ).val( '' );
						}
					);

					button.hide().trigger( 'change' );
				}
			);
		}
	};

	var qodefDimmensionsGroup = {
		init: function () {
			this.$holder = $( '.qodef-dimensions-fields' );

			const dimmensionSelectors = {
				selectors   : {
					wrapper: '.qodef-dimensions-fields',
					units: {
						wrapper: '.qodef-dimensions-units-wrapper',
						single: '.qodef-dimensions-unit',
						value: '.qodef-dimensions-unit-value',
						selectedClass: 'qodef-dimensions-unit--selected'
					},
					linked: {
						button: '.qodef-dimensions-linked',
						value: '.qodef-dimension-linked-value',
						wrapperActiveClass: 'qodef--dimensions-linked-active'
					},
					dimensions: {
						number: '.qodef-dimension-number'
					}
				}
			};

			if ( this.$holder.length ) {
				this.$holder.each(
					function () {
						qodefDimmensionsGroup.initField( $( this ), dimmensionSelectors );
					}
				);
			}
		},
		initField: function ( thisHolder, fieldSelectors ) {

			$( document ).on(
				'click',
				fieldSelectors.selectors.units.single,
				function ( e ) {
					qodefDimmensionsGroup.unitChange( $( this ), fieldSelectors );
				}
			);

			$( document ).on(
				'click',
				fieldSelectors.selectors.linked.button,
				function ( e ) {
					qodefDimmensionsGroup.linkedChange( $( this ), fieldSelectors );
				}
			);

			$( document ).on(
				'change keyup',
				fieldSelectors.selectors.dimensions.number,
				function ( e ) {
					qodefDimmensionsGroup.numberChange( $( this ), fieldSelectors );
				}
			);
		},
		unitChange: function ( clicked, fieldSelectors ) {
			var unit       = clicked.closest( fieldSelectors.selectors.units.single ),
				wrapper    = unit.closest( fieldSelectors.selectors.units.wrapper ),
				units      = wrapper.find( fieldSelectors.selectors.units.single ),
				valueField = wrapper.find( fieldSelectors.selectors.units.value ).first(),
				value      = unit.data( 'value' );

			units.removeClass( fieldSelectors.selectors.units.selectedClass );
			unit.addClass( fieldSelectors.selectors.units.selectedClass );
			valueField.val( value ).trigger( 'change' );
		},
		linkedChange: function ( clicked, fieldSelectors ) {
			var button      = clicked.closest( fieldSelectors.selectors.linked.button ),
				mainWrapper = button.closest( fieldSelectors.selectors.wrapper ),
				valueField  = button.find( fieldSelectors.selectors.linked.value ),
				value       = valueField.val();

			if ( 'yes' === value ) {
				mainWrapper.removeClass( fieldSelectors.selectors.linked.wrapperActiveClass );
				valueField.val( 'no' );
			} else {
				mainWrapper.addClass( fieldSelectors.selectors.linked.wrapperActiveClass );
				valueField.val( 'yes' );

				mainWrapper.find( fieldSelectors.selectors.dimensions.number ).first().trigger( 'change' );
			}
		},
		numberChange: function ( clicked, fieldSelectors ) {
			var number      = clicked.closest( fieldSelectors.selectors.dimensions.number ),
				mainWrapper = number.closest( fieldSelectors.selectors.wrapper );
			if ( mainWrapper.hasClass( fieldSelectors.selectors.linked.wrapperActiveClass ) ) {
				var numbers = mainWrapper.find( fieldSelectors.selectors.dimensions.number );

				numbers.val( number.val() );
			}
		}
	};

	qodefFramework.qodefDimmensionsGroup = qodefDimmensionsGroup;

})( jQuery );
