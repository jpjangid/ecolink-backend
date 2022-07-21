$(document).ready(function() {
    $("#addData").validate({
        rules: {
            name: {
                required: true
            },
            code: {
                required: true
            },
            show_in_front: {
                required: true
            },
            type: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Please Enter Coupon Name",
            },
            code: {
                required: "Please Enter Coupon Code",
            },
            show_in_front: {
                required: "Please Select Publish Coupon Status",
            },
            type: {
                required: "Please Select Coupon Type",
            },
        }
    });
});