/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */

require('./statistics_bootstrap');

/**
 * Format statistics of a single question for Highcharts pie chart
 *
 * @param {Array} data
 * @returns {Array}
 */
window.formatHighchartsPie = function (data) {

    var formattedData = [];

    for (var answer in data) {
        if (data.hasOwnProperty(answer) === false) {
            continue;
        }
        formattedData.push({
            name: answer,
            y: data[answer] // count of answer occurrences
        });
    }

    return formattedData;
};

/**
 * Create a Highcharts pie chart
 *
 * @param {HTMLDOMElement} domElement HTML tag that contains the chart
 * @param {String} question
 * @param {Array} data answers with statistics
 * @param {Object} labels localized chart labels
 */
window.createHighchartsPie = function (domElement, question, data, labels) {

    Highcharts.chart(
        domElement,
        {
            chart: {type: 'pie'},
            title: {text: question},
            tooltip: {pointFormat: labels['Compilations'] + ': <b>{point.y}</b>'},
            plotOptions: {
                pie: {
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                colorByPoint: true,
                data: data
            }]
        }
    );

};
