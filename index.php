<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="libs/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="libs/fontawesome/css/all.min.css">

    <link rel="icon" type="image/png" href="images/icon.png"/>

    <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="libs/jquery/jquery-3.6.3.js"></script>
    <script src="api/https.js"></script>
    <script src="js/datetimeformat.js"></script>

    <title>Attendance</title>

    <style>
        #active-username {
            background-color: lightskyblue;
            animation: blink1 2s linear infinite;
        }
        #active-team {
            background-color: lightskyblue;
            animation: blink2 2s linear infinite;
        }
        #active-nickname {
            background-color: lightskyblue;
            animation: blink3 2s linear infinite;
        }
        @keyframes blink1 {
            50% {
                background-color: white;
            }
        }
        @keyframes blink2 {
            50% {
                background-color: white;
            }
        }
        @keyframes blink3 {
            50% {
                background-color: white;
            }
        }
        table {
            font-size: smaller;
        }
    </style>
</head>

<body>
    <?php
    if (empty($_SESSION['daily_plan_en']) && empty($_SESSION['daily_plan'])) {
        echo "<script>window.location.href = 'login.php'</script>";
    }
    else if (isset($_SESSION['daily_plan_en']) && isset($_SESSION['daily_plan'])) {
        echo '<script> var EN_USER = "'.$_SESSION['daily_plan_en'].'";</script>';
        echo '<script> var FULLNAME = "'.$_SESSION['daily_plan_fullname'].'";</script>';
        echo '<script> var team = "'.$_SESSION['daily_plan_team'].'";</script>';
        echo '<script> var USERTYPE = "'.$_SESSION['daily_plan_usertype'].'";</script>';
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
                                <button class="btn" onclick="location.reload();"><i class="bi bi-house-door-fill me-2 text-dark"></i>Home</button>
                            </li>
                            <?php
                            if ($_SESSION['daily_plan_usertype'] == "Admin") {
                                echo '<li class="nav-item"><button class="btn" onclick="window.location.href = \'pages/admin.php\'"><i class="bi bi-person-circle me-2 text-primary"></i>Admin</button></li>';
                                echo '<li class="nav-item"><button class="btn" onclick="window.location.href = \'pages/plan.php\'"><i class="bi bi-calendar-week-fill me-2 text-primary"></i>Plan</button></li>';
                            } 
                            ?>
                            <li class="nav-item">
                                <button class="btn" onclick="changePassword(false)"><i class="fa-solid fa-gear me-2 text-success"></i>Change Password</button>
                            </li>
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

    <section class="mt-4 pt-5 container-fluid">
        <div class="text-center">
            <h4 id="today-title"></h4>
        </div>
        <div class="row text-center align-items-end justify-content-center">
            <div class="col-lg-2">
                <div class="card mx-auto border-0" style="width: 200px; height: 120px;">
                    <img src="images/football.jpg" alt="football-image" class="card-img" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="border border-3 rounded-4 border-success py-1 shadow">
                    <h5><i class="bi bi-sun-fill me-2"></i>DAY</h5>
                    <h2 id="count_d"><div class="spinner-border text-success my-2" role="status"></div></h5>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="border border-3 rounded-4 border-warning py-1 shadow">
                    <h5><i class="bi bi-moon-fill me-2"></i>NIGHT</h5>
                    <h2 id="count_n"><div class="spinner-border text-warning my-2" role="status"></div></h2>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="border border-3 rounded-4 border-secondary py-1 shadow">
                    <h5><i class="bi bi-building-fill-x me-2"></i></i>OFF</h5>
                    <h2 id="count_off"><div class="spinner-border text-secondary my-2" role="status"></div></h2>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="border border-3 rounded-4 border-danger py-1 shadow">
                    <h5><i class="bi bi-person-fill-slash me-2"></i>LEAVE</h5>
                    <h2 id="count_al"><div class="spinner-border text-danger my-2" role="status"></div></h2>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="card mx-auto border-0" style="width: 200px; height: 120px;">
                    <img src="images/screw.jpg" alt="screw-image" class="card-img" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </section>

    <hr>

    <div class="container mt-4 px-4 pt-3">
        <div class="input-group shadow-sm">
            <select class="form-select" id="month-select">

            </select>
            <select class="form-select" id="year-select">

            </select>
            <button class="btn btn-outline-primary d-sm-block px-5" onclick="changeTablePlan()"><i class="bi bi-arrow-right-square-fill me-2"></i>Go</button>
        </div>
    </div>

    <div class="mt-2 px-4 py-4 container-fluid">
        <div class="table-responsive text-nowrap position-relative overflow-auto vh-100">
            <table class="table table-hover table-sm table-borderless w-auto" id="day-table">
                <thead class="text-center border-dark border-top border-bottom sticky-top">
                    <tr id="date_thead">
                        
                    </tr>
                    <tr id="day_thead">
                        
                    </tr>
                </thead>
                <tbody class="text-center" id="user_column">
                    
                </tbody>
            </table>
        </div>
    </div>
    <hr>

    <div class="modal fade" tabindex="-1" id="option-plan-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="date-select-plan">Select Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container" id="list-options">
                    
                    </div>

                    <div class="alert alert-danger mt-4 visually-hidden" role="alert" id="alert-select-plan"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-save-plan">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="change-password-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new-password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new-password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-password">
                    </div>
                    <div class="alert alert-danger visually-hidden mt-3" role="alert" id="alert-password"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-change-password" onclick="changePassword(true)">Save</button>
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

        // if (USERTYPE == "Admin") {
        //     expandPlan(date, month, year);
        // }
        renderInputMonthYear(current);
        renderTablePlan(month, year);
        loadShutdown();
        countUserWork(date, month, year);
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
        var week_prev_month = new Date(year, month, -6).getDate();
        var last_date_next_month = new Date(year, month+2, 0).getDate();

        var prev_month = new Date(year, month-1, 1).getMonth();
        var prev_year = new Date(year, month-1, 1).getFullYear();
        var next_month = new Date(year, month+1, 1).getMonth();
        var next_year = new Date(year, month+1, 1).getFullYear();

        var DATEsOBJECT = {
            'last_date_current_month': last_date_current_month,
            'last_date_prev_month': last_date_prev_month,
            'week_prev_month': week_prev_month,
            'last_date_next_month': last_date_next_month,
            'current_month': month,
            'current_year': year,
            'prev_month': prev_month,
            'prev_year': prev_year,
            'next_month': next_month,
            'next_year': next_year,
        }

        var date_thead = "";
        var day_thead = "";
        date_thead += '<th valign="bottom" class="fw-semibold text-dark border-dark border-start border-end position-sticky top-0 start-0 bg-white" style="z-index: 1;">NAME</th>';
        date_thead += '<th valign="bottom" class="fw-semibold text-dark border-dark border-start border-end position-sticky top-0 bg-white" style="z-index: 0;">TEAM</th>';
        date_thead += '<th valign="bottom" class="fw-semibold text-dark border-dark border-start border-end position-sticky top-0 bg-white">NICKNAME</th>';
        
        // load previous month ===============================================================================
        for (var i = week_prev_month; i <= last_date_prev_month; i++) {
            var date_fmt = dateFormat(i, "twodigit");
            var month_fmt = monthFormat(prev_month, "idx");
            var year_fmt = yearFormat(prev_year , "fourdigit");
            date_thead += '<th valign="middle" class="fw-bold text-white border-white border-start border-end" style="background-color: #646464;">';
                date_thead += '<div>';
                    date_thead += '<span class="text-center h6">'+date_fmt.twodigit_string+'</span><br>';
                    date_thead += '<span class="text-center h6">'+month_fmt.short+'</span><br>';
                    date_thead += '<span class="text-center h6">'+year_fmt.twodigit_string+'</span>';
                date_thead += '</div>';
            date_thead += '</th>';
        }
        //====================================================================================================

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

        // load next month ===================================================================================
        for (var i = 1; i <= last_date_next_month; i++) {
            if (i < 10) {
                var date_fmt = dateFormat(i, "onedigit");
            }
            else if (i >= 10) {
                var date_fmt = dateFormat(i, "twodigit");
            }
            var month_fmt = monthFormat(next_month, "idx");
            var year_fmt = yearFormat(next_year, "fourdigit");
            date_thead += '<th valign="middle" class="fw-bold text-white border-white border-start border-end" style="background-color: #646464;">';
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

        day_thead += '<td class="border-dark border-start border-end position-sticky start-0 top-0 bg-white" style="z-index: 1;"></td>';
        day_thead += '<td class="border-dark border-start border-end position-sticky top-0 bg-white"></td>';
        day_thead += '<td class="border-dark border-start border-end position-sticky top-0 bg-white"></td>';
        // load previous month ===============================================================================
        for (var i = week_prev_month; i <= last_date_prev_month; i++) {
            d = new Date(prev_year, prev_month, i).getDay();
            var day_fmt = dayFormat(d, "idx");
            day_thead += '<td class="text-dark border-top border-bottom border-start border-dark" style="background-color: '+day_fmt.color+';">';
                day_thead += '<div class="py-3" style="transform: rotate(270deg);">';
                    day_thead += '<span class="text-center fw-semibold">'+day_fmt.short+'</span>';
                day_thead += '</div>';
            day_thead += '</td>';
        }
        //====================================================================================================

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

        // load next month ===================================================================================
        for (var i = 1; i <= last_date_next_month; i++) {
            d = new Date(next_year, next_month, i).getDay();
            var day_fmt = dayFormat(d, "idx");
            day_thead += '<td class="text-dark border-top border-bottom border-start border-dark" style="background-color: '+day_fmt.color+';">';
                day_thead += '<div class="py-3" style="transform: rotate(270deg);">';
                    day_thead += '<span class="text-center fw-semibold">'+day_fmt.short+'</span>';
                day_thead += '</div>';
            day_thead += '</td>';
        }
        //====================================================================================================

        document.getElementById("day_thead").innerHTML = day_thead;
        renderUser(month, year, DATEsOBJECT);
    }

    function changeTablePlan() {
        var select_month = document.getElementById("month-select").value;
        var select_year = document.getElementById("year-select").value;
        var month_cvt = parseInt(select_month);
        var year_cvt = parseInt(select_year);
        var new_plan = new Date();
        renderTablePlan(month_cvt, year_cvt);
    }

    function renderUser(month, year, DATEsOBJECT) {
        var today = new Date();
        var thisday = today.getDate();
        var thismonth = today.getMonth();
        var thisyear = today.getFullYear();
        var load_user = requestHTTPS('api/backend.php', {
            'api': 'load-user',
        }, true);

        var user_column = "";
        var temp = load_user.list_user[0].team;
        for (var i = 0; i < load_user.list_user.length; i++) {
            var item = load_user.list_user[i];

            if (i < load_user.list_user.length-1) {
                if (item.team != load_user.list_user[i+1].team && load_user.list_user[i+1].team != "Office") {
                    user_column += '<tr class="" style="border-bottom: 3px double black;">';
                }
                else if (item.team != load_user.list_user[i+1].team && load_user.list_user[i+1].team == "Office") {
                    user_column += '<tr class="" style="border-bottom: 4px solid black;">';
                }
                else {
                    user_column += '<tr class="border-bottom">';
                }
            }
            else if (i == load_user.list_user.length-1) {
                user_column += '<tr class="border-bottom border-dark">';
            }
            
            if (item.en == EN_USER) {
                user_column += '<td valign="middle" id="active-username" class="border-dark border-start border-end position-sticky start-0" style="z-index: 0;">'+item.fullname+'</td>';
                user_column += '<td valign="middle" id="active-team" class="border-dark border-start border-end" style="z-index: -1;">'+item.team+'</td>';
                user_column += '<td valign="middle" id="active-nickname" class="border-dark border-start border-end" style="z-index: -1;">'+item.nickname+'</td>';
            }
            else if (item.en != EN_USER) {
                user_column += '<td valign="middle" class="border-dark border-start border-end position-sticky start-0 bg-white" style="z-index: 0;">'+item.fullname+'</td>';
                user_column += '<td valign="middle" class="border-dark border-start border-end" style="z-index: -1;">'+item.team+'</td>';
                user_column += '<td valign="middle" class="border-dark border-start border-end" style="z-index: -1;">'+item.nickname+'</td>';
            }

            // load previous month ===============================================================================
            for (var j = DATEsOBJECT.week_prev_month; j <= DATEsOBJECT.last_date_prev_month; j++) {
                var id_td = genID(DATEsOBJECT.prev_year, DATEsOBJECT.prev_month, j);
                if (EN_USER == item.en || USERTYPE == "Admin") {
                    user_column += '<td valign="middle" id="'+item.en+':'+id_td+'" class="border-dark border-start booder-end" style="cursor: pointer;" onclick="optionPopUp(this)">none</td>';
                }
                else {
                    user_column += '<td valign="middle" id="'+item.en+':'+id_td+'" class="table-active border-dark border-start booder-end">none</td>';
                }
            }
            //====================================================================================================

            // load current month ================================================================================
            for (var j = 1; j <= DATEsOBJECT.last_date_current_month; j++) {
                var id_td = genID(year, month, j);
                if (EN_USER == item.en || USERTYPE == "Admin") {
                    user_column += '<td valign="middle" id="'+item.en+':'+id_td+'" class="border-dark border-start booder-end" style="cursor: pointer;" onclick="optionPopUp(this)">none</td>';
                }
                else {
                    user_column += '<td valign="middle" id="'+item.en+':'+id_td+'" class="table-active border-dark border-start booder-end">none</td>';
                }
            }
            //====================================================================================================

            // load next month ===================================================================================
            for (var j = 1; j <= DATEsOBJECT.last_date_next_month; j++) {
                var id_td = genID(DATEsOBJECT.next_year, DATEsOBJECT.next_month, j);
                if (EN_USER == item.en || USERTYPE == "Admin") {
                    user_column += '<td valign="middle" id="'+item.en+':'+id_td+'" class="border-dark border-start booder-end" style="cursor: pointer;" onclick="optionPopUp(this)">none</td>';
                }
                else {
                    user_column += '<td valign="middle" id="'+item.en+':'+id_td+'" class="table-active border-dark border-start booder-end">none</td>';
                }
            }
            //====================================================================================================

            user_column += '</tr>';
        }

        user_column += '<tr><td class="position-sticky start-0" colspan="'+(DATEsOBJECT.last_date_current_month+3)+'"></td></tr>';

        user_column = columnResult("D", DATEsOBJECT, "#C6E0B4", user_column);
        user_column = columnResult("N", DATEsOBJECT, "#FFE699", user_column);
        user_column = columnResult("AL", DATEsOBJECT, "#FF5050", user_column);

        document.getElementById("user_column").innerHTML = user_column;
        userPlan(month, year, DATEsOBJECT);
        dailyResult(month, year, DATEsOBJECT);
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

    function userPlan(month, year, DATEsOBJECT) {
        var user_plan = requestHTTPS('api/backend.php', {
            'api': 'load-user-plan',
            'month': month+1,
            'year': year,
            'week_prev_month': DATEsOBJECT.week_prev_month,
            'last_date': DATEsOBJECT.last_date_prev_month,
            'last_date_next_month': DATEsOBJECT.last_date_next_month,
            'prev_month': DATEsOBJECT.prev_month+1,
            'next_month': DATEsOBJECT.next_month+1,
            'prev_year': DATEsOBJECT.prev_year,
            'next_year': DATEsOBJECT.next_year,
        }, true);

        for (var i in user_plan.plan_current) {
            var item = user_plan.plan_current[i];
            document.getElementById(item.en_user+":"+item.datesave).innerHTML = item.inner_html;
            document.getElementById(item.en_user+":"+item.datesave).title = item.options;
            document.getElementById(item.en_user+":"+item.datesave).style.backgroundColor = item.bg_color;
            document.getElementById(item.en_user+":"+item.datesave).style.color = item.text_color;
            document.getElementById(item.en_user+":"+item.datesave).classList.add(item.font_weight);
        }

        for (var i in user_plan.plan_previous) {
            var item = user_plan.plan_previous[i];
            document.getElementById(item.en_user+":"+item.datesave).innerHTML = item.inner_html;
            document.getElementById(item.en_user+":"+item.datesave).title = item.options;
            document.getElementById(item.en_user+":"+item.datesave).style.backgroundColor = item.bg_color;
            document.getElementById(item.en_user+":"+item.datesave).style.color = item.text_color;
            document.getElementById(item.en_user+":"+item.datesave).classList.add(item.font_weight);
        }

        for (var i in user_plan.plan_next) {
            var item = user_plan.plan_next[i];
            document.getElementById(item.en_user+":"+item.datesave).innerHTML = item.inner_html;
            document.getElementById(item.en_user+":"+item.datesave).title = item.options;
            document.getElementById(item.en_user+":"+item.datesave).style.backgroundColor = item.bg_color;
            document.getElementById(item.en_user+":"+item.datesave).style.color = item.text_color;
            document.getElementById(item.en_user+":"+item.datesave).classList.add(item.font_weight);
        }
    }

    function loadShutdown() {
        var shutdown = requestHTTPS('api/backend.php', {
            'api': 'load-shutdown',
        }, true);
        var users = requestHTTPS('api/backend.php', {
            'api': 'load-user',
        }, true);

        for (var i = 0; i < users.list_user.length; i++) {
            var user = users.list_user[i];
            for (var j = 0; j < shutdown.list_shutdown.length; j++) {
                var shutdown_date = shutdown.list_shutdown[j];
                if (document.getElementById(user.en+":"+shutdown_date.date_shutdown).title != "H") {
                    document.getElementById(user.en+":"+shutdown_date.date_shutdown).innerHTML = shutdown_date.inner_html;
                    document.getElementById(user.en+":"+shutdown_date.date_shutdown).title = shutdown_date.options;
                    document.getElementById(user.en+":"+shutdown_date.date_shutdown).style.backgroundColor = shutdown_date.bg_color;
                    document.getElementById(user.en+":"+shutdown_date.date_shutdown).style.color = shutdown_date.text_color;
                    document.getElementById(user.en+":"+shutdown_date.date_shutdown).classList.add(shutdown_date.font_weight);
                }
            }
        }
    }

    function columnResult(options, DATEsOBJECT, bg, tb) {
        var result_column = tb;

        result_column += '<tr class="border-bottom border-dark">';
            result_column += '<td class="position-sticky start-0 bg-white" style="z-index: 1;">Total : '+options+'</td>';
            result_column += '<td></td>';
            result_column += '<td></td>';

            for (var i = DATEsOBJECT.week_prev_month; i <= DATEsOBJECT.last_date_prev_month; i++) {
                var id_td_ele = genID(DATEsOBJECT.prev_year, DATEsOBJECT.prev_month, i);
                result_column += '<td class="border-dark border-start fw-semibold text-dark" id="sum'+options+':'+id_td_ele+'" style="background-color: '+bg+'">0</td>';
            }

            for (var i = 1; i <= DATEsOBJECT.last_date_current_month; i++) {
                var id_td_ele = genID(DATEsOBJECT.current_year, DATEsOBJECT.current_month, i);
                result_column += '<td class="border-dark border-start fw-semibold text-dark" id="sum'+options+':'+id_td_ele+'" style="background-color: '+bg+'">0</td>';
            }

            for (var i = 1; i <= DATEsOBJECT.last_date_next_month; i++) {
                var id_td_ele = genID(DATEsOBJECT.next_year, DATEsOBJECT.next_month, i);
                result_column += '<td class="border-dark border-start fw-semibold text-dark" id="sum'+options+':'+id_td_ele+'" style="background-color: '+bg+'">0</td>';
            }

        result_column += '</tr>';

        return result_column;
    }

    function dailyResult(month, year, DATEsOBJECT) {
        var daily_count = requestHTTPS('api/backend.php', {
            'api': 'daily-result',
            'last_date_current_month': DATEsOBJECT.last_date_current_month,
            'week_prev_month': DATEsOBJECT.week_prev_month,
            'last_date_prev_month': DATEsOBJECT.last_date_prev_month,
            'last_date_next_month': DATEsOBJECT.last_date_next_month,
            'current_month': monthFormat(DATEsOBJECT.current_month, "idx").twodigit,
            'current_year': yearFormat(DATEsOBJECT.current_year, "fourdigit").fourdigit_string,
            'prev_month': monthFormat(DATEsOBJECT.prev_month, "idx").twodigit,
            'prev_year': yearFormat(DATEsOBJECT.prev_year, "fourdigit").fourdigit_string,
            'next_month': monthFormat(DATEsOBJECT.next_month, "idx").twodigit,
            'next_year': yearFormat(DATEsOBJECT.next_year, "fourdigit").fourdigit_string,
        }, true);
        
        for (var i = 0; i < daily_count.result_current_month.length; i++) {
            var item = daily_count.result_current_month[i];
            document.getElementById("sumD:"+item.datesave).innerText = item.result_D;
            document.getElementById("sumN:"+item.datesave).innerText = item.result_N;
            document.getElementById("sumAL:"+item.datesave).innerText = item.result_AL;
        }

        for (var i in daily_count.result_previous_month) {
            var item = daily_count.result_previous_month[i];
            document.getElementById("sumD:"+item.datesave).innerText = item.result_D;
            document.getElementById("sumN:"+item.datesave).innerText = item.result_N;
            document.getElementById("sumAL:"+item.datesave).innerText = item.result_AL;
        }

        for (var i in daily_count.result_next_month) {
            var item = daily_count.result_next_month[i];
            document.getElementById("sumD:"+item.datesave).innerText = item.result_D;
            document.getElementById("sumN:"+item.datesave).innerText = item.result_N;
            document.getElementById("sumAL:"+item.datesave).innerText = item.result_AL;
        }
    }

    function optionPopUp(ele) {
        var ele_id = ele.id.split(":");
        var en_title = ele_id[0];
        var date_title = ele_id[1];   // ex. 2023-01-31
        var date_head = date_title[8] + date_title[9];
        var month_head = monthFormat(date_title[5] + date_title[6], "twodigit").short;
        var year_head = date_title[0] + date_title[1] + date_title[2] + date_title[3]
        document.getElementById("date-select-plan").innerText = date_head + " " + month_head + ". " + year_head;

        loadOption();
        if (ele.innerHTML != "none" && ele.innerHTML != "H" && ele.innerHTML != "SD") {
            var radio_btn = "option-" + ele.title;
            document.getElementById(radio_btn).checked = true;
            if (ele.style.backgroundColor == "#FFFFFF" || ele.style.backgroundColor == "rgb(255, 255, 255)") {
                document.getElementById(radio_btn).style.backgroundColor = ele.style.color;
            }
            else {
                document.getElementById(radio_btn).style.backgroundColor = ele.style.backgroundColor;
            }
        }
        else {
            var none_btn = document.getElementsByName("option-plan-radio");
            for (var i = 0; i < none_btn.length; i++) {
                none_btn[i].checked = false;
            }
        }
        
        var btn_save = document.getElementById("btn-save-plan");
        btn_save.onclick = function() {
            savePlanUser(date_title, en_title);
        }
        $('#option-plan-modal').modal('show');
    }

    function loadOption() {
        var load_options = requestHTTPS('api/backend.php', {
            'api': 'load-options',
        }, true);

        var options = "";
        for (var i = 0; i < load_options.list_option.length; i++) {
            var item = load_options.list_option[i];
            if ((item.options == "OFF" || item.options == "SET" || item.options == "OT-D" || item.options == "OT-N") && USERTYPE != "Admin") {
                options += '<div class="py-1 px-2 mb-2 rounded-3 visually-hidden radio-box" style="border: 1px solid '+item.border_color+';">';
                    options += '<input type="radio" class="form-check-input me-2 mt-0" name="option-plan-radio" id="option-'+item.options+'" autocomplete="off" style="border-color: '+item.border_color+'; vertical-align: middle;" onclick="clickOption(this)">';
                    options += '<label class="badge border border-dark fs-4 py-1 px-2 '+item.font_weight+'" for="option-'+item.options+'" style="color: '+item.text_color+'; background-color: '+item.bg_color+'; vertical-align: middle;">'+item.inner_html+'</label>';
                options += '</div>';
            }
            else {
                options += '<div class="py-1 px-2 mb-2 rounded-3 radio-box" style="border: 1px solid '+item.border_color+';">';
                    options += '<input type="radio" class="form-check-input me-2 mt-0" name="option-plan-radio" id="option-'+item.options+'" autocomplete="off" style="border-color: '+item.border_color+'; vertical-align: middle;" onclick="clickOption(this)">';
                    options += '<label class="badge border border-dark fs-4 py-1 px-2 '+item.font_weight+'" for="option-'+item.options+'" style="color: '+item.text_color+'; background-color: '+item.bg_color+'; vertical-align: middle;">'+item.inner_html+'</label>';
                options += '</div>';
            }
        }
        document.getElementById("list-options").innerHTML = options;
    }

    function clickOption(checkbox) {
        var radios = document.getElementsByName("option-plan-radio");
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].id == checkbox.id) {
                document.getElementById(checkbox.id).style.backgroundColor = checkbox.style.borderColor;
            }
            else {
                document.getElementById(radios[i].id).style.backgroundColor = null;
            }
        }
    }

    function savePlanUser(date, en) {
        var radio = document.getElementsByName("option-plan-radio");
        var uncheck = 0;
        for (var i = 0; i < radio.length; i++) {
            if (radio[i].checked == false) {
                uncheck++;
            }
        }

        if (uncheck == radio.length) {
            document.getElementById("alert-select-plan").innerText = "Please Select Option!";
            document.getElementById("alert-select-plan").classList.remove("visually-hidden");
            document.getElementById("alert-select-plan").classList.add("visually-visible");
            return;
        }
        else {
            for (var j = 0; j < radio.length; j++) {
                if (radio[j].checked) {
                    var plan = radio[j].id.replace("option-", "");
                    var save_plan = requestHTTPS('api/backend.php', {
                        'api': 'save-plan',
                        'en_user': en,
                        'datesave': date,
                        'options': plan,
                    }, true);
                    if (save_plan.result == true) {
                        document.getElementById("btn-save-plan").setAttribute("disabled", true);
                        document.getElementById("btn-save-plan").innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...';
                        location.reload();
                    }
                    else if (save_plan.result == false) {
                        document.getElementById("alert-select-plan").innerText = save_plan.message;
                        document.getElementById("alert-select-plan").classList.remove("visually-hidden");
                        document.getElementById("alert-select-plan").classList.add("visually-visible");
                        return;
                    }
                }
            }
        }
    }

    function countUserWork(date, month, year) {
        var m = monthFormat(month, "idx");
        var today = year + "-" + m.twodigit + "-" + date;
        document.getElementById("today-title").innerText = date + "-" + m.short + "-" + year;
        var count_user = requestHTTPS('api/backend.php', {
            'api': 'count-user-today',
            'today': today,
        }, true);
        document.getElementById("count_d").innerText = count_user.today_d;
        document.getElementById("count_n").innerText = count_user.today_n;
        document.getElementById("count_off").innerText = count_user.today_off;
        document.getElementById("count_al").innerText = count_user.today_al;
    }

    function changePassword(option) {
        if (option == false) {
            $('#change-password-modal').modal('show');
        }
        else if (option == true) {
            var new_password = document.getElementById("new-password");
            var confirm_password = document.getElementById("confirm-password");
            if (new_password.value != confirm_password.value) {
                new_password.classList.add("border");
                new_password.classList.add("border-danger");
                confirm_password.classList.add("border");
                confirm_password.classList.add("border-danger");
                document.getElementById("alert-password").classList.remove("visually-hidden");
                document.getElementById("alert-password").classList.add("visually-visible");
                document.getElementById("alert-password").innerText = "Password do not match!";
                return;
            }
            else {
                var change_password = requestHTTPS('api/backend.php', {
                    'api': 'change-password', 
                    'password': new_password.value
                }, true);
                if (change_password.result == true) {
                    document.getElementById("btn-change-password").setAttribute("disabled", true);
                    document.getElementById("btn-change-password").innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...';
                    location.reload();
                }
                else {
                    document.getElementById("alert-password").innerText = change_password.message;
                    document.getElementById("alert-password").classList.remove("visually-hidden");
                    document.getElementById("alert-password").classList.add("visually-visible");
                    return;
                }
            }
        }
    }

    function expandPlan(d, m, y) {
        var today = new Date();

        if (d < 10) {
            var d_cvt = dateFormat(d, "onedigit").twodigit_string;
        }
        else if (d > 10) {
            var d_cvt = dateFormat(d, "twodigit").twodigit_string;

        }
        var m_cvt = monthFormat(m, "idx").twodigit;
        var date_today = y + "-" + m_cvt + "-" + d_cvt;

        var expand = requestHTTPS('api/backend.php', {
            'api': 'expand-plan',
            'date': date_today
        }, true);
    }

    function logout() {
        var logout_api = requestHTTPS('api/backend.php', {
            'api': 'logout',
        }, true);
        if (logout_api.result == true) {
            window.location.href = "login.php";
        }
    }
</script>
</body>
</html>
