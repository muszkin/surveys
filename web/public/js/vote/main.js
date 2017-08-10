/**
 * Created by muszkin on 07.11.16.
 */
var React = {
    selector:'.react',
    url: '',
    addEvent:function(){
        $(React.selector).on('click',function(){
            var survey = $(this);
            React.url = survey.data('url');
            $.get(React.url,function(data){
                $('#modal-content').html(data);
                $('.react-send').on('click',function(){
                    var url = $(this).data('url');
                    $.post(url,{'admin_comment':$('#admin_comment').val()},function(d){
                        if (d.success == 1){
                            survey.parent().html($('#admin_comment').val());
                        }
                    })
                });
            });
        })
    },
    init:function(){
        React.addEvent();
    }
};

var Cancel = {
    selector:'.cancel',
    url: '',
    addEvent:function(){
        $(Cancel.selector).on('click',function(){
            var survey = $(this);
            Cancel.url = survey.data('url');
            $.get(Cancel.url,function(data){
                console.log(data);
                $('.cancel-body').html(data);
                $('.cancel-send').on('click',function(){
                    var url = $(this).data('url');
                    var user_comment = $('#user_comment').val();
                    if (user_comment.length < 1){
                        survey.stopPropagation();
                        survey.preventDefault();
                        alert('Komentarz musi zostać uzupełniony');
                    }else {
                        $.post(url, {'user_comment': user_comment}, function (d) {
                            if (d.success == 1) {
                                survey.parent().html($('#user_comment').val());
                            }
                        })
                    }
                });
            });
        })
    },
    init:function(){
        Cancel.addEvent();
    }
};

var Resend = {
    selector:'.resend',
    url: '',
    addEvent:function(){
        $(Resend.selector).on('click',function(){
            var survey = $(this);
            Resend.url = survey.data('url');
            $.get(Resend.url,function(data){
                $('.resend-body').html(data);
                $('.resend-send').on('click',function(){
                    var url = $(this).data('url');
                    $.post(url,{
                        'user_comment':$('#user_comment').val(),
                        'contact':$('#contact').val(),
                        'contact_date':$('#contact_date').val()
                    },function(d){
                        if (d.success == 1){
                            survey.parent().html($('#user_comment').val());
                        }
                    })
                });
            });
        })
    },
    init:function(){
        Resend.addEvent();
    }
};
$(document).ready(function(){
    React.init();
    Cancel.init();
    Resend.init();
    if (parseInt($('#back').val()) == 1){
        setTimeout(function(){
            window.location.replace('https://www.shoper.pl/');
        },5000);
    }
    $( function() {
        $('#filter_cancel_start').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_cancel_end').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_user_list_start').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_user_list_end').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_survey_start').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_survey_end').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_self_user_survey_start').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_self_user_survey_end').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_user_survey_start').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_user_survey_end').datepicker({
            dateFormat:'yy-mm-dd'
        });
    } );
});