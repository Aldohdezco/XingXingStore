// Tabs
$('#tab-view').click(function(){
    $(this).addClass('active');
    $('#tab-register').removeClass('active');
    $('#view-section').show();
    $('#register-section').hide();
});
$('#tab-register').click(function(){
    $(this).addClass('active');
    $('#tab-view').removeClass('active');
    $('#register-section').show();
    $('#view-section').hide();
});

// Login / registro
$('#btn-login').click(function(){
    $(this).addClass('active'); 
    $('#btn-register').removeClass('active');
    $('#login-form').show(); 
    $('#register-form').hide();
});
$('#btn-register').click(function(){
    $(this).addClass('active'); 
    $('#btn-login').removeClass('active');
    $('#register-form').show(); 
    $('#login-form').hide();
});
