$(document).ready(function() {
    $("#addData").validate({
        rules: {
            title: {
                required: true
            },
            slug: {
                required: true
            },
            status: {
                required: true
            },
            description: {
                required: true
            },
        },
        messages: {
            title: {
                required: "Please Enter Page Title",
            },
            slug: {
                required: "Please Enter Page Slug",
            },
            status: {
                required: "Please Select Publish Page Status",
            },
            description: {
                required: "Please Enter Detail Description",
            },
        }
    });
});