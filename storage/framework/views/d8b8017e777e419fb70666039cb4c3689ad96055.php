<?php /* /var/www/html/fitness-dev/resources/views/dashboard/dashboard.blade.php */ ?>
<?php $__env->startSection('content'); ?>
<div class="container">
	<p>Yo dawg I heard you like dashboards test change</p>
	<?php if($plans->count() === 0): ?>
		<p>:( You don't have any plans set up</p>
		<a class="btn btn-primary" href="/dashboard/newplan">Create a Plan</a>
	<?php else: ?>
		<p>Here's a list of your plans</p>
		<ul class="list-group">
			<?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<li class="list-group-item">
					<a href="/plan/<?php echo e($p->id); ?>"><?php echo e($p->name); ?></a>
					<a href="/dashboard/editplan/<?php echo e($p->id); ?>">Edit Plan</a>
				</li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</ul>
		<a class="btn btn-primary" href="/dashboard/newplan">Create a Plan</a>
	<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.appmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>