(function (app, $, ajaxurl) {
  function PhotosPro() {}

  PhotosPro.prototype.init = function () {
    this.initHoverCaptionImage();
    this.initUploadVideo();
    this.initAttributesDialog();
    this.initToastImageEditor();
  };

  PhotosPro.prototype.initHoverCaptionImage = function () {
    $('.select-hover-caption-image').on('click', function () {
      var imageId = $(this).data('image-id'),
        mediaSelect = wp.media({
          multiple: false,
          library: { type: 'image' },
        }),
        $input = $('#hover-caption-image-inp-' + imageId),
        $form = $('#photo-editor-' + imageId);

      if (imageId) {
        mediaSelect.on('close', function () {
          var selection = mediaSelect.state().get('selection').toJSON();
          if (selection.length) {
            $input.val(selection[0].url);

            $form.unbind('submit');
            $form.submit(function (event) {
              event.preventDefault();
              var $formHidden = $('#photo-editor-hidden-' + imageId),
                $formGallery = $('#gallery-editor-hidden'),
                data = '';
              if ($formHidden.length == 1) {
                data += '&' + $formHidden.serialize();
              }
              if ($formGallery.length == 1) {
                data += '&' + $formGallery.serialize();
              }
              $.post(ajaxurl, $(this).serialize() + '&_wpnonce=' + SupsysticGallery.nonce + data, function (response) {
                $.jGrowl('Information updated.' + response);
                //window.location.reload();
              });
            });
            $form.trigger('submit');
          }
        });
        mediaSelect.open();
      }
    });
  };

  PhotosPro.prototype.addAttachmentFromVideoUrl = function (galleryId, whenSuccessAddHandler) {
    $('#sggAduErrorText').html('');
    $('#sggAduErrorText').addClass('sggAduHiden');
    SupsysticGallery.Loader.show('Please, wait until images will be imported.');

    var post = app.Ajax.Post({
      module: 'photos',
      action: 'addImageFromVideoUrl',
    });
    post.add('videoType', $('.sggDlgVideoTypeRadio[name="sggDlgVideoType"]:checked').val());
    post.add('videoUrl', $('#sggDlgUrlVideoInp').val());
    post.add('galleryId', galleryId);

    post.send(function (resp) {
      SupsysticGallery.Loader.hide();
      if (resp && resp.success) {
        $.jGrowl('Image import successfull');
        whenSuccessAddHandler();
      } else {
        if (resp.error) {
          showError(resp.error);
        } else {
          showError('Error Occurred');
        }
      }
    });
    function showError(textStr) {
      $('#sggAduErrorText').html(textStr);
      $('#sggAduErrorText').removeClass('sggAduHiden');
    }
  };

  PhotosPro.prototype.initUploadVideo = function () {
    var selfPp = this,
      videoUrlAddDlg = $('#videoUrlAddDialog'),
      galleryId = 0;
    videoUrlAddDlg.find('.sggVideoUrlAddWr').append('<div class="sggTableRow"><a target="_blank" href="https://supsystic.com/documentation/add-video-to-galleries/">How to upload SelfHosted video?</a></div>');
    videoUrlAddDlg.dialog({
      modal: true,
      autoOpen: false,
      width: 400,
      open: function (event, ui) {
        $('#sggDlgUrlVideoInp').val('');
        $('#sggAduErrorText').addClass('sggAduHiden');
      },
      buttons: {
        Add: function () {
          galleryId = parseInt(videoUrlAddDlg.attr('data-gallery-id'));
          if (galleryId && !isNaN(galleryId)) {
            selfPp.addAttachmentFromVideoUrl(galleryId, function () {
              videoUrlAddDlg.dialog('close');
              window.location.search = 'page=supsystic-gallery&module=galleries&action=view&gallery_id=' + galleryId;
            });
          }
        },
        Cancel: function () {
          videoUrlAddDlg.dialog('close');
        },
      },
    });

    $('#sggUploadVideoFromUrlBtn').on('click', function (event) {
      videoUrlAddDlg.dialog('open');
    });
  };

  PhotosPro.prototype.initAttributesDialog = function () {
    var controller = this;
    $attributesDialog = $('#ggImageAttributesDialog').dialog({
      autoOpen: false,
      modal: true,
      width: 540,
      buttons: {
        Save: function () {
          var $containers = $attributesDialog.find('select.gg-attribute-values');

          if ($containers.length > 0) {
            var newValues = [],
              imageId = $attributesDialog.attr('data-image-id'),
              $form = $('#photo-editor-' + imageId),
              attrValues;

            try {
              attrValues = JSON.parse($('#gg-attribute-values').attr('data-values'));
            } catch (err) {}
            if (!$.isArray(attrValues)) {
              attrValues = [];
            }

            $containers.each(function () {
              var $this = $(this),
                value = $this.val(),
                $tr = $this.closest('tr'),
                attrName = (newValue = $tr.find('input.gg-attribute-names').val()),
                newValue = $tr.find('input.gg-attribute-new').val();

              if (newValue == '') {
                if (value != '') {
                  newValues.push({ name: attrName, value: $this.find('option:selected').text() });
                }
              } else {
                newValues.push({ name: attrName, value: newValue });
                var values = controller.getAtrributeValues(attrValues, attrName, 'values');
                if (values === false) {
                  attrValues.push({ name: attrName, values: [newValue] });
                } else if (values.indexOf(newValue) == -1) {
                  values.push(newValue);
                }
              }
            });

            $('#gg-attribute-values').attr('data-values', JSON.stringify(attrValues));
            $('a.attributes-image[data-image-id="' + imageId + '"]').attr('data-values', JSON.stringify(newValues));
            controller.saveAttributes([{ id: imageId, attributes: newValues }]);
            $form.find('[name="buttonLinkUrl"]').val($attributesDialog.find('#ggButtonLinkUrl').val());
            $form.find('[name="buttonLinkTitle"]').val($attributesDialog.find('#ggButtonLinkTitle').val());
            $form.find('[name="imageKeywords"]').val($attributesDialog.find('#ggImageKeywords').val().replace(/"/g, "'"));
            $form.trigger('submit');
            $(this).dialog('close');
          }
        },
        Cancel: function () {
          $(this).dialog('close');
        },
      },
    });

    $('a.attributes-image').on('click', function (event) {
      event.preventDefault();
      var $this = $(this),
        imageId = $this.data('image-id'),
        $form = $('#photo-editor-' + imageId),
        $containers = $attributesDialog.find('select.gg-attribute-values');

      if ($containers.length > 0) {
        var imgValues, attrValues;
        try {
          imgValues = JSON.parse($this.attr('data-values'));
          attrValues = JSON.parse($('#gg-attribute-values').attr('data-values'));
        } catch (err) {}
        if (!$.isArray(imgValues)) {
          imgValues = [];
        }
        if (!$.isArray(attrValues)) {
          attrValues = [];
        }
        $attributesDialog.attr('data-image-id', imageId);
        $containers.empty().each(function () {
          var $this = $(this),
            attrName = $this.closest('tr').find('input.gg-attribute-names').val(),
            attrValue = controller.getAtrributeValues(imgValues, attrName, 'value'),
            values = controller.getAtrributeValues(attrValues, attrName, 'values');

          $this.append($('<option value="">---</option>'));
          if (attrValue === false) {
            attrValue = '';
          }
          if (values !== false) {
            $.each(values, function (k, value) {
              $this.append($('<option value="' + k + '"' + (attrValue == value ? ' selected' : '') + '>' + value + '</option>'));
            });
          }
        });
        $attributesDialog.find('input.gg-attribute-new').val('');
      }
      $attributesDialog.find('#ggButtonLinkUrl').val($form.find('[name="buttonLinkUrl"]').val());
      $attributesDialog.find('#ggButtonLinkTitle').val($form.find('[name="buttonLinkTitle"]').val());
      $attributesDialog.find('#ggImageKeywords').val($form.find('[name="imageKeywords"]').val());
      $attributesDialog.dialog('open');
    });
  };

  PhotosPro.prototype.saveAttributes = function (attributes) {
    var post = app.Ajax.Post(
      {
        module: 'galleries',
        action: 'saveAttributes',
      },
      { gallery_id: this.galleryId }
    );

    if (attributes.length > 0) {
      post.add('attributes', attributes);
      app.Loader.show('Update image custom attributes...');

      post.send(function (response) {
        $.jGrowl('Custom attributes updated.');
        app.Loader.hide();
      });
    } else {
      $.jGrowl('Select images.');
    }
  };

  PhotosPro.prototype.getAtrributeValues = function (attributes, attrName, attrValue) {
    if (typeof attributes == 'undefined' || typeof attrName == 'undefined' || !$.isArray(attributes)) return false;
    for (var i = 0; i < attributes.length; i++) {
      if ('name' in attributes[i] && attrValue in attributes[i] && attributes[i]['name'] == attrName) {
        return attributes[i][attrValue];
      }
    }
    return false;
  };

  PhotosPro.prototype.getAtrributeIndex = function (attributes, attrName) {
    if (typeof attributes == 'undefined' || typeof attrName == 'undefined' || !$.isArray(attributes)) return false;
    for (var i = 0; i < attributes.length; i++) {
      if ('name' in attributes[i] && attributes[i]['name'] == attrName) {
        return i;
      }
    }
    return false;
  };

  PhotosPro.prototype.initToastImageEditor = function () {
    var self = this;
    if (typeof window.tui === 'undefined' || typeof window.tui.ImageEditor === 'undefined') {
      return;
    }
    var editorInstance = null;
    var current = { imageId: 0, attachmentId: 0, galleryId: 0, imageUrl: '' };

    function openPopup() {
      var overlay = document.createElement('div');
      overlay.id = 'sggToastOverlay';
      overlay.setAttribute('style', 'position:fixed;inset:0;z-index:100500;background:rgba(0,0,0,.6);');
      var wrap = document.createElement('div');
      wrap.id = 'sggToastWrap';
      wrap.setAttribute('style', 'position:fixed;left:50%;top:50%;transform:translate(-50%,-50%);width:980px;max-width:95vw;height:760px;max-height:90vh;background:#fff;z-index:100501;display:flex;flex-direction:column;border-radius:4px;overflow:hidden;');
      var header = document.createElement('div');
      header.setAttribute('style', 'height:42px;display:flex;align-items:center;justify-content:space-between;padding:0 12px;border-bottom:1px solid #e5e5e5;background:#f7f7f7;');
      header.innerHTML = '<div style="font-weight:600;">Image editor</div><button type="button" id="sggToastClose" class="button button-link" style="font-size:18px;line-height:1;">×</button>';
      var body = document.createElement('div');
      body.id = 'sggToastEditorContainer';
      body.setAttribute('style', 'flex:1 1 auto;min-height:0;');
      var footer = document.createElement('div');
      footer.setAttribute('style', 'height:56px;display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:8px 12px;border-top:1px solid #e5e5e5;background:#fafafa;');
      footer.innerHTML = '<button type="button" id="sggToastCancel" class="button">Cancel</button><button type="button" id="sggToastSave" class="button button-primary">Save copy</button>';
      wrap.appendChild(header);
      wrap.appendChild(body);
      wrap.appendChild(footer);
      document.body.appendChild(overlay);
      document.body.appendChild(wrap);
      // block background only
      ['mousedown', 'mouseup', 'click', 'dblclick', 'mousemove', 'touchstart', 'touchmove', 'wheel', 'keydown', 'keyup', 'keypress', 'contextmenu'].forEach(function (ev) {
        overlay.addEventListener(
          ev,
          function (e) {
            e.stopPropagation();
            e.preventDefault();
          },
          true
        );
      });
      return { overlay: overlay, wrap: wrap };
    }

    function closePopup(nodes) {
      try {
        if (editorInstance && editorInstance.destroy) editorInstance.destroy();
      } catch (e) {}
      editorInstance = null;
      if (nodes) {
        if (nodes.wrap && nodes.wrap.parentNode) nodes.wrap.parentNode.removeChild(nodes.wrap);
        if (nodes.overlay && nodes.overlay.parentNode) nodes.overlay.parentNode.removeChild(nodes.overlay);
      }
    }

    jQuery(document).on('click', '.edit-image', function (e) {
      e.preventDefault();
      var $this = jQuery(this);
      current.imageId = parseInt($this.data('image-id'));
      current.attachmentId = parseInt($this.data('attachment-id'));
      var $row = $this.closest('tr');
      var info = $row.data('entity-info');
      current.imageUrl = info && info.attachment && info.attachment.url ? info.attachment.url : '';
      var $formGallery = jQuery('#gallery-editor-hidden');
      current.galleryId = $formGallery.length ? parseInt($formGallery.find('input[name="gallery_id"]').val()) : 0;

      var nodes = openPopup();
      try {
        if (jQuery.fn.sortable) jQuery('.gg-all-container, .gg-category-container, [data-sortable]').sortable('disable');
      } catch (er) {}

      editorInstance = new window.tui.ImageEditor(document.getElementById('sggToastEditorContainer'), {
        includeUI: {
          menu: ['crop', 'flip', 'rotate', 'draw', 'shape', 'icon', 'text', 'mask', 'filter'],
          initMenu: 'filter',
          theme: {},
          menuBarPosition: 'bottom',
          loadImage: current.imageUrl ? { path: current.imageUrl, name: 'Image' } : null,
        },
        cssMaxWidth: 900,
        cssMaxHeight: 600,
        usageStatistics: false,
        selectionStyle: { cornerSize: 20, rotatingPointOffset: 70 },
      });
      // image preloaded via includeUI.loadImage

      jQuery('#sggToastClose, #sggToastCancel').on('click', function () {
        try {
          if (jQuery.fn.sortable) jQuery('.gg-all-container, .gg-category-container, [data-sortable]').sortable('enable');
        } catch (er) {}
        closePopup(nodes);
      });

      jQuery('#sggToastSave').on('click', function () {
        if (!editorInstance) return;
        var dataURL = editorInstance.toDataURL();
        console.log(dataURL);
        if (!dataURL) return;
        // Set loader z-index above popup
        jQuery('.gg-modal-loading-overlay').css('z-index', '99999999');
        jQuery('.gg-modal-loading-object').css('z-index', '999999999');
        SupsysticGallery.Loader.show('Saving edited image...');
        var post = app.Ajax.Post({ module: 'photos', action: 'saveEditedImage' });
        post.add('galleryId', current.galleryId);
        post.add('sourceAttachmentId', current.attachmentId);
        post.add('imageDataUrl', dataURL);
        post.send(function (resp) {
          SupsysticGallery.Loader.hide();
          if (resp && resp.success) {
            jQuery.jGrowl('Edited image saved.');
            try {
              if (jQuery.fn.sortable) jQuery('.gg-all-container, .gg-category-container, [data-sortable]').sortable('enable');
            } catch (er) {}
            closePopup(nodes);
            if (current.galleryId) {
              window.location.search = 'page=supsystic-gallery&module=galleries&action=view&gallery_id=' + current.galleryId;
              document.location.reload(true);
            }
          } else {
            jQuery.jGrowl(resp && resp.message ? resp.message : 'Save failed');
          }
        });
      });
    });
  };

  if (app && app.Controller) {
    app.Controller.PhotosPro = function () {
      return new PhotosPro();
    };
  }
})((window.SupsysticGallery = window.SupsysticGallery || {}), jQuery, ajaxurl);

jQuery(document).ready(function () {
  SupsysticGallery.Controller.PhotosPro().init();
});
