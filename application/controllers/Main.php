<?php

class Main extends CI_Controller
{

    //This function loads the login page and removes the session
    public function index()
    {
        $this->load->view('login');
        $this->load->helper('url');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('id');
    }

    //This function loads the home page
    public function home()
    {
        $this->load->view('home');
    }

    //This function loads the signup page
    public function signup()
    {
        $this->load->view('signup');
    }

    //This function loads the forgot page
    public function forgot()
    {
        $this->load->view('forgot');
    }

    //This function loads the email-verification page
    public function email()
    {
        $this->load->view('email-verification');
    }

    //This function loads the list-of-students page
    public function list()
    {
        $user = $this->session->userdata('id');
        $this->load->model('ams_model');

        $user_session = array(
            "user" => $user
        );

        $result['sections'] = $this->ams_model->LoadSection($user_session);
        $this->load->view("list-of-students", $result);
    }

    // This function loads the profile page
    public function profile()
    {
        $this->load->model('ams_model');
        $user = $this->session->userdata('id');

        $result['user_info'] = $this->ams_model->LoadUserInfo($user);

        if ($this->input->post('btnsave')) {
            $user = $this->session->userdata('id');
            $name = $this->input->post('txtname');
            $fileName = $_FILES['user-img']['name'];
            $fileSize = $_FILES['user-img']['size'];
            $tempName = $_FILES['user-img']['tmp_name'];

            $validImageExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = explode('.', $fileName);
            $imageExtension = strtolower(end($imageExtension));

            if (!in_array($imageExtension, $validImageExtension)) {
                $this->session->set_tempdata('image_error', 'Invalid image extension.', 1);

                redirect('main/profile?id=' . $user);
            } elseif ($fileSize > 1048576) {
                $this->session->set_tempdata('image_error', 'File size should not be greater than 1MB.');
                redirect('main/profile?id=' . $user);
            } else {
                $newImageName = uniqid();
                $newImageName .= '.' . $imageExtension;

                move_uploaded_file($tempName, 'assets/profiles/' . $newImageName);

                $profile_info = array(
                    "user" => $user,
                    "name" => $name,
                    "image" => $newImageName
                );

                $this->ams_model->UpdateUserInfo($profile_info);
            }
        }
        $this->load->view('profile', $result);
    }


    // This function loads the verify user page
    public function verifyuser()
    {
        $this->load->model('ams_model');
        $user = $this->session->userdata('id');
        $password = $this->input->post('txtpass');

        if ($this->input->post('btndverify')) {
            $verify_info = array(

                "user" => $user,
                "password" => $password

            );

            $this->ams_model->dVerifyUserPassword($verify_info);
        } elseif ($this->input->post('btneverify')) {
            $verify_info = array(

                "user" => $user,
                "password" => $password

            );

            $this->ams_model->eVerifyUserPassword($verify_info);
        } else {
            $this->load->view('verifyuser');
        }
    }

    public function verifylogin()
    {
        $this->load->model('ams_model');
        $email = $this->input->post('txtemail');
        $vcode = $this->input->post('txtvcode');
        $selector = $this->input->post('txtselector');

        if ($this->input->post('btnlogin')) {
            $verify_info = array(
                "email" => $email,
                "vcode" => $vcode,
                "selector" => $selector
            );

            $this->ams_model->VerifyAuthCode($verify_info);
        } elseif ($this->input->post('btnresend')) {

            $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $verify_info = array(
                "email" => $email,
                "vcode" => $verification_code,
                "selector" => $selector
            );

            $this->load->library('email');

            $config['protocol']    = 'smtp';
            $config['smtp_crypto'] = 'tls';
            $config['smtp_host']    = 'smtp.gmail.com';
            $config['smtp_port']    = '587';
            $config['smtp_timeout'] = '7';
            $config['smtp_user']    = 'amsecsu@gmail.com';
            $config['smtp_pass']    = 'adivdwyxwtswrcyv';
            $config['charset']    = 'utf-8';
            $config['newline']    = "\r\n";
            $config['mailtype'] = 'html'; // or text
            $config['validation'] = TRUE; // bool whether to validate email or not      

            $this->email->initialize($config);

            $this->email->from('amsecsu@gmail.com', 'amscsu');
            $this->email->to($email);
            $this->email->subject('Email Verification');
            $this->email->message('<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>');

            if ($this->email->send()) {
                $this->ams_model->ResendAuthCode($verify_info);
            }
        } else {
            $this->load->view('verification');
        }
    }

