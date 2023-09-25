
 <?php if ($facebook_pixel_id_FAE) { ?>
	<link rel="stylesheet" type="text/css" href="catalog/view/css/facebook/cookieconsent.min.css" />
    <script type="text/javascript" src = "catalog/view/javascript/facebook/cookieconsent.min.js"></script>
    <script>
    window.addEventListener("load", function(){
      function setConsent() {
        fbq(
          'consent',
          this.hasConsented() ? 'grant' : 'revoke'
        );
      }
      window.cookieconsent.initialise({
        palette: {
          popup: {
            background: '#237afc'
          },
          button: {
            background: '#fff',
            text: '#237afc'
          }
        },
        cookie: {
          name: fbq.consentCookieName
        },
        type: 'opt-out',
        showLink: false,
        content: {
          dismiss: 'Agree',
          deny: 'Opt Out',
          header: 'Our Site Uses Cookies',
          message: 'By clicking Agree, you agree to our <a class="cc-link" href="https://www.facebook.com/legal/terms/update" target="_blank">terms of service</a>, <a class="cc-link" href="https://www.facebook.com/policies/" target="_blank">privacy policy</a> and <a class="cc-link" href="https://www.facebook.com/policies/cookies/" target="_blank">cookies policy</a>.'
        },
        layout: 'basic-header',
        location: true,
        revokable: true,
        onInitialise: setConsent,
        onStatusChange: setConsent,
        onRevokeChoice: setConsent
      }, function (popup) {
        // If this isn't open, we know that we can use cookies.
        if (!popup.getStatus() && !popup.options.enabled) {
          popup.setStatus(cookieconsent.status.dismiss);
        }
      });
    });
    </script>
    <script type="text/javascript">
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
      n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
      document,'script','https://connect.facebook.net/en_US/fbevents.js');
      fbq.consentCookieName = 'fb_cookieconsent_status';

      (function() {
        function getCookie(t){var i=("; "+document.cookie).split("; "+t+"=");if(2==i.length)return i.pop().split(";").shift()}
        var consentValue = getCookie(fbq.consentCookieName);
        fbq('consent', consentValue === 'dismiss' ? 'grant' : 'revoke');
      })();
    </script>
    <script type="text/javascript" src = "catalog/view/javascript/facebook/facebook_pixel.js"></script>
   
      <script type="text/javascript">
        (function() {
          var params = <?= $facebook_pixel_params_FAE ?>;
          _facebookAdsExtension.facebookPixel.init(
            '<?= $facebook_pixel_id_FAE ?>',
            <?= $facebook_pixel_pii_FAE ?>,
            params);
          <?php if ($facebook_pixel_event_params_FAE) { ?>
            _facebookAdsExtension.facebookPixel.firePixel(
              JSON.parse('<?= $facebook_pixel_event_params_FAE ?>'));
          <?php } ?>
        })();
      </script>
    <?php } ?>