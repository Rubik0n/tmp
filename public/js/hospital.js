$('#signInButton').click(function(){


    var login = $('#login').val().trim();
    var password = $('#password').val().trim();


    if (login == ''){
        
        $('#error').text('Введите логин');
        return false;
    }
    else if(password == ''){
        $('#error').text('Введите пароль');

        return false;
    }
    $('#error').text("");


    $.ajax({
        url: '/admin/test',
        type: 'POST',
        cache: false,
        data: {'login': login, 'password': password},

        beforeSend: function(){
            $('#signInButton').prop("disabled", true);
        },
        success: function(response){

            //alert(data.getResponseHeader("Location"));
            alert(response);
            //window.location.href = response.redirect;
            //location = response.getResponseHeader("Location");
            //location.href = "http://198.199.81.218/doctors/list";
            //window.location = data.getResponseHeader("Location");
            //window.location.href = data.redirect(data.getResponseHeader("Location"));

        }
        // complete: function(xhr)
        // {
        //     if (xhr.status == 302) {
        //         location.href = xhr.getResponseHeader("Location");
        //     }
        // }
    });
});

$('#uselessButton').click(function(){

    Swal.fire(
        'Good job!',
        'You clicked the useless button!',
        'success'
    );

});

$('#auth_button').click(function(){

    $('#window').slideDown(300);
    $('#outside').show();
    $('#outside').click(function(){
        $('#window').slideUp(300);
        $('#outside').hide();
    });
});

$('#outside').click(function(){

    $('#window').slideUp(300);
    $('#outside').hide();
});

