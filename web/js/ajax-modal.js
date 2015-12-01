$(function () {
    $('.modal-view-link').click(function () {
        $('#book-view').modal('show')
            .find('#book-view-content')
            .load($(this).attr('data-url'));
    });
});