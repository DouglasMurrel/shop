jQuery(document).ready(function($) {
    $('#accordion ul').hide();
    $('#accordion .badge').on('click', function () {
        var $badge = $(this);
        var closed = $badge.parent().siblings('ul') && !$badge.parent().siblings('ul') .is(':visible');

        if (closed) {
            $badge.parent().siblings('ul').slideDown('normal', function () {
                $badge.parent().children('i').removeClass('fa-plus').addClass('fa-minus');
            });
        } else {
            $badge.parent().siblings('ul').slideUp('normal', function () {
                $badge.parent().children('i').removeClass('fa-minus').addClass('fa-plus');
            });
        }
    });
});