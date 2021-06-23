<?php

namespace WebpConverter\Conversion\Method;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;

/**
 * Initializes image conversion using active image conversion method.
 */
class MethodIntegrator extends PluginAccessAbstract implements PluginAccessInterface {

	/**
	 * Initializes converting source images using active and set conversion method.
	 *
	 * @param string[] $paths Server paths for source images.
	 *
	 * @return array[]|null Results data of conversion.
	 */
	public function init_conversion( array $paths ) {
		if ( ! $method = $this->get_method_used() ) {
			return null;
		}

		$method->convert_paths( $paths );
		return [
			'errors' => apply_filters( 'webpc_convert_errors', $method->get_errors() ),
			'size'   => [
				'before' => $method->get_size_before(),
				'after'  => $method->get_size_after(),
			],
		];
	}

	/**
	 * Returns active and set conversion method.
	 *
	 * @return MethodInterface|null Object of conversion method.
	 */
	private function get_method_used() {
		if ( apply_filters( 'webpc_server_errors', [], true ) ) {
			return null;
		}

		$method_key = $this->get_plugin()->get_settings()['method'] ?? '';
		$methods    = ( new MethodFactory() )->get_methods_objects();
		foreach ( $methods as $method_name => $method ) {
			if ( $method_key === $method_name ) {
				$method->set_plugin( $this->get_plugin() );
				return $method;
			}
		}
		return null;
	}
}
