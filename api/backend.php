<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
error_reporting(E_ERROR | E_PARSE);

include "../config/connect_db.php";
$input = json_decode(file_get_contents('php://input'), true);

function shiftHoliday($holiday, $table) {
    include "../config/connect_db.php";

    $div_date = explode("-", $holiday);
    $d = intval($div_date[2]);
    $m = intval($div_date[1]);
    $y = intval($div_date[0]);

    if ($table == "user") {
        $user = mysqli_query($connect, "SELECT * FROM tb_user WHERE 1 ORDER BY en ASC");
        while ($row = mysqli_fetch_array($user)) {
            $en = $row['en'];
            $team = $row['team'];
            if ($team != "Office") {
                $holiday_exist = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave = '$holiday' AND options = 'H' AND en_user = '$en'");
                if (mysqli_num_rows($holiday_exist) == 0) {
                    $num_shift = 1;
                    $days = strval($num_shift);
        
                    $planned = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave >= '$holiday' AND en_user = '$en' ORDER BY datesave DESC");
                    while ($row_plan = mysqli_fetch_array($planned)) {
                        $en_user = $row_plan['en_user'];
                        $options = $row_plan['options'];
                
                        $old_date = $row_plan['datesave'];
                        $new_date = date('Y-m-d', strtotime($old_date. " + ".$days." days"));
                        $new_d = intval($new_date[8].$new_date[9]);
                        $new_m = intval($new_date[5].$new_date[6]);
                        $new_y = intval($new_date[0].$new_date[1].$new_date[2].$new_date[3]);
                
                        if ($options == "H") {
                            $num_shift++;
                            $days = strval($num_shift);
                            continue;
                        }
                        else {
                            $num_shift = 1;
                            $days = strval($num_shift);
                            $shift = mysqli_query($connect, 
                                "UPDATE tb_plan SET datesave = '$new_date', options = '$options', date = '$new_d', month = '$new_m', year = '$new_y' 
                                WHERE datesave = '$old_date' AND en_user = '$en_user'"
                            );
                        }
                    }
                    $insert = mysqli_query($connect,
                        "INSERT INTO tb_plan (en_user, datesave, options, date, month, year) 
                        VALUES ('$en', '$holiday', 'H', '$d', '$m', '$y')"
                    );
                }
            }
            else if ($team == "Office") {
                $holiday_exist = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave = '$holiday' AND en_user = '$en'");
                if (mysqli_num_rows($holiday_exist) == 0) {
                    $insert = mysqli_query($connect,
                        "INSERT INTO tb_plan (en_user, datesave, options, date, month, year) 
                        VALUES ('$en', '$holiday', 'H', '$d', '$m', '$y')"
                    );
                }
                else if (mysqli_num_rows($holiday_exist) > 0) {
                    $update = mysqli_query($connect, "UPDATE tb_plan SET options = 'H' WHERE datesave = '$holiday' AND en_user = '$en'");
                }
            }
        }
    }
    else if ($table == "team") {
        $team = mysqli_query($connect, "SELECT * FROM tb_team WHERE 1 ORDER BY team ASC");
        while ($row = mysqli_fetch_array($team)) {
            $team_name = $row['team'];
            if ($team_name != "Office") {
                $holiday_exist = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$holiday' AND plan_option = 'H' AND team = '$team_name'");
                if (mysqli_num_rows($holiday_exist) == 0) {
                    $num_shift = 1;
                    $days = strval($num_shift);
                    $planned = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan >= '$holiday' AND team = '$team_name' ORDER BY date_plan DESC");
                    while ($row_plan = mysqli_fetch_array($planned)) {
                        $team_plan = $row_plan['team'];
                        $plan_option = $row_plan['plan_option'];
                
                        $old_date = $row_plan['date_plan'];
                        $new_date = date('Y-m-d', strtotime($old_date. " + ".$days." days"));
                        $new_d = intval($new_date[8].$new_date[9]);
                        $new_m = intval($new_date[5].$new_date[6]);
                        $new_y = intval($new_date[0].$new_date[1].$new_date[2].$new_date[3]);
                
                        if ($plan_option == "H") {
                            $num_shift++;
                            $days = strval($num_shift);
                            continue;
                        }
                        else {
                            $num_shift = 1;
                            $days = strval($num_shift);
                            $shift = mysqli_query($connect, 
                                "UPDATE tb_default_plan SET date_plan = '$new_date', plan_option = '$plan_option', date = '$new_d', month = '$new_m', year = '$new_y' 
                                WHERE date_plan = '$old_date' AND team = '$team_plan'"
                            );
                        }
                    }
                    $insert = mysqli_query($connect,
                        "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) 
                        VALUES ('$team_name', '$holiday', 'H', '$d', '$m', '$y')"
                    );
                }
            }
            else if ($team_name == "Office") {
                $holiday_exist = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$holiday' AND team = '$team_name'");
                if (mysqli_num_rows($holiday_exist) == 0) {
                    $insert = mysqli_query($connect,
                        "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) 
                        VALUES ('$team_name', '$holiday', 'H', '$d', '$m', '$y')"
                    );
                }
                else if (mysqli_num_rows($holiday_exist) > 0) {
                    $update = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'H' WHERE date_plan = '$holiday' AND team = '$team_name'");
                }
            }
        }
    }
}

