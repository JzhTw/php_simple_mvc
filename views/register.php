
<h1>Register</h1>
<?php echo '<pre>',print_r($model),'</pre>'; ?>
<form action="" method="post">
  <div class="mb-3">
    <label class="form-label">Firstname</label>
    <input name="firstname" type="text" class="form-control <?php echo $model->hasError('firstname') ? 'is-invalid' : '' ?>" value="<?php echo $model->firstname; ?>">
    <div class="invalid-feedback">
      <?php echo $model->getFirstError('firstname'); ?>
    </div>
  </div>
  <div class="mb-3">
    <label class="form-label">Lastname</label>
    <input name="lastname" type="text" class="form-control <?php echo $model->hasError('lastname') ? 'is-invalid' : '' ?>" value="<?php echo $model->lastname; ?>">
    <div class="invalid-feedback">
      <?php echo $model->getFirstError('lastname'); ?>
    </div>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control <?php echo $model->hasError('email') ? 'is-invalid' : '' ?>" value="<?php echo $model->email; ?>">
    <div class="invalid-feedback">
      <?php echo $model->getFirstError('email'); ?>
    </div>
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input name="password" type="password" class="form-control <?php echo $model->hasError('password') ? 'is-invalid' : '' ?>" value="<?php echo $model->password; ?>">
    <div class="invalid-feedback">
      <?php echo $model->getFirstError('password'); ?>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>