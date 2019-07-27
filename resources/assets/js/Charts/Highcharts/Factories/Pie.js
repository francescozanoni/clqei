/**
 * Created by Francesco.Zanoni on 11/06/2018.
 */
"use strict";

// Template object is always cloned.
var template = JSON.parse(JSON.stringify(require("../Template")));
var Highcharts = require("highcharts");

/**
 * Highcharts pie chart factory
 */
module.exports = {

    /**
     * Format statistics of a single question and localize answer texts
     *
     * @param {String} questionId
     * @param {Object} data answers with statistics, e.g. {"14": 82, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", ...}
     * @returns {Array} e.g. [{"name": "Novara", "y": 82}, ...]
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
     * @param {Object} data answers with statistics, e.g. {"14": 82, ...}
     * @param {Object} labels localized chart labels, e.g. {"Compilations": "Compilazioni", "14": "Novara", ...}
     */
    create: function (domElement, questionId, data, labels) {

        var config = template;

        config.chart.type = "pie";
        config.tooltip = {pointFormat: labels["Compilations"] + ": <b>{point.y}</b>"};
        config.plotOptions.pie = {
            cursor: "pointer",
            dataLabels: {
                enabled: true,
                format: "{point.name}: {point.percentage:.1f} %"
            }
        };
        config.legend = [{
            name: labels["Compilations"],
            colorByPoint: true,
            data: this.format(questionId, data, labels)
        }];
        config.series = [{
            colorByPoint: true,
            data: this.format(questionId, data, labels)
        }];

        Highcharts.chart(domElement, config);

    }

};
