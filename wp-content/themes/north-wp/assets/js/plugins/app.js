(function($, window, _) {
  'use strict';

  var $doc = $(document),
    win = $(window),
    body = $('body'),
    header = $('.header'),
    wrapper = $('#wrapper'),
    cc = $('.click-capture'),
    adminbar = $('#wpadminbar'),
    thb_ease = new BezierEasing(0.25, 0.46, 0.45, 0.94),
    thb_md = new MobileDetect(window.navigator.userAgent);

  gsap.config({
    nullTargetWarn: false
  });

  var SITE = SITE || {};

  SITE = {
    init: function() {
      var self = this,
        obj;

      win.on('resize.thb-page-padding', _.debounce(function() {
        if ($('.header:not(.fixed)').outerHeight() > 0) {
          $('.page-padding').css({
            'paddingTop': function() {
              return $('.header:not(.fixed)').outerHeight() + ($('.thb-global-notification').outerHeight() || 0) + 'px';
            }
          });
        }
      }, 10)).trigger('resize.thb-page-padding');

      $('.header').imagesLoaded(function() {
        win.trigger('resize.thb-page-padding');
      });

      for (obj in self) {
        if (self.hasOwnProperty(obj)) {
          var _method = self[obj];
          if (_method.selector !== undefined && _method.init !== undefined) {
            if ($(_method.selector).length > 0) {
              _method.init();
            }
          }
        }
      }
    },
    headRoom: {
      selector: '.header',
      init: function() {
        var base = this,
          container = $(base.selector);

        win.on('scroll.thb-fixed-header', function() {
          base.scroll(container);
        }).trigger('scroll.thb-fixed-header');
      },
      scroll: function(container) {
        var animationOffset = $('.thb-global-notification').length && $('.thb-global-notification').is(':visible') ? $('.thb-global-notification').outerHeight() : 0,
          wOffset = win.scrollTop(),
          stick = 'fixed';
        if ($('.subheader').length) {
          animationOffset = $('.subheader').length && $('.subheader').is(':visible') ? $('.subheader').outerHeight() : 0;
        }
        if (wOffset > animationOffset) {
          if (!container.hasClass(stick)) {
            setTimeout(function() {
              container.addClass(stick);
            }, 10);
          }
        } else if ((wOffset < animationOffset && (wOffset > 0))) {
          if (container.hasClass(stick)) {
            container.removeClass(stick);
          }
        } else {
          container.removeClass(stick);
        }
      }

    },
    subheader: {
      selector: '.subheader',
      init: function() {
        var base = this,
          container = $(base.selector);

        win.on('scroll.thb-subheader', function() {
          var negative_offset = win.scrollTop() < container.outerHeight() ? win.scrollTop() : container.outerHeight();
          gsap.set(header, {
            marginTop: -1 * negative_offset,
            immediateRender: true
          });
        }).trigger('scroll.thb-subheader');
      }
    },
    globalNotification: {
      selector: '.thb-global-notification',
      init: function() {
        var base = this,
          container = $(base.selector),
          close = $('.thb-notification-close', container);

        win.on('scroll.thb-global-notification', function() {
          if (!container.is(':visible') || $('.subheader').is(':visible')) {
            return;
          }
          var negative_offset = win.scrollTop() < container.outerHeight() ? win.scrollTop() : container.outerHeight();
          gsap.set(header, {
            marginTop: -1 * negative_offset,
            immediateRender: true
          });
        }).trigger('scroll.thb-global-notification');
        close.on('click', function() {
          container.slideUp('400', function() {
            container.remove();
            Cookies.set('thb-global-notification', '1', {
              expires: 1
            });
          });
        });
      }
    },
    responsiveNav: {
      selector: '#wrapper',
      init: function() {
        var base = this,
          container = $(base.selector),
          menu = $('#mobile-menu'),
          filters = $('#side-filters'),
          cart = $('#side-cart'),
          quick_shop = $('#quick-shop'),
          cc_close = $('.thb-close'),
          children = menu.find('.mobile-menu>li'),
          menu_items = menu.find('h6, .searchform, .mobile-menu>li,.mobile-secondary-menu>li, .social-links, .thb-close'),
          span = menu.find('.mobile-menu li:has(".sub-menu")>a span'),
          tlMainNav = gsap.timeline({
            paused: true,
            onStart: function() {
              container.addClass('open-cc');
            },
            onReverseComplete: function() {
							gsap.set(menu, { clearProps: 'transform' });
              container.removeClass('open-cc');
            }
          }),
          tlCartNav = gsap.timeline({
            paused: true,
            onStart: function() {
              container.addClass('open-cc');
            },
            onReverseComplete: function() {
							gsap.set(cart, { clearProps: 'transform' });
              container.removeClass('open-cc');
            }
          }),
          tlQuickNav = gsap.timeline({
            paused: true,
            onStart: function() {
              container.addClass('open-cc');
            },
            onReverseComplete: function() {
							gsap.set(quick_shop, { clearProps: 'transform' });
              container.removeClass('open-cc');
            }
          }),
          tlFilterNav = gsap.timeline({
            paused: true,
            onStart: function() {
              container.addClass('open-cc');
            },
            onReverseComplete: function() {
							gsap.set(filters, { clearProps: 'transform' });
              container.removeClass('open-cc');
            }
          });

        tlMainNav
					.to( menu, {
            x: '0%',
						duration: 0.25,
          })
          .fromTo(menu_items, {
            x: "-30",
            opacity: 0,
            ease: thb_ease
          }, {
            duration: 0.5,
            delay: 0.25,
            x: "0",
            opacity: 1,
            stagger: 0.05
          }, "-=0.1");

        tlCartNav
					.to( cart, {
						x: '0%',
						duration: 0.25,
					})
          .from($('#side-cart').find('.item'), {
            duration: 0.25,
            delay: 0.25,
            x: "30",
            opacity: 0,
            ease: thb_ease,
            stagger: 0.05
          }, "-=0.1");

        tlQuickNav
					.to( quick_shop, {
						x: '0%',
						duration: 0.25,
					})
          .from(quick_shop.find('.item'), {
            duration: 0.25,
            delay: 0.25,
            x: "30",
            opacity: 0,
            ease: thb_ease,
            stagger: 0.05
          }, "-=0.1");

        tlFilterNav
					.to( filters, {
						x: '0%',
						duration: 0.25,
					})
          .from(filters.find('.widget'), {
            duration: 0.25,
            delay: 0.25,
            x: "-30",
            opacity: 0,
            ease: thb_ease,
            stagger: 0.05
          }, "-=0.1");

        $('.header').on('click', '.mobile-toggle-holder', function() {
          tlMainNav.play();
          return false;
        });
        $('#wrapper').on('click', '.quick-shop', function() {
          tlQuickNav.play();
          return false;
        });
        $('.header').on('click', '#quick_cart', function() {
          if (themeajax.settings.is_cart || themeajax.settings.is_checkout || themeajax.settings.header_quick_cart === 'off') {
            return true;
          } else {
            tlCartNav.play();
            SITE.customScroll.init();
            return false;
          }
        });
        container.on('click', '#thb-shop-filters', function() {
          tlFilterNav.play();
          return false;
        });

        $doc.keyup(function(e) {
          if (e.keyCode === 27) {
            tlMainNav.reverse();
            tlCartNav.reverse();
            tlQuickNav.reverse();
            tlFilterNav.reverse();
          }
        });

        cc.add(cc_close).on('click', function() {
          tlMainNav.reverse();
          tlCartNav.reverse();
          tlQuickNav.reverse();
          tlFilterNav.reverse();
          return false;
        });
        body.on('wc_fragments_refreshed added_to_cart', function() {
          $('.thb-close').on('click', function() {
            tlCartNav.reverse();
            tlFilterNav.reverse();
            return false;
          });
        });
        body.on('updated_wc_div checkout_error', function() {
          if (body.hasClass('woocommerce-checkout') || body.hasClass('woocommerce-cart')) {
            setTimeout(function() {
              $('html, body').stop();
            }, 10);
          }
        });
        span.on('click', function(e) {
          var that = $(this),
            parent = that.parents('a'),
            menu = parent.next('.sub-menu');

          if (parent.hasClass('active')) {
            menu.slideUp('200', function() {
              parent.removeClass('active');
            });
          } else {
            menu.slideDown('200', function() {
              parent.addClass('active');
            });
          }
          e.stopPropagation();
          e.preventDefault();
        });

      }
    },
    updateCart: {
      selector: '#quick_cart',
      init: function() {
        var base = this,
          container = $(base.selector);
        body.on('wc_fragments_refreshed added_to_cart', SITE.updateCart.update_cart_dropdown);
      },
      update_cart_dropdown: function(event) {
        if (event.type === 'added_to_cart') {
          $('#quick_cart').trigger('click');
        }
      }
    },
    fullMenu: {
      selector: '.thb-full-menu, .secondary-menu',
      init: function() {
        var base = this,
          container = $(base.selector),
          li_org = container.find('a'),
          children = container.find('li.menu-item-has-children:not(.menu-item-mega-parent)'),
          mega_menu = container.find('li.menu-item-has-children.menu-item-mega-parent');

        children.each(function() {
          var _this = $(this),
            menu = _this.find('>.sub-menu'),
            li = menu.find('>li>a'),
            tl = gsap.timeline({
              paused: true
            });

          tl
            .to(menu, {
              duration: 0.5,
              autoAlpha: 1
            }, "start")
            .to(li, {
              duration: 0.1,
              opacity: 1,
              x: 0,
              stagger: 0.03
            }, "start");

          _this.hoverIntent({
            sensitivity: 3,
            interval: 20,
            timeout: 70,
            over: function() {
              _this.addClass('sfHover');
              tl.timeScale(1).restart();
            },
            out: function() {
              _this.removeClass('sfHover');
              tl.timeScale(1.5).reverse();
            }
          });
        });
        mega_menu.each(function() {
          var _this = $(this),
            menu = _this.find('>.sub-menu'),
            li = menu.find('>li>a, .menu-item-mega-link>a'),
            tl = gsap.timeline({
              paused: true
            });

          tl
            .fromTo(menu, {
              autoAlpha: 0,
              display: 'none'
            }, {
              duration: 0.5,
              autoAlpha: 1,
              display: 'flex'
            }, "start")
            .to(li, {
              duration: 0.1,
              opacity: 1,
              x: 0,
              stagger: 0.02
            }, "start");

          _this.hoverIntent(
            function() {
              _this.addClass('sfHover');
              tl.timeScale(1).restart();
            },
            function() {
              _this.removeClass('sfHover');
              tl.timeScale(1.5).reverse();
            }
          );
        });
        li_org.on('click', function(e) {
          var _this = $(this),
            url = _this.attr('href'),
            ah = $('#wpadminbar').outerHeight() || 0,
            fh = $('.header').outerHeight(),
            hash = url.indexOf("#") !== -1 ? url.substring(url.indexOf("#") + 1) : '',
            pos = hash ? $('#' + hash).offset().top - ah - fh : 0;
          if (hash) {
            pos = (hash === 'footer') ? "max" : pos;
            gsap.to(win, {
              duration: 1,
              scrollTo: {
                y: pos,
                autoKill: false
              }
            });
            return false;
          } else {
            return true;
          }
        });
      }
    },
    carousel: {
      selector: '.slick',
      init: function(el) {
        var base = this,
          container = el ? el : $(base.selector);

        container.each(function() {
          var that = $(this),
            columns = that.data('columns'),
            navigation = (that.data('navigation') === true ? true : false),
            autoplay = (that.data('autoplay') === false ? false : true),
            pagination = (that.data('pagination') === true ? true : false),
            center = (that.data('center') ? that.data('center') : false),
            disablepadding = (that.data('disablepadding') ? that.data('disablepadding') : false),
            vertical = (that.data('vertical') === true ? true : false),
            asNavFor = that.data('asnavfor'),
            rtl = body.hasClass('rtl');

          var args = {
            dots: pagination,
            arrows: navigation,
            infinite: false,
            speed: 1000,
            rows: 0,
            centerMode: false,
            slidesToShow: columns,
            slidesToScroll: 1,
            slide: ':not(style):not(.label-wrap):not(.thb-product-icon)',
            rtl: rtl,
            autoplay: autoplay,
            centerPadding: (disablepadding ? 0 : '50px'),
            autoplaySpeed: 4000,
            pauseOnHover: true,
            vertical: vertical,
            verticalSwiping: vertical,
            accessibility: false,
            focusOnSelect: false,
            prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-prev" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM48 38H2V2h46V38z"/><path d="M15.3 19.2c0 0 0 0-0.1 0.1 0 0 0 0 0 0 0 0 0 0 0 0 -0.1 0.2-0.2 0.4-0.2 0.7 0 0.2 0.1 0.5 0.2 0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0.1l3.8 3.9c0.4 0.4 1.1 0.4 1.5 0 0.4-0.4 0.4-1.1 0-1.6l-2-2h15.3c0.6 0 1.1-0.5 1.1-1.1 0-0.6-0.5-1.1-1.1-1.1H18.6l2-2c0.4-0.4 0.4-1.1 0-1.6 -0.4-0.4-1.1-0.4-1.5 0l-3.8 3.9C15.3 19.2 15.3 19.2 15.3 19.2z"/></svg>',
            nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-next" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM2 2h46v36H2V2z"/><path d="M34.7 19.2L30.9 15.3c-0.4-0.4-1.1-0.4-1.5 0 -0.4 0.4-0.4 1.1 0 1.6l2 2H16.1c-0.6 0-1.1 0.5-1.1 1.1 0 0.6 0.5 1.1 1.1 1.1h15.3l-2 2c-0.4 0.4-0.4 1.1 0 1.6 0.4 0.4 1.1 0.4 1.5 0l3.8-3.9c0 0 0 0 0.1-0.1 0 0 0 0 0 0 0 0 0 0 0 0 0.1-0.2 0.2-0.4 0.2-0.7 0-0.2-0.1-0.5-0.2-0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0-0.1-0.1C34.7 19.2 34.7 19.2 34.7 19.2z"/></svg>',
            responsive: [{
                breakpoint: 1441,
                settings: {
                  slidesToShow: (columns < 6 ? columns : (vertical ? columns - 1 : 6)),
                  centerPadding: (disablepadding ? 0 : '40px')
                }
              },
              {
                breakpoint: 1201,
                settings: {
                  slidesToShow: (columns < 4 ? columns : (vertical ? columns - 1 : 4)),
                  centerPadding: (disablepadding ? 0 : '40px')
                }
              },
              {
                breakpoint: 1025,
                settings: {
                  slidesToShow: (columns < 3 ? columns : (vertical ? columns - 1 : 3)),
                  centerPadding: (disablepadding ? 0 : '40px')
                }
              },
              {
                breakpoint: 641,
                settings: {
                  slidesToShow: 1,
                  centerPadding: (disablepadding ? 0 : '15px')
                }
              }
            ]
          };
          if (asNavFor && $(asNavFor).is(':visible')) {
            args.asNavFor = asNavFor;
          }
          if (that.data('fade')) {
            args.fade = true;
          }
          if (that.hasClass('product-images')) {
            args.infinite = false;
            args.speed = 500;
            // Zoom Support
            if (typeof wc_single_product_params !== 'undefined') {
              if (window.wc_single_product_params.zoom_enabled && $.fn.zoom) {
                that.on('afterChange', function(event, slick, currentSlide) {
                  var zoomTarget = slick.$slides.eq(currentSlide),
                    galleryWidth = zoomTarget.width(),
                    zoomEnabled = false,
                    image = zoomTarget.find('img');

                  if (image.data('large_image_width') > galleryWidth) {
                    zoomEnabled = true;
                  }
                  if (zoomEnabled) {
                    var zoom_options = $.extend({
                      touch: false
                    }, window.wc_single_product_params.zoom_options);

                    if ('ontouchstart' in window) {
                      zoom_options.touch = true;
                      zoom_options.on = 'click';
                    }

                    zoomTarget.trigger('zoom.destroy');
                    zoomTarget.zoom(zoom_options);
                    zoomTarget.trigger('focus mouseenter.zoom');
                  }

                });
              }
            }
          }
          if (that.hasClass('product-thumbnails')) {
            args.infinite = false;
            args.focusOnSelect = true;
            args.speed = 500;
            args.centerPadding = 0;
            args.slidesToShow = 4;
            args.slidesToShow = 4;
          }
          if (that.hasClass('product-thumbnails') && that.data('product-thumbnail-layout') === 'style1') {
            args.vertical = true;
            args.responsive[2].settings.vertical = false;
            args.responsive[2].settings.slidesToShow = 4;
            args.responsive[3].settings.vertical = false;
            args.responsive[3].settings.slidesToShow = 4;
          }
          if (that.hasClass('product-thumbnails') && that.data('product-thumbnail-layout') === 'style2') {
            args.responsive[2].settings.vertical = false;
            args.responsive[2].settings.slidesToShow = 5;
            args.responsive[3].settings.vertical = false;
            args.responsive[3].settings.slidesToShow = 5;
          }
          if (that.hasClass('testimonial-style3')) {
            args.customPaging = function(slider, i) {
              var portrait = $(slider.$slides[i]).find('.author_image'),
                title = $(slider.$slides[i]).find('.title').text(),
                portrait_html = portrait.length ? portrait.get(0).outerHTML : '';

              return '<a class="portrait_bullet" title="' + title + '">' + portrait_html + '</a>';
            };
          }
          that.slick(args);
        });
      }
    },
    masonry: {
      selector: '.masonry',
      init: function() {
        var base = this,
          container = $(base.selector);

        Outlayer.prototype._setContainerMeasure = function(measure, isWidth) {
          if (measure === undefined) {
            return;
          }
          var elemSize = this.size;
          // add padding and border width if border box
          if (elemSize.isBorderBox) {
            measure += isWidth ? elemSize.paddingLeft + elemSize.paddingRight +
              elemSize.borderLeftWidth + elemSize.borderRightWidth :
              elemSize.paddingBottom + elemSize.paddingTop +
              elemSize.borderTopWidth + elemSize.borderBottomWidth;
          }

          measure = Math.max(measure, 0);
          measure = Math.floor(measure);
          this.element.style[isWidth ? 'width' : 'height'] = measure + 'px';
        };
        container.each(function() {
          var _this = $(this),
            layoutMode = _this.data('layoutmode') ? _this.data('layoutmode') : 'masonry';

          _this.imagesLoaded(function() {
            _this.isotope({
              layoutMode: layoutMode,
              itemSelector: '.item',
              transitionDuration: '0.5s',
              stagger: 150,
              masonry: {
                columnWidth: '.item'
              },
              hiddenStyle: {
                opacity: 0,
                transform: 'translateY(30px)'
              },
              visibleStyle: {
                opacity: 1,
                transform: 'translateY(0px)'
              }
            });
          });
        });
      }
    },
    customScroll: {
      selector: '.custom_scroll, #side-cart .woocommerce-mini-cart',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          var that = $(this);
          that.perfectScrollbar({
            wheelPropagation: false,
            suppressScrollX: true
          });
        });

        win.on('resize.customscroll', function() {
          base.resize(container);
        });
      },
      resize: function(container) {
        container.perfectScrollbar('update');
      }
    },
    videoPlayButton: {
      selector: '.thb_video_play_button_enabled',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          var _this = $(this),
            button = _this.find('.thb_video_play'),
            icon = $('svg', button),
            instance = _this.data("vide"),
            video = instance.getVideoObject();


          button.on('click', function() {
            if (video) {
              if (video.paused) {
                video.play();
                icon.addClass('playing');
              } else {
                video.pause();
                icon.removeClass('playing');
              }
            }
            return false;
          });
        });
      }
    },
    quickShopCategories: {
      selector: '#thb-quick-shop-categories',
      init: function() {
        var base = this,
          container = $(base.selector),
          product_container = $('.product_container', '#quick-shop'),
          ul = product_container.find('.products');

        container.on('change', function() {
          var _this = $(this),
            security = _this.data('security');

          $.ajax(themeajax.url, {
            method: 'POST',
            data: {
              action: 'thb_quick_shop_ajax',
              term_slug: _this.val(),
              security: security
            },
            beforeSend: function() {
              product_container.addClass('thb-loading');
            },
            success: function(data) {
              product_container.removeClass('thb-loading');
              var d = $.parseHTML($.trim(data)),
                products = $(d).filter(function() {
                  return this.nodeType === 1;
                });

              gsap.set(products, {
                opacity: 0,
                y: 30
              });
              ul.html(products);

              gsap.to(products, {
                duration: 0.5,
                y: 0,
                opacity: 1,
                stagger: 0.25
              });
              product_container.perfectScrollbar('update');
            }
          });
        });
      }
    },
    productQuickView: {
      selector: '.thb-quick-view',
      init: function() {
        var base = this,
          container = $(base.selector),
          thb_qw_loading = false,
          ajax_url = window.wc_add_to_cart_params ? wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'thb_product_quickview') : themeajax.url,
          quickview_wrapper = $('.thb-quickview-wrapper', body),
          close = $('.thb-close', quickview_wrapper),
          quickview_tl = gsap.timeline({
            paused: true,
            onStart: function() {
              wrapper.addClass('open-cc');
            },
            onComplete: function() {

            },
            onReverseComplete: function() {
              wrapper.removeClass('open-cc');
              gsap.set(quickview_wrapper, {
                clearProps: "transform"
              });
            }
          });

        quickview_tl
          .set(quickview_wrapper, {
            display: 'flex'
          }, "start")
          .to(cc, {
            duration: 0.3,
            autoAlpha: 1
          }, "start")
          .to(quickview_wrapper, {
            duration: 0.3,
            autoAlpha: 1
          }, "start");

        cc.add(close).on('click', function() {
          quickview_tl.reverse();
        });
        $doc.keyup(function(e) {
          if (e.keyCode === 27) {
            quickview_tl.reverse();
          }
        });
        container.on('click', function(e) {
          var _this = $(this),
            id = _this.data('id');

          if (thb_qw_loading === false) {
            $.ajax(ajax_url, {
              method: 'POST',
              headers: {
                "cache-control": "no-cache",
              },
              data: {
                product_id: id,
                action: 'thb_product_quickview',
                security: themeajax.nonce.product_quickview
              },
              beforeSend: function() {
                thb_qw_loading = true;
                quickview_wrapper.find('.thb-quickview-content').empty();
                _this.addClass('thb-loading');
              },
              success: function(response) {
                var parsed_data = response.data;
                quickview_wrapper.find('.thb-quickview-content').html(parsed_data);
                quickview_tl.add(
                  gsap.to($('.thb-quickview-close'), 0.3, {
                    duration: 0.3,
                    scale: 1
                  }), "start+=0.2"
                );
                if ($('.variations_form', quickview_wrapper)) {
                  $('.variations_form', quickview_wrapper).wc_variation_form();
                }

                SITE.quantity.initialize();
                SITE.variations.init();
                SITE.swatches.init();
                SITE.customScroll.init();
                SITE.carousel.init(quickview_wrapper.find('.carousel'));

                if (quickview_wrapper.find('.thb-product-video')) {
                  SITE.magnificVideo.init();
                }
                win.trigger('resize');
                quickview_tl.restart();
                thb_qw_loading = false;
                _this.removeClass('thb-loading');
              }
            });
          }
          return false;
        });
      }
    },
    wpml: {
      selector: '.thb-language-switcher-select',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.on('change', function() {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
            window.location = url; // redirect
          }
          return false;
        });
      }
    },
    wpmlCurrencyMobile: {
      selector: '.thb-currency-switcher-select',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.on('change', function() {
          var cur = $(this).val(); // get selected value
          if (cur && typeof wcml_load_currency !== "undefined") {
            wcml_load_currency(cur);
          }
          return false;
        });
      }
    },
    loginForm: {
      selector: '.thb-overflow-container',
      init: function() {
        var base = this,
          container = $(base.selector),
          ul = $('ul', container),
          links = $('a', ul);

        links.on('click', function() {
          var _this = $(this);
          if (!_this.hasClass('active')) {
            links.removeClass('active');
            _this.addClass('active');

            $('.thb-form-container', container).toggleClass('register-active');
          }
          return false;
        });
      }
    },
    shop: {
      selector: '.products .product',
      init: function() {
        var base = this,
          container = $(base.selector),
          text;

        $('body')
          .on('adding_to_cart', function(e, $button) {
            if (!$button) {
              return;
            }
            text = $button.text();
            $button.text(themeajax.l10n.adding_to_cart);

          })
          .on('added_to_cart', function(e, fragments, cart_hash, $button) {
            if ($button) {
              $button.text(text);
            }
          })
          .on('removed_from_cart', function(e, fragments, cart_hash, $button) {
            SITE.customScroll.init();
          });
      }
    },
    productAjaxAddtoCart: {
      selector: '.thb-single-product-ajax-on.single-product .product-type-variable form.cart, .thb-single-product-ajax-on.single-product .product-type-simple form.cart',
      init: function() {
        var base = this,
          container = $(base.selector),
          btn = $('.single_add_to_cart_button', container);

        if (typeof wc_add_to_cart_params !== 'undefined') {
          if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
            return;
          }
        }
        $doc.on('submit', 'body.single-product form.cart', function(e) {
          e.preventDefault();
          var _this = $(this),
            btn_text = btn.text();

          if (btn.is('.disabled') || btn.is('.wc-variation-selection-needed')) {
            return;
          }

          var data = {
            product_id: _this.find("[name*='add-to-cart']").val(),
            product_variation_data: _this.serialize()
          };

          $.ajax({
            method: 'POST',
            data: data.product_variation_data,
            dataType: 'html',
            url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add-to-cart=' + data.product_id + '&thb-ajax-add-to-cart=1'),
            cache: false,
            headers: {
              'cache-control': 'no-cache'
            },
            beforeSend: function() {
              body.trigger('adding_to_cart');
              btn.addClass('disabled').text(themeajax.l10n.adding_to_cart);
            },
            success: function(data) {
              var parsed_data = $.parseHTML(data);

              var thb_fragments = {
                '.float_count': $(parsed_data).find('.float_count').html(),
                '.thb_prod_ajax_to_cart_notices': $(parsed_data).find('.thb_prod_ajax_to_cart_notices').html(),
                '.widget_shopping_cart_content': $(parsed_data).find('.widget_shopping_cart_content').html()
              };

              $.each(thb_fragments, function(key, value) {
                $(key).html(value);
              });
              body.trigger('wc_fragments_refreshed');
              btn.removeClass('disabled').text(btn_text);
            },
            error: function(response) {
              body.trigger('wc_fragments_ajax_error');
              btn.removeClass('disabled').text(btn_text);
            }
          });
        });
      }
    },
    variations: {
      selector: 'form.variations_form',
      init: function() {
        var base = this,
          container = $(base.selector),
          slider = $('#product-images'),
          thumbnails = $('#product-thumbnails'),
          org_image = $('.first img', slider).attr('src'),
          org_thumb = $('.first img', thumbnails).attr('src'),
          price_container = $('p.price', '.product-information').eq(0),
          org_price = price_container.html();

        container.on("show_variation", function(e, variation) {
          if (variation.price_html) {
            price_container.html(variation.price_html);
          } else {
            price_container.html(org_price);
          }
          if (variation.hasOwnProperty("image") && variation.image.src) {
            $('.first img', slider).attr("src", variation.image.src).attr("srcset", "");
            $('.first img', thumbnails).attr("src", variation.image.thumb_src).attr("srcset", "");

            if (slider.hasClass('slick-initialized')) {
              slider.slick('slickGoTo', 0);
            }
          }
        }).on('reset_image', function() {
          price_container.html(org_price);
          $('.first img', slider).attr("src", org_image).attr("srcset", "");
          $('.first img', thumbnails).attr("src", org_thumb).attr("srcset", "");
        });
      }
    },
    swatches: {
      selector: '.thb-swatches',
      init: function() {
        var base = this,
          container = $(base.selector),
          form = $('form.variations_form');

        container.each(function() {
          var _this = $(this),
            attr = _this.data('attribute_name');

          if ($('.thb-product-detail').length) {
            _this.on('click', '.thb-swatch', function(e) {
              var swatch = $(this),
                value = swatch.data('value');

              if (swatch.hasClass('disabled')) {
                e.preventDefault();
                return false;
              }
              if (swatch.siblings('.thb-swatch').hasClass('selected')) {
                swatch.siblings('.thb-swatch').removeClass('selected');
              }

              $('select[name=' + attr + ']', form).val(value).change();
              swatch.addClass('selected');
              e.preventDefault();
            });
            form.on('update_variation_values.wc-variation-form', function() {
              $('.thb-swatch', _this).each(function() {
                var swatch_value = $(this).data('value');
                if (!$('select[name=' + attr + '] option[value=' + swatch_value + ']', form).length) {
                  $('.thb-swatch[data-value=' + swatch_value + ']').addClass('disabled');
                } else {
                  $('.thb-swatch[data-value=' + swatch_value + ']').removeClass('disabled');
                }
              });
            });

          } else if (_this.parents('.product').length) {
            var parent = _this.parents('.product'),
              org_image = parent.find('.wp-post-image').attr('src'),
              org_srcset = parent.find('.wp-post-image').attr('srcset');

            _this.on('mouseenter ontouchstart', '.thb-swatch', function(e) {
              var swatch = $(this),
                data = swatch.data('variation');

              if (data.image_src) {
                parent.find('.wp-post-image').attr('src', data.image_src);
                parent.find('.wp-post-image').attr('srcset', '');
              }
            }).on('mouseleave ontouchend', '.thb-swatch', function(e) {
              parent.find('.wp-post-image').attr('src', org_image);
              parent.find('.wp-post-image').attr('srcset', org_srcset);
            });
          }
        });
        form.on('reset_data', function() {
          container.find('.thb-swatch').removeClass('selected');
        });
      }
    },
    quantity: {
      selector: '.quantity:not(.hidden)',
      init: function() {
        var base = this,
          container = $(base.selector);

        base.initialize();
        body.on('updated_cart_totals', function() {
          base.initialize();
        });
      },
      initialize: function() {
        var qty = $('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)');

        qty.each(function() {
          var _this = $(this);

          _this.addClass('buttons_added').append('<div class="plus"></div>').prepend('<div class="minus"></div>').end().find('input[type="number"]').attr('type', 'text');

          $('.plus, .minus', _this).on('click', function() {
            // Get values
            var $qty = $(this).closest('.quantity').find('.qty'),
              currentVal = parseFloat($qty.val()),
              max = parseFloat($qty.attr('max')),
              min = parseFloat($qty.attr('min')),
              step = $qty.attr('step');

            // Format values
            if (!currentVal || currentVal === '' || currentVal === 'NaN') {
              currentVal = 0;
            }
            if (max === '' || max === 'NaN') {
              max = '';
            }
            if (min === '' || min === 'NaN') {
              min = 0;
            }
            if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') {
              step = 1;
            }

            // Change the value
            if ($(this).is('.plus')) {

              if (max && (max === currentVal || currentVal > max)) {
                $qty.val(max);
              } else {
                $qty.val(currentVal + parseFloat(step));
              }

            } else {

              if (min && (min === currentVal || currentVal < min)) {
                $qty.val(min);
              } else if (currentVal > 0) {
                $qty.val(currentVal - parseFloat(step));
              }

            }
            // Trigger change event
            $qty.trigger('change');
            return false;
          });
        });
      }
    },
    autoComplete: {
      selector: '#searchpopup',
      init: function() {
        var base = this,
          container = $(base.selector),
          field = $('.search-field', container),
          security = container.data('security');

        field.autocomplete({
          minChars: 3,
          appendTo: $('.thb-autocomplete-wrapper', container),
          containerClass: 'product_list_widget',
          triggerSelectOnValidInput: false,
          serviceUrl: themeajax.url + '?action=thb_ajax_search_products',
          tabDisabled: true,
          showNoSuggestionNotice: false,
          params: {
            security: security
          },
          onSearchStart: function() {
            $('.thb-search-btn', container).remove();
            $('.thb-autocomplete-wrapper', container).addClass('thb-loading');
          },
          formatResult: function(suggestion, currentValue) {
            return '<a href="' + suggestion.url + '">' + suggestion.thumbnail + '<span class="product-title">' + suggestion.value + '</span></a>' + suggestion.price;
          },
          onSelect: function(suggestion) {
            if (suggestion.id !== -1) {
              window.location.href = suggestion.url;
            }
          },
          onSearchComplete: function(query, suggestions) {
            $('.thb-autocomplete-wrapper', container).removeClass('thb-loading');

            if (suggestions.length) {
              var url = themeajax.settings.site_url + '?s=' + query + '&post_type=product';
              $('.product_list_widget', container).append($('<div class="thb-search-btn"><a href="' + url + '" class="btn alt small">' + themeajax.l10n.results_all + '</a></div>'));
            }
          }
        });

      }
    },
    retinaJS: {
      selector: 'img.retina_size:not(.retina_active)',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          $(this).attr('width', function() {
            var w = $(this).attr('width') / 2;
            return w;
          }).addClass('retina_active');
        });

      }
    },
    magnificVideo: {
      selector: '.mfp-video',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.magnificPopup({
          type: 'iframe',
          tLoading: themeajax.l10n.loading,
          closeBtnInside: false,
          closeMarkup: '<button title="%title%" class="mfp-close">' + themeajax.icons.close + '</button>',
          mainClass: 'mfp',
          removalDelay: 250,
          fixedContentPos: true
        });

      }
    },
    magnificImage: {
      selector: '[rel="magnific"]',
      init: function() {
        var base = this,
          container = $(base.selector),
          stype;

        container.each(function() {
          if ($(this).hasClass('video')) {
            stype = 'iframe';
          } else {
            stype = 'image';
          }
          $(this).magnificPopup({
            type: stype,
            closeOnContentClick: true,
            fixedContentPos: true,
            closeBtnInside: false,
            closeMarkup: '<button title="%title%" class="mfp-close">' + themeajax.icons.close + '</button>',
            mainClass: 'mfp',
            removalDelay: 250,
            overflowY: 'scroll',
            image: {
              verticalFit: false
            }
          });
        });

      }
    },
    magnificInline: {
      selector: '[rel="inline"]',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          var _this = $(this),
            eclass = (_this.data('class') ? _this.data('class') : '');

          _this.magnificPopup({
            type: 'inline',
            midClick: true,
            mainClass: 'mfp ' + eclass,
            removalDelay: 250,
            closeBtnInside: true,
            overflowY: 'scroll',
            closeMarkup: '<button title="%title%" class="mfp-close">' + themeajax.icons.close + '</button>',
            callbacks: {
              open: function() {
                var that = this;
                if (eclass === 'quick-search') {
                  setTimeout(function() {
                    $(that.content[0]).find('.search-field')[0].focus();
                  }, 100);
                }
              },
              close: function() {
                if (eclass === 'newsletter') {
                  Cookies.set('newsletter_popup', '1', {
                    expires: parseInt(themeajax.settings.newsletter_length, 10)
                  });
                }
              }
            }
          });
        });

      }
    },
    magnificGallery: {
      selector: '[rel="gallery"]',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          $(this).magnificPopup({
            delegate: 'a',
            type: 'image',
            closeOnContentClick: true,
            fixedContentPos: true,
            mainClass: 'mfp',
            removalDelay: 250,
            closeMarkup: '<button title="%title%" class="mfp-close">' + themeajax.icons.close + '</button>',
            closeBtnInside: false,
            overflowY: 'scroll',
            gallery: {
              enabled: true,
              navigateByImgClick: false,
              preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
              verticalFit: false,
              titleSrc: function(item) {
                return item.el.attr('title');
              }
            }
          });
        });
      }
    },
    newsletterPopup: {
      selector: '.newsletter-popup',
      init: function() {
        var base = this,
          container = $(base.selector);
        if (container.hasClass('newsletter-popup') && Cookies.get('newsletter_popup') !== '1' && themeajax.settings.newsletter === 'on') {
          _.delay(function() {
            $.magnificPopup.open({
              type: 'inline',
              items: {
                src: '#newsletter-popup',
                type: 'inline'
              },
              midClick: true,
              mainClass: 'mfp newsletter-popup',
              removalDelay: 250,
              closeBtnInside: true,
              overflowY: 'scroll',
              closeMarkup: '<button title="%title%" class="mfp-close">' + themeajax.icons.close + '</button>',
              callbacks: {
                close: function() {
                  Cookies.set('newsletter_popup', '1', {
                    expires: parseInt(themeajax.settings.newsletter_length, 10)
                  });
                }
              }
            });
          }, (parseInt(themeajax.settings.newsletter_delay, 10) * 1000));
        }
      }
    },
    shareArticleDetail: {
      selector: '.share-article',
      init: function() {
        var base = this,
          container = $(base.selector),
          link = container.find('.thb_share'),
          icons = container.find('.icons'),
          social = container.find('.social'),
          tl = gsap.timeline({
            paused: true,
            onStart: function() {
              icons.css('display', 'block');
            },
            onReverseComplete: function() {
              icons.css('display', 'none');
            }
          });

        link.on('click', function() {
          return false;
        });

        social.on('click', function() {
          var left = (screen.width / 2) - (640 / 2),
            top = (screen.height / 2) - (440 / 2) - 100;
          window.open($(this).attr('href'), 'mywin', 'left=' + left + ',top=' + top + ',width=640,height=440,toolbar=0');
          return false;
        });

        var x_perc = body.hasClass('rtl') ? '50%' : '-50%';
        tl
          .fromTo(icons, {
            y: '6',
            x: x_perc,
            autoAlpha: 0
          }, {
            duration: 0.25,
            y: '-2',
            x: x_perc,
            autoAlpha: 1
          });

        container.hoverIntent(function() {
          tl.timeScale(1).play();
        }, function() {
          tl.timeScale(1.5).reverse();
        });
      }
    },
    tweetActions: {
      selector: '.thb-tweet-actions',
      init: function() {
        var base = this,
          container = $(base.selector),
          social = container.find('.social');

        social.on('click', function() {
          var left = (screen.width / 2) - (640 / 2),
            top = (screen.height / 2) - (440 / 2) - 100;
          window.open($(this).attr('href'), 'mywin', 'left=' + left + ',top=' + top + ',width=640,height=440,toolbar=0');
          return false;
        });
      }
    },
    newsletter: {
      selector: '.newsletter-form:not(.thb-active)',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          var _this = $(this),
            security = _this.data('security'),
            args = {
              action: 'thb_subscribe_emails',
              privacy: false,
              security: security,
            };
          _this.addClass('thb-active');
          _this.on('submit', function() {
            if (_this.next('.thb-custom-checkbox').length) {
              args.privacy = true;
              args.checked = _this.next('.thb-custom-checkbox').find('.thb-newsletter-privacy').is(':checked');
            }
            args.email = _this.find('.widget_subscribe').val();
            $.ajax(themeajax.url, {
              method: 'POST',
              data: args,
              beforeSend: function() {
                _this.addClass('thb-loading');
              },
              success: function(data) {
                var d = $.parseHTML($.trim(data));
                _this.removeClass('thb-loading');
                $(d).appendTo(body);
                _.delay(function() {
                  $(d).remove();
                }, 8000);
              }
            });
            return false;
          });
        });

      }
    },
    paginationStyle2: {
      selector: '.pagination-style2',
      init: function() {
        var base = this,
          container = $(base.selector),
          load_more = $('.thb_load_more'),
          thb_loading = false,
          page = 2,
          security = container.data('security');

        load_more.on('click', function() {
          var _this = $(this),
            text = _this.text(),
            count = themeajax.settings.posts_per_page;

          if (thb_loading === false) {
            _this.html(themeajax.l10n.loading).addClass('loading');

            $.ajax(themeajax.url, {
              method: 'POST',
              data: {
                action: 'thb_blog_ajax',
                page: page++,
                security: security
              },
              beforeSend: function() {
                thb_loading = true;
              },
              success: function(data) {
                thb_loading = false;
                var d = $.parseHTML($.trim(data)),
                  l = d ? d.length : 0;

                if (data === '' || data === 'undefined' || data === 'No More Posts' || data === 'No $args array created') {
                  _this.html(themeajax.l10n.nomore).removeClass('loading').off('click');
                } else {

                  $(d).appendTo(container).hide().imagesLoaded(function() {
                    if (container.data('isotope')) {
                      container.isotope('appended', $(d));
                    }
                    $(d).show();
                    gsap.set($(d), {
                      opacity: 0,
                      y: 30
                    });
                    gsap.to($(d), {
                      duration: 0.5,
                      y: 0,
                      opacity: 1,
                      stagger: 0.25
                    });
                  });

                  if (l < count) {
                    _this.html(themeajax.l10n.nomore).removeClass('loading');
                  } else {
                    _this.html(text).removeClass('loading');
                  }
                }
              }
            });
          }
          return false;
        });
      }
    },
    paginationStyle3: {
      selector: '.pagination-style3',
      init: function() {
        var base = this,
          container = $(base.selector),
          page = 2,
          thb_loading = false,
          count = themeajax.settings.posts_per_page,
          security = container.data('security');

        var scrollFunction = _.debounce(function() {

          if (thb_loading === false) {
            container.addClass('thb-loading');
            $.ajax(themeajax.url, {
              method: 'POST',
              data: {
                action: 'thb_blog_ajax',
                page: page++,
                security: security
              },
              beforeSend: function() {
                thb_loading = true;
              },
              success: function(data) {
                thb_loading = false;
                container.removeClass('thb-loading');
                var d = $.parseHTML($.trim(data)),
                  l = d ? d.length : 0;

                if (data === '' || data === 'undefined' || data === 'No More Posts' || data === 'No $args array created') {
                  win.off('scroll', scrollFunction);
                } else {
                  $(d).appendTo(container).hide().imagesLoaded(function() {
                    if (container.data('isotope')) {
                      container.isotope('appended', $(d));
                    }
                    $(d).show();
                    gsap.set($(d), {
                      opacity: 0,
                      y: 30
                    });
                    gsap.to($(d), {
                      duration: 0.5,
                      y: 0,
                      opacity: 1,
                      stagger: 0.25
                    });
                  });

                  if (l >= count) {
                    win.on('scroll', scrollFunction);
                  }
                }
              }
            });
          }
        }, 30);

        win.scroll(scrollFunction);
      }
    },
    shopSidebar: {
      selector: '#side-filters .widget',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          var that = $(this),
            t = that.find('>h6');

          t.append($('<span/>')).on('click', function() {
            t.toggleClass('active');
            t.next().animate({
              height: "toggle",
              opacity: "toggle"
            }, 300);
          });
        });

        $('.widget_layered_nav span.count, .widget_product_categories span.count, .widget_tag_cloud .tag-link-count').each(function() {
          var count = $.trim($(this).html());
          count = count.substring(1, count.length - 1);
          $(this).html(count);
        });
      }
    },
    shopLoading: {
      selector: '.post-type-archive-product ul.products.thb-main-products, .tax-product_cat ul.products.thb-main-products, .tax-product_tag ul.products.thb-main-products',
      thb_loading: false,
      scrollInfinite: false,
      href: false,
      init: function() {
        var base = this,
          container = $(base.selector),
          type = themeajax.settings.shop_product_listing_pagination;

        if ($('.woocommerce-pagination').length && (body.hasClass('post-type-archive-product') || body.hasClass('tax-product_cat') || body.hasClass('tax-product_tag'))) {
          if (type === 'style2') {
            base.loadButton(container);
          } else if (type === 'style3') {
            base.loadInfinite(container);
          }
        }
      },
      loadButton: function(container) {
        var base = this;

        $('.woocommerce-pagination').before('<div class="thb_load_more_container text-center"><a class="thb_load_more button">' + themeajax.l10n.loadmore + '</a></div>');

        if ($('.woocommerce-pagination a.next').length === 0) {
          $('.thb_load_more_container').addClass('is-hidden');
        }
        $('.woocommerce-pagination').hide();

        body.on('click', '.thb_load_more:not(.no-ajax)', function(e) {
          var _this = $(this);
          base.href = $('.woocommerce-pagination a.next').attr('href');


          if (base.thb_loading === false) {
            _this.html(themeajax.l10n.loading).addClass('loading');

            base.loadProducts(_this, container);
          }
          return false;
        });
      },
      loadInfinite: function(container) {
        var base = this;

        if ($('.woocommerce-pagination a.next').length === 0) {
          $('.thb_load_more_container').addClass('is-hidden');
        }
        $('.woocommerce-pagination').hide();

        base.scrollInfinite = _.debounce(function() {
          if ((base.thb_loading === false) && ((win.scrollTop() + win.height() + 150) >= (container.offset().top + container.outerHeight()))) {

            base.href = $('.woocommerce-pagination a.next').attr('href');
            base.loadProducts(false, container, true);
          }
        }, 30);

        win.on('scroll', base.scrollInfinite);
      },
      loadProducts: function(button, container, infinite) {
        var base = this;
        $.ajax(base.href, {
          method: 'GET',
          beforeSend: function() {
            base.thb_loading = true;

            if (infinite) {
              win.off('scroll', base.scrollInfinite);
            }
          },
          success: function(response) {
            var resp = $(response),
              products = resp.find('ul.products.thb-main-products li');

            $('.woocommerce-pagination').html(resp.find('.woocommerce-pagination').html());

            if (button) {
              if (!resp.find('.woocommerce-pagination .next').length) {
                button.html(themeajax.l10n.nomore_products).removeClass('loading').addClass('no-ajax');
              } else {
                button.html(themeajax.l10n.loadmore).removeClass('loading');
              }
            } else if (infinite) {
              if (resp.find('.woocommerce-pagination .next').length) {
                win.on('scroll', base.scrollInfinite);
              }
            }
            if (products.length) {
              products.addClass('will-animate').appendTo(container);
              gsap.set(products, {
                opacity: 0,
                y: 30
              });
              gsap.to(products, {
                duration: 0.3,
                y: 0,
                opacity: 1,
                stagger: 0.15
              });
            }
            base.thb_loading = false;
          }
        });
      }
    },
    postShortcodeLoadmore: {
      selector: '.posts-pagination-style2',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          var _this = $(this),
            security = _this.data('security'),
            load_more = $(_this.data('loadmore')),
            thb_loading = false,
            page = 2;

          load_more.on('click', function() {
            var loadmore = $(this),
              id = loadmore.data('posts-id'),
              text = loadmore.text(),
              pajax = ('thb_postsajax_' + id),
              count = window[pajax].count,
              loop = window[pajax].loop,
              columns = window[pajax].columns,
              excerpts = window[pajax].excerpts;

            if (thb_loading === false) {
              loadmore.prop('title', themeajax.l10n.loading);
              loadmore.text(themeajax.l10n.loading).addClass('loading');
              thb_loading = true;
              $.ajax(themeajax.url, {
                method: 'POST',
                data: {
                  action: 'thb_posts_ajax',
                  security: security,
                  page: page++,
                  loop: loop,
                  columns: columns,
                  excerpts: excerpts,
                },
                beforeSend: function() {
                  thb_loading = true;
                },
                success: function(data) {
                  var d = $.parseHTML($.trim(data)),
                    l = d ? d.length : 0;

                  if (data === '' || data === 'undefined' || data === 'No More Posts' || data === 'No $args array created') {
                    loadmore.html(themeajax.l10n.nomore).removeClass('loading').off('click');
                  } else {

                    $(d).appendTo(_this).hide().imagesLoaded(function() {
                      if (_this.data('isotope')) {
                        _this.isotope('appended', $(d));
                      }
                      $(d).show();
                      var animate = $(d).find('.animation').length ? $(d).find('.animation') : $(d).filter(function() {
                        return this.nodeType === 1;
                      });
                      gsap.fromTo(animate, {
                        autoAlpha: 0,
                        y: 20
                      }, {
                        duration: 0.5,
                        autoAlpha: 1,
                        y: 0,
                        stagger: 0.15,
                        onComplete: function() {
                          thb_loading = false;
                        }
                      });

                    });

                    if (l < count) {
                      loadmore.html(themeajax.l10n.nomore).removeClass('loading');
                    } else {
                      loadmore.html(text).removeClass('loading');
                    }
                  }
                }
              });
            }
            return false;
          });
        });
      }
    },
    revslider: {
      selector: '[data-thb_revslider="thb_revslider_affect_headers"]',
      init: function() {
        var base = this,
          container = $(base.selector),
          revid = container.find('.rev_slider').attr('id'),
          revolution = $('#' + revid);


        if (revolution.length) {
          revolution.bind("revolution.slide.onloaded", function(e) {
            revolution.bind("revolution.slide.onafterswap", function(e, data) {
              var color = data.currentslide.data('param1');
              body.removeClass('light-title dark-title');
              if (color) {
                body.addClass(color);
              }
            });
          });

          container.closest('.thb-arrow').each(function() {
            var _that = $(this),
              cursor_area = _that.parents('.thb-cursor-area');

            container.bind('mousemove', function(e) {
              var offset = cursor_area.offset(),
                mouseX = Math.min(e.pageX - offset.left, cursor_area.width()),
                mouseY = e.pageY - offset.top;
              if (mouseX < 0) {
                mouseX = 0;
              }
              if (mouseY < 0) {
                mouseY = 0;
              }

              gsap.set(_that, {
                x: mouseX - 25,
                y: mouseY - 20,
                force3D: true
              });

            });
            cursor_area.on('click', function() {
              if (cursor_area.hasClass('left')) {
                revolution.revprev();
              } else {
                revolution.revnext();
              }
            });
          });
        }
      }
    },
    cookieBar: {
      selector: '.thb-cookie-bar',
      init: function() {
        var base = this,
          container = $(base.selector),
          button = $('.button-accept', container);

        if (Cookies.get('thb-north-cookiebar') !== 'hide') {
          gsap.to(container, 0.5, {
            duration: 0.5,
            opacity: '1',
            y: '0%'
          });
        }
        button.on('click', function() {
          Cookies.set('thb-north-cookiebar', 'hide', {
            expires: 30
          });
          gsap.to(container, {
            duration: 0.5,
            opacity: '0',
            y: '100%'
          });
          return false;
        });
      }
    },
    onePage: {
      selector: '#thb_fullscreen_rows',
      init: function() {
        var base = this,
          container = $(base.selector),
          animationspeed = 1400,
          footer = $('.footer-container'),
          elementor_wrap = $('.elementor-section-wrap'),
          anchors = [];

        SITE.fullPageEnabled = true;

        $('>.wpb_row, .elementor-section', container).each(function() {
          var _this = $(this),
            anchor = _this.data('onepage-anchor') ? _this.data('onepage-anchor') : '';
          anchors.push(anchor);
        });
        if (elementor_wrap.length) {
          $('.fullpage-wrapper').attr('id', false);
          elementor_wrap.attr('id', 'thb_fullscreen_rows');
          elementor_wrap.addClass('fullpage-wrapper');
          container = elementor_wrap;
        }
        if (footer.length) {
          footer.appendTo(container);
        }
        container.fullpage({
          sectionSelector: '>.wpb_row, .elementor-section',
          navigation: true,
          css3: true,
          scrollingSpeed: animationspeed,
          anchors: anchors,
          scrollOverflow: true,
          navigationPosition: 'left',
          afterLoad: function(anchorLink, index) {
            var firstRow = $('.wpb_row.fp-section:nth-child(' + index + ')', container),
              color = firstRow.data('midnight');

            SITE.animation.container(firstRow);
            var ins = firstRow.data('vide');
            if (ins) {
              ins.getVideoObject().play();
            }
            if (color && !body.hasClass(color)) {
              body.removeClass('light-title dark-title').addClass(color);
            }
            if (elementor_wrap.length) {
              elementor_wrap.addClass('fp-section-active');
            }
            win.trigger('resize');
          },
          onLeave: function(index, nextIndex, direction) {
            var currentRow = $('.wpb_row.fp-section:nth-child(' + index + ')', container),
              nextRow = $('.wpb_row.fp-section:nth-child(' + nextIndex + ')', container),
              color = nextRow.data('midnight'),
              dir = direction === 'down' ? 1 : -1;

            function animateSlide() {
              gsap
                .to(currentRow, {
                  duration: (animationspeed / 1000),
                  opacity: 0.8,
                  y: (dir * 50) + '%',
                  ease: thb_ease,
                  clearProps: "all"
                });
            }

            if (currentRow.data('vide')) {
              currentRow.data('vide').getVideoObject().pause();
            }

            if (direction === 'down') {
              if (!nextRow.hasClass('footer-container')) {
                animateSlide();
              } else {
                currentRow.addClass('before-footer');
              }
            } else {
              if (!nextRow.hasClass('before-footer')) {
                animateSlide();
              }
            }
            var ins = nextRow.data('vide');
            if (ins) {
              ins.resize();
            }
            _.delay(function() {
              SITE.animation.container(nextRow);
              win.trigger('resize');
              currentRow.removeClass('active');

              if (ins) {
                ins.getVideoObject().play();
              }
            }, animationspeed);


          }
        });
      }
    },
    contact: {
      selector: '.contact_map',
      init: function() {
        var base = this,
          container = $(base.selector);


        container.each(function() {
          var _this = $(this),
            mapzoom = _this.data('map-zoom'),
            mapstyle = _this.data('map-style'),
            mapType = _this.data('map-type'),
            panControl = _this.data('pan-control'),
            zoomControl = _this.data('zoom-control'),
            mapTypeControl = _this.data('maptype-control'),
            scaleControl = _this.data('scale-control'),
            streetViewControl = _this.data('streetview-control'),
            locations = _this.find('.thb-location'),
            once;

          var bounds = new google.maps.LatLngBounds();

          var mapOptions = {
            center: {
              lat: -34.397,
              lng: 150.644
            },
            styles: mapstyle,
            zoom: mapzoom,
            draggable: !("ontouchend" in document),
            scrollwheel: false,
            panControl: panControl,
            zoomControl: zoomControl,
            mapTypeControl: mapTypeControl,
            scaleControl: scaleControl,
            streetViewControl: streetViewControl,
            fullscreenControl: false,
            mapTypeId: mapType,
            gestureHandling: 'cooperative'
          };

          var map = new google.maps.Map(_this[0], mapOptions);

          map.addListener('tilesloaded', function() {
            if (!once) {
              locations.each(function(i) {
                var location = $(this),
                  options = location.data('option'),
                  lat = options.latitude,
                  long = options.longitude,
                  latlng = new google.maps.LatLng(lat, long),
                  marker = options.marker_image,
                  marker_size = options.marker_size,
                  retina = options.retina_marker,
                  title = options.marker_title,
                  desc = options.marker_description,
                  pinimageLoad = new Image();

                bounds.extend(latlng);

                pinimageLoad.src = marker;

                $(pinimageLoad).on('load', function() {
                  base.setMarkers(i, locations.length, map, lat, long, marker, marker_size, title, desc, retina);
                });
                once = true;
              });

              if (mapzoom > 0) {
                map.setCenter(bounds.getCenter());
                map.setZoom(mapzoom);
              } else {
                map.setCenter(bounds.getCenter());
                map.fitBounds(bounds);
              }
            }
          });

          win.on('resize', _.debounce(function() {
            map.setCenter(bounds.getCenter());
          }, 50));

        });
      },
      setMarkers: function(i, count, map, lat, long, marker, marker_size, title, desc, retina) {

        function showPin(i) {

          var markerExt = marker.toLowerCase().split('.');
          markerExt = markerExt[markerExt.length - 1];

          if ($.inArray(markerExt, ['svg']) || retina) {
            marker = new google.maps.MarkerImage(marker, null, null, null, new google.maps.Size(marker_size[0] / 2, marker_size[1] / 2));
          }
          var g_marker = new google.maps.Marker({
              position: new google.maps.LatLng(lat, long),
              map: map,
              animation: google.maps.Animation.DROP,
              icon: marker,
              optimized: false
            }),
            contentString = '<h3>' + title + '</h3>' + '<div>' + desc + '</div>';

          // info windows
          var infowindow = new google.maps.InfoWindow({
            content: contentString
          });

          g_marker.addListener('click', function() {
            infowindow.open(map, g_marker);
          });
        }
        setTimeout(showPin, i * 250, i);
      }
    },
    hotspot: {
      selector: '.thb-hotspot-container',
      init: function() {
        var base = this,
          container = $(base.selector);

        container.each(function() {
          var _this = $(this),
            hotspots = _this.find('.thb-hotspot'),
            func = _this.hasClass('hover') ? 'hover' : 'click',
            target = _this.parents('.thb-hotspot-wrapper').find('.slick');

          hotspots.on(func, function() {
            var i = $(this).index() - 1;
            target.slick('slickGoTo', i);
          });
        });
      }
    },
    iconbox: {
      selector: '.thb-iconbox',
      control: function(container, delay) {
        if (container.data('thb-in-viewport') === undefined && !container.hasClass('animation-off')) {
          container.data('thb-in-viewport', true);

          var _this = container,
            animation_speed = _this.data('animation_speed') !== '' ? _this.data('animation_speed') : '1.5',
            svg = _this.find('svg'),
            img = _this.find('img'),
            el = svg.find('path, circle, rect, ellipse'),
            h = _this.find('h5'),
            p = _this.find('p'),
            tl = gsap.timeline({
              delay: delay,
              paused: true
            }),
            all = h.add(p).add(img);

          tl
            .set(_this, {
              visibility: 'visible'
            })
            .set(svg, {
              display: 'block'
            })
            .from(el, {
              duration: animation_speed,
              drawSVG: "0%",
              stagger: 0.2
            }, "s")
            .from(all, {
              duration: (animation_speed / 2),
              autoAlpha: 0,
              y: '20px',
              stagger: 0.1
            }, "s+=" + (animation_speed / 2));

          tl.play();
        }
      }
    },
    animation: {
      selector: '.animation, .thb-iconbox, .thb-slidetype',
      init: function() {
        var base = this,
          container = $(base.selector);

        if (!$('#thb_fullscreen_rows').length) {
          base.control(container, true);

          win.on('scroll.thb-animation', function() {
            base.control(container, true);
          }).trigger('scroll.thb-animation');
        }
      },
      container: function(container) {
        var base = this,
          element = $(base.selector, container);

        base.control(element, false);
      },
      control: function(element, filter) {
        var t = 0,
          delay = 0.2,
          speed = 0.75,
          el = filter ? element.filter(':in-viewport') : element;

        el.each(function() {
          var _this = $(this),
            params = {
              autoAlpha: 1,
              x: 0,
              y: 0,
              z: 0,
              rotationZ: '0deg',
              rotationX: '0deg',
              rotationY: '0deg',
              delay: t * delay
            };

          if (_this.data('thb-animated') !== true) {
            _this.data('thb-animated', true);

            if (_this.hasClass('thb-iconbox')) {
              SITE.iconbox.control(_this, t * delay);
            } else if (_this.hasClass('thb-slidetype')) {
              SITE.slideType.control(_this, t * delay);
            } else {
              if (_this.hasClass('scale')) {
                params.scale = 1;
              }
              params.duration = speed;
              gsap.to(_this, params);
            }
            t++;
          }
        });
      }
    },
    slideType: {
      selector: '.thb-slidetype',
      control: function(container, delay, skip) {
        if ((container.data('thb-in-viewport') === undefined) || skip) {
          container.data('thb-in-viewport', true);
          var style = container.data('style'),
            split,
            tl = gsap.timeline({
              onComplete: function() {
                if (style !== 'style1') {
                  split.revert();
                }
              }
            }),
            animated_split,
            dur = 0.25,
            stagger = 0.05;
          if (!container.find('.thb-slidetype-entry').length) {
            return;
          }
          if (style === 'style1') {
            animated_split = container.find('.thb-slidetype-entry .lines');
            dur = 0.65;
            stagger = 0.15;
          } else if (style === 'style2') {
            split = new SplitText(container.find('.thb-slidetype-entry'), {
              type: 'words'
            });
            animated_split = split.words;
            dur = 0.65;
            stagger = 0.15;
          } else if (style === 'style3') {
            split = new SplitText(container.find('.thb-slidetype-entry'), {
              type: 'chars'
            });
            animated_split = split.chars;
          }

          tl
            .set(container, {
              visibility: "visible"
            })
            .from(animated_split, {
              duration: dur,
              y: '200%',
              delay: delay,
              stagger: stagger
            }, '+=0');

        }
      }
    },
  };

  $(function() {
    if ($('#vc_inline-anchor').length) {
      win.on('vc_reload', function() {
        SITE.init();
      });
    } else {
      SITE.init();
    }
  });

})(jQuery, this, _);