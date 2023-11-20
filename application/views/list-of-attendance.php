<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ams_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>List of attendance</title>
</head>

<?php
if ($this->input->get('page-nr')) {
    $id = $this->input->get('page-nr');
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
                <h1 class="">List of Attendance</h1>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn mr-2"><i class="fa-solid fa-bars"></i></label>
            </div>
            <div class="attendance-content p-2">
                <form method="post" action="attendance">
                    <?php
                    if (isset($student_info)) {
                        foreach ($student_info as $students) {
                    ?>
                            <fieldset class="mt-2 mb-5 p-2">
                                <legend>Add Attendance</legend>

                                <div class="mb-1">
                                    <input type="hidden" name="txtuser" value="<?= $this->session->userdata('id'); ?>">
                                    <input class="" type="text" name="txtsid" value="<?= $students['student_id'] ?>">
                                </div>
                                <div class="mb-1">
                                    <input class="" type="text" name="txtlname" value="<?= $students['lastname'] ?>">
                                </div>
                                <div class="mb-1">
                                    <input class="" type="text" name="txtfname" value="<?= $students['firstname'] ?>">
                                </div>
                                <div class="mb-1">
                                    <select class="select" name="gender">
                                        <option selected><?= $students['gender'] ?></option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <input class="" type="text" name="section" value="<?= $students['section'] ?>">
                                </div>
                                <div class="mb-1">
                                    <select class="select" name="status">
                                        <option selected disabled>Status</option>
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <input type="date" name="date">
                                </div>
                                <div class="mb-1">
                                    <input class="" type="submit" name="btnadd" value="Add">
                                </div>

                                <?php
                                if ($this->session->tempdata('attendance_success')) {
                                ?>
                                    <div class="success mt-2">
                                        <?php echo $this->session->tempdata('attendance_success'); ?>
                                    </div>
                                <?php
                                } elseif ($this->session->tempdata('attendance_error')) {
                                ?>
                                    <div class="danger mt-2 width-100">
                                        <?php echo $this->session->tempdata('attendance_error'); ?>
                                    </div>
                                <?php
                                }
                                ?>
                            </fieldset>
                        <?php
                        }
                    } elseif (isset($stud_data)) {
                        ?>
                        <fieldset class="mt-2 mb-5 p-2">
                            <legend>Adding Attendance</legend>

                            <div class="mb-1">
                                <input type="hidden" name="txtuser" value="<?= $this->session->userdata('id'); ?>">
                                <input class="" type="text" name="txtsid" value="<?= $stud_data['student_id'] ?>">
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="txtlname" value="<?= $stud_data['lastname'] ?>">
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="txtfname" value="<?= $stud_data['firstname'] ?>">
                            </div>
                            <div class="mb-1">
                                <select class="select" name="gender">
                                    <option selected><?= $stud_data['gender'] ?></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="section" value="<?= $stud_data['section'] ?>">
                            </div>
                            <div class="mb-1">
                                <select class="select" name="status">
                                    <option selected disabled>Status</option>
                                    <option value="Present">Present</option>
                                    <option value="Absent">Absent</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <input type="date" name="date">
                            </div>
                            <div class="mb-1">
                                <input class="" type="submit" name="btnadd" value="Add">
                            </div>

                            <?php
                            if ($this->session->tempdata('attendance_success')) {
                            ?>
                                <div class="success mt-2">
                                    <?php echo $this->session->tempdata('attendance_success'); ?>
                                </div>
                            <?php
                            } elseif ($this->session->tempdata('attendance_error')) {
                            ?>
                                <div class="danger mt-2 width-100">
                                    <?php echo $this->session->tempdata('attendance_error'); ?>
                                </div>
                            <?php
                            }
                            ?>
                        </fieldset>
                    <?php
                    } else {
                    ?>
                        <fieldset class="mt-2 mb-5 p-2">
                            <legend>Adding Atendance</legend>

                            <div class="mb-1">
                                <input type="hidden" name="txtuser" value="<?= $this->session->userdata('id'); ?>">
                                <input class="" type="text" name="txtsid" placeholder="Student ID">
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="txtlname" placeholder="Lastname">
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="txtfname" placeholder="Firstname">
                            </div>
                            <div class="mb-1">
                                <select class="select" name="gender">
                                    <option selected disabled>Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="section" placeholder="Section">
                            </div>
                            <div class="mb-1">
                                <select class="select" name="status">
                                    <option disabled>Status</option>
                                    <option value="Present">Present</option>
                                    <option value="Absent">Absent</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <input type="date" name="date">
                            </div>
                            <div class="mb-1">
                                <input class="" type="submit" name="btnadd" value="Add">
                            </div>

                            <?php
                            if ($this->session->tempdata('attendance_success')) {
                            ?>
                                <div class="success mt-2">
                                    <?php echo $this->session->tempdata('attendance_success'); ?>
                                </div>
                            <?php
                            } elseif ($this->session->tempdata('attendance_error')) {
                            ?>
                                <div class="danger mt-2 width-100">
                                    <?php echo $this->session->tempdata('attendance_error'); ?>
                                </div>
                            <?php
                            }
                            ?>
                        </fieldset>
                    <?php
                    }
                    ?>

                    <fieldset class="p-2">
                        <legend>Attendance List</legend>

                        <input type="date" name="listdate">
                        <select class="select" name="select-section">
                            <option selected>Section</option>
                            <?php
                            foreach ($sections as $section) {
                                echo "<option value='" . $section['section'] . "'>" . $section['section'] . "</option>";
                            }
                            ?>
                        </select>
                        <input class="" type="submit" name="btnselect" value="Select">
                        <input class="" type="text" name="txtsearch" placeholder="Search">
                        <input class="" type="submit" name="btnsearch" value="Search">
                        <button class="button" onclick="window.print();">Print</button>
                        <input class="" type="submit" name="btndeleteall" value="Delete All" onclick="return confirm('Are you sure you want to delete all student information in this section?')">

                        <div class="print-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="p-1">Student ID</th>
                                        <th class="p-1">Lastname</th>
                                        <th class="p-1">Firstname</th>
                                        <th class="p-1">Gender</th>
                                        <th class="p-1">Section</th>
                                        <th class="p-1">Status</th>
                                        <th class="p-1">Date</th>
                                        <th class="p-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($section_attendance)) {
                                        foreach ($section_attendance as $students) {
                                    ?>
                                            <tr>
                                                <td class="p-1"><?php echo $students['student_id'] ?></td>
                                                <td class="p-1"><?php echo $students['lastname'] ?></td>
                                                <td class="p-1"><?php echo $students['firstname'] ?></td>
                                                <td class="p-1"><?php echo $students['gender'] ?></td>
                                                <td class="p-1"><?php echo $students['section'] ?></td>
                                                <td class="p-1"><?php echo $students['status'] ?></td>
                                                <td class="p-1"><?php echo $students['date'] ?></td>
                                                <td class="p-1">
                                                    <a href="<?php echo site_url('main/EditAttendance/' . $students['user'] . '/' . $students['student_id'] . '/' . $students['date'] . '/'); ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="<?php echo site_url('main/DeleteAttendance/' . $students['user'] . '/' . $students['student_id'] . '/' . $students['date']); ?>" onclick="return confirm('Are you sure you want to delete this student attendance?')"><i class="fa-solid fa-trash-can"></i></a>
                                                </td>
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
                                                <td class="p-1"><?php echo $search['status'] ?></td>
                                                <td class="p-1"><?php echo $search['date'] ?></td>
                                                <td class="p-1">
                                                    <a href="<?php echo site_url('main/EditAttendance/' . $search['user'] . '/' . $search['student_id'] . '/' . $search['date'] . '/'); ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="<?php echo site_url('main/DeleteAttendance/' . $search['user'] . '/' . $search['student_id'] . '/' . $search['date']); ?>" onclick="return confirm('Are you sure you want to delete this student attendance?')"><i class="fa-solid fa-trash-can"></i></a>
                                                </td>
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
                        <?php
                        if ($this->session->tempdata('edit_success')) {
                        ?>
                            <div class="success mt-2">
                                <?php echo $this->session->tempdata('edit_success'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->tempdata('edit_error')) {
                        ?>
                            <div class="danger mt-2">
                                <?php echo $this->session->tempdata('edit_error'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->tempdata('listatt_success')) {
                        ?>
                            <div class="success mt-2">
                                <?php echo $this->session->tempdata('listatt_success'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->tempdata('listatt_error')) {
                        ?>
                            <div class="danger mt-2">
                                <?php echo $this->session->tempdata('listatt_error'); ?>
                            </div>
                        <?php
                        }
                        ?>
                    </fieldset>
                    <div class="page-info mt-1">
                        <?php
                        if (isset($pages)) {
                            if ($this->input->get('page-nr')) {
                                $page = $this->input->get('page-nr');
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
                        <a href="<?= base_url('main/attendance') . '?page-nr=1' ?>">First</a>
                        <?php
                        if ($this->input->get('page-nr') != NULL && $this->input->get('page-nr') > 1) {
                        ?>
                            <a href="<?= site_url('main/attendance') . '?page-nr=' . $this->input->get('page-nr') - 1 ?>">Previous</a>
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
                                    <a href="<?= base_url('main/attendance') . '?page-nr=' . $counter ?>"><?= $counter ?></a>
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
                        if ($this->input->get('page-nr') == NULL) {
                        ?>
                            <a href="<?= base_url('main/attendance') . '?page-nr=2' ?>">Next</a>
                            <?php
                        } else {
                            if ($this->input->get('page-nr') >= $pages) {
                            ?>
                                <a>Next</a>
                            <?php
                            } else {
                            ?>
                                <a href="<?= base_url('main/attendance') . '?page-nr=' . $this->input->get('page-nr') + 1 ?>">Next</a>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        if (isset($pages)) {
                            if ($pages > 0) {
                        ?>
                                <a href="<?= site_url('main/attendance') . '?page-nr=' . $pages ?>">Last</a>
                            <?php
                            } else {
                            ?>
                                <a>Last</a>
                            <?php
                            }
                        } else {
                            ?>
                            <a href="<?= site_url('main/attendance') ?>">Last</a>
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