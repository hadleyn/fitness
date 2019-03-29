<?php /* /var/www/html/fitness-dev/resources/views/plan/modals/editdatapoint.blade.php */ ?>
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
        <p>You are editing a data point. If this is a derived data point, you are setting a new data value for the day shown.</p>
        <form id="editDataPointForm">
          <?php echo csrf_field(); ?>
          <input type="hidden" id="planDataId" name="planDataId" value="">
          <input type="hidden" name="planId" value="<?php echo e($plan->id); ?>">
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
