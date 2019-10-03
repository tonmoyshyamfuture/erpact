$(function () {
         //------------- BAR CHART DASHBOARD --------------------
            var areaChartData = {
          labels: ["Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec","Jan","Feb","Mar" ],
          datasets: [
            {
              label: "Sales",
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: [65, 59, 80, 81, 56, 55, 40, 75, 80, 85, 88, 82]
            },
            {
              label: "Purchase",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [28, 48, 40, 19, 86, 27, 90, 50,65,75,60,85]
            }
          ]
        };

    var barData = {
                labels: ['Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
                    datasets: [
                        {
                            label: 'Sales',
                            fillColor: '#7c9cd5',
                            data:["100","110","120","90","85","110","130","90","95","80","100","105"]    
                        } 
                    ]
                };

                var barOptions = {
                    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                    scaleBeginAtZero : true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines : true,
                    //String - Colour of the grid lines
                    scaleGridLineColor : "rgba(0,0,0,.05)",
                    //Number - Width of the grid lines
                    scaleGridLineWidth : 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines: true,
                    //Boolean - If there is a stroke on each bar
                    barShowStroke : true,
                    //Number - Pixel width of the bar stroke
                    barStrokeWidth : 1,
                    //Number - Spacing between each of the X value sets
                    barValueSpacing : 10,
                    //Number - Spacing between data sets within X values
                    barDatasetSpacing : 0,
                    //String - A legend template
                    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
                }

    var context = document.getElementById('SalesPurchaseSummary').getContext('2d');
    var clientsChart = new Chart(context).Bar(areaChartData, barOptions);
    var context = document.getElementById('SalesSummary').getContext('2d');
    var clientsChart = new Chart(context).Bar(barData, barOptions);

  });  