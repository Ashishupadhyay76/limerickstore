jQuery(document).ready(function () {
  jQuery(document).on('click', '.supsystic-pro-notice.sg-notification .notice-dismiss', function () {
    jQuery.post(window.ajaxurl, {
      action: 'grid-gallery',
      _wpnonce: SupsysticGallery.nonce,
      route: {
        module: 'license',
        action: 'dismissNotice',
      },
    });
  });
});
