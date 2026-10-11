(function ($, WordPress) {
  var Controller = function () {
    this.$form = $('.license-form');
  };

  Controller.prototype.isNotEmpty = function (email, key) {
    return email && key;
  };

  Controller.prototype.submitForm = function (e) {
    var email = $('[name="license[email]"]').val(),
      key = $('[name="license[key]"]').val(),
      gateway = $('[name="license[gateway]"]').is(':checked') == true ? true : '';
    usetrial = $('[name="license[usetrial]"]').is(':checked') == true ? true : '';
    if (!this.isNotEmpty(email, key)) {
      alert('Empty email or key, please fill all fields with valid values');
      return;
    }
    $.post(window.ajaxurl, {
      mail: email,
      key: key,
      gateway: gateway,
      usetrial: usetrial,
      action: 'grid-gallery',
      _wpnonce: SupsysticGallery.nonce,
      route: {
        module: 'license',
        action: 'activate',
      },
    }).success(function (response) {
      if (response.status) {
        //$.jGrowl('License was successfully activated');
        window.location.reload(true);
      } else {
        var error = '';
        if (response && response.errors && response.errors.length) {
          error = response.errors.join('\n');
        } else {
          error = 'Wrong email or licese key';
        }
        alert(error);
      }
    });
  };

  $(document).ready(function () {
    var controller = new Controller();

    $('#send-license').on('click', function (e) {
      controller.submitForm(e);
    });
  });
})(jQuery, (window.wp = window.wp || {}));
