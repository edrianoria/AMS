<?php

class Ams_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    // This function inserts the user information in tblusers table.
    public function InsertData($data)
    {
        $name = $data['name'];
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $verification_code = $data['verification'];

        $sql = "INSERT INTO tblusers(name, username, email, password, verification_code) VALUES ('$name', '$username', '$email', '$password', '$verification_code')";
        $this->db->query($sql);

        if ($this->db->affected_rows() == 0) {
            $this->session->set_tempdata('signup_error', 'Problem occured on creating account. Please try again.', 1);
            redirect('main/email');
        }
    }

    // This function checks if the user or email is already used.
    public function CheckUserEmail($data)
    {
        $username = $data['username'];
        $email = $data['email'];

        $sql = "SELECT * FROM tblusers WHERE username = '$username' OR email = '$email'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $result = TRUE;
            return $result;
        } else {
            $result = FALSE;
            return $result;
        }
    }

    // This function updates the verification code column in tblusers table.
    public function UpdateCode($everification)
    {
        $email = $everification['email'];
        $verification_code = $everification['vcode'];

        $sql = "UPDATE tblusers SET verification_code = '$verification_code' WHERE email = '$email'";
        $this->db->query($sql);
    }

    // This function updates the email verified at column in tblusers table.
    public function UpdateVerified($everification)
    {
        $email = $everification['email'];
        $verification_code = $everification['vcode'];

        $sql = "UPDATE tblusers SET email_verified_at = NOW() WHERE email = '$email' AND verification_code = '$verification_code'";
        $this->db->query($sql);


        if ($this->db->affected_rows() == 0) {
            $this->session->set_tempdata('email_error', 'Verification code did not matched.', 1);
            $this->session->set_flashdata('email', $email);
            redirect('main/email');
        } else {
            $this->session->set_tempdata('login_success', 'Your email has been verified successfully', 1);
            redirect('main/index');
        }
    }

    /*
    This function gets all the data that matches into the user's inputted username and
    check if the user's inputted password matches to the user's password in the database. This
    also checks if the email_verified_at is NULL or not NULL.
    */
    public function VerifyUser($ulogin)
    {
        $username = $ulogin['username'];
        $password = $ulogin['password'];

        $sql = "SELECT * FROM tblusers WHERE username = '$username'";
        $result = $this->db->query($sql);
        $rows = $result->result_array();

        $user = $result->row_object();

        if ($this->db->affected_rows() > 0) {
            // This code will triggered if there is an affected row in amsdb database.
            if (!password_verify($password, $user->password)) {
                /* This code will trigger if the user's inputted password did not matched to the
                    user's password in the database.
                */
                $this->session->set_tempdata('login_error', 'Incorrect username or password.', 1);
                $this->session->set_tempdata('username', $username, 1);
                redirect('main/index');
            } elseif ($user->email_verified_at == NULL) {
                // This code will trigger if the user's email still not verified.
                foreach ($rows as $row) {
                    $this->session->set_flashdata('email', $row['email']);
                }
                redirect('main/email');
            } else {
                /* This code will trigger if the user's inputted password
                    matches to the user's password in the database and if the user's
                    email is verified.
                    */
                if($user->authentication == "Enabled") {
                    foreach($rows as $row) {
                        $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

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
                        $this->email->to($row['email']);
                        $this->email->subject('Email Verification');
                        $this->email->message('<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>');
            
                        if ($this->email->send()) {
                            $user = $row['id'];
                            $email = $row['email'];
                            $selector = bin2hex(random_bytes(8));

                            $sql = "INSERT INTO authentication (user,email,selector,code) VALUES ('$user', '$email', '$selector', '$verification_code')";
                            $this->db->query($sql);
                            
                            if($this->db->affected_rows() > 0) {
                                $this->session->set_flashdata('email', $row['email']);
                                $this->session->set_flashdata('selector', $selector);
                                $this->session->set_tempdata('email_success', 'Email has been sent successfully.', 1);
                                redirect('main/verifylogin');
                            }
                        }
                    }
                }
                else {
                    foreach ($rows as $row) {
                        $this->session->set_userdata('name', $row['name']);
                        $this->session->set_userdata('id', $row['id']);
                        $this->session->set_userdata('profile', $row['profile_img']);
                    }
                    redirect('main/home');
                }
            }
        } else {
            // This code will trigger if there is no row affected in the amsdb database.
            $this->session->set_tempdata('login_error', 'Problem occured. Please try again.', 1);
            redirect('main/index');
        }
    }

    public function VerifyAuthCode($verify_info) {
        $email = $verify_info['email'];
        $vcode = $verify_info['vcode'];
        $selector = $verify_info['selector'];

        $sql = "SELECT * FROM authentication WHERE email = '$email' AND selector = '$selector'";
        $result = $this->db->query($sql);
        $rows = $result->result_array();

        if($this->db->affected_rows() > 0) {
            foreach($rows as $row) {
                if($row['code'] == $vcode) {
                    $sql = "DELETE FROM authentication WHERE email = '$email'";
                    $this->db->query($sql);

                    if($this->db->affected_rows() > 0) {
                        $sql = "SELECT * FROM tblusers WHERE email = '$email'";
                        $result = $this->db->query($sql);

                        $rows = $result->result_array(); 

                        if($this->db->affected_rows() > 0) {
                           foreach($rows as $row) {
                            $this->session->set_userdata('name', $row['name']);
                            $this->session->set_userdata('id', $row['id']);
                            $this->session->set_userdata('profile', $row['profile_img']);
                            redirect('main/home');
                           }
                        }
                    }
                }
                else {
                    $this->session->set_tempdata('verify_error', 'Verification code did not matched.', 1);
                    $this->session->set_flashdata('email', $email);
                    $this->session->set_flashdata('selector', $selector);
                    redirect('main/verifylogin');
                }
            }
        }
    }

    public function ResendAuthCode($verify_info) {
        $email = $verify_info['email'];
        $vcode = $verify_info['vcode'];
        $selector = $verify_info['selector'];

        $sql = "UPDATE authentication SET code = '$vcode' WHERE email = '$email' AND selector = '$selector'";
        $this->db->query($sql);

        if($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('email', $email);
            $this->session->set_flashdata('selector', $selector);
            $this->session->set_tempdata('email_success', 'Email has been sent successfully.', 1);
            redirect('main/verifylogin');
        }
    }

    public function dVerifyUserPassword($verify_info) {
        $user = $verify_info['user'];
        $password = $verify_info['password'];

        $sql = "SELECT * FROM tblusers WHERE id = '$user'";
        $result = $this->db->query($sql);

        $rows = $result->result_array();
        $user_info = $result->row_object();

        if($this->db->affected_rows() > 0) {
            if(!password_verify($password, $user_info->password)) {
                foreach($rows as $row) {
                    $this->session->set_tempdata('verify_error', 'Password did not matched.', 1);
                    redirect('main/verifyuser?id='.$row['id'].'&authentication='.$row['authentication']);
                }
            }
            elseif(password_verify($password, $user_info->password)) {
                $sql = "UPDATE tblusers SET authentication = 'Enabled' WHERE id = '$user'";
                $this->db->query($sql);

                if($this->db->affected_rows() > 0) {
                    foreach($rows as $row) {
                        $this->session->set_tempdata('save_success', 'The 2-step verification is now enabled', 1);
                        redirect('main/profile?id='.$row['id']);
                    }
                }
            }
        }
    }

    public function eVerifyUserPassword($verify_info) {
        $user = $verify_info['user'];
        $password = $verify_info['password'];

        $sql = "SELECT * FROM tblusers WHERE id = '$user'";
        $result = $this->db->query($sql);

        $rows = $result->result_array();
        $user_info = $result->row_object();

        if($this->db->affected_rows() > 0) {
            if(!password_verify($password, $user_info->password)) {
                foreach($rows as $row) {
                    $this->session->set_tempdata('verify_error', 'Password did not matched.', 1);
                    redirect('main/verifyuser?id='.$row['id'].'&authentication='.$row['authentication']);
                }
            }
            elseif(password_verify($password, $user_info->password)) {
                $sql = "UPDATE tblusers SET authentication = 'Disabled' WHERE id = '$user'";
                $this->db->query($sql);

                if($this->db->affected_rows() > 0) {
                    foreach($rows as $row) {
                        $this->session->set_tempdata('save_success', 'The 2-step verification is now disabled', 1);
                        redirect('main/profile?id='.$row['id']);
                    }
                }
            }
        }
    }

    public function LoadUserInfo($user)
    {
        $user_id = $user;

        $sql = "SELECT * FROM tblusers WHERE id = '$user_id'";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        }
    }

    public function UpdateUserInfo($profile_info)
    {
        $user = $profile_info['user'];
        $name = $profile_info['name'];
        $image = $profile_info['image'];

        $sql = "SELECT * FROM tblusers WHERE id = '$user'";
        $result = $this->db->query($sql);

        $rows = $result->result_array();

        if ($this->db->affected_rows() > 0) {

            foreach ($rows as $row) {

                unlink('assets/profiles/' . $row['profile_img']);
            }
            $sql = "UPDATE tblusers SET profile_img= '$image', name= '$name' WHERE id = '$user'";
            $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {
                $sql = "SELECT * FROM tblusers WHERE id = '$user'";
                $result = $this->db->query($sql);

                $rows = $result->result_array();

                foreach ($rows as $row) {
                    $this->session->set_userdata('profile', $row['profile_img']);
                }
                $this->session->set_tempdata('save_success', 'Profile updated successfully', 1);
                redirect('main/profile?id=' . $user);
            } else {
                $this->session->set_tempdata('save_error', 'Problem occured. Please try again.', 1);
                redirect('main/profile?id=' . $user);
            }
        } else {
            $this->session->set_tempdata('save_error', 'Problem occured. Please try again.', 1);
            redirect('main/profile?id=' . $user);
        }
    }

    public function AddStudent($sinfo)
    {
        $user = $sinfo['user'];
        $student_id = $sinfo['student_id'];
        $lastname = $sinfo['lastname'];
        $firstname = $sinfo['firstname'];
        $gender = $sinfo['gender'];
        $section = $sinfo['section'];

        $sql = "SELECT * FROM student_info WHERE user = '$user' AND student_id = '$student_id'";
        $this->db->query($sql);

        if ($this->db->affected_rows() == 0) {
            $sqll = "INSERT INTO student_info VALUES('$user', '$student_id', '$lastname', '$firstname', '$gender', '$section')";
            $result = $this->db->query($sqll);

            return $result;
        } else {
            $result = FALSE;

            return $result;
        }
    }

    public function LoadSection($user_session)
    {
        $user = $user_session['user'];

        $sql = "SELECT section FROM student_info WHERE user = '$user' GROUP BY section";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        }
    }

    public function SelectSectionRows($stud_section)
    {
        $user = $stud_section['user'];
        $section = $stud_section['section'];

        $sql = "SELECT * FROM student_info WHERE user = '$user' AND section = '$section'";
        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->num_rows();
        }
    }

    public function SearchSectionRows($search_info)
    {
        $user = $search_info['user'];
        $search = $search_info['search'];

        $sql = "SELECT * FROM student_info WHERE user = '$user' AND lastname = '$search' OR  user = '$user' AND student_id = '$search'";
        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->num_rows();
        }
    }

    public function SelectSection($stud_section)
    {
        $user = $stud_section['user'];
        $section = $stud_section['section'];
        $start = $stud_section['start'];
        $end = $stud_section['end'];

        $sql = "SELECT * FROM student_info WHERE user = '$user' AND section = '$section' ORDER BY lastname ASC LIMIT $start, $end";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        } else {
            $this->session->set_tempdata('select_error', 'Problem occured. Please try again.', 1);
            redirect('main/list');
        }
    }

    public function SearchStudent($search_info)
    {
        $user = $search_info['user'];
        $search = $search_info['search'];
        $start = $search_info['start'];
        $end = $search_info['end'];

        $sql = "SELECT * FROM student_info WHERE user = '$user' AND student_id = '$search' OR user = '$user' AND lastname = '$search' ORDER BY lastname LIMIT $start, $end";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        }
        else {
            $this->session->set_tempdata('search_error', 'Problem occured. Please try again.', 1);
            redirect('main/list');
        }
    }

    public function EditStudentData($s_identity)
    {
        $user = $s_identity['user'];
        $student_id = $s_identity['student_id'];

        $sql = "SELECT * FROM student_info WHERE user ='$user' AND student_id = '$student_id'";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        }
    }

    public function UpdateStudentInfo($updated_info)
    {
        $sid = $updated_info['sid'];
        $user = $updated_info['user'];
        $student_id = $updated_info['student_id'];
        $lastname = $updated_info['lastname'];
        $firstname = $updated_info['firstname'];
        $gender = $updated_info['gender'];
        $section = $updated_info['section'];

        $sql = "UPDATE student_info SET student_id = '$student_id', lastname = '$lastname', firstname = '$firstname', gender = '$gender', section = '$section' WHERE user = '$user' AND student_id = '$sid'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_tempdata('edit_success', 'Student information updated successfully', 1);
            redirect('main/list');
        } else {
            $this->session->set_tempdata('edit_error', 'Problem occured. Please try again.', 1);
            redirect('main/list');
        }
    }

    public function DeleteStudentInfo($s_identity)
    {
        $user = $s_identity['user'];
        $student_id = $s_identity['student_id'];

        $sql = "DELETE FROM student_info WHERE user = '$user' AND student_id = '$student_id'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_tempdata('delete_success', 'Student information deleted successfully.', 1);
            redirect('main/list');
        } else {
            $this->session->set_tempdata('delete_error', 'Problem occured. Please try again.', 1);
            redirect('main/list');
        }
    }

    public function DeleteAllInfo($stud_section)
    {
        $user = $stud_section['user'];
        $section = $stud_section['section'];

        $sql = "DELETE FROM student_info WHERE user = '$user' AND section = '$section'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_tempdata('delete_success', 'All student information in ' . $section . 'has been deleted successfully.', 1);
            redirect('main/list');
        } else {
            $this->session->set_tempdata('delete_error', 'Problem occured. Please try again.', 1);
            redirect('main/list');
        }
    }

    public function PassStudentInfo($s_identity)
    {
        $user = $s_identity['user'];
        $student_id = $s_identity['student_id'];

        $sql = "SELECT * FROM student_info WHERE user = '$user' AND student_id = '$student_id'";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        } else {
            $this->session->set_tempdata('attendance_error', 'Problem occured. Please try again.', 1);
            redirect('main/attendance');
        }
    }

    public function AddAttendance($student_info)
    {
        $user = $student_info['user'];
        $student_id = $student_info['student_id'];
        $lastname = $student_info['lastname'];
        $firstname = $student_info['firstname'];
        $gender = $student_info['gender'];
        $section = $student_info['section'];
        $status = $student_info['status'];
        $date = $student_info['date'];

        $sql = "SELECT * FROM student_info WHERE user = '$user' AND student_id = '$student_id' AND lastname = '$lastname' AND firstname = '$firstname' AND gender = '$gender' AND section = '$section'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $sql = "INSERT INTO tblattendance(user, student_id, lastname, firstname, gender, section, status, date)VALUES('$user', '$student_id', '$lastname', '$firstname', '$gender', '$section', '$status', '$date')";
            $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {
                $result = TRUE;

                return $result;
            }
        } else {
            $result = FALSE;

            return $result;
        }
    }

    public function SelectAttendance($attendance_info)
    {
        if (isset($attendance_info['date'])) {
            $user = $attendance_info['user'];
            $section = $attendance_info['section'];
            $date = $attendance_info['date'];
            $start = $attendance_info['start'];
            $end = $attendance_info['end'];

            $sql = "SELECT * FROM tblattendance WHERE user = '$user' AND section = '$section' AND date = '$date' ORDER BY date ASC, lastname ASC LIMIT $start, $end";
            $result = $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {
                return $result->result_array();
            }
        } else {
            $user = $attendance_info['user'];
            $section = $attendance_info['section'];
            $start = $attendance_info['start'];
            $end = $attendance_info['end'];

            $sql = "SELECT * FROM tblattendance WHERE user = '$user' AND section = '$section' ORDER BY date ASC, lastname ASC LIMIT $start, $end";
            $result = $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {
                $this->session->unset_userdata('date');
                return $result->result_array();
            }
        }
    }

    public function SearchAttendance($search_info)
    {
        if (isset($search_info['date'])) {
            $user = $search_info['user'];
            $search = $search_info['search'];
            $date = $search_info['date'];
            $start = $search_info['start'];
            $end = $search_info['end'];

            $sql = "SELECT * FROM tblattendance WHERE user = '$user' AND lastname = '$search' AND date = '$date' OR user = '$user' AND student_id = '$search' AND date = '$date' ORDER BY date ASC, lastname ASC LIMIT $start, $end";
            $result = $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {
                return $result->result_array();
            }
        } else {
            $user = $search_info['user'];
            $search = $search_info['search'];
            $start = $search_info['start'];
            $end = $search_info['end'];

            $sql = "SELECT * FROM tblattendance WHERE user = '$user' AND lastname = '$search' OR '$user' AND student_id = '$search' ORDER BY date ASC, lastname ASC LIMIT $start, $end";
            $result = $this->db->query($sql);

            if ($this->db->affected_rows() > 0) {
                $this->session->unset_userdata('date');
                return $result->result_array();
            }
        }
    }

    public function AttendanceDeleteAll($section_info)
    {
        $user = $section_info['user'];
        $section = $section_info['section'];

        $sql = "DELETE FROM tblattendance WHERE user = '$user' AND section = '$section'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $result = TRUE;

            return $result;
        }
    }

    public function DeleteAttendance($a_info)
    {
        $user = $a_info['user'];
        $student_id = $a_info['student_id'];
        $date = $a_info['date'];

        $sql = "DELETE FROM tblattendance WHERE user = '$user' AND student_id = '$student_id' AND date = '$date'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $sql = "SELECT * FROM student_info WHERE user = '$user' AND student_id = '$student_id'";
            $result = $this->db->query($sql);
            $rows = $result->result_array();

            foreach ($rows as $row) {
                $this->session->set_tempdata('listatt_success', 'Attendance of ' . $row['firstname'] . ' ' . $row['lastname'] . ' has been deleted successfully.', 1);
                redirect('main/attendance');
            }
        } else {
            $this->session->set_tempdata('listatt_error', 'Problem occured. Please try again.', 1);
            redirect('main/attendance');
        }
    }

    public function SelectAttendanceRows($attendance_info)
    {
        if (isset($attendance_info['date'])) {
            $user_session = $attendance_info['user'];
            $section = $attendance_info['section'];
            $date = $attendance_info['date'];

            $sql = "SELECT * FROM tblattendance WHERE user = '$user_session' AND section = '$section' AND date = '$date'";
            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {
                return $result->num_rows();
            }
        } else {
            $user_session = $attendance_info['user'];
            $section = $attendance_info['section'];

            $sql = "SELECT * FROM tblattendance WHERE user = '$user_session' AND section = '$section'";
            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {
                $this->session->unset_userdata('date');
                return $result->num_rows();
            }
        }
    }

    public function SearchAttendanceRows($attendance_info)
    {
        if (isset($attendance_info['date'])) {
            $user_session = $attendance_info['user'];
            $search = $attendance_info['search'];
            $date = $attendance_info['date'];

            $sql = "SELECT * FROM tblattendance WHERE user = '$user_session' AND lastname = '$search' AND date = '$date' OR user = '$user_session' AND student_id = '$search' AND date = '$date'";
            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {
                return $result->num_rows();
            }
        } else {
            $user_session = $attendance_info['user'];
            $search = $attendance_info['search'];

            $sql = "SELECT * FROM tblattendance WHERE user = '$user_session' AND section = '$search'";
            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {
                $this->session->unset_userdata('date');
                return $result->num_rows();
            }
        }
    }

    public function AttendanceSection($user_session)
    {
        $user = $user_session['user'];

        $sql = "SELECT section FROM tblattendance WHERE user = '$user' GROUP BY section";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        }
    }

    public function EditAttendanceData($sa_info)
    {
        $user = $sa_info['user'];
        $student_id = $sa_info['student_id'];
        $date = $sa_info['date'];

        $sql = "SELECT * FROM tblattendance WHERE user = '$user' AND student_id = '$student_id' AND date = '$date'";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        }
    }

    public function UpdateAttendanceInfo($updated_info)
    {
        $user = $updated_info['user'];
        $student_id = $updated_info['sid'];
        $status = $updated_info['status'];
        $date = $updated_info['date'];
        $prev_date = $updated_info['prev_date'];

        $sql = "UPDATE tblattendance SET status = '$status', date = '$date' WHERE user = '$user' AND student_id = '$student_id' AND date = '$prev_date'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $sql = "SELECT * FROM student_info WHERE user = '$user' AND student_id = '$student_id'";
            $result = $this->db->query($sql);

            $rows = $result->result_array();

            foreach ($rows as $row) {
                $this->session->set_tempdata('edit_success', 'Attendance of ' . $row['firstname'] . ' ' . $row['lastname'] . ' has been updated successfully.', 1);
                redirect('main/attendance');
            }
        } else {
            $this->session->set_tempdata('edit_error', 'Problem occured. Please try again.', 1);
            redirect('main/attendance');
        }
    }

    public function SelectTotalRows($total_info)
    {
        $user = $total_info['user'];
        $section = $total_info['section'];
        $date1 = $total_info['date1'];
        $date2 = $total_info['date2'];

        $sql = "SELECT * FROM tblattendance WHERE date BETWEEN '$date1' AND '$date2' AND section = '$section' AND user = '$user' GROUP BY student_id";
        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $this->session->unset_userdata('date3');
            $this->session->unset_userdata('date4');
            return $result->num_rows();
        }
    }

    public function SelectTotal($total_info)
    {
        $user = $total_info['user'];
        $section = $total_info['section'];
        $date1 = $total_info['date1'];
        $date2 = $total_info['date2'];
        $start = $total_info['start'];
        $end = $total_info['end'];

        $sql = "SELECT student_id, lastname, firstname, gender, section, COUNT(case when status = 'Present' then 1 end) AS 'No. of Present', COUNT(case when status = 'Absent' then 1 end) AS 'No. of Absent', COUNT(status) AS 'Total of Attendance' FROM tblattendance WHERE date BETWEEN '$date1' AND '$date2' AND section = '$section' AND user = '$user' GROUP BY student_id ORDER BY lastname ASC LIMIT $start, $end ";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $this->session->unset_userdata('date3');
            $this->session->unset_userdata('date4');
            return $result->result_array();
        }
    }

    public function SearchTotalRows($total_info)
    {
        $user = $total_info['user'];
        $search = $total_info['search'];
        $date1 = $total_info['date1'];
        $date2 = $total_info['date2'];

        $sql = "SELECT * FROM tblattendance WHERE date BETWEEN '$date1' AND '$date2' AND lastname = '$search' AND user = '$user' OR date BETWEEN '$date1' AND '$date2' AND student_id = '$search' AND user = '$user'";
        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $this->session->unset_userdata('date1');
            $this->session->unset_userdata('date2');
            return $result->num_rows();
        }
    }

    public function SearchTotal($total_info)
    {
        $user = $total_info['user'];
        $search = $total_info['search'];
        $date1 = $total_info['date1'];
        $date2 = $total_info['date2'];
        $start = $total_info['start'];
        $end = $total_info['end'];

        $sql = "SELECT student_id, lastname, firstname, gender, section, COUNT(case when status = 'Present' then 1 end) AS 'No. of Present', COUNT(case when status = 'Absent' then 1 end) AS 'No. of Absent', COUNT(status) AS 'Total of Attendance' FROM tblattendance WHERE date BETWEEN '$date1' AND '$date2' AND lastname = '$search' AND user = '$user' OR date BETWEEN '$date1' AND '$date2' AND student_id = '$search' AND user = '$user' GROUP BY student_id ORDER BY lastname ASC LIMIT $start, $end ";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $this->session->unset_userdata('date1');
            $this->session->unset_userdata('date2');
            return $result->result_array();
        }
    }

    public function InsertResetRequest($pword_reset)
    {
        $user_email = $pword_reset['email'];
        $selector = $pword_reset['selector'];
        $token = $pword_reset['token'];
        $time = $pword_reset['created_at'];


        $sql = "INSERT INTO pwordreset(email, selector, token, created_at) VALUES ('$user_email', '$selector', '$token', '$time')";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_tempdata('email_success', 'Email has been sent successfully.', 1);
            redirect('main/forgot');
        }
    }

    public function GetCreatedAt($reset_request)
    {
        $selector = $reset_request['selector'];
        $validator = $reset_request['validator'];

        $sql = "SELECT * FROM pwordreset WHERE selector = '$selector' AND token = '$validator'";
        $result = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $result->result_array();
        }
    }

    public function DeleteResetRequest($reset_request)
    {
        $selector = $reset_request['selector'];
        $validator = $reset_request['validator'];

        $sql = "DELETE FROM pwordreset WHERE selector = '$selector' AND token = '$validator'";
        $this->db->query($sql);

        if ($this->db->affected_rows()) {
            $this->session->set_tempdata('email_error', 'Your reset password link is already expired. Please try again.', 1);
            redirect('main/forgot');
        }
    }

    public function ResetPassword($request_info)
    {
        $selector = $request_info['selector'];
        $validator = $request_info['validator'];
        $password = $request_info['password'];

        $sql = "SELECT email FROM pwordreset WHERE selector = '$selector' AND token = '$validator'";
        $result = $this->db->query($sql);
        $rows = $result->result_array();

        if ($result->num_rows() > 0) {

            foreach ($rows as $row) {
                $email = $row['email'];

                $sql = "UPDATE tblusers SET password = '$password' WHERE email = '$email'";
                $this->db->query($sql);

                if ($this->db->affected_rows() > 0) {
                    $sql = "DELETE FROM pwordreset WHERE selector = '$selector' AND token = '$validator'";
                    $this->db->query($sql);

                    if ($this->db->affected_rows() > 0) {
                        $this->session->set_tempdata('login_success', 'Your password has been changed successfully', 1);
                        redirect('main/index');
                    } else {
                        $this->session->set_tempdata('reset_error', 'Problem occured in updating your password', 1);
                        redirect("main/newpassword?selector={$selector}&validator={$validator}");
                    }
                }
            }
        }
    }
}
