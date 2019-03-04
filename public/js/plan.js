$(document).ready( function(){



  // var myChart = new Chart(ctx, {
  //   type: 'line',
  //   data:
  // });

  pullDataToChart();

});

function pullDataToChart() {
  // Using the core $.ajax() method
  $.ajax({

    // The URL for the request
    url: "/plan/"+$('#planId').val()+"/datapull",

    // Whether this is a POST or GET request
    type: "GET",

    // The type of data we expect back
    dataType : "json",
  })
  // Code to run if the request succeeds (is done);
  // The response is passed to the function
  .done(function( json ) {
    console.log(json);
    createChart(json);
  })
  // Code to run if the request fails; the raw request and
  // status codes are passed to the function
  .fail(function( xhr, status, errorThrown ) {
    // alert( "Sorry, there was a problem!" );
    console.log( "Error: " + errorThrown );
    console.log( "Status: " + status );
    console.dir( xhr );
  })
  // Code to run regardless of success or failure;
  .always(function( xhr, status ) {
    // alert( "The request is complete!" );
  });
}

function createChart(chartData) {
  console.log(chartData);
  var ctx = $('#dataChart');
  var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: chartData.x,
    datasets: [{
          data: chartData.y,
          borderColor: 'rgba(22, 34, 255, 0.9)',
          backgroundColor: 'rgba(22, 34, 255, 0.9)',
          fill: false,
          label: chartData.label
        },
        {
          data: chartData.regression,
          borderColor: 'rgba(0, 0, 0, 0.3)',
          backgroundColor: 'rgba(0, 0, 0, 0.3)',
          fill: false,
          label: 'Regression'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });
}
