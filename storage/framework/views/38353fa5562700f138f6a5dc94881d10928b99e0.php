<?php /* /var/www/html/fitness-dev/resources/views/index.blade.php */ ?>
<?php $__env->startSection('content'); ?>
<div class="container">
	<h1>Get Fit!</h1>
        <p>Welcome to Get Fit! This app helps you to track your weight and fitness goals! TEST MESSAGE</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.appmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>