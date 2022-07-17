$(document).ready(function() {
    $("#addData").validate({
        rules: {
            blog_category: {
                required: true
            }
        },
        messages: {
            blog_category: {
                required: "Please Enter Blog Category",
            }
        }
    });
});