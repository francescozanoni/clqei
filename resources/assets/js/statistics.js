/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */
'use strict';

// require('./statistics_bootstrap');

var HighchartsBarFactory = require('./Charts/Highcharts/Factories/Bar');
// var HighchartsPieFactory = require('./Charts/Highcharts/Factories/Pie');
var HighchartsStackedBarFactory = require('./Charts/Highcharts/Factories/StackedBar');

/**
 * Render a chart
 *
 * @param {DomElement} chartContainerDomElement
 * @param {Object} data chart data (it must contain question, answers and labels items)
 */
window.renderChart = function(chartContainerDomElement, data) {

    // Question/answers data is extracted from data input prameter.
    var question = data['question'];
    var answers = data['answers'];
    var labels = data['labels'];

    // Chart type is chosen according to the number of different answers.
    if (Object.keys(answers).length > 5) {
        HighchartsBarFactory.create(chartContainerDomElement, question['id'], answers, labels);
    } else {
        HighchartsStackedBarFactory.create(chartContainerDomElement, question['id'], answers, labels);
    }

    // Pie chart is currently unused
    // HighchartsPieFactory.create(chartContainerDomElement, question['id'], answers, labels);

};
