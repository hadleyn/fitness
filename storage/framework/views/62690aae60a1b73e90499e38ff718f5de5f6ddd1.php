<?php /* /var/www/html/fitness-dev/resources/views/plan/bodycompdataplan.blade.php */ ?>
<?php $__env->startSection('addData'); ?>
<div class="row">
  <div class="col"><input class="form-control" type="text" placeholder="Body Comp Data"></div>
  <div class="col"><input class="form-control" type="text" placeholder="Data B"></div>
  <div class="col"><input class="form-control" type="text" placeholder="Data C"></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('plan.planmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>