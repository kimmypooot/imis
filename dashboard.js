$(document).ready(function () {
    console.log('yes');
    Luma.FetchData({
        query: `SELECT * FROM system_access WHERE user = :user`,
        params: {
            user: $('#userId').val()
        }
    }).then(data => {
        const access = data[0];
        Luma.SetSession({
            eris: access.eris,
            ors: access.ors,
            otrs: access.otrs,
            iis: access.iis,
            rfcs: access.rfcs,
            cdl: access.cdl,
            dvs: access.dvs,
            cts: access.cts,
            lms: access.lms,
            gad_corner: access.gad_corner
        });
        console.log(access);
        
        if (access.otrs == 'None') {
            $('.manage-btn[data-id="OTRS"]').data('role', access.otrs);
            $('.manage-btn[data-id="OTRS"]').data('user', access.user);
        }
        if (access.eris) {
            $('.manage-btn[data-id="ERIS"]').data('role', access.eris);
            $('.manage-btn[data-id="ERIS"]').data('user', access.user);
        }
        if (access.ors == 'None') {
            $('.manage-btn[data-id="ORS"]').prop('disabled', true);
        } else {
            $('.manage-btn[data-id="ORS"]').data('role', access.ors);
            $('.manage-btn[data-id="ORS"]').data('user', access.user);
        }
        if (access.cdl == 'None') {
            $('.manage-btn[data-id="CDL"]').prop('disabled', true);
        }
        if (access.iis == 'None') {
            $('.manage-btn[data-id="IIS"]').prop('disabled', true);
        }
        if (access.rfcs == 'None') {
            $('.manage-btn[data-id="RFCS"]').prop('disabled', true);
        }
        else {
            $('.manage-btn[data-id="RFCS"]').data('role', access.rfcs);
            $('.manage-btn[data-id="RFCS"]').data('user', access.user);
        }
        if (access.dvs == 'None') {
            $('.manage-btn[data-id="DVS"]').prop('disabled', true);
        }
        if (access.cts == 'None') {
            $('.manage-btn[data-id="CTS"]').prop('disabled', true);
            $('#ctsToHide').hide();
        }
        else {
            $('.manage-btn[data-id="CTS"]').data('role', access.cts);
            $('.manage-btn[data-id="CTS"]').data('user', access.user);
        }
        if (access.lms == 'None') {
            $('.manage-btn[data-id="LMS"]').prop('disabled', true);
        }
        if (access.gad_corner == 'None') {
            $('.manage-btn[data-id="GAD-CORNER"]').prop('disabled', true);
        } else {
            $('.manage-btn[data-id="GAD-CORNER"]').data('role', access.gad_corner);
            $('.manage-btn[data-id="GAD-CORNER"]').data('user', access.user);
        }
    })

    $(document).on('click', '.manage-btn', function() {
    var system = $(this).data('id');
    var role = $(this).data('role');
    var type = $(this).data('type');
    var userId = $(this).data('user');
    console.log(system);
    console.log("re" + role);
    switch (system) {
        case 'OTRS':
            if (role === "Admin") {
                window.location.href = 'otrs/admin/hrd/index';
            } else if (role === "User") {
                window.location.href = `otrs/admin/index?location=${type}`;
            } else {
                Luma.Alert('Access Denied', 'You are not allowed to access OTRS', 'error');
            }
            break;
        case 'ORS':
            if (role === "Admin") {
                console.log('asdasd');
                window.location.href = 'ors/index';
            } else {
                Luma.Alert('Access Denied', 'You are not allowed to access ORS', 'error');
            }
            break;
        case 'CTS':
            Luma.FetchData({
                query: `SELECT * FROM cts_manage_users WHERE user = :user`,
                params: {
                    user: userId
                }
            }).then(data => {
                if (data.length != 0) {
                    Luma.SetSession({userId: data[0].id, ao_number: data[0].ao_number}, false).then(success => {
                        if (success) {
                            if (role == "Admin" || role == "Superadmin") {
                                window.location.href = 'cts/admin/index_dashboard';
                            } else if (role == "User") {
                                window.location.href = 'cts/users/index_dashboard';
                            }
                        } else {
                            Luma.Alert('Error!', 'Error setting session', 'error');
                        }
                    });
                } else {
                    Luma.Alert('Error!', 'You dont have an account with this system yet', 'error');
                }
            });
            break;
        case 'CDL':
            window.location.href = 'cdl/admin/index_clients';
            break;
        case 'RFCS':
            if (role == "Admin") {
                window.location.href = 'fts/admin/msd/index_dashboard';
            } else if (role == "User") {
                Luma.FetchData({
                    query: `SELECT * FROM trip_drivers WHERE user = :user`,
                    params: {
                        user: userId
                    }
                }).then(data => {
                    if (data.length != 0) {
                        Luma.SetSession({driver_id: data[0].id}, false).then(success => {
                            if (success) {
                                window.location.href = 'fts/admin/index_dashboard';
                            } else {
                                Luma.Alert('Error!', 'Error setting session', 'error');
                            }
                        });
                    } else {
                        Luma.Alert('Error!', 'You dont have an account with this system yet', 'error');
                    }
                });
            }
            break;
        case 'ERIS':
            if (role == "Admin") {
                window.location.href = 'eris/esd/index';
            } else if (role == "User") {
                window.location.href = 'eris/index';
            } else if (role == "None") {
                window.location.href = 'eris/db-onsa';
            }
            break;
        case 'GAD-CORNER':
            if (role == "Admin") {
                window.location.href = 'gad-corner/admin/index';
            } else if (role == "User") {
                window.location.href = 'gad-corner/index';
            } else if (role == "None") {
                Luma.Alert('Access Denied', 'You are not allowed to access GAD Corner', 'error');
            }
            break;
    }
});

})