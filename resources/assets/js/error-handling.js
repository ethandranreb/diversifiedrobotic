errorControl = {
    timeDelay : 500,
    reset : function () {
        inputs = $('.input-error');
        msgs = $('.input-error-msg-show');
        $.each(inputs, function () {
            $(this).removeClass('input-error');
            if ($('p#' + $(this).attr('id') + '-help').length){ $('p#' + $(this).attr('id') + '-help').slideUp(errorControl.timeDelay); }
        });
        $.each(msgs, function () { $(this).removeClass('input-error-msg-show').addClass('input-error-msg-hide').text(''); });
    },
    displayInputError : function () {
        errorControl.reset();
        for(var i = 0; i < filterControl.errors.length; i++) {
            $('#' + filterControl.errors[i]).addClass('input-error');
            $('span#' + filterControl.errors[i] + '-msg').removeClass('input-error-msg-hide').addClass('input-error-msg-show').text( '( ' +filterControl.errors[++i] + ' )');
            if ($('p#' + filterControl.errors[i - 1] + '-help').length){ $('p#' + filterControl.errors[i - 1] + '-help').slideDown(errorControl.timeDelay); }
        }
    }
}