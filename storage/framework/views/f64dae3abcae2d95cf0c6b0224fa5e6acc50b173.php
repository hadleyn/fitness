<?php /* /var/www/html/fitness-dev/resources/views/plan/reducefatdataplan.blade.php */ ?>
<?php $__env->startSection('addData'); ?>
<div class="row">
  <div class="col"><input class="form-control" type="text" name="data" placeholder="Fat Percentage Data Point..."></div>
  <div class="col"><input type="submit" class="btn btn-primary" value="Add Data"></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('plan.planmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>