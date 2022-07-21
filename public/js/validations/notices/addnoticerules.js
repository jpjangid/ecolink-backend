$(document).ready(function() {
    $("#addData").validate({
        rules: {
            title: {
                required: true
            },
            status: {
                required: true
            },
            message: {
                required: true
            },
        },
        messages: {
            title: {
                required: "Please Enter Title",
            },
            status: {
                required: "Please Select Status",
            },
            message: {
                required: "Please Enter Message",
            },
        }
    });
});