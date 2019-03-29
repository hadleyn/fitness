<?php /* /var/www/html/fitness-dev/resources/views/plan/modals/deleteplan.blade.php */ ?>
<div class="modal fade" id="deletePlanModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="modalAlerts alert alert-danger">

        </div>
        <p>You are attempting to delete a plan. This will delete all of the data associated with the plan. This CANNOT BE UNDONE.</p>
        <form id="deletePlanForm">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="planId" value="<?php echo e($plan->id); ?>">
          <div class="form-group">
            <label for="deleteConfirm">Type "DELETE" to confirm</label>
            <input type="text" name="deleteConfirm" id="deleteConfirm">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirmDeletePlan">Confirm</button>
      </div>
    </div>
  </div>
</div>
