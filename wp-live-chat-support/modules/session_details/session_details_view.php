<script>
    var wplc_name_override = "admin";
</script>
<div class="wrap wplc_wrap"><h2> <?= $page_title ?></h2>
    <div id="wplc_container">
        <div class="wplc_on_premise_chat_window">
            <div class="wplc_on_premise_chat_window_header">
                <h2><?= __( 'Previous Chat with', 'wp-live-chat-support' ) ?>  <?= esc_html( $session->name ) ?></h2>
            </div>
            <span class='wplc-history__date'><strong><?= __( 'Starting Time:', 'wp-live-chat-support' ) ?></strong>
            <?= date( 'Y-m-d H:i:s', current_time( strtotime( $session->timestamp ) ) ) ?>
        </span>
            <span class='wplc-history__date wplc-history__date-end'><strong><?= __( 'Ending Time:', 'wp-live-chat-support' ) ?> </strong>
            <?= date( 'Y-m-d H:i:s', current_time( strtotime( $session->end_timestamp ) ) ) ?>
        </span>
            <div id='admin_chat_box'>
                <div class='wplc_on_premise_chat_box_user_info'>
                    <div class='wplc_on_premise_chat_box_user_info_avatar'>
                        <img src="//www.gravatar.com/avatar/<?= md5( $session->email ) ?>?d=<?=( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] ) ? 'https' : 'http'?>://ui-avatars.com/api//<?=esc_html($session->avatar_name_alias)?>/64/<?=TCXUtilsHelper::wplc_color_by_string($session->name)?>/fff" class="admin_chat_img"
                             width="50px"/>
                    </div>
                    <div class='wplc_on_premise_chat_box_user_info_details'><?= esc_html( $session->name ) ?>
                        ,(<?= esc_html( $session->email ) ?>)
                        <br>
                        <span class='part1'><b><?= __( "Chat initiated on:", 'wp-live-chat-support' ) ?></b></span>
                        <span class='part2'><?= esc_url( $session->url ) ?></span>
                        <br>
                        <span class='part1'><b><?= __( "Browser:", 'wp-live-chat-support' ) ?></b></span>
                        <span class='part2'> <?= $browser ?> <img src='<?= $browser_image ?>' alt='<?= $browser ?>'
                                                                  title='<?= $browser ?>' align='absmiddle'/></span>
                    </div>
                    <div class='wplc_agent_rating'>
                       <div class="wplc_rating"> <?= $session->getRatingHtml() ?></div>
                        <?php if($session->rating>=0 && strlen($rating_comments)>0) { ?>
                        <div class="wplc_rate_comments">
                            <span class="wplc_rate_comments_header"> Feedback: </span>
                            <span><?=esc_html($rating_comments)?></span>
                        </div>
                        <?php } ?>
                    </div>
					<?php if ( ! empty( $session->custom_fields ) ) { ?>
                        <div class='wplc_on_premise_chat_box_user_custom_fields'>
							<?php foreach ( $session->custom_fields as $cfield ) { ?>
                                <span class='part1'><b><?= esc_html($cfield['name']) ?></b></span>
                                <span class='part2'><?= esc_html( $cfield['value'] ) ?></span><br/>
							<?php } ?>
                        </div>
					<?php } ?>
                </div>
                <div class='admin_chat_box'>
                    <div class='admin_chat_box_inner' id='admin_chat_box_area_1'>
						<?php foreach ( $session_messages as $message ) {
							if ( $message->from == "System notification" ) {
								?>
                                <span class='wplc-system-notification wplc-color-4'>
                                    <?= esc_html($message->get_message()) ?>
                                </span>
								<?php
							} else {
								?>
                                <strong><?= esc_html($message->from) ?>
                                    ( <?= date( 'H:i:s', current_time( strtotime( $message->timestamp ) ) ) ?>
                                    ):</strong>    <span
                                        class="history_chat_message"><?= esc_html($message->get_message()) ?></span>
							<?php }
							?>
                            <br/>
						<?php }
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>