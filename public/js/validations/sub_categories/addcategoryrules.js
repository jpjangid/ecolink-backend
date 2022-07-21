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
            image: {
                required: true
            },
            description: {
                required: true
            },
            parent_id: {
                required: true
            }
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
            image: {
                required: "Please Select Featured Image",
            },
            description: {
                required: "Please Enter Detail Description",
            },
            parent_id: {
                required: "Please Select Parent Category",
            },
        }
    });
});