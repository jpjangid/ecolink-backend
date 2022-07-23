$(document).ready(function() {
    $("#addData").validate({
        rules: {
            billing_name: {
                required: true
            },
            billing_mobile: {
                required: true
            },
            billing_email: {
                required: true
            },
            billing_address: {
                required: true
            },
            billing_country: {
                required: true
            },
            billing_state: {
                required: true
            },
            billing_city: {
                required: true
            },
            billing_zip: {
                required: true
            },
            shipping_name: {
                required: true
            },
            shipping_mobile: {
                required: true
            },
            shipping_email: {
                required: true
            },
            shipping_address: {
                required: true
            },
            shipping_country: {
                required: true
            },
            shipping_state: {
                required: true
            },
            shipping_city: {
                required: true
            },
            shipping_zip: {
                required: true
            },
            total_qty: {
                required: true
            },
            total_amt: {
                required: true
            },
        },
        messages: {
            billing_name: {
                required: "Please Enter Billing Name",
            },
            billing_mobile: {
                required: "Please Enter Billing Mobile No.",
            },
            billing_email: {
                required: "Please Enter Billing Email",
            },
            billing_address: {
                required: "Please Enter Billing Address",
            },
            billing_country: {
                required: "Please Enter Billing Country",
            },
            billing_state: {
                required: "Please Enter Billing State",
            },
            billing_city: {
                required: "Please Enter Billing City",
            },
            billing_zip: {
                required: "Please Enter Billing Zip",
            },
            shipping_name: {
                required: "Please Enter Shipping Name",
            },
            shipping_mobile: {
                required: "Please Enter Shipping Mobile No.",
            },
            shipping_email: {
                required: "Please Enter Shipping Email",
            },
            shipping_address: {
                required: "Please Enter Shipping Address",
            },
            shipping_country: {
                required: "Please Enter Shipping Country",
            },
            shipping_state: {
                required: "Please Enter Shipping State",
            },
            shipping_city: {
                required: "Please Enter Shipping City",
            },
            shipping_zip: {
                required: "Please Enter Shipping Zip",
            },
            total_qty: {
                required: "Please Enter Total Quantity",
            },
            total_amt: {
                required: "Please Enter Total Amount",
            },
        }
    });
});