function dropHoliday($holiday, $table) {
    include "../config/connect_db.php";

    $div_date = explode("-", $holiday);
    $d = intval($div_date[2]);
    $m = intval($div_date[1]);
    $y = intval($div_date[0]);

    if ($table == "user") {
        $user = mysqli_query($connect, "SELECT * FROM tb_user WHERE 1 ORDER BY en ASC");
        while ($row = mysqli_fetch_array($user)) {
            $en = $row['en'];
            $team = $row['team'];
            if ($team != "Office") {
                $holiday_exist = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave = '$holiday' AND options = 'H' AND en_user = '$en'");
                if (mysqli_num_rows($holiday_exist) > 0) {
                    $del = mysqli_query($connect, "DELETE FROM tb_plan WHERE datesave = '$holiday' AND options = 'H' AND en_user = '$en'");

                    $num_shift = 1;
                    $days = strval($num_shift);
        
                    $planned = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave >= '$holiday' AND en_user = '$en' ORDER BY datesave ASC");
                    while ($row_plan = mysqli_fetch_array($planned)) {
                        $en_user = $row_plan['en_user'];
                        $options = $row_plan['options'];
                
                        $old_date = $row_plan['datesave'];
                        $new_date = date('Y-m-d', strtotime($old_date. " - ".$days." days"));
                        $new_d = intval($new_date[8].$new_date[9]);
                        $new_m = intval($new_date[5].$new_date[6]);
                        $new_y = intval($new_date[0].$new_date[1].$new_date[2].$new_date[3]);
                
                        if ($options == "H") {
                            $num_shift++;
                            $days = strval($num_shift);
                            continue;
                        }
                        else {
                            $num_shift = 1;
                            $days = strval($num_shift);
                            $back = mysqli_query($connect, 
                                "UPDATE tb_plan SET datesave = '$new_date', options = '$options', date = '$new_d', month = '$new_m', year = '$new_y' 
                                WHERE datesave = '$old_date' AND en_user = '$en_user'"
                            );
                        }
                    }
                }
            }
            else if ($team == "Office") {
                $holiday_exist = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave = '$holiday' AND en_user = '$en'");
                if (mysqli_num_rows($holiday_exist) > 0) {
                    if (date('D', strtotime($holiday)) == "Sun" || date('D', strtotime($holiday)) == "Sat") {
                        $update = mysqli_query($connect, "UPDATE tb_plan SET options = 'OFF' WHERE datesave = '$holiday' AND en_user = '$en'");
                    }
                    else {
                        $update = mysqli_query($connect, "UPDATE tb_plan SET options = 'D' WHERE datesave = '$holiday' AND en_user = '$en'");
                    }
                }
            }
        }
    }
    else if ($table == "team") {
        $team = mysqli_query($connect, "SELECT * FROM tb_team WHERE 1 ORDER BY team ASC");
        while ($row = mysqli_fetch_array($team)) {
            $team_name = $row['team'];
            if ($team_name != "Office") {
                $holiday_exist = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$holiday' AND plan_option = 'H' AND team = '$team_name'");
                if (mysqli_num_rows($holiday_exist) > 0) {
                    $del = mysqli_query($connect, "DELETE FROM tb_default_plan WHERE date_plan = '$holiday' AND plan_option = 'H' AND team = '$team_name'");

                    $num_shift = 1;
                    $days = strval($num_shift);

                    $planned = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan >= '$holiday' AND team = '$team_name' ORDER BY date_plan ASC");
                    while ($row_plan = mysqli_fetch_array($planned)) {
                        $team_plan = $row_plan['team'];
                        $plan_option = $row_plan['plan_option'];
                
                        $old_date = $row_plan['date_plan'];
                        $new_date = date('Y-m-d', strtotime($old_date. " - ".$days." days"));
                        $new_d = intval($new_date[8].$new_date[9]);
                        $new_m = intval($new_date[5].$new_date[6]);
                        $new_y = intval($new_date[0].$new_date[1].$new_date[2].$new_date[3]);
                
                        if ($plan_option == "H") {
                            $num_shift++;
                            $days = strval($num_shift);
                            continue;
                        }
                        else {
                            $num_shift = 1;
                            $days = strval($num_shift);
                            $shift = mysqli_query($connect, 
                                "UPDATE tb_default_plan SET date_plan = '$new_date', plan_option = '$plan_option', date = '$new_d', month = '$new_m', year = '$new_y' 
                                WHERE date_plan = '$old_date' AND team = '$team_plan'"
                            );
                        }
                    }
                }
            }
            else if ($team_name == "Office") {
                $holiday_exist = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$holiday' AND plan_option = 'H' AND team = '$team_name'");
                if (mysqli_num_rows($holiday_exist) > 0) {
                    if (date('D', strtotime($holiday)) == "Sun" || date('D', strtotime($holiday)) == "Sat") {
                        $update = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$holiday' AND team = '$team_name'");
                    }
                    else {
                        $update = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'D' WHERE date_plan = '$holiday' AND team = '$team_name'");
                    }
                }
            }
        }
    }
}

