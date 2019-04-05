<?php /* /var/www/html/fitness-dev/resources/views/dashboard/dashboard.blade.php */ ?>
<?php $__env->startSection('pageSpecificJS'); ?>
<script src="<?php echo e(URL::asset('js/dashboard.js?t='.time())); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
	<?php if(Session::has('status')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <span><?php echo e(session('status')); ?></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <?php endif; ?>
	<div class="row">
		<div class="col-2">
			<h2>Dashboard</h2>
		</div>
		<div class="col-10">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPlanChooserModal">
			  Create New Plan
			</button>
		</div>
	</div>
	<?php if($plans->count() === 0): ?>
		<p><span data-feather="frown"></span> You don't have any plans set up. Now's a great time to get started!</p>
	<?php else: ?>
		<ul class="list-group">
			<?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<li class="list-group-item">
					<div class="row">
						<div class="col-3">
							<a href="/dashboard/editplan/<?php echo e($p->id); ?>"><span data-feather="edit"></span></a>
							<a href="/plan/<?php echo e($p->id); ?>"><?php echo e($p->name); ?></a>
						</div>
						<div class="col-3">
							<form method="POST" action="/plan/addData">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="planId" value="<?php echo e($p->id); ?>">
								<?php if($dataForToday[$p->id]): ?>
									<input type="text" name="data" disabled="disabled" value="<?php echo e($dataForToday[$p->id]->data); ?>">
								<?php else: ?>
									<input type="text" name="data" placeholder="Data quick add...">
								<?php endif; ?>
							</form>
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col">
									<p>Target Completion Date: <p>
										<?php if(strtotime($completionDate[$p->id])): ?>
											<p>Projected Completion Date:</p>
										<?php endif; ?>
								</div>
								<div class="col">
									<p class="emphasis"><?php echo e(date('D M j, Y', strtotime($p->plannable->goal_date))); ?></p>
									<?php if(strtotime($completionDate[$p->id])): ?>
										<p class="emphasis"><?php echo e(date('D M j, Y', strtotime($completionDate[$p->id]))); ?></p>
										<?php if(strtotime($completionDate[$p->id]) <= strtotime($p->plannable->goal_date)): ?>
											<p class="badge badge-success">On Track!</p>
										<?php else: ?>
											<p class="badge badge-warning">Not on Track</p>
										<?php endif; ?>
									<?php else: ?>
										<p class="badge badge-danger">Will Never Meet Goal</p>
									<?php endif; ?>
									<p>Goal: <?php echo e($p->plannable->getGoalValue()); ?></p>
							</div>
						</div>
					</div>
				</li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</ul>
	<?php endif; ?>
	<?php echo $__env->make('dashboard.modals.newplanchooser', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.appmain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>