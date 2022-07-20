<?php

namespace Roel\WP\Settings\Elements;

use Roel\WP\Settings\Element;

class TextArea extends Element {
	/**
	 * Render the HTML component.
	 *
	 * @since  0.1.0
	 *
	 * @return string   The HTML component.
	 */
	public function render() : string {
		$html  = '<textarea rows="' . esc_attr( $this->settings['rows'] ?? 10 ) . '" ';
		$html .= 'cols="' . esc_attr( $this->settings['cols'] ?? 50 ) . '" id="' . esc_attr( $this->id() ) . '" ';
		$html .= 'name="' . esc_attr( $this->name() ) . '" class="large-text code" ';
		$html .= 'placeholder="' . $this->placeholder() . '" ' . $this->attributes() . '>';
		$html .= $this->value() . '</textarea>';

		$html .= $this->description();

		return $html;
	}

	/**
	 * Get the `<textarea />` placeholder.
	 *
	 * @since  0.1.0
	 *
	 * @return false|string   The `<textarea />` placeholder.
	 */
	public function placeholder() {
		return $this->settings['placeholder'] ?? false;
	}
}