function expandPlan() {
    include "../config/connect_db.php";

    $teams = mysqli_query($connect, "SELECT * FROM tb_team WHERE 1");
    while ($t = mysqli_fetch_array($teams)) {
        $team = $t['team'];

        if ($team != "Office") {
            $latest_date = mysqli_query($connect, "SELECT MAX(date_plan) FROM tb_default_plan WHERE team = '$team'");
            while ($ld = mysqli_fetch_array($latest_date)) {
                $date = $ld['MAX(date_plan)'];
            }
            $d = intval($date[8].$date[9]);
            $month = intval($date[5].$date[6]);
            $year = intval($date[0].$date[1].$date[2].$date[3]);
    
            for ($i = 0; $i < 3; $i++) {
                $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                if (mysqli_num_rows($dayoff) == 0) {
                    $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'D', '$d', '$month', '$year')");
                }
                else if (mysqli_num_rows($dayoff) > 0) {
                    $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'D' WHERE date_plan = '$date' AND team = '$team'");
                }
                
                for ($j = 0; $j < 3; $j++) {
                    $date = date('Y-m-d', strtotime($date. " + 1 days"));
                    $d = intval($date[8].$date[9]);
                    $month = intval($date[5].$date[6]);
                    $year = intval($date[0].$date[1].$date[2].$date[3]);
                    $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                    if (mysqli_num_rows($dayoff) == 0) {
                        $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'D', '$d', '$month', '$year')");
                    }
                    else if (mysqli_num_rows($dayoff) > 0) {
                        $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'D' WHERE date_plan = '$date' AND team = '$team'");
                    }
                }
                
                for ($j = 0; $j < 2; $j++) {
                    $date = date('Y-m-d', strtotime($date. " + 1 days"));
                    $d = intval($date[8].$date[9]);
                    $month = intval($date[5].$date[6]);
                    $year = intval($date[0].$date[1].$date[2].$date[3]);
                    $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                    if (mysqli_num_rows($dayoff) == 0) {
                        $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'OFF', '$d', '$month', '$year')");
                    }
                    else if (mysqli_num_rows($dayoff) > 0) {
                        $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$date' AND team = '$team'");
                    }
                }
    
                for ($j = 0; $j < 4; $j++) {
                    $date = date('Y-m-d', strtotime($date. " + 1 days"));
                    $d = intval($date[8].$date[9]);
                    $month = intval($date[5].$date[6]);
                    $year = intval($date[0].$date[1].$date[2].$date[3]);
                    $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                    if (mysqli_num_rows($dayoff) == 0) {
                        $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'N', '$d', '$month', '$year')");
                    }
                    else if (mysqli_num_rows($dayoff) > 0) {
                        $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'N' WHERE date_plan = '$date' AND team = '$team'");
                    }
                }
    
                for ($j = 0; $j < 2; $j++) {
                    $date = date('Y-m-d', strtotime($date. " + 1 days"));
                    $d = intval($date[8].$date[9]);
                    $month = intval($date[5].$date[6]);
                    $year = intval($date[0].$date[1].$date[2].$date[3]);
                    $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                    if (mysqli_num_rows($dayoff) == 0) {
                        $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'OFF', '$d', '$month', '$year')");
                    }
                    else if (mysqli_num_rows($dayoff) > 0) {
                        $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$date' AND team = '$team'");
                    }
                }
    
                $date = date('Y-m-d', strtotime($date. " + 1 days"));
                $d = intval($date[8].$date[9]);
                $month = intval($date[5].$date[6]);
                $year = intval($date[0].$date[1].$date[2].$date[3]);
            }
    
            $user = mysqli_query($connect, "SELECT * FROM tb_user WHERE team = '$team' AND usertype = 'Tech' ORDER BY en ASC");
            while ($row = mysqli_fetch_array($user)) {
                $en = $row['en'];
                $default = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE team = '$team' ORDER BY date_plan ASC");
                while ($r = mysqli_fetch_array($default)) {
                    $plan_option = $r['plan_option'];
                    $date_plan = $r['date_plan'];
                    $d = $r['date'];
                    $m = $r['month'];
                    $y = $r['year'];
                    $plan = mysqli_query($connect, "SELECT * FROM tb_plan WHERE en_user = '$en' AND datesave = '$date_plan'");
                    if (mysqli_num_rows($plan) > 0) {
                        $update = mysqli_query($connect, "UPDATE tb_plan SET options = '$plan_option' WHERE en_user = '$en' AND datesave = '$date_plan' AND options != 'H'");
                    }
                    else if (mysqli_num_rows($plan) == 0) {
                        $insert = mysqli_query($connect, "INSERT INTO tb_plan (en_user, datesave, options, date, month, year) VALUES ('$en', '$date_plan', '$plan_option', '$d', '$m', '$y')");
                    }
                }
            }
    
            $holiday = mysqli_query($connect, "SELECT * FROM tb_dayoff WHERE options = 'H' ORDER BY date ASC");
            while ($row = mysqli_fetch_array($holiday)) {
                $date_holiday = $row['date'];
                shiftHoliday($date_holiday, "user");
                shiftHoliday($date_holiday, "team");
            }
        }
        else if ($team == "Office") {
            $latest_date = mysqli_query($connect, "SELECT MAX(date_plan) FROM tb_default_plan WHERE team = '$team'");
            while ($ld = mysqli_fetch_array($latest_date)) {
                $date = $ld['MAX(date_plan)'];
            }
            $d = intval($date[8].$date[9]);
            $month = intval($date[5].$date[6]);
            $year = intval($date[0].$date[1].$date[2].$date[3]);
    
            for ($i = 0; $i < 5; $i++) {
                $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                if (mysqli_num_rows($dayoff) == 0) {
                    $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'OFF', '$d', '$month', '$year')");
                }
                else if (mysqli_num_rows($dayoff) > 0) {
                    $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$date' AND plan_option != 'H' AND team = '$team'");
                }
                
                for ($j = 0; $j < 5; $j++) {
                    $date = date('Y-m-d', strtotime($date. " + 1 days"));
                    $d = intval($date[8].$date[9]);
                    $month = intval($date[5].$date[6]);
                    $year = intval($date[0].$date[1].$date[2].$date[3]);
                    $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                    if (mysqli_num_rows($dayoff) == 0) {
                        $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'D', '$d', '$month', '$year')");
                    }
                    else if (mysqli_num_rows($dayoff) > 0) {
                        $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'D' WHERE date_plan = '$date' AND plan_option != 'H' AND team = '$team'");
                    }
                }
                
                $date = date('Y-m-d', strtotime($date. " + 1 days"));
                $d = intval($date[8].$date[9]);
                $month = intval($date[5].$date[6]);
                $year = intval($date[0].$date[1].$date[2].$date[3]);
                $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                if (mysqli_num_rows($dayoff) == 0) {
                    $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'OFF', '$d', '$month', '$year')");
                }
                else if (mysqli_num_rows($dayoff) > 0) {
                    $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$date' AND plan_option != 'H' AND team = '$team'");
                }
        
                $date = date('Y-m-d', strtotime($date. " + 1 days"));
                $d = intval($date[8].$date[9]);
                $month = intval($date[5].$date[6]);
                $year = intval($date[0].$date[1].$date[2].$date[3]);
            }
    
            $user = mysqli_query($connect, "SELECT * FROM tb_user WHERE team = '$team' AND usertype = 'Admin' ORDER BY en ASC");
            while ($row = mysqli_fetch_array($user)) {
                $en = $row['en'];
                $default = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE team = '$team' ORDER BY date_plan ASC");
                while ($r = mysqli_fetch_array($default)) {
                    $plan_option = $r['plan_option'];
                    $date_plan = $r['date_plan'];
                    $d = $r['date'];
                    $m = $r['month'];
                    $y = $r['year'];
                    $plan = mysqli_query($connect, "SELECT * FROM tb_plan WHERE en_user = '$en' AND datesave = '$date_plan'");
                    if (mysqli_num_rows($plan) > 0) {
                        $update = mysqli_query($connect, "UPDATE tb_plan SET options = '$plan_option' WHERE en_user = '$en' AND datesave = '$date_plan' AND options != 'H'");
                    }
                    else if (mysqli_num_rows($plan) == 0) {
                        $insert = mysqli_query($connect, "INSERT INTO tb_plan (en_user, datesave, options, date, month, year) VALUES ('$en', '$date_plan', '$plan_option', '$d', '$m', '$y')");
                    }
                }
            }
        }
    }
}

