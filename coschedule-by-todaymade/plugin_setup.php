<?php
/** @noinspection PhpUndefinedFunctionInspection */
if ( current_user_can( 'manage_options' ) !== true ) {
    include( plugin_dir_path( __FILE__ ) . '_access-denied.php' );
    die;
  }
?>
<style>
  @media only screen and (max-width: 960px) {
    .iframe-container iframe {
      width: 50%;
    }
  }

  .plugin-setup-container {
    display: grid;
    justify-content: center;
    align-content: center;
    gap: 4px;
    grid-auto-flow: column;
  }

  .item-center {
    display: grid;
    grid-auto-flow: column;
    gap: 4px;
    align-items: center;
    justify-items: center;
  }

  .iframe-container iframe {
    top: 0;
    left: 0;
    position: absolute;
    width: 100%;
  }

  .loading-message {
    padding-top: 50px;
  }

  .loader {
    border: 8px solid #e6e6e6; /* gray-2 */
    border-top: 8px solid #db8773; /* light-orange */
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 2s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>

<div class="plugin-setup-container">
  <div id="pluginLoading">
    <div class="item-center">
      <h1 class="loading-message">
        Plugin setup is loading ...
      </h1>
    </div>
    <div class="item-center">
      <div class="loader"></div>
    </div>
  </div>
  <div class="item-center iframe-container">
    <!-- IFRAME goes here -->
  </div>
</div>

<script>
  // NOTE: ajaxurl is a global variable that points to admin-ajax.php
  /* global ajaxurl */
  window.addEventListener('load', () => {
    const APP_URL = 'https://app.coschedule.com';

    function debounce(func, wait, immediate) {
      let timeout;

      return function(...args) {
        const context = this;
        const later = function() {
          timeout = null;
          if (!immediate) {
            func.apply(context, args);
          }
        };

        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);

        if (callNow) {
          func.apply(context, args);
        }
      };
    }

    class CoScheduleWP {
      static async setToken(wordpressSiteKey, calendarId, wordpressSiteId) {
        return new Promise((resolve, reject) => {
          jQuery.post(ajaxurl, {
            action: 'tm_aj_set_token',
            token: wordpressSiteKey,
            calendar_id: calendarId,
            wordpress_site_id: wordpressSiteId,
          })
          .done(resolve)
          .fail(reject);
        })
      }

      static async getBlogInfo() {
        return new Promise((resolve, reject) => {
          jQuery.ajax({
            url: ajaxurl,
            data: {
              action: 'tm_aj_get_bloginfo',
            },
            dataType: 'json',
          })
          .done(resolve)
          .fail(reject);
        })
      }

      static getIframeSrc() {
        return `${APP_URL}/wp-plugin-activate?siteUrl=${encodeURIComponent('<?php echo get_site_url(); ?>')}`;
      }

      static navigateToCalendar() {
        window.location.replace('<?php echo admin_url( 'admin.php?page=tm_coschedule_calendar' ) ?>');
      }
    }

    class EventTypeActions {
      static async pluginActivationReady() {
        try {
          const blogInfo = await CoScheduleWP.getBlogInfo();
          notifyIframe({ action: 'connect', payload: { blogInfo } })
        } catch(err) {
          console.log('ERROR: CoScheduleWP.getBlogInfo failed:', (err && err.stack) || err);
        }
      }

      static async pluginActivationSuccess(payload) {
        try {
          const { wordpressSiteKey, calendarId, wordpressSiteId } = payload;
          await CoScheduleWP.setToken(wordpressSiteKey, calendarId, wordpressSiteId);
          notifyIframe({ action: 'runConnectionTest', payload: { wordpressSiteKey } });
        } catch(err) {
          console.log('ERROR: CoScheduleWP.setToken failed:', (err && err.stack) || err);
        }
      }

      static connectionTestDone() {
        CoScheduleWP.navigateToCalendar();
      }

      static handleEventData(eventData) {
        const { type, payload } = eventData

        const eventTypeAction = EventTypeActions[type];
        if (eventTypeAction) {
          eventTypeAction(payload);
        }
      }
    }

    function receiveMessage(event) {
      if (event.origin !== APP_URL) {
        return;
      }

      const eventData = JSON.parse(event.data);
      EventTypeActions.handleEventData(eventData);
    }

    function notifyIframe(data) {
      iframe.contentWindow.postMessage(JSON.stringify(data), '*')
    }

    window.addEventListener('message', receiveMessage);

    /////////

    const resizeHandler = debounce(() => {
      // get parent container width
      iframe.setAttribute('width', String(iframe.parentElement.offsetWidth));
      iframe.setAttribute('height', String(window.innerHeight));
    }, 250);

    window.onresize = () => resizeHandler();
    window.onresize(null);

    /////////

    const removeElements = [
      document.querySelector('#wpfooter'),
      document.querySelector('.update-nag'),
      document.querySelector('#wpwrap > #footer'),
    ];
    removeElements.forEach((removeElement) => removeElement && removeElement.remove());

    const iframe = document.createElement('iframe');
    // See: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/iframe#attr-sandbox
    iframe.setAttribute('sandbox', 'allow-same-origin allow-scripts allow-forms allow-popups');
    iframe.setAttribute('id', 'coschedulePluginActivationFrame');
    iframe.setAttribute('src', CoScheduleWP.getIframeSrc());
    iframe.onload = () => {
      document.querySelector('#pluginLoading').setAttribute('style', 'display: none;');
    }

    document.querySelector('.iframe-container').appendChild(iframe);
  });
</script>
