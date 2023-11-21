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

$.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    }
    return decodeURI(results[1]) || 0;
}
$(function() {
    var html_button = $('.open_html_modal');
    html_button.click(function() {
        console.log("here");
        var value = $(this).attr('data-id');
        console.log
        $(".html_desc").html(value);
        $("#html-modal").modal('show');
    })
});

function getMonthlyUsers(year) {
    call({ url: 'account/monthly-users', params: { 'year': year }, type: 'GET' }, function(res) {
        if (res.status) {
            $('#monthUser').html(res.html)
        } else {}
    });
}

$(document).ready(function() {


    $(document).on('click', '.update-match', function() {
        $.pjax.reload({
            container: '#matches-grid',
            async: true
        });

        $('#preloader').removeClass('hide');
        var fixture_id = $(this).data('fixture-id');
        call({ url: 'fetch/fetch-fixture', params: { 'fixture_id': fixture_id }, type: 'GET' }, function(res) {
            $('#preloader').addClass('hide');
            if (res.status) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                })
                $.pjax({ container: '#matches' })
            } else {
                Swal.fire("Error", res.message, "error");
            }
        });
    });
    $(document).on('click', '.calculate-point', function() {
        var r = confirm("Are you sure you want calculate point manually? Because system cron will calculate automatically.");
        if (r == true) {

            $('#preloader').removeClass('hide');
            var fixture_id = $(this).data('fixture-id');
            var _this = $(this);
            _this.attr('disabled', "disabled");
            call({ url: 'match/calculate-point', params: { 'fixture_id': fixture_id }, type: 'GET' }, function(res) {
                $('#preloader').addClass('hide');
                _this.removeAttr('disabled');
                if (res.status) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Point is calculated!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    $.pjax({ container: '#matches' })
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            });
        }
    });
    $(document).on('click', '.enable-vote', function() {
        var r = confirm("Are you sure you want enable vote manually? Because system cron will enable voting automatically once match finished(in 10 min). System will send notification to all users");
        if (r == true) {

            $('#preloader').removeClass('hide');
            var fixture_id = $(this).data('fixture-id');
            var _this = $(this);
            _this.attr('disabled', "disabled");
            call({ url: 'match/enable-vote', params: { 'fixture_id': fixture_id }, type: 'GET' }, function(res) {
                $('#preloader').addClass('hide');
                _this.removeAttr('disabled');
                if (res.status) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Vote is enabled!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    $.pjax({ container: '#matches' })
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            });
        }
    });
    $(document).on('click', '.fetch-player', function() {
        $('#preloader').removeClass('hide');
        var fixture_id = $(this).data('fixture-id');
        var _this = $(this);
        call({ url: 'fetch/fetch-match-players', params: { 'fixture_id': fixture_id }, type: 'GET' }, function(res) {
            $('#preloader').addClass('hide');
            if (res.status) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Player Fetched!',
                    showConfirmButton: false,
                    timer: 2000
                })
                $.pjax({ container: '#matches-grid' })
            } else {
                Swal.fire("Error", res.message, "error");
            }
        });
    });
    $(document).on('click', '.fetch-match', function() {
        $('#preloader').removeClass('hide');
        var fixture_id = $(this).data('fixture-id');
        var _this = $(this);
        call({ url: 'fetch/fetch-fixture', params: { 'fixture_id': fixture_id }, type: 'GET' }, function(res) {
            $('#preloader').addClass('hide');
            if (res.status) {
                _this.parent().html("<i class='fa fa-check text-success'></i>");
                $.pjax.reload('#matches', { timeout: false });
            } else {
                Swal.fire("Error", res.message, "error");
            }
        });
    });
    $(document).on('click', '.fetch-league-base-fixtures', function() {
        $('#preloader').show();
        var league_id = $(this).data('league-id');
        var season = $(this).data('season');
        call({ url: 'fetch/fetch-league-based-fixtures', params: { 'league_id': league_id, 'season': season }, type: 'GET' }, function(res) {
            $('#preloader').hide();
            if (res.status) {
                $('#fixtures').html(res.data);
                $('#fixture_table').DataTable({
                    paging: false,
                    scrollY: 600,
                    buttons: [{
                        extend: 'searchBuilder',
                        config: {
                            depthLimit: 2
                        }
                    }],
                    dom: 'Bfrtip',
                });

            } else {
                $('#fixtures').html(res.message);
            }
        });
    });

    //to update user status
    $(document).on('change', '.status-dropdown', function(e) {

        var value = $(this).val();
        var id = $(this).data("id");
        call({ url: 'user/update-status', params: { 'id': id, 'value': value } }, function(response) {
            if (response.data == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Status Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        });
    });

    $(document).on('change', '.document_status', function(e) {

        var value = $(this).val();
        var id = $(this).data("id");
        call({ url: 'user/update-document', params: { 'id': id, 'value': value } }, function(response) {
            if (response.data == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Status Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                })
                $.pjax.reload({
                    container: '#parent-grid',
                    async: true
                });
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        });
    });

    $(document).on('change', '.room_status', function(e) {

        var value = $(this).val();
        var id = $(this).data("id");
        call({ url: 'teasing-room/update-room', params: { 'id': id, 'value': value } }, function(response) {
            if (response.data == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Status Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                })
                $.pjax({container: '#pjax-grid-view'})
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        });
    });

    //update matchday in 
    $(document).on('change', '.match-day', function(e) {
        var value = $(this).val();
        var id = $(this).data("id");
        call({ url: 'match/update-match-day', params: { 'match_id': id, 'match_day': value } }, function(response) {
            if (response.data == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Match Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        });
    });

    $(document).on('change', '.is-active-dropdown', function(e) {
        var quiz_id = $(this).data("id");
        var option = $(this).val();
        call({ url: 'quiz/update-active', params: { quiz_id: quiz_id, option: option } }, function(response) {
            if (response.data == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Status Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                })
                $.pjax({ container: '#quiz-grid' })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        })
    });

    $(document).on('change', '.contact-us-status', function(e) {
        var id = $(this).data("id");
        var option = $(this).val();
        call({ url: 'contact-us/update-status', params: { id: id, option: option } }, function(response) {
            if (response.data == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Status Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                })
                $.pjax({ container: '#quiz-grid' })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        })
    });

    $(document).on('change', '.is-expire-dropdown', function(e) {
        var season = $(this).data("id");
        var option = $(this).data("option");
        call({ url: 'season/update-expire', params: { season: season, option: option } }, function(response) {

            if (response.data == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Is Expired Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                });
                $.pjax({ container: '#quiz-grid' })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        });
    });

    $(document).on('change', '.is-active-ajax-dropdown', function(e) {
        var team_id = $(this).data("id");
        var option = $(this).data("option");
        $.ajax({
            url: 'update-active',
            type: 'post',
            data: { team_id: team_id, option: option },
            dataType: "json",
            encode: true,
            success: function(response) {
                console.log(response)
                if (response.data == true) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Status Updated!!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            },
            error: function() {
                console.log('internal server error');
            }
        });
    });

    $(document).on('change', '.is_main_team-ajax-dropdown', function(e) {
        var id = $(this).data("id");
        var option = $(this).data("is_main_team");

        $.ajax({
            url: 'update-is-main',
            type: 'post',
            data: { id: id, option: option },
            dataType: "json",
            success: function(response) {
                if (response.data == true) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Is Main Team Updated!!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
                console.log(response);
            },
            error: function() {
                console.log('internal server error');
            }
        });
    });

    $(document).on('change', '.is_main-ajax-dropdown', function(e) {
        var id = $(this).data("id");
        var option = $(this).data("is_main");

        $.ajax({
            url: 'update-is-main',
            type: 'post',
            data: { id: id, option: option },
            dataType: "json",
            success: function(response) {
                if (response.data == true) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Is Main League Updated!!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
                console.log(response);
            },
            error: function() {
                console.log('internal server error');
            }
        });
    });
    //add match url ajax call
    $(document).on('click', '.add-url-btn', function() {
        var match_id = $(this).attr('data-id');
        var match_url = $(this).attr('data-url');
        $("#add-url-modal").modal('show');
        $('#seasonmatch-match_url').val(match_url);
        $(".modal-submit").click(function() {
            var form_url = $('.url-text').val();
            if (form_url == '') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "Url can't be blank.",
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                call({ url: 'match/add-url', params: { 'match_id': match_id, 'match_url': form_url, }, async: true, type: 'POST' },
                    function(resp) {
                        if (resp.status == true) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: "Match URL added",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            $("#add-url-modal").modal('hide');
                            $.pjax.reload('#matches');
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: "Something went wrong",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            $.pjax.reload('#matches');
                            $("#add-url-modal").modal('hide');
                        }
                    });
            }
        });
    });

    $(document).on('change', '.is-national-team-ajax-dropdown', function(e) {
        var id = $(this).data("id");
        var option = $(this).data("is-national-team");

        $.ajax({
            url: 'update-is-national',
            type: 'post',
            data: { id: id, option: option },
            dataType: "json",
            success: function(response) {
                if (response.data == true) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Is National Team Updated!!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            },
            error: function() {
                console.log('internal server error');
            }
        });
    });

    $(document).on('change', '.is-active-survey', function(e) {
        var survey_id = $(this).data("id");
        var option = $(this).val();
        call({ url: 'survey/update-active', params: { survey_id: survey_id, option: option } }, function(response) {
            if (response.data == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Status Updated!!',
                    showConfirmButton: false,
                    timer: 2000
                })
                $.pjax({ container: '#survey-grid' })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        })
    });

    $(document).on('change', '#leagueform-season', function() {
        $('#leagueForm').submit();
    });
    $(document).on('click', '#start', function() {
        $(this).text('Starting.....');
        $(this).attr('disabled', 'disabled');
        if ($('#appointment-room').children().length > 0) {
            Swal.fire("Sorry", 'You have already joined..', "error");
            $(this).removeAttr('disabled');
            $(this).text('Start');
            return false;
        }
        call({ url: 'attention/start', params: { 'appointment_id': $(this).attr('app_id') }, type: 'GET' }, function(res) {
            $(this).removeAttr('disabled');
            $(this).text('Start');
            if (res.status) {
                if (res.data.appointment.type == "Personal") {
                    Swal.fire("Success!", res.message, "success");
                }
                location.reload();
            } else {
                Swal.fire("Error", res.message, "error");
            }
        });
    });

});



var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };