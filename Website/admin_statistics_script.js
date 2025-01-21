// We set today's date as the maximum value selectable in both inputs.
const startDtTag = document.getElementById("start-date-line-graph");
const endDtTag = document.getElementById("end-date-line-graph");
startDtTag.max = new Date().toISOString().split("T")[0];
endDtTag.max = startDtTag.max;
endDtTag.value = endDtTag.max;
updateLineGraph();
updateMonthsBarChart();
/* ********************************************************************************** */
/* We must now ensure that the end date of the interval is chronologically later than */                         
/* the start date, and if that does not happen then we set the dates to the same day. */
/* ********************************************************************************** */
function fixDate() {
    let startDt = startDtTag.value;
    let endDt = endDtTag.value;
    if (dateDiffInDays(new Date(startDt), new Date(endDt)) < 0) {
        startDtTag.value = endDtTag.value;
    }
    fixStep();    
}
function dateDiffInDays(date1, date2) {
    const _MS_PER_DAY = 1000 * 60 * 60 * 24;
    diffTime = date2.getTime() - date1.getTime();
    return Math.floor(diffTime / _MS_PER_DAY);
}
 
/* *************************************************************************************************** */
/* This time we will have to ensure that, if a monthly step is selected, there is a difference of      */
/* at least one month between the start and end date of the inspection. The same applies to the annual */ 
/* step. The daily one will not have to be verified as the dates are at least set to the same day.     */
/* *************************************************************************************************** */
function fixStep() {
    let startDt = new Date(startDtTag.value);
    let endDt = new Date(endDtTag.value);
    let monthlyOptionTag = document.querySelector("#step-line-graph > option:nth-child(2)");
    let yearlyOptionTag = document.querySelector("#step-line-graph > option:nth-child(3)");
    let daysDifference = endDt.getDate() - startDt.getDate();
    let monthsDifference = endDt.getMonth() - startDt.getMonth();
    let yearsDifference = endDt.getFullYear() - startDt.getFullYear();
    if (yearlyOptionTag.selected) {
        if (yearsDifference > 1 || (yearsDifference == 1 && monthsDifference > 0) || (yearsDifference == 1 && monthsDifference == 0 && daysDifference >= 0)) {
            // We are sure that there is at least a 1-year difference, so we leave the selection.
            updateLineGraph();
            return;
        }
        // We are sure that there is NOT at least 1 year difference, so we change the selection to daily.
        updateLineGraph();
        document.querySelector("#step-line-graph option:first-child").selected = "selected";
    } else if (monthlyOptionTag.selected) {
        if (yearsDifference > 1 || (yearsDifference == 1 && (startDt.getMonth() != 11 || endDt.getMonth() != 0)) || (yearsDifference == 1  && startDt.getMonth() == 11 && endDt.getMonth() == 0 && daysDifference >= 0) || (yearsDifference == 0 && monthsDifference > 0 && daysDifference >= 0)) {
            // We are sure that there is at least a 1-month difference, so we leave the selection.
            updateLineGraph();
            return
        }
        // We are sure that there is NOT at least 1 month difference, so we change the selection to daily.
        updateLineGraph();
        document.querySelector("#step-line-graph option:first-child").selected = "selected";
    }
}

/* ******************************************************************************************* */
/* We associate this function to the ‘change’ event of both <input> tags of the radio          */
/* button, because unlike what one might expect, when an option loses the selection the change */
/* event is not triggered, but only when it receives it, so it would not be called twice.      */
/* ******************************************************************************************* */
function changeBarGraph() {    
    if (document.getElementById("bar-chart-group-by-month").checked) {
        document.getElementById("bar-chart-years-profit").style.display = "none";
        document.getElementById("bar-chart-months-profit").style.display = "block";
        document.getElementById("year-for-months-analysis").style.display = "inline";
        document.querySelector("label[for='year-for-months-analysis'").style.display = "inline";
        document.getElementById("heading-group-by-month").style.display = "block";
        document.getElementById("heading-group-by-year").style.display = "none";
        document.querySelector("main > section:nth-of-type(2) > p:first-of-type").style.display = "inline";
        document.querySelector("main > section:nth-of-type(2) > p:nth-of-type(2)").style.display = "inline";
        document.querySelector("main > section:nth-of-type(2) > p:nth-of-type(3)").style.display = "none";
        document.querySelector("main > section:nth-of-type(2) > p:nth-of-type(4)").style.display = "none";
    } else {
        document.getElementById("bar-chart-years-profit").style.display = "block";
        document.getElementById("bar-chart-months-profit").style.display = "none";
        document.getElementById("year-for-months-analysis").style.display = "none";
        document.querySelector("label[for='year-for-months-analysis'").style.display = "none";
        document.getElementById("heading-group-by-month").style.display = "none";
        document.getElementById("heading-group-by-year").style.display = "block";
        document.querySelector("main > section:nth-of-type(2) > p:first-of-type").style.display = "none";
        document.querySelector("main > section:nth-of-type(2) > p:nth-of-type(2)").style.display = "none";
        document.querySelector("main > section:nth-of-type(2) > p:nth-of-type(3)").style.display = "inline";
        document.querySelector("main > section:nth-of-type(2) > p:nth-of-type(4)").style.display = "inline";
    }
}

