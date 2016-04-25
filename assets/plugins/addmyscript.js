var btn = $('#btn_reply_action');

    btn.click(function() {
        btn.button('loading');

        
        var form_data = $("#form_reply_action").serialize();
        
        var fnCallback = function(result) {
            if (result.success == false) {
                $(".modal-body1").prepend(result.json_msg);
                btn.button('reset');
            } else {
                $(".modal-body1").prepend(result.json_msg);
                btn.button('reset');
                //oTable.draw();

                setTimeout(function() {
                    $('#btn_create_cancel_modal1').click();
                }, 1000);

            }
            $('#action_modal').animate({scrollTop: 0}, 'slow');
        }

        $.ajax({
            'dataType': 'json',
            'type': 'POST',
            'url': action_url,
            'data': form_data,
            'success': fnCallback
        });
    });
