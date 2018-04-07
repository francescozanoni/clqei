/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

// https://stackoverflow.com/questions/5691054/disable-submit-button-on-form-submit/5691065
$('form').submit(function () {
    $(this).find(':input[type=submit]').prop('disabled', true);
});