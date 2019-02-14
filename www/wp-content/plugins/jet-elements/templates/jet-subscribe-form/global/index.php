<?php
/**
 * Subscribe Form main template
 */

$classes_list[] = 'jet-subscribe-form';

$submit_button_text = $this->get_settings( 'submit_button_text' );
$submit_placeholder = $this->get_settings( 'submit_placeholder' );
$layout             = $this->get_settings( 'layout' );
$button_icon        = $this->get_settings( 'button_icon' );
$use_icon           = $this->get_settings( 'add_button_icon' );

$classes_list[] = 'jet-subscribe-form--' . $layout . '-layout';

$classes = implode( ' ', $classes_list );

$icon_html = '';

if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
	$icon_html = sprintf( '<i class="jet-subscribe-form__submit-icon %s"></i>', $button_icon );
}

?>
<div class="<?php echo $classes; ?>">
	<form method="POST" action="#" class="jet-subscribe-form__form">
		<div class="jet-subscribe-form__input-group">
			<input class="jet-subscribe-form__input" type="email" name="jet-subscribe-mail" value="" placeholder="<?php echo $submit_placeholder; ?>">
			<?php echo sprintf( '<a class="jet-subscribe-form__submit elementor-button elementor-size-md" href="#">%s<span class="jet-subscribe-form__submit-text">%s</span></a>', $icon_html, $submit_button_text ); ?>
		</div>
		<div class="jet-subscribe-form__message"><div class="jet-subscribe-form__message-inner"><span></span></div></div>
	</form>
</div>
