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
                    if(isset($_SESSION['profile'])) {
                        ?>
                        <a href="<?=site_url('main/profile?id=').$_SESSION['id']?>">
                        <img class="profile mt-1 mb-2" src="<?php echo base_url(); ?>assets/profiles/<?=$_SESSION['profile']?>" alt="User Profile">
                        <h1 class="h1 mb-2"><?=$_SESSION['name']?></h1>
                        </a>
                        <?php
                    }
                    else {
                        ?>
                        <a href="<?=site_url('main/profile?id=').$_SESSION['id']?>">
                        <img class="profile mt-1 mb-2" src="<?php echo base_url(); ?>assets/profiles/default.png" alt="User Profile">
                        <h1 class="h1 mb-2"><?=$_SESSION['name']?></h1>
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
                <h1 class="">User Information</h1>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn mr-2"><i class="fa-solid fa-bars"></i></label>
            </div>
            <div class="profile-content p-2">
                <form action="profile" method="post" enctype="multipart/form-data">
                    <?php
                    foreach ($user_info as $row) {
                        if ($row['authentication'] == NULL || $row['authentication'] == "Disabled") {
                    ?>
                            <div class="profile-float mb-5">
                                <?php
                                if ($row['profile_img'] == NULL) {
                                ?>
                                    <img id="image-preview" class="mb-2" src="<?= base_url() ?>assets/profiles/default.png" alt="User Picture">
                                <?php
                                } else {
                                ?>
                                    <img id="image-preview" class="mb-2" src="<?= base_url() ?>assets/profiles/<?= $row['profile_img'] ?>" alt="User Picture">
                                <?php
                                }
                                ?><br>
                                <label id="select-btn" for="select-img">Select Image</label>
                                <input id="select-img" type="file" name="user-img" accept=".jpg, .jpeg, .png" onchange="previewImage()">
                            </div>
                            <div class="mb-2">
                                <label><span class="grey mr-2">Username</span><?= $row['username'] ?></label>
                            </div>
                            <div class="mb-2">
                                <label><span class="grey mr-2">Name</span></label><input type="text" name="txtname" value="<?= $row['name'] ?>">
                            </div>
                            <div class="mb-2">
                                <label><span class="grey mr-2">Email</span><?= $row['email'] ?></label>
                            </div>
                            <div class="mb-2">
                                <label for="conf"><span class="grey mr-2">2-Step Verification</span></label>
                                <a class="red" href="<?=site_url('main/verifyuser?id='.$row['id'].'&authentication='.$row['authentication'])?>">Disabled</a>
                            </div>
                            <div class="clear-fix">
                                <input type="submit" name="btnsave" value="Save">
                            </div>
                            <?php
                            if ($this->session->tempdata('save_success')) {
                            ?>
                                <div class="success mt-2">
                                    <?php echo $this->session->tempdata('save_success'); ?>
                                </div>
                            <?php
                            } elseif ($this->session->tempdata('save_error')) {
                            ?>
                                <div class="danger mt-2">
                                    <?php echo $this->session->tempdata('save_error'); ?>
                                </div>
                            <?php
                            } elseif ($this->session->tempdata('image_error')) {
                            ?>
                                <div class="danger mt-2">
                                    <?php echo $this->session->tempdata('image_error'); ?>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="profile-float mb-5">
                                <?php
                                if ($row['profile_img'] == NULL) {
                                ?>
                                    <img id="image-preview" class="mb-2" src="<?= base_url() ?>assets/profiles/default.png" alt="User Picture">
                                <?php
                                } else {
                                ?>
                                    <img id="image-preview" class="mb-2" src="<?= base_url() ?>assets/profiles/<?= $row['profile_img'] ?>" alt="User Picture">
                                <?php
                                }
                                ?><br>
                                <label id="select-btn" for="select-img">Select Image</label>
                                <input id="select-img" type="file" name="user-img" accept=".jpg, .jpeg, .png" onchange="previewImage()">
                            </div>
                            <div class="mb-2">
                                <label><span class="grey mr-2">Username</span><?= $row['username'] ?></label>
                            </div>
                            <div class="mb-2">
                                <label><span class="grey mr-2">Name</span></label><input type="text" name="txtname" value="<?= $row['name'] ?>">
                            </div>
                            <div class="mb-2">
                                <label><span class="grey mr-2">Email</span><?= $row['email'] ?></label>
                            </div>
                            <div class="mb-2">
                                <label for="conf"><span class="grey mr-2">2-Step Verification</span></label>
                                <a class="green" href="<?=site_url('main/verifyuser?id='. $row['id'].'&authentication='.$row['authentication'])?>">Enabled</a>
                            </div>
                            <div class="clear-fix">
                                <input type="submit" name="btnsave" value="Save">
                            </div>
                            <?php
                            if ($this->session->tempdata('save_success')) {
                            ?>
                                <div class="success mt-2">
                                    <?php echo $this->session->tempdata('save_success'); ?>
                                </div>
                            <?php
                            } elseif ($this->session->tempdata('save_error')) {
                            ?>
                                <div class="danger mt-2">
                                    <?php echo $this->session->tempdata('save_error'); ?>
                                </div>
                            <?php
                            } elseif ($this->session->tempdata('image_error')) {
                            ?>
                                <div class="danger mt-2">
                                    <?php echo $this->session->tempdata('image_error'); ?>
                                </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </form>
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

        function previewImage() {
        var input = document.getElementById('select-img');
        var preview = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>

</html>