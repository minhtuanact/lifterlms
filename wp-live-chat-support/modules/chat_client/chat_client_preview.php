<call-us
        style=" display: inline-block;
                z-index: 99999;
                font-family: 'Source Sans Pro';
                width: 350px;
                --call-us-form-header-background:<?= $baseColor ?>;
                --call-us-client-text-color:<?= $clientColor ?>;
                --call-us-agent-text-color:<?= $agentColor ?>;
                --call-us-form-height:40vh;"
        id="wp-live-chat-by-3CX"
        channel-url="<?= wplc_protocol_agnostic_url( $channel_url ) ?>"
        files-url=""
        wp-url="<?= wplc_protocol_agnostic_url( $channel_url ) ?>"
        minimized="false"
        animation-style="none"
        party=""
        minimized-style="bubble"
        allow-call="false"
        allow-video="false"
        allow-soundnotifications="false"
        enable-onmobile="false"
        allow-emojis="true"
        enable="true"
        soundnotification-url="https://notificationsounds.com/soundfiles/a86c450b76fb8c371afead6410d55534/file-sounds-1108-slow-spring-board.mp3"
        popout="false"
        facebook-integration-url=""
        twitter-integration-url=""
        email-integration-url=""
        ignore-queueownership="false"
        enable-poweredby="true"
        authentication="none"
        show-typing-indicator="false"
        operator-name="Preview"
        show-operator-actual-name="false"
        window-icon=""
        button-icon=""
        channel="wp"
        channel-secret="preview_secret"
        aknowledge-received="false"
        gdpr-enabled="false"
        gdpr-message=""
        files-enabled="true"
        rating-enabled="fasle"
        departments-enabled="false"
>
</call-us>