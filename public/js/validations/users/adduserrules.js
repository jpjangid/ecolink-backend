$(document).ready(function() {
    $("#addData").validate({
        rules: {
            name: {
                required: true
            },
            company_name: {
                required: true
            },
            email: {
                required: true,
                email: true,
                maxlength: 50
            },
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10,
                number: true
            },
            password: {
                required: true
            },
            role_id: {
                required: true
            },
            address: {
                required: true
            },
            landmark: {
                required: true
            },
            city: {
                required: true,
                maxlength: 40
            },
            state: {
                required: true,
                maxlength: 40
            },
            country: {
                required: true,
                maxlength: 40
            },
            pincode: {
                required: true,
                maxlength: 8
            },
            tax_exempt: {
                required: true
            },
            profile_image: {
                required: true,
                extension: "jpg|jpeg|png|svg|gif"
            }
        },
        messages: {
            name: {
                required: "Please Enter Name",
            },
            company_name: {
                required: "Please Enter Company Name",
            },
            email: {
                required: "Please Enter Email is required",
                email: "Email must be a valid email address",
                maxlength: "Email cannot be more than 50 characters",
            },
            mobile: {
                required: "Please Enter Mobile No. is required",
                minlength: "Mobile No. must be of 10 digits",
                maxlength: "Mobile No. must be of 10 digits"
            },
            password: {
                required: "Please Enter Password is required",
            },
            role_id: {
                required: "Please Enter Please select the Role",
            },
            tax_exempt: {
                required: "Please Enter Please select the Tax Exempt",
            },
            address: {
                required: "Please Enter Address",
            },
            landmark: {
                required: "Please Enter Landmark",
            },
            city: {
                required: "Please Enter City",
                maxlength: "City cannot be more than 40 characters",
            },
            state: {
                required: "Please Enter State",
                maxlength: "State cannot be more than 40 characters",
            },
            country: {
                required: "Please Enter Country",
                maxlength: "Country cannot be more than 40 characters",
            },
            pincode: {
                required: "Please Enter Zip Code",
                maxlength: "Zip Code cannot be more than 8 characters",
            },
            profile_image: {
                required: "Please upload file profile image",
                extension: "Please upload file in these format only (jpg, jpeg, png, svg, gif)."
            }
        }
    });
});