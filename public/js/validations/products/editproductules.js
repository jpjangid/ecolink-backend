$(document).ready(function() {
    $("#addData").validate({
        rules: {
            name: {
                required: true
            },
            variant: {
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
            sku: {
                required: true
            },
            regular_price: {
                required: true
            },
            sale_price: {
                required: true
            },
            stock: {
                required: true
            },
            low_stock: {
                required: true
            },
            weight: {
                required: true
            },
            length: {
                required: true
            },
            width: {
                required: true
            },
            height: {
                required: true
            },
            short_desc: {
                required: true
            },
            category_id: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please Enter Product Name",
            },
            variant: {
                required: "Please Enter Product Variant",
            },
            slug: {
                required: "Please Enter Product Slug",
            },
            alt: {
                required: "Please Enter Alt Title",
            },
            status: {
                required: "Please Select Publish Product Status",
            },
            description: {
                required: "Please Enter Detail Description",
            },
            sku: {
                required: "Please Enter SKU",
            },
            regular_price: {
                required: "Please Enter Regular Price",
            },
            sale_price: {
                required: "Please Enter Sale Price",
            },
            stock: {
                required: "Please Enter Product Stock",
            },
            low_stock: {
                required: "Please Enter Product Low Stock",
            },
            weight: {
                required: "Please Enter Weight",
            },
            length: {
                required: "Please Enter Lenght",
            },
            width: {
                required: "Please Enter Width",
            },
            height: {
                required: "Please Enter Height",
            },
            short_desc: {
                required: "Please Enter Short Description",
            },
            category_id: {
                required: "Please Select Category",
            },
        }
    });
});