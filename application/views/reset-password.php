<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/ams_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css">
    <title>Reset Password</title>
</head>

<body>
    <form class="newpass p-2" method="post" action="resetpassword">
        <div class="mb-3">
            <img class="newpass-logo" src="<?= base_url() ?>assets/images/ams_logo.png" alt="AMS logo">
        </div>
        <div class="mb-2">
        <h2>Create a new password</h2>
        </div>
        <input type="hidden" name="selector" value="<?= $this->input->get('selector') ?>">
        <input type="hidden" name="validator" value="<?= $this->input->get('validator') ?>">
        <div class="mb-2">
            <input type="password" name="txtpass" placeholder="Enter a new password" required>
        </div>
        <div class="mb-2">
            <input type="password" name="txtpassrepeat" placeholder="Confirm new password" required>
        </div>
        <input class="btn-reset" type="submit" name="btnreset" value="Reset password">
        <?php
            if($this->session->tempdata('reset_error')) {
                ?>
                    <div class="danger mt-2">
                        <?=$this->session->tempdata('reset_error')?>
                    </div>
                <?php
            }
        ?>
    </form>
</body>

</html>