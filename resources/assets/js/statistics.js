/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */

require('./statistics_bootstrap');

/**
 * Highcharts pie chart factory
 */
window.HighchartsPieFactory = {

    /**
     * Format statistics of a single question and localize answer texts
     *
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     * @returns {Array} e.g. [{"name": "Novara", "y": 82}, {"name": "Vercelli", "y": 11}, ...]
     */
    format: function (data, labels) {

        var formattedData = [];

        for (var answer in data) {
            if (data.hasOwnProperty(answer) === false) {
                continue;
            }
            formattedData.push({
                name: labels[answer],
                y: data[answer] // count of answer occurrences
            });
        }

        return formattedData;
    },

    /**
     * Create a chart
     *
     * @param {HTMLDOMElement} domElement HTML tag that contains the chart
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     */
    create: function (domElement, data, labels) {

        Highcharts.chart(
            domElement,
            {
                chart: {type: 'pie'},
                title: {text: ''},
                tooltip: {pointFormat: labels['Compilations'] + ': <b>{point.y}</b>'},
                plotOptions: {
                    pie: {
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    colorByPoint: true,
                    data: this.format(data, labels)
                }],
                credits: {enabled: false}
            }
        );

    }

};


/**
 * Highcharts stacked bar chart factory
 */
window.HighchartsStackedBarFactory = {

    /**
     * Format statistics of a single question and localize answer texts
     *
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     * @returns {Array} e.g. [{"name": "Novara", "data": [82]}, {"name": "Vercelli", "data": [11]}, ...]
     */
    format: function (data, labels) {

        var formattedData = [];

        for (var answer in data) {
            if (data.hasOwnProperty(answer) === false) {
                continue;
            }
            formattedData.push({
                name: labels[answer],
                data: [data[answer]] // count of answer occurrences
            });
        }

        return formattedData;
    },

    /**
     * Create a chart
     *
     * @param {HTMLDOMElement} domElement HTML tag that contains the chart
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     */
    create: function (domElement, data, labels) {

        Highcharts.chart(
            domElement,
            {
                chart: {type: 'bar'},
                title: {text: ''},
                xAxis: {visible: false},
                yAxis: {
                    min: 0,
                    title: {text: ''}
                },
                plotOptions: {
                    series: {
                        stacking: 'percent'
                    }
                },
                tooltip: {
                    headerFormat: '',
                    pointFormat: '{series.name}: {point.y} ' + labels['Compilations'].toLowerCase()
                },
                legend: {
                    reversed: true,
                    align: 'right',
                    verticalAlign: 'middle',
                    layout: 'vertical',
                    itemWidth: 200
                },
                series: this.format(data, labels),
                credits: {enabled: false}
            }
        );

    }

};

/**
 * Highcharts bar chart factory
 */
window.HighchartsBarFactory = {

    /**
     * Format statistics of a single question and localize answer texts
     *
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     * @returns {Array} e.g. [["Novara", 82], ["Vercelli", 11], ...]
     */
    format: function (data, labels) {

        var formattedData = [];

        for (var answer in data) {
            if (data.hasOwnProperty(answer) === false) {
                continue;
            }
            formattedData.push([labels[answer], data[answer]]);
        }

        return formattedData;
    },

    /**
     * Create a chart
     *
     * @param {HTMLDOMElement} domElement HTML tag that contains the chart
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     */
    create: function (domElement, data, labels) {

        Highcharts.chart(
            domElement,
            {
                chart: {type: 'bar'},
                title: {text: ''},
                xAxis: {
                    title: null,
                    type: 'category'
                },
                yAxis: {
                    title: null,
                    tickInterval: 1
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: false
                        }
                    }
                },
                legend: {enabled: false},
                series: [{
                    name: labels['Compilations'],
                    data: this.format(data, labels)
                }],
                credits: {enabled: false}
            }
        );

    }

};
