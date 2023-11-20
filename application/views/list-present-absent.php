<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ams_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>List of Present & Absent</title>
</head>

<?php
if ($this->input->get('total-page-nr')) {
    $id = $this->input->get('total-page-nr');
} else {
    $id = 1;
}
?>

<body id="<?= $id ?>">
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
                <h1 class="">List of Present & Absent</h1>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn mr-2"><i class="fa-solid fa-bars"></i></label>
            </div>
            <div class="total-content p-2">
                <form method="post" action="total">
                    <fieldset class="p-2">
                        <legend>Total Limiter</legend>

                        <div class="mb-1">
                            <input type="date" name="date"> <label>to</label> <input type="date" name="date2">
                        </div>
                        <div class="mb-1">
                            <select class="select" name="section">
                                <option selected disabled>Section</option>
                                <?php
                                foreach ($sections as $section) {
                                ?>
                                    <option value="<?= $section['section'] ?>"><?= $section['section'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-1">
                            <input type="submit" name="btnselect" value="Select">
                        </div>
                        <div class="mb-1">
                            <input type="text" name="txtsearch" placeholder="Search">
                        </div>
                        <div class="mb-1">
                            <input type="submit" name="btnsearch" value="Search">
                        </div>
                        <div class="mb-1">
                            <button class="button" onclick="window.print();">Print</button>
                        </div>

                        <div class="print-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="p-1">Student ID</th>
                                        <th class="p-1">Lastname</th>
                                        <th class="p-1">Firstname</th>
                                        <th class="p-1">Gender</th>
                                        <th class="p-1">Section</th>
                                        <th class="p-1">No. of Present</th>
                                        <th class="p-1">No. of Absent</th>
                                        <th class="p-1">Total of Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($total)) {
                                        foreach ($total as $students) {
                                    ?>
                                            <tr>
                                                <td class="p-1"><?php echo $students['student_id'] ?></td>
                                                <td class="p-1"><?php echo $students['lastname'] ?></td>
                                                <td class="p-1"><?php echo $students['firstname'] ?></td>
                                                <td class="p-1"><?php echo $students['gender'] ?></td>
                                                <td class="p-1"><?php echo $students['section'] ?></td>
                                                <td class="p-1"><?php echo $students['No. of Present'] ?></td>
                                                <td class="p-1"><?php echo $students['No. of Absent'] ?></td>
                                                <td class="p-1"><?php echo $students['Total of Attendance'] ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } elseif (isset($search_result)) {
                                        foreach ($search_result as $search) {
                                        ?>
                                            <tr>
                                                <td class="p-1"><?php echo $search['student_id'] ?></td>
                                                <td class="p-1"><?php echo $search['lastname'] ?></td>
                                                <td class="p-1"><?php echo $search['firstname'] ?></td>
                                                <td class="p-1"><?php echo $search['gender'] ?></td>
                                                <td class="p-1"><?php echo $search['section'] ?></td>
                                                <td class="p-1"><?php echo $search['No. of Present'] ?></td>
                                                <td class="p-1"><?php echo $search['No. of Absent'] ?></td>
                                                <td class="p-1"><?php echo $search['Total of Attendance'] ?></td>
                                            </tr>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="8" class="p-2">No Results.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                    <div class="page-info mt-1">
                        <?php
                        if (isset($pages)) {
                            if ($this->input->get('total-page-nr')) {
                                $page = $this->input->get('total-page-nr');
                            } else {
                                $page = 1;
                            }
                        ?>
                            Showing <?= $page ?> of <?= $pages ?>
                        <?php
                        } else {
                        ?>
                            Showing 0 of 0
                        <?php
                        }
                        ?>
                    </div>
                    <div class="pagination mt-1">
                        <a href="<?= base_url('main/total') . '?total-page-nr=1' ?>">First</a>
                        <?php
                        if ($this->input->get('total-page-nr') != NULL && $this->input->get('total-page-nr') > 1) {
                        ?>
                            <a href="<?= site_url('main/total') . '?total-page-nr=' . $this->input->get('total-page-nr') - 1 ?>">Previous</a>
                        <?php
                        } else {
                        ?>
                            <a>Previous</a>
                        <?php
                        }
                        ?>
                        <div class="page-numbers">
                            <?php
                            if (isset($pages)) {
                                for ($counter = 1; $counter <= $pages; $counter++) {
                            ?>
                                    <a href="<?= base_url('main/total') . '?total-page-nr=' . $counter ?>"><?= $counter ?></a>
                                <?php
                                }
                            } else {
                                ?>
                                <a>0</a>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        if ($this->input->get('total-page-nr') == NULL) {
                        ?>
                            <a href="<?= base_url('main/total') . '?total-page-nr=2' ?>">Next</a>
                            <?php
                        } else {
                            if ($this->input->get('total-page-nr') >= $pages) {
                            ?>
                                <a>Next</a>
                            <?php
                            } else {
                            ?>
                                <a href="<?= base_url('main/total') . '?total-page-nr=' . $this->input->get('total-page-nr') + 1 ?>">Next</a>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        if (isset($pages)) {
                            if ($pages > 0) {
                        ?>
                                <a href="<?= site_url('main/total') . '?total-page-nr=' . $pages ?>">Last</a>
                            <?php
                            } else {
                            ?>
                                <a>Last</a>
                            <?php
                            }
                        } else {
                            ?>
                            <a href="<?= site_url('main/total') ?>">Last</a>
                        <?php
                        }
                        ?>
                    </div>
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

        let links = document.querySelectorAll('.page-numbers > a');
        let bodyID = parseInt(document.body.id) - 1;
        links[bodyID].classList.add('active');
    </script>
</body>

</html>