    public function total()
    {
        $user = $this->session->userdata('id');
        $this->load->model('ams_model');

        $start = 0;
        $rows_per_page = 20;

        $user_session = array(
            "user" => $user
        );

        $result['sections'] = $this->ams_model->AttendanceSection($user_session);

        if ($this->input->post('btnselect')) {
            $this->load->model('ams_model');

            $this->session->unset_userdata('search');

            $date1 = $this->input->post('date');
            $date2 = $this->input->post('date2');
            $section = $this->input->post('section');

            $total_info = array(
                "user" => $user,
                "date1" => date('Y-m-d', strtotime($date1)),
                "date2" => date('Y-m-d', strtotime($date2)),
                "section" => $section,
                "start" => $start,
                "end" => $rows_per_page
            );

            $result['total'] = $this->ams_model->SelectTotal($total_info);
            $records_count = $this->ams_model->SelectTotalRows($total_info);
            $result['pages'] = ceil($records_count / $rows_per_page);

            $this->session->set_userdata('section', $section);
            $this->session->set_userdata('date1', $date1);
            $this->session->set_userdata('date2', $date2);

            $this->load->view('list-present-absent', $result);
        } elseif ($this->input->post('btnsearch')) {
            $this->load->model('ams_model');

            $this->session->unset_userdata('section');

            $date1 = $this->input->post('date');
            $date2 = $this->input->post('date2');
            $search = $this->input->post('txtsearch');

            $total_info = array(
                "user" => $user,
                "date1" => date('Y-m-d', strtotime($date1)),
                "date2" => date('Y-m-d', strtotime($date2)),
                "search" => $search,
                "start" => $start,
                "end" => $rows_per_page
            );

            $result['search_result'] = $this->ams_model->SearchTotal($total_info);
            $records_count = $this->ams_model->SearchTotalRows($total_info);
            $result['pages'] = ceil($records_count / $rows_per_page);

            $this->session->set_userdata('search', $search);
            $this->session->set_userdata('date3', $date1);
            $this->session->set_userdata('date4', $date2);

            $this->load->view('list-present-absent', $result);
        } elseif ($this->input->get('total-page-nr')) {
            $user = $this->session->userdata('id');

            $page = $this->input->get('total-page-nr') - 1;
            $start = $page * $rows_per_page;

            $user_session = array(
                "user" => $user
            );

            $result['sections'] = $this->ams_model->AttendanceSection($user_session);

            if ($this->session->userdata('section')) {
                $this->load->model('ams_model');

                $date1 = $this->session->userdata('date1');
                $date2 = $this->session->userdata('date2');
                $section = $this->session->userdata('section');

                $total_info = array(
                    "user" => $user,
                    "date1" => date('Y-m-d', strtotime($date1)),
                    "date2" => date('Y-m-d', strtotime($date2)),
                    "section" => $section,
                    "start" => $start,
                    "end" => $rows_per_page
                );

                $result['total'] = $this->ams_model->SelectTotal($total_info);
                $records_count = $this->ams_model->SelectTotalRows($total_info);
                $result['pages'] = ceil($records_count / $rows_per_page);

                $this->load->view('list-present-absent', $result);
            } elseif ($this->session->userdata('search')) {
                $this->load->model('ams_model');

                $date1 = $this->session->userdata('date3');
                $date2 = $this->session->userdata('date4');
                $search = $this->session->userdata('search');

                $total_info = array(
                    "user" => $user,
                    "date1" => date('Y-m-d', strtotime($date1)),
                    "date2" => date('Y-m-d', strtotime($date2)),
                    "search" => $search,
                    "start" => $start,
                    "end" => $rows_per_page
                );

                $result['search_result'] = $this->ams_model->SearchTotal($total_info);
                $records_count = $this->ams_model->SearchTotalRows($total_info);
                $result['pages'] = ceil($records_count / $rows_per_page);

                $this->load->view('list-present-absent', $result);
            }
        } else {
            $this->load->view('list-present-absent', $result);
        }
    }

