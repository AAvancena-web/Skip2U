/**
 * Compatibility shims.
 *
 * The child theme's footer.php carries a block of inline jQuery that never
 * runs on our templates, because we do not load that file. Most of it drives
 * the old header and is deliberately dropped. These are the initialisers page
 * content still depends on.
 *
 * Deliberately NOT ported: the .reflex-menu-toggle mobile menu, the sticky
 * .fixed header class, and the checkout datepickers (checkout stays on the
 * original theme).
 */
(function ($) {
  'use strict';
  if (!$) return;

  $(function () {
    // WPBakery accordions inside page content.
    if ($.fn.vcAccordion && $.fn.vcAccordion.Constructor) {
      $.fn.vcAccordion.Constructor.prototype.changeLocationHash = function () {};
    }

    // Product and gallery sliders used by some inner pages.
    if ($.fn.lightSlider && $('.image-gallery').length) {
      $('.image-gallery').lightSlider({
        gallery: true, item: 1, loop: true, thumbItem: 7, thumbMargin: 16,
        galleryMargin: 16, slideMove: 1, speed: 2500, controls: false,
        pager: true, mode: 'slide', adaptiveHeight: true,
        onSliderLoad: function () {
          if ($.fn.magnificPopup) {
            $('.image-gallery').magnificPopup({
              delegate: 'a', type: 'image',
              gallery: { enabled: true, navigateByImgClick: true, preload: [0, 1] }
            });
          }
        }
      });
    }

    // Dimensions modal on bin pages.
    $('.custom-button .Dimensions-view, .dimensions_btn').on('click', function (e) {
      e.preventDefault();
      $(this).closest('li, .field_right').find('.dimensions_image').addClass('is-visible');
      $('body').addClass('open-modal');
    });
    $('.uk-close').on('click', function () {
      $('.dimensions_image').removeClass('is-visible');
      $('body').removeClass('open-modal');
    });

    // Tabbed blocks.
    $('.location_tab-nav span, .tab-nav span').on('click', function () {
      $([$(this).parent()[0], $($(this).data('href'))[0]])
        .addClass('active').siblings('.active').removeClass('active');
    });

    // Read more toggles.
    $('.skip-content span').on('click', function () {
      $(this).prev().slideToggle('slow');
      $(this).text($(this).text() === 'Read more' ? 'Read less' : 'Read more');
    });
  });
})(window.jQuery);
