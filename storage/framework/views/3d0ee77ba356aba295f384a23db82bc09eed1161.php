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
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('plan.planmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>