<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ams_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>User Information</title>
</head>

<body>
    <?php
    if (isset($_SESSION['name'])) {
    ?>
        <div class="container">
            <div class="navbar">
                <div class="nav-profile">
                    <?php
                    if (isset($_SESSION['profile'])) {
                    ?>
                        <a href="<?= site_url('main/profile?id=') . $_SESSION['id'] ?>">
                            <img class="profile mt-1 mb-2" src="<?php echo base_url(); ?>assets/profiles/<?= $_SESSION['profile'] ?>" alt="User Profile">
                            <h1 class="h1 mb-2"><?= $_SESSION['name'] ?></h1>
                        </a>
                    <?php
                    } else {
                    ?>
                        <a href="<?= site_url('main/profile?id=') . $_SESSION['id'] ?>">
                            <img class="profile mt-1 mb-2" src="<?php echo base_url(); ?>assets/profiles/default.png" alt="User Profile">
                            <h1 class="h1 mb-2"><?= $_SESSION['name'] ?></h1>
                        </a>
                    <?php
                    }
                    ?>
                </div>
                <ul>
                    <li><a href="<?php echo site_url('main/home'); ?>"><i class="fa-solid fa-house mr-1"></i>Home</a></li>
                    <li><a href="<?php echo site_url('main/list'); ?>"><i class="fa-solid fa-user mr-1"></i>List of Students</a></li>
                    <li><a href="<?php echo site_url('main/attendance'); ?>"><i class="fa-solid fa-clipboard-user mr-1"></i>List of Attendance</a></li>
                    <li><a href="<?php echo site_url('main/total'); ?>"><i class="fa-solid fa-table-list mr-1"></i>List of Present & Absent</a></li>
                    <li><a href="<?php echo site_url('main/index'); ?>"><i class="fa-solid fa-right-from-bracket mr-1"></i>Log out</a></li>
                </ul>
            </div>
            <div class="header">
                <h1 class="">Verify User</h1>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn mr-2"><i class="fa-solid fa-bars"></i></label>
            </div>
            <div class="verify-content p-2">
                <form action="verifyuser" method="post">
                    <?php
                    if ($this->input->get('authentication') == NULL || $this->input->get('authentication') == "Disabled") {
                    ?>
                        <div class="mb-3">
                            <h1>Use your password to confirm it's really you</h1>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="txtpass" placeholder="Enter your password">
                        </div>
                        <input type="submit" name="btndverify" value="Verify">
                    <?php
                    } elseif ($this->input->get('authentication') == "Enabled") {
                    ?>
                        <div class="mb-3">
                            <h1>Use your password to confirm it's really you</h1>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="txtpass" placeholder="Enter your password">
                        </div>
                        <input type="submit" name="btneverify" value="Verify">
                    <?php
                    }
                    ?>
                </form>
                <?php
                if ($this->session->tempdata('verify_error')) {
                ?>
                    <div class="danger mt-2">
                        <?php echo $this->session->tempdata('verify_error'); ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="footer">
                <div class="copyright">
                    <a href="<?= site_url('main/home') ?>"><img src="<?= base_url() ?>assets/images/ams_logo.png" alt="AMS logo"></a>
                    <p><b>Attendance Management System</b>, All Rights Reserved. | Group N</p>
                </div>
                <div class="social-media">
                    <div class="mb-1">
                        <b>Contact Us</b>
                    </div>
                    <a><i class="fa-brands fa-facebook mr-1"></i>Facebook</a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=amsecsu@gmail.com&su=Subject%20Here&body=Body%20Text%20Here" target="_blank"><i class="fa-solid fa-envelope mr-1"></i>E-mail</a>
                </div>
            </div>
        </div>
    <?php
    } else {
        redirect('main/index');
    }
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkbox = document.getElementById('check');
            var sidebar = document.querySelector('.navbar');
            var checkbtn = document.querySelector('.checkbtn');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    sidebar.style.left = '0';
                    checkbtn.style.display = 'block';
                } else {
                    sidebar.style.left = '-100%';
                    checkbtn.style.display = 'block';
                }
            });
        });
    </script>
</body>

</html>