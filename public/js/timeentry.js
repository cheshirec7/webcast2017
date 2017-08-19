(function (exports, $) {
    $(function () {
        "use strict";
        let checktimes_arr, pull_arr, racers_arr, user_roles_arr, global_obj = {},
            $racerno_text = $('.racerno-text'),
            $modal_hour_title = $('.modal-title', '#modal-hour'),
            $modal_select_racer_number = $('#modal-select-racer-number').on('shown.bs.modal', function (e) {
                $modal_select_racer_number_invalid_racer_number_msg.addClass('hidden');
                $modal_select_racer_number_control.val('').focus();
            }),
            $modal_select_racer_number_invalid_racer_number_msg = $('#modal-select-racer-number-invalid-racer-number-msg'),
            $modal_select_racer_number_control = $('#modal-select-racer-number-control', $modal_select_racer_number).keydown(function (e) {
                $modal_select_racer_number_invalid_racer_number_msg.addClass('hidden');
                return isNumber(e);
            }),
            $times_display_form = $("#times-display-form"),
            $times_display_form_racer_results_body = $('#times-display-form-racer-results-body'),
            $time12 = $("#time12", $times_display_form).click(function (e) {
                global_obj.use_12_hour_time = 1;
                displayChecktimes();
            }),

            $time24 = $("#time24", $times_display_form).click(function (e) {
                global_obj.use_12_hour_time = 0;
                displayChecktimes();
            }),

            $modal_select_racer_number_keypad_buttons = $("button", "#modal-select-racer-number-keypad").click(function (e) {
                e.stopPropagation();
                let new_val = this.innerHTML,
                    old_val = $modal_select_racer_number_control.val();
                $modal_select_racer_number_invalid_racer_number_msg.addClass('hidden');
                if ((new_val === '0' && old_val === '') || new_val === 'CLR') {
                    new_val = '';
                }
                else if (new_val === 'DEL') new_val = old_val.substring(0, old_val.length - 1);
                else if (old_val.length < 3) new_val = old_val + new_val;
                else new_val = old_val;
                $modal_select_racer_number_control.val(new_val).focus();
            }),

            $select_racer_form = $('#select-racer-form').submit(function (e) {
                e.preventDefault();
                let i, found = false,
                    racerno = parseInt($modal_select_racer_number_control.val());
                if (isNaN(racerno) || racerno <= 0) {
                    displayInvalidMsg(racerno);
                    return;
                }
                for (i = 0; i < racers_arr.length; i++) {
                    if (racers_arr[i].racer_no === racerno) {
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    displayInvalidMsg(racerno);
                    return;
                }
                global_obj.selected_racer_no = racerno;
                loadRacerData();
                return false;
            }),

            /*** time entry form ***/
            $time_entry_form = $("#time-entry-form").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/admin/checktimes',
                    type: 'POST',
                    data: {
                        racer_no: global_obj.selected_racer_no,
                        cid: global_obj.selected_cid,
                        check_type: global_obj.selected_checktype,
                        hour: global_obj.selected_hour,
                        min: global_obj.selected_minutes,
                        check_time_order: $time_entry_form_finish_order_select.val(),
                        use_12_hour_time: global_obj.use_12_hour_time,
                        sortorder: global_obj.selected_sortorder
                    }
                }).done(function (data) {
                    if (!Array.isArray(data)) {
                        location.reload();
                    } else {
                        checktimes_arr = data;
                        updateChecktimesArray();
                        showResults(true);
                    }
                });
                return false;
            }),

            $time_entry_form_in_or_out = $('#time-entry-form-in-or-out', $time_entry_form),
            $time_entry_form_ampm = $('#time-entry-form-ampm', $time_entry_form),
            $time_entry_form_1224_time_text = $('#time-entry-form-1224-time-text', $time_entry_form),
            $time_entry_form_chkpt = $("#time-entry-form-chkpt", $time_entry_form),
            $time_entry_form_show_order = $('#time-entry-form-show-order', $time_entry_form),
            $time_entry_form_btn_hour = $('#time-entry-form-btn-hour', $time_entry_form),
            $time_entry_form_btn_min = $('#time-entry-form-btn-min', $time_entry_form),
            $time_entry_form_btn_submit = $('#time-entry-form-btn-submit', $time_entry_form),
            $time_entry_form_finish_order_select = $('#time-entry-form-finish-order-select', $time_entry_form).change(function (e) {
                updateSaveUpdateButtonStatus();
            }),

            $time_entry_form_btn_delete = $('#time-entry-form-btn-delete').click(function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'DELETE',
                    url: '/admin/checktimes/' + global_obj.selected_racer_no,
                    data: {
                        cid: global_obj.selected_cid,
                        check_type: global_obj.selected_checktype
                    }
                }).done(function (data) {
                    if (!Array.isArray(data)) {
                        location.reload();
                    } else {
                        checktimes_arr = data;
                        updateChecktimesArray();
                        showResults(true);
                    }
                });
                return false;
            }),

            $time_entry_form_btn_cancel = $('#time-entry-form-btn-cancel').click(function (e) {
                e.preventDefault();
                showResults(false);
            }),
            /*** end time entry form ***/

            /*** pull racer form ***/
            $pull_racer_form = $("#pull-racer-form").submit(function (e) {
                e.preventDefault();
                let pull_id = 0;
                if (pull_arr && pull_arr.length) {
                    pull_id = pull_arr[0].id;
                }
                $.ajax({
                    type: 'POST',
                    url: '/admin/pulls',
                    data: {
                        pull_id: pull_id,
                        racer_no: global_obj.selected_racer_no,
                        checkpoint_id: $pull_racer_form_checkpoint_select.val(),
                        status_id: $pull_racer_form_reason_select.val(),
                        remarks: $pull_racer_form_remarks_ta.val().trim(),
                        pull_dest: $pull_racer_form_dest_ta.val().trim(),
                        use_12_hour_time: global_obj.use_12_hour_time
                    }
                }).done(function (data) {
                    if (data && data.hasOwnProperty('error') && !data.error) {
                        loadRacerData();
                        showResults(false);
                    } else {
                        location.reload();
                    }
                });
                return false;
            }),

            $pull_racer_form_btn_submit = $('#pull-racer-form-btn-submit', $pull_racer_form),
            $pull_racer_form_btn_delete = $('#pull-racer-form-btn-delete', $pull_racer_form).click(function (e) {
                e.preventDefault();
                let pull_id = 0;
                if (pull_arr && pull_arr.length) pull_id = pull_arr[0].id;
                $.ajax({
                    type: 'DELETE',
                    url: '/admin/pulls/' + pull_id
                }).done(function (data) {
                    if (data && data.hasOwnProperty('error') && !data.error) {
                        loadRacerData();
                        showResults(false);
                    } else {
                        location.reload();
                    }
                });
                return false;
            }),

            $pull_racer_form_btn_cancel = $('#pull-racer-form-btn-cancel', $pull_racer_form).click(function (e) {
                e.preventDefault();
                showResults(false);
            }),

            $pull_racer_form_reason_select = $("#pull-racer-form-reason-select", $pull_racer_form).change(function () {
                updatePullSaveUpdateButtonStatus();
            }),

            $pull_racer_form_checkpoint_select = $("#pull-racer-form-checkpoint-select", $pull_racer_form).change(function () {
                updatePullSaveUpdateButtonStatus();
            }),

            $pull_racer_form_remarks_ta = $('#pull-racer-form-remarks-ta', $pull_racer_form),
            $pull_racer_form_dest_ta = $('#pull-racer-form-dest-ta', $pull_racer_form),
            /*** end pull racer form ***/

            $modal_hour = $('#modal-hour'),
            $keypad_hour_table = $('#keypad-hour-table', $modal_hour),

            /*** modal minutes ***/
            $modal_minutes = $('#modal-minutes').on('shown.bs.modal', function (e) {
                $('.modal-title', $modal_minutes).text('Select Minutes - Rider #' + global_obj.selected_racer_no);
            }),

            $keypad_minute_buttons = $('button', '#keypad-min-table').click(function (e) {
                $time_entry_form_btn_min.text(this.innerHTML);
                global_obj.selected_minutes = parseInt(this.innerHTML);
                updateSaveUpdateButtonStatus();
                $modal_minutes.modal('hide');
            }),
            /*** end modal minutes ***/

            /*** dynamic ***/
            $document = $(document).on("click", ".btn-timeentry", function (e) {
                let $this = $(this),
                    checktime_obj = null,
                    first_hour = 0,
                    last_hour = 0,
                    buttons = '<tr>',
                    hour = 0,
                    hour_val = 0,
                    idx = 0,
                    btn_ampm = '',
                    cnt = 0,
                    check_time_order = 1;

                global_obj.selected_cid = $this.data('cid');
                global_obj.selected_checktype = $this.data('checktype');
                global_obj.selected_checktimes_arr_idx = $this.data('arridx');
                global_obj.selected_sortorder = $this.data('sortorder');

                $time_entry_form_in_or_out.text(global_obj.selected_checktype);
                $time_entry_form_ampm.text('');
                $time_entry_form_btn_submit.prop('disabled', true);
                if (global_obj.selected_checktimes_arr_idx >= 0) {
                    checktime_obj = checktimes_arr[global_obj.selected_checktimes_arr_idx];
                    if (global_obj.use_12_hour_time) {
                        $time_entry_form_btn_hour.text(checktime_obj.check_time.format("h"));
                        $time_entry_form_ampm.text(checktime_obj.check_time.format("a"));
                    } else {
                        $time_entry_form_btn_hour.text(checktime_obj.check_time.format("HH"));
                        $time_entry_form_ampm.text('');
                    }
                    $time_entry_form_btn_min.text(checktime_obj.check_time.format("mm"));
                    global_obj.selected_hour = checktime_obj.check_time.hour();
                    global_obj.selected_minutes = checktime_obj.check_time.minute();
                    $time_entry_form_chkpt.text(checktime_obj.checkpoint_name);
                    $time_entry_form_btn_submit.text('Update');
                    $time_entry_form_btn_delete.removeClass('hidden');
                    check_time_order = checktime_obj.check_time_order;
                } else {
                    checktime_obj = getCheckpointObjFromID(global_obj.selected_cid);
                    $time_entry_form_btn_hour.text('---');
                    $time_entry_form_btn_min.text('---');
                    $time_entry_form_chkpt.text(checktime_obj.checkpoint_name);
                    $time_entry_form_btn_submit.text('Save');
                    $time_entry_form_btn_delete.addClass('hidden');
                    global_obj.selected_hour = -1;
                    global_obj.selected_minutes = -1;
                }

                $time_entry_form_finish_order_select.val(check_time_order);
                if ((checktime_obj.in_time_show_ordering && global_obj.selected_checktype === 'IN') || (checktime_obj.out_time_show_ordering && global_obj.selected_checktype === 'OUT')) {
                    $time_entry_form_show_order.removeClass('hidden');
                } else {
                    $time_entry_form_show_order.addClass('hidden');
                }

                if (global_obj.use_12_hour_time) {
                    $time_entry_form_1224_time_text.text('Time (12-hr)');
                    $modal_hour_title.text("Select Hour (12-hr)");
                } else {
                    $time_entry_form_1224_time_text.text('Time (24-hr)');
                    $modal_hour_title.text("Select Hour (24-hr)");
                }
                if (global_obj.selected_checktype === 'IN') {
                    first_hour = checktime_obj.in_time_first_hour;
                    last_hour = checktime_obj.in_time_last_hour;
                } else {
                    first_hour = checktime_obj.out_time_first_hour;
                    last_hour = checktime_obj.out_time_last_hour;
                }
                if (last_hour < first_hour) {
                    last_hour += 24;
                }
                for (idx = first_hour; idx <= last_hour; idx++) {
                    hour = idx;
                    hour_val = idx;
                    if (hour_val > 23) hour_val -= 24;
                    if (global_obj.use_12_hour_time === 0) {
                        if (hour > 23) hour -= 24;
                        if (hour < 10) hour = '0' + hour;
                    } else {
                        if (hour === 24) hour = 12;
                        else if (hour > 24) hour -= 24;
                        else if (hour > 12) hour -= 12;
                    }
                    if (hour_val < 12) {
                        btn_ampm = 'am';
                    } else {
                        btn_ampm = 'pm';
                    }
                    buttons += '<td><button type="button" data-ampm="' + btn_ampm + '" data-hour="' + hour_val + '" class="btn btn-default btn-lg">' + hour + '</button></td>';
                    cnt += 1;
                    if (cnt % 4 === 0) {
                        buttons += '</tr><tr>';
                        cnt = 0;
                    }
                }
                buttons += '</tr>';
                $keypad_hour_table.html(buttons);
                $times_display_form.addClass("hidden");
                $time_entry_form.removeClass("hidden");
            }).on("click", "#btn-pull", function (e) {
                if (!pull_arr || pull_arr.length === 0) {
                    $pull_racer_form_reason_select.prop('selectedIndex', 0);
                    $pull_racer_form_checkpoint_select.prop('selectedIndex', 0);
                    $pull_racer_form_remarks_ta.val('');
                    $pull_racer_form_dest_ta.val('');
                    $pull_racer_form_btn_submit.prop('disabled', true).text('Save');
                    $pull_racer_form_btn_delete.addClass('hidden');
                } else {
                    $pull_racer_form_reason_select.val(pull_arr[0].status_id);
                    $pull_racer_form_checkpoint_select.val(pull_arr[0].checkpoint_id);
                    $pull_racer_form_remarks_ta.val(pull_arr[0].remarks);
                    $pull_racer_form_dest_ta.val(pull_arr[0].pull_dest);
                    $pull_racer_form_btn_submit.prop('disabled', false).text('Update');
                    $pull_racer_form_btn_delete.removeClass('hidden');
                }
                $times_display_form.addClass("hidden");
                $pull_racer_form.removeClass("hidden");
            }).on("click", "#keypad-hour-table button", function (e) {
                let $this = $(this);
                $time_entry_form_btn_hour.text($this.text());
                global_obj.selected_hour = parseInt($this.data('hour'));
                if (global_obj.use_12_hour_time) {
                    $time_entry_form_ampm.text($this.data('ampm'));
                }
                updateSaveUpdateButtonStatus();
                $modal_hour.modal('hide');
            });

        function getCheckpointObjFromID(cid) {
            let idx = 0,
                checktimes_length = checktimes_arr.length;
            while (idx < checktimes_length) {
                if (checktimes_arr[idx].id === cid)
                    return (checktimes_arr[idx]);
                idx++;
            }
            return 'not found';
        }

        function showResults(reRender) {
            if (reRender) {
                displayChecktimes();
            }
            $times_display_form.removeClass("hidden");
            $time_entry_form.addClass("hidden");
            $pull_racer_form.addClass("hidden");
        }

        function updateSaveUpdateButtonStatus() {
            if ($time_entry_form_btn_hour.text() !== '---' && $time_entry_form_btn_min.text() !== '---') {
                $time_entry_form_btn_submit.prop('disabled', false);
            }
        }

        function updatePullSaveUpdateButtonStatus() {
            let i = parseInt($pull_racer_form_reason_select.val()),
                j = parseInt($pull_racer_form_checkpoint_select.val()),
                state = (i === 0 || j === 0);
            $pull_racer_form_btn_submit.prop('disabled', state);
        }

        function isNumber(event) {
            if (event) {
                let charCode = (event.which) ? event.which : event.keyCode;
                if (charCode !== 190 && charCode > 31 && (charCode < 48 || charCode > 57) &&
                    (charCode < 96 || charCode > 105) && (charCode < 37 || charCode > 40) &&
                    charCode !== 110 && charCode !== 8 && charCode !== 46)
                    return false;
            }
            return true;
        }

        function displayInvalidMsg(racer_no) {
            $modal_select_racer_number_invalid_racer_number_msg.text('Invalid Rider #' + racer_no).removeClass('hidden');
            $modal_select_racer_number_control.val('').focus();
        }

        function displayPull(param_obj) {
            let res = '';
            if (pull_arr.length === 0) {
                res = '<tr><th colspan="3" class="bkgStatusOK text-center">Current Rider Status</th></tr>';
                res += '<tr><td class="txtStatusOK">OK</td>';
                res += '<td colspan="2" class="text-center"><button id="btn-pull" data-pullid="0" data-cid="0" type="button" class="btn btn-sm btn-default">Pull This Rider</button></td></tr>';
            } else {
                res += '<tr><th colspan="3" class="bkgStatusPULL text-center">Current Rider Status: PULLED</th></tr>';
                res += '<tr><td colspan="2"><span class="txtStatusPULL">Pulled at&nbsp;&nbsp;</span>' + pull_arr[0].checkpoint_name + ' (' + pull_arr[0].description + ')</td>';
                res += '<td><button id="btn-pull" type="button" class="btn btn-default btn-sm">Update</button></td></tr>';
                res += '<tr><td colspan="3"><span class="txtStatusPULL">Destination&nbsp;&nbsp;</span>' + pull_arr[0].pull_dest + '</td></tr>';
                res += '<tr><td colspan="3"><span class="txtStatusPULL">Remarks&nbsp;&nbsp;</span>' + pull_arr[0].remarks + '</td></tr>';
                if (param_obj.times_after_pull) res += '<tr><th colspan="3" class="bkgStatusPULL text-center"><i>** Note: Times are entered after pull **</i></th></tr>';
            }
            return res;
        }

        function getButtonData(arr_idx, check_type, cid, miles_from_start) {
            let bdata = 'data-checktype=' + check_type;
            bdata += ' data-cid=' + cid;
            bdata += ' data-arridx=' + arr_idx;
            miles_from_start *= 1000;
            if (check_type === 'OUT')
                miles_from_start += 1;
            bdata += ' data-sortorder=' + miles_from_start;
            return bdata;
        }

        function getButton(check_type, checktime_obj, param_obj) {
            let res = '<td>',
                bdata = '';
            if (param_obj.can_edit) {
                if ((check_type === 'IN' && checktime_obj.allow_in_times) || (check_type === 'OUT' && checktime_obj.allow_out_times)) {
                    if (param_obj.pull_miles_from_start === -1 || param_obj.pull_miles_from_start >= checktime_obj.miles_from_start) {
                        bdata = getButtonData(-1, check_type, checktime_obj.id, checktime_obj.miles_from_start);
                        res += '<button type="button" ' + bdata + ' class="btn btn-default btn-sm btn-timeentry">' + check_type + '</button>';
                    }
                }
            }
            return res + '</td>';
        }

        function getTimeRow(idx, checktime_obj, param_obj) {
            let displaytime = '',
                time_error,
                text_color = 'textBlue',
                bdata,
                res;
            if (global_obj.use_12_hour_time)
                displaytime = checktime_obj.check_time.format("h:mm") + checktime_obj.check_time.format("a")[0];
            else
                displaytime = checktime_obj.check_time.format("HH:mm");
            if (param_obj.pull_miles_from_start !== -1 && param_obj.pull_miles_from_start < checktime_obj.miles_from_start)
                param_obj.times_after_pull = true;
            time_error = (param_obj.compare_check_time !== null && param_obj.compare_check_time.valueOf() >= checktime_obj.check_time.valueOf());
            param_obj.compare_check_time = checktime_obj.check_time;
            if (!param_obj.can_edit) {
                if (param_obj.times_after_pull || time_error) {
                    return '<div class="textRed">' + displaytime + '</div>';
                }
                else {
                    return displaytime;
                }
            }
            if (param_obj.times_after_pull || time_error) {
                text_color = 'textRed';
            }
            bdata = getButtonData(idx, checktime_obj.check_type, checktime_obj.id, checktime_obj.miles_from_start);
            res = '<button type="button" ' + bdata + ' class="btn btn-default btn-sm ' + text_color + ' btn-timeentry">' + displaytime + '</button>';
            return res;
        }

        function updateChecktimesArray() {
            let idx = 0,
                checktimes_length = checktimes_arr.length;
            while (idx < checktimes_length) {
                //TODO parseFloat needed when JSON_NUMERIC_CHECK not used
                checktimes_arr[idx].miles_from_start = parseFloat(checktimes_arr[idx].miles_from_start);
                if (checktimes_arr[idx].check_time)
                    checktimes_arr[idx].check_time = moment(checktimes_arr[idx].check_time, "YYYY-MM-DD HH:mm:ss");
                if (!checktimes_arr[idx].check_time_order)
                    checktimes_arr[idx].check_time_order = 1;
                idx++;
            }
        }

        function updatePullArray() {
            if (pull_arr && pull_arr.length > 0)
                pull_arr[0].miles_from_start = parseFloat(pull_arr[0].miles_from_start);
        }

        function displayChecktimes() {
            let idx = 0,
                num_shown = 0,
                checktime_obj, res = '',
                cell = '',
                param_obj = {},
                checktimes_length = checktimes_arr.length,
                showAllCheckpoints = user_roles_arr[0].checkpoint_id === 0;

            param_obj.pull_miles_from_start = -1;
            if (pull_arr && pull_arr.length > 0)
                param_obj.pull_miles_from_start = pull_arr[0].miles_from_start;

            param_obj.times_after_pull = false;
            param_obj.compare_check_time = null;
            if (!showAllCheckpoints) {
                while (idx < checktimes_length) {
                    if (checktimes_arr[idx].id === user_roles_arr[0].checkpoint_id)
                        break;
                    idx++;
                }
                idx -= 3;
                if (idx < 0) {
                    idx = 0;
                }
            }

            while (idx < checktimes_length) {
                checktime_obj = checktimes_arr[idx];
                param_obj.can_edit = user_roles_arr[0].checkpoint_id === 0 || user_roles_arr[0].checkpoint_id === checktime_obj.id;
                res += '<tr>';
                res += '<td>[' + checktime_obj.checkpoint_code + '] ' + checktime_obj.checkpoint_name + ' (' + checktime_obj.num_in + '/' + checktime_obj.num_out + '/' + checktime_obj.num_pull + ')</td>';
                if (global_obj.selected_racer_no <= 0) {
                    res += '<td></td><td></td>';
                } else {
                    if (checktime_obj.check_time) {
                        cell = '<td>' + getTimeRow(idx, checktime_obj, param_obj) + '</td>';
                        if (idx + 1 < checktimes_length && checktimes_arr[idx + 1].checkpoint_code === checktime_obj.checkpoint_code) {
                            idx++;
                            checktime_obj = checktimes_arr[idx];
                            res += cell + '<td>' + getTimeRow(idx, checktime_obj, param_obj) + '</td>';
                        } else if (checktime_obj.check_type === 'IN') {
                            res += cell + getButton('OUT', checktime_obj, param_obj);
                        } else {
                            res += getButton('IN', checktime_obj, param_obj) + cell;
                        }
                    } else {
                        res += getButton('IN', checktime_obj, param_obj) + getButton('OUT', checktime_obj, param_obj);
                    }
                }
                res += '</tr>';
                idx++;
                num_shown++;
                if (!showAllCheckpoints && num_shown === 7) {
                    break;
                }
            }
            if (global_obj.selected_racer_no > 0) {
                res += displayPull(param_obj);
            }
            $times_display_form_racer_results_body.html(res);
        }

        function loadRacerData() {
            $.ajax({
                url: '/api/getcheckpointswithtimes/' + global_obj.selected_racer_no,
                type: 'GET',
                dataType: 'json'
            }).done(function (data) {
                if (!Array.isArray(data)) {
                    location.reload();
                } else {
                    checktimes_arr = data;
                    updateChecktimesArray();
                    if (global_obj.selected_racer_no > 0) {
                        $.ajax({
                            url: '/api/getpull/' + global_obj.selected_racer_no,
                            type: 'GET',
                            dataType: 'json'
                        }).done(function (data) {
                            if (!Array.isArray(data)) {
                                location.reload();
                            } else {
                                pull_arr = data;
                                updatePullArray();
                                displayChecktimes();
                                $racerno_text.text(global_obj.selected_racer_no);
                                $modal_select_racer_number.modal('hide');
                            }
                        });
                    } else {
                        pull_arr = null;
                        displayChecktimes();
                        $racerno_text.text('- - -');
                    }
                }
            })
        }

        global_obj.selected_racer_no = $modal_select_racer_number_control.val();
        global_obj.use_12_hour_time = 0;
        global_obj.selected_cid = 0;
        global_obj.selected_checktype = '';
        global_obj.selected_checktimes_arr_idx = 0;
        global_obj.selected_hour = 0;
        global_obj.selected_minutes = 0;
        global_obj.selected_sortorder = 0;

        // forgot what I was trying to do here??
        // history.pushState(null, null, $(location).attr('href'));
        // window.addEventListener('popstate', function () {
        //     history.pushState(null, null, $(location).attr('href'));
        // });

        $.ajax({
            type: 'GET',
            url: '/api/getracernumbers',
            dataType: 'json'
        }).done(function (data) {
            if (!Array.isArray(data)) {
                location.reload();
            } else {
                racers_arr = data;
                $.ajax({
                    type: 'GET',
                    url: '/api/getuserroles/'+window.user_id,
                    dataType: 'json'
                }).done(function (data) {
                    if (!Array.isArray(data)) {
                        location.reload();
                    } else {
                        user_roles_arr = data;
                        if ($time12.is(":checked")) {
                            global_obj.use_12_hour_time = 1;
                        }
                        loadRacerData();
                    }
                });
            }
        });
    })
}(this, jQuery));