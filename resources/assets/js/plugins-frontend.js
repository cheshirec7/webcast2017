/**
 * Place any jQuery/helper plugins in here.
 */
$(function(){
    let $loading = $('.loader');

    $(document).ajaxStart(function () {
        $loading.show();
    }).ajaxError(function (event, jqxhr, settings, thrownError) {
        //console.log(event);
        $loading.hide();
        location.reload();
    }).ajaxStop(function () {
        $loading.hide();
    });

    /**
     * Place the CSRF token as a header on all pages for access in AJAX requests
     */
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    /**
     * Bind all bootstrap tooltips & popovers
     */
    //$("[data-toggle='tooltip']").tooltip();
    //$("[data-toggle='popover']").popover();
});
