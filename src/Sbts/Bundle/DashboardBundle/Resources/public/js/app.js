'use strict';

$(function () {
    $(':checkbox').radiocheck();

    $('a[data-confirm]').click(function() {
        var href = $(this).attr('href');
        var modal = $('#confirm-delete');

        modal.find('.modal-body').text($(this).attr('data-confirm'));
        modal.find('.btn-ok').attr('href', href);
        modal.modal({show:true});
        return false;
    });
});