/* **************************************************************************************************************************** */
/* On the page we have two graphs that must change dynamically depending on the input provided by the user when consulting      */
/* the statistics: the line graph, where the start/end dates and step can be altered, and the monthly bar graph, where the      */
/* year can be switched. We make sure that the generation of these 2 graphs is encapsulated in a function that we call whenever */ 
/* we register a change in the HTML controls (as well as of course at the time the page is loaded for the first time).          */
/* **************************************************************************************************************************** */
function updateLineGraph() {
    const xValuesLine = ["5-03-2021","6-03-2021","7-03-2021","8-03-2021","9-03-2021","10-03-2021","11-03-2021","12-03-2021","13-03-2021","14-03-2021","15-03-2021"];
    const yValuesLine = [700,800,850,950,800,900,700,950,650,700,800];
    new Chart("line-graph-day-profit", {
        type: "line",
        data: {
            labels: xValuesLine,
            datasets: [{
                fill: false,
                backgroundColor:"#006BA6",
                borderColor: "#006BA6",
                data: yValuesLine
            }]
        },
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Euro (€)'
                    }
                }]
            }
        }
    });
}

function updateMonthsBarChart() {
    const xValuesBar1 = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const yValuesBar1 = [55, 49, 44, 24, 15, 55, 49, 44, 24, 15, 77, 10];
    const barColors1 = ["#332288", "#6699cc", "#88ccee", "#44aa99", "#117733", "#999933", "#ddcc77", "#661100", "#cc6677", "#aa4466", "#882255", "#aa4499"];
    new Chart("bar-chart-months-profit", {
        type: "bar",
        data: {
            labels: xValuesBar1,
            datasets: [{
                backgroundColor: barColors1,
                data: yValuesBar1
            }]
        },
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Euro (€)'
                    }
                }]
            }
        }
    });
}

const xValuesBar2 = ["2020", "2021", "2022", "2023", "2024"];
const yValuesBar2 = [55, 49, 44, 24, 15];
const barColors2 = ["#781c81", "#447cbf", "#83ba6d", "#dbab3b", "#d92120"];
new Chart("bar-chart-years-profit", {
    type: "bar",
    data: {
        labels: xValuesBar2,
        datasets: [{
            backgroundColor: barColors2,
            data: yValuesBar2
        }]
    },
    options: {
        legend: {display: false},
        scales: {
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Euro (€)'
                }
            }]
        }
    }
});

const xValuesDoughnut1 = ["Adidas", "New Balance", "Fila", "Puma", "Others"];
const yValuesDoughnut1 = [40, 25, 15, 15, 5];
const barColors3 = ["#8B1E3F", "#3C153B", "#89BD9E", "#F0C987", "#DB4C40"];
new Chart("doughnut-chart-brand-sales-number", {
    type: "doughnut",
    data: {
        labels: xValuesDoughnut1,
        datasets: [{
            backgroundColor: barColors3,
            data: yValuesDoughnut1
        }]
    },
    options: {
    }
});

const xValuesDoughnut2 = ["Adidas", "New Balance", "Fila", "Puma", "Others"];
const yValuesDoughnut2 = [40, 25, 15, 15, 5];
const barColors4 = ["#8B1E3F", "#3C153B", "#89BD9E", "#F0C987", "#DB4C40"];
new Chart("doughnut-chart-brand-sales-value", {
    type: "doughnut",
    data: {
        labels: xValuesDoughnut2,
        datasets: [{
            backgroundColor: barColors4,
            data: yValuesDoughnut2
        }]
    },
    options: {
    }
});

var xValuesPie1 = ["Nike Air Force 1", "Adidas Samba", "Nike Dunk Low", "Adidas Gazelle", "Others"];
var yValuesPie1 = [40, 25, 15, 15, 5];
var barColors5 = ["#b91d47", "#00aba9", "#2b5797", "#e8c3b9", "#1e7145"];
new Chart("pie-chart-model-sales-number", {
    type: "pie",
    data: {
        labels: xValuesPie1,
        datasets: [{
            backgroundColor: barColors5,
            data: yValuesPie1
        }]
    },
    options: {
    }
});

var xValuesPie2 = ["Nike Air Force 1", "Adidas Samba", "Nike Dunk Low", "Adidas Gazelle", "Others"];
var yValuesPie2 = [40, 25, 15, 15, 5];
var barColors6 = ["#b91d47", "#00aba9", "#2b5797", "#e8c3b9", "#1e7145"];
new Chart("pie-chart-model-sales-value", {
    type: "pie",
    data: {
        labels: xValuesPie2,
        datasets: [{
            backgroundColor: barColors6,
            data: yValuesPie2
        }]
    },
    options: {
    }
});