/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

// Disable submit button after form submission.
// https://stackoverflow.com/questions/5691054/disable-submit-button-on-form-submit/5691065
$('form').submit(function () {
    $(this).find(':input[type=submit]').prop('disabled', true);
});

/**
 * Get query string parameter value
 *
 * @param {String} name URL parameter name
 * @param {String|null} url URL (if empty, the current URL is used)
 * @returns {*}
 *
 * @see https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
 *
 * @example
 * query string: ?foo=lorem&bar=&baz
 * getUrlParameter('foo'); -> "lorem"
 * getUrlParameter('bar'); -> "" (present with empty value)
 * getUrlParameter('baz'); -> "" (present with no value)
 * getUrlParameter('qux'); -> null (absent)
 */
getUrlParameter = function (name, url) {
    if (!url) {
        url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
    var results = regex.exec(url);
    if (!results) {
        return null;
    }
    if (!results[2]) {
        return '';
    }
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
