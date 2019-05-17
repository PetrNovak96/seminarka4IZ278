function on_delete(link, id) {
    //window.location.href='/~novp19/events/delete/' + id;
    $.ajax({
        url: '/~novp19/'+link+'/delete/'+id,
        type: 'POST',
        success: function(result) {
            window.location.href='/~novp19/'+link+'/deleted/' + id;
        },
        error: function (error) {
            window.location.href='/~novp19/'+link+'/deleteError/' + id + '/' + error.responseText;
        }
    });
}

function signUp() {
    window.location.href='/~novp19/signUp/';
}

function signUpNew() {
    window.location.href='/~novp19/signUp/newUser/';
}

function signUpExisting() {
    window.location.href='/~novp19/signUp/existing/';
}

function onLogOut() {
    window.location.href='/~novp19/logout/';
}

function google(url) {
    window.location.href = url;
}

function forgottenPassword() {
    window.location.href = '/~novp19/login/forgotten/';
}

jQuery(document).ready(function($) {
    $( document ).delegate( ".report", "click", function() {
        var element = $(this);
        var employeeId = $(this).data("employeeid");
        var eventId = $(this).data("eventid");

        $.ajax({
            url: '/~novp19/events/report',
            type: 'POST',
            data: {
                employee: employeeId,
                event: eventId,
            },
            success: function(result) {
                element.removeClass("fa-times");
                element.addClass("fa-check");
                element.removeClass("report");
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});