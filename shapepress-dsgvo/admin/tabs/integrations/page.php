<h1><?php _e('Integrations', 'shapepress-dsgvo'); ?></h1>
<hr>

<form method="post" action="<?= SPDSGVOIntegrationsAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= SPDSGVOIntegrationsAction::getActionName() ?>">
    <?php wp_nonce_field( SPDSGVOIntegrationsAction::getActionName(). '-nonce' ); ?>

	<table class="lw-form-table">
		<tbody>

			<?php $integrations = SPDSGVOIntegration::getAllIntegrations(FALSE); ?>
			<?php if(count($integrations) === 0): ?>

				<tr>
					<th scope="row"><?php _e('No integrations installed','shapepress-dsgvo')?></th>
					<td></td>
				</tr>

			<?php else: ?>

				<?php foreach($integrations as $key => $integration): ?>

					<tr>
						<th scope="row"><?= $integration->title ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?= $integration->title ?></span>
								</legend>

								<label for="<?= $integration->slug ?>">
									<input name="integrations[<?= $integration->slug ?>]" type="checkbox" id="<?= $integration->slug ?>" value="1" <?= (SPDSGVOIntegration::isEnabled($integration->slug))? ' checked ' : '';  ?>>
								</label>
							</fieldset>
						</td>
					</tr>

				<?php endforeach; ?>
			<?php endif; ?>

		</tbody>
	</table>

	<?php submit_button(); ?>
</form>
