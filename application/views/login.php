<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ams_favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
  <title>Log in</title>
</head>

<body>
  <?php
  if ($this->session->tempdata('username')) {
  ?>
      <form class="login p-2" method="post" action="login">
      <img class="login-logo mb-4 mt-2" src="<?php echo base_url();?>assets/images/ams_logo.png" alt="AMS logo">
      <div class ="mb-2">
        <input type ="text" name ="txtuname" value="<?=$this->session->tempdata('username')?>" required>
      </div>
      <div class="mb-2">
        <input type ="password" name ="txtpword" placeholder="Enter your password" required>
      </div>
      <div class="mb-5">
        <input class ="mr-1" type ="submit" name ="btnlogin" value ="Login">
        <a class="" href ="<?php echo site_url();?>main/forgot">Forgot your password?</a>
      </div>
      <div class="mb-1">
      <label>Don't have an account?</label><a class="" href ="<?php echo site_url('main/signup')?>">Sign up</a>
      </div>
      <?php
      if($this->session->tempdata('login_success')) {
        ?>
              <div class="success mt-2">
                <?php echo $this->session->tempdata('login_success');?>
              </div>
        <?php
      }
      elseif($this->session->tempdata('login_error')) {
        ?>
              <div class="danger mt-2">
                <?php echo $this->session->tempdata('login_error');?>
              </div>
        <?php
      }
      ?>
    </form>
  <?php
  } else {
  ?>
    <form class="login p-2" method="post" action="login">
      <img class="login-logo mb-4 mt-2" src="<?php echo base_url(); ?>assets/images/ams_logo.png" alt="AMS logo">
      <div class="mb-2">
        <input type="text" name="txtuname" placeholder="Enter your username" required>
      </div>
      <div class="mb-2">
        <input type="password" name="txtpword" placeholder="Enter your password" required>
      </div>
      <div class="mb-5">
        <input class="mr-1" type="submit" name="btnlogin" value="Login">
        <a class="" href="<?php echo site_url(); ?>main/forgot">Forgot your password?</a>
      </div>
      <div class="mb-1">
        <label>Don't have an account?</label><a class="" href="<?php echo site_url('main/signup') ?>">Sign up</a>
      </div>
      <?php
      if ($this->session->tempdata('login_success')) {
      ?>
        <div class="success mt-2">
          <?php echo $this->session->tempdata('login_success'); ?>
        </div>
      <?php
      } elseif ($this->session->tempdata('login_error')) {
      ?>
        <div class="danger mt-2">
          <?php echo $this->session->tempdata('login_error'); ?>
        </div>
      <?php
      }
      ?>
    </form>
  <?php
  }
  ?>
</body>

</html>