if ($input['api'] == 'login') {
    $en = $input['en'];
    $password = $input['password'];
    $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE en = '$en'");
    if (mysqli_num_rows($sql) > 0) {
        $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE en = '$en' AND password = '$password'");
        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_array($sql)) {
                $_SESSION['daily_plan_en'] = $row['en'];
                $_SESSION['daily_plan_fullname'] = $row['fullname'];
                $_SESSION['daily_plan_team'] = $row['team'];
                $_SESSION['daily_plan_usertype'] = $row['usertype'];
                $_SESSION['daily_plan'] = true;
            }
            $output = array('result'=>true, 'message'=>"");
        }
        else if (mysqli_num_rows($sql) == 0) {
            $output = array('result'=>false, 'message'=>"wrong-password");
        }
    }
    else {
        $output = array('result'=>false, 'message'=>"user-does-not-exist");
    }
    echo json_encode($output);
    exit();
}

if (empty($_SESSION['daily_plan_en']) || empty($_SESSION['daily_plan'])) {
    header("Location: ../login.php");
}

if ($input['api'] == "logout") {
    session_destroy();
    $output = array('result'=>true,);
}

else if ($input['api'] == 'load-user') {
    $users = array();
    $i = 0;
    $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE 1 ORDER BY team ASC, fullname ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $users[$i] = array(
            'en'=>$row['en'],
            'fullname'=>$row['fullname'],
            'nickname'=>$row['nickname'],
            'team'=>$row['team'],
            'usertype'=>$row['usertype'],
        );
        $i++;
    }
    $output['list_user'] = $users;
}

else if ($input['api'] == 'load-team') {
    $teams = array();
    $i = 0;
    $sql = mysqli_query($connect, "SELECT * FROM tb_team WHERE 1 ORDER BY team ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $teams[$i] = array(
            'id'=>$row['id'],
            'team'=>$row['team'],
        );
        $i++;
    }
    $output['list_team'] = $teams;
}

else if ($input['api'] == 'load-user-plan') {
    // $date = $input['date'];
    $month = $input['month'];
    $year = $input['year'];
    $week_prev_month = $input['week_prev_month'];
    $last_date = $input['last_date'];
    $last_date_next_month = $input['last_date_next_month'];
    $prev_month = $input['prev_month'];
    $next_month = $input['next_month'];
    $prev_year = $input['prev_year'];
    $next_year = $input['next_year'];
    $plan_current = array();
    $plan_previous = array();
    $plan_next = array();
    $i = 0;
    $j = 0;
    $k = 0;

    $sql_current = mysqli_query($connect, 
        "SELECT * FROM tb_plan JOIN tb_options 
        ON tb_plan.options = tb_options.options
        AND tb_plan.month = '$month' 
        AND tb_plan.year = '$year' 
        ORDER BY tb_plan.datesave ASC"
    );
    while ($row = mysqli_fetch_array($sql_current)) {
        $plan_current[$i] = array(
            'en_user'=>$row['en_user'],
            'datesave'=>$row['datesave'],
            'options'=>$row['options'],
            'date'=>$row['date'],
            'month'=>$row['month'],
            'year'=>$row['year'],
            'text_color'=>$row['text_color'],
            'bg_color'=>$row['bg_color'],
            'font_weight'=>$row['font_weight'],
            'inner_html'=>$row['inner_html'],
        );
        $i++;
    }

    $sql_previous = mysqli_query($connect, 
        "SELECT * FROM tb_plan JOIN tb_options 
        ON tb_plan.options = tb_options.options
        AND tb_plan.year = '$prev_year' 
        AND tb_plan.month = '$prev_month' 
        AND tb_plan.date >= '$week_prev_month' 
        ORDER BY tb_plan.datesave ASC"
    );
    while ($row = mysqli_fetch_array($sql_previous)) {
        $plan_previous[$j] = array(
            'en_user'=>$row['en_user'],
            'datesave'=>$row['datesave'],
            'options'=>$row['options'],
            'date'=>$row['date'],
            'month'=>$row['month'],
            'year'=>$row['year'],
            'text_color'=>$row['text_color'],
            'bg_color'=>$row['bg_color'],
            'font_weight'=>$row['font_weight'],
            'inner_html'=>$row['inner_html'],
        );
        $j++;
    }

    $sql_next = mysqli_query($connect, 
        "SELECT * FROM tb_plan JOIN tb_options 
        ON tb_plan.options = tb_options.options
        AND tb_plan.year = '$next_year' 
        AND tb_plan.month = '$next_month' 
        AND tb_plan.date <= '$last_date_next_month' 
        ORDER BY tb_plan.datesave ASC"
    );
    while ($row = mysqli_fetch_array($sql_next)) {
        $plan_next[$k] = array(
            'en_user'=>$row['en_user'],
            'datesave'=>$row['datesave'],
            'options'=>$row['options'],
            'date'=>$row['date'],
            'month'=>$row['month'],
            'year'=>$row['year'],
            'text_color'=>$row['text_color'],
            'bg_color'=>$row['bg_color'],
            'font_weight'=>$row['font_weight'],
            'inner_html'=>$row['inner_html'],
        );
        $k++;
    }

    $output['plan_current'] = $plan_current;
    $output['plan_previous'] = $plan_previous;
    $output['plan_next'] = $plan_next;
}