    public function edit()
    {
        $this->load->model('ams_model');

        $user = $this->uri->segment(3);
        $student_id = $this->uri->segment(4);

        $s_identity = array(
            "user" => $user,
            "student_id" => $student_id
        );

        $result['edit_info'] = $this->ams_model->EditStudentData($s_identity);
        $this->load->view('edit', $result);

        if ($this->input->post('btnupdate')) {

            $user_session = $this->input->post('txtuser');
            $sid = $this->input->post('txtsid');
            $lastname = $this->input->post('txtlname');
            $firstname = $this->input->post('txtfname');
            $gender = $this->input->post('gender');
            $section = $this->input->post('section');

            $updated_info = array(
                "sid" => $student_id,
                "user" => $user_session,
                "student_id" => $sid,
                "lastname" => $lastname,
                "firstname" => $firstname,
                "gender" => $gender,
                "section" => $section
            );

            $this->ams_model->UpdateStudentInfo($updated_info);
        }
    }

    public function delete()
    {
        $this->load->model('ams_model');

        $user = $this->uri->segment(3);
        $sid = $this->uri->segment(4);

        $s_identity = array(
            "user" => $user,
            "student_id" => $sid
        );

        $this->ams_model->DeleteStudentInfo($s_identity);
    }

    public function attendance()
    {
        $this->load->model('ams_model');

        $start = 0;
        $rows_per_page = 20;

        $user = $this->session->userdata('id');

        $user_session = array(
            "user" => $user
        );

        $result['sections'] = $this->ams_model->AttendanceSection($user_session);

        if ($this->uri->segment(3) != NULL && $this->uri->segment(4) != NULL) {
            $user = $this->uri->segment(3);
            $sid = $this->uri->segment(4);

            $s_identity = array(
                "user" => $user,
                "student_id" => $sid
            );

            $result['student_info'] = $this->ams_model->PassStudentInfo($s_identity);
            $this->load->view('list-of-attendance', $result);
        }

        if ($this->input->post('btnadd')) {
            $user = $this->input->post('txtuser');
            $student_id = $this->input->post('txtsid');
            $lastname = $this->input->post('txtlname');
            $firstname = $this->input->post('txtfname');
            $gender = $this->input->post('gender');
            $section = $this->input->post('section');
            $status = $this->input->post('status');
            $date = $this->input->post('date');

            $student_info = array(
                "user" => $user,
                "student_id" => $student_id,
                "lastname" => $lastname,
                "firstname" => $firstname,
                "gender" => $gender,
                "section" => $section,
                "status" => $status,
                "date" => date('Y-m-d', strtotime($date))
            );

            $result = $this->ams_model->AddAttendance($student_info);

            if ($result == TRUE) {
                $this->session->set_tempdata('attendance_success', 'Student attendance saved successfully.', 1);
                redirect('main/attendance');
            } elseif ($result == FALSE) {
                $user = $this->session->userdata('id');

                $user_session = array(
                    "user" => $user
                );

                $data['sections'] = $this->ams_model->AttendanceSection($user_session);

                $data['stud_data'] = array(
                    "student_id" => $student_id,
                    "lastname" => $lastname,
                    "firstname" => $firstname,
                    "gender" => $gender,
                    "section" => $section,

                );
                $this->session->set_tempdata('attendance_error', 'The inputted information did not matched to the student information.', 1);
                $this->load->view("list-of-attendance", $data);
            }
        } elseif ($this->input->post('btnselect')) {
            $user = $this->session->userdata('id');
            $section = $this->input->post('select-section');
            $date = $this->input->post('listdate');
            $this->session->unset_userdata('search');

            $user_session = array(
                "user" => $user
            );

            $result['sections'] = $this->ams_model->AttendanceSection($user_session);

            if (!empty($date)) {
                $attendance_info = array(
                    "user" => $user,
                    "section" => $section,
                    "date" => date('Y-m-d', strtotime($date)),
                    "start" => $start,
                    "end" => $rows_per_page
                );

                $result['section_attendance'] = $this->ams_model->SelectAttendance($attendance_info);
                $records_count = $this->ams_model->SelectAttendanceRows($attendance_info);
                $result['pages'] = ceil($records_count / $rows_per_page);

                $this->session->set_userdata('section', $section);
                $this->session->set_userdata('date', $date);

                $this->load->view("list-of-attendance", $result);
            } elseif (empty($date)) {
                $attendance_info = array(
                    "user" => $user,
                    "section" => $section,
                    "start" => $start,
                    "end" => $rows_per_page
                );

                $result['section_attendance'] = $this->ams_model->SelectAttendance($attendance_info);
                $records_count = $this->ams_model->SelectAttendanceRows($attendance_info);
                $result['pages'] = ceil($records_count / $rows_per_page);

                $this->session->set_userdata('section', $section);

                $this->load->view("list-of-attendance", $result);
            }
        } elseif ($this->input->post('btnsearch')) {
            $user = $this->session->userdata('id');
            $search = $this->input->post('txtsearch');
            $date = $this->input->post('listdate');
            $this->session->unset_userdata('section');

            $user_session = array(
                "user" => $user
            );

            $result['sections'] = $this->ams_model->AttendanceSection($user_session);

            if (!empty($date)) {
                $search_info = array(
                    "user" => $user,
                    "search" => $search,
                    "date" => date('Y-m-d', strtotime($date)),
                    "start" => $start,
                    "end" => $rows_per_page
                );

                $result['search_result'] = $this->ams_model->SearchAttendance($search_info);
                $records_count = $this->ams_model->SearchAttendanceRows($search_info);
                $result['pages'] = ceil($records_count / $rows_per_page);

                $this->session->set_userdata('search', $search);
                $this->session->set_userdata('date', $date);

                $this->load->view('list-of-attendance', $result);
            } elseif (empty($date)) {
                $search_info = array(
                    "user" => $user,
                    "search" => $search,
                    "start" => $start,
                    "end" => $rows_per_page
                );

                $result['search_result'] = $this->ams_model->SearchAttendance($search_info);
                $records_count = $this->ams_model->SearchAttendanceRows($search_info);
                $result['pages'] = ceil($records_count / $rows_per_page);

                $this->session->set_userdata('search', $search);

                $this->load->view('list-of-attendance', $result);
            }
        } elseif ($this->input->post('btndeleteall')) {
            $user = $this->session->userdata('id');
            $section = $this->input->post('select-section');

            $section_info = array(
                "user" => $user,
                "section" => $section
            );

            $result = $this->ams_model->AttendanceDeleteAll($section_info);

            if ($result == TRUE) {
                $this->session->set_tempdata('listatt_success', 'All attendance in ' . $section . ' has been deleted successfully.', 1);
                redirect('main/attendance');
            }
        } elseif ($this->input->get('page-nr')) {
            $user = $this->session->userdata('id');

            $page = $this->input->get('page-nr') - 1;
            $start = $page * $rows_per_page;

            $user_session = array(
                "user" => $user
            );

            $result['sections'] = $this->ams_model->AttendanceSection($user_session);

            if ($this->session->userdata('date')) {
                if ($this->session->userdata('section')) {
                    $section = $this->session->userdata('section');
                    $date = $this->session->userdata('date');

                    $attendance_info = array(
                        "user" => $user,
                        "section" => $section,
                        "date" => date('Y-m-d', strtotime($date)),
                        "start" => $start,
                        "end" => $rows_per_page
                    );

                    $records_count = $this->ams_model->SelectAttendanceRows($attendance_info);

                    $result['pages'] = ceil($records_count / $rows_per_page);

                    $result['section_attendance'] = $this->ams_model->SelectAttendance($attendance_info);
                    $this->load->view('list-of-attendance', $result);
                } elseif ($this->session->userdata('search')) {
                    $search = $this->session->userdata('search');
                    $date = $this->session->userdata('date');

                    $search_info = array(
                        "user" => $user,
                        "search" => $search,
                        "date" => date('Y-m-d', strtotime($date)),
                        "start" => $start,
                        "end" => $rows_per_page
                    );

                    $records_count = $this->ams_model->SearchAttendanceRows($search_info);

                    $result['pages'] = ceil($records_count / $rows_per_page);

                    $result['search_result'] = $this->ams_model->SearchAttendance($search_info);
                    $this->load->view('list-of-attendance', $result);
                }
            } elseif ($this->session->userdata('date') == NULL) {
                if ($this->session->userdata('section')) {
                    $section = $this->session->userdata('section');

                    $attendance_info = array(
                        "user" => $user,
                        "section" => $section,
                        "start" => $start,
                        "end" => $rows_per_page
                    );

                    $records_count = $this->ams_model->SelectAttendanceRows($attendance_info);

                    $result['pages'] = ceil($records_count / $rows_per_page);

                    $result['section_attendance'] = $this->ams_model->SelectAttendance($attendance_info);
                    $this->load->view('list-of-attendance', $result);
                } elseif ($this->session->userdata('search')) {
                    $search = $this->session->userdata('search');

                    $search_info = array(
                        "user" => $user,
                        "search" => $search,
                        "start" => $start,
                        "end" => $rows_per_page
                    );

                    $records_count = $this->ams_model->SearchAttendanceRows($search_info);

                    $result['pages'] = ceil($records_count / $rows_per_page);

                    $result['search_result'] = $this->ams_model->SearchAttendance($search_info);
                    $this->load->view('list-of-attendance', $result);
                }
            }
        } elseif ($this->uri->segment(3) == NULL && $this->uri->segment(4) == NULL && $this->input->get('page-nr') == NULL) {
            $this->session->unset_userdata('date');
            $this->session->unset_userdata('section');
            $this->session->unset_userdata('search');
            $this->load->view("list-of-attendance", $result);
        }
    }

    public function DeleteAttendance()
    {
        $this->load->model('ams_model');

        $user = $this->uri->segment(3);
        $student_id = $this->uri->segment(4);
        $date = $this->uri->segment(5);

        $a_info = array(
            "user" => $user,
            "student_id" => $student_id,
            "date" => $date
        );

        $this->ams_model->DeleteAttendance($a_info);
    }

    public function EditAttendance()
    {
        $this->load->model('ams_model');

        $user = $this->uri->segment(3);
        $student_id = $this->uri->segment(4);
        $prev_date = $this->uri->segment(5);

        $sa_info = array(
            "user" => $user,
            "student_id" => $student_id,
            "date" => $prev_date
        );

        $result['edit_info'] = $this->ams_model->EditAttendanceData($sa_info);
        $this->load->view('edit-attendance', $result);

        if ($this->input->post('btnupdate')) {

            $status = $this->input->post('status');
            $date = $this->input->post('date');

            $updated_info = array(
                "sid" => $student_id,
                "user" => $user,
                "prev_date" => $prev_date,
                "status" => $status,
                "date" => date('Y-m-d', strtotime($date))
            );

            $this->ams_model->UpdateAttendanceInfo($updated_info);
        }
    }

    /*
    This function gets the information inputted by the user and insert
    it into the database and also sends a verification code to user's
    email.
    */
    public function get_info()
    {
        $password = $this->input->post("txtpword");
        $cpassword = $this->input->post("txtcpword");
        $email = $this->input->post("txtemail");
        $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $data = array(
            "name" => $this->input->post("txtname"),
            "username" => $this->input->post("txtuname"),
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "verification" => $verification_code
        );

        $this->load->model('ams_model');

        $result1 = $this->ams_model->CheckUserEmail($data);

        if ($result1 == TRUE) {
            $result['userdata'] = array(
                "name" => $this->input->post('txtname'),
                "username" => $this->input->post('txtuname'),
                "email" => $email
            );

            $this->session->set_tempdata('signup_error', 'The username or email has already been taken.', 1);
            $this->load->view("signup", $result);
        } elseif ($result1 == FALSE) {
            $this->form_validation->set_rules('txtpword', 'Passowrd', 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[0-9]/]');

            if ($this->form_validation->run() == FALSE) {
                $result['userdata'] = array(
                    "name" => $this->input->post('txtname'),
                    "username" => $this->input->post('txtuname'),
                    "email" => $email
                );

                $this->session->set_tempdata('signup_error', 'Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters.', 1);
                $this->load->view("signup", $result);
            } else {
                if ($password != $cpassword) {
                    $result['userdata'] = array(
                        "name" => $this->input->post('txtname'),
                        "username" => $this->input->post('txtuname'),
                        "email" => $email
                    );

                    $this->session->set_tempdata('signup_error', 'Your password did not matched', 1);
                    $this->load->view('signup', $result);
                } else {
                    $this->load->library('email');

                    $config['protocol']    = 'smtp';
                    $config['smtp_crypto'] = 'tls';
                    $config['smtp_host']    = 'smtp.gmail.com';
                    $config['smtp_port']    = '587';
                    $config['smtp_timeout'] = '7';
                    $config['smtp_user']    = 'amsecsu@gmail.com';
                    $config['smtp_pass']    = 'adivdwyxwtswrcyv';
                    $config['charset']    = 'utf-8';
                    $config['newline']    = "\r\n";
                    $config['mailtype'] = 'html'; // or text
                    $config['validation'] = TRUE; // bool whether to validate email or not      

                    $this->email->initialize($config);

                    $this->email->from('amsecsu@gmail.com', 'amscsu');
                    $this->email->to($email);
                    $this->email->subject('Email Verification');
                    $this->email->message('<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>');

                    if ($this->email->send()) {
                        $this->session->set_tempdata('email_success', 'Email sent successfully.', 1);
                        $this->session->set_flashdata('email', $email);
                        $this->ams_model->InsertData($data);
                        redirect(site_url('main/email'));
                    } else {
                        $this->session->set_tempdata('email_error', 'Problem occured. Please try again.', 1);
                        redirect(site_url('main/email'));
                    }
                }
            }
        }
    }

