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
    $.ajaxSetup({
        error: function (xhr, status, error) {
            let alert = this;
            if (this.data !== '') {
                if (this.data.indexOf('sitemap_generator=1') >= 0) {
                    alert = $('.alert-message.alert-sitemap-generator');
                } else if (this.data.indexOf('submit_sitemap=1') >= 0) {
                    alert = $('.alert-message.alert-sitemap-submit-button');
                } else if (this.data.indexOf('sitemap_generator_url=1') >= 0) {
                    alert = $('.alert-message.alert-sitemap-generator-url');
                } else if (this.data.indexOf('submit_sitemap_generator_url=1') >= 0) {
                    alert = $('.alert-message.alert-sitemap-generator-url-submit-button');
                }
            }
            let alertIcon = alert.find('.alert .alert-icon');
            let alertText = alert.find('.alert .text');
            alert.hide();
            alertIcon.hide();
            alertText.text();
            let response = xhr.responseJSON;
            if (response !== undefined && response.hasOwnProperty('status') && response.status) {
                alert.find('.alert').removeClass('alert-danger').addClass('alert-success');
                alertIcon.closest('.alert-success').show();
                alertIcon.closest('.alert-danger').hide();
            } else {
                alert.find('.alert').removeClass('alert-success').addClass('alert-danger');
                alertIcon.closest('.alert-danger').show();
                alertIcon.closest('.alert-success').hide();
            }
            if (error !== '') {
                alertText.html(status + ' ' + error);
                alert.show();
            }
        }
    });
    $('form input[type="checkbox"]').each(function () {
        $(this).val($(this).is(':checked') ? 1 : 0);
    });
    $('form input[type="checkbox"]').on("click", function () {
        $(this).val($(this).is(':checked') ? 1 : 0);
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
    $("form.sitemap-generator").submit(function (event) {
        event.preventDefault();
        let alert = $('.alert-message.alert-sitemap-generator');
        let alertIcon = alert.find('.alert .alert-icon');
        let alertText = alert.find('.alert .text');
        alert.hide();
        alertIcon.hide();
        alertText.text();
        let submit_sitemap_button = $('.sitemap-submit-button');
        submit_sitemap_button.hide();
        let alert_submit_button = $('.alert-message.alert-sitemap-submit-button');
        alert_submit_button.hide();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        var formData = $(this).serializeArray();
        //formData.find(input => input.name == 'file_path').value = 'test';
        formData.push({name: 'sitemap_generator', value: 1});
        $.ajax({
            url: url,
            type: method,
            data: formData,
            dataType: "JSON"
        }).done(function (response) {
            if (response.hasOwnProperty('message')) {
                alertText.html(response.message);
                alert.show();
            }
            if (response.hasOwnProperty('status')) {
                if (response.status) {
                    alert.find('.alert').removeClass('alert-danger').addClass('alert-success');
                    alertIcon.closest('.alert-success').show();
                    alertIcon.closest('.alert-danger').hide();
                    submit_sitemap_button.show();
                } else {
                    alert.find('.alert').removeClass('alert-success').addClass('alert-danger');
                    alertIcon.closest('.alert-danger').show();
                    alertIcon.closest('.alert-success').hide();
                }
            }
            if (response.data.hasOwnProperty('file_url')) {
                submit_sitemap_button.attr('data-sitemap-url', response.data.file_url)
            }
        });
    });
    $(".sitemap-submit-button").on("click", function (event) {
        event.preventDefault();
        let alert = $('.alert-message.alert-sitemap-submit-button');
        let alertIcon = alert.find('.alert .alert-icon');
        let alertText = alert.find('.alert .text');
        alert.hide();
        alertIcon.hide();
        alertText.text();
        var url = $(this).attr('href');
        var sitemap_url = $(this).data('sitemap-url');
        $.ajax({
            url: url,
            type: "POST",
            data: {'submit_sitemap': 1, 'sitemap_url': sitemap_url},
            dataType: "JSON"
        }).done(function (response) {
            if (response.hasOwnProperty('message')) {
                alertText.html(response.message);
                alert.show();
            }
            if (response.hasOwnProperty('data')) {
                if ($.isArray(response.data)) {
                    var list_html = '<ul class="list-group list-unstyled mt-3">';
                    $.each(response.data, function (key, value) {
                        list_html += '<li class="mb-3"><div><strong>URL</strong>: ' + value.url + '</div><div><strong>Response</strong>: ' + value.response + '</div></li>';
                    });
                    list_html += '</ul>';
                    alertText.append(list_html);
                }
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
    $("form.sitemap-generator-url").submit(function (event) {
        event.preventDefault();
        let alert = $('.alert-message.alert-sitemap-generator-url');
        let alertIcon = alert.find('.alert .alert-icon');
        let alertText = alert.find('.alert .text');
        alert.hide();
        alertIcon.hide();
        alertText.text();
        let submit_sitemap_button = $('.sitemap-generator-url-submit-button');
        submit_sitemap_button.hide();
        let alert_submit_button = $('.alert-message.alert-sitemap-generator-url-submit-button');
        alert_submit_button.hide();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        var formData = $(this).serializeArray();
        formData.push({name: 'sitemap_generator_url', value: 1});
        $.ajax({
            url: url,
            type: method,
            data: formData,
            dataType: "JSON",
        }).done(function (response) {
            if (response.hasOwnProperty('message')) {
                alertText.html(response.message);
                alert.show();
            }
            if (response.hasOwnProperty('status')) {
                if (response.status) {
                    alert.find('.alert').removeClass('alert-danger').addClass('alert-success');
                    alertIcon.closest('.alert-success').show();
                    alertIcon.closest('.alert-danger').hide();
                    submit_sitemap_button.show();
                } else {
                    alert.find('.alert').removeClass('alert-success').addClass('alert-danger');
                    alertIcon.closest('.alert-danger').show();
                    alertIcon.closest('.alert-success').hide();
                }
            }
            if (response.data.hasOwnProperty('file_url')) {
                submit_sitemap_button.attr('data-sitemap-url', response.data.file_url)
            }
        });
    });
    $(".sitemap-generator-url-submit-button").on("click", function (event) {
        event.preventDefault();
        let alert = $('.alert-message.alert-sitemap-generator-url-submit-button');
        let alertIcon = alert.find('.alert .alert-icon');
        let alertText = alert.find('.alert .text');
        alert.hide();
        alertIcon.hide();
        alertText.text();
        var url = $(this).attr('href');
        var sitemap_url = $(this).data('sitemap-url');
        $.ajax({
            url: url,
            type: "POST",
            data: {'submit_sitemap_generator_url': 1, 'sitemap_url': sitemap_url},
            dataType: "JSON"
        }).done(function (response) {
            if (response.hasOwnProperty('message')) {
                alertText.html(response.message);
                alert.show();
            }
            if (response.hasOwnProperty('data')) {
                if ($.isArray(response.data)) {
                    var list_html = '<ul class="list-group list-unstyled mt-3">';
                    $.each(response.data, function (key, value) {
                        list_html += '<li class="mb-3"><div><strong>URL</strong>: ' + value.url + '</div><div><strong>Response</strong>: ' + value.response + '</div></li>';
                    });
                    list_html += '</ul>';
                    alertText.append(list_html);
                }
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