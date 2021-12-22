<h1>Login</h1>

<form action="" method="post">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email"
            class="form-control <?php echo $model->hasError('email') ? 'is-invalid' : '' ?>">
        <div class="invalid-feedback">
            <?php echo $model->getFirstError('email'); ?>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password"
            class="form-control <?php echo $model->hasError('password') ? 'is-invalid' : '' ?>">
        <div class="invalid-feedback">
            <?php echo $model->getFirstError('password'); ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>