    /*
    This function verifies if the email inputed by the user exist.
    */
    public function verification()
    {
        $email = $this->input->post('email');
        $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        if ($this->input->post('btnverify')) {
            // This code will triggered if the user clicked the verify email button
            $this->load->model('ams_model');

            $everification = array(
                "email" => $email,
                "vcode" => $this->input->post('txtvcode')
            );

            $this->ams_model->UpdateVerified($everification);
        } elseif ($this->input->post('btnresend')) {
            // This code will triggered if the user clicked the re-send button
            $this->load->model('ams_model');

            $everification = array(
                "email" => $email,
                "vcode" => $verification_code
            );

            $this->load->library('email');

            $config['protocol']    = 'smtp';
            $config['smtp_crypto'] = 'tls';
            $config['smtp_host']    = 'smtp.gmail.com';
            $config['smtp_port']    = '587';
            $config['smtp_timeout'] = '7';
            $config['smtp_user']    = 'amsecsu@gmail.com';
            $config['smtp_pass']    = 'adivdwyxwtswrcyv';
            $config['charset']    = 'utf-8';
            $config['newline']    = "\r\n";
            $config['mailtype'] = 'html'; // or text
            $config['validation'] = TRUE; // bool whether to validate email or not      

            $this->email->initialize($config);

            $this->email->from('amsecsu@gmail.com', 'amscsu');
            $this->email->to($email);
            $this->email->subject('Email Verification');
            $this->email->message('<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>');

            if ($this->email->send()) {
                $this->ams_model->UpdateCode($everification);
                $this->session->set_flashdata('email', $email);
                $this->session->set_tempdata('email_success', 'Verification Code Re-sent successfully.', 1);
                $this->load->view('email-verification');
            } else {
                $this->session->set_tempdata('email_error', 'Problem occured. Please try again', 1);
                redirect('main/email');
            }
        }
    }

