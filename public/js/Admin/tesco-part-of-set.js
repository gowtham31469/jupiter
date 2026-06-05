var isPartOfSetSubmitting = false;

$('#add-part-of-set-form').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {
    } else {
        e.preventDefault();
        addPartOfSet();
    }
});

$(document).on('click', '.edit-tesco-part-of-set', function(e) {
    var id = $(this).closest('tr').attr('id');
    e.preventDefault();
    getTescoPartOfSetById(id);
});

$('#edit-part-of-set-form').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {
    } else {
        e.preventDefault();
        updatePartOfSet();
    }
});

function tescoPartOfSet() {
    try {
        $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
        $('.orchestra-sidebar').hide();
        $('.tesco-sidebar').show();

        $.ajax({
            url: base_url + "/admin/tesco/settings/tesco-part-of-set",
            type: "POST",
            dataType: "json",
            data: { _token: csrf_token },
            tryCount: 0,
            retryLimit: 2,
            beforeSend: function() { $(".loader").show(); },
            success: function(data) {
                var tesco_metadata =
                    '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>' +
                    '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button">' +
                    '<a href="#" data-target="#addPartOfSetModal" class="btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false">' +
                    '<i class="fa fa-plus"></i> Add Part of Set</a></div></div>' +
                    '<div class="template-block"><div class="top-content"><h3 class="content-heading">Part of Set</h3></div>' +
                    '<table id="tesco-part-of-set-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">' +
                    '<thead><tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Actions</th></tr></thead><tbody>';

                $.each(data.part_of_set, function(i, item) {
                    var index = i + 1;
                    tesco_metadata += '<tr id="' + item.id + '">' +
                        '<td>' + index + '</td>' +
                        '<td>' + (item.name || '') + '</td>' +
                        '<td>' + (item.polish || '') + '</td>' +
                        '<td>' + (item.czech || '') + '</td>' +
                        '<td>' + (item.slovak || '') + '</td>' +
                        '<td>' + (item.hungarian || '') + '</td>' +
                        '<td><a href="#"><i class="fa fa-pencil fa-button edit-tesco-part-of-set filterbtn options" title="Edit" data-placement="left"></i></a></td>' +
                        '</tr>';
                });

                tesco_metadata += '</tbody></table></div>';
                $('.content').html(tesco_metadata);
                $('#tesco-part-of-set-table').DataTable();
                $("#tesco-part-of-set-table_filter input").addClass('form-control input-sm');
                $("#tesco-part-of-set-table_filter input").attr('placeholder', 'Data Search');
                $('#tesco-part-of-set-table_length select').addClass('form-control input-sm');
            },
            error: function(errorXHR, errorStatus, errorThrown) { console.log(errorThrown); },
            complete: function() { $(".loader").hide(); }
        });
    } catch (e) {
        console.log("Error:" + e);
    }
}

function addPartOfSet() {
    try {
        $.ajax({
            url: base_url + "/admin/tesco/add-tesco-part-of-set",
            method: 'POST',
            data: {
                _token: csrf_token,
                name: $('#part_of_set_name').val(),
                polish: $('#part_of_set_polish').val(),
                czech: $('#part_of_set_czech').val(),
                slovak: $('#part_of_set_slovak').val(),
                hungarian: $('#part_of_set_hungarian').val(),
            },
            tryCount: 0,
            retryLimit: 2,
            beforeSend: function() { $(".loader").show(); },
            statusCode: {
                200: function() {
                    $('.snackbar_span').html("Part of Set Added Successfully");
                    showSnackbar();
                    $('#addPartOfSetModal').modal('hide');
                    $('#add-part-of-set-form')[0].reset();
                    tescoPartOfSet();
                },
                208: function() {
                    $('#part_of_set_error').removeClass('hidden').removeAttr('style')
                        .html('Entry already exists.').addClass('alert alert-warning');
                },
                400: function() {
                    $('.snackbar_span').html("Something went wrong!!");
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) { console.log(errorThrown); },
            complete: function() { $(".loader").hide(); }
        });
    } catch (e) {
        console.log("Error:" + e);
    }
}

function getTescoPartOfSetById(id) {
    try {
        if (isPartOfSetSubmitting) return;
        isPartOfSetSubmitting = true;

        $.ajax({
            url: base_url + "/admin/tesco/get-tesco-part-of-set",
            dataType: "json",
            method: "get",
            data: { id: id },
            tryCount: 0,
            retryLimit: 2,
            beforeSend: function() { $(".loader").show(); },
            success: function(data) {
                isPartOfSetSubmitting = false;
                var item = data.part_of_set[0];
                $('#edit_part_of_set_id').val(item.id);
                $('#edit_part_of_set_name').val(item.name);
                $('#edit_part_of_set_polish').val(item.polish);
                $('#edit_part_of_set_czech').val(item.czech);
                $('#edit_part_of_set_slovak').val(item.slovak);
                $('#edit_part_of_set_hungarian').val(item.hungarian);
                $('#editPartOfSetModal').modal({ backdrop: 'static', keyboard: false });
                $('#edit-part-of-set-form').validator('validate');
                $('#editPartOfSetModal').modal('show');
            },
            error: function(errorXHR, errorStatus, errorThrown) { console.log(errorThrown); },
            complete: function() { $(".loader").hide(); }
        });
    } catch (e) {
        console.log("Error:" + e);
    }
}

function updatePartOfSet() {
    try {
        $.ajax({
            url: base_url + "/admin/tesco/update-tesco-part-of-set",
            dataType: "json",
            method: "put",
            data: {
                _token: csrf_token,
                id: $('#edit_part_of_set_id').val(),
                name: $('#edit_part_of_set_name').val(),
                polish: $('#edit_part_of_set_polish').val(),
                czech: $('#edit_part_of_set_czech').val(),
                slovak: $('#edit_part_of_set_slovak').val(),
                hungarian: $('#edit_part_of_set_hungarian').val(),
            },
            tryCount: 0,
            retryLimit: 2,
            beforeSend: function() {
                $(".loader").show();
                $('#part_of_set_update_error').addClass('hidden');
            },
            statusCode: {
                200: function() {
                    $('.snackbar_span').html("Part of Set Updated Successfully");
                    showSnackbar();
                    $('#editPartOfSetModal').modal('hide');
                    tescoPartOfSet();
                },
                208: function() {
                    $('#part_of_set_update_error').removeClass('hidden').removeAttr('style')
                        .html('Entry already exists.').addClass('alert alert-warning');
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) { console.log(errorThrown); },
            complete: function() { $(".loader").hide(); }
        });
    } catch (e) {
        console.log("Error:" + e);
    }
}
