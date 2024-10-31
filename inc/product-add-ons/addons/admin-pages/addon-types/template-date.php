<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Date Template
 *
 * @var object $addon
 * @var string $addon_type
 * @var int $x
 */

$years_options = array_combine( range( gmdate( 'Y', strtotime( '+50 years' ) ), 1900 ), range( gmdate( 'Y', strtotime( '+50 years' ) ), 1900 ) );

?>

<div class="qodef-fields">

	<?php
	qode_product_extra_options_for_woocommerce_template_part(
		'product-add-ons',
		'addons/admin-pages/addons-view/templates/template',
		'option-common-fields',
		array(
			'x'          => $x,
			'addon_type' => $addon_type,
			'addon'      => $addon,
		)
	);
	?>

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Date format', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Choose date format', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'option-date-format',
						'name'    => 'options[date_format][]',
						'type'    => 'select',
						'value'   => $addon->get_option( 'date_format', $x, 'd/m/Y', false ),
						'options' => array(
							'd/m/Y' => esc_html__( 'Day / Month / Year', 'qode-product-extra-options-for-woocommerce' ),
							'm/d/Y' => esc_html__( 'Month / Day / Year', 'qode-product-extra-options-for-woocommerce' ),
							'd.m.Y' => esc_html__( 'Day . Month . Year', 'qode-product-extra-options-for-woocommerce' ),
						),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Year', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set start and end year', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-increase-or-decrease">
							<div class="qodef-field-wrapper qodef-filed-type--text qodef-start-year">
								<small>
								<?php
									echo esc_html__( 'Start year', 'qode-product-extra-options-for-woocommerce' );
								?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'      => 'option-start-year-' . $x,
										'name'    => 'options[start_year][]',
										'type'    => 'select',
										'value'   => esc_attr( $addon->get_option( 'start_year', $x, gmdate( 'Y' ), false ) ),
										'options' => $years_options,
									),
									true
								);
								?>
							</div>
							<div class="qodef-field-wrapper qodef-filed-type--text qodef-end-year">
								<small>
								<?php
									echo esc_html__( 'End year', 'qode-product-extra-options-for-woocommerce' );
								?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'      => 'option-end-year-' . $x,
										'name'    => 'options[end_year][]',
										'type'    => 'select',
										'value'   => esc_attr( $addon->get_option( 'end_year', $x, gmdate( 'Y' ), false ) ),
										'options' => $years_options,
									),
									true
								);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Default date', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Choose default date', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'option-date-default-' . $x,
						'name'    => 'options[date_default][' . $x . ']',
						'type'    => 'select',
						'value'   => $addon->get_option( 'date_default', $x, '', false ),
						'options' => array(
							''         => esc_html__( 'None', 'qode-product-extra-options-for-woocommerce' ),
							'today'    => esc_html__( 'Current day', 'qode-product-extra-options-for-woocommerce' ),
							'tomorrow' => esc_html__( 'Current day', 'qode-product-extra-options-for-woocommerce' ) . ' + 1',
							'specific' => esc_html__( 'Set a specific day', 'qode-product-extra-options-for-woocommerce' ),
							'interval' => esc_html__( 'Set a time interval from current day', 'qode-product-extra-options-for-woocommerce' ),
							'firstavl' => esc_html__( 'First available day', 'qode-product-extra-options-for-woocommerce' ),
						),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_default][' . $x . ']":"specific"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Specific day', 'qode-product-extra-options-for-woocommerce' ) . esc_html( ':' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set precise day', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-date-default-day-' . $x,
						'name'  => 'options[date_default_day][]',
						'type'  => 'datepicker',
						'value' => $addon->get_option( 'date_default_day', $x, '', false ),
						'data'  => array(
							'date-format' => 'yy-mm-dd',
						),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_default][' . $x . ']":"interval"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'For default date, calculate', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Set value to calculate from current day specified amount of days or months', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-increase-or-decrease">
							<div class="qodef-field-wrapper qodef-filed-type--text qodef-number-amount">
								<small>
								<?php
									echo esc_html__( 'Number Amount', 'qode-product-extra-options-for-woocommerce' );
								?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'      => 'option-date-default-interval-num-' . $x,
										'name'    => 'options[date_default_calculate_num][]',
										'type'    => 'select',
										'value'   => $addon->get_option( 'date_default_calculate_num', $x, '', false ),
										'options' => array(
											0,
											1,
											2,
											3,
											4,
											5,
											6,
											7,
											8,
											9,
											10,
											11,
											12,
											13,
											14,
											15,
											16,
											17,
											18,
											19,
											20,
											21,
											22,
											23,
											24,
											25,
											26,
											27,
											28,
											29,
											30,
											31,
										),
									),
									true
								);
								?>
							</div>
							<div class="qodef-field-wrapper qodef-filed-type--text qodef-number-range">
								<small>
								<?php
									echo esc_html__( 'From current day', 'qode-product-extra-options-for-woocommerce' );
								?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'      => 'option-date-default-interval-type-' . $x,
										'name'    => 'options[date_default_calculate_type][]',
										'type'    => 'select',
										'value'   => $addon->get_option( 'date_default_calculate_type', $x, '', false ),
										'options' => array(
											'days'   => esc_html__( 'Days', 'qode-product-extra-options-for-woocommerce' ),
											'months' => esc_html__( 'Months', 'qode-product-extra-options-for-woocommerce' ),
											'years'  => esc_html__( 'Years', 'qode-product-extra-options-for-woocommerce' ),
										),
									),
									true
								);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Selectable dates', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Choose date rule limit', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				$selectable_dates_option = $addon->get_option( 'selectable_dates', $x, '', false );
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'option-selectable-dates-' . $x,
						'name'    => 'options[selectable_dates][' . $x . ']',
						'type'    => 'select',
						'value'   => $selectable_dates_option,
						'options' => array(
							''       => esc_html__( 'Set no limits', 'qode-product-extra-options-for-woocommerce' ),
							'days'   => esc_html__( 'Set a range of days', 'qode-product-extra-options-for-woocommerce' ),
							'date'   => esc_html__( 'Set a specific date range', 'qode-product-extra-options-for-woocommerce' ),
							'before' => esc_html__( 'Disable dates previous to current day', 'qode-product-extra-options-for-woocommerce' ),
						),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[selectable_dates][' . $x . ']":"days"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Selectable days ranges', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set days range limit', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-increase-or-decrease">
							<div class="qodef-field-wrapper qodef-filed-type--text">
								<small>
								<?php
									echo esc_html__( 'day min', 'qode-product-extra-options-for-woocommerce' );
								?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'    => 'option-days_min-' . $x,
										'name'  => 'options[days_min][]',
										'type'  => 'text',
										'value' => $addon->get_option( 'days_min', $x, '', false ),
									),
									true
								);
								?>
							</div>
							<div class="qodef-field-wrapper qodef-filed-type--text">
								<small>
								<?php
									echo esc_html__( 'day max', 'qode-product-extra-options-for-woocommerce' );
								?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'    => 'option-days_max-' . $x,
										'name'  => 'options[days_max][]',
										'type'  => 'text',
										'value' => $addon->get_option( 'days_max', $x, '', false ),
									),
									true
								);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12  qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[selectable_dates][' . $x . ']":"date"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Selectable date ranges', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set date range limit', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-increase-or-decrease">
							<div class="qodef-field-wrapper qodef-filed-type--text">
								<small>
									<?php
									echo esc_html__( 'date min', 'qode-product-extra-options-for-woocommerce' );
									?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'    => 'option-date_min-' . $x,
										'name'  => 'options[date_min][]',
										'type'  => 'datepicker',
										'value' => $addon->get_option( 'date_min', $x, '', false ),
										'data'  => array(
											'date-format' => 'yy-mm-dd',
										),
									),
									true
								);
								?>
							</div>
							<div class="qodef-field-wrapper qodef-filed-type--text">
								<small>
									<?php
									echo esc_html__( 'date max', 'qode-product-extra-options-for-woocommerce' );
									?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'    => 'option-date_max-' . $x,
										'name'  => 'options[date_max][]',
										'type'  => 'datepicker',
										'value' => $addon->get_option( 'date_max', $x, '', false ),
										'data'  => array(
											'date-format' => 'yy-mm-dd',
										),
									),
									true
								);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Enable / disable specific days', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Enable this option in order to configure corresponding rules', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-enable-disable-days-' . $x,
						'name'  => 'options[enable_disable_days][' . $x . ']',
						'type'  => 'yesno-radio',
						'value' => $addon->get_option( 'enable_disable_days', $x, 'no', false ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[enable_disable_days][' . $x . ']":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Rule type', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Choose rule type', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-rule-conditions-wrapper">
							<div id="qodef-disable-date-rules-<?php echo esc_attr( $x ); ?>" class="qodef-disable-date-rules">
								<div class="qodef-field-wrapper qodef-filed-type--text qodef-rules-type">
									<small>
									<?php
										echo esc_html__( 'these dates in calendar', 'qode-product-extra-options-for-woocommerce' );
									?>
									</small>
									<?php
									qode_product_extra_options_for_woocommerce_get_field(
										array(
											'id'      => 'option-enable-disable-days-type-' . $x,
											'name'    => 'options[enable_disable_date_rules][]',
											'type'    => 'select',
											'value'   => $addon->get_option( 'enable_disable_date_rules', $x, 'enable', false ),
											'options' => array(
												'enable'  => esc_html__( 'Enable', 'qode-product-extra-options-for-woocommerce' ),
												'disable' => esc_html__( 'Disable', 'qode-product-extra-options-for-woocommerce' ),
											),
										),
										true
									);
									?>
								</div>

								<div id="qodef-date-rules-<?php echo esc_attr( $x ); ?>" class="qodef-date-rules">
									<div class="qodef-date-rules-container">
										<?php
										$date_rules_count = count( (array) $addon->get_option( 'date_rule_what', $x, '', false ) );
										for ( $y = 0; $y < $date_rules_count; $y++ ) :
											$date_rule_what = $addon->get_option( 'date_rule_what', $x, 'enable', false )[ $y ];
											?>
											<div class="qodef-rule">
												<div class="qodef-field-what qodef-field-wrapper">
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'      => 'date-rule-what-' . $x . '-' . $y,
															'name'    => 'options[date_rule_what][' . $x . '][' . $y . ']',
															'type'    => 'select',
															'value'   => $date_rule_what,
															'options' => array(
																'days'     => esc_html__( 'Days', 'qode-product-extra-options-for-woocommerce' ),
																'daysweek' => esc_html__( 'Days of the week', 'qode-product-extra-options-for-woocommerce' ),
																'months'   => esc_html__( 'Months', 'qode-product-extra-options-for-woocommerce' ),
																'years'    => esc_html__( 'Years', 'qode-product-extra-options-for-woocommerce' ),
															),
														),
														true,
														false
													);
													?>

													<a class="qodef-delete-rule" href="javascript: void(0)" rel="noopener noreferrer"><?php qode_product_extra_options_for_woocommerce_render_svg_icon( 'trash' ); ?></a>

												</div>

												<div class="qodef-field-days qodef-field-wrapper qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_rule_what][' . $x . '][' . $y . ']":"days"' ); ?>}">
													<small>
														<?php
														echo esc_html__( 'specific day', 'qode-product-extra-options-for-woocommerce' );
														?>
													</small>
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'          => 'date-rule-value-days-' . $x . '-' . $y,
															'name'        => 'options[date_rule_value_days][' . $x . '][' . $y . ']',
															'type'        => 'datepicker',
															'value'       => isset( $addon->get_option( 'date_rule_value_days', $x, '', false )[ $y ] ) ? $addon->get_option( 'date_rule_value_days', $x, '', false )[ $y ] : '',
															'date_format' => 'yy-mm-dd',
														),
														true,
														false
													);
													?>
												</div>
												<div class="qodef-field-daysweek qodef-field-wrapper qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_rule_what][' . $x . '][' . $y . ']":"daysweek"' ); ?>}">
													<small>
														<?php
														echo esc_html__( 'day(s) in week', 'qode-product-extra-options-for-woocommerce' );
														?>
													</small>
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'       => 'date-rule-value-daysweek-' . $x . '-' . $y,
															'name'     => 'options[date_rule_value_daysweek][' . $x . '][' . $y . ']',
															'type'     => 'select',
															'multiple' => true,
															'options'  => array(
																'1' => esc_html__( 'Monday', 'qode-product-extra-options-for-woocommerce' ),
																'2' => esc_html__( 'Tuesday', 'qode-product-extra-options-for-woocommerce' ),
																'3' => esc_html__( 'Wednesday', 'qode-product-extra-options-for-woocommerce' ),
																'4' => esc_html__( 'Thursday', 'qode-product-extra-options-for-woocommerce' ),
																'5' => esc_html__( 'Friday', 'qode-product-extra-options-for-woocommerce' ),
																'6' => esc_html__( 'Saturday', 'qode-product-extra-options-for-woocommerce' ),
																'0' => esc_html__( 'Sunday', 'qode-product-extra-options-for-woocommerce' ),
															),
															'value'    => isset( $addon->get_option( 'date_rule_value_daysweek', $x, '', false )[ $y ] ) ? $addon->get_option( 'date_rule_value_daysweek', $x, '', false )[ $y ] : '',
														),
														true,
														false
													);
													?>
												</div>

												<div class="qodef-field-months qodef-field-wrapper qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_rule_what][' . $x . '][' . $y . ']":"months"' ); ?>}">
													<small>
														<?php
														echo esc_html__( 'month(s)', 'qode-product-extra-options-for-woocommerce' );
														?>
													</small>
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'       => 'date-rule-value-months-' . $x . '-' . $y,
															'name'     => 'options[date_rule_value_months][' . $x . '][' . $y . ']',
															'type'     => 'select',
															'multiple' => true,
															'options'  => qode_product_extra_options_for_woocommerce_get_select_type_options_pool( 'months', false ),
															'value'    => isset( $addon->get_option( 'date_rule_value_months', $x, '', false )[ $y ] ) ? $addon->get_option( 'date_rule_value_months', $x, '', false )[ $y ] : '',
														),
														true,
														false
													);
													?>
												</div>

												<div class="qodef-field-years qodef-field-wrapper qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_rule_what][' . $x . '][' . $y . ']":"years"' ); ?>}">
													<small>
														<?php
														echo esc_html__( 'year(s)', 'qode-product-extra-options-for-woocommerce' );
														?>
													</small>
													<?php
													$years = array();
													$datey = gmdate( 'Y' );
													for ( $yy = $datey; $yy < $datey + 10; $yy++ ) {
														$years[ $yy ] = $yy;
													}
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'       => 'date-rule-value-years' . $x . '-' . $y,
															'name'     => 'options[date_rule_value_years][' . $x . '][' . $y . ']',
															'type'     => 'select',
															'multiple' => true,
															'options'  => $years,
															'value'    => isset( $addon->get_option( 'date_rule_value_years', $x, '' )[ $y ] ) ? $addon->get_option( 'date_rule_value_years', $x, '', false )[ $y ] : '',
														),
														true,
														false
													);
													?>
												</div>
											</div>
										<?php endfor; ?>
									</div>

									<div id="qodef-add-date-rule" class="qodef-add-date-rule">
										<a class="qodef-btn qodef-btn-solid qodef-add-rule-button" href="javascript: void(0)"><?php echo esc_html__( 'Add rule', 'qode-product-extra-options-for-woocommerce' ); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Show time selector', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Enable this option in order to configure time interval and time slot options', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-show-time-selector-' . $x,
						'name'  => 'options[show_time_selector][' . $x . ']',
						'type'  => 'yesno-radio',
						'value' => $addon->get_option( 'show_time_selector', $x, 'no', false ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[show_time_selector][' . $x . ']":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Time slots', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Enable / disable time slots', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-enable-time-slots-' . $x,
						'name'  => 'options[enable_time_slots][' . $x . ']',
						'type'  => 'yesno-radio',
						'value' => $addon->get_option( 'enable_time_slots', $x, 'no', false ),
					),
					true,
					false
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[enable_time_slots][' . $x . ']":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Rule type', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Enable / disable rule type', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-slot-conditions-wrapper">
							<div id="qodef-enable-disable-time-slots-<?php echo esc_attr( $x ); ?>" class="qodef-time-slots-container">
								<div class="qodef-field-wrapper qodef-filed-type--text qodef-rules-type">
									<small>
										<?php
										echo esc_html__( 'the following time slot(s)', 'qode-product-extra-options-for-woocommerce' );
										?>
									</small>
									<?php
									qode_product_extra_options_for_woocommerce_get_field(
										array(
											'id'      => 'option-time-slots-type-' . $x,
											'name'    => 'options[time_slots_type][]',
											'type'    => 'select',
											'value'   => $addon->get_option( 'time_slots_type', $x, 'enable', false ),
											'options' => array(
												'enable'  => esc_html__( 'Enable', 'qode-product-extra-options-for-woocommerce' ),
												'disable' => esc_html__( 'Disable', 'qode-product-extra-options-for-woocommerce' ),
											),
										),
										true,
										false
									);
									?>
								</div>

								<div id="qodef-time-slots-<?php echo esc_attr( $x ); ?>" class="qodef-time-slots">
									<div class="qodef-time-rules-container">
										<?php
										$time_slots_count = count( (array) $addon->get_option( 'time_slot_from', $x, '', false ) );
										for ( $y = 0; $y < $time_slots_count; $y++ ) :
											?>
										<div class="qodef-slot">

											<a class="qodef-delete-slot" href="javascript: void(0)" rel="noopener noreferrer"><?php qode_product_extra_options_for_woocommerce_render_svg_icon( 'trash' ); ?></a>

											<span class="qodef-time-slot-from-text">
											<?php
												echo esc_html__( 'From', 'qode-product-extra-options-for-woocommerce' );
											?>
											</span>

											<div class="qodef-time-slot-from-container">
												<div class="qodef-field-from qodef-field-wrapper">
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'      => 'time-slot-from-' . $x . '-' . $y,
															'name'    => 'options[time_slot_from][' . $x . '][]',
															'type'    => 'select',
															'value'   => isset( $addon->get_option( 'time_slot_from', $x, '', false )[ $y ] ) ? $addon->get_option( 'time_slot_from', $x, '', false )[ $y ] : '',
															'options' => array(
																'1'  => '01',
																'2'  => '02',
																'3'  => '03',
																'4'  => '04',
																'5'  => '05',
																'6'  => '06',
																'7'  => '07',
																'8'  => '08',
																'9'  => '09',
																'10' => '10',
																'11' => '11',
																'12' => '12',
															),
														),
														true,
														false
													);
													?>
												</div>

												<span class="qodef-time-slot-hour-separator">:</span>

												<div class="qodef-field-from-min qodef-field-wrapper">
													<?php
													$minutes_array = array();
													for ( $mn = 0; $mn < 60; $mn++ ) {
														$minutes_array[ $mn ] = str_pad( $mn, 2, '0', STR_PAD_LEFT );
													}
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'      => 'time-slot-from-min-' . $x . '-' . $y,
															'name'    => 'options[time_slot_from_min][' . $x . '][]',
															'type'    => 'select',
															'value'   => isset( $addon->get_option( 'time_slot_from_min', $x, '', false )[ $y ] ) ? $addon->get_option( 'time_slot_from_min', $x, '', false )[ $y ] : '',
															'options' => $minutes_array,
														),
														true,
														false
													);
													?>
												</div>

												<div class="qodef-field-type qodef-field-wrapper">
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'      => 'time-slot-from-type-' . $x . '-' . $y,
															'name'    => 'options[time_slot_from_type][' . $x . '][]',
															'type'    => 'select',
															'value'   => isset( $addon->get_option( 'time_slot_from_type', $x, '', false )[ $y ] ) ? $addon->get_option( 'time_slot_from_type', $x, '', false )[ $y ] : '',
															'options' => array(
																'am' => 'am',
																'pm' => 'pm',
															),
														),
														true,
														false
													);
													?>
												</div>
											</div>

											<span class="qodef-time-slot-to-text">
											<?php
												echo esc_html__( 'To', 'qode-product-extra-options-for-woocommerce' );
											?>
											</span>

											<div class="qodef-time-slot-to-container">
												<div class="qodef-field-to qodef-field-wrapper">
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'      => 'time-slot-to-' . $x . '-' . $y,
															'name'    => 'options[time_slot_to][' . $x . '][]',
															'type'    => 'select',
															'value'   => isset( $addon->get_option( 'time_slot_from', $x, '', false )[ $y ] ) ? $addon->get_option( 'time_slot_from', $x, '', false )[ $y ] : '',
															'options' => array(
																'1'  => '01',
																'2'  => '02',
																'3'  => '03',
																'4'  => '04',
																'5'  => '05',
																'6'  => '06',
																'7'  => '07',
																'8'  => '08',
																'9'  => '09',
																'10' => '10',
																'11' => '11',
																'12' => '12',
															),
														),
														true,
														false
													);
													?>
												</div>

												<span class="qodef-time-slot-hour-separator">:</span>

												<div class="qodef-field-to-min qodef-field-wrapper">
													<?php
													$minutes_array = array();
													for ( $mn = 0; $mn < 60; $mn++ ) {
														$minutes_array[ $mn ] = str_pad( $mn, 2, '0', STR_PAD_LEFT );
													}
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'      => 'time-slot-to-min-' . $x . '-' . $y,
															'name'    => 'options[time_slot_to_min][' . $x . '][]',
															'type'    => 'select',
															'value'   => isset( $addon->get_option( 'time_slot_from_min', $x, '', false )[ $y ] ) ? $addon->get_option( 'time_slot_from_min', $x, '', false )[ $y ] : '',
															'options' => $minutes_array,
														),
														true,
														false
													);
													?>
												</div>

												<div class="qodef-field-to-type qodef-field-wrapper">
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'      => 'time-slot-to-type-' . $x . '-' . $y,
															'name'    => 'options[time_slot_to_type][' . $x . '][]',
															'type'    => 'select',
															'value'   => isset( $addon->get_option( 'time_slot_to_type', $x, '', false )[ $y ] ) ? $addon->get_option( 'time_slot_to_type', $x, '', false )[ $y ] : '',
															'options' => array(
																'am' => 'am',
																'pm' => 'pm',
															),
														),
														true,
														false
													);
													?>
												</div>
											</div>
										</div> <!--close qodef-slot-->

										<?php endfor; ?>

									</div> <!--close qodef-time-rules-container-->

									<div id="qodef-add-time-slot" class="qodef-add-time-slot">
										<a class="qodef-btn qodef-btn-solid qodef-add-time-slot-button" href="javascript: void(0)"><?php echo esc_html__( 'Add time slot', 'qode-product-extra-options-for-woocommerce' ); ?></a>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[show_time_selector][' . $x . ']":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Time interval', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set time interval up to 30 sconds, minutes or hours', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-increase-or-decrease">
							<div class="qodef-field-wrapper qodef-filed-type--text qodef-time-interval">
								<small class="option-price-method">
									<?php
									echo esc_html__( 'time', 'qode-product-extra-options-for-woocommerce' );
									?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'      => 'time-interval-' . $x,
										'name'    => 'options[time_interval][' . $x . ']',
										'type'    => 'select',
										'value'   => $addon->get_option( 'time_interval', $x, '10', false ),
										'options' => array(
											'1'  => '1',
											'2'  => '2',
											'3'  => '3',
											'4'  => '4',
											'5'  => '5',
											'6'  => '6',
											'7'  => '7',
											'8'  => '8',
											'9'  => '9',
											'10' => '10',
											'11' => '11',
											'12' => '12',
											'13' => '13',
											'14' => '14',
											'15' => '15',
											'16' => '16',
											'17' => '17',
											'18' => '18',
											'19' => '19',
											'20' => '20',
											'21' => '21',
											'22' => '22',
											'23' => '23',
											'24' => '24',
											'25' => '25',
											'26' => '26',
											'27' => '27',
											'28' => '28',
											'29' => '29',
											'30' => '30',
										),
									),
									true,
									false
								);
								?>
							</div>

							<div class="qodef-field-wrapper qodef-filed-type--text qodef-time-type-interval">
								<small>
									<?php
									echo esc_html__( 'interval tipe', 'qode-product-extra-options-for-woocommerce' );
									?>
								</small>
								<?php
								qode_product_extra_options_for_woocommerce_get_field(
									array(
										'id'      => 'time-interval-type-' . $x,
										'name'    => 'options[time_interval_type][' . $x . ']',
										'type'    => 'select',
										'value'   => $addon->get_option( 'time_interval_type', $x, 'minutes', false ),
										'options' => array(
											'seconds' => esc_html__( 'Seconds', 'qode-product-extra-options-for-woocommerce' ),
											'minutes' => esc_html__( 'Minutes', 'qode-product-extra-options-for-woocommerce' ),
											'hours'   => esc_html__( 'Hours', 'qode-product-extra-options-for-woocommerce' ),
										),
									),
									true,
									false
								);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Required', 'qode-product-extra-options-for-woocommerce' ) . esc_html( ':' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Enable to make this option mandatory for users', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-required-' . $x,
						'name'  => 'options[required][' . $x . ']',
						'type'  => 'yesno-radio',
						'value' => $addon->get_option( 'required', $x, 'no', false ),
					),
					true,
					false
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

</div>
