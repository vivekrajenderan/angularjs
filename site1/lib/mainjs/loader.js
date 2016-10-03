$(document).ready(function () {
        $("input.switch").bootstrapSwitch();
        console.log("came");
        $('.switch').on('switchChange.bootstrapSwitch', function (event, state) {
            console.log("enter");
            var standing = 0;
            if (state == true)
            {
                standing = 1;
            }
            var pk_cust_id = $(this).attr('data-name');

            $.ajax({
                type: "POST",
                url: "admin/users/change_users_active",
                data: "standing=" + standing + "&pk_cust_id=" + pk_cust_id,
                dataType: 'json'
            }).done(function (response) {

                if (response.status == 1)
                {                    
                    $('#alert-success').show();
                    $('#alert-success').html(response.msg);
                    setTimeout(function () {
                        $('#alert-success').hide('slow');
                    }, 4000);
                }
                else
                {
                    $('#alert-error').show();
                    $('#alert-error').html(response.msg);
                    setTimeout(function () {
                        $('#alert-error').hide('slow');
                    }, 4000);
                }
            });
            return false;

        });
    });