$(function () {
    'use strict';

    let $selInTimeFirstHour = $("select[name='in_time_first_hour']"),
        $selInTimeFirstMinute = $("select[name='in_time_first_minute']"),
        $selInTimeLastHour = $("select[name='in_time_last_hour']"),
        $selInTimeLastMinute = $("select[name='in_time_last_minute']"),
        $selOutTimeFirstHour = $("select[name='out_time_first_hour']"),
        $selOutTimeFirstMinute = $("select[name='out_time_first_minute']"),
        $selOutTimeLastHour = $("select[name='out_time_last_hour']"),
        $selOutTimeLastMinute = $("select[name='out_time_last_minute']"),

        $chkAllowInTimes = $("input[name='allow_in_times']").click(function (e) {
            resetIns(!$chkAllowInTimes.is(':checked'));
        }),
        $chkAllowOutTimes = $("input[name='allow_out_times']").click(function (e) {
            resetOuts(!$chkAllowOutTimes.is(':checked'));
        }),
        $chkInTimeShowOrdering = $("input[name='in_time_show_ordering']"),
        $chkOutTimeShowOrdering = $("input[name='out_time_show_ordering']");

    function resetIns(disabled) {
        $selInTimeFirstHour.prop('disabled', disabled).val(-1);
        $selInTimeFirstMinute.prop('disabled', disabled).val(-1);
        $selInTimeLastHour.prop('disabled', disabled).val(-1);
        $selInTimeLastMinute.prop('disabled', disabled).val(-1);
        $chkInTimeShowOrdering.prop('disabled', disabled);
    }

    function resetOuts(disabled) {
        $selOutTimeFirstHour.prop('disabled', disabled).val(-1);
        $selOutTimeFirstMinute.prop('disabled', disabled).val(-1);
        $selOutTimeLastHour.prop('disabled', disabled).val(-1);
        $selOutTimeLastMinute.prop('disabled', disabled).val(-1);
        $chkOutTimeShowOrdering.prop('disabled', disabled);
    }

    if (!$chkAllowInTimes.is(':checked')) {
        resetIns(true);
    }
    if (!$chkAllowOutTimes.is(':checked')) {
        resetOuts(true);
    }
});