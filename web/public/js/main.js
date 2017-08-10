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
                $('#contact_date').datepicker({
                    dateFormat:'yy-mm-dd'
                });
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
        $('#filter_user_list_start').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_user_list_end').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_cancel_start').datepicker({
            dateFormat:'yy-mm-dd'
        });
        $('#filter_cancel_end').datepicker({
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

    $(document).ready(function () {
        function exportTableToCSV($table, filename) {
            var $headers = $table.find('tr:has(th)')
                ,$rows = $table.find('tr:has(td)')
                ,tmpColDelim = String.fromCharCode(11)
                ,tmpRowDelim = String.fromCharCode(0)
                ,colDelim = '";"'
                ,rowDelim = '"\r\n"';
            var csv = '"';
            csv += formatRows($headers.map(grabRow));
            csv += rowDelim;
            csv += formatRows($rows.map(grabRow)) + '"';
            var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

            if (window.navigator.msSaveOrOpenBlob) {
                var blob = new Blob([decodeURIComponent(encodeURI(csv))], {
                    type: "text/csv;charset=utf-8;"
                });
                navigator.msSaveBlob(blob, filename);
            } else {
                $(this)
                    .attr({
                        'download': filename
                        ,'href': csvData
                        ,'target' : '_blank'
                    });
            }

            function formatRows(rows){
                return rows.get().join(tmpRowDelim)
                    .split(tmpRowDelim).join(rowDelim)
                    .split(tmpColDelim).join(colDelim);
            }

            function grabRow(i,row){

                var $row = $(row);
                var $cols = $row.find('td');
                if(!$cols.length) $cols = $row.find('th');

                return $cols.map(grabCol)
                    .get().join(tmpColDelim);
            }
            function grabCol(j,col){
                var $col = $(col),
                    $text = $col.text();

                return $text.replace('"', '""'); // escape double quotes

            }
        }

        $("#export").click(function (event) {
            var outputFile = 'export'
            var outputFile = window.prompt("What do you want to name your output file (Note: This won't have any effect on Safari)") || 'export';
            outputFile = outputFile.replace('.csv','') + '.csv'
            exportTableToCSV.apply(this, [$('table.table.table-bordered.table-striped.table-hover.table-responsive'), outputFile]);
        });
    });