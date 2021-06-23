<?php
$true_page = 'userway';

function usw_userway_settings()
{
    add_options_page('UserWay', 'UserWay', 'manage_options', 'userway', 'usw_userway_settings_page');
}

add_action('admin_menu', 'usw_userway_settings');

/**
 *
 */
function usw_userway_settings_page()
{
    global $wpdb;

    $tableName = $wpdb->prefix . 'userway';
    $accountDb = $wpdb->get_row("SELECT * FROM {$tableName} LIMIT 1");

    $url = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);

    $widgetUrl = "https://userway.org/apps/wp?storeUrl={$url}";
    if ($accountDb) {
        if (isset($accountDb->account_id)) {
            $widgetUrl .= "&account_id={$accountDb->account_id}";
        }
        if (isset($accountDb->state)) {
            $state = $accountDb->state ? 'true' : 'false';
            $widgetUrl .= "&active=${state}";
        }
    }

    ?>
    <div>
        <iframe
                id="userway-frame"
                src="<?php echo $widgetUrl ?>"
                title="UserWay Widget"
                width="100%"
                height="1180px"
                style="border: none;"
        >
        </iframe>
        <script type="text/javascript">
            const MESSAGE_ACTION_TOGGLE = 'WIDGET_TOGGLE';
            const MESSAGE_ACTION_SIGNUP = "WIDGET_SIGNUP";
            const MESSAGE_ACTION_SIGNIN = "WIDGET_SIGNIN";

            const request = (data) => {
                return jQuery.when(
                    jQuery.ajax({
                        url: '/wp-json/userway/v1/save',
                        type: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify(data),
                    })
                )
            };

            const isPostMessageValid = (postMessage) => {
                return postMessage.data !== undefined
                    && postMessage.data.action
                    && postMessage.data.account !== undefined
                    && postMessage.data.state !== undefined
                    && [MESSAGE_ACTION_TOGGLE, MESSAGE_ACTION_SIGNUP, MESSAGE_ACTION_SIGNIN].includes(postMessage.data.action)
            }

            jQuery(document).ready(function () {
                const selector = document.getElementById('userway-frame');
                const frameContentWindow = selector.contentWindow;
                const {url} = selector.dataset;
                window.addEventListener('message', postMessage => {
                    if (postMessage.source !== frameContentWindow || !isPostMessageValid(postMessage)) {
                        return;
                    }

                    const requestPayload = {
                        account: postMessage.data.account,
                        state: postMessage.data.state,
                    }

                    request(requestPayload)
                        .then(res => console.log(res))
                        .catch(err => console.error(err));
                });
            });
        </script>
    </div>
    <?php
}
