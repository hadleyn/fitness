<?php /* /var/www/html/fitness-dev/resources/views/plan/planmain.blade.php */ ?>
<?php $__env->startSection('content'); ?>
<div class="container">
  <h1><?php echo e($plan->name); ?> <span class="badge badge-primary"><?php echo e($plan->planType->description); ?></span></h1>

  <?php if(count($planData) === 0): ?>
  <div class="alert alert-warning">
    <p>Looks like you don't have any data on this plan yet. Let's add some!</p>
  </div>
  <?php endif; ?>

  <?php if(Session::has('status')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <span><?php echo e(session('status')); ?></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <?php endif; ?>

  <div class="row">
    <div class="col">
      <form method="POST" action="/plan/addData">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="planId" id="planId" value="<?php echo e($plan->id); ?>" />
        <?php echo $__env->yieldContent('addData'); ?>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <button class="btn btn-primary" id="toggleGraphView">Toggle to Table View</button>
    </div>
    <div class="col">
      <button class="btn btn-primary" id="bulkDataUpload">Bulk Data Upload</button>
    </div>
  </div>

  <div class="chart-container row showing">
    <div class="col">
      <canvas id="dataChart" width="400" height="400"></canvas>
    </div>
  </div>
  <div class="table-container row">
    <?php echo $__env->yieldContent('dataTable'); ?>
  </div>

  <!-- Modals -->
  <?php echo $__env->make('plan.modals.editdatapoint', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('plan.modals.bulkupload', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.appmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>