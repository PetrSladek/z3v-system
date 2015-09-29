
$(function(){

    $.nette.init();

    $(document).on('click','.tr-remove', function() {
        $(this).closest('tr').fadeOut(1000, function () {
            $(this).remove();
        });
    });
    $(document).on('click','.tbody-remove', function() {
        $(this).closest('tbody').fadeOut(1000, function () {
            $(this).remove();
        });
    });

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
        },
        success: function (payload) {
            if(payload.modalClose)
            {
                $('.modal').modal('hide');
            }
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

    $.nette.ext('tr-remove', {
        before: function (xhr, settings) {
            if(settings.nette.el.is('.tr-remove'))
            {
                settings.nette.el.closest('tr').fadeOut(500, function() {
                    $(this).remove();
                });
            }
        }
    }, {

    });


    $.nette.ext('jq', {
        init: function() {
            this.initialize();
        },
        complete: function() {
            this.initialize();
        }
    }, {
        initialize: function() {
            /**
             * This file is part of the Nextras community extensions of Nette Framework
             *
             * @license    MIT
             * @link       https://github.com/nextras/forms
             * @author     Jan Skrasek
             */

            $('.typeahead').each(function() {
                $(this).typeahead({
                    remote: {
                        url: $(this).attr('data-typeahead-url'),
                        wildcard: '__QUERY_PLACEHOLDER__'
                    }
                });
            });

            /**
             * This file is part of the Nextras community extensions of Nette Framework
             *
             * @license    MIT
             * @link       https://github.com/nextras/forms
             * @author     Jan Skrasek
             */

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
        }
    })



})(jQuery);

