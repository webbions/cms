// Delete for confirmation box..
$(document).ready(function() {
    $('.modal input').keypress(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    // Delete record on Id
    $('body').on('click', '.deleteLinkButton', function() {
        var recordId = $(this).attr('id');
        var anchor_tag_html = $('#anchor_' + recordId).html();
        $('#delete_anchor_tag_content').html(anchor_tag_html);
    });

    var triggeredByChild = false;

    $('body').on('ifChecked', '#check-all', function(event) {
        $('.check').iCheck('check');
        triggeredByChild = false;
        massDeleteCountChecked();
    });

    $('body').on('ifUnchecked', '#check-all', function(event) {
        if (!triggeredByChild) {
            $('.check').iCheck('uncheck');
        }
        triggeredByChild = false;
        massDeleteCountChecked();
    });
    // Removed the checked state from "All" if any checkbox is unchecked
    $('body').on('ifUnchecked', '.check', function(event) {
        triggeredByChild = true;
        $('#check-all').iCheck('uncheck');
        massDeleteCountChecked();
    });

    $('body').on('ifChecked', '.check', function(event) {
        if ($('.check').filter(':checked').length == $('.check').length) {
            $('#check-all').iCheck('check');
        }
        massDeleteCountChecked();
    });

    $("#apply_mass_deletes").click(function() {
        var option = $(".massActions").val();
        var pageUrl = $("#page_url").val();

        var pageUrlCopy = $("#page_url_copy").val();
        var pageUrlMove = $("#page_url_move").val();

        var checkedValues = $('.check:checked').map(function() {
            return this.value;
        }).get();
        $('.massCount').text(checkedValues.length);
        $('#delete-all-flag').val('no');
        if (option == "delete" && pageUrl != '') {
            $('#mass_value').val(checkedValues);
            $('#mass_delete_form').attr('action', pageUrl);
            $('#multiple_delete_modal').modal({show: true});
        }

        if (option == "copy" && pageUrl != '') {
            $('#library_type_id_form').val($('#library_type_id').val());
            $('#mass_value').val(checkedValues);
            $('#mass_delete_form').attr('action', pageUrlCopy);
            $('#multiple_delete_modal').modal({show: true});
        }

        if (option == "move" && pageUrl != '') {
            $('#library_type_id_form').val($('#library_type_id').val());

            $('#mass_value').val(checkedValues);
            $('#mass_delete_form').attr('action', pageUrlMove);
            $('#multiple_delete_modal').modal({show: true});
        }
        return false;
    });

    $("#apply_all_mass_actions").click(function() {
        $('.massCount').text($('.allResults').text());
        var option = $(".massActions").val();
        var pageUrl = $("#page_url").val();
        if (option == "delete" && pageUrl != '') {
            $('#delete-all-flag').val('yes');
            $('#deleteWhereStatement').val($('#where-statement-id').val());
            $('#mass_delete_form').attr('action', pageUrl);
            $('#multiple_delete_modal').modal({show: true});
        }
        return false;
    });

    $("#apply_btn").click(function() {
        $('#apply-all-flag').val('no');
        $('#delete-all-flag').val('no');
    });

    $("#apply_all").click(function() {
        $('.massCount').text($('.allResults').text());
        $('#apply-all-flag').val('yes');
        $('#delete-all-flag').val('yes');
        $('#deleteWhereStatement').val($('#where-statement-id').val());
    });

    $("#apply_btn, #apply_all").click(function() {
        var option = $(".massActionsList").val();
        var checkedValues = $('.check:checked').map(function() {
            return this.value;
        }).get();
        var pageUrl = $("#page_url").val();

        if (option == "delete" && pageUrl != '') {
            $('#mass_value').val(checkedValues);
            $('#mass_delete_form').attr('action', pageUrl);
            $('#multiple_delete_modal').modal({show: true});
        }

        if (option == "mark_read") {
            markToRead(checkedValues);
        }

        if (option == "active") {
            $('#active_btn').trigger('click');
        }
        if (option == "deactivate") {
            $('#deactive_btn').trigger('click');
        }
        if (option == "copy") {
            $('#copy_btn').trigger('click');
        }
        if (option == "move") {
            $('#move_btn').trigger('click');
        }
        if (option == "export") {
            $('#export_btn').trigger('click');
        }
        return false;
    });

    /* Set page length */
    $('#example2_length').change(function() {
        oTable.page.len(this.value).draw();
    });

    /* Filter reset */
    $('#filterReset, #filter_reset_btn').click(function() {
        $('.search').val('');
        $('.search_filter').val('');
        $('.search').prop('checked', false);
        $('.search_filter').prop('checked', false);
        oTable.columns().search('').draw();

    });

    /* Apply search filter on change and keyup event */
    $('.search').on('keyup change', function() {
        oTable.columns($(this).attr('data-column')).search($(this).val()).draw();
    });

    /* Apply search filter ifChecked */
    $('body').on('ifChecked', '.search', function(event) {
        oTable.columns($(this).attr('data-column')).search($(this).val()).draw();
    });
});

/**
 * Delete multiple rows
 * @returns {undefined}
 */
function massDeleteCountChecked() {
    var numberOfChecked = $('.check:checkbox:checked').length;
    if (numberOfChecked > 0) {
        $(".massActionsButton").removeAttr("disabled");
    }
    else {
        $(".massActionsButton").attr("disabled", "disabled");
    }
    $('#apply_mass_deletes').text('(' + numberOfChecked + ')Apply');
    $('#apply_btn').text('(' + numberOfChecked + ')Apply');
    $('.massCount').text(numberOfChecked);
}

/**
 *  massCallBack method
 * @param {type} response
 * @returns {undefined}         
 **/
function massCallBack(response) {
    $("#where-statement-id").val(response.whereStatement);
    var numberOfChecked = response.iTotalDisplayRecords;
    if (numberOfChecked > 0) {
        $('.all-mass-actions').removeAttr("disabled");
    }
    else {
        $('.all-mass-actions').attr("disabled", "disabled");
    }
    ;
    $(".allResults").text(numberOfChecked);

}

/**
 * changeSelectedRecordsStatus- method
 * @param {type} status
 * @param {type} selectedFieldsName
 * @param {type} tableName
 * @param {type} whereFieldName
 * @param {type} updateFieldName
 * @returns {undefined}     
 */
function changeSelectedRecordsStatus(status, selectedFieldsName, tableName, whereFieldName, updateFieldName) {

    /* Get selected records id */
    var selectedRecordsids = getSelectedValues(selectedFieldsName);
    var applyAllFlag = $('#apply-all-flag').val();
    var whereStatementId = $('#where-statement-id').val();
    var selectedCheckFlag = true;
    if (selectedRecordsids.length < 1 && applyAllFlag == 'no') {
        selectedCheckFlag = false;
    }

    if (selectedCheckFlag) {

        $.ajax({
            url: '/LibraryTypes/change_selected_records_status',
            type: 'post',
            data: 'status=' + status +
                    '&selectedRecordsIds=' + selectedRecordsids +
                    '&tableName=' + tableName +
                    '&whereFieldName=' + whereFieldName +
                    '&updateFieldName=' + updateFieldName +
                    '&apply-all-flag=' + applyAllFlag +
                    '&library_type_id=' + $('#library_type_id').val() +
                    '&where-statement-cond=' + whereStatementId,
            dataType: 'json',
            beforeSend: function() {
                $('body').css('opacity', 0.5);
            },
            complete: function() {
                $('body').css('opacity', 1);
            },
            success: function(result) {
                if (result.success == false) {
                    $(".box-body").prepend(result.json_msg);
                } else {
                    $(".box-body").prepend(result.json_msg);
                    oTable.draw();
                }
                $('html, body').animate({scrollTop: 0}, 'fast');

            }
        });

        $('#custom_error_msg span').text('');
        $('#custom_error_msg').hide();

    } else {

        $('html, body').animate({scrollTop: 0}, 'slow');
        $('#custom_error_msg span').text('Please select at least one record to proceed.');
        $('#custom_error_msg').show();
    }
}

/**
 * Mark To Read
 * @param {type} checkedValues
 * @returns {undefined}
 */
function markToRead(checkedValues) {
    /* Get selected records id */
    var selectedRecordsids = checkedValues;
    var applyAllFlag = $('#apply-all-flag').val();
    var massReadPageUrlFlag = $('#mass_read_page_url').val();
    var whereStatementId = $('#where-statement-id').val();
    var selectedCheckFlag = true;
    if (selectedRecordsids.length < 1 && applyAllFlag == 'no') {
        selectedCheckFlag = false;
    }
    if (selectedCheckFlag && massReadPageUrlFlag != '') {
        $.ajax({
            url: massReadPageUrlFlag,
            type: 'post',
            data: 'selectedRecordsIds=' + selectedRecordsids +
                    '&apply-all-flag=' + applyAllFlag +
                    '&where-statement-cond=' + whereStatementId,
            dataType: 'json',
            beforeSend: function() {
                $('body').css('opacity', 0.5);
            },
            complete: function() {
                $('body').css('opacity', 1);
            },
            success: function(result) {
                if (result.success == false) {
                    $(".box-body").prepend(result.json_msg);
                } else {
                    $(".box-body").prepend(result.json_msg);
                    oTable.draw();
                }
                $('html, body').animate({scrollTop: 0}, 'fast');

            }
        });

        $('#custom_error_msg span').text('');
        $('#custom_error_msg').hide();

    } else {

        $('html, body').animate({scrollTop: 0}, 'slow');
        $('#custom_error_msg span').text('Please select at least one record to proceed.');
        $('#custom_error_msg').show();
    }
}

/**
 * deleteSelectedRecords method
 * @param {type} selectedRecordsIds
 * @param {type} tableName
 * @returns {undefined}
 */
function  deleteSelectedRecords(selectedRecordsIds, tableName, fieldName) {
    if (selectedRecordsIds.length > 0) {
        $.ajax({
            url: '/LibraryTypes/delete_selected_records',
            type: 'post',
            data: 'selectedRecordsIds=' + selectedRecordsIds +
                    '&tableName=' + tableName +
                    '&fieldName=' + fieldName,
            beforeSend: function() {
                $('body').css('opacity', 0.5);
            },
            complete: function() {
                $('body').css('opacity', 1);
            },
            success: function(result) {
                location.reload('refresh');
            }
        });

        $('#custom_error_msg span').text('');
        $('#custom_error_msg').hide();
    } else {
        $('html, body').animate({scrollTop: 0}, 'slow');
        $('#custom_error_msg span').text('Please select at least one record to proceed.');
        $('#custom_error_msg').show();
    }
}

/**
 * getSelectedValues method
 * @param {type} name
 * @returns {Array|getSelectedValues.allVals}
 */
function getSelectedValues(name) {
    var allVals = [];
    $('input[name="' + name + '"]:checked').each(function() {
        allVals.push(this.value);
    });
    return allVals;
}

/**
 * delete_action method  method
 * @param {type} id
 * @param {type} table_name
 * @param {type} field_name
 * @returns {undefined}
 */
function delete_action(name, table_name, field_name) {

    /* Get selected records id */
    var allVals = getSelectedValues(name);

    if (allVals.length > 0) {
        /* Open delete popup */
        $('#deletePopUp').trigger('click');

        /* delete_anchor_tag_content replace with Delete anchor */
        var name = '"' + name + '"';
        var table_name = '"' + table_name + '"';
        var field_name = '"' + field_name + '"';
        $('#multiple_delete_anchor_tag_content').html("<a class='btn btn-default' href='javascript:void(0);' onclick='multipleDeleteRecords(" + name + "," + table_name + "," + field_name + ");'>Delete</a>");
    } else {
        /* Remove error block */
        $('html, body').animate({scrollTop: 0}, 'slow');
        $('#custom_error_msg span').text('Please select at least one record to proceed.');
        $('#custom_error_msg').show();
    }
}

/**
 *  Delete selected records 
 */
function multipleDeleteRecords(deleted_checkbox_name, table_name, field_name) {
    /* Close pop up */
    $('#close_delete_pop_up').trigger('click');

    /* Get selected records id */
    var selectedRecordsIds = getSelectedValues(deleted_checkbox_name);

    /* Delete selected records */
    deleteSelectedRecords(selectedRecordsIds, table_name, field_name);
}


/**
 * openPopupForSelectTableToMoveAndCopy - Method
 * @param {type} type
 * @param {type} selectedFieldsName
 * @returns {undefined}     
 */
function openPopupForSelectTableToMoveAndCopy(type, selectedFieldsName) {

    $('#action_type').val(type);

    /* Get selected records id */
    var selectedRecordsids = getSelectedValues(selectedFieldsName);
    var applyAllFlag = $('#apply-all-flag').val();
    var whereStatementId = $('#where-statement-id').val();
    var selectedCheckFlag = true;
    if (selectedRecordsids.length < 1 && applyAllFlag == 'no') {
        selectedCheckFlag = false;
    }

    if (selectedCheckFlag) {

        $('#custom_error_msg span').text('');
        $('#custom_error_msg').hide();
        $('#open_library_type_modal').trigger('click');
    } else {

        $('html, body').animate({scrollTop: 0}, 'slow');
        $('#custom_error_msg span').text('Please select at least one record to proceed.');
        $('#custom_error_msg').show();
    }

}

/**
 * copyMoveSelectedRecords - Method
 * @param {type} selectedFieldsName
 * @param {type} tableName
 * @param {type} whereFieldName
 * @returns {undefined}
 */
function copyMoveSelectedRecords(selectedFieldsName, tableName, whereFieldName) {

    var type = $('#action_type').val();

    /* Get selected records id */
    var selectedRecordsids = getSelectedValues(selectedFieldsName);
    var applyAllFlag = $('#apply-all-flag').val();
    var whereStatementId = $('#where-statement-id').val();
    var selectedCheckFlag = true;
    if (selectedRecordsids.length < 1 && applyAllFlag == 'no') {
        selectedCheckFlag = false;
    }
    if (selectedCheckFlag) {

        $.ajax({
            url: '/LibraryTypes/copy_move_selected_records',
            type: 'post',
            data: 'type=' + type +
                    '&selectedRecordsIds=' + selectedRecordsids +
                    '&tableName=' + tableName +
                    '&whereFieldName=' + whereFieldName +
                    '&library_type_id=' + $('#library_type_id').val() +
                    '&selected_library_type_id=' + $('#library_type').val() +
                    '&apply-all-flag=' + applyAllFlag +
                    '&where-statement-cond=' + whereStatementId,
            dataType: 'json',
            beforeSend: function() {
                $('body').css('opacity', 0.5);
            },
            complete: function() {
                $('body').css('opacity', 1);
            },
            success: function(result) {
                if (result.success == false) {
                    $(".modal-body").prepend(result.json_msg);
                } else {
                    $(".modal-body").prepend(result.json_msg);
                    oTable.draw();
                    setTimeout(function() {
                        $('#btn_edit_cancel_modal').click();
                    }, 1000);
                }
                $('#action_modal').animate({scrollTop: 0}, 'slow');
            }
        });

        $('#custom_error_msg span').text('');
        $('#custom_error_msg').hide();

    } else {

        $('html, body').animate({scrollTop: 0}, 'slow');
        $('#custom_error_msg span').text('Please select at least one record to proceed.');
        $('#custom_error_msg').show();
    }

}

/**
 * exportSelectedData - method
 * @param {type} selectedFieldsName
 * @param {type} tableName
 * @param {type} whereFieldName
 * @returns {undefined}
 */
function exportSelectedData(selectedFieldsName, tableName, whereFieldName) {

    /* Get selected records id */
    var selectedRecordsids = getSelectedValues(selectedFieldsName);
    var applyAllFlag = $('#apply-all-flag').val();
    var whereStatementId = $('#where-statement-id').val();
    var selectedCheckFlag = true;
    if (selectedRecordsids.length < 1 && applyAllFlag == 'no') {
        selectedCheckFlag = false;
    }

    if (selectedCheckFlag) {

        $('#custom_error_msg span').text('');
        $('#custom_error_msg').hide();

        $('<form action="/LibraryTypes/exportSelectedData" method="POST"/>')
                .append($('<input type="hidden" name="selectedRecordsIds" value="' + selectedRecordsids + '">'))
                .append($('<input type="hidden" name="tableName" value="' + tableName + '">'))
                .append($('<input type="hidden" name="whereFieldName" value="' + whereFieldName + '">'))
                .append($('<input type="hidden" name="apply-all-flag" value="' + applyAllFlag + '">'))
                .append($('<input type="hidden" name="where-statement-cond" value="' + whereStatementId + '">'))
                .appendTo($(document.body)) //it has to be added somewhere into the <body>
                .submit();

    } else {

        $('html, body').animate({scrollTop: 0}, 'slow');
        $('#custom_error_msg span').text('Please select at least one record to proceed.');
        $('#custom_error_msg').show();
    }
}
//for common lightbox open use this function
function customLightbox(redirectAction, responseId)
{
    $('#' + responseId).css('display', 'block');
    actionTaskList(redirectAction, responseId);
}
//This code for ajax call task list
function actionTaskList(redirectAction, responseId)
{
            $.ajax({
                    url: redirectAction,
                    type: 'POST',
                    data: '',
                    context: document.body,
                    success: function(data)
                    {
                            $("#" + responseId).html(data);
                    }
            
            });
 }

/**
 * edit_action method
 * @param {type} url
 * @returns {undefined}
 */
function edit_action(url) {
    $("#edit_ifram_id").attr('src', url);
    $('#edit_modal_form').modal('show');
}

function updateSettings(urlData, consumerKey, consumersecret)
{
    $.ajax({
                    url: urlData,
                    type: 'POST',
                    data: {'api_consumer_key': $('#' + consumerKey).val(),
            'api_consumer_secret': $('#' + consumersecret).val()
        },
                    context: document.body,
                    success: function(result)
                    {
            $(".modal-body").prepend(result.json_msg);
          }
            
        });
}
//add langauge use function
function addLanguage(redirectUrl, responseId)
{
            $.ajax({
                    url: redirectUrl,
                    type: 'POST',
                    data: {'language': $('#langaugeid').val()},
                    context: document.body,
                    success: function(result)
                    {
                           $(".modal-body").prepend(result.json_msg);
                    }
            
            });
 }
//add langauge use function
function addTranslation(redirectUrl, responseId)
{
            $.ajax({
                    url: redirectUrl,
                    type: 'POST',
                    data: {'translation_key': $('#translation_key').val(), 'translation_lang': $('#translation_lang').val(), 'translation_set': $('#translation_set').val(), 'translation_text': $('#translation_text').val()},
                    context: document.body,
                    success: function(result)
                    {
                           $(".modal-body").prepend(result.json_msg);
                    }
            
            });
 }

/**
 * Delete multiple rows
 * @returns {undefined}
 */
function massDeleteApplyCountChecked() {
    var numberOfChecked = $('.check:checkbox:checked').length;
    if (numberOfChecked > 0) {
        $(".massActionsButton").removeAttr("disabled");
    }
    else {
        $(".massActionsButton").attr("disabled", "disabled");
    }
    $('#apply_btn').text('(' + numberOfChecked + ')Apply');
    $('.massCount').text(numberOfChecked);
}

/**
 * Open edit pop up for all pages
 * @param {type} target
 * @returns {undefined}
 */
function openEditPopup(target) {
    $("#action_modal .modal-content").load(target, function() {
        $("#action_modal").modal("show");
    });
}

/**
 * exportData - method
 * @param {type} selectedFieldsName
 * @param {type} controller
 * @returns {undefined}
 */
function exportData(selectedFieldsName, controller) {

    /* Get selected records id */
    var selectedRecordsids = getSelectedValues(selectedFieldsName);
    var applyAllFlag = $('#apply-all-flag').val();
    var whereStatementId = $('#where-statement-id').val();
    var selectedCheckFlag = true;
    if (selectedRecordsids.length < 1 && applyAllFlag == 'no') {
        selectedCheckFlag = false;
    }

    if (selectedCheckFlag) {

        $('#custom_error_msg span').text('');
        $('#custom_error_msg').hide();

        $('<form action="/' + controller + '/export_data" method="POST"/>')
                .append($('<input type="hidden" name="selectedRecordsIds" value="' + selectedRecordsids + '">'))
                .append($('<input type="hidden" name="apply-all-flag" value="' + applyAllFlag + '">'))
                .append($('<input type="hidden" name="where-statement-cond" value="' + whereStatementId + '">'))
                .appendTo($(document.body)) //it has to be added somewhere into the <body>
                .submit();

    } else {

        $('html, body').animate({scrollTop: 0}, 'slow');
        $('#custom_error_msg span').text('Please select at least one record to proceed.');
        $('#custom_error_msg').show();
    }
}


function updateSystemSettings(urlData, formId, btnId)
{
    var data = $("#"+formId).serialize();
    
    $.ajax({
            url: urlData,
            type: 'POST',
            data: data,
            beforeSend: function() {
                $('.update-system-notifications').css('opacity', 0.5);
                $('#'+btnId).button('loading');
            },
            complete: function() {
                $('.update-system-notifications').css('opacity', 1);
                $('#'+btnId).button('reset');
            },
            success: function(result) 
            {
                $(".update-system-notifications > .alert").remove();
                $(".update-system-notifications").prepend(result.json_msg);
            }            
        });
}