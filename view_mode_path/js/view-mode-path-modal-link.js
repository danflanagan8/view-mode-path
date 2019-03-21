(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.viewModePath = {
    attach: function attach(context, settings) {

      //basically reproduce the use-ajax thing from core ajax.js
      //but with a fullscreen progress element.
      $('.view-mode-path-modal-link', context).once('view-mode-path').each(function (i, ajaxLink) {
        var $linkElement = $(ajaxLink);
        var elementSettings = {
          progress: { type: 'fullscreen' },
          dialogType: $linkElement.data('dialog-type'),
          dialog: $linkElement.data('dialog-options'),
          dialogRenderer: $linkElement.data('dialog-renderer'),
          base: $linkElement.attr('id'),
          element: ajaxLink
        };
        var href = $linkElement.attr('href');

        if (href) {
          elementSettings.url = href;
          elementSettings.event = 'click';
        }
        Drupal.ajax(elementSettings);
      });
    },
  };
})(jQuery, Drupal, drupalSettings);
