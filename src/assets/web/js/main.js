function checkInputNumberValue(element) {
    if (element.value != "") {
        if (parseFloat(element.value) < parseFloat(element.min)) {
            element.value = element.min;
        }
        if (parseFloat(element.value) > parseFloat(element.max)) {
            element.value = element.max;
        }
    } else {
        element.value = element.min;
    }
}

jQuery(function ($) {
    $('form input[type="checkbox"]').each(function () {
        $(this).val($(this).is(':checked') ? 1 : 0);
    });
    $('form input[type="checkbox"]').on("click", function () {
        $(this).val($(this).is(':checked') ? 1 : 0);
        let val = $(this).val();
    });
    $(".flatpickr").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
    });
    $('#http_secure').on("click", function () {
        let val = $(this).val();
        let input_domain = $('#domain');
        let input_domain_val = input_domain.val();
        if (input_domain_val.startsWith('http://')) {
            input_domain.val(input_domain_val.replace('http://', 'https://'));
        } else if (input_domain_val.startsWith('https://')) {
            input_domain.val(input_domain_val.replace('https://', 'http://'));
        } else {
            var http_secure = '';
            if (val == 1) {
                http_secure = 'https://';
            } else {
                http_secure = 'http://';
            }
            input_domain_val = http_secure + input_domain_val;
            input_domain.val(input_domain_val);
        }
    });
    $("form").submit(function (event) {
        event.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        var formData = $(this).serializeArray();
        //formData.find(input => input.name == 'file_path').value = 'test';
        formData.push({name: 'sitemap', value: 1});
        $.ajax({
            url: url,
            type: method,
            data: formData,
            dataType: "JSON"
        }).done(function (response) {
            let alert = $('.alert-message');
            let alertIcon = alert.find('.alert .alert-icon');
            let alertText = alert.find('.alert .text');
            alert.hide();
            alertIcon.hide();
            alertText.text();
            if (response.hasOwnProperty('message')) {
                alertText.html(response.message);
                alert.show();
            }
            if (response.hasOwnProperty('status')) {
                if (response.status) {
                    alert.find('.alert').removeClass('alert-danger').addClass('alert-success');
                    alertIcon.closest('.alert-success').show();
                    alertIcon.closest('.alert-danger').hide();
                } else {
                    alert.find('.alert').removeClass('alert-success').addClass('alert-danger');
                    alertIcon.closest('.alert-danger').show();
                    alertIcon.closest('.alert-success').hide();
                }
            }
        });
    });
});