
$(function(){

    $.nette.init();


    $('.select2').each(function() {
        $(this).select2({
            theme: "bootstrap",
            ajax: {
                url: $(this).attr('data-select2-url'),
                dataType: 'json',
                delay: 250,
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },

            //escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1,
            //templateResult: formatRepo, // omitted for brevity, see the source of this page
            //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    });

});


(function ($) {

    $.nette.ext('bs-modal', {
        init: function () {
            var self = this;

            this.ext('snippets', true).after($.proxy(function ($el) {
                if (!$el.is('.modal')) {
                    return;
                }

                self.open($el);
            }, this));

            $('.modal[id^="snippet-"]').each(function () {
                self.open($(this));
            });
        }
    }, {
        open: function (el) {
            var content = el.find('.modal-content');
            if (!content.length) {
                return; // ignore empty modal
            }

            el.modal({});
        }
    });

})(jQuery);


/**
 * This file is part of the Nextras community extensions of Nette Framework
 *
 * @license    MIT
 * @link       https://github.com/nextras/forms
 * @author     Jan Skrasek
 */

jQuery(function($) {
    $('.typeahead').each(function() {
        $(this).typeahead({
            remote: {
                url: $(this).attr('data-typeahead-url'),
                wildcard: '__QUERY_PLACEHOLDER__'
            }
        });
    });
});


/**
 * This file is part of the Nextras community extensions of Nette Framework
 *
 * @license    MIT
 * @link       https://github.com/nextras/forms
 * @author     Jan Skrasek
 */

jQuery(function($) {
    $('input.date, input.datetime-local').each(function(i, el) {
        el = $(el);
        el.get(0).type = 'text';
        el.datetimepicker({
            startDate: el.attr('min'),
            endDate: el.attr('max'),
            weekStart: 1,
            minView: el.is('.date') ? 'month' : 'hour',
            format: el.is('.date') ? 'd. m. yyyy' : 'd. m. yyyy - hh:ii', // for seconds support use 'd. m. yyyy - hh:ii:ss'
            autoclose: true
        });
        el.attr('value') && el.datetimepicker('setValue');
    });
});