else if ($input['api'] == 'daily-result') {
    $last_date_current_month = $input['last_date_current_month'];
    $week_prev_month = $input['week_prev_month'];
    $last_date_prev_month = $input['last_date_prev_month'];
    $last_date_next_month = $input['last_date_next_month'];
    
    $current_month = $input['current_month'];
    $current_year = $input['current_year'];
    $prev_month = $input['prev_month'];
    $prev_year = $input['prev_year'];
    $next_year = $input['next_year'];
    $next_month = $input['next_month'];

    $result_current_month = array();
    $result_previous_month = array();
    $result_next_month = array();

    for ($i = 0; $i < $last_date_current_month; $i++) {
        if ($i+1 < 10) {
            $datesave = $current_year."-".$current_month."-0".strval($i+1);
        }
        else if ($i+1 >= 10) {
            $datesave = $current_year."-".$current_month."-".strval($i+1);
        }
        $count_D = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND options = 'D'");
        $count_N = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND options = 'N'");
        $count_AL = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND (options = 'AL' OR options = '1st-AL' OR options = '2nd-AL')");

        $result_D = mysqli_num_rows($count_D);
        $result_N = mysqli_num_rows($count_N);
        $result_AL = mysqli_num_rows($count_AL);

        $result_current_month[$i] = array(
            'result_D'=>$result_D,
            'result_N'=>$result_N,
            'result_AL'=>$result_AL,
            'datesave'=>$datesave,
        );
    }

    for ($i = $week_prev_month; $i <= $last_date_prev_month; $i++) {
        $datesave = $prev_year."-".$prev_month."-".strval($i);
        $count_D = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND options = 'D'");
        $count_N = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND options = 'N'");
        $count_AL = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND (options = 'AL' OR options = '1st-AL' OR options = '2nd-AL')");

        $result_D = mysqli_num_rows($count_D);
        $result_N = mysqli_num_rows($count_N);
        $result_AL = mysqli_num_rows($count_AL);

        $result_previous_month[$i] = array(
            'result_D'=>$result_D,
            'result_N'=>$result_N,
            'result_AL'=>$result_AL,
            'datesave'=>$datesave,
        );
    }

    for ($i = 1; $i <= $last_date_next_month; $i++) {
        if ($i < 10) {
            $datesave = $next_year."-".$next_month."-0".strval($i);
        }
        else if ($i >= 10) {
            $datesave = $next_year."-".$next_month."-".strval($i);
        }
        $count_D = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND options = 'D'");
        $count_N = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND options = 'N'");
        $count_AL = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$datesave' AND (options = 'AL' OR options = '1st-AL' OR options = '2nd-AL')");

        $result_D = mysqli_num_rows($count_D);
        $result_N = mysqli_num_rows($count_N);
        $result_AL = mysqli_num_rows($count_AL);

        $result_next_month[$i] = array(
            'result_D'=>$result_D,
            'result_N'=>$result_N,
            'result_AL'=>$result_AL,
            'datesave'=>$datesave,
        );
    }

    $output['result_current_month'] = $result_current_month;
    $output['result_previous_month'] = $result_previous_month;
    $output['result_next_month'] = $result_next_month;
}

else if ($input['api'] == 'load-team-plan') {
    // $date = $input['date'];
    $month = $input['month'];
    $year = $input['year'];
    $plan_current = array();
    $i = 0;

    $sql_current = mysqli_query($connect, 
        "SELECT * FROM tb_default_plan JOIN tb_options 
        ON tb_default_plan.plan_option = tb_options.options
        AND tb_default_plan.month = '$month' 
        AND tb_default_plan.year = '$year' 
        ORDER BY tb_default_plan.date_plan ASC"
    );
    while ($row = mysqli_fetch_array($sql_current)) {
        $plan_current[$i] = array(
            'team'=>$row['team'],
            'date_plan'=>$row['date_plan'],
            'plan_option'=>$row['plan_option'],
            'date'=>$row['date'],
            'month'=>$row['month'],
            'year'=>$row['year'],
            'text_color'=>$row['text_color'],
            'bg_color'=>$row['bg_color'],
            'font_weight'=>$row['font_weight'],
            'inner_html'=>$row['inner_html'],
        );
        $i++;
    }

    $output['plan_current'] = $plan_current;
}

else if ($input['api'] == 'count-user-today') {
    $today = $input['today'];
    $i = 0;
    $sql_d = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$today' AND options = 'D'");
    $sql_n = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$today' AND options = 'N'");
    $sql_off = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$today' AND options = 'OFF'");
    $sql_al = mysqli_query($connect, "SELECT * FROM tb_plan JOIN tb_user ON tb_plan.en_user = tb_user.en AND tb_user.usertype = 'Tech' AND datesave = '$today' AND (options = 'AL' OR options = '1st-AL' OR options = '2nd-AL')");
    
    $output = array(
        'today_d'=>mysqli_num_rows($sql_d),
        'today_n'=>mysqli_num_rows($sql_n),
        'today_off'=>mysqli_num_rows($sql_off),
        'today_al'=>mysqli_num_rows($sql_al),
    );
}

