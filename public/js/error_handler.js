/**
 * Created by Francesco.Zanoni on 29/05/2018.
 */

// https://stackoverflow.com/questions/951791/javascript-global-error-handling
window.onerror = function(message, url, line) {

    // You can view the information in an alert to see things working like this:
    alert(
        "Error: " + JSON.stringify(message) + "\n" +
        "URL: " + JSON.stringify(url) + "\n" +
        "line: " + line
    );

    // If you return true, then error alerts (like in older versions of
    // Internet Explorer) will be suppressed.
    return true;

};
