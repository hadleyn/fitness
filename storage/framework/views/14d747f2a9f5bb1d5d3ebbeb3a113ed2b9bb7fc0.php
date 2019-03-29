<?php /* /var/www/html/fitness-dev/resources/views/dashboard/musclegainplanform.blade.php */ ?>
<?php $__env->startSection('pageSpecificJS'); ?>
<script src="<?php echo e(URL::asset('js/dashboard.js?t='.time())); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
  <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
  <?php endif; ?>
  <form method="POST" action="/dashboard/saveGainMusclePlan">
    <?php echo csrf_field(); ?>
    <?php if(isset($plan)): ?>
    <input type="hidden" value="<?php echo e($plan->id); ?>" name="planId">
    <?php endif; ?>

    <div class="form-group">
      <?php if(empty($plan->id)): ?>
      <p>Create your new plan!</p>
      <?php else: ?>
      <p>Edit your plan</p>
      <?php endif; ?>
      <label class="required" for="planName">Plan Name</label>
      <input type="text" class="form-control" id="planName" name="planName" placeholder="Enter a descriptive name for your new plan!" value="<?php echo e(old('planName', $plan->name)); ?>">
    </div>
    <div class="form-group">
      <label for="startingFatPercentage">Starting Muscle Percentage</label>
      <?php if(empty($plan->id)): ?>
        <input type="text" class="form-control" id="startingMusclePercentage" name="startingMusclePercentage" value="<?php echo e(old('startingMusclePercentage')); ?>">
      <?php else: ?>
        <input type="text" class="form-control" id="startingMusclePercentage" name="startingMusclePercentage" value="<?php echo e(old('startingMusclePercentage', $plan->plannable->starting_muscle_percentage)); ?>">
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label for="startDate">Goal Muscle Percentage</label>
      <?php if(empty($plan->id)): ?>
        <input type="text" class="form-control" id="planGoal" name="planGoal" value="<?php echo e(old('planGoal')); ?>">
      <?php else: ?>
        <input type="text" class="form-control" id="planGoal" name="planGoal" value="<?php echo e(old('planGoal', $plan->plannable->goal_muscle_percentage)); ?>">
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label for="startDate">Start Date</label>
      <input type="text" class="form-control" id="startDate" name="startDate" placeholder="mm/dd/yyyy" value="<?php echo e(old('startDate', $plan->start_date)); ?>">
    </div>
    <div class="form-group">
      <label for="goalDate">Goal Date</label>
      <?php if(empty($plan->id)): ?>
        <input type="text" class="form-control" id="goalDate" name="goalDate" placeholder="mm/dd/yyyy" value="<?php echo e(old('goalDate')); ?>">
      <?php else: ?>
        <input type="text" class="form-control" id="goalDate" name="goalDate" placeholder="mm/dd/yyyy" value="<?php echo e(old('goalDate', $plan->plannable->goal_date)); ?>">
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button><a href="/dashboard" class="btn btn-link">Cancel</a>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.appmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>