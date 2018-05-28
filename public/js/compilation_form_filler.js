/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */

/**
 * Compilation form is filled with random value, except date fields.
 */
function fillForm() {

    try {

        var form = $('.panel-body form');

        // Random compilation of select boxes.
        form.find('select').each(function () {
            var options = $(this).find('option');
            var selectValue = '';
            while (typeof selectValue === 'undefined' || selectValue === '') {
                selectValue = options[Math.floor(Math.random() * options.length)].value;
            }
            $(this).val(selectValue).change();
        });

        // Random compilation of radio buttons.
        form.find('input[type=radio]').each(function () {
            var options = form.find('input[type=radio][name=' + this.name + ']');
            options[Math.floor(Math.random() * options.length)].checked = true;
        });

        // Random compilation of checkboxes.
        form.find('input[type=checkbox]').each(function () {
            this.checked = false;
            var options = form.find('input[type=checkbox][name="' + this.name + '"]');
            options[Math.floor(Math.random() * options.length)].checked = true;
        });

    } catch (e) {
        alert(e);
    }

}
