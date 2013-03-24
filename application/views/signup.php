<h1>Sign up</h1>

<? if ($errors) {?>
	<div class="alert alert-error">
		<ul>
		<? foreach ($errors as $error) {?>
			<li><?=$error?></li>
		<?}?>
		</ul>
	</div>
<?}?>
<form method="post" class="form-horizontal">
  <div class="control-group">
	<label class="control-label" for="inputUsername">Username</label>
	<div class="controls">
	  <input type="text" name="username" id="inputUsername" placeholder="">
	</div>
  </div>
  <div class="control-group">
	<label class="control-label" for="inputEmail">Email</label>
	<div class="controls">
	  <input type="text" name="email" id="inputEmail" placeholder="">
	</div>
  </div>
  <div class="control-group">
	<label class="control-label" for="inputPassword">Password</label>
	<div class="controls">
	  <input type="password" name="password" id="inputPassword" placeholder="">
	</div>
  </div>
  <div class="control-group">
	<label class="control-label" for="inputPasswordConfirm">Confirm Password</label>
	<div class="controls">
	  <input type="password" name="password_confirm" id="inputPasswordConfirm" placeholder="">
	</div>
  </div>
  <div class="control-group">
	<div class="controls">
	  <button type="submit" class="btn">Sign up</button>
	</div>
  </div>
</form>