    /*
    This function sends the information inputted by the user
    in VerifyUser function in Ams_model Model.
    */
    public function login()
    {
        $this->load->model('ams_model');
        $username = $this->input->post('txtuname');
        $password = $this->input->post('txtpword');

        $ulogin = array(

            "username" => $username,
            "password" => $password
        );

        $this->ams_model->VerifyUser($ulogin);
    }

    public function students()
    {
        $user = $this->input->post('txtuser');
        $student_id = $this->input->post('txtsid');
        $lastname = $this->input->post('txtlname');
        $firstname = $this->input->post('txtfname');
        $gender = $this->input->post('gender');
        $section = $this->input->post('section');

        $start = 0;
        $rows_per_page = 20;

        if ($this->input->post('btnadd')) {
            $this->load->model('ams_model');

            $sinfo = array(
                "user" => $user,
                "student_id" => $student_id,
                "lastname" => $lastname,
                "firstname" => $firstname,
                "gender" => $gender,
                "section" => $section
            );

            $result = $this->ams_model->AddStudent($sinfo);

            if ($result == TRUE) {
                $this->session->set_tempdata('sinfo_success', 'Student information saved successfully.', 1);
                redirect('main/list');
            } elseif ($result == FALSE) {
                $user = $this->session->userdata('id');
                $this->load->model('ams_model');

                $user_session = array(
                    "user" => $user
                );

                $data['sections'] = $this->ams_model->LoadSection($user_session);

                $data['s_data'] = array(
                    "student_id" => $student_id,
                    "lastname" => $lastname,
                    "firstname" => $firstname,
                    "gender" => $gender,
                    "section" => $section

                );
                $this->session->set_tempdata('sinfo_error', 'This Student ID already exist.', 1);
                $this->load->view("list-of-students", $data);
            }
        } elseif ($this->input->post('btnselect')) {
            $this->load->model('ams_model');
            $user = $this->session->userdata('id');
            $section = $this->input->post('select-section');
            $this->session->unset_userdata('search');

            $user_session = array(
                "user" => $user
            );

            $result['sections'] = $this->ams_model->LoadSection($user_session);

            $stud_section = array(
                "user" => $user,
                "section" => $section,
                "start" => $start,
                "end" => $rows_per_page
            );

            $records_count = $this->ams_model->SelectSectionRows($stud_section);
            $result['pages'] = ceil($records_count / $rows_per_page);
            $result['select_section'] = $this->ams_model->SelectSection($stud_section);

            $this->session->set_userdata('section', $section);

            $this->load->view("list-of-students", $result);
        } elseif ($this->input->post('btnsearch')) {
            $this->load->model('ams_model');
            $user = $this->session->userdata('id');
            $search = $this->input->post('txtsearch');
            $this->session->unset_userdata('section');

            $search_info = array(
                "user" => $user,
                "search" => $search,
                "start" => $start,
                "end" => $rows_per_page
            );

            $records_count = $this->ams_model->SearchSectionRows($search_info);
            $result['pages'] = ceil($records_count / $rows_per_page);
            $result['search_result'] = $this->ams_model->SearchStudent($search_info);

            $this->session->set_userdata('search', $search);

            $this->load->view('list-of-students', $result);
        } elseif ($this->input->get('student-page-nr')) {
            $this->load->model('ams_model');
            $user = $this->session->userdata('id');

            $page = $this->input->get('student-page-nr') - 1;
            $start = $page * $rows_per_page;

            $user_session = array(
                "user" => $user
            );

            $result['sections'] = $this->ams_model->AttendanceSection($user_session);

            if ($this->session->userdata('section')) {
                $section = $this->session->userdata('section');

                $stud_section = array(
                    "user" => $user,
                    "section" => $section,
                    "start" => $start,
                    "end" => $rows_per_page
                );

                $records_count = $this->ams_model->SelectSectionRows($stud_section);
                $result['pages'] = ceil($records_count / $rows_per_page);
                $result['select_section'] = $this->ams_model->SelectSection($stud_section);


                $this->load->view("list-of-students", $result);
            } elseif ($this->session->userdata('search')) {
                $search = $this->session->userdata('search');

                $search_info = array(
                    "user" => $user,
                    "search" => $search,
                    "start" => $start,
                    "end" => $rows_per_page
                );

                $records_count = $this->ams_model->SearchSectionRows($search_info);
                $result['pages'] = ceil($records_count / $rows_per_page);
                $result['search_result'] = $this->ams_model->SearchStudent($search_info);

                $this->load->view("list-of-students", $result);
            }
        } elseif ($this->input->post('btndeleteall')) {
            $this->load->model('ams_model');
            $user = $this->session->userdata('id');
            $section = $this->input->post('select-section');

            $stud_section = array(
                "user" => $user,
                "section" => $section
            );

            $this->ams_model->DeleteAllInfo($stud_section);
        } elseif ($this->input->get('student-page-nr') == NULL) {
            $this->session->unset_userdata('section');
            $this->session->unset_userdata('search');
            redirect('main/list');
        }
    }
    public function reset()
    {
        if ($this->input->post('btnsubmit')) {
            $this->load->model('ams_model');
            $this->load->helper('date');

            $user_email = $this->input->post('txtemail');

            $selector = bin2hex(random_bytes(8));
            $token = bin2hex(random_bytes(32));

            $time = date('H:i:s', now());

            $url = site_url("main/newpassword?selector={$selector}&validator={$token}");

            $message = '<p>We received a password reset request. The link to reset your password is below. If you did not
            make this request, you can ignore this email</p>';
            $message .= '<p>Here is your password reset link: </br>';
            $message .= '<a href="' . $url . '">' . $url . '</a></p><br>';

            $this->load->library('email');

            $config['protocol']    = 'smtp';
            $config['smtp_crypto'] = 'tls';
            $config['smtp_host']    = 'smtp.gmail.com';
            $config['smtp_port']    = '587';
            $config['smtp_timeout'] = '7';
            $config['smtp_user']    = 'amsecsu@gmail.com';
            $config['smtp_pass']    = 'adivdwyxwtswrcyv';
            $config['charset']    = 'utf-8';
            $config['newline']    = "\r\n";
            $config['mailtype'] = 'html'; // or text
            $config['validation'] = TRUE; // bool whether to validate email or not      

            $this->email->initialize($config);

            $this->email->from('amsecsu@gmail.com', 'amscsu');
            $this->email->to($user_email);
            $this->email->subject('Password Reset');
            $this->email->message($message);

            if ($this->email->send()) {
                $pword_reset = array(
                    "email" => $user_email,
                    "selector" => $selector,
                    "token" => $token,
                    "created_at" => $time
                );
                $this->ams_model->InsertResetRequest($pword_reset);
            } else {
                $this->session->set_tempdata('email_error', 'Problem occured. Please try again', 1);
                redirect('main/forgot');
            }
        }
    }

