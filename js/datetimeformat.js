function dayFormat(input, format) {
    var days = [
        {"idx": 0, "num": 1, "short": "Sun", "full": "Sunday", "color": "#FF0000", "th_full": "อาทิตย์", "th_short": "อา"},
        {"idx": 1, "num": 2, "short": "Mon", "full": "Monday", "color": "#FFFF66", "th_full": "จันทร์", "th_short": "จ"},
        {"idx": 2, "num": 3, "short": "Tue", "full": "Tuesday", "color": "#FF99CC", "th_full": "อังคาร", "th_short": "อ"},
        {"idx": 3, "num": 4, "short": "Wed", "full": "Wednesday", "color": "#99FF99", "th_full": "พุธ", "th_short": "พ"},
        {"idx": 4, "num": 5, "short": "Thu", "full": "Thursday", "color": "#FF9933", "th_full": "พฤหัสบดี", "th_short": "พฤ"},
        {"idx": 5, "num": 6, "short": "Fri", "full": "Friday", "color": "#00CCFF", "th_full": "ศุกร์", "th_short": "ศ"},
        {"idx": 6, "num": 7, "short": "Sat", "full": "Saturday", "color": "#CC99FF", "th_full": "เสาร์", "th_short": "ส"},
    ];

    if (format == "idx") {
        for (var i in days) {
            if (days[i].idx == input) {
                return days[i];
            }
        }
    }
    else if (format == "num") {
        for (var i in days) {
            if (days[i].num == input) {
                return days[i];
            }
        }
    }
    else if (format == "full") {
        for (var i in days) {
            if (days[i].full == input) {
                return days[i];
            }
        }
    }
    else if (format == "short") {
        for (var i in days) {
            if (days[i].short == input) {
                return days[i];
            }
        }
    }
    else {
        return "'" + format + "' Invalid Format!";
    }
}

function monthFormat(input, format) {
    var months = [
        {"idx": 0, "num": 1, "twodigit": "01", "short": "Jan", "full": "January", "th_full": "มกราคม", "th_short": "ม.ค."},
        {"idx": 1, "num": 2, "twodigit": "02", "short": "Feb", "full": "February", "th_full": "กุมภาพันธ์", "th_short": "ก.พ."},
        {"idx": 2, "num": 3, "twodigit": "03", "short": "Mar", "full": "March", "th_full": "มีนาคม", "th_short": "มี.ค."},
        {"idx": 3, "num": 4, "twodigit": "04", "short": "Apr", "full": "April", "th_full": "เมษายน", "th_short": "เม.ย."},
        {"idx": 4, "num": 5, "twodigit": "05", "short": "May", "full": "May", "th_full": "พฤษภาคม", "th_short": "พ.ค."},
        {"idx": 5, "num": 6, "twodigit": "06", "short": "Jun", "full": "June", "th_full": "มิถุนายน", "th_short": "มิ.ย."},
        {"idx": 6, "num": 7, "twodigit": "07", "short": "Jul", "full": "July", "th_full": "กรกฎาคม", "th_short": "ก.ค."},
        {"idx": 7, "num": 8, "twodigit": "08", "short": "Aug", "full": "August", "th_full": "สิงหาคม", "th_short": "ส.ค."},
        {"idx": 8, "num": 9, "twodigit": "09", "short": "Sep", "full": "September", "th_full": "กันยายน", "th_short": "ก.ย."},
        {"idx": 9, "num": 10, "twodigit": "10", "short": "Oct", "full": "October", "th_full": "ตุลาคม", "th_short": "ต.ค."},
        {"idx": 10, "num": 11, "twodigit": "11", "short": "Nov", "full": "November", "th_full": "พฤศจิกายน", "th_short": "พ.ย."},
        {"idx": 11, "num": 12, "twodigit": "12", "short": "Dec", "full": "December", "th_full": "ธันวาคม", "th_short": "ธ.ค."},
    ];

    if (format == "idx") {
        for (var i in months) {
            if (months[i].idx == input) {
                return months[i];
            }
        }
    }
    else if (format == "num") {
        for (var i in months) {
            if (months[i].num == input) {
                return months[i];
            }
        }
    }
    else if (format == "twodigit") {
        for (var i in months) {
            if (months[i].twodigit == input) {
                return months[i];
            }
        }
    }
    else if (format == "short") {
        for (var i in months) {
            if (months[i].short == input) {
                return months[i];
            }
        }
    }
    else if (format == "full") {
        for (var i in months) {
            if (months[i].full == input) {
                return months[i];
            }
        }
    }
    else {
        return "'" + format + "' Invalid Format!";
    }
}

function yearFormat(input, format) {
    if (format == "fourdigit") {
        if (typeof input == "number") {
            var num2string = input.toString();
            var th_int = input + 543;
            var th_string = th_int.toString();
            return {
                'fourdigit_int': input,
                'fourdigit_string': num2string,
                'twodigit_string': num2string[2]+num2string[3],
                'th_fourdigit_int': th_int,
                'th_fourdigit_string': th_string,
                'th_twodigit_string': th_string[2]+th_string[3],
            }
        }
        else if (typeof input == "string") {
            var string2num = parseInt(input);
            var th_int = string2num + 543;
            var th_string = th_int.toString(); 
            return {
                'fourdigit_int': string2num,
                'fourdigit_string': input,
                'twodigit_string': input[2]+input[3],
                'th_fourdigit_int': th_int,
                'th_fourdigit_string': th_string,
                'th_twodigit_string': th_string[2]+th_string[3],
            }
        }
    }
    else if (format == "fourdigit_th") {
        if (typeof input == "number") {
            var num2string = input.toString();
            var th_int = input - 543;
            var th_string = th_int.toString();
            return {
                'fourdigit_int': input,
                'fourdigit_string': num2string,
                'twodigit_string': num2string[2]+num2string[3],
                'th_fourdigit_int': th_int,
                'th_fourdigit_string': th_string,
                'th_twodigit_string': th_string[2]+th_string[3],
            }
        }
        else if (typeof input == "string") {
            var string2num = parseInt(input);
            var th_int = string2num - 543;
            var th_string = th_int.toString(); 
            return {
                'fourdigit_int': string2num,
                'fourdigit_string': input,
                'twodigit_string': input[2]+input[3],
                'th_fourdigit_int': th_int,
                'th_fourdigit_string': th_string,
                'th_twodigit_string': th_string[2]+th_string[3],
            }
        }
    }
}

function dateFormat(input, format) {
    if (format == "twodigit") {
        if (typeof input == "number") {
            var num2string = input.toString();
            return {
                'twodigit_int': input,
                'twodigit_string': num2string,
            }
        }
        else if (typeof input == "string") {
            var string2num = parseInt(input);
            return {
                'twodigit_int': string2num,
                'twodigit_string': input,
            }
        }
    }
    else if (format == "onedigit") {
        if (typeof input == "number") {
            var num2string = input.toString();
            return {
                'onedigit_int': input,
                'onedigit_string': num2string,
                'twodigit_string': "0"+num2string,
            }
        }
        else if (typeof input == "string") {
            var string2num = parseInt(input);
            return {
                'onedigit_int': string2num,
                'onedigit_string': input,
                'twodigit_string': "0"+input,
            }
        }
    }
}