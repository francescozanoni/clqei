/**
 * Created by Francesco.Zanoni on 11/06/2018.
 */
'use strict';

// Template object is always cloned.
var template = JSON.parse(JSON.stringify(require('../Template')));
var Highcharts = require('highcharts');

/**
 * Highcharts stacked bar chart factory
 */
module.exports = {

    /**
     * Format statistics of a single question and localize answer texts
     *
     * @param {String} questionId
     * @param {Object} data answers with statistics, e.g. {"14": 82, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", ...}
     * @returns {Array} e.g. [{"name": "Novara", "data": [82]}, ...]
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
     * @param {Object} data answers with statistics, e.g. {"14": 82, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", ...}
     */
    create: function (domElement, questionId, data, labels) {

        var config = template;

        config.chart.type = 'bar';
        config.xAxis = {visible: false};
        config.yAxis = {
            min: 0,
            title: {text: ''},
            labels: {format: '{value} %'}
        };
        config.plotOptions.series = {stacking: 'percent'};
        config.tooltip = {
            headerFormat: '',
            pointFormat: '{series.name}: {point.y} ' + labels['Compilations'].toLowerCase()
        };
        config.legend = {
            reversed: true,
            align: 'right',
            verticalAlign: 'middle',
            layout: 'vertical',
            itemWidth: 260,
            itemStyle: {
                fontWeight: 'auto',
                textOverflow: ''
            },
            style: {'white-space': 'wrap'}
        };
        config.series = this.format(questionId, data, labels);

        Highcharts.chart(domElement, config);

    }

};
