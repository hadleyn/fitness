<?php /* /var/www/html/fitness-dev/resources/views/index.blade.php */ ?>
<?php $__env->startSection('content'); ?>
<div class="container">
	<h1>TwoPaths</h1>
        <p>Welcome to TwoPaths! Find a path. Follow through. Meet your goals.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.appmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>