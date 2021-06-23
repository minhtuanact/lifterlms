<?php

namespace WebpConverter\Action;

use WebpConverter\PluginAccessAbstract;
use WebpConverter\PluginAccessInterface;
use WebpConverter\HookableInterface;
use WebpConverter\Conversion\Media\Attachment;

/**
 * Initializes conversion of all image sizes for attachment.
 */
class ConvertAttachment extends PluginAccessAbstract implements PluginAccessInterface, HookableInterface {

	/**
	 * Integrates with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'webpc_convert_attachment', [ $this, 'convert_files_by_attachment' ] );
	}

	/**
	 * Converts all sizes of attachment to output formats.
	 *
	 * @param int $post_id ID of attachment.
	 *
	 * @return void
	 * @internal
	 */
	public function convert_files_by_attachment( int $post_id ) {
		$attachment = new Attachment();
		$attachment->set_plugin( $this->get_plugin() );

		do_action( 'webpc_convert_paths', $attachment->get_attachment_paths( $post_id ) );
	}
}
