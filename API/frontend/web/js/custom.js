var call = function(data, callback) {
    var callTry = function(data, callback) {
        var DATA = data.params;
        var ajxOpts = {
            url: baseUrl + data.url,
            data: DATA,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            type: (typeof data.type != 'undefined' ? data.type : 'Post'),
        };
        $.ajax(ajxOpts).done(function(res) {
            callback(res);
        }).fail(function(r) {
            callback('fail');
        });
    }
    callTry(data, callback);
}

$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
        return null;
    }
    return decodeURI(results[1]) || 0;
}

$(document).ready(function() {
    $(document).on('click', '.menu-icon', function(e) {
        $('.header-menu').toggleClass('shown');
    });
    $(document).on('click', '.time-button', function(e) {
        $('.time-button').removeClass('time-selected');
        $(this).addClass('time-selected');
        // $('.app_slot_box').prop('checked',false);
        $(this).find('.app_slot_box').attr('checked','checked');
    });

});
var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };
$(document).ready(function() {
    $('#appoint-date-filter').daterangepicker({
        opens: 'right',
    }, function(start, end, label) {
        var startDate = start.format('YYYY-MM-DD');
        var endDate = end.format('YYYY-MM-DD');
        var current = $(location).attr('href');
        var url = new URL(current);
        var c = url.searchParams.get("type");
        if (c == 'list') {
            window.location.href = url + "&start_date=" + startDate + "&end_date=" + endDate;
        } else {
            window.location.href = url + "?start_date=" + startDate + "&end_date=" + endDate;
        }
    });

    
    $(document).on('change','.rate-dropdown',function(){
        var incident_id = $(this).attr('incident_id');
        call({ url: 'incident/do-rate',params: {'incident_id':incident_id,'star':$(this).val()}, type: 'GET' }, function(resp) {
            $.pjax.reload('#incident-grid', {timeout: 3000});
        });
    });
});

