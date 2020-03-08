jQuery(document).ready(function ($) {
    $('body').on('click', '.ticket-borrar', function (event) {
        event.preventDefault();
        var $enlace = $(this);
        var $filaEnlace = $enlace.parents('tr');
        $.post(ajax_object.ajax_url,
            {
                action: 'kfp_ticket_borrar',
                nonce: ajax_object.ajax_nonce,
                ticket_id: $enlace.data('ticket_id')
            },
            function (response) {
                $filaEnlace.remove();
            });
        return false;
    });
});