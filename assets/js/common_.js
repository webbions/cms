$(document).ready(function (){
    
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


    /* Set page length */
    $('#example2_length').change(function() {
        oTable.page.len(this.value).draw();
    });
    
});
/**
 *  massCallBack method
 * @param {type} response
 * @returns {undefined}         
 **/
function massCallBack(response) {
    /*$("#where-statement-id").val(response.whereStatement);
    var numberOfChecked = response.iTotalDisplayRecords;
    if (numberOfChecked > 0) {
        $('.all-mass-actions').removeAttr("disabled");
    }
    else {
        $('.all-mass-actions').attr("disabled", "disabled");
    }
    ;
    $(".allResults").text(numberOfChecked);*/

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
