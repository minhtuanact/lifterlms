(function ($) {
    $(function () {

        $('#mn-0, #mn-1, #mn-2, #mn-3, #mn-4, #mn-5, #mn-6').dialog({
            autoOpen: false,
            width: 500,
            height: 250,
            draggable: false,
            dialogClass: 'magenet-dialog',
            show: {effect: "fade", duration: 300},
            hide: {effect: "fade", duration: 300}
        });

        $('.btn_prev').click(function () {
            close_all_tutorial();
            next_prev_popup_open(this, 'prev');
        });

        $('.btn_next').click(function () {
            close_all_tutorial();
            next_prev_popup_open(this, 'next');
        });

        $('.show-magenet-tutorial').click(function () {
            close_all_tutorial();
            $('#mn-1').dialog('open');
            $('#mn-1').find('a').blur();
        });

        jQuery.post(ajaxurl, {'action': 'magenet_action'}, function (response) {
            if (response == 1)
                $('#mn-0').dialog('open');
        });

        var close_all_tutorial = function () {
            $('#mn-0, #mn-1, #mn-2, #mn-3, #mn-4, #mn-5, #mn-6').dialog('close');
            return false;
        };

        var next_prev_popup_open = function (button, direction)
        {
            var id_popup = $(button).closest('.magenet-tutorial-popup').attr('id');

            if (id_popup) {
                var where = 1;
                if (direction == 'prev')
                    where = -1;
                var ids_array = id_popup.split('-');
                
                var id_other_number = parseInt(ids_array[1]) + 1 * where;
                var id_other_str = '#' + ids_array[0] + '-' + id_other_number;

                $(id_other_str).dialog('open');
                $(id_other_str).find('a').blur();
            }
        };

    });
})(jQuery);