<?php /* /var/www/html/fitness-dev/resources/views/plan/modals/bulkupload.blade.php */ ?>
<div class="modal fade" id="bulkDataUploadModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bulk Data Upload</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="modalAlerts">

        </div>
        <p>Upload bulk data here</p>
        <form id="bulkDataUploadForm" method="post" action="/plan/submitBulkDataUpload" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="planId" value="<?php echo e($plan->id); ?>">
          <label for="bulkFile">Find your file</label>
          <input type="file" name="bulkFile" class="form-control-file" id="bulkFile">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="bulkDataUploadSubmit" class="btn btn-primary">Next</button>
      </div>
    </div>
  </div>
</div>
