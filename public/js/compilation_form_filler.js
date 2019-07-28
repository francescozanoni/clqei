/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */

/**
 * @see http://jsfiddle.net/t94778xt/
 */
function randomDate(fromDate, toDate) {

    var from_ts = new Date(fromDate).getTime();
    var to_ts   = new Date(toDate).getTime();
    var fDate   = new Date(Math.floor(Math.random() * (to_ts - from_ts)) + from_ts);
    
    return fDate.toISOString().substring(0, 10);
    
}

/**
 * @see https://community.apigee.com/questions/51354/how-to-write-a-javascript-for-subtracting-days-fro.html
 */
function nDaysAgoDate(days) {

    var myDate = new Date();
    myDate.setDate(myDate.getDate() - days);
    
    return myDate.toISOString().substring(0, 10);
    
}

/**
 * Compilation form is filled with random value, except date fields.
 */
function fillForm() {

    try {

        var form = $(".panel-body form");

        // Random compilation of select boxes.
        form.find("select").each(function () {
            var options = $(this).find("option");
            var selectValue = "";
            while (typeof selectValue === "undefined" || selectValue === "") {
                selectValue = options[Math.floor(Math.random() * options.length)].value;
            }
            $(this).val(selectValue).change();
        });

        // Random compilation of radio buttons.
        form.find("input[type=radio]").each(function () {
            var options = form.find("input[type=radio][name=" + this.name + "]");
            options[Math.floor(Math.random() * options.length)].checked = true;
        });

        // Random compilation of checkboxes.
        form.find("input[type=checkbox]").each(function () {
            this.checked = false;
            var options = form.find("input[type=checkbox][name=\"" + this.name + "\"]");
            options[Math.floor(Math.random() * options.length)].checked = true;
        });
        
        // Random compilation of date fields.
        form.find("input[type=date]").each(function () {
            var fromDate = nDaysAgoDate(60);
            var toDate = (new Date()).toISOString().substring(0, 10); // today
            $(this).val(randomDate(fromDate, toDate));
        });

    } catch (e) {
        alert(e);
    }

}
