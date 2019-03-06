var ctx;
var myChart;

$(document).ready( function(){

  if ($('.chart-container').length > 0) {
    pullDataToChart();
  }

  $('#dataPointEditSave').on('click', function(){
    saveDataPointEdit();
  });

  $('#toggleGraphView').on('click', function(){
    toggleGraphView();
  });

  $('.editDataPoint').on('click', function() {
    editDataPoint($(this).data('id'));
  });

  $('#bulkDataUpload').on('click', function(){
    $('#bulkDataUploadModal').modal('show');
  });

  $('#bulkDataUploadSubmit').on('click', function(event){
    //stop submit the form, we will post it manually.
        event.preventDefault();
        submitBulkDataUpload();
    });
});

function pullDataToChart() {
  // Using the core $.ajax() method
  $.ajax({
    url: "/plan/"+$('#planId').val()+"/datapull",
    type: "GET",
    dataType : "json"
  })
  // Code to run if the request succeeds (is done);
  // The response is passed to the function
  .done(function( json ) {
    console.log(json);
    createChart(json);
  });
}

function createChart(chartData) {
  console.log(chartData);
  ctx = $('#dataChart');
  myChart = new Chart(ctx, {
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
        },
        {
          data: chartData.expected,
          borderColor: 'rgba(204, 0, 0, 0.9)',
          backgroundColor: 'rgba(204, 0, 0, 0.9)',
          fill: false,
          label: 'Expected'
        }]
    },
    options: {
      spanGaps: true,
      responsive: true,
      maintainAspectRatio: false,
      onClick: function(evt) {
        // chartClicked(evt);
      }
    }
  });
}

function chartClicked(evt) {
  console.log(myChart.getElementAtEvent(evt));
  var index = myChart.getElementAtEvent(evt)[0]._index;
  $.ajax({
    url: "/plan/"+$('#planId').val()+"/editDataPoint/"+index,
    type: "GET",
    dataType : "json"
  })
  .done(function(json) {
    console.log(json);
    $('#editData').val(json.data);
    $('#editDataDate').val(json.date);
    $('#planDataId').val(json.planDataId);
    $('#dataPointEditModal').modal('show');
    $('#editDataDate').datepicker();
  });
}

function editDataPoint(dataPointId) {
  $.ajax({
    url: "/plan/"+$('#planId').val()+"/editDataPoint/"+dataPointId,
    type: "GET",
    dataType : "json"
  })
  .done(function(json) {
    console.log(json);
    $('#editData').val(json.data);
    $('#editDataDate').val(json.date);
    $('#planDataId').val(json.planDataId);
    $('#dataPointEditModal').modal('show');
    $('#editDataDate').datepicker();
  });
}

function saveDataPointEdit(evt) {
  $.ajax({
    url: "/plan/saveDataPointEdit",
    type: 'POST',
    dataType: 'json',
    data: $('#editDataPointForm').serialize()
  })
  .done(function(json){
    console.log(json);
    if (json.errors.length != 0) {
      $(json.errors).each(function(i, error){
        console.log(error)
        $('#dataPointEditModal .modalAlerts').append('<div class="alert alert-danger">'+error+'</div>');
      });
    } else {
      $('#dataPointEditModal').modal('hide');
      // pullDataToChart();
      window.location.reload();
    }
  });
}

function toggleGraphView() {
  console.log('toggling graph view');
  console.log($('.chart-container').hasClass('showing'));
  if ($('.chart-container').hasClass('showing')) {
    //Switch to the table view
    $('.chart-container').fadeOut(500, function(){
      $('.table-container').fadeIn();
      $('.chart-container').removeClass('showing');
      $('.table-container').addClass('showing');
    });
  } else {
    //Switch to the graph view
    $('.table-container').fadeOut(500, function(){
      $('.chart-container').fadeIn();
      $('.table-container').removeClass('showing');
      $('.chart-container').addClass('showing');
    });
  }
}

function submitBulkDataUpload() {
  // Get form
  var form = $('#bulkDataUploadForm')[0];

  // Create an FormData object
  var data = new FormData(form);

  $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: "/plan/submitBulkDataUpload",
      data: data,
      dataType: 'json',
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000,
      success: function (json) {
        if (json.errors.length != 0) {
          $(json.errors).each(function(i, error){
            $('#dataPointEditModal .modalAlerts').append('<div class="alert alert-danger">'+error+'</div>');
          });
        } else {
          window.location.reload();
        }
      }
  });
}
