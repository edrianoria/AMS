<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ams_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Home</title>
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
                <h1 class="">Welcome! <?php echo $this->session->userdata('name'); ?></h1>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn mr-2"><i class="fa-solid fa-bars"></i></label>
            </div>
            <div class="home-content p-2">
                <img class="home-logo mb-4 mt-2" src="<?php echo base_url(); ?>assets/images/ams_logo.png" alt="AMS">
                <div class="mb-2">
                    <p>We noticed that some of the teachers or professors are using different kinds of programs like excel, google class and etc. and sometimes using the traditional way of checking of attendance. Our website named Attendance Management System (AMS) is implemented in order to make a simple and better website for the teachers or professors when checking an attendance.</p>
                </div>
                <div class="mb-3">
                    <p>Attendance Management System aims to help the teachers or professors of the Divine Mercy College Foundation Inc. to have a simple and better program for checking the attendance. The Attendance Management System also aims to have an accurate data in attendance because sometimes some of the students are still having an attendance even though they didn’t attended in the class. The Attendance Management System also aims to secure all of the data by having a log in form in the website.</p>
                </div>

                <div class="mb-1">
                    <h2>Page Previews</h2>
                </div>
                <div class="mt-1">
                    <ul>
                        <li class="mb-2">List of Students</li>
                        <div class="prev-img mb-2">
                        <img src="<?= base_url()?>assets/images/los.png" alt="List Of Students">
                        </div>
                        <li class="mb-2">List of Attendance</li>
                        <div class="prev-img mb-2">
                        <img src="<?= base_url()?>assets/images/loa.png" alt="List Of Attendance">
                        </div>
                        <li class="mb-2">List of Present & Absent</li>
                        <div class="prev-img mb-2">
                        <img src="<?= base_url()?>assets/images/lopa.png" alt="List Of Present & Absent">
                        </div>
                    </ul>
                </div>
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