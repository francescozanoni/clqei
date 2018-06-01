/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */

require('./statistics_bootstrap');

/**
 * Highcharts pie chart factory
 */
HighchartsPieFactory = {

    /**
     * Format statistics of a single question and localize answer texts
     *
     * @param {String} questionId
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     * @returns {Array} e.g. [{"name": "Novara", "y": 82}, {"name": "Vercelli", "y": 11}, ...]
     */
    format: function (questionId, data, labels) {

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
     * @param {String} questionId
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     */
    create: function (domElement, questionId, data, labels) {

        Highcharts.chart(
            domElement,
            {
                chart: {type: 'pie'},
                title: null,
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
                    data: this.format(questionId, data, labels)
                }],
                credits: {enabled: false}
            }
        );

    }

};


/**
 * Highcharts stacked bar chart factory
 */
HighchartsStackedBarFactory = {

    /**
     * Format statistics of a single question and localize answer texts
     *
     * @param {String} questionId
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     * @returns {Array} e.g. [{"name": "Novara", "data": [82]}, {"name": "Vercelli", "data": [11]}, ...]
     */
    format: function (questionId, data, labels) {

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
     * @param {String} questionId
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     */
    create: function (domElement, questionId, data, labels) {

        Highcharts.chart(
            domElement,
            {
                chart: {type: 'bar'},
                title: null,
                xAxis: {visible: false},
                yAxis: {
                    min: 0,
                    title: {text: ''},
                    labels: {format: '{value} %'}
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
                    itemWidth: 260,
                    itemStyle: {
                        fontWeight: 'auto',
                        textOverflow: ''
                    },
                    style: {
                        'white-space': 'wrap'
                    }
                },
                series: this.format(questionId, data, labels),
                credits: {enabled: false}
            }
        );

    }

};

/**
 * Highcharts bar chart factory
 */
HighchartsBarFactory = {

    /**
     * Format statistics of a single question and localize answer texts
     *
     * @param {String} questionId
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     * @returns {Array} e.g. [["Novara", 82], ["Vercelli", 11], ...]
     */
    format: function (questionId, data, labels) {

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
     * @param {String} questionId
     * @param {Object} data answers with statistics, e.g. {"14": 82, "21": 11, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", "21": "Vercelli", ...}
     */
    create: function (domElement, questionId, data, labels) {

        Highcharts.chart(
            domElement,
            {
                chart: {type: 'bar'},
                title: null,
                xAxis: {
                    title: null,
                    type: 'category',
                    labels: {
                        style: {
                            width: 150,
                            'text-align': 'right'
                        }
                    }
                },
                yAxis: {
                    title: null,
                    tickInterval: 1
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: false
                        },
                        pointWidth: 20
                    }
                },
                legend: {enabled: false},
                series: [{
                    name: labels['Compilations'],
                    colorByPoint: true,
                    data: this.format(questionId, data, labels)
                }],
                credits: {enabled: false}
            }
        );

    }

};
