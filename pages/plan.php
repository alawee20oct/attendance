<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../libs/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../libs/fontawesome/css/all.min.css">

    <link rel="icon" type="image/png" href="../images/icon.png"/>

    <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/jquery/jquery-3.6.3.js"></script>
    <script src="../api/https.js"></script>
    <script src="../js/datetimeformat.js"></script>

    <title>Set Plan</title>
</head>
<body>
    <?php
    if (empty($_SESSION['daily_plan_en']) && empty($_SESSION['daily_plan'])) {
        echo "<script>window.location.href = '../login.php'</script>";
    }
    else if (isset($_SESSION['daily_plan_en']) && isset($_SESSION['daily_plan'])) {
        if ($_SESSION['daily_plan_usertype'] != "Admin") {
            echo "<script>window.location.href = '../index.php'</script>";
        }
        else {
            echo '<script> var EN_USER = "'.$_SESSION['daily_plan_en'].'";</script>';
            echo '<script> var FULLNAME = "'.$_SESSION['daily_plan_fullname'].'";</script>';
            echo '<script> var team = "'.$_SESSION['daily_plan_team'].'";</script>';
            echo '<script> var USERTYPE = "'.$_SESSION['daily_plan_usertype'].'";</script>';
        }
    }
    ?>
    <section>
        <nav class="navbar bg-light fixed-top">
            <div class="container-fluid">
                <span class="navbar-brand" id="navbar-username"></span>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" data-bs-scroll="true">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvas-username"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <button class="btn" onclick="window.location.href = '../index.php'"><i class="bi bi-house-door-fill me-2 text-dark"></i>Home</button>
                            </li>
                            <?php
                            if ($_SESSION['daily_plan_usertype'] == "Admin") {
                                echo '<li class="nav-item"><button class="btn" onclick="window.location.href = \'admin.php\'"><i class="bi bi-person-circle me-2 text-primary"></i>Admin</button></li>';
                                echo '<li class="nav-item"><button class="btn" onclick="location.reload();"><i class="bi bi-calendar-week-fill me-2 text-primary"></i>Plan</button></li>';
                            } 
                            ?>
                            <hr>
                            <li class="nav-item">
                                <button class="btn" onclick="logout()"><i class="fa-solid fa-arrow-right-from-bracket me-2 text-danger"></i>Logout</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </section>

    <div class="container mt-5 px-4 pt-5">
        <div class="input-group shadow-sm">
            <select class="form-select" id="month-select">

            </select>
            <select class="form-select" id="year-select">

            </select>
            <button class="btn btn-outline-primary d-sm-block px-4" onclick="changeTablePlan()"><i class="bi bi-arrow-right-square-fill me-2"></i>Go</button>
            <button class="btn btn-outline-danger d-sm-block px-4" onclick="holiday(0)" id="btn-set-holiday"><i class="bi bi-calendar-x me-2"></i>Holiday</button>
            <button class="btn btn-outline-danger d-sm-block px-4" onclick="shutdown(0)" id="btn-set-shutdown"><i class="fa-solid fa-power-off me-2"></i>Shutdown</button>
        </div>
    </div>

    <div class="container mt-2 px-4 py-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-sm table-borderless w-100" id="day-table">
                <thead class="text-center border-dark border-top border-bottom" >
                    <tr id="date_thead">
                        
                    </tr>
                    <tr id="day_thead">
                        
                    </tr>
                </thead>
                <tbody class="text-center" id="team_column">
                    
                </tbody>
            </table>
        </div>
    </div>
    <hr>

    <div class="container mt-3 py-4">
        <div class="table-responsive">
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th valign="middle" align="center">Team</th>
                        <th valign="middle" align="center">Initialize</th>
                        <th valign="middle" align="center">Clear</th>
                    </tr>
                </thead>
                <tbody id="init_teams">

                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="init-plan-modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="init_title">Initial Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hidden_team">
                    <ul class="nav nav-pills nav-fill justify-content-center mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tech-tab" data-bs-toggle="pill" data-bs-target="#tech-template" type="button" role="tab" aria-controls="tect-template" aria-selected="true">Tech</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="office-tab" data-bs-toggle="pill" data-bs-target="#office-template" type="button" role="tab" aria-controls="office-template" aria-selected="false">Office</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-2" id="myTabContent">
                        <div class="mb-3">
                            <input type="date" class="form-control" name="" id="date-init">
                        </div>
                        <div class="tab-pane fade show active" id="tech-template" role="tabpanel" aria-labelledby="tech-template" tabindex="0">
                            <div class="text-center mb-3">
                                <h4 class="d-inline"><span class="fw-normal badge text-dark border border-dark" style="background-color: #C6E0B4">D</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-dark border border-dark" style="background-color: #C6E0B4">D</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-dark border border-dark" style="background-color: #C6E0B4">D</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-dark border border-dark" style="background-color: #C6E0B4">D</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-white border border-dark" style="background-color: #A6A6A6">OFF</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-white border border-dark" style="background-color: #A6A6A6">OFF</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-dark border border-dark" style="background-color: #FFE699">N</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-dark border border-dark" style="background-color: #FFE699">N</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-dark border border-dark" style="background-color: #FFE699">N</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-dark border border-dark" style="background-color: #FFE699">N</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-white border border-dark" style="background-color: #A6A6A6">OFF</span></h4>
                                <h4 class="d-inline"><span class="fw-normal badge text-white border border-dark" style="background-color: #A6A6A6">OFF</span></h4>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="office-template" role="tabpanel" aria-labelledby="office-template" tabindex="0">  
                            <table class="table table-sm text-center table-bordered border-dark" style="table-layout: fixed;">
                                <tbody>
                                    <tr>
                                        <td style="background-color: #FF0000;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Sun</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #FFFF66;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Mon</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #FF99CC;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Tue</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #99FF99;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Wed</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #FF9933;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Thu</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #00CCFF;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Fri</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #CC99FF;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Sat</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #FF0000;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Sun</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #FFFF66;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Mon</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #FF99CC;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Tue</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #99FF99;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Wed</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #FF9933;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Thu</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #00CCFF;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Fri</span>
                                            </div>    
                                        </td>
                                        <td style="background-color: #CC99FF;">
                                            <div class="py-2" style="transform: rotate(270deg);"> 
                                                <span style="color: #000000;">Sat</span>
                                            </div>    
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #A6A6A6; color: #FFFFFF;">OFF</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #A6A6A6; color: #FFFFFF;">OFF</td>
                                        <td style="background-color: #A6A6A6; color: #FFFFFF;">OFF</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #C6E0B4; color: #000000;">D</td>
                                        <td style="background-color: #A6A6A6; color: #FFFFFF;">OFF</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="alert alert-danger visually-hidden mt-3" role="alert" id="alert-init"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-init-plan" onclick="initPlan()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="holiday-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Set Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="date" class="form-control" id="date-holiday">
                        <button type="button" class="btn btn-primary" id="btn-holiday" onclick="holiday(1)">Save</button>
                    </div>
                    <div class="alert alert-danger visually-hidden mt-3" role="alert" id="alert-holiday"></div>
                    <hr>
                    <table class="table table-hover text-center">
                        <thead>
                            <th>yyyy/mm/dd</th>
                            <th><i class="bi bi-trash3-fill"></i></th>
                        </thead>
                        <tbody id="list_holiday" class="table-group-divider">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="shutdown-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Set Shutdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="date" class="form-control" id="date-shutdown">
                        <button type="button" class="btn btn-primary" id="btn-shutdown" onclick="shutdown(1)">Save</button>
                    </div>
                    <div class="alert alert-danger visually-hidden mt-3" role="alert" id="alert-shutdown"></div>
                    <hr>
                    <table class="table table-hover text-center">
                        <thead>
                            <th>yyyy/mm/dd</th>
                            <th><i class="bi bi-trash3-fill"></i></th>
                        </thead>
                        <tbody id="list_shutdown" class="table-group-divider">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="clear-plan-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Clear Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">
                    <div class="container text-center">
                        <h5 class="mb-3"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Do You Want to Clear Plan Team : <strong id="ask-clear-team">A1</strong> ?</h5>
                        <button class="btn btn-secondary me-1" id="btn-clear-x" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger ms-1" id="btn-clear-plan" onclick="clearPlan()">Accept</button>
                    </div>
                    <div class="alert alert-danger visually-hidden mt-3" role="alert" id="alert-clear"></div>
                </div>
            </div>
        </div>
    </div>

