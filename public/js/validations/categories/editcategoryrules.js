$(document).ready(function() {
    $("#addData").validate({
        rules: {
            name: {
                required: true
            },
            slug: {
                required: true
            },
            alt: {
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
            name: {
                required: "Please Enter Category Name",
            },
            slug: {
                required: "Please Enter Category Slug",
            },
            alt: {
                required: "Please Enter Alt Title",
            },
            status: {
                required: "Please Select Publish Category Status",
            },
            description: {
                required: "Please Enter Detail Description",
            },
        }
    });
});