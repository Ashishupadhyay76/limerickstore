(function ($, app) {
  function SettingsController() {
    this.strToBool = function (value) {
      if (value == 'true') {
        return true;
      } else {
        return false;
      }
    };
    this.$catsPresetDialog = $('#selCatsPresetDlg');
    this.$pagPresetDialog = $('#selPagPresetDlg');

    this.init();
  }

  SettingsController.prototype.toggleOverlay = function (e) {
    var $rows = $('.icons-overlay-toggle'),
      value = $(e.currentTarget).val();

    if (value == 'false') {
      $rows.hide();
    } else {
      $rows.show();
    }
  };

  SettingsController.prototype.toggleColorPicker = function (el, e) {
    var $el = $(el).parents('.wp-picker-container');

    if (e.currentTarget.checked) {
      $el.hide();
    } else {
      $el.show();
    }
  };

  SettingsController.prototype.toggleCatsContainerBg = function (e) {
    this.toggleColorPicker('#catsNavBg', e);
  };

  SettingsController.prototype.toggleCatsTextBg = function (e) {
    this.toggleColorPicker('#catsTextBg', e);
  };

  SettingsController.prototype.toggleCatsTextColor = function (e) {
    this.toggleColorPicker('#catsTextColor', e);
  };

  SettingsController.prototype.togglePagContainerBg = function (e) {
    this.toggleColorPicker('#pagContainerBg', e);
  };

  SettingsController.prototype.togglePagTextBg = function (e) {
    this.toggleColorPicker('#pagTextBackground', e);
  };

  SettingsController.prototype.togglePagTextColor = function (e) {
    this.toggleColorPicker('#pagTextColor', e);
  };

  SettingsController.prototype.init = function () {
    if ($('.categories-order ul').length) {
      $('.categories-order ul').sortable({
        start: function (event, ui) {
          ui.item.draggable();
        },
        stop: function (event, ui) {
          ui.item.draggable('destroy');
        },
      });
    }

    // this.initIconsPreview();
  };

  SettingsController.prototype.toggleCats = function () {
    var $table = $('table[name="cats"] thead'),
      $toggleRow = $('#useCats'),
      $pagesRow = $('#usePages'),
      value = 0;

    value = this.strToBool($('[name="categories[enabled]"]:checked').val());

    $('#hideCats').on('change', function () {
      $table.find('tr').hide();
      $('.categories-order').hide();
      $toggleRow.show();
    });

    $('#showCats').on('change', function () {
      $table.find('tr').show();
      $('.categories-order').show();
      if ($pagesRow.find('#showPages').prop('checked')) {
        $pagesRow.find('#hidePages').prop('checked', true).trigger('change');
        $.jGrowl('Pagination disabled now');
      }
    });

    if (value) {
      $table.find('tr').show();
      $('.categories-order').show();
    } else {
      $table.find('tr').hide();
      $('.categories-order').hide();
      $toggleRow.show();
    }

    // $('#enableMultiselect').on('change', function () {
    // 	var $this = $(this),
    // 		subOpts = $($this.data('toggle'));
    //
    // 	if($this.is(':checked')) {
    // 		subOpts.show()
    // 	} else {
    // 		subOpts.hide();
    // 	}
    // });
  };

  SettingsController.prototype.togglePages = function () {
    var $table = $('table[name="pages"] thead'),
      $toggleRow = $('#usePages'),
      $catsRow = $('#useCats');
    value = 0;

    value = this.strToBool($('[name="pagination[enabled]"]:checked').val());

    $('#hidePages').on('change', function () {
      $table.find('tr').hide();
      $toggleRow.show();
    });

    $('#showPages').on('change', function () {
      $table.find('tr').show();
      if ($catsRow.find('#showCats').prop('checked')) {
        $catsRow.find('#hideCats').prop('checked', true).trigger('change');
        $.jGrowl('Categories disabled now');
      }

      if ($('#show-more-enable').prop('checked')) {
        $('#show-more-disable').prop('checked', true).trigger('change');
        $.jGrowl('Load More is disabled now');
      }
    });

    if (value) {
      $table.find('tr').show();
    } else {
      $table.find('tr').hide();
      $toggleRow.show();
    }

    $('#ggPaginationShowNextPrevArrSelect')
      .on('change', function (e1) {
        var selectedPagNextPrev = $(this).val(),
          $ggPagPrevTextRow = $('#ggPaginationArrowPrevTextRow'),
          $ggPagNextTextRow = $('#ggPaginationArrowNextTextRow');
        if (selectedPagNextPrev == '0') {
          $ggPagPrevTextRow.addClass('ggDisplNone');
          $ggPagNextTextRow.addClass('ggDisplNone');
        } else {
          $ggPagPrevTextRow.removeClass('ggDisplNone');
          $ggPagNextTextRow.removeClass('ggDisplNone');
        }
      })
      .trigger('change');
  };

  SettingsController.prototype.toggleButtons = function () {
    $('#custom-buttons-enable').on('change', function () {
      $('table[name="buttons"] tbody').show();
    });

    $('#custom-buttons-disable').on('change', function () {
      $('table[name="buttons"] tbody').hide();
    });

    $('table[name="buttons"] thead input[type="radio"]:checked').trigger('click').trigger('change');
  };

  SettingsController.prototype.toggleShowMore = function () {
    $('#show-more-enable').on('change', function () {
      $('table[name="show-more"] tbody').show();
      $pagination = $('#usePages');

      if ($pagination.find('#showPages').prop('checked')) {
        $pagination.find('#hidePages').prop('checked', true).trigger('change');
        $.jGrowl('Pagination disabled now');
      }
    });

    $('#show-more-disable').on('change', function () {
      $('table[name="show-more"] tbody').hide();
    });

    $('table[name="show-more"] thead input[type="radio"]:checked').trigger('click').trigger('change');
  };

  SettingsController.prototype.loadFontFamily = function (font) {
    if (window && window.sggStandartFontsList && $.inArray(font.replace(/\"/g, ''), window.sggStandartFontsList) != -1) {
      return false;
    }
    if (font && font !== 'Default') {
      if (app && app.ImagePreview && app.ImagePreview['importFontFamily']) {
        app.ImagePreview.importFontFamily(font);
      }
    }
  };

  SettingsController.prototype.initButtonsEditorDialog = function () {
    var self = this,
      presetNames = [],
      $buttonsEditPresetDialog = $('.buttons-edit-preset-dialog');
    $buttonSocialSetupDialog = $('.social-setup-dialog');

    $buttonsEditPresetDialog.dialog({
      autoOpen: false,
      modal: true,
      width: 850,
      buttons: {
        Cancel: function () {
          $(this).dialog('close');
        },
        'Save as preset': function () {
          $('<div><input type="text" id="preset-name" placeholder="Enter preset name"></div>').dialog({
            title: 'Preset name',
            autoOpen: true,
            modal: true,
            width: 230,
            buttons: {
              Cancel: function () {
                $(this).dialog('close');
              },
              Save: function () {
                $dialog = $(this);
                preset = {};
                presetName = $(this).find('#preset-name').val();
                preset[presetName] = self.getButtonsProperties();

                var request = SupsysticGallery.Ajax.Post({
                  module: 'galleries',
                  action: 'updateButtonsPreset',
                  preset: preset,
                });

                request.send(function (response) {
                  if (response['success'] == 'ok') {
                    addButtonToPressetsList(presetName, properties, true);
                    $('.presets-list-item:first').addClass('active');
                    $.jGrowl('Preset settings have been updated successfully!');
                  } else {
                    $.jGrowl('Preset is not saved.');
                  }
                  $dialog.dialog('close');
                });
              },
            },
          });
        },
        Save: function () {
          $dialog = $(this);
          var request = SupsysticGallery.Ajax.Post({
            module: 'galleries',
            action: 'updateButtonsSettings',
            gallery_id: $('#buttons-preset').data('gallery-id'),
            buttons: self.getButtonsProperties(),
          });

          request.send(function (response) {
            if (response['success'] == 'ok') {
              $.jGrowl('Buttons settings saved successfully!');
            } else {
              $.jGrowl('Buttons settings is not saved.');
            }
            $dialog.dialog('close');
          });
        },
      },
    });
    $buttonSocialSetupDialog.dialog({
      autoOpen: false,
      modal: true,
      width: 850,

      buttons: {
        Cancel: function () {
          $(this).dialog('close');
        },
        Save: function () {
          $dialog = $(this);
          var request = SupsysticGallery.Ajax.Post({
            module: 'galleries',
            action: 'updateNetworksSettings',
            gallery_id: $('#social-setup-dialog-table').data('gallery-id'),
            networks: self.getSocialSetupProperties(),
          });

          request.send(function (response) {
            if (response['success'] == 'ok') {
              $.jGrowl('Social share settings saved successfully!');
              $dialog.find('input').each(function () {
                $(this).removeClass('input-error');
              });
              $dialog.dialog('close');
            } else {
              $.jGrowl('Social share settings is not saved.');
              var errors = response['errors'];

              $.each(errors, function (item, value) {
                var input = $dialog.find("input[name='custom_network[" + item + "]']");
                if (value === false) {
                  input.addClass('input-error');
                } else {
                  input.removeClass('input-error');
                }
              });
            }
          });
        },
      },
    });

    $.each($('#buttons-presets-list').data('presets-data'), function (name, preset) {
      addButtonToPressetsList(name, preset);
    });

    this.initColorPicker();

    $('[name="buttons-presets-editor"]').on('click', function (argument) {
      $buttonsEditPresetDialog.dialog('open');
    });

    $('[name="button-social-setup"]').on('click', function (argument) {
      $buttonSocialSetupDialog.dialog('open');
    });

    // Update preview button on changes.
    $(document).on('change', '.buttons-edit-preset-dialog [name*="buttons"]', function (event) {
      self.updateButtonsPreview();
    });

    $(document).on('change', '.social-setup-dialog [name*="buttons"]', function (event) {
      self.updateButtonsPreview();
    });

    // Selecting presets from preset list
    $(document).on('click', '#buttons-presets-list > div', function (event) {
      event.preventDefault();
      $('#buttons-presets-list > div').removeClass('active');
      preset = $(this).find('a').data('preset');
      updateButtonsInputs(preset);

      // We need set short delay before we set current preset active
      setTimeout(
        $.proxy(function () {
          $(this).addClass('active');
        }, this),
        200
      );
    });

    if ($('#buttons-presets-list').data('init-preset')) {
      updateButtonsInputs($('#buttons-presets-list').data('init-preset'));
    } else {
      $('#buttons-presets-list > div:first').trigger('click');
    }

    function updateButtonsInputs(preset) {
      $.each(preset, function (index, value) {
        $property = $buttonsEditPresetDialog.find('[name="buttons[' + index + ']"]').val(value);
        // Due to a bug with color picker, not updating color result in some case we must do it manually
        if ($property.hasClass('gg-color-picker-buttons')) {
          $property.closest('.wp-picker-container').find('.wp-color-result').css('background-color', value);
        }
      });
      // Trigger event to change color value in color picker
      $buttonsEditPresetDialog.find('.gg-color-picker-buttons').trigger('keyup');
      self.updateButtonsPreview();
    }

    function addButtonToPressetsList(name, preset, first) {
      $presetButton = $('<a>', {
        css: self.parseButtonPreset(preset),
        'data-preset': JSON.stringify(preset),
        text: name,
      });

      $closeButton = $('<i>', {
        class: 'fa fa-times',
      }).on('click', function (event) {
        event.stopPropagation();

        var request = SupsysticGallery.Ajax.Post({
          module: 'galleries',
          action: 'deleteButtonsPreset',
          name: name,
        });

        request.send(function (response) {
          if (response['success'] == 'ok') {
            $('.presets-list-item[data-button-name="' + name + '"]').remove();
            $.jGrowl('Preset removed successfully!');
          } else {
            $.jGrowl('Preset is not removed.');
          }
        });
      });

      $presetListItem = $('<div>', {
        class: 'presets-list-item',
        'data-button-name': name,
      }).append($presetButton, $closeButton);

      if (first) {
        $('#buttons-presets-list p:first').after($presetListItem);
      } else {
        $('#buttons-presets-list').append($presetListItem);
      }
    }

    // watermark Processing
    $(document).on('ggProSettingsWaterMarkProcessEvent', function (event, saveRes, resultType) {
      if (resultType == 1) {
        // standart Gallery Saving
        if (saveRes == 1 || saveRes == 2) {
          SupsysticGallery.Loader.hide();
          self.saveConfig();
        } else if (saveRes == 3) {
          $.jGrowl('Watermark Add Error!');
          setTimeout(self.saveConfig, 1000);
        } else {
          //if(saveRes == 4) {
          self.saveConfig();
        }
      } else if (resultType == 2) {
        // gallery Cloning
        var paramsSend = [1];
        if ($('#showWaterm').is(':checked')) {
          paramsSend.push($('#ggMsgWatermarkSavedSuccesfully').val());
        }
        $(document).trigger('ggProSettingsWaterMarkResultEvent', paramsSend);
      }
    });
    $('#btnSave')
      .off('click')
      .on('click', function () {
        self.saveScrollPos();
        self.saveWatermarks(1);
      });

    $('#ggWmImgUpdateBtn')
      .off('click')
      .on('click', function () {
        $('#watermarkCheckChangesInput').val('1');
        self.saveScrollPos();
        self.saveWatermarks(1);
      });
  };

  SettingsController.prototype.saveScrollPos = function () {
    var scrollTopPos = parseInt($('.settings-wrap').scrollTop());
    if (!isNaN(scrollTopPos)) {
      $('#slimScrollStartPos').val(scrollTopPos);
    }
  };

  SettingsController.prototype.saveWatermarks = function (useSaveGallery) {
    if ($('#showWaterm').is(':checked') && $('#watermarkCheckChangesInput').val() === '1') {
      var galleryId = $('#buttons-preset').data('gallery-id'),
        waterMarkArr = {
          rotate: $('#ggWmRotateVal').val(),
          enabled: true,
          transparency: $('#ggWmTransparency').val(),
          position: $('#ggWmPosition').val(),
          margin: $('#ggWmMargin').val(),
          url: $('#ggWmImgPath').val(),
          type: $('#ggWatermarkType').val(),
          galleryId: galleryId,
          show_for: $('#ggWmShowFor').val(),
          sizeThumbnails: $('[name="watermark[sizeThumbnails]"]').val(),
          sizeThumbnailsFrom: $('[name="watermark[sizeThumbnailsFrom]"]').val(),
          sizePopup: $('[name="watermark[sizePopup]"]').val(),
          sizePopupFrom: $('[name="watermark[sizePopupFrom]"]').val(),
        };

      SupsysticGallery.Loader.show('Please, wait reading images info...');
      if (waterMarkArr && waterMarkArr.url && waterMarkArr.url.length && waterMarkArr.url.length > 0) {
        var gallSetting = {
          photo_width: $('[name="area[photo_width]"]').val(),
          photo_height: $('[name="area[photo_height]"]').val(),
          crop_quality: $('[name="thumbnail[cropQuality]"]').val(),
          photo_width_unit: $('[name="area[photo_width_unit]"]').val(),
          photo_height_unit: $('[name="area[photo_height_unit]"]').val(),
          grid: $('[name="area[grid]"]').val(),
          area_width_unit: $('[name="area[width_unit]"]').val(),
          area_width: $('[name="area[width]"]').val(),
        };

        // get current photocount
        var request = SupsysticGallery.Ajax.Post({
            module: 'galleries',
            action: 'getWatermarkPhotosInfo',
            gallery_id: galleryId,
            last_gall_setting: gallSetting,
            watermark: waterMarkArr,
          }),
          onePhotoRequest;

        request
          .send(function (response) {
            if (response['success'] == true) {
              SupsysticGallery.Loader.show('Please wait until watermarks will be added.');

              if (response.galery_settings && response.photos && waterMarkArr) {
                var ajaxPromise = new $.Deferred().resolve(),
                  indWm = 0;

                function addWmToImage(onePhoto) {
                  onePhotoRequest = SupsysticGallery.Ajax.Post({
                    module: 'galleries',
                    action: 'addOneWatermark',
                    galery_settings: response.galery_settings,
                    galery_old_settings: response.galery_old_settings ? response.galery_old_settings : null,
                    photo: onePhoto,
                    watermark: waterMarkArr,
                    old_watermark: response.old_watermark ? response.old_watermark : null,
                  });

                  return onePhotoRequest.send(function (opResponse) {
                    if (opResponse['success'] == true) {
                      indWm++;
                      $.jGrowl('Watermark added. Processing ' + indWm + ' of ' + response.photos.length);
                    }
                    if (indWm == response.photos.length) {
                      $(document).trigger('ggProSettingsWaterMarkProcessEvent', [1, useSaveGallery]);
                    }
                  });
                }
                $.each(response.photos, function (index, onePhoto) {
                  ajaxPromise = ajaxPromise.then(
                    function () {
                      return addWmToImage(onePhoto);
                    },
                    function () {
                      indWm++;
                      $.jGrowl('Watermark add ERROR!. Processing ' + indWm);

                      if (indWm == response.photos.length - 1) {
                        $(document).trigger('ggProSettingsWaterMarkProcessEvent', [1, useSaveGallery]);
                      }
                      return addWmToImage(onePhoto);
                    }
                  );
                });
              }
            } else {
              $(document).trigger('ggProSettingsWaterMarkProcessEvent', [2, useSaveGallery]);
            }
          })
          .fail(function (response) {
            $(document).trigger('ggProSettingsWaterMarkProcessEvent', [3, useSaveGallery]);
          });
      } else {
        $(document).trigger('ggProSettingsWaterMarkProcessEvent', [4, useSaveGallery]);
      }
    } else {
      $(document).trigger('ggProSettingsWaterMarkProcessEvent', [4, useSaveGallery]);
    }
  };

  SettingsController.prototype.saveConfig = function () {
    $('#form-settings').append($('.buttons-edit-preset-dialog').hide());
    $('#form-settings').trigger('submit');

    $('#form-settings').append($('.social-setup-dialog').hide());
    $('#form-settings').trigger('submit');
  };

  SettingsController.prototype.initColorPicker = function () {
    $('.gg-color-picker-buttons').wpColorPicker({
      change: function (event) {
        setTimeout(function () {
          $(event.target).trigger('change', event.originalEvent);
        }, 50);
      },
      clear: function (event) {
        setTimeout(function () {
          $(event.target).siblings('.gg-color-picker-buttons').trigger('change');
        }, 50);
      },
    });
  };

  SettingsController.prototype.getButtonsProperties = function () {
    properties = {};
    $('.buttons-edit-preset-dialog [data-property]').each(function (index) {
      properties[$(this).data('property')] = $(this).val() || '';
    });
    return properties;
  };

  SettingsController.prototype.getSocialSetupProperties = function () {
    properties = {};
    $('.social-setup-dialog [data-property]').each(function (index) {
      properties[$(this).data('property')] = $(this).val() || '';
    });
    return properties;
  };

  SettingsController.prototype.updateButtonsPreview = function () {
    $('#buttons-presets-list > div').removeClass('active');

    function shadeColor(color, percent) {
      var f = parseInt(color.slice(1), 16),
        t = percent < 0 ? 0 : 255,
        p = percent < 0 ? percent * -1 : percent,
        R = f >> 16,
        G = (f >> 8) & 0x00ff,
        B = f & 0x0000ff;
      return '#' + (0x1000000 + (Math.round((t - R) * p) + R) * 0x10000 + (Math.round((t - G) * p) + G) * 0x100 + (Math.round((t - B) * p) + B)).toString(16).slice(1);
    }

    preset = this.getButtonsProperties();

    $('#preview-button').css(this.parseButtonPreset(preset));

    $('#preview-button')
      .mouseover(function () {
        if (preset.backgroundColor) {
          $(this).css('backgroundColor', shadeColor(preset.backgroundColor, 0.3));
        }
      })
      .mouseout(function () {
        $(this).css('backgroundColor', preset.backgroundColor);
      })
      .mousedown(function () {
        if (preset.backgroundColor) {
          $(this).css('backgroundColor', shadeColor(preset.backgroundColor, -0.1));
        }
      })
      .mouseup(function () {
        $(this).trigger('mouseover');
      });
  };

  SettingsController.prototype.parseButtonPreset = function (preset) {
    self = this;
    properties = {};
    pxUnits = ['fontSize', 'paddingV', 'paddingH', 'borderWidth', 'borderRadius'];

    $.each(preset, function (property, value) {
      if (property === 'class') {
        return;
      }

      if ($.inArray(property, pxUnits) !== -1 && value) {
        value += 'px';
      }

      switch (property) {
        case 'paddingV':
          properties['paddingTop'] = value;
          properties['paddingBottom'] = value;
          break;
        case 'paddingH':
          properties['paddingLeft'] = value;
          properties['paddingRight'] = value;
          break;
        case 'fontFamily':
          if (value === 'Default') {
            value = 'inherit';
          } else {
            self.loadFontFamily(value);
          }
        default:
          properties[property] = value;
      }
    });
    return properties;
  };

  SettingsController.prototype.toggleFullscreen = function () {
    var fullscreenOption = '[name="fullscreen[enabled]"]';
    if ($(fullscreenOption).val() == 'true') {
      $(fullscreenOption).attr('checked', 'checked').iCheck('update');
    } else {
      $(fullscreenOption).removeAttr('checked').iCheck('update');
    }
    $(fullscreenOption).click(function () {
      if ($(this).attr('value') == 'true') {
        $(this).removeAttr('checked').attr('value', 'false').iCheck('update');
      } else {
        $(this).attr('checked', 'checked').attr('value', 'true').iCheck('update');
      }
    });
  };

  //Preloader
  SettingsController.prototype.initPreiconDialog = function () {
    var $wrapper = $('#preiconDialog');

    $wrapper.dialog({
      autoOpen: false,
      modal: true,
      width: 600,
      buttons: {
        Cancel: function () {
          $(this).dialog('close');
        },
      },
    });
  };

  SettingsController.prototype.initAttributesDialog = function () {
    var $attributesDialog = $('#attributesDialog');

    $attributesDialog.dialog({
      autoOpen: false,
      modal: true,
      width: 500,
      buttons: {
        Save: function () {
          var attributes = [],
            enable = [],
            rename = [];

          $attributesDialog.find('label.gg-attribute-name').each(function () {
            var name = $(this).text(),
              oldName = $(this).closest('li').find('label.gg-attribute-oldname'),
              enableInFilter = $(this).closest('li').find('.gg-attribute-enable-wrapper input').is(':checked');
            if (name != '') {
              attributes.push(name);
              if (enableInFilter) {
                enable.push(name);
              }
            }
            if (oldName.length && oldName.text() != name) {
              rename.push({ oldName: oldName.text(), newName: name });
            }
          });
          $('#ggAttributesOrder').val(JSON.stringify(attributes));
          $('#ggAttributesEnable').val(JSON.stringify(enable));
          $('#ggAttributesRename').val(JSON.stringify(rename));
          $(this).dialog('close');
        },
        Cancel: function () {
          $(this).dialog('close');
        },
      },
    });
    $attributesDialog.find('.gg-sort-attributes').sortable({
      axis: 'y',
    });

    $attributesDialog.find('#ggAddAttribute').on('click', function () {
      var $newAttr = $attributesDialog.find('#ggNewAttribute');
      name = $newAttr.val();

      if (!controlAttribute(name)) return;

      var $ul = $attributesDialog.find('ul'),
        $li = $ul.find('li.ggSettingsDisplNone');
      if ($li.length > 0) {
        var $newLi = $li.clone(true).removeClass('ggSettingsDisplNone');
        $newLi.find('label.gg-attribute-name').text(name);
        $newLi.appendTo($ul);
      }
      $newAttr.val('').focus();
      $('.gg-attributes-item').iCheck('destroy');
      $('.gg-attributes-item').iCheck({ checkboxClass: 'icheckbox_minimal' });
    });

    $attributesDialog.find('.gg-attribute-delete').on('click', function () {
      $(this).closest('li').remove();
    });

    $attributesDialog.find('.gg-attribute-rename').on('click', function () {
      var name = $(this).closest('li').find('label.gg-attribute-name').text();
      $attributesDialog.find('#ggNewNameAttribute').attr('data-old-name', name).val(name);
      toggleAddRename(false);
    });

    $attributesDialog.find('#ggRenameAttribute').on('click', function () {
      var $input = $attributesDialog.find('#ggNewNameAttribute');
      ((newValue = $input.val()), (oldName = $input.attr('data-old-name')));

      if (!controlAttribute(newValue)) return;

      var $ul = $attributesDialog.find('ul'),
        $attributes = $ul.find('label.gg-attribute-name');
      if ($attributes.length > 0) {
        for (var i = 0; i < $attributes.length; i++) {
          if ($attributes.eq(i).text() == oldName) {
            $attributes.eq(i).text(newValue);
            break;
          }
        }
      }
      toggleAddRename(true);
    });

    $attributesDialog.find('#ggRenameCancel').on('click', function () {
      toggleAddRename(true);
    });

    function toggleAddRename(isAdd) {
      if (isAdd) {
        $attributesDialog.find('div.gg-new-attribute').removeClass('ggSettingsDisplNone');
        $attributesDialog.find('div.gg-rename-attribute').addClass('ggSettingsDisplNone');
      } else {
        $attributesDialog.find('div.gg-new-attribute').addClass('ggSettingsDisplNone');
        $attributesDialog.find('div.gg-rename-attribute').removeClass('ggSettingsDisplNone');
      }
    }
    function findAttribute(name) {
      var $ul = $attributesDialog.find('ul'),
        $attributes = $ul.find('label.gg-attribute-name'),
        found = false;
      if ($attributes.length > 0) {
        for (var i = 0; i < $attributes.length; i++) {
          if ($attributes.eq(i).text() == name) {
            found = true;
            break;
          }
        }
      }
      return found;
    }
    function controlAttribute(name) {
      $attributesDialog.find('.gg-attribute-error').addClass('ggSettingsDisplNone');
      if (name == '') {
        $attributesDialog.find('#ggAttributeEmpty').removeClass('ggSettingsDisplNone');
        return false;
      }
      if (name.length > 25) {
        $attributesDialog.find('#ggAttributeLong').removeClass('ggSettingsDisplNone');
        return false;
      }
      if (findAttribute(name)) {
        $attributesDialog.find('#ggAttributeDouble').removeClass('ggSettingsDisplNone');
        return false;
      }
      return true;
    }
  };

  SettingsController.prototype.initPreiconSelect = function () {
    var $plIconName = $('#plIconName'),
      $pldIconItems = $('#pldIconItems'),
      $inserPlace = $('#preloadIconPlace'),
      self = this;

    $('.preicon_img').on('click', function () {
      $plIconName.val($(this).data('name'));
      $pldIconItems.val($(this).data('items'));
      if ($plIconName.val() == 'default') {
        $('div:last-child', $inserPlace).remove();
        $inserPlace.append('<div class="gallery-loading">\n' + '<div class="blocks">\n' + '<div class="block"></div>\n' + '<div class="block"></div>\n' + '<div class="block"></div>\n' + '<div class="block"></div>\n' + '</div>\n' + '</div>\n');
      } else {
        var $pldIconColor = $(this).css('color');
        var items = '';
        $('div:last-child', $inserPlace).remove();
        for (var i = 0; i < $pldIconItems.val(); i++) {
          items += '<div></div>';
        }
        $inserPlace.append('<div class="gallery-loading">' + '<div class="la-' + $plIconName.val() + '" style="color:' + $pldIconColor + '">' + items + '</div>' + '</div>');
      }
      $('#preiconDialog').dialog('close');
    });
  };

  SettingsController.prototype.openPreiconDialog = function () {
    var $preicon_getcolor = $('#preload-background').find('.wp-color-result').css('backgroundColor');
    var $preicon_img = $('.preicon_img');
    $preicon_img.css('color', $preicon_getcolor);

    $('#preiconDialog').dialog('open');
  };

  SettingsController.prototype.openAttributesDialog = function () {
    $('#attributesDialog').dialog('open');
  };

  SettingsController.prototype.prePickerChange = function () {
    var $prepicker = $('#preload-background .wp-color-result');
    var $preImg = $('.gallery-loading').find('div');

    $preImg.css('color', $prepicker.css('backgroundColor'));
  };

  SettingsController.prototype.togglePreload = function () {
    $preloadTable = $('[name=preload]');

    $('#preload-enable').on('change', function () {
      $preloadTable.find('tbody').show();
    });

    $('#preload-disable').on('change', function () {
      $preloadTable.find('tbody').hide();
    });

    $preloadTable.find('thead input[type="radio"]:checked').trigger('click').trigger('change');
  };

  SettingsController.prototype.toggleAttributes = function () {
    $attributesTable = $('[name=attributes]');

    $('#attributes-enable').on('change', function () {
      $attributesTable.find('tbody').show();
    });

    $('#attributes-disable').on('change', function () {
      $attributesTable.find('tbody').hide();
    });

    $attributesTable.find('thead input[type="radio"]:checked').trigger('click').trigger('change');
  };

  SettingsController.prototype.addCaptionOnHoverImageEvent = function ($figure, $image, hoverImgSrc) {
    var fadeTimeout = 100;

    // need setup width and height, because hover image proportion was incompatible
    $image.css('width', $image.width());
    $image.css('height', $image.height());

    // save source image to attribute
    $image.attr('without-hover-img-effect', $image.attr('src'));
    $figure.hover(
      function () {
        $image.stop(true, true).fadeOut(fadeTimeout, function () {
          $image.attr('src', hoverImgSrc).fadeIn(fadeTimeout);
        });
      },
      function () {
        $image.stop(true, true).fadeOut(fadeTimeout, function () {
          $image.attr('src', $image.attr('without-hover-img-effect')).fadeIn(fadeTimeout);
        });
      }
    );
  };

  SettingsController.prototype.setCaptionOnHoverImage = function () {
    //check for all images
    var $figure = $('#preview .grid-gallery-caption'),
      hoverImgSrc = $figure.data('image-on-hover'),
      $image = $figure.find('img'),
      self = this;

    if (hoverImgSrc && hoverImgSrc.length) {
      if ($image.length) {
        self.addCaptionOnHoverImageEvent($figure, $image, hoverImgSrc);
      }
    } else if ($figure.data('thumb-overlay-personal') == '1') {
      if ($image.length) {
        hoverImgSrc = $image.data('personal-hover-on-image');
        self.addCaptionOnHoverImageEvent($figure, $image, hoverImgSrc);
      }
    }
    var $entryCellWrapper = $figure.find('.sggCbEntrySpan');
    if ($entryCellWrapper.length) {
      var fontValue = $entryCellWrapper.css('font-family');
      if (fontValue && fontValue.indexOf(',') == -1) {
        self.loadFontFamily(fontValue);
      }
    }
  };

  SettingsController.prototype.thumnailOverlayInit = function () {
    // image-on-hover
    var $ggOverlayImgOnHoverImg = $('.ggOverlayImgOnHoverImg'),
      $ggImageOnHoverPath = $('#ggImageOnHoverPath');

    // manually set data params
    $(
      '.caption-type[data-gg-cb-type="captions-icons"] .ggOverlayImgOnHoverImg, ' +
        '.caption-type[data-gg-cb-type="captions-icons"] .ggOverlayEnableImageOnHover, ' +
        '.caption-type[data-gg-cb-type="captions-icons"] .ggOverlayEnableOnHoverRow, ' +
        '.caption-type[data-gg-cb-type="captions-icons"] .ggImageOnHoverBtn'
    ).attr('data-captions-type', 1);

    $(
      '.caption-type[data-gg-cb-type="caption-builder"] .ggOverlayImgOnHoverImg, ' +
        '.caption-type[data-gg-cb-type="caption-builder"] .ggOverlayEnableImageOnHover, ' +
        '.caption-type[data-gg-cb-type="caption-builder"] .ggOverlayEnableOnHoverRow, ' +
        '.caption-type[data-gg-cb-type="caption-builder"] .ggImageOnHoverBtn'
    ).attr('data-captions-type', 2);

    $('.ggOverlayEnableImageOnHover').on('change', function () {
      var $currElem = $(this),
        dataId = $currElem.attr('data-captions-type');
      if ($currElem.val() == '1') {
        $('.ggOverlayEnableOnHoverRow[data-captions-type="' + dataId + '"]').removeClass('ggDisplNone');
      } else {
        $('.ggOverlayEnableOnHoverRow[data-captions-type="' + dataId + '"]').addClass('ggDisplNone');
      }
    });

    $ggOverlayImgOnHoverImg.on('change', function () {
      var $currElem = $(this);
      if ($ggImageOnHoverPath.val() == '') {
        $currElem.addClass('ggDisplNone');
      } else {
        $currElem.removeClass('ggDisplNone');
      }
    });
    $ggOverlayImgOnHoverImg.trigger('change');

    $('.ggImageOnHoverBtn').on('click', function (event) {
      var $currElem = $(this),
        dataId = $currElem.attr('data-captions-type'),
        $overlayImage = $('.ggOverlayImgOnHoverImg[data-captions-type="' + dataId + '"]'),
        wpMediaWindow = wp.media({
          multiple: false,
          library: { type: 'image' },
        });

      wpMediaWindow.on('select', function () {
        var attachments = wpMediaWindow.state().get('selection').toJSON();
        if (attachments.length == 1 && attachments[0].url) {
          $ggImageOnHoverPath.val(attachments[0].url);
          $overlayImage.attr('src', attachments[0].url);
          $overlayImage.trigger('change');
        }
      });
      wpMediaWindow.open();
    });

    $('.ggOverlayEnableImageOnHover').trigger('change');
  };

  SettingsController.prototype.waterMarkInit = function () {
    var $ggWmRotateInputVal = $('#ggWmRotateVal'),
      $ggWmTable = $('table[name="water-m-tab"] thead'),
      $showWatermCh = $('#showWaterm'),
      $hideWatermCh = $('#hideWaterm'),
      $ggWmImgPath = $('#ggWmImgPath'),
      $ggWmImgPreviewBlock = $('#ggWmImgPreviewBlock'),
      $ggWmImgUploadBtn = $('#ggWmImgUploadBtn'),
      $ggWmRotateSlider = $('#ggWmRotateSlider').slider({
        min: 0,
        max: 360,
        step: 1,
        value: $ggWmRotateInputVal.val(),
        slide: function (event, ui) {
          $ggWmRotateInputVal.val(ui.value);
          $('#watermarkCheckChangesInput').val('1');
        },
      });

    $ggWmRotateInputVal.on('change input paste', function () {
      var min = parseInt($ggWmRotateSlider.slider('option', 'min')),
        max = parseInt($ggWmRotateSlider.slider('option', 'max')),
        currVal = parseInt($ggWmRotateInputVal.val());

      if (isNaN(min) || isNaN(max) || isNaN(currVal)) {
        currVal = 0;
      } else {
        if (currVal < min) {
          currVal = min;
        }
        if (currVal > max) {
          currVal = max;
        }
      }
      $ggWmRotateSlider.slider('value', currVal);
      $ggWmRotateInputVal.val(currVal);
      $('#watermarkCheckChangesInput').val('1');
    });

    $showWatermCh.on('change', function () {
      $ggWmTable.find('tr:not(#showWatermRow)').show();
    });
    $hideWatermCh.on('change', function () {
      $ggWmTable.find('tr:not(#showWatermRow)').hide();
    });

    if ($showWatermCh.is(':checked')) {
      $showWatermCh.trigger('change');
    }
    if ($hideWatermCh.is(':checked')) {
      $hideWatermCh.trigger('change');
    }

    $ggWmImgPreviewBlock.on('change', function () {
      if ($ggWmImgPath.val() == '') {
        $ggWmImgPreviewBlock.addClass('ggDisplNone');
      } else {
        $ggWmImgPreviewBlock.removeClass('ggDisplNone');
      }
    });
    $ggWmImgPreviewBlock.trigger('change');

    $ggWmImgUploadBtn.on('click', function (event) {
      var wpMediaWindow = wp.media({
        multiple: false,
        library: { type: 'image' },
      });

      wpMediaWindow.on('select', function () {
        var attachments = wpMediaWindow.state().get('selection').toJSON();
        if (attachments.length == 1 && attachments[0].url) {
          $ggWmImgPath.val(attachments[0].url);
          $ggWmImgPreviewBlock.attr('src', attachments[0].url);
          $ggWmImgPreviewBlock.trigger('change');
        }
      });
      wpMediaWindow.open();
    });
  };

  SettingsController.prototype.waterMarkCheckChanges = function () {
    $('#watermark-img-upload-block img').on('load', function () {
      $('#watermarkCheckChangesInput').val('1');
    });
    $('#ggWmRotateVal').bind('keyup mouseup', function () {
      $('#watermarkCheckChangesInput').val('1');
    });
    $('#ggWmRotateSlider').bind('click', function () {
      $('#watermarkCheckChangesInput').val('1');
    });

    $("#form-settings div[data-tab='water-m-tab'] input, #form-settings div[data-tab='water-m-tab'] select").on('change', function () {
      $('#watermarkCheckChangesInput').val('1');
    });
    $('#grid-type select').on('change', function () {
      $('#watermarkCheckChangesInput').val('1');
    });
    $('#photo-width input').on('change', function () {
      $('#watermarkCheckChangesInput').val('1');
    });
    $('#photo-height input').on('change', function () {
      $('#watermarkCheckChangesInput').val('1');
    });
  };

  SettingsController.prototype.exifInit = function () {
    var $recollectBnt = $('#ggExifRecollectBtn');
    function onExifRadioShowChangeHandler(event) {
      var isChecked = $('#ggShowExif').prop('checked'),
        $tableExifProp = $('#ggExifParametersTbl');
      if (isChecked) {
        $tableExifProp.show();
      } else {
        $tableExifProp.hide();
      }
    }

    $('#ggShowExif').on('ifChanged', onExifRadioShowChangeHandler);

    $recollectBnt.on('click', function () {
      function recollectEndHandler() {
        SupsysticGallery.Loader.hide();
        $recollectBnt.prop('disabled', false);
      }

      $recollectBnt.prop('disabled', true);
      SupsysticGallery.Loader.show($('#msgStrWaitWhileReadImgInfo').val());
      // get current photos list
      var request = SupsysticGallery.Ajax.Post({
          module: 'photos',
          action: 'getAttachmentArrBy',
          gallery_id: $('#ggExifDataTable').data('gallery-id'),
        }),
        strMsgImgRec1 = $('#msgStrImageRecollectedPr').val(),
        strMsgImgRec2 = $('#msgStrIrOf').val(),
        strMsgImgRec3 = $('#msgStrRecollectError2').val(),
        strMsgImgRec4 = $('#msgStrRecollectError3').val(),
        onePhotoRequest;

      request
        .send(function (response) {
          if (response['attachmentArr']) {
            SupsysticGallery.Loader.show($('#msgStr1WaitUntillRecolect').val());
            var ajaxQueueObj = new $.Deferred().resolve(),
              exifDataUpdateRequest = null,
              photoIndex = 0;
            function exifDataUpdateFunc(attachment_id) {
              exifDataUpdateRequest = SupsysticGallery.Ajax.Post({
                module: 'photos',
                action: 'updatePhotoExifInfo',
                attachment_id: attachment_id,
              });

              return exifDataUpdateRequest
                .send(function (opResponse) {
                  if (opResponse['success'] == true) {
                    $.jGrowl(strMsgImgRec1 + (photoIndex + 1) + strMsgImgRec2 + response['attachmentArr'].length);
                  } else {
                    $.jGrowl(strMsgImgRec3 + (photoIndex + 1) + strMsgImgRec4);
                  }
                })
                .fail(function (opResponse) {
                  $.jGrowl(strMsgImgRec3 + (photoIndex + 1) + strMsgImgRec4);
                })
                .always(function () {
                  photoIndex++;
                  if (photoIndex == response['attachmentArr'].length) {
                    // ended
                    recollectEndHandler();
                  }
                });
            }
            $.each(response['attachmentArr'], function (ind2, attachmentObj) {
              ajaxQueueObj = ajaxQueueObj.then(
                function () {
                  return exifDataUpdateFunc(attachmentObj);
                },
                function () {
                  return exifDataUpdateFunc(attachmentObj);
                }
              );
            });
          } else {
            var errMsg = $('#msgStr1ErrorOccurred').val();
            if (response['error'] && response['message']) {
              errMsg = response['message'];
            }
            $.jGrowl(errMsg);
          }
        })
        .fail(function (response) {
          $.jGrowl($('#msgStr1ErrorOccurred').val());
          recollectEndHandler();
        })
        .always(function () {});
    });
    // init
    onExifRadioShowChangeHandler();
  };

  SettingsController.prototype.prepareGalleryForClone = function () {
    this.saveWatermarks(2);
  };

  SettingsController.prototype.initMosaicConfig = function () {
    var $displayAllInp = $('#mosaic-display-all-images input'),
      $showHiddenInp = $('#mosaic-show-hidden-images-row input'),
      $mosaicDisplayAllImgCheckBox = $('#mosaicDisplayAllImg'),
      $mosaicShowHiddenImgCheckBox = $('#mosaicShowHiddenImages');
    function toogleImageCountParams(event, $currCheckbox) {
      var $diplayAllRow = $('#mosaic-display-all-images-row'),
        $hiddenImageRow = $('#mosaic-show-hidden-images-row');
      if ($currCheckbox.attr('name') == $mosaicShowHiddenImgCheckBox.attr('name')) {
        if ($currCheckbox.is(':checked')) {
          // change another checkbox visibility
          $diplayAllRow.addClass('ggSettingsDisplNone');
          $mosaicDisplayAllImgCheckBox.prop('checked', false);
        } else {
          $diplayAllRow.removeClass('ggSettingsDisplNone');
        }
      } else if ($currCheckbox.attr('name') == $mosaicDisplayAllImgCheckBox.attr('name')) {
        if ($currCheckbox.is(':checked')) {
          // change another checkbox visibility
          $hiddenImageRow.addClass('ggSettingsDisplNone');
          $mosaicShowHiddenImgCheckBox.prop('checked', false);
        } else {
          $hiddenImageRow.removeClass('ggSettingsDisplNone');
        }
      }
    }
    $showHiddenInp.on('click', function (event) {
      toogleImageCountParams(event, $(this));
    });
    $displayAllInp.on('click', function (event) {
      toogleImageCountParams(event, $(this));
    });
    // init visibility, when load gallery config
    toogleImageCountParams(null, $showHiddenInp);
    toogleImageCountParams(null, $displayAllInp);
  };

  SettingsController.prototype.initCaptionAdditionalSett = function () {
    var $tableAddEffectEnbl = $('.captAdditEffectSettEnbl'),
      $rowShrink = $('#growShrinkEnableRow'),
      $rowOpacityFade = $('#opacityFadeEnabledRow'),
      $rowSquareToCircle = $('#squareToCircleEnabledRow'),
      $rowRotateDeg = $('#rotateDegreeEnabledRow'),
      $inpEnblShrink = $('#captionEffSettGrowShrinkEnable'),
      $inpEnblOpacityFade = $('#captionEffSettOpacityFadeEnabled'),
      $inpEnblSquareToCrcl = $('#captionEffSettSquareToCircleEnabled'),
      $inpEnblRotateDeg = $('#captionEffSettRotateDegreeEnabled');
    function applyToPreview() {
      var styleStr = '';
      if ($tableAddEffectEnbl.filter(':checked').attr('id') == 'captEffSettEnable') {
        var sggFigureTransformScaleVal = '',
          sggFigureTransformRotateVal = '';
        styleStr += '#preview .grid-gallery-caption[data-grid-gallery-type] figcaption {top: 0;left: 0;right: 0;bottom: 0;}';
        if ($inpEnblShrink.is(':checked')) {
          var $selectedRadFromGrowShrink = $('.sggCasGrowSrinkRad:checked'),
            growVal = parseInt($('#captionEffSettGrowShrinkVal').val());
          if ($selectedRadFromGrowShrink.attr('id') == 'captEffGrowType') {
            sggFigureTransformScaleVal = 'scale(' + (1 + growVal / 100) + ')';
          } else {
            sggFigureTransformScaleVal = 'scale(' + (1 - growVal / 100) + ')';
          }
        }
        if ($inpEnblOpacityFade.is(':checked')) {
          var opacityFadeVal = parseInt($('#captionEffSettOpacityFade').val()) / 100;
          styleStr +=
            '#preview .grid-gallery-caption[data-grid-gallery-type] figcaption {opacity:' +
            opacityFadeVal +
            ';}' +
            '#preview .grid-gallery-caption[data-grid-gallery-type]:hover figcaption,' +
            '#preview .grid-gallery-caption[data-grid-gallery-type].hovered figcaption,' +
            '#preview .grid-gallery-caption[data-grid-gallery-type]:active figcaption {opacity: 1;}';
        }
        if ($inpEnblSquareToCrcl.is(':checked')) {
          var squareToCircleVal = parseInt($('#captionEffSettSquareToCircle').val());
          styleStr +=
            '#preview .grid-gallery-caption[data-grid-gallery-type]:hover,' +
            '#preview .grid-gallery-caption[data-grid-gallery-type].hovered,' +
            '#preview .grid-gallery-caption[data-grid-gallery-type]:active {' +
            '-webkit-border-radius: ' +
            squareToCircleVal +
            '% !important;' +
            '-moz-border-radius: ' +
            squareToCircleVal +
            '% !important;' +
            'border-radius: ' +
            squareToCircleVal +
            '% !important;}';
        }
        if ($inpEnblRotateDeg.is(':checked')) {
          var rotateDegVal = parseInt($('#captionEffSettRotateDegree').val());
          sggFigureTransformRotateVal = 'rotateZ(-' + rotateDegVal + 'deg)';
        }
        if (sggFigureTransformScaleVal || sggFigureTransformRotateVal) {
          var sggFigureTransformFullValue = sggFigureTransformScaleVal + ' ' + sggFigureTransformRotateVal;

          styleStr +=
            '#preview .grid-gallery-caption[data-grid-gallery-type]:hover,' +
            '#preview .grid-gallery-caption[data-grid-gallery-type].hovered,' +
            '#preview .grid-gallery-caption[data-grid-gallery-type]:active {' +
            'z-index: 999999;-webkit-transform:' +
            sggFigureTransformFullValue +
            ';' +
            '-ms-transform:' +
            sggFigureTransformFullValue +
            ';' +
            'transform:' +
            sggFigureTransformFullValue +
            ';}';
        }
      }
      $('#additionalCaptionSettStyleElem').html(styleStr);
    }

    // if "Caption Aditional Settings" changed, then run this function
    function casChangeTableVisibilityHandler() {
      var $currCheckedRadio = $tableAddEffectEnbl.filter(':checked');

      if ($currCheckedRadio.length) {
        if ($currCheckedRadio.attr('id') == 'captEffSettEnable') {
          $rowShrink.show();
          $rowOpacityFade.show();
          $rowSquareToCircle.show();
          $rowRotateDeg.show();
        } else if ($currCheckedRadio.attr('id') == 'captEffDisable') {
          $rowShrink.hide();
          $rowOpacityFade.hide();
          $rowSquareToCircle.hide();
          $rowRotateDeg.hide();
        }
      }
      applyToPreview();
    }
    // for Enable checkboxInput
    function casEnableCheckBoxVisibilityHandler($checkBoxInput) {
      var $divElementToHide = $checkBoxInput.closest('td').find('.sggAdminCollapseElement');

      if ($checkBoxInput.is(':checked')) {
        $divElementToHide.show();
      } else {
        $divElementToHide.hide();
      }
      applyToPreview();
    }
    // adding event handlers
    $tableAddEffectEnbl.on('ifChecked', casChangeTableVisibilityHandler);
    //
    $inpEnblShrink.on('ifChecked ifUnchecked', function (event) {
      casEnableCheckBoxVisibilityHandler($inpEnblShrink);
    });
    $inpEnblOpacityFade.on('ifChecked ifUnchecked', function (event) {
      casEnableCheckBoxVisibilityHandler($inpEnblOpacityFade);
    });
    $inpEnblSquareToCrcl.on('ifChecked ifUnchecked', function (event) {
      casEnableCheckBoxVisibilityHandler($inpEnblSquareToCrcl);
    });
    $inpEnblRotateDeg.on('ifChecked ifUnchecked', function (event) {
      casEnableCheckBoxVisibilityHandler($inpEnblRotateDeg);
    });
    // initial run
    casChangeTableVisibilityHandler();
    casEnableCheckBoxVisibilityHandler($inpEnblShrink);
    casEnableCheckBoxVisibilityHandler($inpEnblOpacityFade);
    casEnableCheckBoxVisibilityHandler($inpEnblSquareToCrcl);
    casEnableCheckBoxVisibilityHandler($inpEnblRotateDeg);

    $('.sggInpNumberForCes').on('change', function (event) {
      applyToPreview();
    });
    $('.sggCasGrowSrinkRad').on('ifChanged', function () {
      // at Once runned two events
      if ($(this).is(':checked')) {
        applyToPreview();
      }
    });
  };

  function ggStringCapitalize(str1) {
    if (str1.length > 1) {
      return str1[0].charAt(0).toUpperCase() + str1.slice(1);
    }
    return str1[0].charAt(0).toUpperCase();
  }

  $(document).ready(function () {
    var Ctrl = new SettingsController();

    $('#catsNavTrans').on('change', $.proxy(Ctrl.toggleCatsContainerBg, Ctrl)).trigger('change');

    $('#catsTextTrans').on('change', $.proxy(Ctrl.toggleCatsTextBg, Ctrl)).trigger('change');

    $('#catsThemeColor').on('change', $.proxy(Ctrl.toggleCatsTextColor, Ctrl)).trigger('change');

    $('#pagHideContainerBg').on('change', $.proxy(Ctrl.togglePagContainerBg, Ctrl)).trigger('change');

    $('#pagHideTextBackground').on('change', $.proxy(Ctrl.togglePagTextBg, Ctrl)).trigger('change');

    $('#pagHideTextColor').on('change', $.proxy(Ctrl.togglePagTextColor, Ctrl)).trigger('change');

    $('#preloadColor').on('change', $.proxy(Ctrl.prePickerChange, Ctrl)).trigger('change');

    // Open preload icon dialog
    $('#choosePreicon').on('click', Ctrl.openPreiconDialog);

    $('#editAttributes').on('click', Ctrl.openAttributesDialog);

    Ctrl.toggleCats();
    Ctrl.togglePages();
    Ctrl.toggleButtons();
    Ctrl.toggleShowMore();
    Ctrl.initButtonsEditorDialog();
    Ctrl.toggleFullscreen();

    Ctrl.initPreiconDialog();
    Ctrl.initPreiconSelect();
    Ctrl.togglePreload();
    Ctrl.initAttributesDialog();
    Ctrl.toggleAttributes();
    Ctrl.waterMarkInit();
    Ctrl.waterMarkCheckChanges();
    Ctrl.exifInit();
    Ctrl.thumnailOverlayInit();
    Ctrl.setCaptionOnHoverImage();
    Ctrl.initMosaicConfig();
    Ctrl.initCaptionAdditionalSett();

    app.proSettings = Ctrl;

    //create polaroid effect after page load
    if ($('#captionBuilderPolaroidEffect').val() == 'true' && $('#preview figure img').is(':visible')) {
      $('#effectsPreview [data-grid-gallery-type="polaroid"]').trigger('click');
    }
  });

  function validateUrl(userInput) {
    var regexQuery = '/(http(s)?://.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/';
    var url = new RegExp(regexQuery, 'g');
    if (url.test(userInput)) {
      alert('Great, you entered an E-Mail-address');
      return true;
    }
    return false;
  }

  function gallerySettingsProHandler(event, gallerySettingsFree) {
    if (!gallerySettingsFree) {
      return;
    }
    var GallerySettingsPro = gallerySettingsFree.constructor.prototype;

    GallerySettingsPro.callImgPreviewFreeFunc = function (funcName, funcParams) {
      if (app && app.ImagePreview && app.ImagePreview[funcName]) {
        app.ImagePreview[funcName].call(funcParams);
      }
    };

    GallerySettingsPro.captionBuilderInit = function () {
      this.gsProIsInited = false;

      // variables
      if ($('.ggUserCaptionBuilderCl:checked').val() == '0') {
        return;
      }

      var ggSelfSett = this,
        cbTdiChangedTimeout = null,
        $cbBackgroundEnable = $('#captionBuilderBackgroundEnable'),
        $cbTitleEnable = $('#captionBuilderTitleEnable'),
        $cbDescriptionEnable = $('#captionBuilderDescriptionEnable'),
        $cbIconsEnable = $('#captionBuilderIconsEnable');
      // background table
      $cbBackgroundEnable.on('click', function () {
        ggSelfSett.captionBuilderRowsVisibility($cbBackgroundEnable, '.sgg-caption-builder[name="captions-builder-background"] tr:not(:first-child)');
        ggSelfSett.changeSettBgEnableHandler();
      });
      // title table
      $cbTitleEnable.on('click', function () {
        ggSelfSett.captionBuilderRowsVisibility($cbTitleEnable, '.sgg-caption-builder[name="captions-builder-title"] tr:not(:first-child)');

        ggSelfSett.captionBuilderSetOtherPositions('description');
        ggSelfSett.captionBuilderSetOtherPositions('icons');

        if (app.ImagePreview) {
          if (app.ImagePreview.cbTdiEnableChangeHandler) {
            app.ImagePreview.cbTdiEnableChangeHandler();
          }
          if (app.ImagePreview.cbTdiPositionChangeHandler) {
            app.ImagePreview.cbTdiPositionChangeHandler();
          }
        }
      });
      // description table
      $cbDescriptionEnable.on('click', function () {
        ggSelfSett.captionBuilderRowsVisibility($cbDescriptionEnable, '.sgg-caption-builder[name="captions-builder-description"] tr:not(:first-child)');

        ggSelfSett.captionBuilderSetOtherPositions('title');
        ggSelfSett.captionBuilderSetOtherPositions('icons');

        if (app.ImagePreview) {
          if (app.ImagePreview.cbTdiEnableChangeHandler) {
            app.ImagePreview.cbTdiEnableChangeHandler();
          }
          if (app.ImagePreview.cbTdiPositionChangeHandler) {
            app.ImagePreview.cbTdiPositionChangeHandler();
          }
        }
      });
      // icons table
      $cbIconsEnable.on('ifChanged', function () {
        ggSelfSett.captionBuilderRowsVisibility($cbIconsEnable, '.sgg-caption-builder[name="captions-builder-icons"] tr:not(:first-child)');

        ggSelfSett.captionBuilderSetOtherPositions('title');
        ggSelfSett.captionBuilderSetOtherPositions('description');

        if (app.ImagePreview) {
          if (app.ImagePreview.cbTdiEnableChangeHandler) {
            app.ImagePreview.cbTdiEnableChangeHandler();
          }
          if (app.ImagePreview.cbTdiPositionChangeHandler) {
            app.ImagePreview.cbTdiPositionChangeHandler();
          }
          // reinit Icons Functional
          ggSelfSett.callImgPreviewFreeFunc('updateIcons', null);
        }
      });

      // Visibility init
      ggSelfSett.captionBuilderRowsVisibility($cbBackgroundEnable, '.sgg-caption-builder[name="captions-builder-background"] tr:not(:first-child)');
      ggSelfSett.captionBuilderRowsVisibility($cbTitleEnable, '.sgg-caption-builder[name="captions-builder-title"] tr:not(:first-child)');
      ggSelfSett.captionBuilderRowsVisibility($cbDescriptionEnable, '.sgg-caption-builder[name="captions-builder-description"] tr:not(:first-child)');
      ggSelfSett.captionBuilderRowsVisibility($cbIconsEnable, '.sgg-caption-builder[name="captions-builder-icons"] tr:not(:first-child)');

      // start select Position by default
      if ($('input[type="radio"][name="captionBuilder[title][position]"]:checked').length == 0) {
        $('label[for="captBuilderTitlePositionCenterCenter"]').trigger('click');
      }
      if ($('input[type="radio"][name="captionBuilder[description][position]"]:checked').length == 0) {
        $('label[for="captBuilderDescriptionPositionCenterCenter"]').trigger('click');
      }
      if ($('input[type="radio"][name="captionBuilder[description][position]"]:checked').length == 0) {
        $('label[for="iconsPositionCenterCenter"]').trigger('click');
      }

      // fix start position
      ggSelfSett.captionBuilderSetOtherPositions();

      $('.captionBuilderPosRadio').on('ifChanged', function () {
        if (!ggSelfSett.lockCaptionBuilderPosition) {
          ggSelfSett.lockCaptionBuilderPosition = true;
          var $this = $(this);

          // use this function once (at checked = true event)
          if ($this.prop('checked')) {
            var captionBuilderName = $this.attr('id');
            if (captionBuilderName.indexOf('captBuilderIconsPosition') >= 0) {
              ggSelfSett.captionBuilderSetOtherPositions('icons');
            } else if (captionBuilderName.indexOf('captBuilderDescriptionPosition') >= 0) {
              ggSelfSett.captionBuilderSetOtherPositions('description');
            } else if (captionBuilderName.indexOf('captBuilderTitlePosition') >= 0) {
              ggSelfSett.captionBuilderSetOtherPositions('title');
            }
          }

          if (app.ImagePreview && app.ImagePreview.cbTdiPositionChangeHandler) {
            if (cbTdiChangedTimeout) {
              clearTimeout(cbTdiChangedTimeout);
            }
            cbTdiChangedTimeout = setTimeout(function () {
              app.ImagePreview.cbTdiPositionChangeHandler.apply(app.ImagePreview, []);
              cbTdiChangedTimeout = null;
            }, 300);
          }
          ggSelfSett.lockCaptionBuilderPosition = false;
        }
      });

      ggSelfSett.captionBuilderIconsSelectDialog();

      // show all tables
      $('.sgg-caption-builder').removeClass('sggSettDisplNone');
      this.polaroidEffectSettings();

      // Open effects dialog
      $('#captionBuilderChooseEffect').on('click', function () {
        $('#effectDialog').dialog('open');
      });

      $('#captionBuilderSelectIconEffect').on('click', function (event) {
        event.preventDefault();
        var $iconEffectDialog = $('#iconsPreview');
        if ($iconEffectDialog.dialog) {
          $('#iconsPreview').dialog('open');
        }
      });

      $('.ggCaptionBuilderWrap input[name="thumbnail[overlay][backgroundForShowOnHover]"], ' + '.ggCaptionBuilderWrap input[name="thumbnail[overlay][background]"], ' + '.ggCaptionBuilderWrap select[name="thumbnail[overlay][transparency]"]').on('change', function () {
        ggSelfSett.imagePreviewCbChangeValue();
      });
    };

    GallerySettingsPro.captionSettingsTypeInit = function () {
      var self1 = this;
      this.cbTypeChangeLock = false;

      $('.ggUserCaptionBuilderCl')
        .on('ifChanged', function (event) {
          var $captionSettType = $(this);
          // in this function value of $captionBuilderSelector changed, and need to "lock" method entry
          if (!self1.cbTypeChangeLock && $captionSettType.is(':checked')) {
            self1.cbTypeChangeLock = true;
            var confirmRes = true,
              $captionAndIcons = $('.ggCaptionBuilderWrap .caption-type[data-gg-cb-type="captions-icons"]'),
              $captionBuilder = $('.ggCaptionBuilderWrap .caption-type[data-gg-cb-type="caption-builder"]');
            // show message only if user change value
            if (self1.gsProIsInited) {
              confirmRes = confirm('After changing "Use Caption Builder" option all changes will be saved, and you can changing Caption options. Do you want to change this option?');
            }
            if (confirmRes) {
              if ($captionSettType.val() == 1) {
                $captionAndIcons.hide();
                $captionBuilder.show();
                $captionAndIcons.find('input, select').prop('disabled', true);
                $captionBuilder.find('input, select').prop('disabled', false);

                $('#captionBuilderOnceUsed').val('1');
                self1.captionBuilderTypeChangeHandler(1);
              } else {
                $captionAndIcons.show();
                $captionBuilder.hide();
                $captionAndIcons.find('input, select').prop('disabled', false);
                $captionBuilder.find('input, select').prop('disabled', true);
                self1.captionBuilderTypeChangeHandler(0);
              }
              if (self1.gsProIsInited) {
                // save gallery changes
                $('#btnSave').trigger('click');
              }
            } else {
              // set another Select value - for reset
              if ($captionSettType.val() == '0') {
                $captionSettType.val('1');
              } else {
                $captionSettType.val('0');
              }
            }
            self1.cbTypeChangeLock = false;
          }
        })
        .trigger('ifChanged');

      this.gsProIsInited = true;
    };

    GallerySettingsPro.polaroidEffectSettings = function () {
      var self1 = this;
      // on change handler
      $('#captionBuilderPolaroidEffect')
        .on('change', function (event) {
          event.preventDefault();
          var $overlayEffect = $('#overlayEffect').val(),
            $polaroidSettings = $('#captionBuilderPolaroidAnimation, #captionBuilderPolaroidScattering, #captionBuilderPolaroidFrameWidth').closest('tr');

          if ($(this).val() == 'true') {
            $polaroidSettings.removeClass('hidden');
            if ($overlayEffect !== 'polaroid') {
              var confirms = confirm('Caption Effect will change to Polaroid Style. Some settings, such as background Color, Transparency, caption paddings, as well as the shadows will be automatically changed.');
              if (confirms) {
                if (app && app.SettingsController && app.SettingsController['setPolaroidDefaultSettings']) {
                  app.SettingsController.setPolaroidDefaultSettings();
                }
                $('#effectsPreview [data-grid-gallery-type="polaroid"]').trigger('click');
              } else {
                $('#captionBuilderPolaroidEffect').val('false');
                $polaroidSettings.addClass('hidden');
                $('#preview .grid-gallery-caption figcaption').css({ padding: '0' });
              }
            }
          } else {
            $polaroidSettings.addClass('hidden');
            $('#preview .grid-gallery-caption figcaption').css({ padding: '0' });
            if ($overlayEffect == 'polaroid') {
              // set default effect as "none"
              $('#effectsPreview [data-grid-gallery-type]:first').trigger('click');
            }
          }
        })
        .trigger('change');

      $('#captionBuilderPolaroidAnimation, #captionBuilderPolaroidScattering, #captionBuilderPolaroidFrameWidth').on('change', function (event) {
        event.preventDefault();
        clearTimeout($('#preview.gallery-preview').data('polaroidDebounce'));
        $('#preview.gallery-preview').data(
          'polaroidDebounce',
          setTimeout(function () {
            app.ImagePreview.initCaptionEffects();
          }, 300)
        );
      });
    };

    GallerySettingsPro.captionBuilderTypeChangeHandler = function (cbType) {
      var self2 = this,
        $captionsSubTab = $('.caption-type[data-gg-cb-type="captions-icons"]'),
        $captionBuilderTab = $('.caption-type[data-gg-cb-type="caption-builder"]'),
        // 0
        $ciForShowOnHover = $captionsSubTab.find('input[name="thumbnail[overlay][backgroundForShowOnHover]"]'),
        $ciThumbnailBackground = $captionsSubTab.find('input[name="thumbnail[overlay][background]"]'),
        $ciThumbnailTransparency = $captionsSubTab.find('select[name="thumbnail[overlay][transparency]"]'),
        // 1
        $cbForShowOnHover = $captionBuilderTab.find('input[name="thumbnail[overlay][backgroundForShowOnHover]"]'),
        $cbThumbnailBackground = $captionBuilderTab.find('input[name="thumbnail[overlay][background]"]'),
        $cbThumbnailTransparency = $captionBuilderTab.find('select[name="thumbnail[overlay][transparency]"]');
      if (cbType == 1) {
        // show on hover image
        $cbForShowOnHover.val($ciForShowOnHover.val());
        self2.changeColopickerBg($cbForShowOnHover);
        $cbThumbnailBackground.val($ciThumbnailBackground.val());
        self2.changeColopickerBg($cbThumbnailBackground);
        $cbThumbnailTransparency.val($ciThumbnailTransparency.val());
      } else {
        if ($('#captionBuilderBackgroundEnable:checked').length) {
          // show on hover image
          $ciForShowOnHover.val($cbForShowOnHover.val());
          self2.changeColopickerBg($ciForShowOnHover);
          $ciThumbnailBackground.val($cbThumbnailBackground.val());
          self2.changeColopickerBg($ciThumbnailBackground);
          $ciThumbnailTransparency.val($cbThumbnailTransparency.val());
        }
      }
      self2.imagePreviewCbChangeValue();
    };

    GallerySettingsPro.captionBuilderIconsSelectDialog = function () {
      var $cbIconSelectdialog = $('#captionBuilderIconsSelect');
      // init dialog
      $cbIconSelectdialog.dialog({
        autoOpen: false,
        modal: true,
        width: 640,
        height: $(window).height() * 0.9,
        buttons: {
          Cancel: function () {
            $(this).dialog('close');
          },
        },
      });

      function previewIcons() {
        var popupIcon = $('i#popupFontAwesomeClass'),
          linkIcon = $('i#linkFontAwesomeClass'),
          videIcon = $('i#videoFontAwesomeClass'),
          downloadIcon = $('i#downloadFontAwesomeClass'),
          popupIconHidden = $('input#popupIconFaClass'),
          linkIconHidden = $('input#linkIconFaClass'),
          videoIconHidden = $('input#videoIconFaClass'),
          downloadIconHidden = $('input#downloadIconFaClass');

        popupIcon.attr('class', 'fa mp-icon-preview ' + popupIconHidden.val());
        linkIcon.attr('class', 'fa mp-icon-preview ' + linkIconHidden.val());
        videIcon.attr('class', 'fa mp-icon-preview ' + videoIconHidden.val());
        downloadIcon.attr('class', 'fa mp-icon-preview ' + downloadIconHidden.val());
      }

      $cbIconSelectdialog.on('dialogclose', function ($event) {
        previewIcons();
      });

      $('.icon-fa-select').on('click', function () {
        var iconType = $(this).data('icon-type');
        if (iconType) {
          var currIconSelected = $('#' + iconType + 'IconFaClass').val();
          $('#captionBuilderIconsSelect .gg-set-fa-one-elem').removeClass('active');
          // set previous value, if exists
          if (currIconSelected) {
            $('#captionBuilderIconsSelect .gg-set-fa-one-elem[data-fa-name="' + currIconSelected + '"]').addClass('active');
          }
          $cbIconSelectdialog.attr('data-icon-type', iconType);
          $cbIconSelectdialog.dialog('open');
        }
      });

      $('#captionBuilderIconsSelect .gg-set-fa-one-elem').on('click', function () {
        // get curr value
        var currIconValue = $(this).data('fa-name'),
          currIconType = $cbIconSelectdialog.attr('data-icon-type');
        // save value
        if (currIconValue && currIconType) {
          var $iconInModalWindowPreview = $('#iconsPreview .hi-icon'),
            $iconInGgPreview = $('#preview .hi-icon[data-icon-type="' + currIconType + '"]'),
            prevClassToRemove = $('#' + currIconType + 'IconFaClass').val();
          // remove icon class in icons effect window
          if (currIconType == 'popup') {
            $iconInModalWindowPreview.removeClass(prevClassToRemove);
          }
          $iconInGgPreview.removeClass(prevClassToRemove);

          $('#' + currIconType + 'IconFaClass').val(currIconValue);
          $cbIconSelectdialog.dialog('close');

          // add icon class in icons effect window
          if (currIconType == 'popup') {
            $iconInModalWindowPreview.addClass(currIconValue);
          }
          $iconInGgPreview.addClass(currIconValue);
        }
      });
    };

    GallerySettingsPro.captionBuilderSetOtherPositions = function (firstCaptionBuilderItemStr) {
      if (!firstCaptionBuilderItemStr) {
        firstCaptionBuilderItemStr = 'title';
      }

      var checkedCaptionBuilderCount = parseInt($('#captionBuilderTitleEnable').prop('checked') ? 1 : 0) + parseInt($('#captionBuilderDescriptionEnable').prop('checked') ? 1 : 0) + parseInt($('#captionBuilderIconsEnable').prop('checked') ? 1 : 0),
        posArray = {
          bottom: 'bottom',
          top: 'top',
          center: 'center',
          myCountProp: 3,
        },
        captionBuilderItems = ['title', 'description', 'icons'],
        itemPos = captionBuilderItems.indexOf(firstCaptionBuilderItemStr);

      // set current Item to first position (priority to reserve Position)
      if (itemPos >= 0) {
        captionBuilderItems.splice(itemPos, 1);
        captionBuilderItems.unshift(firstCaptionBuilderItemStr);
      }

      for (var cbPositionTypeInd in captionBuilderItems) {
        var cbPositionTypeStr = captionBuilderItems[cbPositionTypeInd],
          cbPositionTypeCapitalizeStr = ggStringCapitalize(cbPositionTypeStr),
          $cbInputRadio = $('#captionBuilder' + cbPositionTypeCapitalizeStr + 'Enable'),
          $cbInputRadioPosition = $('input[type="radio"][name="captionBuilder[' + cbPositionTypeStr + '][position]"]:checked');
        if ($cbInputRadio.length && $cbInputRadio.prop('checked') && $cbInputRadioPosition.length) {
          var inputPosCodeStr = $cbInputRadioPosition.val(),
            inputPosCodeArr = inputPosCodeStr.split('_');
          if (inputPosCodeArr.length > 1) {
            if (posArray.myCountProp == 3) {
              delete posArray[inputPosCodeArr[1]];
              posArray.myCountProp--;
            } else {
              // check can use current position
              if (inputPosCodeArr[1] in posArray) {
                delete posArray[inputPosCodeArr[1]];
                posArray.myCountProp--;
              } else {
                // need to change position
                $cbInputRadioPosition.prop('checked', false);
                var selectorRadioToCheck = 'captBuilder' + cbPositionTypeCapitalizeStr + 'Position' + ggStringCapitalize(inputPosCodeArr[0]);

                if ('top' in posArray) {
                  $('label[for="' + selectorRadioToCheck + 'Top' + '"]').trigger('click');
                  delete posArray['top'];
                  posArray.myCountProp--;
                } else if ('bottom' in posArray) {
                  $('label[for="' + selectorRadioToCheck + 'Bottom' + '"]').trigger('click');
                  delete posArray['bottom'];
                  posArray.myCountProp--;
                } else {
                  $('label[for="' + selectorRadioToCheck + 'Center' + '"]').trigger('click');
                  delete posArray['center'];
                  posArray.myCountProp--;
                }
              }
            }
          }
        }
      }
    };

    GallerySettingsPro.changeColopickerBg = function ($colopickerInput) {
      if ($colopickerInput && $colopickerInput.length) {
        var $colorLink = $colopickerInput.closest('.wp-picker-container').find('.wp-color-result');
        if ($colorLink && $colorLink.length) {
          var newColor = $colopickerInput.val();
          $colorLink.css('background-color', newColor);
        }
      }
    };

    GallerySettingsPro.imagePreviewCbChangeValue = function () {
      if (app.ImagePreview && app.ImagePreview.captionBuilderChangeValueHandler) {
        app.ImagePreview.captionBuilderChangeValueHandler();
      }
    };
    GallerySettingsPro.changeSettBgEnableHandler = function () {
      if (app.ImagePreview) {
        if (app.ImagePreview.cbBackgroundEnableChangeHandler) {
          app.ImagePreview.cbBackgroundEnableChangeHandler();
        }
      }
    };

    GallerySettingsPro.captionBuilderRowsVisibility = function ($checkBox, toogleSelector) {
      if ($checkBox.prop('checked')) {
        $(toogleSelector).removeClass('sggSettDisplNone');
      } else {
        $(toogleSelector).addClass('sggSettDisplNone');
      }
    };

    GallerySettingsPro.popupThemeChanged = function (themeCode) {
      var $showPopupRotateBtnRow = $('#showPopupRotateBtnRow'),
        $showPopupLinkBtnRow = $('#showPopupLinkBtnRow'),
        $showPopupThumbnailOnHoverRow = $('#showPopupThumbnailOnHoverRow'),
        $showPopupRotateBtn = $('#showPopupRotateBtn'),
        $showPopupLinkBtn = $('#showPopupLinkBtn'),
        $showPopupThumbnailOnHover = $('#showPopupThumbnailOnHover');

      if (themeCode == 'theme_6') {
        $showPopupRotateBtnRow.removeClass('hidden');
        $showPopupLinkBtnRow.removeClass('hidden');
        $showPopupThumbnailOnHoverRow.removeClass('hidden');
      } else {
        // hide rows
        $showPopupRotateBtnRow.addClass('hidden');
        $showPopupLinkBtnRow.addClass('hidden');
        $showPopupThumbnailOnHoverRow.addClass('hidden');
        // uncheck values for iCheck
        $showPopupRotateBtn.iCheck('uncheck');
        $showPopupLinkBtn.iCheck('uncheck');
        $showPopupThumbnailOnHover.iCheck('uncheck');
      }
    };

    GallerySettingsPro.checkCurrentSettPage = function (params) {
      var qs = new URI().query(true);
      if (qs.module === 'galleries') {
        // if settings
        if (qs.action === 'settings') {
          GallerySettingsPro.captionBuilderInit();
          GallerySettingsPro.captionSettingsTypeInit();
        }
      }
      // init popup theme_6 rows: rotateButton, linkButton and ImageOnHoverThumb
      this.popupThemeChanged($('#bigImageTheme').val());
    };

    GallerySettingsPro.checkCurrentSettPage();
  }
  function galleryImagePreviewProHandler(event, imagePreviewFree) {
    if (!imagePreviewFree) {
      return;
    }
    var ImagePreviewPro = imagePreviewFree.constructor.prototype;

    ImagePreviewPro.isCaptionBuilderEnabled = function () {
      // check value by preview param
      var captBuilderValue = this.$previewCaption.attr('data-caption-buider');
      return captBuilderValue == '1';
    };

    ImagePreviewPro.isCbBackgroundEnabled = function () {
      return $('#captionBuilderBackgroundEnable:checked').length == 1;
    };
    ImagePreviewPro.isCbTitleEnabled = function () {
      return $('#captionBuilderTitleEnable:checked').length == 1;
    };
    ImagePreviewPro.isCbDescriptionEnabled = function () {
      return $('#captionBuilderDescriptionEnable:checked').length == 1;
    };
    ImagePreviewPro.isCbIconsEnabled = function () {
      return $('#captionBuilderIconsEnable:checked').length == 1;
    };

    ImagePreviewPro.initBackgroundEnableChangeHandler = function () {
      if (this.isCbBackgroundEnabled()) {
        var fields = {
          effect: 'thumbnail[overlay][effect]',
          bg: 'thumbnail[overlay][background]',
          opacity: 'thumbnail[overlay][transparency]',
        };
        this.previewCaptionShow(fields);
      } else if (this.isCbTitleEnabled() || this.isCbDescriptionEnabled() || this.isCbIconsEnabled()) {
        // if bg disabled - always show as caption-effect "none"
        this.$previewCaption.attr('data-grid-gallery-type', 'none');
        this.$previewFigCaption.css({ top: 0, bottom: 0 });
        this.$previewFigCaption.show();
      } else {
        this.previewCaptionHide();
      }
    };

    ImagePreviewPro.initCurrSettingsPro = function () {
      // init variables
      this.$previewCaption = $('#preview .grid-gallery-caption');
      this.$previewFigCaption = this.$previewCaption.find('figcaption');
      //
      $.extend(this.previewSettingsKeys, {
        'caption-builder-title': {
          height: '#captionBuilderTitleHeight',
          position: '.captionBuilderPosRadio[name="captionBuilder[title][position]"]',
          'padding-left': '#thumbnailOverlayPaddingsLeft',
          'padding-right': '#thumbnailOverlayPaddingsRight',
          'padding-top': '#thumbnailOverlayPaddingsTop',
          'padding-bottom': '#thumbnailOverlayPaddingsBottom',
          'font-family': '#thumbnailOverlayFontFamily',
          'font-size-num': '#thumbnailOverlayTextSize',
          'font-size-unit': '#thumbnailOverlayTextSizeUnit',
          color: '#thumbnailOverlayFontColor',
          isBold: '#thumbnailOverlayFontStyleBold',
          isItalic: '#thumbnailOverlayFontStyleItalic',
          isLine: '#thumbnailOverlayFontStyleLine',
          'text-hl-color': '#captionBuilderTitleBgColor',
          'text-hl-color-transparency': '#captionBuilderTitleBgColorTransparency',
        },
        'caption-builder-description': {
          height: '#captionBuilderDescriptionHeight',
          position: '.captionBuilderPosRadio[name="captionBuilder[description][position]"]',
          'padding-left': '#captionBuilderDescriptionPaddingsLeft',
          'padding-right': '#captionBuilderDescriptionPaddingsRight',
          'padding-top': '#captionBuilderDescriptionPaddingsTop',
          'padding-bottom': '#captionBuilderDescriptionPaddingsBottom',
          'font-family': '#captionBuilderDescriptionFontFamily',
          'font-size-num': '#captionBuilderDescriptionFontSize',
          'font-size-unit': '#captionBuilderDescriptionFontSizeUnit',
          color: '#captionBuilderDescriptionFontColor',
          isBold: '#captBuildDescriptionFontStyleBold',
          isItalic: '#captBuildDescriptionFontStyleItalic',
          isLine: '#captBuildDescriptionFontStyleLine',
          'text-hl-color': '#captBuildDescriptionBgColor',
          'text-hl-color-transparency': '#captBuildDescriptionBgColorTransparency',
        },
        'caption-builder-icons': {
          height: '#captionBuilderIconsHeight',
          position: '.captionBuilderPosRadio[name="captionBuilder[icons][position]"]',
          'padding-left': '.sgg-caption-builder #iconsPaddingsLeft',
          'padding-right': '.sgg-caption-builder #iconsPaddingsRight',
          'padding-top': '.sgg-caption-builder #iconsPaddingsTop',
          'padding-bottom': '.sgg-caption-builder #iconsPaddingsBottom',
          'icon-popup': '.sgg-caption-builder #popupIconFaClass',
          'icon-size-num': '.sgg-caption-builder #iconsSize',
          'icon-color': '.sgg-caption-builder #iconsColor',
          'icon-color-hover': '.sgg-caption-builder #iconsHoverColor',
          'icon-transparency': '.sgg-caption-builder #iconsTransparency',
          'icon-bg-color': '.sgg-caption-builder #iconsBackgroundColor',
          'icon-bg-color-hover': '.sgg-caption-builder #iconsBackgroundHoverColor',
          'icon-bg-color-transparency': '.sgg-caption-builder #iconsBgTransparency',
          'text-hl-color': '#captBuildIconsBgColor',
          'text-hl-color-transparency': '#captBuildIconsBgColorTransparency',
          'margin-all': '.sgg-caption-builder #iconsMargin',
          'icon-animation': '.sgg-caption-builder #iconsEffectName',
        },
      });
      this.objectsData.init({
        keys: this.previewSettingsKeys,
      });

      this.initItemsChangeHandler();
    };

    ImagePreviewPro.initItemsChangeHandler = function () {
      var sIpp = this;
      // height
      $('.sgg-caption-builder #captionBuilderTitleHeight').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { height: null });
      });
      $('.sgg-caption-builder #captionBuilderDescriptionHeight').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { height: null });
      });
      $('.sgg-caption-builder #captionBuilderIconsHeight').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { height: null });
      });
      // paddings
      $('.sgg-caption-builder #thumbnailOverlayPaddingsLeft, .sgg-caption-builder #thumbnailOverlayPaddingsRight, .sgg-caption-builder #thumbnailOverlayPaddingsTop, .sgg-caption-builder #thumbnailOverlayPaddingsBottom').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { padding: null });
      });
      $('#captionBuilderDescriptionPaddingsLeft, #captionBuilderDescriptionPaddingsRight, #captionBuilderDescriptionPaddingsTop, #captionBuilderDescriptionPaddingsBottom').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { padding: null });
      });
      $('.sgg-caption-builder #iconsPaddingsLeft, .sgg-caption-builder #iconsPaddingsRight, .sgg-caption-builder #iconsPaddingsTop, .sgg-caption-builder #iconsPaddingsBottom').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { padding: null });
      });
      // font family
      $('.sgg-caption-builder #thumbnailOverlayFontFamily').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { 'font-family': null });
      });
      $('#captionBuilderDescriptionFontFamily').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { 'font-family': null });
      });
      // font size
      $('.sgg-caption-builder #thumbnailOverlayTextSize, .sgg-caption-builder #thumbnailOverlayTextSizeUnit').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { 'font-size-num': null });
      });
      $('#captionBuilderDescriptionFontSize, #captionBuilderDescriptionFontSizeUnit').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { 'font-size-num': null });
      });
      // color
      $('.sgg-caption-builder #thumbnailOverlayFontColor').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { color: null });
      });
      $('#captionBuilderDescriptionFontColor').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { color: null });
      });
      // isBold
      $('.sgg-caption-builder #thumbnailOverlayFontStyleBold').on('ifChanged', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { isBold: null });
      });
      $('#captBuildDescriptionFontStyleBold').on('ifChanged', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { isBold: null });
      });
      // isItalic
      $('.sgg-caption-builder #thumbnailOverlayFontStyleItalic').on('ifChanged', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { isItalic: null });
      });
      $('#captBuildDescriptionFontStyleItalic').on('ifChanged', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { isItalic: null });
      });
      // isLine
      $('.sgg-caption-builder #thumbnailOverlayFontStyleLine').on('ifChanged', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { isLine: null });
      });
      $('#captBuildDescriptionFontStyleLine').on('ifChanged', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { isLine: null });
      });
      // text background color
      $('#captionBuilderTitleBgColor, #captionBuilderTitleBgColorTransparency').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-title', { 'text-hl-color': null });
      });
      $('#captBuildDescriptionBgColor, #captBuildDescriptionBgColorTransparency').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-description', { 'text-hl-color': null });
      });
      $('#captBuildIconsBgColor, #captBuildIconsBgColorTransparency').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'text-hl-color': null });
      });
      // 'icon-size-num'
      $('.sgg-caption-builder #iconsSize').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-size-num': null });
      });
      // 'icon-color'
      $('.sgg-caption-builder #iconsColor').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-color': null });
      });
      $('.sgg-caption-builder #iconsTransparency').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-color': null });
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-color-hover': null });
      });
      // 'icon-color-hover'
      $('.sgg-caption-builder #iconsHoverColor').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-color-hover': null });
      });
      // 'icon-bg-color'
      $('.sgg-caption-builder #iconsBackgroundColor').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-bg-color': null });
      });
      $('.sgg-caption-builder #iconsBgTransparency').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-bg-color': null });
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-bg-color-hover': null });
      });
      // 'icon-bg-color-hover'
      $('.sgg-caption-builder #iconsBackgroundHoverColor').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'icon-bg-color-hover': null });
      });
      // 'margin-all'
      $('.sgg-caption-builder #iconsMargin').on('change', function (event) {
        sIpp.setStyleByArrayCodes('caption-builder-icons', { 'margin-all': null });
      });
    };

    ImagePreviewPro.getPreviewRowObjectByCodePro = function (code) {
      var $rowObject = null;
      if (code == 'caption-builder-title' && this.isCbTitleEnabled()) {
        $rowObject = $('.gg-caption-row.sggCbr-title');
      } else if (code == 'caption-builder-description' && this.isCbDescriptionEnabled()) {
        $rowObject = $('.gg-caption-row.sggCbr-description');
      } else if (code == 'caption-builder-icons' && this.isCbIconsEnabled()) {
        $rowObject = $('.gg-caption-row.sggCbr-icons');
      }
      return $rowObject;
    };

    ImagePreviewPro.setStyleByArrayCodesPro = function (allParams) {
      var stylesKeys = null,
        objectCode = null,
        styleCodesObject = null;
      if (allParams[0]) {
        stylesKeys = allParams[0];
      }
      if (allParams[1]) {
        objectCode = allParams[1];
      }
      if (allParams[2]) {
        styleCodesObject = allParams[2];
      }

      var $rowObject = this.getPreviewRowObjectByCode(objectCode),
        $cellObject = null,
        $entryCellWrapper = null,
        $popupIcon = null,
        isPaddingSetted = false,
        isFontSizeSetted = false,
        isHlSetted = false,
        isIconColorSetted = false,
        isIconBgColorSetted = false;
      if ($rowObject && stylesKeys.length) {
        $cellObject = $rowObject.find('.gg-caption-cell');
        $entryCellWrapper = $cellObject.find('.sggCbEntrySpan');
        if (objectCode == 'caption-builder-icons') {
          $popupIcon = $entryCellWrapper.find('.hi-icon');
          if (!$popupIcon || !$popupIcon.length) {
            return;
          }
        }

        var keyIndex = 0,
          keyIndexCode = null,
          keyIndexValue = null,
          tmpVal = null;
        for (keyIndex = 0; keyIndex < stylesKeys.length; keyIndex++) {
          keyIndexCode = stylesKeys[keyIndex];
          keyIndexValue = null;
          if (styleCodesObject && styleCodesObject[keyIndexCode]) {
            keyIndexValue = styleCodesObject[keyIndexCode];
          }
          switch (keyIndexCode) {
            case 'height':
              if (!keyIndexValue) {
                var $heightObj = this.getCaptionObjectByKey([objectCode, 'height']),
                  heightVal = '33%';
                if ($heightObj && $heightObj.length) {
                  tmpVal = $heightObj.val();
                  if (tmpVal) {
                    heightVal = tmpVal;
                  }
                }
                $rowObject.css('height', heightVal);
              } else {
                $rowObject.css('height', keyIndexValue);
              }
              break;
            case 'position':
              var $positionObjects = this.getCaptionObjectByKey([objectCode, 'position']),
                $posObjectSelected = null;
              if (!keyIndexValue) {
                if ($positionObjects) {
                  $posObjectSelected = $positionObjects.filter(':checked');
                  if ($posObjectSelected.length) {
                    var posObjValue = $posObjectSelected.val(),
                      posObjArrVal = null;
                    if (posObjValue && posObjValue.length) {
                      posObjArrVal = posObjValue.split('_');
                      if (posObjArrVal.length == 2) {
                        $rowObject.css('text-align', posObjArrVal[0]);
                        $rowObject.removeClass('top center bottom disabled');
                        $rowObject.addClass(posObjArrVal[1]);
                      }
                    }
                  }
                }
              } else {
                $rowObject.css('text-align', keyIndexValue);
              }
              break;
            case 'text-hl-color':
            case 'text-hl-color-transparency':
              if (!isHlSetted) {
                if (!keyIndexValue) {
                  var bgColorObj = this.getCaptionObjectByKey([objectCode, 'text-hl-color']),
                    bgColorAlphaObj = this.getCaptionObjectByKey([objectCode, 'text-hl-color-transparency']),
                    bgColorVal = { r: 0, g: 0, b: 0 },
                    bgColorAlphaVal = 1;
                  // prepare Color
                  if (bgColorObj && bgColorObj.length) {
                    // Gallery
                    // app
                    tmpVal = app.hexToRgb(bgColorObj.val());
                    if (tmpVal) {
                      bgColorVal = tmpVal;
                    }
                  }
                  // prepare alpha
                  if (bgColorAlphaObj && bgColorAlphaObj.length) {
                    // get value
                    tmpVal = parseInt(bgColorAlphaObj.val());
                    if (!isNaN(tmpVal)) {
                      // divide 10
                      tmpVal = tmpVal / 10;
                      // prepare as @transparency
                      bgColorAlphaVal = 1 - tmpVal;
                    }
                  }
                  bgColorVal.a = bgColorAlphaVal;
                  $rowObject.css('background-color', 'rgba(' + bgColorVal.r + ',' + bgColorVal.g + ',' + bgColorVal.b + ',' + bgColorVal.a + ')');
                } else {
                  $rowObject.css('background-color', keyIndexValue);
                }
                isHlSetted = true;
              }
              break;
            case 'padding':
            case 'padding-left':
            case 'padding-right':
            case 'padding-top':
            case 'padding-bottom':
              if (!isPaddingSetted) {
                if (!keyIndexValue) {
                  var $paddLObject = this.getCaptionObjectByKey([objectCode, 'padding-left']),
                    $paddRObject = this.getCaptionObjectByKey([objectCode, 'padding-right']),
                    $paddTObject = this.getCaptionObjectByKey([objectCode, 'padding-top']),
                    $paddBObject = this.getCaptionObjectByKey([objectCode, 'padding-bottom']),
                    paddingLeftVal = 0,
                    paddingRightVal = 0,
                    paddingTopVal = 0,
                    paddingBottomVal = 0;
                  if ($paddLObject && $paddLObject.length && $paddLObject.val()) {
                    tmpVal = parseInt($paddLObject.val());
                    if (!isNaN(tmpVal)) {
                      paddingLeftVal = tmpVal;
                    }
                  }
                  if ($paddRObject && $paddRObject.length && $paddRObject.val()) {
                    tmpVal = parseInt($paddRObject.val());
                    if (!isNaN(tmpVal)) {
                      paddingRightVal = tmpVal;
                    }
                  }
                  if ($paddTObject && $paddTObject.length && $paddTObject.val()) {
                    tmpVal = parseInt($paddTObject.val());
                    if (!isNaN(tmpVal)) {
                      paddingTopVal = tmpVal;
                    }
                  }
                  if ($paddBObject && $paddBObject.length && $paddBObject.val()) {
                    tmpVal = parseInt($paddBObject.val());
                    if (!isNaN(tmpVal)) {
                      paddingBottomVal = tmpVal;
                    }
                  }
                  $cellObject.css('padding', paddingTopVal + 'px ' + paddingRightVal + 'px ' + paddingBottomVal + 'px ' + paddingLeftVal + 'px');
                } else {
                  $cellObject.css('padding', keyIndexValue);
                }
                isPaddingSetted = true;
              }
              break;
            case 'font-family':
              if (!keyIndexValue) {
                var $fontObj = this.getCaptionObjectByKey([objectCode, 'font-family']),
                  fontValue = null;
                if ($fontObj && $fontObj.length) {
                  fontValue = $fontObj.val();
                  if (fontValue == 'Default') {
                    $entryCellWrapper.css('font-family', 'inherit');
                  } else if (fontValue && fontValue.length) {
                    $entryCellWrapper.css('font-family', fontValue);
                    app.proSettings.loadFontFamily(fontValue);
                  }
                }
              } else {
                $entryCellWrapper.css('font-family', keyIndexValue);
              }

              break;
            case 'font-size-num':
            case 'font-size-unit':
              if (!isFontSizeSetted) {
                if (!keyIndexValue) {
                  var $fontSizeObj = this.getCaptionObjectByKey([objectCode, 'font-size-num']),
                    $fontSizeUnitObj = this.getCaptionObjectByKey([objectCode, 'font-size-unit']),
                    fontSizeVal = '10',
                    fontSizeUnitVal = 'px';

                  if ($fontSizeObj && $fontSizeObj.length) {
                    tmpVal = parseInt($fontSizeObj.val());
                    if (!isNaN(tmpVal)) {
                      fontSizeVal = tmpVal;
                    }
                  }
                  if ($fontSizeUnitObj && $fontSizeUnitObj.length) {
                    tmpVal = parseInt($fontSizeUnitObj.val());
                    if (!isNaN(tmpVal) && [0, 1, 2].indexOf(tmpVal) != -1) {
                      if (tmpVal == 1) {
                        fontSizeUnitVal = '%';
                      } else if (tmpVal == 2) {
                        fontSizeUnitVal = 'em';
                      } else {
                        fontSizeUnitVal = 'px';
                      }
                    }
                  }
                  $entryCellWrapper.css('font-size', fontSizeVal + fontSizeUnitVal);
                } else {
                  $entryCellWrapper.css('font-size', keyIndexValue);
                }
                isFontSizeSetted = true;
              }
              break;
            case 'color':
              if (!keyIndexValue) {
                var $fontColorObj = this.getCaptionObjectByKey([objectCode, 'color']),
                  fontColorVal = '#000';
                if ($fontColorObj && $fontColorObj.length) {
                  tmpVal = $fontColorObj.val();
                  if (tmpVal) {
                    fontColorVal = tmpVal;
                  }
                }
                $entryCellWrapper.css('color', fontColorVal);
              } else {
                $entryCellWrapper.css('color', keyIndexValue);
              }
              break;
            case 'isBold':
              if (!keyIndexValue) {
                var $boldObj = this.getCaptionObjectByKey([objectCode, 'isBold']);
                if ($boldObj && $boldObj.length && $boldObj.prop('checked')) {
                  $entryCellWrapper.css('font-weight', 'bold');
                } else {
                  $entryCellWrapper.css('font-weight', 'normal');
                }
              } else {
                if (['normal', 'bold', 'medium', 'black', 'semi bold', 'extra bold', '400', '500', '600', '700', '800', '900']) {
                  $entryCellWrapper.css('font-weight', keyIndexValue);
                } else {
                  $entryCellWrapper.css('font-weight', 'bold');
                }
              }
              break;
            case 'isItalic':
              if (!keyIndexValue) {
                var $italicObj = this.getCaptionObjectByKey([objectCode, 'isItalic']);
                if ($italicObj && $italicObj.length && $italicObj.prop('checked')) {
                  $entryCellWrapper.css('font-style', 'italic');
                } else {
                  $entryCellWrapper.css('font-style', 'normal');
                }
              } else {
                if (['italic', 'normal'].indexOf(keyIndexValue) != -1) {
                  $entryCellWrapper.css('font-style', keyIndexValue);
                } else {
                  $entryCellWrapper.css('font-style', 'normal');
                }
              }
              break;
            case 'isLine':
              if (!keyIndexValue) {
                var $lineObj = this.getCaptionObjectByKey([objectCode, 'isLine']);
                if ($lineObj && $lineObj.length && $lineObj.prop('checked')) {
                  $entryCellWrapper.css('text-decoration', 'underline');
                } else {
                  $entryCellWrapper.css('text-decoration', 'none');
                }
              } else {
                if (['underline', 'none'].indexOf(keyIndexValue) != -1) {
                  $entryCellWrapper.css('text-decoration', keyIndexValue);
                } else {
                  $entryCellWrapper.css('text-decoration', 'none');
                }
              }
              break;
            // icons only
            case 'margin-all':
              if (!keyIndexValue) {
                var $marginObject = this.getCaptionObjectByKey([objectCode, 'margin-all']),
                  marginVal = null;
                if ($marginObject && $marginObject.length) {
                  marginVal = parseInt($marginObject.val());
                  if (!isNaN(marginVal)) {
                    $popupIcon.css({
                      'margin-right': marginVal + 'px',
                      'margin-left': marginVal + 'px',
                    });
                  }
                }
              } else {
                $popupIcon.css({
                  'margin-right': keyIndexValue,
                  'margin-left': keyIndexValue,
                });
              }
              break;
            case 'icon-color':
            case 'icon-transparency':
              if (!isIconColorSetted) {
                var $colorStyle = $('#sggSettingsIconColorStyle');
                if (!keyIndexValue) {
                  var $colorObject = this.getCaptionObjectByKey([objectCode, 'icon-color']),
                    colorVal = { r: '255', g: '255', b: '255' },
                    $iconTransparency = this.getCaptionObjectByKey([objectCode, 'icon-transparency']),
                    opacityVal = 1;

                  if ($colorObject && $colorObject.length) {
                    tmpVal = app.hexToRgb($colorObject.val());
                    if (tmpVal) {
                      colorVal = tmpVal;
                    }
                  }
                  if ($iconTransparency && $iconTransparency.length) {
                    tmpVal = parseInt($iconTransparency.val());
                    if (!isNaN(tmpVal)) {
                      tmpVal = tmpVal / 10;
                      opacityVal = 1 - tmpVal;
                    }
                  }
                  colorVal.a = opacityVal;
                  tmpVal = 'rgba(' + colorVal.r + ', ' + colorVal.g + ', ' + colorVal.b + ', ' + colorVal.a + ')';
                  $colorStyle.html('.hi-icon { color: ' + tmpVal + ' !important;}');
                } else {
                  $colorStyle.html('.hi-icon { color: ' + keyIndexValue + ' !important;}');
                }
                isIconColorSetted = true;
              }
              break;
            case 'icon-bg-color':
            case 'icon-bg-color-transparency':
              if (!isIconBgColorSetted) {
                var $bgColorStyle = $('#sggSettingsIconBgColorStyle');
                if (!keyIndexValue) {
                  var $bgColorObject = this.getCaptionObjectByKey([objectCode, 'icon-bg-color']),
                    bgColorVal = { r: '0', g: '0', b: '0' },
                    $bgTransp = this.getCaptionObjectByKey([objectCode, 'icon-bg-color-transparency']),
                    bgOpacityVal = 1;

                  if ($bgColorObject && $bgColorObject.length) {
                    tmpVal = app.hexToRgb($bgColorObject.val());
                    if (tmpVal) {
                      bgColorVal = tmpVal;
                    }
                  }
                  if ($bgTransp && $bgTransp.length) {
                    tmpVal = parseInt($bgTransp.val());
                    if (!isNaN(tmpVal)) {
                      tmpVal = tmpVal / 10;
                      bgOpacityVal = 1 - tmpVal;
                    }
                  }
                  bgColorVal.a = bgOpacityVal;
                  tmpVal = tmpVal = 'rgba(' + bgColorVal.r + ', ' + bgColorVal.g + ', ' + bgColorVal.b + ', ' + bgColorVal.a + ')';
                  $bgColorStyle.html('.hi-icon { background: ' + tmpVal + ' !important; }');
                } else {
                  $bgColorStyle.html('.hi-icon { background: ' + keyIndexValue + ' !important; }');
                }
                isIconBgColorSetted = true;
              }
              break;
            case 'icon-popup':
              if (!keyIndexValue) {
                // only setting
                var popupFaIconClass = $('#popupIconFaClass').val();
                $popupIcon.filter(':not(.fa-download)').addClass(popupFaIconClass);
              }
              break;
            case 'icon-color-hover':
              var $iconHoverColorStyle = $('#sggSettingHiIconHoverColorStyle');
              if (!keyIndexValue) {
                var $colorOnHoverObject = this.getCaptionObjectByKey([objectCode, 'icon-color-hover']),
                  colorOnHoverVal = { r: '0', g: '0', b: '0' },
                  $bgTransp = this.getCaptionObjectByKey([objectCode, 'icon-transparency']),
                  bgOpacityVal = 1;

                if ($colorOnHoverObject && $colorOnHoverObject.length) {
                  tmpVal = app.hexToRgb($colorOnHoverObject.val());
                  if (tmpVal) {
                    colorOnHoverVal = tmpVal;
                  }
                }
                if ($bgTransp && $bgTransp.length) {
                  tmpVal = parseInt($bgTransp.val());
                  if (!isNaN(tmpVal)) {
                    tmpVal = tmpVal / 10;
                    bgOpacityVal = 1 - tmpVal;
                  }
                }
                colorOnHoverVal.a = bgOpacityVal;
                tmpVal = tmpVal = 'rgba(' + colorOnHoverVal.r + ', ' + colorOnHoverVal.g + ', ' + colorOnHoverVal.b + ', ' + colorOnHoverVal.a + ')';
                $iconHoverColorStyle.html('.hi-icon:hover { color: ' + tmpVal + ' !important; }');
              } else {
                $iconHoverColorStyle.html('.hi-icon:hover { color: ' + keyIndexValue + ' !important; }');
              }
              break;
            case 'icon-bg-color-hover':
              var $iconOnHoverBgColorStyle = $('#sggSettingHiIconHoverBgStyle');
              if (!keyIndexValue) {
                var $iconOnHoverBgColor = this.getCaptionObjectByKey([objectCode, 'icon-bg-color-hover']),
                  iconOnHoverBgColorVal = { r: '255', g: '255', b: '255' },
                  $bgTransp = this.getCaptionObjectByKey([objectCode, 'icon-bg-color-transparency']),
                  bgOpacityVal = 1;

                if ($iconOnHoverBgColor && $iconOnHoverBgColor.length) {
                  tmpVal = app.hexToRgb($iconOnHoverBgColor.val());
                  if (tmpVal) {
                    iconOnHoverBgColorVal = tmpVal;
                  }
                }
                if ($bgTransp && $bgTransp.length) {
                  tmpVal = parseInt($bgTransp.val());
                  if (!isNaN(tmpVal)) {
                    tmpVal = tmpVal / 10;
                    bgOpacityVal = 1 - tmpVal;
                  }
                }
                iconOnHoverBgColorVal.a = bgOpacityVal;
                tmpVal = tmpVal = 'rgba(' + iconOnHoverBgColorVal.r + ', ' + iconOnHoverBgColorVal.g + ', ' + iconOnHoverBgColorVal.b + ', ' + iconOnHoverBgColorVal.a + ')';
                $iconOnHoverBgColorStyle.html('.hi-icon:hover { background: ' + tmpVal + ' !important; }');
              } else {
                $iconOnHoverBgColorStyle.html('.hi-icon:hover { background: ' + keyIndexValue + ' !important; }');
              }
              break;
            case 'icon-size-num':
              var $iconSettWidthStyle = $('#sggSettingsIconSizeStyle'),
                $iconSettSizeStyle = $('#sggSettingHiIconBeforeFontSizeStyle');
              if (!keyIndexValue) {
                var $iconSize = this.getCaptionObjectByKey([objectCode, 'icon-size-num']),
                  iconSizeVal = 16;
                if ($iconSize && $iconSize.length) {
                  tmpVal = parseInt($iconSize.val());
                  if (!isNaN(tmpVal)) {
                    iconSizeVal = tmpVal;
                  }
                }
                $iconSettWidthStyle.html('.hi-icon {' + 'width: ' + 2 * iconSizeVal + 'px !important;' + 'height: ' + 2 * iconSizeVal + 'px !important;' + '}');
                $iconSettSizeStyle.html('.hi-icon:before {' + 'font-size: ' + iconSizeVal + 'px !important;' + 'line-height: ' + 2 * iconSizeVal + 'px !important;' + '}');
              } else {
                keyIndexValue = parseInt(keyIndexValue);
                if (!isNaN(keyIndexValue)) {
                  $iconSettWidthStyle.html('.hi-icon {' + 'width: ' + 2 * keyIndexValue + 'px !important;' + 'height: ' + 2 * keyIndexValue + 'px !important;' + '}');
                  $iconSettSizeStyle.html('.hi-icon:before {' + 'font-size: ' + keyIndexValue + 'px !important;' + 'line-height: ' + 2 * keyIndexValue + 'px !important;' + '}');
                }
              }
              break;
            case 'icon-animation':
              if (!keyIndexValue) {
                var $iconAnim = this.getCaptionObjectByKey([objectCode, 'icon-animation']);
                if ($iconAnim && $iconAnim.length) {
                  tmpVal = $iconAnim.val();
                  if (tmpVal) {
                    // find prev classes
                    var oldClassArr = this.findClassByStr($entryCellWrapper, 'hi-icon-effect');
                    for (var indTmpInd = 0; indTmpInd < oldClassArr.length; indTmpInd++) {
                      $entryCellWrapper.removeClass(oldClassArr[indTmpInd]);
                    }

                    // set new classes
                    $entryCellWrapper.addClass(tmpVal);
                    tmpVal = tmpVal.substr(0, tmpVal.length - 1);
                    $entryCellWrapper.addClass(tmpVal);
                  }
                }
              }
              break;
          }
        }
      }
    };

    ImagePreviewPro.checkCurrentSettPage = function (params) {
      var qs = new URI().query(true);
      if (qs.module === 'galleries') {
        if (qs.action === 'settings') {
          this.initCurrSettingsPro();
        }
      }
    };

    ImagePreviewPro.captionBuilderChangeValueHandler = function () {
      var cbSelectValue = $('.ggUserCaptionBuilderCl:checked').val();

      // change preview wrapper attribute value
      this.$previewCaption.attr('data-caption-buider', cbSelectValue);
      // configure "image on hover" visibility
      this.initImageOnHoverEffectHandler();
    };

    ImagePreviewPro.cbTdiPositionChangeHandler = function () {
      var previewObj = this;
      function getHorizontalAndVerticalPosition($elem) {
        if ($elem && $elem.length) {
          var cbCheckValue = $elem.val(),
            horizontalValuesArr = ['left', 'center', 'right'],
            verticalValuesArr = ['top', 'center', 'bottom'],
            tempValArr = null,
            verticalPos = null,
            horizontalPos = null;
          if (cbCheckValue && cbCheckValue.length) {
            var tempValArr = cbCheckValue.split('_');
            if (tempValArr.length == 2) {
              horizontalPos = tempValArr[0];
              verticalPos = tempValArr[1];
              if (horizontalValuesArr.indexOf(horizontalPos) != -1 && verticalValuesArr.indexOf(verticalPos) != -1) {
                return { h: horizontalPos, v: verticalPos };
              }
            }
          }
        }
        return null;
      }
      if (this.isCaptionBuilderEnabled()) {
        var tempPosArr = null,
          verticalPos = {
            top: null,
            center: null,
            bottom: null,
          },
          $tableRows = this.$previewFigCaption.find('.gg-caption-row'),
          prevIconCellEntry = '',
          previewRowCellObj = {
            title: $tableRows.filter('.sggCbr-title'),
            description: $tableRows.filter('.sggCbr-description'),
            icons: $tableRows.filter('.sggCbr-icons'),
          };

        if ($('#captBuildIconRowEntry').val()) {
          prevIconCellEntry = JSON.parse($('#captBuildIconRowEntry').val());
        }

        if (this.isCbTitleEnabled()) {
          previewRowCellObj['title'].removeClass('disabled');
          tempPosArr = getHorizontalAndVerticalPosition($('.captionBuilderPosRadio[name="captionBuilder[title][position]"]:checked'));
          if (tempPosArr) {
            verticalPos[tempPosArr['v']] = 'title';
          }
        } else {
          previewRowCellObj['title'].addClass('disabled');
        }
        if (this.isCbDescriptionEnabled()) {
          previewRowCellObj['description'].removeClass('disabled');
          tempPosArr = getHorizontalAndVerticalPosition($('.captionBuilderPosRadio[name="captionBuilder[description][position]"]:checked'));
          if (tempPosArr) {
            verticalPos[tempPosArr['v']] = 'description';
          }
        } else {
          previewRowCellObj['description'].addClass('disabled');
        }
        if (this.isCbIconsEnabled()) {
          previewRowCellObj['icons'].removeClass('disabled');
          tempPosArr = getHorizontalAndVerticalPosition($('.captionBuilderPosRadio[name="captionBuilder[icons][position]"]:checked'));
          if (tempPosArr) {
            verticalPos[tempPosArr['v']] = 'icons';
          }
        } else {
          previewRowCellObj['icons'].addClass('disabled');
        }
        // remove previous classes
        previewRowCellObj['title'].removeClass('sggCbr-title');
        previewRowCellObj['description'].removeClass('sggCbr-description');
        previewRowCellObj['icons'].removeClass('sggCbr-icons');

        var posArrKeys = Object.keys(verticalPos),
          posCode = null,
          itemPos = null,
          self2 = this,
          notExistsValuesArr = [];

        // fill not exists keys
        if (!verticalPos['bottom'] || !verticalPos['center'] || !verticalPos['top']) {
          var tmpInd = 0,
            posArr = ['bottom', 'center', 'top'],
            notExistsValuesArr = ['title', 'description', 'icons'];
          // prepare not exists values
          for (tmpInd = 0; tmpInd < posArr.length; tmpInd++) {
            var tmpCode = posArr[tmpInd],
              tmpValue = verticalPos[tmpCode];

            if (!tmpValue) {
              delete posArr[tmpCode];
            } else {
              var findIaPos = notExistsValuesArr.indexOf(tmpValue);
              if (findIaPos != -1) {
                // remove item from array
                notExistsValuesArr.splice(findIaPos, 1);
              }
            }
          }
        }

        // set null to rows entry
        $tableRows.html('');
        // add info to rows entry
        $tableRows.each(function (ind1, item1) {
          var $rowItem = $(item1);
          posCode = posArrKeys[ind1];
          itemPos = verticalPos[posCode];

          if (!itemPos) {
            $rowItem.attr('style', '');
            // prepare classes
            if (notExistsValuesArr.length) {
              $rowItem.addClass('sggCbr-' + notExistsValuesArr[0]);
              notExistsValuesArr = notExistsValuesArr.slice(1);
            }
          } else {
            $rowItem.html('<div class="gg-caption-cell"></div>');
            $rowItem.addClass('sggCbr-' + itemPos);

            var $rowCell = $rowItem.find('.gg-caption-cell');
            if (itemPos == 'title') {
              $rowCell.html('<span class="sggCbEntrySpan" style="">' + $('#ggPreviewTitleRowHtmlInp').val() + '</span>');
              self2.setStyleByArrayCodes('caption-builder-title', null);
            } else if (itemPos == 'description') {
              $rowCell.html('<span class="sggCbEntrySpan" style="">' + $('#ggPreviewDescrRowHtmlInp').val() + '</span>');
              self2.setStyleByArrayCodes('caption-builder-description', null);
            } else if (itemPos == 'icons') {
              $rowCell.html('<div class="sggCbEntrySpan hi-icon-wrap">' + prevIconCellEntry + '</div>');
              self2.setStyleByArrayCodes('caption-builder-icons', null);
            }
          }
        });
        previewObj.initCaptionEffects();
      }
    };

    ///
    ImagePreviewPro.initImageOnHoverEffectHandler = function (selectedGrigGalleryTypeEffect) {
      // get effect from preview
      if (!selectedGrigGalleryTypeEffect) {
        selectedGrigGalleryTypeEffect = this.$previewCaption.attr('data-grid-gallery-type');
      }
      var selfPro = this,
        $rowShowOnHoverBg = null,
        $captionsSubTab = $('.caption-type[data-gg-cb-type="captions-icons"]'),
        $captionBuilderTab = $('.caption-type[data-gg-cb-type="caption-builder"]');

      // Get Show on Hover Row
      if ($('.ggUserCaptionBuilderCl:checked').val() == '0') {
        $rowShowOnHoverBg = $captionsSubTab.find('#showOnHoverBgColorRow');
      } else {
        if ($('#captionBuilderBackgroundEnable:checked').length) {
          $rowShowOnHoverBg = $captionBuilderTab.find('#showOnHoverBgColorRow');
        }
      }

      if (selectedGrigGalleryTypeEffect == 'show-on-hover') {
        if ($rowShowOnHoverBg && $rowShowOnHoverBg.length) {
          $rowShowOnHoverBg.show();
        }
        var $previewFigure = $('#preview .grid-gallery-caption'),
          $bgForShowOnHover = null,
          $thumbnailBackground = null,
          $thumbnailTransparency = null,
          $figcaption = $previewFigure.find('figcaption');

        if ($('.ggUserCaptionBuilderCl:checked').val() == '0') {
          $bgForShowOnHover = $captionsSubTab.find('input[name="thumbnail[overlay][backgroundForShowOnHover]"]');
          $thumbnailBackground = $captionsSubTab.find('input[name="thumbnail[overlay][background]"]');
          $thumbnailTransparency = $captionsSubTab.find('select[name="thumbnail[overlay][transparency]"]');
        } else {
          if ($('#captionBuilderBackgroundEnable:checked').length) {
            $bgForShowOnHover = $captionBuilderTab.find('input[name="thumbnail[overlay][backgroundForShowOnHover]"]');
            $thumbnailBackground = $captionBuilderTab.find('input[name="thumbnail[overlay][background]"]');
            $thumbnailTransparency = $captionBuilderTab.find('select[name="thumbnail[overlay][transparency]"]');
          }
        }
        // add style tag
        if ($bgForShowOnHover && $thumbnailBackground && $thumbnailTransparency && $figcaption.length && $bgForShowOnHover.length && $thumbnailBackground.length && $thumbnailTransparency.length) {
          var calcOpacityWithHex = app.hexToRgb($thumbnailBackground.val()),
            transparencyColor = (10 - $thumbnailTransparency.val()) / 10;
          if (calcOpacityWithHex && 'r' in calcOpacityWithHex && 'g' in calcOpacityWithHex && 'b' in calcOpacityWithHex) {
            var styleStr =
              '.grid-gallery-caption[data-grid-gallery-type="show-on-hover"] figcaption { background-color: ' +
              $bgForShowOnHover.val() +
              ' !important; }' +
              ' .grid-gallery-caption[data-grid-gallery-type="show-on-hover"]:hover figcaption, .grid-gallery-caption[data-grid-gallery-type="show-on-hover"].hovered figcaption, .grid-gallery-caption[data-grid-gallery-type="show-on-hover"]:active figcaption { background-color: ' +
              'rgba(' +
              calcOpacityWithHex.r +
              ',' +
              calcOpacityWithHex.g +
              ',' +
              calcOpacityWithHex.b +
              ',' +
              transparencyColor +
              ')' +
              '!important; }';

            $('#showOnHoverStyle').html(styleStr);
          }
        }
      } else {
        if ($rowShowOnHoverBg && $rowShowOnHoverBg.length) {
          $rowShowOnHoverBg.hide();
        }
      }
    };

    ImagePreviewPro.cbBackgroundEnableChangeHandler = function () {
      this.cbTdiEnableChangeHandler();

      // add effect
      // add bg Color + transparency
      // polaroid effect ???
      // image on hover effect ???

      //if(this.isCbBackgroundEnabled()) {
      //
      //} else {
      //
      //}
    };

    // function for future - change caption color in preview
    // ImagePreviewPro.changeBackgroundColorPreview = (function() {
    //
    //    //change color preview
    //    jQuery('#overlayBackgroundRow .wp-color-result').attr('id', 'overlayBackgroundRowColorInp');
    //    var observer = new MutationObserver(styleChangedCallback);
    //    observer.observe(document.getElementById('overlayBackgroundRowColorInp'), {
    //        attributes: true,
    //        attributeFilter: ['style'],
    //    });
    //    var oldIndex = document.getElementById('overlayBackgroundRowColorInp').style.backgroundColor;
    //    function styleChangedCallback(mutations) {
    //        var newIndex = mutations[0].target.style.backgroundColor;
    //        if (newIndex !== oldIndex) {
    //            console.log(newIndex);
    //            jQuery('.grid-gallery-caption figcaption').css('background-color', newIndex);
    //        }
    //    }
    //
    // });

    ImagePreviewPro.cbTdiEnableChangeHandler = function () {
      var $previewCaption = $('#preview figcaption');
      // turn on Background "wrapper"
      if (this.isCbBackgroundEnabled() || this.isCbTitleEnabled() || this.isCbDescriptionEnabled() || this.isCbIconsEnabled()) {
        $previewCaption.show();
      } else {
        $previewCaption.hide();
      }
    };

    imagePreviewFree.checkCurrentSettPage();
  }

  $(document).on('sggInitGallerySettingsPro', gallerySettingsProHandler);
  $(document).on('sggInitGalleryImagePreviewPro', galleryImagePreviewProHandler);
})(jQuery, (window.SupsysticGallery = window.SupsysticGallery || {}));