else if ($input['api'] == 'save-plan') {
    $en_user = $input['en_user'];
    $datesave = $input['datesave'];
    $options = $input['options'];

    $sql = mysqli_query($connect, "SELECT * FROM tb_plan WHERE en_user = '$en_user' AND datesave = '$datesave'");
    if (mysqli_num_rows($sql) > 0) {
        $update = mysqli_query($connect, "UPDATE tb_plan SET options = '$options' WHERE en_user = '$en_user' AND datesave = '$datesave'");
        if ($update) {
            $output = array('result'=>true, 'message'=>"");
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
    else {
        $div_date = explode("-", $datesave);
        $d = intval($div_date[2]);
        $m = intval($div_date[1]);
        $y = intval($div_date[0]);
        $insert = mysqli_query($connect,
            "INSERT INTO tb_plan (en_user, datesave, options, date, month, year) 
            VALUES ('$en_user', '$datesave', '$options', '$d', '$m', '$y')"
        );
        if ($insert) {
            $output = array('result'=>true, 'message'=>"");
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'load-options') {
    $options = array();
    $i = 0;
    $sql = mysqli_query($connect, "SELECT * FROM tb_options WHERE options != 'H' AND options != 'SD' ORDER BY id ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $options[$i] = array(
            'options'=>$row['options'],
            'text_color'=>$row['text_color'],
            'bg_color'=>$row['bg_color'],
            'font_weight'=>$row['font_weight'],
            'inner_html'=>$row['inner_html'],
            'border_color'=>$row['border_color'],
        );
        $i++;
    }
    $output['list_option'] = $options;
}

else if ($input['api'] == 'init-plan') {
    $team = $input['team'];

    if ($team != "Office") {
        $date = $input['date'];
        $d = intval($date[8].$date[9]);
        $month = intval($date[5].$date[6]);
        $year = intval($date[0].$date[1].$date[2].$date[3]);

        for ($i = 0; $i < 30; $i++) {
            $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
            if (mysqli_num_rows($dayoff) == 0) {
                $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'D', '$d', '$month', '$year')");
            }
            else if (mysqli_num_rows($dayoff) > 0) {
                $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'D' WHERE date_plan = '$date' AND team = '$team'");
            }
            
            for ($j = 0; $j < 3; $j++) {
                $date = date('Y-m-d', strtotime($date. " + 1 days"));
                $d = intval($date[8].$date[9]);
                $month = intval($date[5].$date[6]);
                $year = intval($date[0].$date[1].$date[2].$date[3]);
                $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                if (mysqli_num_rows($dayoff) == 0) {
                    $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'D', '$d', '$month', '$year')");
                }
                else if (mysqli_num_rows($dayoff) > 0) {
                    $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'D' WHERE date_plan = '$date' AND team = '$team'");
                }
            }
            
            for ($j = 0; $j < 2; $j++) {
                $date = date('Y-m-d', strtotime($date. " + 1 days"));
                $d = intval($date[8].$date[9]);
                $month = intval($date[5].$date[6]);
                $year = intval($date[0].$date[1].$date[2].$date[3]);
                $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                if (mysqli_num_rows($dayoff) == 0) {
                    $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'OFF', '$d', '$month', '$year')");
                }
                else if (mysqli_num_rows($dayoff) > 0) {
                    $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$date' AND team = '$team'");
                }
            }

            for ($j = 0; $j < 4; $j++) {
                $date = date('Y-m-d', strtotime($date. " + 1 days"));
                $d = intval($date[8].$date[9]);
                $month = intval($date[5].$date[6]);
                $year = intval($date[0].$date[1].$date[2].$date[3]);
                $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                if (mysqli_num_rows($dayoff) == 0) {
                    $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'N', '$d', '$month', '$year')");
                }
                else if (mysqli_num_rows($dayoff) > 0) {
                    $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'N' WHERE date_plan = '$date' AND team = '$team'");
                }
            }

            for ($j = 0; $j < 2; $j++) {
                $date = date('Y-m-d', strtotime($date. " + 1 days"));
                $d = intval($date[8].$date[9]);
                $month = intval($date[5].$date[6]);
                $year = intval($date[0].$date[1].$date[2].$date[3]);
                $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                if (mysqli_num_rows($dayoff) == 0) {
                    $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'OFF', '$d', '$month', '$year')");
                }
                else if (mysqli_num_rows($dayoff) > 0) {
                    $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$date' AND team = '$team'");
                }
            }

            $date = date('Y-m-d', strtotime($date. " + 1 days"));
            $d = intval($date[8].$date[9]);
            $month = intval($date[5].$date[6]);
            $year = intval($date[0].$date[1].$date[2].$date[3]);
        }

        $user = mysqli_query($connect, "SELECT * FROM tb_user WHERE team = '$team' AND usertype = 'Tech' ORDER BY en ASC");
        while ($row = mysqli_fetch_array($user)) {
            $en = $row['en'];
            $default = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE team = '$team' ORDER BY date_plan ASC");
            while ($r = mysqli_fetch_array($default)) {
                $plan_option = $r['plan_option'];
                $date_plan = $r['date_plan'];
                $d = $r['date'];
                $m = $r['month'];
                $y = $r['year'];
                $plan = mysqli_query($connect, "SELECT * FROM tb_plan WHERE en_user = '$en' AND datesave = '$date_plan'");
                if (mysqli_num_rows($plan) > 0) {
                    $update = mysqli_query($connect, "UPDATE tb_plan SET options = '$plan_option' WHERE en_user = '$en' AND datesave = '$date_plan' AND options != 'H'");
                }
                else if (mysqli_num_rows($plan) == 0) {
                    $insert = mysqli_query($connect, "INSERT INTO tb_plan (en_user, datesave, options, date, month, year) VALUES ('$en', '$date_plan', '$plan_option', '$d', '$m', '$y')");
                }
            }
        }

        $holiday = mysqli_query($connect, "SELECT * FROM tb_dayoff WHERE options = 'H' ORDER BY date ASC");
        while ($row = mysqli_fetch_array($holiday)) {
            $date_holiday = $row['date'];
            shiftHoliday($date_holiday, "user");
            shiftHoliday($date_holiday, "team");
        }
    }
    else if ($team == "Office") {
        $date = $input['date'];
        $d = intval($date[8].$date[9]);
        $month = intval($date[5].$date[6]);
        $year = intval($date[0].$date[1].$date[2].$date[3]);

        for ($i = 0; $i < 52; $i++) {
            $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
            if (mysqli_num_rows($dayoff) == 0) {
                $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'OFF', '$d', '$month', '$year')");
            }
            else if (mysqli_num_rows($dayoff) > 0) {
                $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$date' AND plan_option != 'H' AND team = '$team'");
            }
            
            for ($j = 0; $j < 5; $j++) {
                $date = date('Y-m-d', strtotime($date. " + 1 days"));
                $d = intval($date[8].$date[9]);
                $month = intval($date[5].$date[6]);
                $year = intval($date[0].$date[1].$date[2].$date[3]);
                $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
                if (mysqli_num_rows($dayoff) == 0) {
                    $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'D', '$d', '$month', '$year')");
                }
                else if (mysqli_num_rows($dayoff) > 0) {
                    $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'D' WHERE date_plan = '$date' AND plan_option != 'H' AND team = '$team'");
                }
            }
            
            $date = date('Y-m-d', strtotime($date. " + 1 days"));
            $d = intval($date[8].$date[9]);
            $month = intval($date[5].$date[6]);
            $year = intval($date[0].$date[1].$date[2].$date[3]);
            $dayoff = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE date_plan = '$date' AND team = '$team'");
            if (mysqli_num_rows($dayoff) == 0) {
                $sql = mysqli_query($connect, "INSERT INTO tb_default_plan (team, date_plan, plan_option, date, month, year) VALUES ('$team', '$date', 'OFF', '$d', '$month', '$year')");
            }
            else if (mysqli_num_rows($dayoff) > 0) {
                $sql = mysqli_query($connect, "UPDATE tb_default_plan SET plan_option = 'OFF' WHERE date_plan = '$date' AND plan_option != 'H' AND team = '$team'");
            }
    
            $date = date('Y-m-d', strtotime($date. " + 1 days"));
            $d = intval($date[8].$date[9]);
            $month = intval($date[5].$date[6]);
            $year = intval($date[0].$date[1].$date[2].$date[3]);
        }

        $user = mysqli_query($connect, "SELECT * FROM tb_user WHERE team = '$team' AND usertype = 'Admin' ORDER BY en ASC");
        while ($row = mysqli_fetch_array($user)) {
            $en = $row['en'];
            $default = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE team = '$team' ORDER BY date_plan ASC");
            while ($r = mysqli_fetch_array($default)) {
                $plan_option = $r['plan_option'];
                $date_plan = $r['date_plan'];
                $d = $r['date'];
                $m = $r['month'];
                $y = $r['year'];
                $plan = mysqli_query($connect, "SELECT * FROM tb_plan WHERE en_user = '$en' AND datesave = '$date_plan'");
                if (mysqli_num_rows($plan) > 0) {
                    $update = mysqli_query($connect, "UPDATE tb_plan SET options = '$plan_option' WHERE en_user = '$en' AND datesave = '$date_plan' AND options != 'H'");
                }
                else if (mysqli_num_rows($plan) == 0) {
                    $insert = mysqli_query($connect, "INSERT INTO tb_plan (en_user, datesave, options, date, month, year) VALUES ('$en', '$date_plan', '$plan_option', '$d', '$m', '$y')");
                }
            }
        }
    }

    $output = array('result'=>true);
}

