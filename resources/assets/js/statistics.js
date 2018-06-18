/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */
'use strict';

// require('./statistics_bootstrap');

var HighchartsBarFactory = require('./Charts/Highcharts/Factories/Bar');
// var HighchartsPieFactory = require('./Charts/Highcharts/Factories/Pie');
var HighchartsStackedBarFactory = require('./Charts/Highcharts/Factories/StackedBar');

window.renderChart = function(chartContainerDomElement) {

    var chartContainerObject = $(chartContainerDomElement);

    var questionId = Object.keys(JSON.parse(chartContainerObject.find('.question').html()))[0];
    var data = JSON.parse(chartContainerObject.find('.answers').html());
    var labels = JSON.parse(chartContainerObject.find('.labels').html());

    // Chart type is chosen according to the number of different answers.
    if (Object.keys(data).length > 5) {
        HighchartsBarFactory.create(chartContainerDomElement, questionId, data, labels);
    } else {
        HighchartsStackedBarFactory.create(chartContainerDomElement, questionId, data, labels);
    }

    // Pie chart is currently unused
    // HighchartsPieFactory.create(chartContainerDomElement, questionId, data, labels);

};
