$(document).ready(function() {
    // Attach a submit event handler to all forms with the class 'ajax-form'
    $(document).on('submit', '.ajax-form', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var $form = $(this); // Reference to the form

        $.ajax({
            url: $form.attr('action'), // Use the form's action attribute for the URL
            type: 'POST',
            data: $form.serialize(), // Serialize form data
            dataType: 'json', // Expect a JSON response
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    }).then(function() {
                        // Optionally redirect or clear the form
                        if (response.redirect) {
                            window.location.href = response.redirect; // Redirect if provided
                        }
                    });
                } else if (response.status === 'error' || response.status === 'warning') {
                    Swal.fire({
                        icon: response.status,
                        title: response.status.charAt(0).toUpperCase() + response.status.slice(1) + '!',
                        text: response.message
                    });
                }
            },
            error: function() {
                // Handle error
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was a problem with the submission.'
                });
            }
        });
    });
});
