/**
 * Created by Francesco.Zanoni on 28/05/2018.
 */
"use strict";

// require('./statistics_bootstrap');

var HighchartsBarFactory = require("./Charts/Highcharts/Factories/Bar");
// var HighchartsPieFactory = require('./Charts/Highcharts/Factories/Pie');
var HighchartsStackedBarFactory = require("./Charts/Highcharts/Factories/StackedBar");

/**
 * Render a chart
 *
 * @param {DomElement} chartContainerDomElement
 * @param {Object} data chart data (it must contain question, answers and labels items)
 */
window.renderChart = function (chartContainerDomElement, data) {

    // Question/answers data is extracted from data input prameter.
    var question = data["question"];
    var answers = data["answers"];
    var labels = data["labels"];

    // Chart type is chosen according to the number of different answers.
    if (Object.keys(answers).length > 5) {
        HighchartsBarFactory.create(chartContainerDomElement, question["id"], answers, labels);
    } else {
        HighchartsStackedBarFactory.create(chartContainerDomElement, question["id"], answers, labels);
    }

    // Pie chart is currently unused
    // HighchartsPieFactory.create(chartContainerDomElement, question["id"], answers, labels);

};

/**
 * Add a question and its answers to filter modal
 *
 * @param {jQuery} modalBody
 * @param {Object} data chart data (it must contain question, answers and labels items)
 * @param {Object} urlParameters
 */
window.addFilterToModal = function (modalBody, data, urlParameters) {

    var question = data["question"];
    var answers = data["answers"];
    var labels = data["labels"];

    modalBody.append(
        '<div class="clearfix row">' +
        '    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">' + question["text"] + '</div>' +
        '    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' +
        '        <select name="' + question["id"] + '" style="width: 100%">' +
        '            <option></option>' +
        '        </select>' +
        '    </div>' +
        '</div>'
    );
    for (var answerId in answers) {
        if (answers.hasOwnProperty(answerId) === false) {
            continue;
        }
        modalBody.find("select:last").append('<option value="' + answerId + '">');
        modalBody.find("option:last").append(labels[answerId] + ' (' + answers[answerId] + ')');
        if (urlParameters[question["id"]] === answerId) {
            modalBody.find("option:last").prop("selected", "selected");
        }
    }

};
