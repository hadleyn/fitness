<?php /* /var/www/html/fitness-dev/resources/views/plan/gainmuscledataplan.blade.php */ ?>
<?php $__env->startSection('addData'); ?>
<div class="row">
  <div class="col"><input class="form-control" type="text" name="data" placeholder="Muscle Percentage Data Point..."></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dataChart'); ?>
<div class="chart-container row">
  <div class="col">
    <canvas id="dataChart" width="400" height="400"></canvas>
  </div>
</div>
<div class="chart-container row">
  <div class="col">
    <canvas id="dailyDeltaChart" width="400" height="400"></canvas>
  </div>
</div>
<div class="chart-container row">
	<div class="col">
		<canvas id="dailySlopeChart" width="400" height="400"></canvas>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dataTable'); ?>
<!-- <div class="col"> -->
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Muscle Percentage</th>
        <th scope="col">Expected</th>
        <th scope="col">Daily Delta</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $continuousPlanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if($pd->estimated): ?>
      	<tr class="table-info">
      <?php else: ?>
      	<tr>
      <?php endif; ?>
        <th scope="row"><?php echo e(date($displayDateFormat, strtotime($pd->simple_date))); ?></th>
        <?php if($pd->data == null): ?>
          <td>No Data</td>
          <td><?php echo e($plan->getExpectedDataForDate($pd->simple_date)); ?></td>
          <td><?php echo e($dailyDeltas->get($index)->data); ?></td>
          <td><a href="#" class="editDataPoint">Set Data?</a></td>
        <?php else: ?>
          <td><?php echo e($pd->data); ?></td>
          <td><?php echo e($plan->getExpectedDataForDate($pd->simple_date)); ?></td>
          <td><?php echo e($dailyDeltas->get($index)->data); ?></td>
          <td><a href="#" class="editDataPoint" data-id="<?php echo e($pd->id); ?>">Edit</a></td>
        <?php endif; ?>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
<!-- </div> -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('planAnalysis'); ?>
<div class="row">
  <div class="col">
    Slope (% muscle gained per day): <?php echo e(round($slope, 3)); ?>

  </div>
  <div class="col">
    Expected Loss Per Day: <?php echo e($plan->getExpectedLossPerDay()); ?>

  </div>
  <div class="col">
    Y-Intercept: <?php echo e($yIntercept); ?>

  </div>
</div>
<div class="row">
  	<div class="col">
  		Total Fat Lost: <?php echo e($plan->plannable->getTotalMuscleGained()); ?>%
	</div>
	<!-- <div class="col">
    <div>
      <label>
        <input id="toggleRollingAverage" type="checkbox" data-toggle="toggle">
        Toggle Rolling Average
      </label>
    </div>
	</div> -->
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('plan.planmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>