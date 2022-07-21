$(document).ready(function() {
    $("#editBlogForm").validate({
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
            description: {
                required: "Please Enter Detail Description",
            },
        }
    });
});