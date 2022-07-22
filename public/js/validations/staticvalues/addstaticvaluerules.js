$(document).ready(function() {
    $("#addData").validate({
        rules: {
            name: {
                required: true
            },
            type: {
                required: true
            },
            value: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Please Enter Name",
            },
            type: {
                required: "Please Enter Type",
            },
            value: {
                required: "Please Enter Value",
            },
        }
    });
});