else if ($input['api'] == 'clear-plan') {
    $team = $input['team'];
    $member = array();
    $i = 0;
    $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE team = '$team'");
    while ($row = mysqli_fetch_array($sql)) {
        $en = $row['en'];
        $clear = mysqli_query($connect, "DELETE FROM tb_plan WHERE en_user = '$en'");
    }
    $count_plan = mysqli_query($connect, "SELECT * FROM tb_plan WHERE 1");
    if (mysqli_num_rows($count_plan) == 0) {
        $reset = mysqli_query($connect, "ALTER TABLE tb_plan AUTO_INCREMENT = 1");
    }

    $del = mysqli_query($connect, "DELETE FROM tb_default_plan WHERE team = '$team'");
    $count_default = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE 1");
    if (mysqli_num_rows($count_default) == 0) {
        $reset = mysqli_query($connect, "ALTER TABLE tb_default_plan AUTO_INCREMENT = 1");
    }

    if ($del) {
        $output = array('result'=>true);
    }
    else {
        $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
    }
}

else if ($input['api'] == 'save-holiday') {
    $date_holiday = $input['holiday'];

    shiftHoliday($date_holiday, "user");
    shiftHoliday($date_holiday, "team");

    $holiday = mysqli_query($connect, "SELECT * FROM tb_dayoff WHERE date = '$date_holiday'");
    if (mysqli_num_rows($holiday) == 0) {
        $sql = mysqli_query($connect, "INSERT INTO tb_dayoff (date, options) VALUES ('$date_holiday', 'H')");
        if ($sql) {
            $output = array('result'=>true);
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
    else if (mysqli_num_rows($holiday) > 0) {
        $sql = mysqli_query($connect, "UPDATE tb_dayoff SET options = 'H' WHERE date = '$date_holiday' AND options != 'H'");
        if ($sql) {
            $output = array('result'=>true);
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'delete-holiday') {
    $date_holiday = $input['holiday'];
    $holiday = mysqli_query($connect, "SELECT * FROM tb_dayoff WHERE date = '$date_holiday' AND options = 'H'");
    if (mysqli_num_rows($holiday) == 0) {
        $output = array('result'=>false, 'message'=>"Holiday Date not Found");
    }
    else if (mysqli_num_rows($holiday) > 0) {
        $sql = mysqli_query($connect, "DELETE FROM tb_dayoff WHERE date = '$date_holiday' AND options = 'H'");
        if ($sql) {
            dropHoliday($date_holiday, "user");
            dropHoliday($date_holiday, "team");

            $count = mysqli_query($connect, "SELECT * FROM tb_dayoff WHERE 1");
            if (mysqli_num_rows($count) == 0) {
                $reset = mysqli_query($connect, "ALTER TABLE tb_dayoff AUTO_INCREMENT = 1");
            }

            $output = array('result'=>true);
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'save-shutdown') {
    $date_shutdown = $input['shutdown'];
    $shutdown = mysqli_query($connect, "SELECT * FROM tb_dayoff WHERE date = '$date_shutdown'");
    if (mysqli_num_rows($shutdown) == 0) {
        $sql = mysqli_query($connect, "INSERT INTO tb_dayoff (date, options) VALUES ('$date_shutdown', 'SD')");
        if ($sql) {
            $output = array('result'=>true);
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'delete-shutdown') {
    $date_shutdown = $input['shutdown'];
    $shutdown = mysqli_query($connect, "SELECT * FROM tb_dayoff WHERE date = '$date_shutdown' AND options = 'SD'");
    if (mysqli_num_rows($shutdown) == 0) {
        $output = array('result'=>false, 'message'=>"Shutdown Date not Found");
    }
    else if (mysqli_num_rows($shutdown) > 0) {
        $sql = mysqli_query($connect, "DELETE FROM tb_dayoff WHERE date = '$date_shutdown' AND options = 'SD'");
        if ($sql) {
            $count = mysqli_query($connect, "SELECT * FROM tb_dayoff WHERE 1");
            if (mysqli_num_rows($count) == 0) {
                $reset = mysqli_query($connect, "ALTER TABLE tb_dayoff AUTO_INCREMENT = 1");
            }

            $output = array('result'=>true);
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'load-shutdown') {
    $shutdown = array();
    $i = 0;
    $sql = mysqli_query($connect, 
        "SELECT * FROM tb_dayoff JOIN tb_options
        ON tb_dayoff.options = tb_options.options
        AND tb_dayoff.options = 'SD'
        ORDER BY tb_dayoff.date ASC"
    );
    while ($row = mysqli_fetch_array($sql)) {
        $shutdown[$i] = array(
            'date_shutdown'=>$row['date'],
            'options'=>$row['options'],
            'text_color'=>$row['text_color'],
            'bg_color'=>$row['bg_color'],
            'font_weight'=>$row['font_weight'],
            'inner_html'=>$row['inner_html'],
        );
        $i++;
    }
    $output['list_shutdown'] = $shutdown;
}

else if ($input['api'] == 'change-password') {
    $en = $_SESSION['daily_plan_en'];
    $password = $input['password'];
    $sql = mysqli_query($connect, "UPDATE tb_user SET password = '$password' WHERE en = '$en'");
    if ($sql) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
    }
}

else if ($input['api'] == 'expand-plan') {
    $date = $input['date'];

    $check = mysqli_query($connect, "SELECT * FROM tb_update_plan WHERE status = 0");
    if (mysqli_num_rows($check) == 0) {
        $sql = mysqli_query($connect, "INSERT INTO tb_update_plan (date) VALUES ('2023-04-30')");
    }
    else {
        $sql = mysqli_query($connect, "SELECT * FROM tb_update_plan WHERE date <= '$date' AND status = 0");
        if (mysqli_num_rows($sql) > 0) {
            expandPlan();
    
            $update_status = mysqli_query($connect, "UPDATE tb_update_plan SET status = 1 WHERE date <= '$date' AND status = 0");
            
            $next_date = date('Y-m-d', strtotime($date. " + 1 months"));
            $new_update = mysqli_query($connect, "INSERT INTO tb_update_plan (date) VALUES ('$next_date')");
        }
    }
}

else if ($input['api'] == 'insert-user') {
    $en = $input['en'];
    $fullname = $input['fullname'];
    $nickname = $input['nickname'];
    $password = $input['password'];
    $team = $input['team'];
    $type = $input['type'];
    $sql = mysqli_query($connect,
        "INSERT INTO tb_user (en, fullname, nickname, password, team, usertype)
        VALUE ('$en', '$fullname', '$nickname', '$password', '$team', '$type')"
    );

    $plan = mysqli_query($connect, "SELECT * FROM tb_plan WHERE en_user = '$en'");
    if (mysqli_num_rows($plan) == 0) {
        $get_plan = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE team = '$team' ORDER BY date_plan ASC");
        while ($row = mysqli_fetch_array($get_plan)) {
            $datesave = $row['date_plan'];
            $options = $row['plan_option'];
            $d = $row['date'];
            $m = $row['month'];
            $y = $row['year'];
            $new_plan = mysqli_query($connect, 
                "INSERT INTO tb_plan (en_user, datesave, options, date, month, year)
                VALUES ('$en', '$datesave', '$options', '$d', '$m', '$y')"
            );
        }
    }

    if ($sql) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
    }
}

else if ($input['api'] == 'select-user') {
    $en = $input['en'];
    $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE en = '$en'");
    while ($row = mysqli_fetch_array($sql)) {
        $output = array(
            'en' => $row['en'],
            'id' => $row['id'],
            'fullname' => $row['fullname'],
            'nickname' => $row['nickname'],
            'password' => $row['password'],
            'team' => $row['team'],
            'usertype' => $row['usertype']
        );
    }
}

else if ($input['api'] == 'update-user') {
    $en = $input['en'];
    $id = $input['id'];
    $fullname = $input['fullname'];
    $nickname = $input['nickname'];
    $password = $input['password'];
    $team = $input['team'];
    $old_team = $input['old_team'];
    $type = $input['type'];
    $sql = mysqli_query($connect, "UPDATE tb_user SET en = '$en', fullname = '$fullname', nickname = '$nickname', password = '$password', team = '$team', usertype = '$type' WHERE id = '$id'");
    
    if ($old_team != $team) {
        $plan = mysqli_query($connect, "SELECT * FROM tb_plan WHERE en_user = '$en'");
        if (mysqli_num_rows($plan) == 0) {
            $get_plan = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE team = '$team' ORDER BY date_plan ASC");
            while ($row = mysqli_fetch_array($get_plan)) {
                $datesave = $row['date_plan'];
                $options = $row['plan_option'];
                $d = $row['date'];
                $m = $row['month'];
                $y = $row['year'];
                $new_plan = mysqli_query($connect, 
                    "INSERT INTO tb_plan (en_user, datesave, options, date, month, year)
                    VALUES ('$en', '$datesave', '$options', '$d', '$m', '$y')"
                );
            }
        }
        else if (mysqli_num_rows($plan) > 0) {
            $clear = mysqli_query($connect, "DELETE FROM tb_plan WHERE en_user = '$en'");
            $get_plan = mysqli_query($connect, "SELECT * FROM tb_default_plan WHERE team = '$team' ORDER BY date_plan ASC");
            while ($row = mysqli_fetch_array($get_plan)) {
                $datesave = $row['date_plan'];
                $options = $row['plan_option'];
                $d = $row['date'];
                $m = $row['month'];
                $y = $row['year'];
                $new_plan = mysqli_query($connect, 
                    "INSERT INTO tb_plan (en_user, datesave, options, date, month, year)
                    VALUES ('$en', '$datesave', '$options', '$d', '$m', '$y')"
                );
            }
        }
    }

    if ($sql) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
    }
}

else if ($input['api'] == 'delete-user') {
    $en = $input['en'];
    $sql = mysqli_query($connect, "DELETE FROM tb_user WHERE en = '$en'");
    if ($sql) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
    }
}

else if ($input['api'] == 'search-user') {
    $text = $input['text'];
    $users = array();
    $i = 0;
    $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE en LIKE '%$text%' OR fullname LIKE '%$text%' OR nickname LIKE '%$text%' ORDER BY team ASC, fullname ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $users[$i] = array(
            'en'=>$row['en'],
            'fullname'=>$row['fullname'],
            'nickname'=>$row['nickname'],
            'team'=>$row['team'],
            'usertype'=>$row['usertype'],
        );
        $i++;
    }
    $output['list_user'] = $users;
}

else if ($input['api'] == 'insert-team') {
    $team = $input['team'];
    $sql = mysqli_query($connect, "SELECT * FROM tb_team WHERE team = '$team'");
    if (mysqli_num_rows($sql) > 0) {
        $output = array('result'=>false, 'message'=>"This Team Already Exist!");
    }
    else {
        $insert = mysqli_query($connect, "INSERT INTO tb_team (team) VALUES ('$team')");
        if ($insert) {
            $output = array('result'=>true, 'message'=>"");
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'update-team') {
    $teamID = $input['teamID'];
    $team = $input['team'];
    $sql = mysqli_query($connect, "SELECT * FROM tb_team WHERE team = '$team'");
    if (mysqli_num_rows($sql) > 0) {
        $output = array('result'=>false, 'message'=>"This Team Already Exist!");
    }
    else {
        $update = mysqli_query($connect, "UPDATE tb_team SET team = '$team' WHERE id = '$teamID'");
        if ($update) {
            $output = array('result'=>true, 'message'=>"");
        }
        else {
            $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'delete-team') {
    $teamID = $input['teamID'];
    $delete = mysqli_query($connect, "DELETE FROM tb_team WHERE id = '$teamID'");
    if ($delete) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>"Database Error : ".mysqli_error($connect));
    }
}

echo json_encode($output);
exit();
?>
