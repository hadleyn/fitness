var ctx;
var myChart;

$(document).ready( function(){

  if ($('.chart-container').length > 0) {
    createChart();
    pullDataToChart();
  }

  $('#dataPointEditSave').on('click', function(){
    saveDataPointEdit();
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

	$('#toggleRollingAverage').on('click', function(){
		toggleRollingAverage(this);
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
    var datasets = [{
          data: json.y,
          borderColor: 'rgba(22, 34, 255, 0.9)',
          backgroundColor: 'rgba(22, 34, 255, 0.9)',
          fill: false,
          label: json.label,
          borderWidth: 2
        },
        {
          data: json.regression,
          borderColor: 'rgba(0, 0, 0, 0.3)',
          backgroundColor: 'rgba(0, 0, 0, 0.3)',
          fill: false,
          label: 'Regression',
          borderWidth: 2
        },
        {
          data: json.expected,
          borderColor: 'rgba(204, 0, 0, 0.9)',
          backgroundColor: 'rgba(204, 0, 0, 0.9)',
          fill: false,
          label: 'Expected',
          borderWidth: 2
        }];
    updateChart(json.x, datasets);
  });
}

function createChart(chartData) {
  console.log(chartData);
  ctx = $('#dataChart');
  myChart = new Chart(ctx, {
  type: 'line',
  data: {},
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

function toggleRollingAverage(button) {
  if ($(button).hasClass('active')) {
    pullRollingAverageToChart();
  } else {
    pullDataToChart();
  }
}

function pullRollingAverageToChart() {
  $.ajax({
    type: 'get',
    dataType: 'json',
    url: '/plan/'+$('#planId').val()+'/rollingAverageDataPull'
  })
  .done(function(json){
    var datasets = [{
            data: json.y,
            borderColor: 'rgba(22, 34, 255, 0.9)',
            backgroundColor: 'rgba(22, 34, 255, 0.9)',
            fill: false,
            label: json.label
          }];
    updateChart(json.x, datasets);
  });
}

function updateChart(labels, dataSets) {
  myChart.data.datasets = dataSets;
  myChart.data.labels = labels;
  myChart.update();
}
