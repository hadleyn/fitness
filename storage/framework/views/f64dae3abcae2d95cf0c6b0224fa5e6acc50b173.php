<?php /* /var/www/html/fitness-dev/resources/views/plan/reducefatdataplan.blade.php */ ?>
<?php $__env->startSection('addData'); ?>
<div class="row">
  <?php if($dataForToday): ?>
    <div class="col"><input class="form-control" type="text" name="data"  id="test" disabled="disabled" value="<?php echo e($dataForToday->data); ?>"></div>
  <?php else: ?>
    <div class="col-8"><input class="form-control" type="text" name="data"  id="test" placeholder="Today's Fat Percentage Data Point..."></div>
    <div class="col-4"><input type="submit" class="btn btn-primary" value="Add Data"></div>
  <?php endif; ?>
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
        <th scope="col">Fat Percentage</th>
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
        <td><?php echo e($pd->data); ?></td>
        <td><?php echo e($plan->getExpectedDataForDate($pd->simple_date)); ?></td>
        <td><?php echo e($dailyDeltas->get($index)->data); ?></td>
        <?php if($pd->estimated): ?>
          <td><a href="#" class="editDataPoint" data-id="<?php echo e($pd->id); ?>" data-simpledate="<?php echo e($pd->simple_date); ?>">Set Data?</a></td>
        <?php else: ?>
          <td><a href="#" class="editDataPoint" data-id="<?php echo e($pd->id); ?>" data-simpledate="<?php echo e($pd->simple_date); ?>">Edit</a></td>
        <?php endif; ?>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
<!-- </div> -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('planAnalysis'); ?>
<div class="row">
  <div class="col-3">
    <p>Slope (% fat lost per day):</p>
    <p>Expected Loss Per Day:</p>
    <p>Y-Intercept:</p>
    <p>Total Fat Lost:</p>
  </div>
  <div class="col-9 emphasis">
    <p><?php echo e(round($slope, 3)); ?></p>
    <p><?php echo e($plan->getExpectedLossPerDay()); ?></p>
    <p><?php echo e($yIntercept); ?></p>
  	<p><?php echo e($plan->plannable->getTotalFatLost()); ?>%</p>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('planOperations'); ?>
<a href="#" id="deletePlan">Delete Plan</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('plan.planmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>