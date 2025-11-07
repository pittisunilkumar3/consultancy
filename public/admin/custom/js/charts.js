(function ($) {
    ("use strict");

    //event pie chart start
    var eventOptions = {
        series: JSON.parse($('#event-order-list').val()),
        chart: {
            height: 370,
            type: 'pie',
            redrawOnWindowResize: false,
            redrawOnParentResize: false,
        },
        labels: JSON.parse($('#event-name-list').val()),
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var eventChart = new ApexCharts(document.querySelector("#event-order-chart"), eventOptions);
    eventChart.render();
    //event pie chart end

    //consultant pie chart start
    var consultantOptions = {
        series: JSON.parse($('#consultant-order-list').val()),
        chart: {
            height: 370,
            type: 'pie',
            redrawOnWindowResize: false,
            redrawOnParentResize: false,
        },
        labels: JSON.parse($('#consultant-name-list').val()),
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var consultantChart = new ApexCharts(document.querySelector("#consultant-order-chart"), consultantOptions);
    consultantChart.render();
   //consultant pie chart end

    //service area chart start
    var serviceOptions = {
        series: [{
            name: "Service Orders",
            data: JSON.parse($('#yearly-chart-service-amount').val()),
        }],
        chart: {
            toolbar : {
                show : false,
            },
            type: 'area',
            height: 350,
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: JSON.parse($('#service-month-category-list').val()),
            type: 'category',
        },
        yaxis: {
            opposite: true
        },
        legend: {
            horizontalAlign: 'left'
        }
    };

    var serviceChart = new ApexCharts(document.querySelector("#service-order-chart"), serviceOptions);
    serviceChart.render();
   //service area chart end

    //course area chart start
    var courseOptions = {
        series: [{
            name: "Course Orders",
            data: JSON.parse($('#yearly-chart-course-amount').val()),
        }],
        chart: {
            toolbar : {
                show : false,
            },
            type: 'area',
            height: 350,
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: JSON.parse($('#course-month-category-list').val()),
            type: 'category',
        },
        yaxis: {
            opposite: true
        },
        legend: {
            horizontalAlign: 'left'
        }
    };

    var courseChart = new ApexCharts(document.querySelector("#course-order-chart"), courseOptions);
    courseChart.render();
    //course area chart end

})(jQuery);
