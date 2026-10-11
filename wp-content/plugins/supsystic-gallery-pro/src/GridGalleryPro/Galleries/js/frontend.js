(function ($) {
  function ProGallery() {
    this.topLvlItemSelector = null;
    this.scrollInProgress = false;
  }

  ProGallery.prototype._parent = function (func, arguments) {
    return typeof this.constructor.prototype[func] != 'undefined' ? this.constructor.prototype[func].call(this, arguments) : false;
  };

  ProGallery.prototype._afterInit = function ($callback) {
    $(document).on('GalleryAfterInit', function (event, Gallery) {
      $callback.call(Gallery);
    });
  };

  ProGallery.prototype._beforeInit = function ($callback) {
    var galleryBeforeInitId = 'GalleryBeforeInit' + this.galleryContainerId;
    $(document).on(galleryBeforeInitId, (event, Gallery) => {
      $callback.call(Gallery);
    });
  };

  ProGallery.prototype.init = function () {
    var self = this;
    this._parent('init');
    this._beforeInit(() => {
      this.initInfiniteScroll();
      this.initColorBoxOnLoadHandler();
    });

    this._afterInit(function () {
      this.initVideoPopup();
      this.initLinkedImages();
      this.initDisableRightClick();
      self.selectCategoryByUrlHash(self);
      if (!this.afterInitFlag) {
        this.initMosaicGalleryType();
        this.setCaptionOnHoverImage();
        this.afterInitFlag = true;
        this.initPopupFunc();
      }
      this.initFilterByAttributes();
      this.initSearchByKeywords();
      this.initDownloadButtons();
    });

    $(window).on('hashchange', function (event) {
      self.selectCategoryByUrlHash(self);
    });

    $(document).on('beforeResizeColorBoxResponsive', function (event) {
      if (typeof self.currentVideoPlayer == 'object') {
        if (typeof self.currentVideoPlayer.getCurrentTime == 'function') {
          self.savedColorBoxVideoStartTime = self.currentVideoPlayer.getCurrentTime();
        }
      }
    });

    if (this.$container.data('caption-builder-icons') == '1' || (this.$container.data('caption-buider') != 1 && this.$container.data('icons') == true)) {
      this.topLvlItemSelector = '.grid-gallery-caption ';
    } else {
      this.topLvlItemSelector = '.gg-link';
    }
  };

  ProGallery.prototype.reinit = function () {
    this.setIconsPosition();
    this.setOverlayTransparency();
    this.setImageOverlay();
    this.setMouseShadow();
    this.initRowsMode();
    this.preventImages();
    this.initPopup();
    this.initVideoPopup();
    this.initCaptionCalculations();
    this.initCaptionEffects();
    this.showCaption();
    this.hidePopupCaptions();
    this.correctMargin();
    this.initHorizontalMode();
    this.initWookmark();
    this.$container.trigger('wookmark.changed');
    if (this.socialSharing && this.socialSharing.enabled) {
      this.initImageSocialSharing();
    }
    this.initMosaicGalleryType();
  };

  ProGallery.prototype.loadButtonsFontFamily = function (font, callback) {
    callback = callback || function () {};

    if (font && font !== 'Default') {
      this._parent('importFontFamily', font);
    }
  };

  ProGallery.prototype.shadeColor = function (color, percent) {
    var f = parseInt(color.slice(1), 16),
      t = percent < 0 ? 0 : 255,
      p = percent < 0 ? percent * -1 : percent,
      R = f >> 16,
      G = (f >> 8) & 0x00ff,
      B = f & 0x0000ff;
    return '#' + (0x1000000 + (Math.round((t - R) * p) + R) * 0x10000 + (Math.round((t - G) * p) + G) * 0x100 + (Math.round((t - B) * p) + B)).toString(16).slice(1);
  };

  ProGallery.prototype.initButtonStyles = function ($buttons, font, fontCallback, single) {
    var self = this,
      $paginationPrev = null,
      $paginationNext = null;
    this.loadButtonsFontFamily(font, fontCallback);

    if (self.$container.data('use-pagination-prev-next') == 1) {
      $paginationPrev = $('.grid-gallery-page.gg-prev-pagination-btn');
      $paginationNext = $('.grid-gallery-page.gg-next-pagination-btn');
    }

    var background = (function ($button) {
      var initial = $button.css('background-color'),
        hasBackground = true;

      if (initial == 'rgba(0, 0, 0, 0)' || initial == 'transparent' || initial == 'rgb(255, 255, 255)') {
        hasBackground = false;
        initial = $button.css('color');
      }
      if (typeof initial === 'undefined') {
        initial = '#6f6f6f';
      }
      if (!initial.match(/^#?(?:[0-9a-f]{3}){1,2}$/)) {
        initial = '#' + self.rgb2hex(initial);
      }
      var shaded = self.shadeColor(initial, 0.5);

      return {
        initial: initial,
        shaded: shaded,
        hasBackground: hasBackground,
      };
    })($buttons.first());

    (function (background) {
      var attribute = 'background-color';
      if (!background.hasBackground) {
        attribute = 'color';
      }

      if (typeof single === 'undefined') {
        $buttons.not('.active').hover(
          function () {
            $(this).css(attribute, background.initial);
          },
          function () {
            $(this).not('.active').css(attribute, background.shaded);
          }
        );

        $buttons.on('click', function (event) {
          event.preventDefault();
          var multiselect = $buttons.closest('nav').data('multiselect') == '1',
            isActiveButton = false;

          if (multiselect) {
            var curentTag = String($(event.currentTarget).data('tag')),
              $active = $buttons.filter('.active[data-tag="' + curentTag + '"]');
            if ($active && $active.length) {
              $active.removeClass('active');
              isActiveButton = true;
            }
            if (curentTag == '__all__') {
              $buttons.removeClass('active');
            } else {
              $buttons.filter('.active[data-tag="__all__"]').removeClass('active');
            }
            $buttons.not('.active').css(attribute, background.shaded);
          } else {
            $buttons.css(attribute, background.shaded).removeClass('active');
          }

          if (self.$container.data('use-pagination-prev-next') == 1 && $paginationPrev.length && $paginationNext.length) {
            var currBtn = $(event.currentTarget),
              currPage = parseInt(currBtn.attr('data-page'), 10),
              maxPrev = parseInt($paginationPrev.attr('data-pagemax'), 10),
              maxNext = parseInt($paginationNext.attr('data-pagemax'), 10);

            var $toSelectBtn = self.pagination.$wrapper.find('a.grid-gallery-page:not(.gg-prev-pagination-btn):not(.gg-next-pagination-btn)[data-page="' + currPage + '"]');
            if ($toSelectBtn.length) {
              $toSelectBtn.css(attribute, background.initial).addClass('active');

              // recalc data-page for Prev/Next btn
              if (!isNaN(currPage) && !isNaN(maxPrev) && !isNaN(maxNext)) {
                if (currPage > 1) {
                  if (maxPrev >= currPage) {
                    $paginationPrev.attr('data-page', currPage - 1);
                  } else {
                    $paginationPrev.attr('data-page', maxPrev);
                  }
                  if (maxNext - 1 > currPage) {
                    $paginationNext.attr('data-page', currPage + 1);
                  } else {
                    $paginationNext.attr('data-page', maxNext);
                  }
                } else {
                  $paginationPrev.attr('data-page', 1);
                  $paginationNext.attr('data-page', 2);
                }
              }
            }
            $('.grid-gallery-pagination-wrap').attr('data-page', currPage);
          } else {
            var $newActiveButton = $(event.currentTarget);
            if (isActiveButton) {
              $newActiveButton = null;
              if ($buttons.filter('.active').length == 0) {
                $newActiveButton = $buttons.eq(0);
              }
            }
            if ($newActiveButton) {
              $newActiveButton.css(attribute, background.initial).addClass('active');
            }
          }
        });
      } else {
        $buttons.css(attribute, background.shaded);
        $buttons.hover(
          function () {
            $(this).css(attribute, background.initial);
          },
          function () {
            $(this).css(attribute, background.shaded);
          }
        );
      }
    })(background);
  };

  ProGallery.prototype.initFilterByAttributes = function () {
    if (this.$container.attr('data-show-filter') != 1) return;
    var $filterContainer = this.$container.find('.grid-gallery-filter'),
      $namesContainer = $filterContainer.find('.gg-filter-attributes-names'),
      $valuesContainer = $filterContainer.find('.gg-filter-attributes-values'),
      $filterClear = $filterContainer.find('.gg-filter-attributes-clear'),
      gallery = this,
      attrValues;

    if ($filterContainer.data('custom-buttons')) {
      this.loadButtonsFontFamily($filterContainer.data('font'), null);
    }

    try {
      attrValues = JSON.parse($valuesContainer.attr('data-values'));
    } catch (err) {}
    if (!$.isArray(attrValues)) {
      attrValues = [];
    }

    $namesContainer.on('change', function (event) {
      var $selected = $(this).find('option:selected');
      ($valuesContainer.empty(), (display = 'none'));
      if ($selected.length > 0 && $selected.val() != '0') {
        var attrName = $selected.text();
        for (var i = 0; i < attrValues.length; i++) {
          if ('name' in attrValues[i] && attrValues[i]['name'] == attrName) {
            if ('values' in attrValues[i] && $.isArray(attrValues[i]['values'])) {
              $.each(attrValues[i]['values'], function (index, value) {
                $valuesContainer.append($('<option value="' + index + '">' + value + '</option>'));
              });
            }
            break;
          }
        }
        display = 'block';
      }
      $filterClear.css('display', display);
      $valuesContainer.css('display', display).trigger('change');
    });

    $valuesContainer.on('change', function (event) {
      gallery.doFilterAndSearch();
    });

    $filterClear.on('click', function () {
      $namesContainer.find('option[value="0"]').prop('selected', true);
      $namesContainer.trigger('change');
    });

    $filterContainer.css('display', 'block');
    $filterContainer.find('select').height($filterClear.height());
    $filterClear.css('display', 'none');
  };

  ProGallery.prototype.doFilterAndSearch = function () {
    var $container = this.$container,
      $filterContainer = $container.find('.grid-gallery-filter'),
      $searchContainer = this.$container.find('.grid-gallery-search'),
      $elements = $container.find('.gg-link').removeClass('hidden-item');

    if ($filterContainer.length) {
      var $selectedName = $filterContainer.find('.gg-filter-attributes-names').find('option:selected');

      if ($selectedName.length > 0 && $selectedName.val() != '') {
        var attrName = $selectedName.text(),
          attrValue = $filterContainer.find('.gg-filter-attributes-values').find('option:selected').text();

        if (attrValue.length > 0) {
          $elements.each(function () {
            var found = false,
              attributes;
            try {
              attributes = JSON.parse($($(this)).attr('data-attributes'));
            } catch (err) {}
            if (!$.isArray(attributes)) {
              attributes = [];
            }
            for (var i = 0; i < attributes.length; i++) {
              if ('name' in attributes[i] && attributes[i]['name'] == attrName) {
                if ('value' in attributes[i] && attributes[i]['value'] == attrValue) {
                  found = true;
                  break;
                }
              }
            }
            if (!found) {
              $(this).addClass('hidden-item');
            }
          });
        }
      }
    }

    if ($searchContainer.length) {
      var search = $searchContainer.find('input.gg-search-keywords').val();
      if (search.length > 0) {
        $elements.not('[data-keywords *= "' + search.toLowerCase() + '"]').addClass('hidden-item');
      }
    }
    if ($container.find('a.grid-gallery-page').length > 0) {
      this.initPagination(false);
      //$container.find('a.grid-gallery-page:not(.gg-prev-pagination-btn)').first().trigger('click');
    } else {
      this.initWookmark();
      if (this.afterInitFlag) {
        this.correctMargin();
        this.initPopup();
        this.initVideoPopup();
      }
    }
  };

  ProGallery.prototype.initSearchByKeywords = function () {
    if (this.$container.attr('data-show-search') != 1) return;

    var $searchContainer = this.$container.find('.grid-gallery-search'),
      $searchButton = $searchContainer.find('a.gg-search-btn'),
      gallery = this;

    if ($searchContainer.data('custom-buttons')) {
      this.loadButtonsFontFamily($searchContainer.data('font'), null);
    }

    $searchButton.on('click', function (e) {
      e.preventDefault();
      gallery.doFilterAndSearch();
    });

    $searchContainer.css('display', 'block');
    $searchContainer.find('input').height($searchButton.height());
  };

  ProGallery.prototype.initDownloadButtons = function () {
    var container = this.$container;
    if (this.$container.attr('data-popup-enable-download-photos-button') == 1) {
      var popupType = this.$container.data('popup-type'),
        $downloadBtn = $('<a>').addClass('popup-download-photos-button'),
        $downloadBtnTxt = this.$container.attr('data-popup-enable-download-photos-button-text'),
        self = this,
        galleryId = container.attr('data-gg-id');

      $downloadBtn
        .attr({
          title: $downloadBtnTxt,
          download: '',
        })
        .text($downloadBtnTxt);

      switch (popupType) {
        case 'photobox':
        case 'pretty-photo':
          self.initVideoPopup($downloadBtn);
          break;
        case 'colorbox':
          $(document).bind('cbox_complete', function () {
            var $element = $.colorbox.element();
            $('#cboxContent').find('.popup-download-photos-button').remove();

            var interval = setInterval(function () {
              if (!$element.is('[data-video-source]')) {
                clearInterval(interval);
                $downloadBtn.attr({
                  href: $('#cboxContent').find('.cboxPhoto').attr('src'),
                });
                $('#cboxContent').append($downloadBtn);
              }
            }, 500);
          });
          break;
        default:
          break;
      }

      // $(document).on('click','.popup-download-photos-button',function() {
      // 	var href = $(this).attr('href'),
      // 		url = window.sggHelperajax_url+'?action='+window.sggHelperaction_download+'&id='+galleryId+'&size=full';//&image='+$(this).attr('href');
      // 		link = container.find('a[href="'+href+'"]'),
      // 		attachment = false;
      // 	if(link.length) {
      // 		attachment = link.attr('data-attachment-id');
      // 	}
      // 	if(attachment) {
      // 		url += '&attachment='+attachment+'&image='+href;
      //         window.open(url,'_self');
      //     }
      //
      // 	return false;
      // });
    }
    var sizes = container.attr('data-popup-download-sizes');
    if (typeof sizes != 'undefined' && sizes !== false) {
      var galleryId = container.attr('data-gg-id'),
        downloadSize = 'full',
        oneSize = true;

      if (sizes.length) {
        var menuElems = sizes.split('|');
        if (menuElems.length == 1) {
          downloadSize = menuElems[0];
        } else {
          var menuContent = '<div class="gg-download-wrapper"><div class="gg-download-sizes">';
          menuElems.push('cancel');

          menuElems.forEach(function (s) {
            var name = container.attr('data-popup-download-' + s);
            menuContent += '<a href="#" class="gg-download-size" data-size="' + s + '">' + (typeof name == 'undefined' ? s : name) + '</a>';
          });
          oneSize = false;
        }
      }
      $(document).on('click', '.gg-download-button', function () {
        if (oneSize) {
          var url = window.sggHelperajax_url + '?action=' + window.sggHelperaction_download + '&id=' + galleryId + '&size=' + downloadSize,
            attachment = $(this).attr('data-attachment-id');
          if (attachment) {
            url += '&attachment=' + attachment;
            window.open(url, '_self');
          }
        } else {
          var figcaption = $(this).closest('figcaption');
          if (figcaption.find('.gg-download-sizes').length) {
            figcaption.find('.gg-download-sizes').show();
          } else {
            figcaption.append(menuContent);
          }
        }
        return false;
      });
      if (!oneSize) {
        $(document).on('click', '.gg-download-size', function () {
          var size = $(this).attr('data-size');
          if (size != 'cancel') {
            var url = window.sggHelperajax_url + '?action=' + window.sggHelperaction_download + '&id=' + galleryId + '&size=' + size,
              button = $(this).closest('figcaption').find('.gg-download-button');
            if (button.length == 0) return false;
            var attachment = button.attr('data-attachment-id');
            if (attachment) {
              url += '&attachment=' + attachment;
              window.open(url, '_self');
            }
          }
          $(this).parent().hide();
          return false;
        });
      }
    }
  };

  ProGallery.prototype.setPrettyPhotoDownloadBtn = function ($downloadBtn) {
    $('.pp_content_container').find('.popup-download-photos-button').remove();
    var interval = setInterval(function () {
      if (typeof $('.pp_content_container') !== 'undefined') {
        clearInterval(interval);

        $downloadBtn
          .attr({
            href: $('#fullResImage').attr('src'),
          })
          .css({
            bottom: '3px',
            position: 'relative',
          });
        $('.pp_content_container').append($downloadBtn);
      }
    }, 500);
  };

  ProGallery.prototype.setPhotoboxDownloadBtn = function ($downloadBtn) {
    $('.pbWrapper').find('.popup-download-photos-button').remove();
    var interval = setInterval(function () {
      if (typeof $('.pbWrapper') !== 'undefined') {
        clearInterval(interval);

        $downloadBtn
          .attr({
            href: $('.pbWrapper').find('img').attr('src'),
          })
          .css({
            bottom: '75px',
          });
        $('.pbWrapper').append($downloadBtn);
      }
    }, 500);
  };

  ProGallery.prototype.initCategoriesButtons = function () {
    var $navigation = this.$navigation,
      $elements = $navigation.find('a'),
      $defaultBackground = $elements.first().css('background-color'),
      $background;

    if (this.$navigation.data('custom-buttons')) {
      this.initButtonStyles($elements, $navigation.data('font'), function () {
        $navigation.css({
          opacity: '1',
          transition: 'all 0.5s ease-out',
        });
      });
    } else {
      // Old
      $background = this.shadeColor('#' + this.rgb2hex($elements.first().css('borderTopColor')), 0.3);
      this.$navigation.find('a').on('click', function (event) {
        event.preventDefault();
        $elements.css('background-color', $defaultBackground);
        $(event.currentTarget).css('background-color', $background);
      });
    }
  };

  var showMorePause = false;

  ProGallery.prototype.initCategories = function () {
    this.initCategoriesButtons();

    this.$navigation.find('a').on(
      'click',
      $.proxy(function (event) {
        event.preventDefault();
        var $category = $(event.currentTarget).parent().find('a.active'),
          requested = [],
          _defaultTag = '__all__',
          self = this,
          currentGallery = this.$navigation.parent().attr('id');

        $category.each(function () {
          requested.push(String($(this).data('tag')));
        });

        var aTagClick = false;
        if (this.$navigation.parent().data('show-more-enabled')) {
          aTagClick = true;
          if (!showMorePause) {
            self.showMore(aTagClick);
            showMorePause = true;
          }
          setTimeout(function () {
            showMorePause = false;
          }, 1000);
        }

        if (requested.length == 1 && requested[0] == _defaultTag) {
          this.$elements.each(function () {
            var $parent = $(this).parent();

            if ($parent.attr('data-rel')) {
              $parent.attr('data-rel', 'prettyPhoto[' + currentGallery + ']');
            } else if ((self.$container.data('captionBuider') == 1 && self.$container.data('caption-builder-icons') == 1) || (self.$container.data('captionBuider') != 1 && self.$container.data('icons') != false)) {
              var $aLink = $(this).find('a');
              if ($aLink.attr('data-rel')) {
                $aLink.attr('data-rel', 'prettyPhoto[' + currentGallery + ']');
              }
            }

            if ($parent.hasClass('pbox') || $parent.hasClass('gg-colorbox')) {
              $parent.fadeIn();
            }
          });
          this.$elements.fadeIn();
          self.initWookmark();
          $.when(this.$elements.fadeIn()).done(function () {
            self.correctMargin();
            //self.initWookmark();
            //$(event.target).trigger('change.category');
            self.initPopup();
            self.initVideoPopup();
            self.initLinkedImages();

            if (!self.isFluidHeight() && self.$qsEnable) {
              self.callQuicksand(self.$qsHolder, self.$qsData, self.$qsDuration);
            }
            self.initMosaicGalleryType();
          });
          return false;
        }

        if (!this.isFluidHeight() && this.$qsEnable) {
          var $filteredData = this.$qsData.filter(function () {
            var tags = $(this).children().data('tags');
            if (typeof tags !== 'undefined') {
              tags = tags.toString().split('|');
            }
            for (var i = 0; i < requested.length; i++) {
              if ($.inArray(requested[i], tags) > -1) return true;
            }
            self.initMosaicGalleryType();
            return false;
          });
          this.callQuicksand(this.$qsHolder, $filteredData, this.$qsDuration);
        } else {
          var activeTags = requested.join(','),
            resultType = this.$navigation.data('multiselect-results');

          $hidden = $();
          $visible = $();
          this.$elements.each(function () {
            var $element = $(this),
              tags = $element.data('tags'),
              $parent = $element.parent();

            if (typeof tags != 'string') {
              tags = String(tags);
            }

            if (tags != undefined) {
              tags = tags.toString().split('|');
            }
            var foundTag = null,
              i;
            switch (resultType) {
              case 'all_selected':
                for (i = 0; i < requested.length; i++) {
                  var categorySorted = requested;
                  if (!arraysEqual(categorySorted.sort(), tags.sort())) {
                    foundTag = false;
                    break;
                  } else {
                    foundTag = true;
                  }
                }
                break;
              default:
                for (i = 0; i < tags.length; i++) {
                  if ($.inArray(tags[i], requested) > -1) {
                    foundTag = tags[i];
                    break;
                  }
                }
                break;
            }
            if (foundTag) {
              if ($parent.attr('data-rel')) {
                $parent.attr('data-rel', 'prettyPhoto[' + currentGallery + '-' + activeTags + ']');
              } else if ((self.$container.data('captionBuider') == 1 && self.$container.data('caption-builder-icons') == 1) || (self.$container.data('captionBuider') != 1 && self.$container.data('icons') != false)) {
                var $aLink = $(this).find('a');
                if ($aLink.attr('data-rel')) {
                  $aLink.attr('data-rel', 'prettyPhoto[' + currentGallery + '-' + activeTags + ']');
                }
              }

              if ($parent.hasClass('pbox') || $parent.hasClass('gg-colorbox')) {
                $visible.push($parent[0]);
              } else {
                $visible.push(this);
              }
            } else {
              if ($parent.hasClass('pbox') || $parent.hasClass('gg-colorbox')) {
                $hidden.push($parent[0]);
              } else {
                $hidden.push(this);
              }
            }
          });

          $.when($hidden.fadeOut()).done(function () {
            $visible.fadeIn().css({ height: '' });
            if (self.$container.data('gridType') == 1) {
              $visible.closest('a.gg-link[data-type="link"]').css('display', 'inline-block');
              $hidden.closest('a.gg-link[data-type="link"]').css('display', 'none');
            }
            self.correctMargin();
            self.initWookmark();
            self.initPopup();
            self.initVideoPopup();
            self.initLinkedImages();
            //$(event.target).trigger('change.category');
            self.lazyLoadTriggerHandler();
            self.initMosaicGalleryType();
            $visible.find('figure').css({ height: '' });
          });
        }

        setTimeout(function () {
          jQuery(window).trigger('resize');
        }, 1000);
      }, this)
    );

    if (!window.location.hash) {
      this.selectFirstCategory();
    }
  };

  ProGallery.prototype.initPaginationButtons = function () {
    var $navigation = this.pagination.$wrapper;
    var $elements = $navigation.find('a.grid-gallery-page');

    if (this.pagination.$wrapper.data('custom-buttons')) {
      this.initButtonStyles($elements, this.pagination.$wrapper.data('font'), function () {
        $navigation.css({
          opacity: '1',
          transition: 'all 0.5s ease-out',
        });
      });
    } else {
      // Old
      this.pagination.$wrapper.find('a.grid-gallery-page').on('click', function (event) {
        event.preventDefault();
        $elements.css('font-size', 'inherit');
        $(this).css('font-size', '19pt');
      });
    }
  };

  ProGallery.prototype.initPagination = function (all) {
    var all = typeof all == 'undefined' ? true : all;
    if (all) {
      this.initPaginationButtons();
    }
    this.buffer = [];

    var perPage = parseInt(this.$container.find('.grid-gallery-photos').data('per-page'), 10),
      buffer = this.buffer,
      page = 1,
      offset = 0,
      $elements = this.$elements,
      self = this;

    if (isNaN(perPage)) {
      this.$elements.fadeIn();
      return false;
    }

    if (this.$container.data('gridType') == 4 && this.$container.data('show-mosaic-all-img') != 1) {
      this.$elements.fadeIn();
      this.pagination.wrapper.hide();
      return false;
    }

    var showCurrentPage = function (page) {
      $.when($elements.removeClass('current-page').hide().parent('a').hide()).done(function () {
        $(buffer[page])
          .addClass('current-page')
          .css({ height: self.$container.data('height'), width: self.$container.data('width') })
          .fadeIn()
          .parent('a')
          .show();
        self.initWookmark();
        if (self.afterInitFlag) {
          self.correctMargin();
          self.initPopup();
          self.initVideoPopup();
        }
        self.initMosaicGalleryType();
      });
    };

    this.pagination.limit = perPage;

    // Create buffer
    var viewIndex = 0;
    $elements
      .each(
        $.proxy(function (index, el) {
          if (all || $(el).closest('a.gg-link').hasClass('hidden-item') == false) {
            if (all) {
              var currentIndex = index + 1;
            } else {
              viewIndex++;
              var currentIndex = viewIndex;
            }
            if (currentIndex - offset <= this.pagination.limit) {
              if (!$.isArray(buffer[page])) {
                buffer[page] = [];
              }
              buffer[page].push(el);
            } else {
              offset += this.pagination.limit;
              page += 1;

              buffer[page] = [el];
            }
          }
        }, this)
      )
      .hide();

    if (!all) {
      $buttons = this.pagination.$wrapper;
      $buttons.find('a.grid-gallery-page:not(.gg-prev-pagination-btn, .gg-next-pagination-btn)').css('display', 'none');
      $.each(buffer, function (page, els) {
        $buttons.find('a.grid-gallery-page[data-page="' + page + '"]').css('display', 'inline-block');
      });
    }

    this.pagination.$wrapper
      .find('a.grid-gallery-page')
      .off('click.pagination')
      .on('click.pagination', function (event) {
        event.preventDefault();
        if (self.$container.data('use-pagination-prev-next') == 1 && $('.grid-gallery-page.gg-prev-pagination-btn').length && $('.grid-gallery-page.gg-next-pagination-btn').length) {
          showCurrentPage($('.grid-gallery-pagination-wrap').attr('data-page'));
        } else {
          showCurrentPage($(event.currentTarget).attr('data-page'));
        }
      });

    this.pagination.$wrapper.find('a.grid-gallery-page:not(.gg-prev-pagination-btn)').first().trigger('click');
    //bug fixed for pagination
    setTimeout(function () {
      self.initWookmark();
    }, 500);
  };

  ProGallery.prototype.initInfiniteScroll = function () {
    if (!this.$container.is('[data-show-more-amount]') || this.InfinityScrollIsInitialized) {
      return;
    }
    // for Mosaic gallery type
    if (this.$container.data('gridType') == 4 && this.$container.attr('data-useMosaic2Layout') != 1) {
      return;
    }

    this.InfinityScrollIsInitialized = true;

    var self = this,
      $showMoreButton = this.$container.find('.showMore');

    this.initButtonStyles($showMoreButton, $showMoreButton.data('font'), null, true);

    if (self.$elements.length < $showMoreButton.data('total') || !$showMoreButton.data('total')) {
      $showMoreButton.show();
    }

    $showMoreButton.after($('<span class="showMoreLoading">' + self.loadingText + '</span>').css('display', 'none'));

    $showMoreButton.on('click', function (event) {
      event.preventDefault();
      self.showMore();
    });

    function onScrollHandler() {
      var scroll = true;
      self.showMore(false, scroll);
    }

    if (this.$container.data('show-more-scroll')) {
      $(window).on('scroll', onScrollHandler);
    }
  };

  ProGallery.prototype.showMore = function (aTagClick, scroll) {
    aTagClick = typeof aTagClick == 'undefined' ? false : aTagClick;
    scroll = typeof scroll == 'undefined' ? false : scroll;

    var $lastGalleryCaption = $('.grid-gallery-caption:last');
    var breakpoint = $lastGalleryCaption.length ? $lastGalleryCaption.offset().top : 0;

    if (scroll) {
      //previous load not completed
      if (this.scrollInProgress) {
        return;
      }
      //load new images if needed
      if (!(window.pageYOffset + window.innerHeight > breakpoint - window.innerHeight * 2)) {
        return;
      }
    }

    var categories = $('.grid-gallery-nav a'),
      pickedOutCategories = [],
      activeCategory = [],
      self = this,
      isLazyLoad = this.$container.data('lazyload-enable') == '1',
      $showMoreButton = this.$container.find('.showMore'),
      $photoWrapper = this.$container.find('.grid-gallery-photos');

    $.each(categories, function (index, item) {
      if ($(item).hasClass('active')) {
        activeCategory.push($(item).data('tag'));
      }
    });

    var limit = aTagClick ? this.$container.data('show-more-img-first-load') : this.$container.data('show-more-amount');

    if (aTagClick) {
      $photoWrapper.find('*').not('.grid-gallery-clearfix').remove();
    }

    var request = {
      action: window.sggHelperaction,
      gallery_id: this.$container.data('id'),
      limit: limit,
      except: '',
      category: '',
      posts_except: '',
      offset: 0,
    };

    function showMore() {
      var except = [],
        except_posts = [];

      self.scrollInProgress = true;
      request.category = '';

      if (activeCategory.length) {
        request.category = activeCategory.join(',');
      }
      //if click on category a tags no need except
      if (!aTagClick) {
        $.each(self.$elements, function (index, element) {
          if (typeof $(element).data('id') !== 'undefined' && $(element).is(':visible')) {
            except.push($(element).data('id'));
          }

          if (typeof $(element).data('post-id') !== 'undefined' && $(element).is(':visible')) {
            except_posts.push($(element).data('post-id'));
          }
        });
        request.except = except.join(',');
        request.except_posts = except_posts.join(',');
        request.offset++;
      }

      updateImages(request, function (response) {
        if (response.success) {
          $showMoreButton.next('.showMoreLoading').hide();
          var $elements = $($.parseHTML(response.images.trim()));
          if (!aTagClick) {
            self.$container.find('.grid-gallery-photos > *').not('.grid-gallery-clearfix').last().after($elements);
          } else {
            self.$container.find('.grid-gallery-photos').prepend($elements);
          }

          self.$container.imagesLoaded(function () {
            $.merge(self.$elements, $elements.find('figure.grid-gallery-caption').addBack().not($('.post, .gg-link')).fadeIn());

            if (($elements.length < request.limit && response.remaining === 0) || (response.remaining === 0 && response.remaining_posts == 0)) {
              $showMoreButton.hide();
              if (request.category) {
                pickedOutCategories.push(request.category);
              } else {
                $.each(categories, function (index, item) {
                  pickedOutCategories.push($(item).data('tag'));
                });
                // console.log('off scroll');
                // $(window).off('scroll', onScrollHandler);
                //this.scrollInProgress = true;
              }
            } else {
              if (!aTagClick) {
                self.$container.one('wookmark.changed', function (event) {
                  breakpoint = self.$elements.filter(':visible').last().offset().top;
                });
              }
              $showMoreButton.show();
              self.scrollInProgress = false;
            }
            self.reinit();
            if (isLazyLoad) {
              self._parent('initLazyLoad');
              self._parent('lazyLoadDistanceRefresh');
            }
            self.initPopup();
          });
        }
      });
    }

    function updateImages(request, response) {
      $showMoreButton.add($showMoreButton.next('.showMoreLoading')).toggle();
      $.post(window.sggHelperajax_url, request, response);
    }

    showMore();
  };

  ProGallery.prototype.initVideoPopup = function (download) {
    if (!this.$container.find('[data-video-source]').length && typeof download === 'undefined') {
      return;
    }

    function loadApi(src) {
      var tag = document.createElement('script');
      tag.src = src;
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    function createYouTubePlayer(options) {
      player = new YT.Player(options.node, {
        startFrom: options.start || 0,
        width: options.width || '853',
        height: options.height || '480',
        videoId: options.videoId,
        playerVars: options.params,
        events: {
          onReady:
            options.onReady ||
            function (event) {
              if (popupSettings.autoplay === 'true') {
                event.target.playVideo();
              }
            },
          onStateChange: options.onStateChange,
        },
      });
      return player;
    }

    //HTML5 Player http://www.w3schools.com/tags/ref_av_dom.asp
    function createHTML5Player(src, $playerBox, endfunc, frame) {
      var $player = $(document.createElement('video')),
        $source = $(document.createElement('source')),
        videoWidth = Number(popupSettings.width || '853'),
        videoHeight = Number(popupSettings.height || '480'),
        onEndAction = Number(popupSettings.onEnd || 0);

      //Init
      $source.attr('src', src);
      $player.attr('controls', 'controls');
      $player.attr('width', videoWidth);

      if (window.innerWidth > videoWidth) {
        $player.attr('height', videoHeight);
        $player.css('margin-bottom', '0px');
      }
      if (popupSettings.autoplay == 'true') {
        $player.attr('autoplay', 'autoplay');
      }
      $player.append($source);

      if (frame && frame != null) {
        $playerBox.contents().find('body').html($player);
      } else {
        $playerBox.html($player);
      }

      //Controll
      $player.on('click', function () {
        if (this.paused) {
          this.play();
        } else {
          this.pause();
        }
      });
      $player.on('ended', endfunc);
      return $player;
    }

    if ($('[data-video-source="youtube"]').length > 0) {
      loadApi('//www.youtube.com/iframe_api');
    }

    if ($('[data-video-source="vimeo"]').length > 0) {
      loadApi('https://player.vimeo.com/api/player.js');
    }

    var $videoIframe,
      isVideo,
      videoSource,
      videoId,
      popupType = this.$container.data('popup-type'),
      popupRel = this.$container.attr('id'),
      popupSettings = this.$container.data('popup-video') || {},
      videoWidth = Number(popupSettings.width || '853'),
      videoHeight = Number(popupSettings.height || '480'),
      onEndAction = Number(popupSettings.onEnd || 0),
      youTubeRegExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/,
      slidePlay = this.$container.data('popup-slideshow') === true,
      slidePlayAuto = slidePlay && this.$container.data('popup-slideshow-auto') === true,
      popupHoverStop = slidePlay && this.$container.data('popup-hoverstop') === true,
      isLazyLoad = this.$container.data('lazyload-enable') == '1',
      self = this;

    if (popupType == 'colorbox') {
      $('<style type="text/css">.video > .pbWrapper > div { width: ' + videoWidth + 'px; height: ' + videoHeight + 'px; } #cboxLoadedContent iframe { margin: 0; padding: 0;}</style>').appendTo('head');

      function colorBoxVideoEnd() {
        if (onEndAction > 0) {
          if (onEndAction === 1) {
            $.colorbox.next();
          } else if (onEndAction === 2) {
            $.colorbox.close();
          }
        }
      }

      if (videoWidth > window.innerWidth) {
        videoWidth = '90%';
      }

      this.$colorbox = this.$container.find('.gg-video:visible').colorbox({
        iframe: true,
        fadeOut: this.$container.data('popup-fadeOut'),
        fixed: true,
        scrolling: false,
        scalePhotos: true,
        innerWidth: videoWidth,
        innerHeight: videoHeight,
        maxWidth: '90%',
        returnFocus: false,
        slideshow: this.$container.data('popup-slideshow'),
        slideshowAuto: this.$container.data('popup-slideshow-auto'),
        slideshowSpeed: this.$container.data('popup-slideshow-speed'),
        rel: popupRel,
        slideshowStart: self.popupTranslates.start_slideshow,
        slideshowStop: self.popupTranslates.stop_slideshow,
        current: self.popupTranslates.image + ' {current} ' + self.popupTranslates.of + ' {total}',
        previous: self.popupTranslates.previous,
        next: self.popupTranslates.next,
        close: self.popupTranslates.close,
        title: function () {
          return self.getPopupTitle($(this));
        },
        onLoad: function () {
          videoSource = $(this).data('video-source');
          $('#cboxContent').find('.popup-download-photos-button').remove();
        },
        onComplete: function (e) {
          self.changePopUpHash($(e.el).attr('id') || $(e.el).attr('data-id'));
          self.addSocialShareToPopUp($(e.el), $('#cboxContent'), 'popup');
          // $('#colorbox').css('padding', '0px');
          $('#cboxLoadedContent').css('opacity', 0);
          $('#cboxLoadingGraphic').show();
          $videoFrame = $('#cboxLoadedContent iframe');
          $videoFrameWrapper = $videoFrame.parent();
          if (videoSource == 'youtube') {
            if ($videoFrameWrapper.attr('data-iframe') === 'original_frame') {
              var width = $videoFrame.parent().width(),
                height = Math.round((width / 16) * 9);
              $videoFrame.css({ width: width, height: height });

              $('#cboxLoadingGraphic').hide();
              $('#cboxLoadedContent').css({
                opacity: 1,
                overflow: 'hidden',
                transition: 'all 0.4s',
              });
            } else {
              $videoFrame.stop();
              $('<div/>', { id: 'sgg-youtube-player' }).insertBefore($videoFrame);

              var width = $videoFrame.parent().width() - 4,
                height = Math.round((width / 16) * 9);

              var startWith = typeof self.savedColorBoxVideoStartTime != 'undefined' ? self.savedColorBoxVideoStartTime : 0;
              self.currentVideoPlayer = createYouTubePlayer({
                startFrom: startWith,
                node: 'sgg-youtube-player',
                videoId: $(this).attr('href').match(youTubeRegExp) ? $(this).attr('href').match(youTubeRegExp)[1] : '',
                params: $(this)
                  .attr('href')
                  .match(/[\?&]rel=0/i)
                  ? { rel: 0 }
                  : {},
                onReady: function (event) {
                  $('#cboxLoadingGraphic').hide();
                  $('#cboxLoadedContent').css({
                    opacity: 1,
                    overflow: 'hidden',
                    transition: 'all 0.4s',
                  });
                  if (popupSettings.autoplay === 'true' || startWith) {
                    if (startWith) {
                      event.target.seekTo(startWith);
                    }
                    event.target.playVideo();
                  }
                },
                width: width,
                height: height,
                onStateChange: function (event) {
                  if (event.data === 0) {
                    colorBoxVideoEnd();
                  }
                  if (event.data === 1) {
                    $('.pp_nav a.pp_play').addClass('stop');
                    $('.pp_nav a.pp_pause').addClass('stop');
                  } else {
                    $('.pp_nav a.pp_play').removeClass('stop');
                    $('.pp_nav a.pp_pause').removeClass('stop');
                  }
                },
              });
              $videoFrame.remove();
            }
          }

          if (videoSource == 'vimeo') {
            var player = new Vimeo.Player($videoFrame[0]); //$f($videoFrame[0]);
            self.currentVideoPlayer = player;
            player.ready().then(function () {
              $('#cboxLoadingGraphic').hide();
              $('#cboxLoadedContent').css({
                opacity: 1,
                overflow: 'hidden',
                transition: 'all 0.4s',
              });
              if (popupSettings.autoplay === 'true') {
                player.play().catch(function (error) {
                  console.error('error playing the video:', error.name);
                });
              }
              player.on('ended', colorBoxVideoEnd);
            });
          }

          if (videoSource == 'other') {
            createHTML5Player($('#cboxLoadedContent iframe').attr('src'), $('#cboxLoadedContent'), colorBoxVideoEnd);
            $('#cboxLoadingGraphic').hide();
            $('#cboxLoadedContent').css({
              opacity: 1,
              overflow: 'hidden',
              transition: 'all 0.4s',
            });
          }

          var width = $videoFrame.parent().width(),
            height = Math.round((width / 16) * 9);

          if ($videoFrameWrapper.attr('data-iframe') !== 'original_frame') {
            $.colorbox.resize({ width: width, height: height });
          }
        },
        onClosed: function () {
          self.clearPopUpHash();
        },
      });

      // need because after change category "theme class" disapearing
      $('#cboxOverlay')
        .removeClass()
        .addClass(self.$container.data('popup-theme') + '-overlay');
      $('#colorbox').removeClass().addClass(self.$container.data('popup-theme'));
    }

    if (popupType == 'pretty-photo') {
      function prettyPhotoVideoEnd() {
        if (onEndAction > 0 && self.$prettyPhoto) {
          if (onEndAction === 1) {
            self.$prettyPhoto.changePage('next');
          } else if (onEndAction === 2) {
            self.$prettyPhoto.close();
          }
        }
      }

      if (this.$container.attr('data-show-buttonlink-in-popup') == 1) {
        this.loadButtonsFontFamily(this.$container.attr('data-buttonlink-font'), null);
      }

      var findPrettyItems =
        typeof download !== 'undefined'
          ? this.$container.find(".grid-gallery-photos > a[data-rel^='prettyPhoto'], .grid-gallery-photos .hi-icon-wrap > a[data-rel^='prettyPhoto'], .grid-gallery-photos .gg-mosaic-wrapper a[data-rel^='prettyPhoto']")
          : this.$container.find(".grid-gallery-photos > a[data-rel^='prettyPhoto'], .grid-gallery-photos .hi-icon-wrap > a[data-rel^='prettyPhoto']");

      this.$prettyPhoto = findPrettyItems.ggPrettyPhoto({
        hook: 'data-rel',
        theme: 'light_square',
        default_width: this.popupMaxWidth,
        default_height: this.popupMaxHeight,
        default_movie_width: popupSettings.width || '853',
        default_movie_height: popupSettings.height || '480',
        allow_resize: true,
        allow_expand: false,
        slideshow: this.$container.data('popup-slideshow-speed'),
        autoplay_slideshow: this.$container.data('popup-slideshowAuto'),
        social_tools: '',
        deeplinking: false,
        // callback: function() {},
        is_lazy_load: isLazyLoad,
        ppTranslates: self.popupTranslates,
        isDisableRightClick: this.$container.attr('data-disable-right-click') == 'true',
        isShowRotateBtn: this.$container.attr('data-show-rotate-btn-in-popup') == 1,
        isShowAttributes: this.$container.attr('data-show-attributes-in-popup') == 1,
        attributesPosition: this.$container.attr('data-attributes-position'),
        attributesWidth: this.$container.attr('data-attributes-width'),
        isShowButtonLink: this.$container.attr('data-show-buttonlink-in-popup') == 1,
        buttonLinkStyle: this.$container.attr('data-buttonlink-style'),
        isShowLinkBtn: this.$container.attr('data-show-link-btn-in-popup') == 1,
        isShowHovThumbnail: this.$container.attr('data-show-thumb-hov-in-popup') == 1,
        galleryId: this.$container.attr('data-gg-id'),
        getImageDimensions: this.popupImageDimension,
        setImageTitleForPrepare: function ($element) {
          var imgTile = self.getPopupTitle($element);
          return imgTile;
        },
        changepicturecallback: function (element) {
          self.changePopUpHash(element.attr('id') || element.attr('data-id'));
          self.popup_opened_image = element;
          $('.pp_description').html(self.getPopupTitle(element)).show();

          //hide thumbnails on video popup and show it on image popup
          isVideo = $(element).is('[data-video-source]');
          if (isVideo) {
            $('.pp_content_container').find('.popup-download-photos-button').remove();
            self.addSocialShareToPopUp(element, $('#pp_full_res'), 'popup prettyPhoto');
            $('.pp_gallery').hide();
          } else {
            self.addSocialShareToPopUp(element, $('.pp_hoverContainer'), 'popup');
            $('.pp_gallery').show();
          }

          if (self.$container.attr('data-show-attributes-in-popup') == 1) {
            window.prettyPhotoAttributes(element, self.$container);
          }

          //Enable/Disable stop slideshow on mouse hover
          if (popupHoverStop) {
            $('.pp_fade').hover(
              function () {
                $('.pp_nav .pp_pause').click();
              },
              function () {
                if (slidePlayAuto) {
                  $('.pp_nav .pp_play').click();
                }
              }
            );
          }

          videoSource = $(element).data('video-source');
          $videoFrame = $('#pp_full_res iframe');

          if (!slidePlay) {
            $('.pp_play').hide();
          }

          if (videoSource == 'youtube') {
            var frameHtml = htmlDecode($(element).attr('href'));

            if (frameHtml.match(/<iframe(.+)<\/iframe>/i)) {
              var frameWrapper = $('#pp_full_res');
              frameWrapper.html(frameHtml);
              frameWrapper.$videoFrame.attr('width');
              frameWrapper.$videoFrame.attr('height');
            } else {
              $videoFrame.stop();
              var videoSrc = $videoFrame.attr('src');
              videoId = videoSrc.match(youTubeRegExp)[1];
              var $newFrame = $('<div/>', { id: 'sgg-youtube-player' }).insertBefore($videoFrame);
              createYouTubePlayer({
                node: 'sgg-youtube-player',
                videoId: videoId,
                width: $videoFrame.attr('width'),
                height: $videoFrame.attr('height'),
                params: videoSrc.match(/[\?&]rel=0/i) ? { rel: 0 } : {},
                onStateChange: function (event) {
                  if (event.data === 0) {
                    prettyPhotoVideoEnd();
                  }
                  if (event.data === 1) {
                    $('.pp_nav a.pp_play').addClass('stop');
                    $('.pp_nav a.pp_pause').addClass('stop');
                  } else {
                    $('.pp_nav a.pp_play').removeClass('stop');
                    $('.pp_nav a.pp_pause').removeClass('stop');
                  }
                },
              });
              $videoFrame.remove();
            }
          }

          if (videoSource == 'vimeo') {
            var player = new Vimeo.Player($videoFrame[0]);
            player.ready().then(function () {
              if (popupSettings.autoplay === 'true') {
                player.play().catch(function (error) {
                  console.error('error playing the video:', error.name);
                });
              }
              player.on('ended', prettyPhotoVideoEnd);
            });
          }

          if (videoSource == 'other') {
            createHTML5Player($('#pp_full_res source').attr('src'), $('#pp_full_res'), prettyPhotoVideoEnd);
          }
          var $_desc = $('.pp_description'),
            desc_height = parseInt($_desc.height()),
            desc_line_height = parseInt($_desc.css('font-size'));
          if (desc_line_height < desc_height) {
            $('.pp_content').height($('.pp_content').height() + desc_height - desc_line_height);
          }

          if (typeof download !== 'undefined' && !videoSource) {
            self.setPrettyPhotoDownloadBtn(download);
          }
        },
        callback: function () {
          self.popup_opened_image = false;
          self.clearPopUpHash();
        },
      });
    }

    if (popupType == 'photobox') {
      var photoBoxConfig = null;

      $('<style type="text/css">.video > .pbWrapper > div { width: ' + videoWidth + 'px; height: ' + videoHeight + 'px; }</style>').appendTo('head');

      $('#pbOverlay .prevNext').on('click', function () {
        self.setPhotoboxMobileArrowPosition(1);
      });

      function photoBoxVideoEnd() {
        if (onEndAction > 0) {
          if (onEndAction === 1) {
            $('#pbNextBtn').trigger('click');
          } else if (onEndAction === 2) {
            $('#pbCloseBtn').trigger('click');
          }
        }
      }

      $(window).on('resize', function () {
        if (videoWidth > window.innerWidth) {
          $('.pbWrapper .zoomable').attr('style', 'width: ' + window.innerWidth * 0.8 + 'px; height: ' + ((window.innerWidth * 0.8) / 4) * 3 + 'px;');
          $('img.zoomable').removeAttr('style');
        }
        clearTimeout(window.photoBoxResizeTimeout);
        window.photoBoxResizeTimeout = setTimeout(function () {
          if ($('#pbOverlay').hasClass('thumbs') && window.screen.width < 900) {
            $('#pbOverlay').width(window.screen.width);
            $('#pbOverlay').height(window.screen.height);
            $(document).scrollLeft(0);
          }
          var $videoFrame = $('#pbOverlay .pbWrapper iframe');
          if ($videoFrame.length > 0) {
            if ($videoFrame.width() > window.screen.width) {
              $videoFrame.parent().css({
                width: window.screen.width,
                height: '100%',
              });
            }
            if ($videoFrame.height() > window.screen.height) {
              $videoFrame.parent().css({
                height: window.screen.height,
                width: '100%',
              });
            }
          }
        }, 100);
      });

      this.$container.find('.grid-gallery-photos').photobox('destroy');
      photoBoxConfig = {
        autoplay: this.$container.data('popup-slideshowAuto'),
        beforeShow: function (element) {
          self.changePopUpHash($(element).attr('id') || $(element).attr('data-id'));
          self.addSocialShareToPopUp($(element), $('#pbCaption'), 'photobox', true);
          videoSource = $(element).data('video-source');

          if (typeof download !== 'undefined' && !videoSource) {
            self.setPhotoboxDownloadBtn(download);
          } else {
            $('.pbWrapper').find('.popup-download-photos-button').remove();
          }
        },
        afterClose: function () {
          $('#pbOverlay .pbWrapper iframe').remove();
          self.clearPopUpHash();
        },
        getTitle: function (el) {
          return self.getPopupTitle($(el));
        },
        thumb: function (link) {
          if (self.$container.data('caption-buider') == '1' && self.$container.data('caption-builder-icons') == 1) {
            return link.closest('.grid-gallery-caption').find('img')[0];
          } else if (self.$container.data('icons')) {
            return link.closest('.grid-gallery-caption').find('img')[0];
          }
          return null;
        },
      };
      if (isLazyLoad) {
        photoBoxConfig['thumbAttr'] = 'data-gg-real-image-href';
      }

      this.$container.find('.grid-gallery-photos').photobox('a.pbox', photoBoxConfig, function (event) {
        var $videoFrame = $('#pbOverlay .pbWrapper iframe');
        $videoFrameWrapper = $videoFrame.parent();
        if (videoSource == 'youtube') {
          if ($videoFrameWrapper.attr('data-iframe') !== 'original_frame') {
            $videoFrame.stop();
            var videoSrc = $videoFrame.attr('src');
            videoId = videoSrc.match(youTubeRegExp)[1];
            $('<div/>', { id: 'youtube-player' }).insertBefore($videoFrame);
            $videoFrame.remove();
            createYouTubePlayer({
              node: 'youtube-player',
              videoId: videoId,
              params: videoSrc.match(/[\?&]rel=0/i) ? { rel: 0 } : {},
              onReady: function (event) {
                $(window).trigger('resize');
                if (popupSettings.autoplay === 'true') {
                  event.target.playVideo();
                }
              },
              onStateChange: function (event) {
                if (event.data === 0) {
                  photoBoxVideoEnd();
                }
                // arrows visibility for mobile
                if (event.data == 1) {
                  self.setPhotoboxMobileArrowPosition(0);
                } else {
                  self.setPhotoboxMobileArrowPosition(1);
                }
                if (event.data === 1) {
                  $('.pp_nav a.pp_play').addClass('stop');
                  $('.pp_nav a.pp_pause').addClass('stop');
                } else {
                  $('.pp_nav a.pp_play').removeClass('stop');
                  $('.pp_nav a.pp_pause').removeClass('stop');
                }
              },
            });
          }
        }
        if (videoSource == 'vimeo') {
          var player = new Vimeo.Player($videoFrame[0]);
          player.ready().then(function () {
            if (popupSettings.autoplay === 'true') {
              $(window).trigger('resize');
              player.play().catch(function (error) {
                console.error('error playing the video:', error.name);
              });
            }
            player.on('ended', photoBoxVideoEnd);
            player.on('play', function (paused) {
              self.setPhotoboxMobileArrowPosition(0);
            });
            player.on('pause', function (paused) {
              self.setPhotoboxMobileArrowPosition(1);
            });
          });
        }
        if (videoSource == 'other') {
          createHTML5Player($videoFrame.attr('src'), $videoFrame, photoBoxVideoEnd, true);
          $(window).trigger('resize');
        }
      });
      $(document).on('pboxPreviewClicked', function () {
        self.setPhotoboxMobileArrowPosition(1);
      });
    }
  };

  ProGallery.prototype.setPhotoboxMobileArrowPosition = function (pos) {
    if (this.$container.data('popupType') == 'photobox' && this.$container.data('isMobile') == 1) {
      var $arrows = $('#pbOverlay .prevNext');
      if (pos == 0) {
        $arrows.css('z-index', '0');
      } else {
        $arrows.css('z-index', '100');
      }
    }
  };

  ProGallery.prototype.changeLinkedImageScrollOffset = function (event) {
    var $linkedImageObj = $('.linkedImages');

    var scrollHeight = parseInt($linkedImageObj.prop('scrollHeight'));
    var currScroll = parseInt($linkedImageObj.scrollTop());
    var clickOffset = 85;

    if (!isNaN(scrollHeight) && !isNaN(currScroll) && event && event.data) {
      var newScrollOfsset;
      if (event.data.direction == 0) {
        newScrollOfsset = currScroll - clickOffset;
        if (newScrollOfsset < 0) {
          newScrollOfsset = 0;
        }
      } else {
        newScrollOfsset = currScroll + clickOffset;
        if (newScrollOfsset > scrollHeight) {
          newScrollOfsset = scrollHeight;
        }
      }
      $linkedImageObj.stop(true, true).animate({ scrollTop: newScrollOfsset }, 500);
    }
  };

  ProGallery.prototype.autoChangeLinkImgScrollOffset = function (selItem) {
    var $linkedImageObj = $('.linkedImages');
    if (selItem.length && selItem[0].offsetTop) {
      var wndPaddingTop = 55;
      if ($(window).width() < 500) {
        wndPaddingTop = 35;
      }
      var newOffset = parseInt(selItem[0].offsetTop) - wndPaddingTop;
      if (!isNaN(newOffset)) {
        $linkedImageObj.stop(true, true).animate({ scrollTop: newOffset }, 1000);
      }
    }
  };

  ProGallery.prototype.getLinkedImagesContainer = function () {
    var $colorBoxObj = $('#colorbox');
    var $linkedImagesContainer = $('<div class="linkedImages">')
      .css({
        position: 'absolute',
        right: '0',
        bottom: '0',
        width: '75px',
      })
      .appendTo($colorBoxObj);
    $colorBoxObj.css('padding-right', '85px');

    // add "right": "top arrow" and "bottom arrow"
    $('<div class="link-img-arr-top"></div>').appendTo($colorBoxObj);
    $('<div class="link-img-arr-bottom"></div>').appendTo($colorBoxObj);
    $('#colorbox .link-img-arr-top').on('click', { direction: 0 }, this.changeLinkedImageScrollOffset);
    $('#colorbox .link-img-arr-bottom').on('click', { direction: 1 }, this.changeLinkedImageScrollOffset);
    return $linkedImagesContainer;
  };

  ProGallery.prototype.initColorBoxOnLoadHandler = function () {
    var self = this;

    $(document).on('cbox_load', function (event) {
      if (self.popup_opened_image == $.colorbox.element()) return;
      self.popup_opened_image = $.colorbox.element();

      var $linkElem = $($.colorbox.element()),
        popupWindowWidth = parseFloat(($(window).width() * 95) / 100),
        mobileWidth = popupWindowWidth < 700,
        $colorBoxPopup = $('#colorbox'),
        linkedImage = $linkElem.is('.gg-video') ? undefined : $linkElem.children('figure').attr('data-linked-images');

      if (linkedImage || $linkElem.hasClass('linked-element')) {
        if (!$linkElem.hasClass('linked-element')) {
          if (self.$container.data('popupDisableSourceImageForLinkedImg') == 1) {
            var images = $.parseJSON(linkedImage);
            if (images && images.length > 0) {
              $linkElem.attr('href', images[0]['full']);
            }
          }
          // its parameter show that linked images exists
          if ($colorBoxPopup.css('padding-right')) {
            var tmpPaddingRight = parseInt($colorBoxPopup.css('padding-right'), 10),
              tmpLeft = parseInt($colorBoxPopup.css('left'), 10);
            if (!isNaN(tmpPaddingRight) && !isNaN(tmpLeft)) {
              if (tmpLeft - tmpPaddingRight < 0) {
                $.colorbox.settings.left = 0;
              } else {
                if (mobileWidth) {
                  // only when left not settet previously
                  if ($.colorbox.settings.left == false) {
                    $.colorbox.settings.left = tmpLeft - tmpPaddingRight;
                  }
                }
              }
            } else {
              $.colorbox.settings.left = false;
            }
          }
        }
      } else {
        $.colorbox.settings.left = false;
        $.colorbox.settings.width = false;
      }

      if (mobileWidth) {
        $.colorbox.settings.width = popupWindowWidth - tmpPaddingRight;
      }

      // resize once
      if (!self.initPopupWndSizeForLinkedImages && $linkElem.children('figure').attr('data-linked-images')) {
        self.initPopupWndSizeForLinkedImages = true;
        $.colorbox.resizeResponsive(self.popup_opened_image);
      }
    });
  };

  ProGallery.prototype.initLinkedImages = function () {
    if ($('#colorbox .linkedImages').length) {
      return;
    }

    var self = this;
    var $linkedImagesContainer = self.getLinkedImagesContainer();
    var $linkedImagesColorbox;
    var isLinkedImage = false;
    var isIconsEnabled = false;

    var $linkedImageTemplate = $('<a class="linked-element"><img></a>').on('click', function () {
      isLinkedImage = true;
    });

    $(document).on('cbox_complete', function (event) {
      if (!$('#colorbox .linkedImages').length) {
        $linkedImagesContainer = self.getLinkedImagesContainer();
      }

      var $colorboxElement = $.colorbox.element(),
        $element,
        paddingLeft = 0,
        $colorBoxPopup = $('#colorbox'),
        maxLinkedImgWindowWidth = parseFloat(($(window).width() * 95) / 100),
        colorboxSettings = $colorboxElement.data('colorbox');

      if ($colorboxElement.hasClass('hi-icon')) {
        $element = $colorboxElement.closest('.grid-gallery-caption');
        isIconsEnabled = true;
      } else {
        $element = $colorboxElement.find('.grid-gallery-caption');
      }

      if ($colorboxElement.hasClass('linked-element')) {
        $('#colorbox .linkedImages a').removeClass('active');
        $colorboxElement.addClass('active');
      }

      if (isLinkedImage && !$element.length) {
        return;
      }

      $('#colorbox .link-img-arr-top').hide();
      $('#colorbox .link-img-arr-bottom').hide();
      $('#colorbox .linkedImages').empty();

      var linkedImages = $colorboxElement.is('.gg-video') ? undefined : $element.data('linked-images');
      if (linkedImages) {
        // fix maxWidth for linked images
        if ($colorBoxPopup.css('padding-right')) {
          var tmpVal = parseInt($colorBoxPopup.css('padding-right'), 10);
          if (!isNaN(tmpVal)) {
            paddingLeft = tmpVal;
          }
        }
        colorboxSettings.maxWidth = maxLinkedImgWindowWidth - paddingLeft;

        for (var i = linkedImages.length - 1; i >= 0; i--) {
          var $template = $linkedImageTemplate.clone(true, true),
            linkImageText = null;

          if ($element.data('linked-image-title') == 'data-description') {
            linkImageText = linkedImages[i].description;
          } else if ($element.data('linked-image-title') == 'data-title') {
            linkImageText = linkedImages[i].title;
          } else if ($element.data('linked-image-title') == 'alt') {
            linkImageText = linkedImages[i].alt;
          }

          $template.attr('data-href', linkedImages[i].full);
          $template
            .find('img')
            .attr('src', linkedImages[i].thumbnail || linkedImages[i].full)
            .attr('title', linkImageText);
          $template.appendTo($linkedImagesContainer);
        }

        $linkedImagesColorbox = $('#colorbox .linkedImages a').colorbox(
          $.extend({}, colorboxSettings, {
            iframe: false,
            onComplete: function (e) {
              var $imgObj = $('#cboxWrapper .cboxPhoto');
              $imgObj.css({ margin: '0' });
              self.changePopUpHash($(e.el).attr('id') || $(e.el).attr('data-id'));
              self.addSocialShareToPopUp($(e.el), $('#cboxContent'), 'popup');
              self.$container.find('.grid-gallery-photos > .gg-colorbox, .hi-icon.gg-colorbox').colorbox.resize({
                width: $imgObj.width(),
                height: $imgObj.height(),
                innerHeight: $imgObj.innerHeight(),
                innerWidth: $imgObj.innerWidth(),
              });
              $('#cboxLoadedContent').append("<div id='cboxRight'></div><div id='cboxLeft'></div>");
            },
            rel: 'grid-gallery-' + $element.data('id'),
            href: function () {
              return $(this).attr('data-href');
            },
            onOpen: function (e) {
              colorboxSettings.onOpen(e);
            },
            onLoad: function (e) {
              if (self.popup_opened_image != false && self.popup_opened_image == e.el) return;
              self.popup_opened_image = e.el;
              self.autoChangeLinkImgScrollOffset($(self.popup_opened_image));
            },
            onClosed: function (e) {
              self.popup_opened_image = false;
              colorboxSettings.onClosed(e);
            },
          })
        );

        var thumbnail = $element.find('img').attr('src'),
          thumbNailSize = self.$container.data('thumbnail-size');
        if (!thumbNailSize) {
          thumbNailSize = '150x150';
        }

        if (/(-\d{2,}x\d{2,})(:?\..{3,})?$/.test(thumbnail)) {
          thumbnail = thumbnail.replace(/(-\d{2,}x\d{2,})(:?\..{3,})?$/, function (string, match, extension) {
            return string.replace(match + extension, '-' + thumbNailSize + extension);
          });
        }

        if (self.$container.data('popupDisableSourceImageForLinkedImg') != 1) {
          var $parentTemplate = $linkedImageTemplate.clone(true, true);
          $parentTemplate.find('img').attr('src', thumbnail);
          $parentTemplate.prependTo($linkedImagesContainer);
          $parentTemplate.addClass('active');
          $parentTemplate.on('click', function () {
            if (isIconsEnabled) {
              $element.find('a').trigger('click');
            } else {
              $colorboxElement.trigger('click');
            }
          });
        }

        $('#colorbox .linkedImages').fadeIn();
        $('#colorbox .linkedImages img').css('margin-bottom', '10px');

        var $linkedImageObj = $('.linkedImages');
        var scrollHeight = parseInt($linkedImageObj.prop('scrollHeight'));
        if (scrollHeight > $linkedImageObj.height()) {
          $('#colorbox .link-img-arr-top').show();
          $('#colorbox .link-img-arr-bottom').show();
        }

        if (self.$container.data('popupDisableSourceImageForLinkedImg') != 1) {
          // need to optimize and remove code bellow
          // fix for first Gallery show
          if (self.prevPopUpOpenedImg != self.popup_opened_image) {
            self.prevPopUpOpenedImg = self.popup_opened_image;
            setTimeout(function () {
              $.colorbox.resizeResponsive(self.popup_opened_image);
            }, 300);
          }
        }
      }
    });
  };

  ProGallery.prototype.initDisableRightClick = function () {
    if (this.$container.attr('data-disable-right-click')) {
      $(this.$container.find('.grid-gallery-photos')).bind('contextmenu', function (e) {
        return false;
      });
    }
  };

  ProGallery.prototype.addCaptionOnHoverImageEvent = function ($figure, $image, hoverImgSrc) {
    var fadeTimeout = 100;
    if ($image.hasClass('ggLazyImg')) return;

    if ($image.attr('src') != hoverImgSrc) {
      // need setup width and height, because hover image proportion was incompatible
      if ($image.length && $image[0].naturalWidth && this.$container.data('gridType') != 4) {
        $image.css('width', $image[0].naturalWidth);
      } else {
        $image.css('width', $image.width());
      }
      if ($image.length && $image[0].naturalHeight && this.$container.data('gridType') != 4) {
        $image.css('height', $image[0].naturalHeight);
      } else {
        $image.css('height', $image.height());
      }

      // save source image to attribute
      $image.attr('without-hover-img-effect', $image.attr('src'));
      if (!$image.is('[data-gg-real-image-href]')) {
        $image.attr('data-gg-real-image-href', $image.attr('src'));
      }
    }

    $figure
      .on('mouseenter', function (event) {
        $image.attr('src', hoverImgSrc);
      })
      .on('mouseleave', function () {
        $image.attr('src', $image.attr('data-gg-real-image-href'));
      });
  };

  ProGallery.prototype.setCaptionOnHoverImage = function () {
    //check for all images
    var hoverImgSrc = this.$container.data('image-on-hover'),
      self = this;

    if (hoverImgSrc && hoverImgSrc.length) {
      this.$elements.each(function (index, figure) {
        var $figure = $(figure),
          $image = $figure.find('img');
        if ($image.length) {
          self.addCaptionOnHoverImageEvent($figure, $image, hoverImgSrc);
        }
      });
    } else if (this.$container.data('thumb-overlay-personal') == '1') {
      // check for one image
      this.$elements.each(function (index, figure) {
        var $figure = $(figure),
          $image = $figure.find('img[data-personal-hover-on-image]');
        if ($image.length) {
          hoverImgSrc = $image.data('personal-hover-on-image');
          self.addCaptionOnHoverImageEvent($figure, $image, hoverImgSrc);
        }
      });
    }
  };

  ProGallery.prototype.selectCategoryByUrlHash = function (currGallery) {
    if (currGallery && currGallery.$container && currGallery.$container.attr && currGallery.$container.attr('id') && window.location.hash) {
      var galleryId = currGallery.$container.attr('data-gg-id'),
        openCategoryByLinkRegexp = new RegExp('^#gg-categ-' + galleryId + '-([- _\\|"@\\+!=\\w\\d]*)$', 'gi'),
        categoryMatches = openCategoryByLinkRegexp.exec(window.location.hash.replace(new RegExp('__', 'g'), ' '));

      var catMatch = categoryMatches && categoryMatches.length && categoryMatches[1],
        catNames = catMatch ? null : window.location.hash.replace(new RegExp('__', 'g'), ' ').split('-');

      if (catMatch || catNames) {
        var catName = catMatch ? categoryMatches[1] : catNames.slice(-1)[0];
        currGallery.$navigation.find('a[data-tag="' + catName + '"]').trigger('click');
      } else {
        this.selectFirstCategory();
      }
    } else {
      this.selectFirstCategory();
    }
  };

  ProGallery.prototype.selectFirstCategory = function () {
    var firstTag = this.$navigation.find('a').first().data('tag');
    this.$navigation.find('a[data-tag="' + firstTag + '"]').trigger('click');
  };

  ProGallery.prototype.initMosaicGalleryType = function () {
    // check mosaic
    if (this.$container.data('gridType') == 4) {
      // Event handler for "last image Click
      var $photoRemainDiv = $('.gg-mosaic-photo-remain'),
        $galleryCaptFigure = $('.gg-mosaic-photo-remain').closest('.grid-gallery-caption'),
        self2 = this,
        useMosaic2Layout = this.$container.attr('data-useMosaic2Layout') == 1;
      //
      if (useMosaic2Layout) {
        var wall = new Freewall('#' + self2.$container.attr('id') + ' .grid-gallery-photos');

        function mosaicGalleryRefresh(wallScript) {
          setTimeout(function () {
            wallScript.fitWidth();
            // refresh some incorrect images
            setTimeout(function () {
              wallScript.fitWidth();
            }, 500);
          }, 5);
        }
        // first size save
        self2.$container.attr('data-mosaic2wnd-size', this.$container.width());

        function render2MosaicLayout() {
          var cntColumns = parseInt(self2.$container.attr('data-useMosaicCountColumns'));
          if (isNaN(cntColumns) || cntColumns < 3) {
            cntColumns = 4;
          }
          if (cntColumns > 20) {
            cntColumns = 20;
          }

          var imgMaxWidth = Math.round(self2.$container.width() / cntColumns),
            $imageContainer = self2.$container.find('.grid-gallery-photos'),
            $imageFigures = $imageContainer.find('.grid-gallery-caption'),
            notBigImage = Math.round($imageFigures.length / 3),
            imageDistance = parseInt(self2.$container.attr('data-offset')),
            sizesArr = [imgMaxWidth],
            maxColumnsForImg = Math.round(cntColumns * (cntColumns >= 8 ? 0.5 : 0.7));
          for (var i = maxColumnsForImg; i > 1; i--) {
            sizesArr.push(i * imgMaxWidth);
          }

          $imageFigures.each(function (ind, item) {
            var $currWrapper = $(this),
              $wrapperParent = $currWrapper.parent(),
              imgHeight = 0,
              imgWidth = 0,
              $image = $currWrapper.find('.ggImg'),
              newIndex = Math.abs(Math.round(Math.random() * maxColumnsForImg - 0.5)),
              imgNewWidth = sizesArr[notBigImage < ind || newIndex != 1 ? newIndex : sizesArr.length - 1];

            if ($currWrapper.css('block') == 'none') {
              mgNewWidth = 0;
              $wrapperParent.css({ height: '0', width: '0' });
            } else {
              $wrapperParent.css({ height: '', width: '' });
              if ($image.length) {
                imgHeight = parseInt($image[0].naturalHeight);
                imgWidth = parseInt($image[0].naturalWidth);
                if (imgHeight && imgWidth && !isNaN(imgHeight) && !isNaN(imgWidth)) {
                  if (imgWidth > imgHeight) {
                    if (imgHeight * 2 < imgWidth) {
                      // set max mask width for image
                      imgNewWidth = sizesArr[1];
                    }
                  } else {
                    // fix automaticaly set Widest mask, for Portrait images
                    if (newIndex == 1) {
                      imgNewWidth = sizesArr[0];
                    }
                  }
                }
              }
            }
            if ($wrapperParent.hasClass('gg-link')) {
              $wrapperParent.css({ width: imgNewWidth + 'px' });
              $currWrapper.css({ width: '100%' });
            } else {
              $currWrapper.css({ width: imgNewWidth + 'px' });
            }
          });
          if (isNaN(imageDistance)) {
            imageDistance = 0;
          }

          wall.reset({
            selector: '.gg-link',
            animate: true,
            cellW: imgMaxWidth,
            cellH: 'auto',
            gutterX: imageDistance,
            gutterY: imageDistance,
            onResize: function () {
              var prevWndSize = self2.$container.attr('data-mosaic2wnd-size'),
                containerWidth = self2.$container.width();
              if (prevWndSize != containerWidth) {
                render2MosaicLayout();
              } else {
                mosaicGalleryRefresh(wall);
              }
              self2.$container.attr('data-mosaic2wnd-size', containerWidth);
            },
          });
          mosaicGalleryRefresh(wall);
        }

        render2MosaicLayout();
      } else {
        self2.calcImgHeightInMosaicGallery();
        // save figcaption effect
        if (self2.$container.data('show-mosaic-all-img') != '1') {
          if ($galleryCaptFigure.length) {
            $galleryCaptFigure.attr('data-grid-gallery-type-original', $galleryCaptFigure.attr('data-grid-gallery-type'));
            //$galleryCaptFigure.attr('data-grid-gallery-type', 'none');
          }
        }
        // init once
        if (!this.isMosaicInit) {
          $(document).on('ggMosaicResizedEvent', function () {
            self2.initMosaicGalleryType();
          });
          this.isMosaicInit = true;
        }

        $photoRemainDiv.off('click').on('click', function (event) {
          // remove Remain count div
          if (self2.$container.data('show-hide-mosaic-img-by-click') == '1') {
            event.preventDefault();
            event.stopPropagation();

            // restore figcaption effect for "remain photo"
            if ($galleryCaptFigure.length) {
              $galleryCaptFigure.attr('data-grid-gallery-type', $galleryCaptFigure.attr('data-grid-gallery-type-original'));
              // show caption
              var $figcaption = $galleryCaptFigure.find('figcaption');
              if ($figcaption.length) {
                $figcaption.removeClass('sggDisplNone');
              }
            }
            // show other rows
            self2.$container.find('.gg-mosaic-wrapper .gg-mw-row').removeClass('sggDisplNone');
            // remove Remain div
            $photoRemainDiv.remove();
            self2.calcImgHeightInMosaicGallery();
            self2.initPopup();
            self2.initVideoPopup();
          }
        });
      }
    }
  };

  ProGallery.prototype.calcImgHeightInMosaicGallery = function (recalc) {
    var $firstRows = this.$container.find('.gg-mw-row.gg-mw-first-row'),
      $secondRows = this.$container.find('.gg-mw-row.gg-mw-second-row'),
      imageMargin = parseInt(this.$container.data('offset')),
      $firstFigure = this.$container.find('.gg-mw-column.gg-mw-column-1:first figure'),
      self2 = this;
    if (isNaN(imageMargin)) {
      imageMargin = 0;
    }

    $firstRows.each(function (ind, elem) {
      var $currRow = $(elem),
        $figure2 = $currRow.find('.gg-mw-column-1 figure'),
        $figure3 = $currRow.find('.gg-mw-column-2 figure'),
        $img2 = $figure2.find('img'),
        $img3 = $figure3.find('img'),
        height2 = self2._parent('getRealImgSizeInBrowser', [$img2, 'h']),
        height3 = self2._parent('getRealImgSizeInBrowser', [$img3, 'h']),
        correctHeight2 = height2,
        correctHeight3 = height3;
      // if item visible height more than 0
      if ($currRow.find('figure.initialized').length == 0) {
        if (height2 || height3) {
          // fix height for rows
          if ($img2.length && $img3.length && !isNaN(height2) && !isNaN(height3)) {
            if (height2 && height3) {
              if (height2 > height3) {
                correctHeight2 = height3;
              } else {
                correctHeight3 = height2;
              }
            }
          }
          // special prepare for "3d-cube" effect
          if ($firstFigure.data('grid-gallery-type') == '3d-cube') {
            self2._parent('changeImageHeightFor3dCubeEffect', { figure: $figure2, width: null, height: correctHeight2 });
            self2._parent('changeImageHeightFor3dCubeEffect', { figure: $figure3, width: null, height: correctHeight3 });
          } else {
            if ($img2.length && $img3.length && !isNaN(correctHeight2) && !isNaN(correctHeight3)) {
              $img2.parent().css('height', correctHeight2 + 'px');
              $img3.parent().css('height', correctHeight3 + 'px');
            }
          }
        }
        $currRow.css('height', height2 || height3 ? '' : '0');
      }
    });
    var recalc = typeof recalc == 'undefined' ? false : recalc,
      needRecalc = false,
      cntSecondRows = $secondRows.length,
      curRow = 0;

    $secondRows.each(function (ind, elem) {
      var $currElem = $(elem),
        $figure1 = $currElem.find('.gg-mt-column1 figure'),
        $figures = $currElem.find('.gg-mt-row2-column figure'),
        $img1 = $figure1.find('img'),
        $images = $figures.find('img'),
        height1 = self2._parent('getRealImgSizeInBrowser', [$img1, 'h']),
        correctHeight1 = height1;
      curRow++;
      if ($images.length && $currElem.find('figure.initialized').length == 0) {
        var cntImages = $images.length,
          heights = [],
          groups = {},
          groupMin = 0;
        $images.each(function (i) {
          var $img = $(this),
            h = self2._parent('getRealImgSizeInBrowser', [$img, 'h']),
            g = $img.closest('td').attr('data-group');
          if (typeof g == 'undefined') {
            g = 1;
          }
          heights.push(h);
          if (g in groups) {
            groups[g]['imgs'].push(i);
            if (groups[g]['min'] == 0 || groups[g]['min'] > h) groups[g]['min'] = h;
          } else {
            groups[g] = { min: h, imgs: [i] };
          }
          if (groupMin == 0 || (groups[g]['min'] > 0 && groupMin > groups[g]['min'])) groupMin = groups[g]['min'];
        });
        var sumHeight = 2 * imageMargin;
        for (var g in groups) {
          if (groups[g]['min'] == 0) groups[g]['min'] = groupMin;
          $.each(groups[g]['imgs'], function (ind, i) {
            heights[i] = groups[g]['min'];
          });
        }
        var sumHeight = 2 * imageMargin;
        for (var g in groups) {
          $.each(groups[g]['imgs'], function (ind, i) {
            heights[i] = groups[g]['min'];
          });
          sumHeight += groups[g]['min'];
        }
        if (height1 > sumHeight) {
          if (Math.round(height1 / sumHeight) >= 2 && !recalc) {
            $img1.closest('tr').find('td.gg-mt-column1').css('width', '50%');
            needRecalc = true;
          } else if (curRow < cntSecondRows && sumHeight > 2 * imageMargin) {
            correctHeight1 = sumHeight;
          }
        } else if (correctHeight1 < sumHeight && correctHeight1 > 0) {
          var delta = ((sumHeight - correctHeight1) * 100) / (sumHeight - 2 * imageMargin);
          for (var g in groups) {
            var cut = groups[g]['min'] - (delta * groups[g]['min']) / 100;
            $.each(groups[g]['imgs'], function (ind, i) {
              heights[i] = cut;
            });
          }
        }
        // special prepare for "3d-cube" effect
        if ($firstFigure.data('grid-gallery-type') == '3d-cube') {
          self2._parent('changeImageHeightFor3dCubeEffect', { figure: $figure1, width: null, height: correctHeight1 });
          $.each(heights, function (i, h) {
            self2._parent('changeImageHeightFor3dCubeEffect', { figure: $images.eq(i).closest('figure'), width: null, height: h });
          });
        } else {
          $img1.parent().css('height', correctHeight1 + 'px');
          $.each(heights, function (i, h) {
            $images
              .eq(i)
              .parent()
              .css('height', h + 'px');
          });
        }
      }
    });
    if (!recalc && needRecalc) self2.calcImgHeightInMosaicGallery(true);
    else {
      $.each(self2.$elements, function (index, el) {
        var $el = $(el);
        if ($el.closest('table').find('img.ggLazyImg').length == 0) {
          $el.find('img').removeClass('ggNotInitImg');
          self2.polaroidCaptionCalculate($el);
        }
      });
    }
  };

  ProGallery.prototype.initPopupFunc = function () {
    var popupType = this.$container.data('popup-type');

    if (popupType == 'pretty-photo') {
      window.prettyPhotoShowContent = function (changeWidthAndHeight, currImgRotateVal, $ppPopupImage, $ppImageWrapper, pp_dimensions) {
        if (!changeWidthAndHeight) {
          // simple
          $ppImageWrapper.css({
            width: 'auto',
            height: 'auto',
          });
        } else {
          // show with ROTATE
          $ppImageWrapper.css({
            width: pp_dimensions['width'],
            height: pp_dimensions['height'],
          });
          // calc translate value
          var translateVal = (pp_dimensions['height'] - pp_dimensions['width']) / 2,
            maxWidthVal = pp_dimensions['width'],
            rotateVal = 90 * currImgRotateVal;

          switch (currImgRotateVal) {
            case 3:
              translateVal *= -1;
              maxWidthVal = pp_dimensions['height'];
              break;
            case 2:
              translateVal = 0;
              break;
            case 1:
              maxWidthVal = pp_dimensions['height'];
              break;
            case 0:
            default:
              translateVal = 0;
              break;
          }

          if (!isNaN(rotateVal)) {
            $ppPopupImage.css({
              width: 'auto',
              height: 'auto',
              'max-width': maxWidthVal,
              // some like "transform: rotateZ(90deg) translate(90px, 90px);"
              transform: 'rotateZ(' + rotateVal + 'deg) translate(' + translateVal + 'px, ' + translateVal + 'px)',
            });
          }
        }
        $ppPopupImage.show();
      };

      window.prettyPhotoRotateCalcs = function (event, handlerFunc, needToIncrease) {
        if (event && event.preventDefault) {
          event.preventDefault();
        }
        // 0:   0 degree (or 360 degree)
        // 1:  90 degree
        // 2: 180 degree
        // 3: 270 degree
        var $imgForRotate = $('#fullResImage'),
          currImgRotateVal = parseInt($imgForRotate.attr('data-rotate-angl')),
          toFitWidth,
          toFitHeigth;
        $imgForRotate.hide();
        if (!$imgForRotate.length) {
          return;
        }
        if (currImgRotateVal < 0 || currImgRotateVal > 3 || isNaN(currImgRotateVal)) {
          currImgRotateVal = 0;
        }
        if (needToIncrease) {
          currImgRotateVal++;
          $imgForRotate.attr('data-rotate-angl', currImgRotateVal);
        }

        if ([1, 3].indexOf(currImgRotateVal) != -1) {
          toFitHeigth = $imgForRotate[0].naturalWidth;
          toFitWidth = $imgForRotate[0].naturalHeight;
        } else {
          toFitHeigth = $imgForRotate[0].naturalHeight;
          toFitWidth = $imgForRotate[0].naturalWidth;
        }
        handlerFunc(toFitWidth, toFitHeigth, currImgRotateVal);

        return false;
      };
      window.prettyPhotoInitRotate = function (handlerFunc) {
        var $rotateBtn = $('.sggRotateBtn[data-popup-theme-id="6"]');
        $rotateBtn.on('click', function (event) {
          window.prettyPhotoRotateCalcs(event, handlerFunc, 1);
        });
      };

      window.prettyPhotoDetailLink = function (element) {
        var $popupDetailsLink = $('.sggTheme6PopupBtns .sggLinkBtn');
        $popupDetailsLink.hide();
        if (element.attr('data-link-url')) {
          $popupDetailsLink.attr('href', element.attr('data-link-url'));
          $popupDetailsLink.show();
        }
      };

      window.prettyPhotoAttributes = function (element, container) {
        var $popupAttributes = $('#ppCustomAttributes').empty(),
          order,
          attributes;

        try {
          order = JSON.parse(container.attr('data-attributes-order'));
          attributes = JSON.parse(element.attr('data-attributes'));
        } catch (err) {}
        if (!$.isArray(attributes)) {
          attributes = [];
        }

        if ($.isArray(order)) {
          $.each(order, function (index, attrName) {
            $popupAttributes.append('<span class="pp_attribute_name">' + attrName + ':</span>');

            for (var i = 0; i < attributes.length; i++) {
              if ('name' in attributes[i] && 'value' in attributes[i] && attributes[i]['name'] == attrName) {
                $popupAttributes.append('<span class="pp_attribute_value">' + attributes[i]['value'] + '</span>');
                break;
              }
            }
            $popupAttributes.append('</br>');
          });
        }
        if (container.attr('data-show-buttonlink-in-popup') == 1) {
          var title = element.attr('data-buttonlink-title'),
            url = element.attr('data-buttonlink-url');
          if (typeof title == 'undefined' || title == '') {
            title = container.attr('data-buttonlink-title');
          }
          if (typeof url == 'undefined' || url == '') {
            url = container.attr('data-buttonlink-url');
          }
          $('#ppAttributeButton').find('a').attr('href', url).html(title);
        }
      };

      window.prettyPhotoInitOnHoverThumb = function ($element, galleryId, runPopupResizeHandler) {
        var $gallery = $('.grid-gallery[data-gg-id="' + galleryId + '"]'),
          $ggImgItem = $element.find('img.ggImg'),
          galleryOnHoverImgSrc = $gallery.attr('data-image-on-hover'),
          personalOnHoverSrc = $ggImgItem.attr('data-personal-hover-on-image'),
          $imgInPopup = $('.sggT6OnHoverThumbImg'),
          $imgWrapper = $('.sggTheme6OnHoverThumbWr'),
          imgSrc = $element.attr('href');
        // if personal on hover exists, then get him
        if (personalOnHoverSrc) {
          galleryOnHoverImgSrc = personalOnHoverSrc;
        }
        // reset values
        $imgInPopup.attr('src', galleryOnHoverImgSrc);
        $imgWrapper.attr('data-show-on-hover', 1);

        if ($element.length && $gallery.length) {
          if (!galleryOnHoverImgSrc) {
            $imgWrapper.hide();
            $imgInPopup.attr('src', '');
          } else {
            $imgWrapper.show();

            // init changing
            $imgWrapper.off('click').on('click', function (event) {
              var $currWrapper = $(this),
                $imgForPreview = $('#fullResImage');
              // if user click on Preview
              if ($currWrapper.attr('data-show-on-hover')) {
                $imgInPopup.attr('src', imgSrc);
                $currWrapper.attr('data-show-on-hover', '');
                $imgForPreview.attr('src', galleryOnHoverImgSrc);
              } else {
                $imgInPopup.attr('src', galleryOnHoverImgSrc);
                $currWrapper.attr('data-show-on-hover', 1);
                $imgForPreview.attr('src', imgSrc);
              }
              window.prettyPhotoRotateCalcs(null, runPopupResizeHandler, 0);
            });
          }
          // fix popup height bar, when change "item" in popup gallery preview
          // ...
        }
      };
    }
  };

  $(document).on('GalleryExtend', function (event, Gallery) {
    $.extend(Gallery, new ProGallery());
  });
})(jQuery);

function htmlDecode(input) {
  var e = document.createElement('div');
  e.innerHTML = input;
  return e.childNodes[0].nodeValue;
}
function arraysEqual(a, b) {
  if (a === b) return true;
  if (a == null || b == null) return false;
  if (a.length != b.length) return false;

  // If you don't care about the order of the elements inside
  // the array, you should sort both arrays here.
  // Please note that calling sort on an array will modify that array.
  // you might want to clone your array first.

  for (var i = 0; i < a.length; ++i) {
    if (a[i] !== b[i]) return false;
  }
  return true;
}