<script>
    window.onload = function () {
        document.getElementById("navbar-username").innerText = FULLNAME;
        document.getElementById("offcanvas-username").innerText = FULLNAME;

        var current = new Date();
        var year = current.getFullYear();
        var month = current.getMonth();
        var date = current.getDate();
        var day = current.getDay();

        renderInputMonthYear(current);
        renderTablePlan(month, year);
        loadInitTable();
        loadShutdown();
    }

    function renderInputMonthYear(current) {
        var month = current.getMonth();
        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var month_option = "";
        for (var i in months) {
            if (i == month) {
                month_option += '<option value="'+i+'" selected>'+months[i]+'</option>';
            }
            else {
                month_option += '<option value="'+i+'">'+months[i]+'</option>';
            }
        }
        document.getElementById("month-select").innerHTML = month_option;

        var year = current.getFullYear();
        var year_option = "";
        for (var i = year-2; i < year+5; i++) {
            if (i == year) {
                year_option += '<option value="'+i+'" selected>'+i+'</option>';
            }
            else {
                year_option += '<option value="'+i+'">'+i+'</option>';
            }
        }
        document.getElementById("year-select").innerHTML = year_option;
    }

    function renderInputMonthYear(current) {
        var month = current.getMonth();
        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var month_option = "";
        for (var i in months) {
            if (i == month) {
                month_option += '<option value="'+i+'" selected>'+months[i]+'</option>';
            }
            else {
                month_option += '<option value="'+i+'">'+months[i]+'</option>';
            }
        }
        document.getElementById("month-select").innerHTML = month_option;

        var year = current.getFullYear();
        var year_option = "";
        for (var i = year-2; i < year+5; i++) {
            if (i == year) {
                year_option += '<option value="'+i+'" selected>'+i+'</option>';
            }
            else {
                year_option += '<option value="'+i+'">'+i+'</option>';
            }
        }
        document.getElementById("year-select").innerHTML = year_option;
    }

    function renderTablePlan(month, year) {
        var first_day_month = new Date(year, month, 1).getDay();
        var last_date_current_month = new Date(year, month+1, 0).getDate();
        var last_day_month = new Date(year, month, last_date_current_month).getDay();

        var last_date_prev_month = new Date(year, month, 0).getDate();
        var half_date_prev_month = new Date(year, month, -14).getDate();
        var half_date_next_month = new Date(year, month+1, 15).getDate();

        var prev_month = new Date(year, month-1, 1).getMonth();
        var prev_year = new Date(year, month-1, 1).getFullYear();
        var next_month = new Date(year, month+1, 1).getMonth();
        var next_year = new Date(year, month+1, 1).getFullYear();

        var DATEsOBJECT = {
            'last_date_current_month': last_date_current_month,
            'last_date_prev_month': last_date_prev_month,
            'half_date_prev_month': half_date_prev_month,
            'half_date_next_month': half_date_next_month,
            'current_month': month,
            'current_year': year,
            'prev_month': prev_month,
            'prev_year': prev_year,
            'next_month': next_month,
            'next_year': next_year,
        }

        var date_thead = "";
        var day_thead = "";
        date_thead += '<th valign="middle" class="fw-semibold text-dark border-dark border-start border-end" rowspan="2">TEAM</th>';

        // load current month ================================================================================
        for (var i = 1; i <= last_date_current_month; i++) {
            if (i < 10) {
                var date_fmt = dateFormat(i, "onedigit");
            }
            else if (i >= 10) {
                var date_fmt = dateFormat(i, "twodigit");
            }
            var month_fmt = monthFormat(month, "idx");
            var year_fmt = yearFormat(year, "fourdigit");
            date_thead += '<th valign="middle" class="fw-bold text-white border-white border-start border-end" style="background-color: #242424;">';
                date_thead += '<div>';
                    date_thead += '<span class="text-center h6">'+date_fmt.twodigit_string+'</span><br>';
                    date_thead += '<span class="text-center h6">'+month_fmt.short+'</span><br>';
                    date_thead += '<span class="text-center h6">'+year_fmt.twodigit_string+'</span>';
                date_thead += '</div>';
            date_thead += '</th>';
        }
        //====================================================================================================

        document.getElementById("date_thead").innerHTML = date_thead;

        var d = 0;

        // load current month ================================================================================
        for (var i = 1; i <= last_date_current_month; i++) {
            d = new Date(year, month, i).getDay();
            var day_fmt = dayFormat(d, "idx");
            day_thead += '<td class="text-dark border-top border-bottom border-start border-dark" style="background-color: '+day_fmt.color+';">';
                day_thead += '<div class="py-3" style="transform: rotate(270deg);">';
                    day_thead += '<span class="text-center fw-semibold">'+day_fmt.short+'</span>';
                day_thead += '</div>';
            day_thead += '</td>';
        }
        //====================================================================================================

        document.getElementById("day_thead").innerHTML = day_thead;
        renderTeam(month, year, DATEsOBJECT);
    }

    function renderTeam(month, year, DATEsOBJECT) {
        var today = new Date();
        var thisday = today.getDate();
        var thismonth = today.getMonth();
        var thisyear = today.getFullYear();
        var load_team = requestHTTPS('../api/backend.php', {
            'api': 'load-team',
        }, true);

        var team_column = "";
        for (var i = 0; i < load_team.list_team.length; i++) {
            var item = load_team.list_team[i];

            team_column += '<tr class="border-bottom border-dark">';
                team_column += '<td valign="middle">'+item.team+'</td>';

            // load current month ================================================================================
            for (var j = 1; j <= DATEsOBJECT.last_date_current_month; j++) {
                var id_td = genID(year, month, j);
                team_column += '<td valign="middle" id="'+item.team+':'+id_td+'" class="border-dark border-start booder-end">none</td>';
            }
            //====================================================================================================

            team_column += '</tr>';
        }

        document.getElementById("team_column").innerHTML = team_column;
        teamPlan(month, year, DATEsOBJECT);
    }

    function genID(year, month, date) {
        if (date < 10) {
            var id_td_tag = yearFormat(year, "fourdigit").fourdigit_string + "-" + monthFormat(month, "idx").twodigit + "-" + dateFormat(date, "onedigit").twodigit_string;
        }
        else if (date >= 10) {
            var id_td_tag = yearFormat(year, "fourdigit").fourdigit_string + "-" + monthFormat(month, "idx").twodigit + "-" + dateFormat(date, "twodigit").twodigit_string;
        }
        return id_td_tag;
    }

    function teamPlan(month, year, DATEsOBJECT) {
        var team_plan = requestHTTPS('../api/backend.php', {
            'api': 'load-team-plan',
            'month': month+1,
            'year': year,
            'half_date': DATEsOBJECT.half_date_prev_month,
            'last_date': DATEsOBJECT.last_date_prev_month,
            'prev_month': DATEsOBJECT.prev_month+1,
            'next_month': DATEsOBJECT.next_month+1,
            'prev_year': DATEsOBJECT.prev_year,
            'next_year': DATEsOBJECT.next_year,
        }, true);

        for (var i in team_plan.plan_current) {
            var item = team_plan.plan_current[i];
            document.getElementById(item.team+":"+item.date_plan).innerHTML = item.inner_html;
            document.getElementById(item.team+":"+item.date_plan).style.backgroundColor = item.bg_color;
            document.getElementById(item.team+":"+item.date_plan).style.color = item.text_color;
            document.getElementById(item.team+":"+item.date_plan).classList.add(item.font_weight);
        }
    }

    function loadInitTable() {
        var load_team = requestHTTPS('../api/backend.php', {
            'api': 'load-team'
        }, true);

        var teams = "";
        for (var i = 0; i < load_team.list_team.length; i++) {
            var item = load_team.list_team[i];
            teams += '<tr>';
                teams += '<td valign="middle" align="center">'+item.team+'</td>'
                teams += '<td valign="middle" align="center">';
                    teams += '<button class="btn btn-outline-primary" id="INIT:'+item.team+'" onclick="initPlan(this)"><i class="fa-regular fa-calendar-plus"></i></button>';
                teams += '</td>';
                teams += '<td valign="middle" align="center">';
                    teams += '<button class="btn btn-outline-danger" id="CLEAR:'+item.team+'" onclick="clearPlan(this)"><i class="fa-regular fa-calendar-xmark"></i></button>';
                teams += '</td>';
            teams += '</tr>';
        }

        document.getElementById("init_teams").innerHTML = teams;
    }

    function initPlan(btn) {
        if (typeof btn != "undefined") {
            document.getElementById("init_title").innerHTML = 'Initial Plan Team : <b>'+btn.id.replace("INIT:", "")+'</b>';
            document.getElementById("hidden_team").value = btn.id.replace("INIT:", "");
            $('#init-plan-modal').modal('show');
        }
        else if (typeof btn == "undefined") {
            var team = document.getElementById("hidden_team").value;
            var date = document.getElementById("date-init").value;
            if (date == "") {
                document.getElementById("alert-init").classList.remove("visually-hidden");
                document.getElementById("alert-init").classList.add("visually-visible");
                document.getElementById("alert-init").innerText = "Please Select Initial Date";
                return;
            }
            else {
                var tech_btn = document.getElementById("tech-tab");
                var office_btn = document.getElementById("office-tab");
                if (tech_btn.classList.contains("active") && !office_btn.classList.contains("active")) {
                    var init_plan = requestHTTPS('../api/backend.php', {
                        'api': 'init-plan',
                        'team': team,
                        'date': date,
                    }, true);
                    if (init_plan.result == true) {
                        document.getElementById("btn-init-plan").setAttribute("disabled", true);
                        document.getElementById("btn-init-plan").innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...';
                        location.reload();
                    }
                }
                else if (office_btn.classList.contains("active") && !tech_btn.classList.contains("active")) {
                    var getDay = new Date(date).getDay();
                    if (getDay != 0) {
                        document.getElementById("alert-init").classList.remove("visually-hidden");
                        document.getElementById("alert-init").classList.add("visually-visible");
                        document.getElementById("alert-init").innerText = "Please Select a Date on Sunday";
                        return;
                    }
                    else if (getDay == 0) {
                        var init_plan = requestHTTPS('../api/backend.php', {
                            'api': 'init-plan',
                            'team': "Office",
                            'date': date,
                        }, true);
                        document.getElementById("btn-init-plan").setAttribute("disabled", true);
                        document.getElementById("btn-init-plan").innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...';
                        if (init_plan.result == true) {
                            location.reload();
                        }
                    }
                }
            }
        }
    }

    function clearPlan(btn) {
        if (typeof btn != "undefined") {
            var team = btn.id.replace("CLEAR:", "");
            document.getElementById("ask-clear-team").innerText = team;
            $('#clear-plan-modal').modal('show');
        }
        else {
            var team = document.getElementById("ask-clear-team").innerText;
            var clear_plan = requestHTTPS('../api/backend.php', {
                'api': 'clear-plan',
                'team': team
            }, true);
            if (clear_plan.result == true) {
                document.getElementById("btn-clear-x").setAttribute("disabled", true);
                document.getElementById("btn-clear-plan").setAttribute("disabled", true);
                document.getElementById("btn-clear-plan").innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...';
                location.reload();
            }
            else {
                document.getElementById("alert-clear").innerText = clear_plan.message;
                document.getElementById("alert-clear").classList.remove("visually-hidden");
                document.getElementById("alert-clear").classList.add("visually-visible");
                return;
            }
        }
    }

    function changeTablePlan() {
        var select_month = document.getElementById("month-select").value;
        var select_year = document.getElementById("year-select").value;
        var month_cvt = parseInt(select_month);
        var year_cvt = parseInt(select_year);
        var new_plan = new Date();
        renderTablePlan(month_cvt, year_cvt);
    }

    function holiday(option) {
        if (option == 0) {
            var load_holiday = requestHTTPS('../api/backend.php', {
                'api' : 'load-holiday'
            }, true);
            var tb_holiday = "";
            for (var i = 0; i < load_holiday.holidays.length; i++) {
                var item = load_holiday.holidays[i];
                tb_holiday += '<tr>';
                    tb_holiday += '<td valign="middle">'+item.date+'</td>';
                    tb_holiday += '<td valign="middle"><button class="btn btn-danger btn-sm" id="'+item.date+'" onclick="holiday(this)">Delete</button></td>';
                tb_holiday += '</tr>';
            }
            document.getElementById("list_holiday").innerHTML = tb_holiday;
            $('#holiday-modal').modal('show');
        }
        else if (option == 1) {
            var date = document.getElementById("date-holiday").value;
            if (date == "") {
                document.getElementById("alert-holiday").innerText = "Please Select Date";
                document.getElementById("alert-holiday").classList.remove("visually-hidden");
                document.getElementById("alert-holiday").classList.add("visually-visible");
                return;
            }
            else {
                var save_holiday = requestHTTPS('../api/backend.php', {
                    'api': 'save-holiday',
                    'holiday': date,
                }, true);
                if (save_holiday.result == true) {
                    document.getElementById("btn-holiday").setAttribute("disabled", true);
                    document.getElementById("date-holiday").setAttribute("disabled", true);
                    document.getElementById("btn-holiday").innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...';
                    location.reload();
                }
                else {
                    document.getElementById("alert-holiday").innerText = save_holiday.message;
                    document.getElementById("alert-holiday").classList.remove("visually-hidden");
                    document.getElementById("alert-holiday").classList.add("visually-visible");
                    return;
                }
            }
        }
        else {
            var date = option.id;
            var delete_holiday = requestHTTPS('../api/backend.php', {
                'api': 'delete-holiday',
                'holiday': date,
            }, true);
            if (delete_holiday.result == false) {
                document.getElementById("alert-holiday").innerText = delete_holiday.message;
                document.getElementById("alert-holiday").classList.remove("visually-hidden");
                document.getElementById("alert-holiday").classList.add("visually-visible");
                return;
            }
            else if (delete_holiday.result == true) {
                location.reload();
            }
        }
    }

    function shutdown(option) {
        if (option == 0) {
            var load_shutdown = requestHTTPS('../api/backend.php', {
                'api' : 'load-shutdown'
            }, true);
            var tb_shutdown = "";
            for (var i = 0; i < load_shutdown.list_shutdown.length; i++) {
                var item = load_shutdown.list_shutdown[i];
                tb_shutdown += '<tr>';
                    tb_shutdown += '<td valign="middle">'+item.date_shutdown+'</td>';
                    tb_shutdown += '<td valign="middle"><button class="btn btn-danger btn-sm" id="'+item.date_shutdown+'" onclick="shutdown(this)">Delete</button></td>';
                tb_shutdown += '</tr>';
            }
            document.getElementById("list_shutdown").innerHTML = tb_shutdown;
            $('#shutdown-modal').modal('show');
        }
        else if (option == 1) {
            var date = document.getElementById("date-shutdown").value;
            if (date == "") {
                document.getElementById("alert-shutdown").innerText = "Please Select Date";
                document.getElementById("alert-shutdown").classList.remove("visually-hidden");
                document.getElementById("alert-shutdown").classList.add("visually-visible");
                return;
            }
            else {
                var save_shutdown = requestHTTPS('../api/backend.php', {
                    'api': 'save-shutdown',
                    'shutdown': date,
                }, true);
                if (save_shutdown.result == true) {
                    document.getElementById("btn-shutdown").setAttribute("disabled", true);
                    document.getElementById("date-shutdown").setAttribute("disabled", true);
                    document.getElementById("btn-shutdown").innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...';
                    location.reload();
                }
                else {
                    document.getElementById("alert-shutdown").innerText = save_shutdown.message;
                    document.getElementById("alert-shutdown").classList.remove("visually-hidden");
                    document.getElementById("alert-shutdown").classList.add("visually-visible");
                    return;
                }
            }
        }
        else {
            var date = option.id;
            var delete_shutdown = requestHTTPS('../api/backend.php', {
                'api': 'delete-shutdown',
                'shutdown': date,
            }, true);
            if (delete_shutdown.result == false) {
                document.getElementById("alert-shutdown").innerText = delete_shutdown.message;
                document.getElementById("alert-shutdown").classList.remove("visually-hidden");
                document.getElementById("alert-shutdown").classList.add("visually-visible");
                return;
            }
            else if (delete_shutdown.result == true) {
                location.reload();
            }
        }
    }

    function loadShutdown() {
        var shutdown = requestHTTPS('../api/backend.php', {
            'api': 'load-shutdown',
        }, true);
        var team = requestHTTPS('../api/backend.php', {
            'api': 'load-team',
        }, true);

        for (var i = 0; i < team.list_team.length; i++) {
            var team_name = team.list_team[i];
            for (var j = 0; j < shutdown.list_shutdown.length; j++) {
                var shutdown_date = shutdown.list_shutdown[j];
                if (document.getElementById(team_name.team+":"+shutdown_date.date_shutdown).innerHTML != "H") {
                    document.getElementById(team_name.team+":"+shutdown_date.date_shutdown).innerHTML = shutdown_date.inner_html;
                    document.getElementById(team_name.team+":"+shutdown_date.date_shutdown).style.backgroundColor = shutdown_date.bg_color;
                    document.getElementById(team_name.team+":"+shutdown_date.date_shutdown).style.color = shutdown_date.text_color;
                    document.getElementById(team_name.team+":"+shutdown_date.date_shutdown).classList.add(shutdown_date.font_weight);
                }
            }
        }
    }

    function logout() {
        var logout_api = requestHTTPS('../api/backend.php', {
            'api': 'logout',
        }, true);
        if (logout_api.result == true) {
            window.location.href = "../login.php";
        }
    }
</script>
</body>
</html>
