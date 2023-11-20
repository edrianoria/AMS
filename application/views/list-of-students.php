<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ams_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>List of Students</title>
</head>

<?php
if ($this->input->get('student-page-nr')) {
    $id = $this->input->get('student-page-nr');
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
                <h1 class="">List of Students</h1>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn mr-2"><i class="fa-solid fa-bars"></i></label>
            </div>
            <div class="lists-content p-2">
                <form method="post" action="students">
                    <?php
                    if (isset($s_data)) {
                    ?>
                        <fieldset class="mt-2 mb-5 p-2">
                            <legend>Input Student Info</legend>

                            <div class="mb-1">
                                <input type="hidden" name="txtuser" value="<?= $this->session->userdata('id'); ?>">
                                <input class="" type="text" name="txtsid" value="<?= $s_data['student_id'] ?>">
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="txtlname" value="<?= $s_data['lastname'] ?>">
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="txtfname" value="<?= $s_data['firstname'] ?>">
                            </div>
                            <div class="mb-1">
                                <select class="select" name="gender">
                                    <option selected><?= $s_data['gender'] ?></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <input class="" type="text" name="section" value="<?= $s_data['section'] ?>">
                            </div>
                            <div class="mb-1">
                                <input class="" type="submit" name="btnadd" value="Add">
                            </div>

                            <?php
                            if ($this->session->tempdata('sinfo_success')) {
                            ?>
                                <div class="success mt-2">
                                    <?php echo $this->session->tempdata('sinfo_success'); ?>
                                </div>
                            <?php
                            } elseif ($this->session->tempdata('sinfo_error')) {
                            ?>
                                <div class="danger mt-2 width-100">
                                    <?php echo $this->session->tempdata('sinfo_error'); ?>
                                </div>
                            <?php
                            }
                            ?>
                        </fieldset>
                    <?php
                    } else {
                    ?>
                        <fieldset class="mt-2 mb-5 p-2">
                            <legend>Input Student Info</legend>

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
                                <input class="" type="submit" name="btnadd" value="Add">
                            </div>

                            <?php
                            if ($this->session->tempdata('sinfo_success')) {
                            ?>
                                <div class="success mt-2">
                                    <?php echo $this->session->tempdata('sinfo_success'); ?>
                                </div>
                            <?php
                            } elseif ($this->session->tempdata('sinfo_error')) {
                            ?>
                                <div class="danger mt-2 width-100">
                                    <?php echo $this->session->tempdata('sinfo_error'); ?>
                                </div>
                            <?php
                            }
                            ?>
                        </fieldset>
                    <?php
                    }
                    ?>
                    <fieldset class="p-2">
                        <legend>Student List</legend>

                        <div class="mb-1">
                            <select class="select" name="select-section">
                                <option selected>Section</option>
                                <?php
                                foreach ($sections as $section) {
                                    echo "<option value='" . $section['section'] . "'>" . $section['section'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-1">
                            <input class="" type="submit" name="btnselect" value="Select">
                        </div>
                        <div class="mb-1">
                            <input class="" type="text" name="txtsearch" placeholder="Search">
                        </div>
                        <div class="mb-1">
                            <input class="" type="submit" name="btnsearch" value="Search">
                        </div>
                        <div class="mb-1">
                            <button class="button" onclick="window.print()">Print</button>
                        </div>
                        <div class="mb-1">
                            <input class="" type="submit" name="btndeleteall" value="Delete All" onclick="return confirm('Are you sure you want to delete all student information in this section?')">
                        </div>

                        <div class="print-container mt-2">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="p-1">Student ID</th>
                                        <th class="p-1">Lastname</th>
                                        <th class="p-1">Firstname</th>
                                        <th class="p-1">Gender</th>
                                        <th class="p-1">Section</th>
                                        <th class="p-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($select_section)) {
                                        foreach ($select_section as $students) {
                                    ?>
                                            <tr>
                                                <td class="p-1"><?php echo $students['student_id'] ?></td>
                                                <td class="p-1"><?php echo $students['lastname'] ?></td>
                                                <td class="p-1"><?php echo $students['firstname'] ?></td>
                                                <td class="p-1"><?php echo $students['gender'] ?></td>
                                                <td class="p-1"><?php echo $students['section'] ?></td>
                                                <td class="p-1">
                                                    <a href="<?php echo site_url('main/edit/' . $students['user'] . '/' . $students['student_id'] . '/'); ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="<?php echo site_url('main/delete/' . $students['user'] . '/' . $students['student_id']); ?>" onclick="return confirm('Are you sure you want to delete this student information?')"><i class="fa-solid fa-trash-can"></i></a>
                                                    <a href="<?php echo site_url('main/attendance/' . $students['user'] . '/' . $students['student_id'] . '/'); ?>"><i class="fa-solid fa-clipboard-user"></i></a>
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
                                                <td class="p-1">
                                                    <a href="<?php echo site_url('main/edit/' . $search['user'] . '/' . $search['student_id'] . '/'); ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="<?php echo site_url('main/delete/' . $search['user'] . '/' . $search['student_id']); ?>" onclick="return confirm('Are you sure you want to delete this student information?')"><i class="fa-solid fa-trash-can"></i></a>
                                                    <a href="<?php echo site_url('main/attendance/' . $search['user'] . '/' . $search['student_id'] . '/'); ?>"><i class="fa-solid fa-clipboard-user"></i></a>
                                                </td>
                                            </tr>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="p-2">No Results.</td>
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
                        } elseif ($this->session->tempdata('delete_success')) {
                        ?>
                            <div class="success mt-2">
                                <?php echo $this->session->tempdata('delete_success'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->tempdata('delete_error')) {
                        ?>
                            <div class="danger mt-2">
                                <?php echo $this->session->tempdata('delete_error'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->tempdata('select_error')) {
                        ?>
                            <div class="danger mt-2">
                                <?php echo $this->session->tempdata('select_error'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->tempdata('search_error')) {
                            ?>
                                <div class="danger mt-2">
                                    <?php echo $this->session->tempdata('search_error'); ?>
                                </div>
                            <?php
                            }
                        ?>
                    </fieldset>

                    <div class="page-info mt-1">
                        <?php
                        if (isset($pages)) {
                            if ($this->input->get('student-page-nr')) {
                                $page = $this->input->get('student-page-nr');
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
                        <a href="<?= base_url('main/students') . '?student-page-nr=1' ?>">First</a>
                        <?php
                        if ($this->input->get('student-page-nr') != NULL && $this->input->get('student-page-nr') > 1) {
                        ?>
                            <a href="<?= site_url('main/students') . '?student-page-nr=' . $this->input->get('student-page-nr') - 1 ?>">Previous</a>
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
                                    <a href="<?= base_url('main/students') . '?student-page-nr=' . $counter ?>"><?= $counter ?></a>
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
                        if ($this->input->get('student-page-nr') == NULL) {
                        ?>
                            <a href="<?= base_url('main/students') . '?student-page-nr=2' ?>">Next</a>
                            <?php
                        } else {
                            if ($this->input->get('student-page-nr') >= $pages) {
                            ?>
                                <a>Next</a>
                            <?php
                            } else {
                            ?>
                                <a href="<?= base_url('main/students') . '?student-page-nr=' . $this->input->get('student-page-nr') + 1 ?>">Next</a>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        if (isset($pages)) {
                            if ($pages > 0) {
                        ?>
                                <a href="<?= site_url('main/students') . '?student-page-nr=' . $pages ?>">Last</a>
                            <?php
                            } else {
                            ?>
                                <a>Last</a>
                            <?php
                            }
                        } else {
                            ?>
                            <a href="<?= site_url('main/students') ?>">Last</a>
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