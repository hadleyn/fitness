<?php /* /var/www/html/fitness-dev/resources/views/plan/planmain.blade.php */ ?>
<?php $__env->startSection('content'); ?>
<div class="container">
  <h1><?php echo e($plan->name); ?> <span class="badge badge-primary"><?php echo e($plan->plannable->getPlanTypeDescription()); ?></span></h1>

  <?php if(count($continuousPlanData) === 0): ?>
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
    <div class="col">
      <button class="btn btn-primary" id="bulkDataUpload">Bulk Data Upload</button>
    </div>
  </div>

  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <a class="nav-item nav-link active" id="nav-charts-tab" data-toggle="tab" href="#charts" role="tab" aria-controls="nav-charts" aria-selected="true">Charts</a>
      <a class="nav-item nav-link" id="nav-tables-tab" data-toggle="tab" href="#tables" role="tab" aria-controls="nav-tables" aria-selected="false">Tables</a>
      <a class="nav-item nav-link" id="nav-analysis-tab" data-toggle="tab" href="#analysis" role="tab" aria-controls="nav-analysis" aria-selected="false">Analysis</a>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="charts" role="tabpanel" aria-labelledby="nav-charts-tab">
        <?php echo $__env->yieldContent('dataChart'); ?>
    </div>
    <div class="tab-pane fade" id="tables" role="tabpanel" aria-labelledby="nav-tables-tab">
      <div class="table-container row">
        <?php echo $__env->yieldContent('dataTable'); ?>
      </div>
    </div>
    <div class="tab-pane fade" id="analysis" role="tabpanel" aria-labelledby="nav-analysis-tab">
      <?php echo $__env->yieldContent('planAnalysis'); ?>
    </div>
  </div>

  <!-- Modals -->
  <?php echo $__env->make('plan.modals.editdatapoint', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('plan.modals.bulkupload', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.appmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>