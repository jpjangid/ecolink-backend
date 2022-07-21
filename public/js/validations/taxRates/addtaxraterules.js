$(document).ready(function() {
    $("#addData").validate({
        rules: {
            country_code: {
                required: true
            },
            state_code: {
                required: true
            },
            state_name: {
                required: true
            },
            city: {
                required: true
            },
            zip: {
                required: true
            },
            rate: {
                required: true
            },
        },
        messages: {
            country_code: {
                required: "Please Enter Country Code",
            },
            state_code: {
                required: "Please Enter State Code",
            },
            state_name: {
                required: "Please Enter State Name",
            },
            city: {
                required: "Please Enter City",
            },
            zip: {
                required: "Please Enter Zip",
            },
            rate: {
                required: "Please Enter Rate",
            },
        }
    });
});