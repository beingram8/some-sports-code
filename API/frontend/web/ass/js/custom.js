$(document).ready(function() {
    $(document).on('click', '.menu-icon', function(e) {
        $('.header-menu').toggleClass('shown');
    });
    $(document).on('click', '.time-button', function(e) {
        $('.time-button').removeClass('time-selected');
        $(this).addClass('time-selected');
    });

});
var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };
$(document).ready(function() {
    $('#appoint-date-filter').daterangepicker({
        opens: 'right',

    }, function(start, end, label) {
        var startDate = start.format('MM/DD/Y');
        var endDate = end.format('MM/DD/Y');
        var current = $(location).attr('href');
        var url = new URL(current);
        var c = url.searchParams.get("type");
        if (c == 'list') {
            window.location.href = "?type=list&start_date=" + startDate + "&end_date=" + endDate;
        } else {
            window.location.href = "?start_date=" + startDate + "&end_date=" + endDate;
        }
    });
});