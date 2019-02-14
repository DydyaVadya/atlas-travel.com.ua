<?php
/**
 * Pricing table action button
 */
?>
<a class="elementor-button elementor-size-md pricing-table-button btn btn-accent-2" href="<?php echo $this->__html( 'button_url' ); ?>"><?php

	$position = $this->get_settings( 'button_icon_position' );
	$icon     = $this->get_settings( 'add_button_icon' );

	if ( $icon && 'left' === $position ) {
		echo $this->__html( 'button_icon', '<i class="button-icon %s"></i>' );
	}

	echo $this->__html( 'button_text' );

	if ( $icon && 'right' === $position ) {
		echo $this->__html( 'button_icon', '<i class="button-icon %s"></i>' );
	}

?></a>
