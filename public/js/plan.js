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
    $.each(json, function(element, value){
      value.x = Date(value.x);
    });
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
  var ctx = $('#dataChart');
  var myChart = new Chart(ctx, {
     type: 'line',
     data: chartData,
     options: {
       label: "Baby's First Chart"
     }
   });
}
