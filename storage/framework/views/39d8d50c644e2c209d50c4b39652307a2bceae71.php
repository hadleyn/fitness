<?php /* /var/www/html/fitness-dev/resources/views/profile/profile.blade.php */ ?>
<?php $__env->startSection('content'); ?>
<div class="container">
  <h2>Profile and Preferences</h2>
  <form method="post" action="/profile/saveUserPreferences">
    <?php echo csrf_field(); ?>
    <div class="form-group">
      <label class="required" for="timezonePicker">Select your local timezone</label>
      <select class="form-control" id="timezonePicker" name="timezone">
        <?php echo DateHelper::timezoneSelector($timezone); ?>

      </select>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary">Save</button><a href="/dashboard" class="btn btn-link">Cancel</a>
    </div>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.appmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>