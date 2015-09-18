
$(function(){

    $('.typeahead').each(function() {
        $(this).typeahead({
            remote: {
                url: $(this).attr('data-typeahead-url'),
                wildcard: '__QUERY_PLACEHOLDER__'
            }
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