    public function newpassword()
    {
        if ($this->input->get('selector') && $this->input->get('validator')) {
            $this->load->model('ams_model');
            $selector = $this->input->get('selector');
            $validator = $this->input->get('validator');

            $reset_request = array(
                "selector" => $selector,
                "validator" => $validator
            );

            $rows = $this->ams_model->GetCreatedAt($reset_request);
            if (isset($rows)) {
                foreach ($rows as $row) {
                    $current_time = new DateTime();
                    $created_at = new DateTime($row['created_at']);
                    $interval = $current_time->diff($created_at);

                    if ($interval->s > 300 || $interval->i > 5) {
                        $this->ams_model->DeleteResetRequest($reset_request);
                    } else {
                        $this->load->view('reset-password');
                    }
                }
            } else {
                $this->session->set_tempdata('email_error', 'Your reset password link is already expired. Please try again.', 1);
                redirect('main/forgot');
            }
        } elseif ($this->input->get('selector') == NULL && $this->input->get('validator') == NULL) {
            redirect('main/forgot');
        }
    }

    public function resetpassword()
    {
        $this->load->model('ams_model');
        $selector = $this->input->post('selector');
        $validator = $this->input->post('validator');
        $pass = $this->input->post('txtpass');
        $passrepeat = $this->input->post('txtpassrepeat');

        $this->form_validation->set_rules('txtpass', 'Passowrd', 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[0-9]/]');

        if ($this->form_validation->run() == FALSE) {

            $this->session->set_tempdata('reset_error', 'Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters.', 1);
            redirect('main/newpassword?selector=' . $selector . '&validator=' . $validator);
        } elseif ($pass != $passrepeat) {
            $this->session->set_tempdata('reset_error', 'Your password and confirm password did not matched.', 1);
            redirect('main/newpassword?selector=' . $selector . '&validator=' . $validator);
        } else {
            $request_info = array(
                "selector" => $selector,
                "validator" => $validator,
                "password" => password_hash($pass, PASSWORD_DEFAULT)
            );

            $this->ams_model->ResetPassword($request_info);
        }
    }
}
