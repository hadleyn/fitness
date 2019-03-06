<?php /* /var/www/html/fitness-dev/resources/views/plan/weightdataplan.blade.php */ ?>
<?php $__env->startSection('addData'); ?>
<div class="row">
  <div class="col"><input class="form-control" type="text" name="data"  id="test" placeholder="Add a weight data point..."></div>
  <div class="col"><input type="submit" class="btn btn-primary" value="Add Data"></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dataTable'); ?>
<div class="col">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Weight</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $planData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <th scope="row"><?php echo e($pd->created_at); ?></th>
        <td><?php echo e($pd->data); ?></td>
        <td><a href="#" class="editDataPoint" data-id="<?php echo e($pd->id); ?>">Edit</a></td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('planAnalysis'); ?>
<div class="row">
  <div class="col">
    <h2>Plan Analysis</h2>
  </div>
</div>
<div class="row">
  <div class="col">
    Slope (weight lost per day): <?php echo e(round($plan->getSlope(), 3)); ?>

  </div>
  <div class="col">
    Expected Loss Per Day: <?php echo e($plan->plannable->getExpectedLossPerDay()); ?>

  </div>
  <div class="col">
    Y-Intercept: <?php echo e($plan->getYIntercept()); ?>

  </div>
</div>
<div class="row">
  	<div class="col">
  		Total Weight Lost: <?php echo e($plan->plannable->getTotalWeightLost()); ?>	
	</div>	
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('plan.planmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>