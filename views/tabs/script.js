$(document).ready(function(){

    // Для того, чтобы запоминать открытую вкладку в куках.
    // Открывать ее при reload.

    changeTab(
        document.getElementById($.cookie('idOfTab')));
    $('ul.tabs li').click(function(){
        changeTab(this);
    })

    function changeTab(obj) {
        if (obj == null) {
            obj = $('#tabH1');
        }
        var tab_id = $(obj).attr('content-tab');

        $.cookie('idOfTab', $(obj).attr('id'),{
            expires: 1, path: '/'
        });

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(obj).addClass('current');
        $("#"+tab_id).addClass('current');
        }
})