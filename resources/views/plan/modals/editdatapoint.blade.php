<div class="modal fade" id="dataPointEditModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Data Point</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="modalAlerts">

        </div>
        <p>You are editing a data point. You can provide a new value as well as a new date for this data point.</p>
        <form id="editDataPointForm">
          @csrf
          <input type="hidden" id="planDataId" name="planDataId" value="">
          <input type="hidden" name="planId" value="{{ $plan->id }}">
          <div class="row">
            <div class="col"><input class="form-control" type="text" name="editData" id="editData" placeholder="Edit Data Point"></div>
          </div>
          <div class="row">
            <div class="col"><input class="form-control" type="text" readonly name="editDataDate" id="editDataDate" placeholder="Edit Data Date"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="dataPointEditSave" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
