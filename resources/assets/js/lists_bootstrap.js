/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */

try {

    window.$ = window.jQuery = require('jquery');

    window.moment = require('moment');

    require('datatables.net');
    require('datatables.net-bs');
    require('datatables.net-buttons');
    require('datatables.net-buttons-bs');

} catch (e) {

    alert(e);

}
