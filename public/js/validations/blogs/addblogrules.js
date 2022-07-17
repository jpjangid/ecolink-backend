$(document).ready(function() {
    $("#addData").validate({
        rules: {
            title: {
                required: true
            },
            slug: {
                required: true
            },
            alt: {
                required: true
            },
            category: {
                required: true
            },
            publish_date: {
                required: true
            },
            status: {
                required: true
            },
            image: {
                required: true
            },
            description: {
                required: true
            },
        },
        messages: {
            title: {
                required: "Please Enter Blog Title",
            },
            slug: {
                required: "Please Enter Blog Slug",
            },
            alt: {
                required: "Please Enter Alt Title",
            },
            category: {
                required: "Please Select Blog Category",
            },
            publish_date: {
                required: "Please Select Blog Publish Date",
            },
            status: {
                required: "Please Select Publish Blog Status",
            },
            image: {
                required: "Please Select Featured Image",
            },
            description: {
                required: "Please Enter Detail Description",
            },
        }
    });
});