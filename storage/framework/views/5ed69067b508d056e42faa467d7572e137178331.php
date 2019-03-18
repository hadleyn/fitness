<?php /* /var/www/html/fitness-dev/resources/views/dashboard/modals/newplanchooser.blade.php */ ?>
<div class="modal fade" id="newPlanChooserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">What Type of New Plan Are You Creating?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="modalAlerts">

        </div>
        <div class="list-group list-group-horizontal">
          <a href="/dashboard/newWeightReductionPlan" class="list-group-item list-group-item-action">Weight Reduction Plan</a>
          <a href="/dashboard/newFatReductionPlan" class="list-group-item list-group-item-action">Fat Reduction Plan</a>
          <a href="/dashboard/newMuscleGainPlan" class="list-group-item list-group-item-action">Muscle Gain Plan</a>
          <a href="#" class="list-group-item list-group-item-action">Weight Gain Plan</a>
          <a href="#" class="list-group-item list-group-item-action">Workout Plan</a>
        </div>
      </div>
    </div>
  </div>
</div>
