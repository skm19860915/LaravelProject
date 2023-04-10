<?php

function countryDialCodes() {
    $codes = array(
        array('countryCode' => 'GB', 'code' => '44', 'name' => 'UK (+44)'),
        array('countryCode' => 'US', 'code' => '1', 'name' => 'USA (+1)'),
        array('countryCode' => 'DZ', 'code' => '213', 'name' => 'Algeria (+213)'),
        array('countryCode' => 'AD', 'code' => '376', 'name' => 'Andorra (+376)'),
        array('countryCode' => 'AO', 'code' => '244', 'name' => 'Angola (+244)'),
        array('countryCode' => 'AI', 'code' => '1264', 'name' => 'Anguilla (+1264)'),
        array('countryCode' => 'AG', 'code' => '1268', 'name' => 'Antigua &amp; Barbuda (+1268)'),
        array('countryCode' => 'AR', 'code' => '54', 'name' => 'Argentina (+54)'),
        array('countryCode' => 'AM', 'code' => '374', 'name' => 'Armenia (+374)'),
        array('countryCode' => 'AW', 'code' => '297', 'name' => 'Aruba (+297)'),
        array('countryCode' => 'AU', 'code' => '61', 'name' => 'Australia (+61)'),
        array('countryCode' => 'AT', 'code' => '43', 'name' => 'Austria (+43)'),
        array('countryCode' => 'AZ', 'code' => '994', 'name' => 'Azerbaijan (+994)'),
        array('countryCode' => 'BS', 'code' => '1242', 'name' => 'Bahamas (+1242)'),
        array('countryCode' => 'BH', 'code' => '973', 'name' => 'Bahrain (+973)'),
        array('countryCode' => 'BD', 'code' => '880', 'name' => 'Bangladesh (+880)'),
        array('countryCode' => 'BB', 'code' => '1246', 'name' => 'Barbados (+1246)'),
        array('countryCode' => 'BY', 'code' => '375', 'name' => 'Belarus (+375)'),
        array('countryCode' => 'BE', 'code' => '32', 'name' => 'Belgium (+32)'),
        array('countryCode' => 'BZ', 'code' => '501', 'name' => 'Belize (+501)'),
        array('countryCode' => 'BJ', 'code' => '229', 'name' => 'Benin (+229)'),
        array('countryCode' => 'BM', 'code' => '1441', 'name' => 'Bermuda (+1441)'),
        array('countryCode' => 'BT', 'code' => '975', 'name' => 'Bhutan (+975)'),
        array('countryCode' => 'BO', 'code' => '591', 'name' => 'Bolivia (+591)'),
        array('countryCode' => 'BA', 'code' => '387', 'name' => 'Bosnia Herzegovina (+387)'),
        array('countryCode' => 'BW', 'code' => '267', 'name' => 'Botswana (+267)'),
        array('countryCode' => 'BR', 'code' => '55', 'name' => 'Brazil (+55)'),
        array('countryCode' => 'BN', 'code' => '673', 'name' => 'Brunei (+673)'),
        array('countryCode' => 'BG', 'code' => '359', 'name' => 'Bulgaria (+359)'),
        array('countryCode' => 'BF', 'code' => '226', 'name' => 'Burkina Faso (+226)'),
        array('countryCode' => 'BI', 'code' => '257', 'name' => 'Burundi (+257)'),
        array('countryCode' => 'KH', 'code' => '855', 'name' => 'Cambodia (+855)'),
        array('countryCode' => 'CM', 'code' => '237', 'name' => 'Cameroon (+237)'),
        array('countryCode' => 'CA', 'code' => '1', 'name' => 'Canada (+1)'),
        array('countryCode' => 'CV', 'code' => '238', 'name' => 'Cape Verde Islands (+238)'),
        array('countryCode' => 'KY', 'code' => '1345', 'name' => 'Cayman Islands (+1345)'),
        array('countryCode' => 'CF', 'code' => '236', 'name' => 'Central African Republic (+236)'),
        array('countryCode' => 'CL', 'code' => '56', 'name' => 'Chile (+56)'),
        array('countryCode' => 'CN', 'code' => '86', 'name' => 'China (+86)'),
        array('countryCode' => 'CO', 'code' => '57', 'name' => 'Colombia (+57)'),
        array('countryCode' => 'KM', 'code' => '269', 'name' => 'Comoros (+269)'),
        array('countryCode' => 'CG', 'code' => '242', 'name' => 'Congo (+242)'),
        array('countryCode' => 'CK', 'code' => '682', 'name' => 'Cook Islands (+682)'),
        array('countryCode' => 'CR', 'code' => '506', 'name' => 'Costa Rica (+506)'),
        array('countryCode' => 'HR', 'code' => '385', 'name' => 'Croatia (+385)'),
        array('countryCode' => 'CU', 'code' => '53', 'name' => 'Cuba (+53)'),
        array('countryCode' => 'CY', 'code' => '90392', 'name' => 'Cyprus North (+90392)'),
        array('countryCode' => 'CY', 'code' => '357', 'name' => 'Cyprus South (+357)'),
        array('countryCode' => 'CZ', 'code' => '42', 'name' => 'Czech Republic (+42)'),
        array('countryCode' => 'DK', 'code' => '45', 'name' => 'Denmark (+45)'),
        array('countryCode' => 'DJ', 'code' => '253', 'name' => 'Djibouti (+253)'),
        array('countryCode' => 'DM', 'code' => '1809', 'name' => 'Dominica (+1809)'),
        array('countryCode' => 'DO', 'code' => '1809', 'name' => 'Dominican Republic (+1809)'),
        array('countryCode' => 'EC', 'code' => '593', 'name' => 'Ecuador (+593)'),
        array('countryCode' => 'EG', 'code' => '20', 'name' => 'Egypt (+20)'),
        array('countryCode' => 'SV', 'code' => '503', 'name' => 'El Salvador (+503)'),
        array('countryCode' => 'GQ', 'code' => '240', 'name' => 'Equatorial Guinea (+240)'),
        array('countryCode' => 'ER', 'code' => '291', 'name' => 'Eritrea (+291)'),
        array('countryCode' => 'EE', 'code' => '372', 'name' => 'Estonia (+372)'),
        array('countryCode' => 'ET', 'code' => '251', 'name' => 'Ethiopia (+251)'),
        array('countryCode' => 'FK', 'code' => '500', 'name' => 'Falkland Islands (+500)'),
        array('countryCode' => 'FO', 'code' => '298', 'name' => 'Faroe Islands (+298)'),
        array('countryCode' => 'FJ', 'code' => '679', 'name' => 'Fiji (+679)'),
        array('countryCode' => 'FI', 'code' => '358', 'name' => 'Finland (+358)'),
        array('countryCode' => 'FR', 'code' => '33', 'name' => 'France (+33)'),
        array('countryCode' => 'GF', 'code' => '594', 'name' => 'French Guiana (+594)'),
        array('countryCode' => 'PF', 'code' => '689', 'name' => 'French Polynesia (+689)'),
        array('countryCode' => 'GA', 'code' => '241', 'name' => 'Gabon (+241)'),
        array('countryCode' => 'GM', 'code' => '220', 'name' => 'Gambia (+220)'),
        array('countryCode' => 'GE', 'code' => '7880', 'name' => 'Georgia (+7880)'),
        array('countryCode' => 'DE', 'code' => '49', 'name' => 'Germany (+49)'),
        array('countryCode' => 'GH', 'code' => '233', 'name' => 'Ghana (+233)'),
        array('countryCode' => 'GI', 'code' => '350', 'name' => 'Gibraltar (+350)'),
        array('countryCode' => 'GR', 'code' => '30', 'name' => 'Greece (+30)'),
        array('countryCode' => 'GL', 'code' => '299', 'name' => 'Greenland (+299)'),
        array('countryCode' => 'GD', 'code' => '1473', 'name' => 'Grenada (+1473)'),
        array('countryCode' => 'GP', 'code' => '590', 'name' => 'Guadeloupe (+590)'),
        array('countryCode' => 'GU', 'code' => '671', 'name' => 'Guam (+671)'),
        array('countryCode' => 'GT', 'code' => '502', 'name' => 'Guatemala (+502)'),
        array('countryCode' => 'GN', 'code' => '224', 'name' => 'Guinea (+224)'),
        array('countryCode' => 'GW', 'code' => '245', 'name' => 'Guinea - Bissau (+245)'),
        array('countryCode' => 'GY', 'code' => '592', 'name' => 'Guyana (+592)'),
        array('countryCode' => 'HT', 'code' => '509', 'name' => 'Haiti (+509)'),
        array('countryCode' => 'HN', 'code' => '504', 'name' => 'Honduras (+504)'),
        array('countryCode' => 'HK', 'code' => '852', 'name' => 'Hong Kong (+852)'),
        array('countryCode' => 'HU', 'code' => '36', 'name' => 'Hungary (+36)'),
        array('countryCode' => 'IS', 'code' => '354', 'name' => 'Iceland (+354)'),
        array('countryCode' => 'IN', 'code' => '91', 'name' => 'India (+91)'),
        array('countryCode' => 'ID', 'code' => '62', 'name' => 'Indonesia (+62)'),
        array('countryCode' => 'IR', 'code' => '98', 'name' => 'Iran (+98)'),
        array('countryCode' => 'IQ', 'code' => '964', 'name' => 'Iraq (+964)'),
        array('countryCode' => 'IE', 'code' => '353', 'name' => 'Ireland (+353)'),
        array('countryCode' => 'IL', 'code' => '972', 'name' => 'Israel (+972)'),
        array('countryCode' => 'IT', 'code' => '39', 'name' => 'Italy (+39)'),
        array('countryCode' => 'JM', 'code' => '1876', 'name' => 'Jamaica (+1876)'),
        array('countryCode' => 'JP', 'code' => '81', 'name' => 'Japan (+81)'),
        array('countryCode' => 'JO', 'code' => '962', 'name' => 'Jordan (+962)'),
        array('countryCode' => 'KZ', 'code' => '7', 'name' => 'Kazakhstan (+7)'),
        array('countryCode' => 'KE', 'code' => '254', 'name' => 'Kenya (+254)'),
        array('countryCode' => 'KI', 'code' => '686', 'name' => 'Kiribati (+686)'),
        array('countryCode' => 'KP', 'code' => '850', 'name' => 'Korea North (+850)'),
        array('countryCode' => 'KR', 'code' => '82', 'name' => 'Korea South (+82)'),
        array('countryCode' => 'KW', 'code' => '965', 'name' => 'Kuwait (+965)'),
        array('countryCode' => 'KG', 'code' => '996', 'name' => 'Kyrgyzstan (+996)'),
        array('countryCode' => 'LA', 'code' => '856', 'name' => 'Laos (+856)'),
        array('countryCode' => 'LV', 'code' => '371', 'name' => 'Latvia (+371)'),
        array('countryCode' => 'LB', 'code' => '961', 'name' => 'Lebanon (+961)'),
        array('countryCode' => 'LS', 'code' => '266', 'name' => 'Lesotho (+266)'),
        array('countryCode' => 'LR', 'code' => '231', 'name' => 'Liberia (+231)'),
        array('countryCode' => 'LY', 'code' => '218', 'name' => 'Libya (+218)'),
        array('countryCode' => 'LI', 'code' => '417', 'name' => 'Liechtenstein (+417)'),
        array('countryCode' => 'LT', 'code' => '370', 'name' => 'Lithuania (+370)'),
        array('countryCode' => 'LU', 'code' => '352', 'name' => 'Luxembourg (+352)'),
        array('countryCode' => 'MO', 'code' => '853', 'name' => 'Macao (+853)'),
        array('countryCode' => 'MK', 'code' => '389', 'name' => 'Macedonia (+389)'),
        array('countryCode' => 'MG', 'code' => '261', 'name' => 'Madagascar (+261)'),
        array('countryCode' => 'MW', 'code' => '265', 'name' => 'Malawi (+265)'),
        array('countryCode' => 'MY', 'code' => '60', 'name' => 'Malaysia (+60)'),
        array('countryCode' => 'MV', 'code' => '960', 'name' => 'Maldives (+960)'),
        array('countryCode' => 'ML', 'code' => '223', 'name' => 'Mali (+223)'),
        array('countryCode' => 'MT', 'code' => '356', 'name' => 'Malta (+356)'),
        array('countryCode' => 'MH', 'code' => '692', 'name' => 'Marshall Islands (+692)'),
        array('countryCode' => 'MQ', 'code' => '596', 'name' => 'Martinique (+596)'),
        array('countryCode' => 'MR', 'code' => '222', 'name' => 'Mauritania (+222)'),
        array('countryCode' => 'YT', 'code' => '269', 'name' => 'Mayotte (+269)'),
        array('countryCode' => 'MX', 'code' => '52', 'name' => 'Mexico (+52)'),
        array('countryCode' => 'FM', 'code' => '691', 'name' => 'Micronesia (+691)'),
        array('countryCode' => 'MD', 'code' => '373', 'name' => 'Moldova (+373)'),
        array('countryCode' => 'MC', 'code' => '377', 'name' => 'Monaco (+377)'),
        array('countryCode' => 'MN', 'code' => '976', 'name' => 'Mongolia (+976)'),
        array('countryCode' => 'MS', 'code' => '1664', 'name' => 'Montserrat (+1664)'),
        array('countryCode' => 'MA', 'code' => '212', 'name' => 'Morocco (+212)'),
        array('countryCode' => 'MZ', 'code' => '258', 'name' => 'Mozambique (+258)'),
        array('countryCode' => 'MN', 'code' => '95', 'name' => 'Myanmar (+95)'),
        array('countryCode' => 'NA', 'code' => '264', 'name' => 'Namibia (+264)'),
        array('countryCode' => 'NR', 'code' => '674', 'name' => 'Nauru (+674)'),
        array('countryCode' => 'NP', 'code' => '977', 'name' => 'Nepal (+977)'),
        array('countryCode' => 'NL', 'code' => '31', 'name' => 'Netherlands (+31)'),
        array('countryCode' => 'NC', 'code' => '687', 'name' => 'New Caledonia (+687)'),
        array('countryCode' => 'NZ', 'code' => '64', 'name' => 'New Zealand (+64)'),
        array('countryCode' => 'NI', 'code' => '505', 'name' => 'Nicaragua (+505)'),
        array('countryCode' => 'NE', 'code' => '227', 'name' => 'Niger (+227)'),
        array('countryCode' => 'NG', 'code' => '234', 'name' => 'Nigeria (+234)'),
        array('countryCode' => 'NU', 'code' => '683', 'name' => 'Niue (+683)'),
        array('countryCode' => 'NF', 'code' => '672', 'name' => 'Norfolk Islands (+672)'),
        array('countryCode' => 'NP', 'code' => '670', 'name' => 'Northern Marianas (+670)'),
        array('countryCode' => 'NO', 'code' => '47', 'name' => 'Norway (+47)'),
        array('countryCode' => 'OM', 'code' => '968', 'name' => 'Oman (+968)'),
        array('countryCode' => 'PW', 'code' => '680', 'name' => 'Palau (+680)'),
        array('countryCode' => 'PA', 'code' => '507', 'name' => 'Panama (+507)'),
        array('countryCode' => 'PG', 'code' => '675', 'name' => 'Papua New Guinea (+675)'),
        array('countryCode' => 'PY', 'code' => '595', 'name' => 'Paraguay (+595)'),
        array('countryCode' => 'PE', 'code' => '51', 'name' => 'Peru (+51)'),
        array('countryCode' => 'PH', 'code' => '63', 'name' => 'Philippines (+63)'),
        array('countryCode' => 'PL', 'code' => '48', 'name' => 'Poland (+48)'),
        array('countryCode' => 'PT', 'code' => '351', 'name' => 'Portugal (+351)'),
        array('countryCode' => 'PR', 'code' => '1787', 'name' => 'Puerto Rico (+1787)'),
        array('countryCode' => 'QA', 'code' => '974', 'name' => 'Qatar (+974)'),
        array('countryCode' => 'RE', 'code' => '262', 'name' => 'Reunion (+262)'),
        array('countryCode' => 'RO', 'code' => '40', 'name' => 'Romania (+40)'),
        array('countryCode' => 'RU', 'code' => '7', 'name' => 'Russia (+7)'),
        array('countryCode' => 'RW', 'code' => '250', 'name' => 'Rwanda (+250)'),
        array('countryCode' => 'SM', 'code' => '378', 'name' => 'San Marino (+378)'),
        array('countryCode' => 'ST', 'code' => '239', 'name' => 'Sao Tome &amp; Principe (+239)'),
        array('countryCode' => 'SA', 'code' => '966', 'name' => 'Saudi Arabia (+966)'),
        array('countryCode' => 'SN', 'code' => '221', 'name' => 'Senegal (+221)'),
        array('countryCode' => 'CS', 'code' => '381', 'name' => 'Serbia (+381)'),
        array('countryCode' => 'SC', 'code' => '248', 'name' => 'Seychelles (+248)'),
        array('countryCode' => 'SL', 'code' => '232', 'name' => 'Sierra Leone (+232)'),
        array('countryCode' => 'SG', 'code' => '65', 'name' => 'Singapore (+65)'),
        array('countryCode' => 'SK', 'code' => '421', 'name' => 'Slovak Republic (+421)'),
        array('countryCode' => 'SI', 'code' => '386', 'name' => 'Slovenia (+386)'),
        array('countryCode' => 'SB', 'code' => '677', 'name' => 'Solomon Islands (+677)'),
        array('countryCode' => 'SO', 'code' => '252', 'name' => 'Somalia (+252)'),
        array('countryCode' => 'ZA', 'code' => '27', 'name' => 'South Africa (+27)'),
        array('countryCode' => 'ES', 'code' => '34', 'name' => 'Spain (+34)'),
        array('countryCode' => 'LK', 'code' => '94', 'name' => 'Sri Lanka (+94)'),
        array('countryCode' => 'SH', 'code' => '290', 'name' => 'St. Helena (+290)'),
        array('countryCode' => 'KN', 'code' => '1869', 'name' => 'St. Kitts (+1869)'),
        array('countryCode' => 'SC', 'code' => '1758', 'name' => 'St. Lucia (+1758)'),
        array('countryCode' => 'SD', 'code' => '249', 'name' => 'Sudan (+249)'),
        array('countryCode' => 'SR', 'code' => '597', 'name' => 'Suriname (+597)'),
        array('countryCode' => 'SZ', 'code' => '268', 'name' => 'Swaziland (+268)'),
        array('countryCode' => 'SE', 'code' => '46', 'name' => 'Sweden (+46)'),
        array('countryCode' => 'CH', 'code' => '41', 'name' => 'Switzerland (+41)'),
        array('countryCode' => 'SI', 'code' => '963', 'name' => 'Syria (+963)'),
        array('countryCode' => 'TW', 'code' => '886', 'name' => 'Taiwan (+886)'),
        array('countryCode' => 'TJ', 'code' => '7', 'name' => 'Tajikstan (+7)'),
        array('countryCode' => 'TH', 'code' => '66', 'name' => 'Thailand (+66)'),
        array('countryCode' => 'TG', 'code' => '228', 'name' => 'Togo (+228)'),
        array('countryCode' => 'TO', 'code' => '676', 'name' => 'Tonga (+676)'),
        array('countryCode' => 'TT', 'code' => '1868', 'name' => 'Trinidad &amp; Tobago (+1868)'),
        array('countryCode' => 'TN', 'code' => '216', 'name' => 'Tunisia (+216)'),
        array('countryCode' => 'TR', 'code' => '90', 'name' => 'Turkey (+90)'),
        array('countryCode' => 'TM', 'code' => '7', 'name' => 'Turkmenistan (+7)'),
        array('countryCode' => 'TM', 'code' => '993', 'name' => 'Turkmenistan (+993)'),
        array('countryCode' => 'TC', 'code' => '1649', 'name' => 'Turks &amp; Caicos Islands (+1649)'),
        array('countryCode' => 'TV', 'code' => '688', 'name' => 'Tuvalu (+688)'),
        array('countryCode' => 'UG', 'code' => '256', 'name' => 'Uganda (+256)'),
        array('countryCode' => 'UA', 'code' => '380', 'name' => 'Ukraine (+380)'),
        array('countryCode' => 'AE', 'code' => '971', 'name' => 'United Arab Emirates (+971)'),
        array('countryCode' => 'UY', 'code' => '598', 'name' => 'Uruguay (+598)'),
        array('countryCode' => 'UZ', 'code' => '7', 'name' => 'Uzbekistan (+7)'),
        array('countryCode' => 'VU', 'code' => '678', 'name' => 'Vanuatu (+678)'),
        array('countryCode' => 'VA', 'code' => '379', 'name' => 'Vatican City (+379)'),
        array('countryCode' => 'VE', 'code' => '58', 'name' => 'Venezuela (+58)'),
        array('countryCode' => 'VN', 'code' => '84', 'name' => 'Vietnam (+84)'),
        array('countryCode' => 'VG', 'code' => '84', 'name' => 'Virgin Islands - British (+1284)'),
        array('countryCode' => 'VI', 'code' => '84', 'name' => 'Virgin Islands - US (+1340)'),
        array('countryCode' => 'WF', 'code' => '681', 'name' => 'Wallis &amp; Futuna (+681)'),
        array('countryCode' => 'YE', 'code' => '969', 'name' => 'Yemen (North)(+969)'),
        array('countryCode' => 'YE', 'code' => '967', 'name' => 'Yemen (South)(+967)'),
        array('countryCode' => 'ZM', 'code' => '260', 'name' => 'Zambia (+260)'),
        array('countryCode' => 'ZW', 'code' => '263', 'name' => 'Zimbabwe (+263)'),
    );
    return $codes;
}




if (!function_exists('pre')) {

    function pre($args) {
        echo '<prE>';
        print_r($args);
        echo '</prE>';
    }

}


function timeago($date) {
    $timestamp = strtotime($date);

    $strTime = array("second", "minute", "hour", "day", "month", "year");
    $length = array("60", "60", "24", "30", "12", "10");

    $currentTime = time();
    if ($currentTime >= $timestamp) {
        $diff = time() - $timestamp;
        for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
            $diff = $diff / $length[$i];
        }

        $diff = round($diff);
        return $diff . " " . $strTime[$i] . "(s) ago ";
    }
}

function getCountryName($id) {
    if ($id > 0) {
        $country = DB::table("countries")->where("id", $id)->get();
        if ($country) {
            return $country[0]->name;
        }
    }
    return 'unnone';
}

function getStateName($id) {
    $regions = DB::table("regions")->where("id", $id)->get();
    if (!empty($regions) && $id != 'Select State') {
        return $regions[0]->name;
    }
    return '';
}

function getCityName($id) {
    // $cities = DB::table("cities")->where("id", $id)->get();
    // if ($cities && $id != 'Select City') {
    //     return $cities[0]->name;
    // }
    return $id;
}


function getUserName($id) {
    $uc = DB::table("users")->where("id", $id)->first();
    return $uc;
}

function getusercount() {
    $data = Auth::User();
    $firm_id = $data->firm_id;
    $uc = DB::table("users")->where("firm_id", $firm_id)->count();
    return $uc;
}

function get_user_meta($id, $key = '', $flag = 0) {
    $uc = array();
    $uc = DB::table("usermeta")->where("user_id", $id)->where("meta_key", $key)->get();
    if (count($uc) && $flag == 0) {
        return $uc[0]->meta_value;
    } else {
        if($uc == '[]') {
            return '';
        }
        return $uc;
    }
}

function update_user_meta($user_id, $key, $value = '', $flag = 0) {
    $uc = DB::table("usermeta")->where("user_id", $user_id)->where("meta_key", $key)->get();
    if ($flag == 0) {
        if (count($uc)) {
            DB::table("usermeta")->where(["user_id" => $user_id, "meta_key" => $key])->update(["meta_value" => $value]);
        } else {
            DB::table("usermeta")->insert(["user_id" => $user_id, "meta_key" => $key, "meta_value" => $value]);
        }
    } else {
        DB::table("usermeta")->insert(["user_id" => $user_id, "meta_key" => $key, "meta_value" => $value]);
    }
}



function get_local_time() {

    // $ip = file_get_contents("http://ipecho.net/plain");
    $ip = \request()->ip();
    $url = 'http://ip-api.com/json/' . $ip;
    $tz = file_get_contents($url);
    // $tz = json_decode($tz,true)['timezone'];

    return $tz;
}

function setTImezone($timezone = 'America/New_York') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    } 
    if(isset($_SESSION['UserTimeZone']) && !empty($_SESSION['UserTimeZone'])) {
        $timezone = $_SESSION['UserTimeZone'];
    }
    //Config::set('app.timezone', $timezone);
    config(['app.timezone' => $timezone]);
    date_default_timezone_set($timezone);
}

function getTimeZoneDropdown($timezone= '') { ?>
    <select placeholder="Choose a timezone..." class="timezonedropdown" name="usertimezone" data-live-search="true">
        <option value=""  <?php if($timezone == '') { echo 'selected="selected"'; } ?>data-select2-id="10">Select Timezone</option>
        <option value="America/New_York" <?php if($timezone == 'America/New_York') { echo 'selected="selected"'; } ?> data-select2-id="162">America/New_York - UTC/GMT -05:00</option>
        <option value="America/Chicago" <?php if($timezone == 'America/Chicago') { echo 'selected="selected"'; } ?> data-select2-id="98">America/Chicago - UTC/GMT -06:00</option>
        <option value="America/Denver" <?php if($timezone == 'America/Denver') { echo 'selected="selected"'; } ?> data-select2-id="107">America/Denver - UTC/GMT -07:00</option>
        <option value="America/Phoenix" <?php if($timezone == 'America/Phoenix') { echo 'selected="selected"'; } ?> data-select2-id="173">America/Phoenix - UTC/GMT -07:00</option>
        <option value="America/Los_Angeles" <?php if($timezone == 'America/Los_Angeles') { echo 'selected="selected"'; } ?> data-select2-id="2">America/Los_Angeles - UTC/GMT -08:00</option>
        <option value="America/Anchorage" <?php if($timezone == 'America/Anchorage') { echo 'selected="selected"'; } ?> data-select2-id="64">America/Anchorage - UTC/GMT -09:00</option>
        <option value="America/Adak" <?php if($timezone == 'America/Adak') { echo 'selected="selected"'; } ?> data-select2-id="63">America/Adak - UTC/GMT -10:00</option>
        <option value="Pacific/Honolulu" <?php if($timezone == 'Pacific/Honolulu') { echo 'selected="selected"'; } ?> data-select2-id="412">Pacific/Honolulu - UTC/GMT -10:00</option>
        <!-- <option value="Africa/Abidjan" <?php if($timezone == 'Africa/Abidjan') { echo 'selected="selected"'; } ?> data-select2-id="11">Africa/Abidjan - UTC/GMT +00:00</option>

        <option value="Africa/Accra" <?php if($timezone == 'Africa/Accra') { echo 'selected="selected"'; } ?> data-select2-id="12">Africa/Accra - UTC/GMT +00:00</option>

        <option value="Africa/Addis_Ababa" <?php if($timezone == 'Africa/Addis_Ababa') { echo 'selected="selected"'; } ?> data-select2-id="13">Africa/Addis_Ababa - UTC/GMT +03:00</option>

        <option value="Africa/Algiers" <?php if($timezone == 'Africa/Algiers') { echo 'selected="selected"'; } ?> data-select2-id="14">Africa/Algiers - UTC/GMT +01:00</option>

        <option value="Africa/Asmara" <?php if($timezone == 'Africa/Asmara') { echo 'selected="selected"'; } ?> data-select2-id="15">Africa/Asmara - UTC/GMT +03:00</option>

        <option value="Africa/Bamako" <?php if($timezone == 'Africa/Bamako') { echo 'selected="selected"'; } ?> data-select2-id="16">Africa/Bamako - UTC/GMT +00:00</option>

        <option value="Africa/Bangui" <?php if($timezone == 'Africa/Bangui') { echo 'selected="selected"'; } ?> data-select2-id="17">Africa/Bangui - UTC/GMT +01:00</option>

        <option value="Africa/Banjul" <?php if($timezone == 'Africa/Banjul') { echo 'selected="selected"'; } ?> data-select2-id="18">Africa/Banjul - UTC/GMT +00:00</option>

        <option value="Africa/Bissau" <?php if($timezone == 'Africa/Bissau') { echo 'selected="selected"'; } ?> data-select2-id="19">Africa/Bissau - UTC/GMT +00:00</option>

        <option value="Africa/Blantyre" <?php if($timezone == 'Africa/Blantyre') { echo 'selected="selected"'; } ?> data-select2-id="20">Africa/Blantyre - UTC/GMT +02:00</option>

        <option value="Africa/Brazzaville" <?php if($timezone == 'Africa/Brazzaville') { echo 'selected="selected"'; } ?> data-select2-id="21">Africa/Brazzaville - UTC/GMT +01:00</option>

        <option value="Africa/Bujumbura" <?php if($timezone == 'Africa/Bujumbura') { echo 'selected="selected"'; } ?> data-select2-id="22">Africa/Bujumbura - UTC/GMT +02:00</option>

        <option value="Africa/Cairo" <?php if($timezone == 'Africa/Cairo') { echo 'selected="selected"'; } ?> data-select2-id="23">Africa/Cairo - UTC/GMT +02:00</option>

        <option value="Africa/Casablanca" <?php if($timezone == 'Africa/Casablanca') { echo 'selected="selected"'; } ?> data-select2-id="24">Africa/Casablanca - UTC/GMT +01:00</option>

        <option value="Africa/Ceuta" <?php if($timezone == 'Africa/Ceuta') { echo 'selected="selected"'; } ?> data-select2-id="25">Africa/Ceuta - UTC/GMT +01:00</option>

        <option value="Africa/Conakry" <?php if($timezone == 'Africa/Conakry') { echo 'selected="selected"'; } ?> data-select2-id="26">Africa/Conakry - UTC/GMT +00:00</option>

        <option value="Africa/Dakar" <?php if($timezone == 'Africa/Dakar') { echo 'selected="selected"'; } ?> data-select2-id="27">Africa/Dakar - UTC/GMT +00:00</option>

        <option value="Africa/Dar_es_Salaam" <?php if($timezone == 'Africa/Dar_es_Salaam') { echo 'selected="selected"'; } ?> data-select2-id="28">Africa/Dar_es_Salaam - UTC/GMT +03:00</option>

        <option value="Africa/Djibouti" <?php if($timezone == 'Africa/Djibouti') { echo 'selected="selected"'; } ?> data-select2-id="29">Africa/Djibouti - UTC/GMT +03:00</option>

        <option value="Africa/Douala" <?php if($timezone == 'Africa/Douala') { echo 'selected="selected"'; } ?> data-select2-id="30">Africa/Douala - UTC/GMT +01:00</option>

        <option value="Africa/El_Aaiun" <?php if($timezone == 'Africa/El_Aaiun') { echo 'selected="selected"'; } ?> data-select2-id="31">Africa/El_Aaiun - UTC/GMT +01:00</option>

        <option value="Africa/Freetown" <?php if($timezone == 'Africa/Freetown') { echo 'selected="selected"'; } ?> data-select2-id="32">Africa/Freetown - UTC/GMT +00:00</option>

        <option value="Africa/Gaborone" <?php if($timezone == 'Africa/Gaborone') { echo 'selected="selected"'; } ?> data-select2-id="33">Africa/Gaborone - UTC/GMT +02:00</option>

        <option value="Africa/Harare" <?php if($timezone == 'Africa/Harare') { echo 'selected="selected"'; } ?> data-select2-id="34">Africa/Harare - UTC/GMT +02:00</option>

        <option value="Africa/Johannesburg" <?php if($timezone == 'Africa/Johannesburg') { echo 'selected="selected"'; } ?> data-select2-id="35">Africa/Johannesburg - UTC/GMT +02:00</option>

        <option value="Africa/Juba" <?php if($timezone == 'Africa/Juba') { echo 'selected="selected"'; } ?> data-select2-id="36">Africa/Juba - UTC/GMT +03:00</option>

        <option value="Africa/Kampala" <?php if($timezone == 'Africa/Kampala') { echo 'selected="selected"'; } ?> data-select2-id="37">Africa/Kampala - UTC/GMT +03:00</option>

        <option value="Africa/Khartoum" <?php if($timezone == 'Africa/Khartoum') { echo 'selected="selected"'; } ?> data-select2-id="38">Africa/Khartoum - UTC/GMT +02:00</option>

        <option value="Africa/Kigali" <?php if($timezone == 'Africa/Kigali') { echo 'selected="selected"'; } ?> data-select2-id="39">Africa/Kigali - UTC/GMT +02:00</option>

        <option value="Africa/Kinshasa" <?php if($timezone == 'Africa/Kinshasa') { echo 'selected="selected"'; } ?> data-select2-id="40">Africa/Kinshasa - UTC/GMT +01:00</option>

        <option value="Africa/Lagos" <?php if($timezone == 'Africa/Lagos') { echo 'selected="selected"'; } ?> data-select2-id="41">Africa/Lagos - UTC/GMT +01:00</option>

        <option value="Africa/Libreville" <?php if($timezone == 'Africa/Libreville') { echo 'selected="selected"'; } ?> data-select2-id="42">Africa/Libreville - UTC/GMT +01:00</option>

        <option value="Africa/Lome" <?php if($timezone == 'Africa/Lome') { echo 'selected="selected"'; } ?> data-select2-id="43">Africa/Lome - UTC/GMT +00:00</option>

        <option value="Africa/Luanda" <?php if($timezone == 'Africa/Luanda') { echo 'selected="selected"'; } ?> data-select2-id="44">Africa/Luanda - UTC/GMT +01:00</option>

        <option value="Africa/Lubumbashi" <?php if($timezone == 'Africa/Lubumbashi') { echo 'selected="selected"'; } ?> data-select2-id="45">Africa/Lubumbashi - UTC/GMT +02:00</option>

        <option value="Africa/Lusaka" <?php if($timezone == 'Africa/Lusaka') { echo 'selected="selected"'; } ?> data-select2-id="46">Africa/Lusaka - UTC/GMT +02:00</option>

        <option value="Africa/Malabo" <?php if($timezone == 'Africa/Malabo') { echo 'selected="selected"'; } ?> data-select2-id="47">Africa/Malabo - UTC/GMT +01:00</option>

        <option value="Africa/Maputo" <?php if($timezone == 'Africa/Maputo') { echo 'selected="selected"'; } ?> data-select2-id="48">Africa/Maputo - UTC/GMT +02:00</option>

        <option value="Africa/Maseru" <?php if($timezone == 'Africa/Maseru') { echo 'selected="selected"'; } ?> data-select2-id="49">Africa/Maseru - UTC/GMT +02:00</option>

        <option value="Africa/Mbabane" <?php if($timezone == 'Africa/Mbabane') { echo 'selected="selected"'; } ?> data-select2-id="50">Africa/Mbabane - UTC/GMT +02:00</option>

        <option value="Africa/Mogadishu" <?php if($timezone == 'Africa/Mogadishu') { echo 'selected="selected"'; } ?> data-select2-id="51">Africa/Mogadishu - UTC/GMT +03:00</option>

        <option value="Africa/Monrovia" <?php if($timezone == 'Africa/Monrovia') { echo 'selected="selected"'; } ?> data-select2-id="52">Africa/Monrovia - UTC/GMT +00:00</option>

        <option value="Africa/Nairobi" <?php if($timezone == 'Africa/Nairobi') { echo 'selected="selected"'; } ?> data-select2-id="53">Africa/Nairobi - UTC/GMT +03:00</option>

        <option value="Africa/Ndjamena" <?php if($timezone == 'Africa/Ndjamena') { echo 'selected="selected"'; } ?> data-select2-id="54">Africa/Ndjamena - UTC/GMT +01:00</option>

        <option value="Africa/Niamey" <?php if($timezone == 'Africa/Niamey') { echo 'selected="selected"'; } ?> data-select2-id="55">Africa/Niamey - UTC/GMT +01:00</option>

        <option value="Africa/Nouakchott" <?php if($timezone == 'Africa/Nouakchott') { echo 'selected="selected"'; } ?> data-select2-id="56">Africa/Nouakchott - UTC/GMT +00:00</option>

        <option value="Africa/Ouagadougou" <?php if($timezone == 'Africa/Ouagadougou') { echo 'selected="selected"'; } ?> data-select2-id="57">Africa/Ouagadougou - UTC/GMT +00:00</option>

        <option value="Africa/Porto-Novo" <?php if($timezone == 'Africa/Porto-Novo') { echo 'selected="selected"'; } ?> data-select2-id="58">Africa/Porto-Novo - UTC/GMT +01:00</option>

        <option value="Africa/Sao_Tome" <?php if($timezone == 'Africa/Sao_Tome') { echo 'selected="selected"'; } ?> data-select2-id="59">Africa/Sao_Tome - UTC/GMT +00:00</option>

        <option value="Africa/Tripoli" <?php if($timezone == 'Africa/Tripoli') { echo 'selected="selected"'; } ?> data-select2-id="60">Africa/Tripoli - UTC/GMT +02:00</option>

        <option value="Africa/Tunis" <?php if($timezone == 'Africa/Tunis') { echo 'selected="selected"'; } ?> data-select2-id="61">Africa/Tunis - UTC/GMT +01:00</option>

        <option value="Africa/Windhoek" <?php if($timezone == 'Africa/Windhoek') { echo 'selected="selected"'; } ?> data-select2-id="62">Africa/Windhoek - UTC/GMT +02:00</option>

        <option value="America/Adak" <?php if($timezone == 'America/Adak') { echo 'selected="selected"'; } ?> data-select2-id="63">America/Adak - UTC/GMT -10:00</option>

        <option value="America/Anchorage" <?php if($timezone == 'America/Anchorage') { echo 'selected="selected"'; } ?> data-select2-id="64">America/Anchorage - UTC/GMT -09:00</option>

        <option value="America/Anguilla" <?php if($timezone == 'America/Anguilla') { echo 'selected="selected"'; } ?> data-select2-id="65">America/Anguilla - UTC/GMT -04:00</option>

        <option value="America/Antigua" <?php if($timezone == 'America/Antigua') { echo 'selected="selected"'; } ?> data-select2-id="66">America/Antigua - UTC/GMT -04:00</option>

        <option value="America/Araguaina" <?php if($timezone == 'America/Araguaina') { echo 'selected="selected"'; } ?> data-select2-id="67">America/Araguaina - UTC/GMT -03:00</option>

        <option value="America/Argentina/Buenos_Aires" <?php if($timezone == 'America/Argentina/Buenos_Aires') { echo 'selected="selected"'; } ?> data-select2-id="68">America/Argentina/Buenos_Aires - UTC/GMT -03:00</option>

        <option value="America/Argentina/Catamarca" <?php if($timezone == 'America/Argentina/Catamarca') { echo 'selected="selected"'; } ?> data-select2-id="69">America/Argentina/Catamarca - UTC/GMT -03:00</option>

        <option value="America/Argentina/Cordoba" <?php if($timezone == 'America/Argentina/Cordoba') { echo 'selected="selected"'; } ?> data-select2-id="70">America/Argentina/Cordoba - UTC/GMT -03:00</option>

        <option value="America/Argentina/Jujuy" <?php if($timezone == 'America/Argentina/Jujuy') { echo 'selected="selected"'; } ?> data-select2-id="71">America/Argentina/Jujuy - UTC/GMT -03:00</option>

        <option value="America/Argentina/La_Rioja" <?php if($timezone == 'America/Argentina/La_Rioja') { echo 'selected="selected"'; } ?> data-select2-id="72">America/Argentina/La_Rioja - UTC/GMT -03:00</option>

        <option value="America/Argentina/Mendoza" <?php if($timezone == 'America/Argentina/Mendoza') { echo 'selected="selected"'; } ?> data-select2-id="73">America/Argentina/Mendoza - UTC/GMT -03:00</option>

        <option value="America/Argentina/Rio_Gallegos" <?php if($timezone == 'America/Argentina/Rio_Gallegos') { echo 'selected="selected"'; } ?> data-select2-id="74">America/Argentina/Rio_Gallegos - UTC/GMT -03:00</option>

        <option value="America/Argentina/Salta" <?php if($timezone == 'America/Argentina/Salta') { echo 'selected="selected"'; } ?> data-select2-id="75">America/Argentina/Salta - UTC/GMT -03:00</option>

        <option value="America/Argentina/San_Juan" <?php if($timezone == 'America/Argentina/San_Juan') { echo 'selected="selected"'; } ?> data-select2-id="76">America/Argentina/San_Juan - UTC/GMT -03:00</option>

        <option value="America/Argentina/San_Luis" <?php if($timezone == 'America/Argentina/San_Luis') { echo 'selected="selected"'; } ?> data-select2-id="77">America/Argentina/San_Luis - UTC/GMT -03:00</option>

        <option value="America/Argentina/Tucuman" <?php if($timezone == 'America/Argentina/Tucuman') { echo 'selected="selected"'; } ?> data-select2-id="78">America/Argentina/Tucuman - UTC/GMT -03:00</option>

        <option value="America/Argentina/Ushuaia" <?php if($timezone == 'America/Argentina/Ushuaia') { echo 'selected="selected"'; } ?> data-select2-id="79">America/Argentina/Ushuaia - UTC/GMT -03:00</option>

        <option value="America/Aruba" <?php if($timezone == 'America/Aruba') { echo 'selected="selected"'; } ?> data-select2-id="80">America/Aruba - UTC/GMT -04:00</option>

        <option value="America/Asuncion" <?php if($timezone == 'America/Asuncion') { echo 'selected="selected"'; } ?> data-select2-id="81">America/Asuncion - UTC/GMT -03:00</option>

        <option value="America/Atikokan" <?php if($timezone == 'America/Atikokan') { echo 'selected="selected"'; } ?> data-select2-id="82">America/Atikokan - UTC/GMT -05:00</option>

        <option value="America/Bahia" <?php if($timezone == 'America/Bahia') { echo 'selected="selected"'; } ?> data-select2-id="83">America/Bahia - UTC/GMT -03:00</option>

        <option value="America/Bahia_Banderas" <?php if($timezone == 'America/Bahia_Banderas') { echo 'selected="selected"'; } ?> data-select2-id="84">America/Bahia_Banderas - UTC/GMT -06:00</option>

        <option value="America/Barbados" <?php if($timezone == 'America/Barbados') { echo 'selected="selected"'; } ?> data-select2-id="85">America/Barbados - UTC/GMT -04:00</option>

        <option value="America/Belem" <?php if($timezone == 'America/Belem') { echo 'selected="selected"'; } ?> data-select2-id="86">America/Belem - UTC/GMT -03:00</option>

        <option value="America/Belize" <?php if($timezone == 'America/Belize') { echo 'selected="selected"'; } ?> data-select2-id="87">America/Belize - UTC/GMT -06:00</option>

        <option value="America/Blanc-Sablon" <?php if($timezone == 'America/Blanc-Sablon') { echo 'selected="selected"'; } ?> data-select2-id="88">America/Blanc-Sablon - UTC/GMT -04:00</option>

        <option value="America/Boa_Vista" <?php if($timezone == 'America/Boa_Vista') { echo 'selected="selected"'; } ?> data-select2-id="89">America/Boa_Vista - UTC/GMT -04:00</option>

        <option value="America/Bogota" <?php if($timezone == 'America/Bogota') { echo 'selected="selected"'; } ?> data-select2-id="90">America/Bogota - UTC/GMT -05:00</option>

        <option value="America/Boise" <?php if($timezone == 'America/Boise') { echo 'selected="selected"'; } ?> data-select2-id="91">America/Boise - UTC/GMT -07:00</option>

        <option value="America/Cambridge_Bay" <?php if($timezone == 'America/Cambridge_Bay') { echo 'selected="selected"'; } ?> data-select2-id="92">America/Cambridge_Bay - UTC/GMT -07:00</option>

        <option value="America/Campo_Grande" <?php if($timezone == 'America/Campo_Grande') { echo 'selected="selected"'; } ?> data-select2-id="93">America/Campo_Grande - UTC/GMT -04:00</option>

        <option value="America/Cancun" <?php if($timezone == 'America/Cancun') { echo 'selected="selected"'; } ?> data-select2-id="94">America/Cancun - UTC/GMT -05:00</option>

        <option value="America/Caracas" <?php if($timezone == 'America/Caracas') { echo 'selected="selected"'; } ?> data-select2-id="95">America/Caracas - UTC/GMT -04:00</option>

        <option value="America/Cayenne" <?php if($timezone == 'America/Cayenne') { echo 'selected="selected"'; } ?> data-select2-id="96">America/Cayenne - UTC/GMT -03:00</option>

        <option value="America/Cayman" <?php if($timezone == 'America/Cayman') { echo 'selected="selected"'; } ?> data-select2-id="97">America/Cayman - UTC/GMT -05:00</option>

        <option value="America/Chicago" <?php if($timezone == 'America/Chicago') { echo 'selected="selected"'; } ?> data-select2-id="98">America/Chicago - UTC/GMT -06:00</option>

        <option value="America/Chihuahua" <?php if($timezone == 'America/Chihuahua') { echo 'selected="selected"'; } ?> data-select2-id="99">America/Chihuahua - UTC/GMT -07:00</option>

        <option value="America/Costa_Rica" <?php if($timezone == 'America/Costa_Rica') { echo 'selected="selected"'; } ?> data-select2-id="100">America/Costa_Rica - UTC/GMT -06:00</option>

        <option value="America/Creston" <?php if($timezone == 'America/Creston') { echo 'selected="selected"'; } ?> data-select2-id="101">America/Creston - UTC/GMT -07:00</option>

        <option value="America/Cuiaba" <?php if($timezone == 'America/Cuiaba') { echo 'selected="selected"'; } ?> data-select2-id="102">America/Cuiaba - UTC/GMT -04:00</option>

        <option value="America/Curacao" <?php if($timezone == 'America/Curacao') { echo 'selected="selected"'; } ?> data-select2-id="103">America/Curacao - UTC/GMT -04:00</option>

        <option value="America/Danmarkshavn" <?php if($timezone == 'America/Danmarkshavn') { echo 'selected="selected"'; } ?> data-select2-id="104">America/Danmarkshavn - UTC/GMT +00:00</option>

        <option value="America/Dawson" <?php if($timezone == 'America/Dawson') { echo 'selected="selected"'; } ?> data-select2-id="105">America/Dawson - UTC/GMT -08:00</option>

        <option value="America/Dawson_Creek" <?php if($timezone == 'America/Dawson_Creek') { echo 'selected="selected"'; } ?> data-select2-id="106">America/Dawson_Creek - UTC/GMT -07:00</option>

        <option value="America/Denver" <?php if($timezone == 'America/Denver') { echo 'selected="selected"'; } ?> data-select2-id="107">America/Denver - UTC/GMT -07:00</option>

        <option value="America/Detroit" <?php if($timezone == 'America/Detroit') { echo 'selected="selected"'; } ?> data-select2-id="108">America/Detroit - UTC/GMT -05:00</option>

        <option value="America/Dominica" <?php if($timezone == 'America/Dominica') { echo 'selected="selected"'; } ?> data-select2-id="109">America/Dominica - UTC/GMT -04:00</option>

        <option value="America/Edmonton" <?php if($timezone == 'America/Edmonton') { echo 'selected="selected"'; } ?> data-select2-id="110">America/Edmonton - UTC/GMT -07:00</option>

        <option value="America/Eirunepe" <?php if($timezone == 'America/Eirunepe') { echo 'selected="selected"'; } ?> data-select2-id="111">America/Eirunepe - UTC/GMT -05:00</option>

        <option value="America/El_Salvador" <?php if($timezone == 'America/El_Salvador') { echo 'selected="selected"'; } ?> data-select2-id="112">America/El_Salvador - UTC/GMT -06:00</option>

        <option value="America/Fort_Nelson" <?php if($timezone == 'America/Fort_Nelson') { echo 'selected="selected"'; } ?> data-select2-id="113">America/Fort_Nelson - UTC/GMT -07:00</option>

        <option value="America/Fortaleza" <?php if($timezone == 'America/Fortaleza') { echo 'selected="selected"'; } ?> data-select2-id="114">America/Fortaleza - UTC/GMT -03:00</option>

        <option value="America/Glace_Bay" <?php if($timezone == 'America/Glace_Bay') { echo 'selected="selected"'; } ?> data-select2-id="115">America/Glace_Bay - UTC/GMT -04:00</option>

        <option value="America/Godthab" <?php if($timezone == 'America/Godthab') { echo 'selected="selected"'; } ?> data-select2-id="116">America/Godthab - UTC/GMT -03:00</option>

        <option value="America/Goose_Bay" <?php if($timezone == 'America/Goose_Bay') { echo 'selected="selected"'; } ?> data-select2-id="117">America/Goose_Bay - UTC/GMT -04:00</option>

        <option value="America/Grand_Turk" <?php if($timezone == 'America/Grand_Turk') { echo 'selected="selected"'; } ?> data-select2-id="118">America/Grand_Turk - UTC/GMT -05:00</option>

        <option value="America/Grenada" <?php if($timezone == 'America/Grenada') { echo 'selected="selected"'; } ?> data-select2-id="119">America/Grenada - UTC/GMT -04:00</option>

        <option value="America/Guadeloupe" <?php if($timezone == 'America/Guadeloupe') { echo 'selected="selected"'; } ?> data-select2-id="120">America/Guadeloupe - UTC/GMT -04:00</option>

        <option value="America/Guatemala" <?php if($timezone == 'America/Guatemala') { echo 'selected="selected"'; } ?> data-select2-id="121">America/Guatemala - UTC/GMT -06:00</option>

        <option value="America/Guayaquil" <?php if($timezone == 'America/Guayaquil') { echo 'selected="selected"'; } ?> data-select2-id="122">America/Guayaquil - UTC/GMT -05:00</option>

        <option value="America/Guyana" <?php if($timezone == 'America/Guyana') { echo 'selected="selected"'; } ?> data-select2-id="123">America/Guyana - UTC/GMT -04:00</option>

        <option value="America/Halifax" <?php if($timezone == 'America/Halifax') { echo 'selected="selected"'; } ?> data-select2-id="124">America/Halifax - UTC/GMT -04:00</option>

        <option value="America/Havana" <?php if($timezone == 'America/Havana') { echo 'selected="selected"'; } ?> data-select2-id="125">America/Havana - UTC/GMT -05:00</option>

        <option value="America/Hermosillo" <?php if($timezone == 'America/Hermosillo') { echo 'selected="selected"'; } ?> data-select2-id="126">America/Hermosillo - UTC/GMT -07:00</option>

        <option value="America/Indiana/Indianapolis" <?php if($timezone == 'America/Indiana/Indianapolis') { echo 'selected="selected"'; } ?> data-select2-id="127">America/Indiana/Indianapolis - UTC/GMT -05:00</option>

        <option value="America/Indiana/Knox" <?php if($timezone == 'America/Indiana/Knox') { echo 'selected="selected"'; } ?> data-select2-id="128">America/Indiana/Knox - UTC/GMT -06:00</option>

        <option value="America/Indiana/Marengo" <?php if($timezone == 'America/Indiana/Marengo') { echo 'selected="selected"'; } ?> data-select2-id="129">America/Indiana/Marengo - UTC/GMT -05:00</option>

        <option value="America/Indiana/Petersburg" <?php if($timezone == 'America/Indiana/Petersburg') { echo 'selected="selected"'; } ?> data-select2-id="130">America/Indiana/Petersburg - UTC/GMT -05:00</option>

        <option value="America/Indiana/Tell_City" <?php if($timezone == 'America/Indiana/Tell_City') { echo 'selected="selected"'; } ?> data-select2-id="131">America/Indiana/Tell_City - UTC/GMT -06:00</option>

        <option value="America/Indiana/Vevay" <?php if($timezone == 'America/Indiana/Vevay') { echo 'selected="selected"'; } ?> data-select2-id="132">America/Indiana/Vevay - UTC/GMT -05:00</option>

        <option value="America/Indiana/Vincennes" <?php if($timezone == 'America/Indiana/Vincennes') { echo 'selected="selected"'; } ?> data-select2-id="133">America/Indiana/Vincennes - UTC/GMT -05:00</option>

        <option value="America/Indiana/Winamac" <?php if($timezone == 'America/Indiana/Winamac') { echo 'selected="selected"'; } ?> data-select2-id="134">America/Indiana/Winamac - UTC/GMT -05:00</option>

        <option value="America/Inuvik" <?php if($timezone == 'America/Inuvik') { echo 'selected="selected"'; } ?> data-select2-id="135">America/Inuvik - UTC/GMT -07:00</option>

        <option value="America/Iqaluit" <?php if($timezone == 'America/Iqaluit') { echo 'selected="selected"'; } ?> data-select2-id="136">America/Iqaluit - UTC/GMT -05:00</option>

        <option value="America/Jamaica" <?php if($timezone == 'America/Jamaica') { echo 'selected="selected"'; } ?> data-select2-id="137">America/Jamaica - UTC/GMT -05:00</option>

        <option value="America/Juneau" <?php if($timezone == 'America/Juneau') { echo 'selected="selected"'; } ?> data-select2-id="138">America/Juneau - UTC/GMT -09:00</option>

        <option value="America/Kentucky/Louisville" <?php if($timezone == 'America/Kentucky/Louisville') { echo 'selected="selected"'; } ?> data-select2-id="139">America/Kentucky/Louisville - UTC/GMT -05:00</option>

        <option value="America/Kentucky/Monticello" <?php if($timezone == 'America/Kentucky/Monticello') { echo 'selected="selected"'; } ?> data-select2-id="140">America/Kentucky/Monticello - UTC/GMT -05:00</option>

        <option value="America/Kralendijk" <?php if($timezone == 'America/Kralendijk') { echo 'selected="selected"'; } ?> data-select2-id="141">America/Kralendijk - UTC/GMT -04:00</option>

        <option value="America/La_Paz" <?php if($timezone == 'America/La_Paz') { echo 'selected="selected"'; } ?> data-select2-id="142">America/La_Paz - UTC/GMT -04:00</option>

        <option value="America/Lima" <?php if($timezone == 'America/Lima') { echo 'selected="selected"'; } ?> data-select2-id="143">America/Lima - UTC/GMT -05:00</option>

        <option value="America/Los_Angeles" <?php if($timezone == 'America/Los_Angeles') { echo 'selected="selected"'; } ?> data-select2-id="2">America/Los_Angeles - UTC/GMT -08:00</option>

        <option value="America/Lower_Princes" <?php if($timezone == 'America/Lower_Princes') { echo 'selected="selected"'; } ?> data-select2-id="144">America/Lower_Princes - UTC/GMT -04:00</option>

        <option value="America/Maceio" <?php if($timezone == 'America/Maceio') { echo 'selected="selected"'; } ?> data-select2-id="145">America/Maceio - UTC/GMT -03:00</option>

        <option value="America/Managua" <?php if($timezone == 'America/Managua') { echo 'selected="selected"'; } ?> data-select2-id="146">America/Managua - UTC/GMT -06:00</option>

        <option value="America/Manaus" <?php if($timezone == 'America/Manaus') { echo 'selected="selected"'; } ?> data-select2-id="147">America/Manaus - UTC/GMT -04:00</option>

        <option value="America/Marigot" <?php if($timezone == 'America/Marigot') { echo 'selected="selected"'; } ?> data-select2-id="148">America/Marigot - UTC/GMT -04:00</option>

        <option value="America/Martinique" <?php if($timezone == 'America/Martinique') { echo 'selected="selected"'; } ?> data-select2-id="149">America/Martinique - UTC/GMT -04:00</option>

        <option value="America/Matamoros" <?php if($timezone == 'America/Matamoros') { echo 'selected="selected"'; } ?> data-select2-id="150">America/Matamoros - UTC/GMT -06:00</option>

        <option value="America/Mazatlan" <?php if($timezone == 'America/Mazatlan') { echo 'selected="selected"'; } ?> data-select2-id="151">America/Mazatlan - UTC/GMT -07:00</option>

        <option value="America/Menominee" <?php if($timezone == 'America/Menominee') { echo 'selected="selected"'; } ?> data-select2-id="152">America/Menominee - UTC/GMT -06:00</option>

        <option value="America/Merida" <?php if($timezone == 'America/Merida') { echo 'selected="selected"'; } ?> data-select2-id="153">America/Merida - UTC/GMT -06:00</option>

        <option value="America/Metlakatla" <?php if($timezone == 'America/Metlakatla') { echo 'selected="selected"'; } ?> data-select2-id="154">America/Metlakatla - UTC/GMT -09:00</option>

        <option value="America/Mexico_City" <?php if($timezone == 'America/Mexico_City') { echo 'selected="selected"'; } ?> data-select2-id="155">America/Mexico_City - UTC/GMT -06:00</option>

        <option value="America/Miquelon" <?php if($timezone == 'America/Miquelon') { echo 'selected="selected"'; } ?> data-select2-id="156">America/Miquelon - UTC/GMT -03:00</option>

        <option value="America/Moncton" <?php if($timezone == 'America/Moncton') { echo 'selected="selected"'; } ?> data-select2-id="157">America/Moncton - UTC/GMT -04:00</option>

        <option value="America/Monterrey" <?php if($timezone == 'America/Monterrey') { echo 'selected="selected"'; } ?> data-select2-id="158">America/Monterrey - UTC/GMT -06:00</option>

        <option value="America/Montevideo" <?php if($timezone == 'America/Montevideo') { echo 'selected="selected"'; } ?> data-select2-id="159">America/Montevideo - UTC/GMT -03:00</option>

        <option value="America/Montserrat" <?php if($timezone == 'America/Montserrat') { echo 'selected="selected"'; } ?> data-select2-id="160">America/Montserrat - UTC/GMT -04:00</option>

        <option value="America/Nassau" <?php if($timezone == 'America/Nassau') { echo 'selected="selected"'; } ?> data-select2-id="161">America/Nassau - UTC/GMT -05:00</option>

        <option value="America/New_York" <?php if($timezone == 'America/New_York') { echo 'selected="selected"'; } ?> data-select2-id="162">America/New_York - UTC/GMT -05:00</option>

        <option value="America/Nipigon" <?php if($timezone == 'America/Nipigon') { echo 'selected="selected"'; } ?> data-select2-id="163">America/Nipigon - UTC/GMT -05:00</option>

        <option value="America/Nome" <?php if($timezone == 'America/Nome') { echo 'selected="selected"'; } ?> data-select2-id="164">America/Nome - UTC/GMT -09:00</option>

        <option value="America/Noronha" <?php if($timezone == 'America/Noronha') { echo 'selected="selected"'; } ?> data-select2-id="165">America/Noronha - UTC/GMT -02:00</option>

        <option value="America/North_Dakota/Beulah" <?php if($timezone == 'America/North_Dakota/Beulah') { echo 'selected="selected"'; } ?> data-select2-id="166">America/North_Dakota/Beulah - UTC/GMT -06:00</option>

        <option value="America/North_Dakota/Center" <?php if($timezone == 'America/North_Dakota/Center') { echo 'selected="selected"'; } ?> data-select2-id="167">America/North_Dakota/Center - UTC/GMT -06:00</option>

        <option value="America/North_Dakota/New_Salem" <?php if($timezone == 'America/North_Dakota/New_Salem') { echo 'selected="selected"'; } ?> data-select2-id="168">America/North_Dakota/New_Salem - UTC/GMT -06:00</option>

        <option value="America/Ojinaga" <?php if($timezone == 'America/Ojinaga') { echo 'selected="selected"'; } ?> data-select2-id="169">America/Ojinaga - UTC/GMT -07:00</option>

        <option value="America/Panama" <?php if($timezone == 'America/Panama') { echo 'selected="selected"'; } ?> data-select2-id="170">America/Panama - UTC/GMT -05:00</option>

        <option value="America/Pangnirtung" <?php if($timezone == 'America/Pangnirtung') { echo 'selected="selected"'; } ?> data-select2-id="171">America/Pangnirtung - UTC/GMT -05:00</option>

        <option value="America/Paramaribo" <?php if($timezone == 'America/Paramaribo') { echo 'selected="selected"'; } ?> data-select2-id="172">America/Paramaribo - UTC/GMT -03:00</option>

        <option value="America/Phoenix" <?php if($timezone == 'America/Phoenix') { echo 'selected="selected"'; } ?> data-select2-id="173">America/Phoenix - UTC/GMT -07:00</option>

        <option value="America/Port-au-Prince" <?php if($timezone == 'America/Port-au-Prince') { echo 'selected="selected"'; } ?> data-select2-id="174">America/Port-au-Prince - UTC/GMT -05:00</option>

        <option value="America/Port_of_Spain" <?php if($timezone == 'America/Port_of_Spain') { echo 'selected="selected"'; } ?> data-select2-id="175">America/Port_of_Spain - UTC/GMT -04:00</option>

        <option value="America/Porto_Velho" <?php if($timezone == 'America/Porto_Velho') { echo 'selected="selected"'; } ?> data-select2-id="176">America/Porto_Velho - UTC/GMT -04:00</option>

        <option value="America/Puerto_Rico" <?php if($timezone == 'America/Puerto_Rico') { echo 'selected="selected"'; } ?> data-select2-id="177">America/Puerto_Rico - UTC/GMT -04:00</option>

        <option value="America/Punta_Arenas" <?php if($timezone == 'America/Punta_Arenas') { echo 'selected="selected"'; } ?> data-select2-id="178">America/Punta_Arenas - UTC/GMT -03:00</option>

        <option value="America/Rainy_River" <?php if($timezone == 'America/Rainy_River') { echo 'selected="selected"'; } ?> data-select2-id="179">America/Rainy_River - UTC/GMT -06:00</option>

        <option value="America/Rankin_Inlet" <?php if($timezone == 'America/Rankin_Inlet') { echo 'selected="selected"'; } ?> data-select2-id="180">America/Rankin_Inlet - UTC/GMT -06:00</option>

        <option value="America/Recife" <?php if($timezone == 'America/Recife') { echo 'selected="selected"'; } ?> data-select2-id="181">America/Recife - UTC/GMT -03:00</option>

        <option value="America/Regina" <?php if($timezone == 'America/Regina') { echo 'selected="selected"'; } ?> data-select2-id="182">America/Regina - UTC/GMT -06:00</option>

        <option value="America/Resolute" <?php if($timezone == 'America/Resolute') { echo 'selected="selected"'; } ?> data-select2-id="183">America/Resolute - UTC/GMT -06:00</option>

        <option value="America/Rio_Branco" <?php if($timezone == 'America/Rio_Branco') { echo 'selected="selected"'; } ?> data-select2-id="184">America/Rio_Branco - UTC/GMT -05:00</option>

        <option value="America/Santarem" <?php if($timezone == 'America/Santarem') { echo 'selected="selected"'; } ?> data-select2-id="185">America/Santarem - UTC/GMT -03:00</option>

        <option value="America/Santiago" <?php if($timezone == 'America/Santiago') { echo 'selected="selected"'; } ?> data-select2-id="186">America/Santiago - UTC/GMT -03:00</option>

        <option value="America/Santo_Domingo" <?php if($timezone == 'America/Santo_Domingo') { echo 'selected="selected"'; } ?> data-select2-id="187">America/Santo_Domingo - UTC/GMT -04:00</option>

        <option value="America/Sao_Paulo" <?php if($timezone == 'America/Sao_Paulo') { echo 'selected="selected"'; } ?> data-select2-id="188">America/Sao_Paulo - UTC/GMT -03:00</option>

        <option value="America/Scoresbysund" <?php if($timezone == 'America/Scoresbysund') { echo 'selected="selected"'; } ?> data-select2-id="189">America/Scoresbysund - UTC/GMT -01:00</option>

        <option value="America/Sitka" <?php if($timezone == 'America/Sitka') { echo 'selected="selected"'; } ?> data-select2-id="190">America/Sitka - UTC/GMT -09:00</option>

        <option value="America/St_Barthelemy" <?php if($timezone == 'America/St_Barthelemy') { echo 'selected="selected"'; } ?> data-select2-id="191">America/St_Barthelemy - UTC/GMT -04:00</option>

        <option value="America/St_Johns" <?php if($timezone == 'America/St_Johns') { echo 'selected="selected"'; } ?> data-select2-id="192">America/St_Johns - UTC/GMT -03:30</option>

        <option value="America/St_Kitts" <?php if($timezone == 'America/St_Kitts') { echo 'selected="selected"'; } ?> data-select2-id="193">America/St_Kitts - UTC/GMT -04:00</option>

        <option value="America/St_Lucia" <?php if($timezone == 'America/St_Lucia') { echo 'selected="selected"'; } ?> data-select2-id="194">America/St_Lucia - UTC/GMT -04:00</option>

        <option value="America/St_Thomas" <?php if($timezone == 'America/St_Thomas') { echo 'selected="selected"'; } ?> data-select2-id="195">America/St_Thomas - UTC/GMT -04:00</option>

        <option value="America/St_Vincent" <?php if($timezone == 'America/St_Vincent') { echo 'selected="selected"'; } ?> data-select2-id="196">America/St_Vincent - UTC/GMT -04:00</option>

        <option value="America/Swift_Current" <?php if($timezone == 'America/Swift_Current') { echo 'selected="selected"'; } ?> data-select2-id="197">America/Swift_Current - UTC/GMT -06:00</option>

        <option value="America/Tegucigalpa" <?php if($timezone == 'America/Tegucigalpa') { echo 'selected="selected"'; } ?> data-select2-id="198">America/Tegucigalpa - UTC/GMT -06:00</option>

        <option value="America/Thule" <?php if($timezone == 'America/Thule') { echo 'selected="selected"'; } ?> data-select2-id="199">America/Thule - UTC/GMT -04:00</option>

        <option value="America/Thunder_Bay" <?php if($timezone == 'America/Thunder_Bay') { echo 'selected="selected"'; } ?> data-select2-id="200">America/Thunder_Bay - UTC/GMT -05:00</option>

        <option value="America/Tijuana" <?php if($timezone == 'America/Tijuana') { echo 'selected="selected"'; } ?> data-select2-id="201">America/Tijuana - UTC/GMT -08:00</option>

        <option value="America/Toronto" <?php if($timezone == 'America/Toronto') { echo 'selected="selected"'; } ?> data-select2-id="202">America/Toronto - UTC/GMT -05:00</option>

        <option value="America/Tortola" <?php if($timezone == 'America/Tortola') { echo 'selected="selected"'; } ?> data-select2-id="203">America/Tortola - UTC/GMT -04:00</option>

        <option value="America/Vancouver" <?php if($timezone == 'America/Vancouver') { echo 'selected="selected"'; } ?> data-select2-id="204">America/Vancouver - UTC/GMT -08:00</option>

        <option value="America/Whitehorse" <?php if($timezone == 'America/Whitehorse') { echo 'selected="selected"'; } ?> data-select2-id="205">America/Whitehorse - UTC/GMT -08:00</option>

        <option value="America/Winnipeg" <?php if($timezone == 'America/Winnipeg') { echo 'selected="selected"'; } ?> data-select2-id="206">America/Winnipeg - UTC/GMT -06:00</option>

        <option value="America/Yakutat" <?php if($timezone == 'America/Yakutat') { echo 'selected="selected"'; } ?> data-select2-id="207">America/Yakutat - UTC/GMT -09:00</option>

        <option value="America/Yellowknife" <?php if($timezone == 'America/Yellowknife') { echo 'selected="selected"'; } ?> data-select2-id="208">America/Yellowknife - UTC/GMT -07:00</option>

        <option value="Antarctica/Casey" <?php if($timezone == 'Antarctica/Casey') { echo 'selected="selected"'; } ?> data-select2-id="209">Antarctica/Casey - UTC/GMT +08:00</option>

        <option value="Antarctica/Davis" <?php if($timezone == 'Antarctica/Davis') { echo 'selected="selected"'; } ?> data-select2-id="210">Antarctica/Davis - UTC/GMT +07:00</option>

        <option value="Antarctica/DumontDUrville" <?php if($timezone == 'Antarctica/DumontDUrville') { echo 'selected="selected"'; } ?> data-select2-id="211">Antarctica/DumontDUrville - UTC/GMT +10:00</option>

        <option value="Antarctica/Macquarie" <?php if($timezone == 'Antarctica/Macquarie') { echo 'selected="selected"'; } ?> data-select2-id="212">Antarctica/Macquarie - UTC/GMT +11:00</option>

        <option value="Antarctica/Mawson" <?php if($timezone == 'Antarctica/Mawson') { echo 'selected="selected"'; } ?> data-select2-id="213">Antarctica/Mawson - UTC/GMT +05:00</option>

        <option value="Antarctica/McMurdo" <?php if($timezone == 'Antarctica/McMurdo') { echo 'selected="selected"'; } ?> data-select2-id="214">Antarctica/McMurdo - UTC/GMT +13:00</option>

        <option value="Antarctica/Palmer" <?php if($timezone == 'Antarctica/Palmer') { echo 'selected="selected"'; } ?> data-select2-id="215">Antarctica/Palmer - UTC/GMT -03:00</option>

        <option value="Antarctica/Rothera" <?php if($timezone == 'Antarctica/Rothera') { echo 'selected="selected"'; } ?> data-select2-id="216">Antarctica/Rothera - UTC/GMT -03:00</option>

        <option value="Antarctica/Syowa" <?php if($timezone == 'Antarctica/Syowa') { echo 'selected="selected"'; } ?> data-select2-id="217">Antarctica/Syowa - UTC/GMT +03:00</option>

        <option value="Antarctica/Troll" <?php if($timezone == 'Antarctica/Troll') { echo 'selected="selected"'; } ?> data-select2-id="218">Antarctica/Troll - UTC/GMT +00:00</option>

        <option value="Antarctica/Vostok" <?php if($timezone == 'Antarctica/Vostok') { echo 'selected="selected"'; } ?> data-select2-id="219">Antarctica/Vostok - UTC/GMT +06:00</option>

        <option value="Arctic/Longyearbyen" <?php if($timezone == 'Arctic/Longyearbyen') { echo 'selected="selected"'; } ?> data-select2-id="220">Arctic/Longyearbyen - UTC/GMT +01:00</option>

        <option value="Asia/Aden" <?php if($timezone == 'Asia/Aden') { echo 'selected="selected"'; } ?> data-select2-id="221">Asia/Aden - UTC/GMT +03:00</option>

        <option value="Asia/Almaty" <?php if($timezone == 'Asia/Almaty') { echo 'selected="selected"'; } ?> data-select2-id="222">Asia/Almaty - UTC/GMT +06:00</option>

        <option value="Asia/Amman" <?php if($timezone == 'Asia/Amman') { echo 'selected="selected"'; } ?> data-select2-id="223">Asia/Amman - UTC/GMT +02:00</option>

        <option value="Asia/Anadyr" <?php if($timezone == 'Asia/Anadyr') { echo 'selected="selected"'; } ?> data-select2-id="224">Asia/Anadyr - UTC/GMT +12:00</option>

        <option value="Asia/Aqtau" <?php if($timezone == 'Asia/Aqtau') { echo 'selected="selected"'; } ?> data-select2-id="225">Asia/Aqtau - UTC/GMT +05:00</option>

        <option value="Asia/Aqtobe" <?php if($timezone == 'Asia/Aqtobe') { echo 'selected="selected"'; } ?> data-select2-id="226">Asia/Aqtobe - UTC/GMT +05:00</option>

        <option value="Asia/Ashgabat" <?php if($timezone == 'Asia/Ashgabat') { echo 'selected="selected"'; } ?> data-select2-id="227">Asia/Ashgabat - UTC/GMT +05:00</option>

        <option value="Asia/Atyrau" <?php if($timezone == 'Asia/Atyrau') { echo 'selected="selected"'; } ?> data-select2-id="228">Asia/Atyrau - UTC/GMT +05:00</option>

        <option value="Asia/Baghdad" <?php if($timezone == 'Asia/Baghdad') { echo 'selected="selected"'; } ?> data-select2-id="229">Asia/Baghdad - UTC/GMT +03:00</option>

        <option value="Asia/Bahrain" <?php if($timezone == 'Asia/Bahrain') { echo 'selected="selected"'; } ?> data-select2-id="230">Asia/Bahrain - UTC/GMT +03:00</option>

        <option value="Asia/Baku" <?php if($timezone == 'Asia/Baku') { echo 'selected="selected"'; } ?> data-select2-id="231">Asia/Baku - UTC/GMT +04:00</option>

        <option value="Asia/Bangkok" <?php if($timezone == 'Asia/Bangkok') { echo 'selected="selected"'; } ?> data-select2-id="232">Asia/Bangkok - UTC/GMT +07:00</option>

        <option value="Asia/Barnaul" <?php if($timezone == 'Asia/Barnaul') { echo 'selected="selected"'; } ?> data-select2-id="233">Asia/Barnaul - UTC/GMT +07:00</option>

        <option value="Asia/Beirut" <?php if($timezone == 'Asia/Beirut') { echo 'selected="selected"'; } ?> data-select2-id="234">Asia/Beirut - UTC/GMT +02:00</option>

        <option value="Asia/Bishkek" <?php if($timezone == 'Asia/Bishkek') { echo 'selected="selected"'; } ?> data-select2-id="235">Asia/Bishkek - UTC/GMT +06:00</option>

        <option value="Asia/Brunei" <?php if($timezone == 'Asia/Brunei') { echo 'selected="selected"'; } ?> data-select2-id="236">Asia/Brunei - UTC/GMT +08:00</option>

        <option value="Asia/Chita" <?php if($timezone == 'Asia/Chita') { echo 'selected="selected"'; } ?> data-select2-id="237">Asia/Chita - UTC/GMT +09:00</option>

        <option value="Asia/Choibalsan" <?php if($timezone == 'Asia/Choibalsan') { echo 'selected="selected"'; } ?> data-select2-id="238">Asia/Choibalsan - UTC/GMT +08:00</option>

        <option value="Asia/Colombo" <?php if($timezone == 'Asia/Colombo') { echo 'selected="selected"'; } ?> data-select2-id="239">Asia/Colombo - UTC/GMT +05:30</option>

        <option value="Asia/Damascus" <?php if($timezone == 'Asia/Damascus') { echo 'selected="selected"'; } ?> data-select2-id="240">Asia/Damascus - UTC/GMT +02:00</option>

        <option value="Asia/Dhaka" <?php if($timezone == 'Asia/Dhaka') { echo 'selected="selected"'; } ?> data-select2-id="241">Asia/Dhaka - UTC/GMT +06:00</option>

        <option value="Asia/Dili" <?php if($timezone == 'Asia/Dili') { echo 'selected="selected"'; } ?> data-select2-id="242">Asia/Dili - UTC/GMT +09:00</option>

        <option value="Asia/Dubai" <?php if($timezone == 'Asia/Dubai') { echo 'selected="selected"'; } ?> data-select2-id="243">Asia/Dubai - UTC/GMT +04:00</option>

        <option value="Asia/Dushanbe" <?php if($timezone == 'Asia/Dushanbe') { echo 'selected="selected"'; } ?> data-select2-id="244">Asia/Dushanbe - UTC/GMT +05:00</option>

        <option value="Asia/Famagusta" <?php if($timezone == 'Asia/Famagusta') { echo 'selected="selected"'; } ?> data-select2-id="245">Asia/Famagusta - UTC/GMT +02:00</option>

        <option value="Asia/Gaza" <?php if($timezone == 'Asia/Gaza') { echo 'selected="selected"'; } ?> data-select2-id="246">Asia/Gaza - UTC/GMT +02:00</option>

        <option value="Asia/Hebron" <?php if($timezone == 'Asia/Hebron') { echo 'selected="selected"'; } ?> data-select2-id="247">Asia/Hebron - UTC/GMT +02:00</option>

        <option value="Asia/Ho_Chi_Minh" <?php if($timezone == 'Asia/Ho_Chi_Minh') { echo 'selected="selected"'; } ?> data-select2-id="248">Asia/Ho_Chi_Minh - UTC/GMT +07:00</option>

        <option value="Asia/Hong_Kong" <?php if($timezone == 'Asia/Hong_Kong') { echo 'selected="selected"'; } ?> data-select2-id="249">Asia/Hong_Kong - UTC/GMT +08:00</option>

        <option value="Asia/Hovd" <?php if($timezone == 'Asia/Hovd') { echo 'selected="selected"'; } ?> data-select2-id="250">Asia/Hovd - UTC/GMT +07:00</option>

        <option value="Asia/Irkutsk" <?php if($timezone == 'Asia/Irkutsk') { echo 'selected="selected"'; } ?> data-select2-id="251">Asia/Irkutsk - UTC/GMT +08:00</option>

        <option value="Asia/Jakarta" <?php if($timezone == 'Asia/Jakarta') { echo 'selected="selected"'; } ?> data-select2-id="252">Asia/Jakarta - UTC/GMT +07:00</option>

        <option value="Asia/Jayapura" <?php if($timezone == 'Asia/Jayapura') { echo 'selected="selected"'; } ?> data-select2-id="253">Asia/Jayapura - UTC/GMT +09:00</option>

        <option value="Asia/Jerusalem" <?php if($timezone == 'Asia/Jerusalem') { echo 'selected="selected"'; } ?> data-select2-id="254">Asia/Jerusalem - UTC/GMT +02:00</option>

        <option value="Asia/Kabul" <?php if($timezone == 'Asia/Kabul') { echo 'selected="selected"'; } ?> data-select2-id="255">Asia/Kabul - UTC/GMT +04:30</option>

        <option value="Asia/Kamchatka" <?php if($timezone == 'Asia/Kamchatka') { echo 'selected="selected"'; } ?> data-select2-id="256">Asia/Kamchatka - UTC/GMT +12:00</option>

        <option value="Asia/Karachi" <?php if($timezone == 'Asia/Karachi') { echo 'selected="selected"'; } ?> data-select2-id="257">Asia/Karachi - UTC/GMT +05:00</option>

        <option value="Asia/Kathmandu" <?php if($timezone == 'Asia/Kathmandu') { echo 'selected="selected"'; } ?> data-select2-id="258">Asia/Kathmandu - UTC/GMT +05:45</option>

        <option value="Asia/Khandyga" <?php if($timezone == 'Asia/Khandyga') { echo 'selected="selected"'; } ?> data-select2-id="259">Asia/Khandyga - UTC/GMT +09:00</option>

        <option value="Asia/Kolkata" <?php if($timezone == 'Asia/Kolkata') { echo 'selected="selected"'; } ?> data-select2-id="260">Asia/Kolkata - UTC/GMT +05:30</option>

        <option value="Asia/Krasnoyarsk" <?php if($timezone == 'Asia/Krasnoyarsk') { echo 'selected="selected"'; } ?> data-select2-id="261">Asia/Krasnoyarsk - UTC/GMT +07:00</option>

        <option value="Asia/Kuala_Lumpur" <?php if($timezone == 'Asia/Kuala_Lumpur') { echo 'selected="selected"'; } ?> data-select2-id="262">Asia/Kuala_Lumpur - UTC/GMT +08:00</option>

        <option value="Asia/Kuching" <?php if($timezone == 'Asia/Kuching') { echo 'selected="selected"'; } ?> data-select2-id="263">Asia/Kuching - UTC/GMT +08:00</option>

        <option value="Asia/Kuwait" <?php if($timezone == 'Asia/Kuwait') { echo 'selected="selected"'; } ?> data-select2-id="264">Asia/Kuwait - UTC/GMT +03:00</option>

        <option value="Asia/Macau" <?php if($timezone == 'Asia/Macau') { echo 'selected="selected"'; } ?> data-select2-id="265">Asia/Macau - UTC/GMT +08:00</option>

        <option value="Asia/Magadan" <?php if($timezone == 'Asia/Magadan') { echo 'selected="selected"'; } ?> data-select2-id="266">Asia/Magadan - UTC/GMT +11:00</option>

        <option value="Asia/Makassar" <?php if($timezone == 'Asia/Makassar') { echo 'selected="selected"'; } ?> data-select2-id="267">Asia/Makassar - UTC/GMT +08:00</option>

        <option value="Asia/Manila" <?php if($timezone == 'Asia/Manila') { echo 'selected="selected"'; } ?> data-select2-id="268">Asia/Manila - UTC/GMT +08:00</option>

        <option value="Asia/Muscat" <?php if($timezone == 'Asia/Muscat') { echo 'selected="selected"'; } ?> data-select2-id="269">Asia/Muscat - UTC/GMT +04:00</option>

        <option value="Asia/Nicosia" <?php if($timezone == 'Asia/Nicosia') { echo 'selected="selected"'; } ?> data-select2-id="270">Asia/Nicosia - UTC/GMT +02:00</option>

        <option value="Asia/Novokuznetsk" <?php if($timezone == 'Asia/Novokuznetsk') { echo 'selected="selected"'; } ?> data-select2-id="271">Asia/Novokuznetsk - UTC/GMT +07:00</option>

        <option value="Asia/Novosibirsk" <?php if($timezone == 'Asia/Novosibirsk') { echo 'selected="selected"'; } ?> data-select2-id="272">Asia/Novosibirsk - UTC/GMT +07:00</option>

        <option value="Asia/Omsk" <?php if($timezone == 'Asia/Omsk') { echo 'selected="selected"'; } ?> data-select2-id="273">Asia/Omsk - UTC/GMT +06:00</option>

        <option value="Asia/Oral" <?php if($timezone == 'Asia/Oral') { echo 'selected="selected"'; } ?> data-select2-id="274">Asia/Oral - UTC/GMT +05:00</option>

        <option value="Asia/Phnom_Penh" <?php if($timezone == 'Asia/Phnom_Penh') { echo 'selected="selected"'; } ?> data-select2-id="275">Asia/Phnom_Penh - UTC/GMT +07:00</option>

        <option value="Asia/Pontianak" <?php if($timezone == 'Asia/Pontianak') { echo 'selected="selected"'; } ?> data-select2-id="276">Asia/Pontianak - UTC/GMT +07:00</option>

        <option value="Asia/Pyongyang" <?php if($timezone == 'Asia/Pyongyang') { echo 'selected="selected"'; } ?> data-select2-id="277">Asia/Pyongyang - UTC/GMT +09:00</option>

        <option value="Asia/Qatar" <?php if($timezone == 'Asia/Qatar') { echo 'selected="selected"'; } ?> data-select2-id="278">Asia/Qatar - UTC/GMT +03:00</option>

        <option value="Asia/Qostanay" <?php if($timezone == 'Asia/Qostanay') { echo 'selected="selected"'; } ?> data-select2-id="279">Asia/Qostanay - UTC/GMT +06:00</option>

        <option value="Asia/Qyzylorda" <?php if($timezone == 'Asia/Qyzylorda') { echo 'selected="selected"'; } ?> data-select2-id="280">Asia/Qyzylorda - UTC/GMT +05:00</option>

        <option value="Asia/Riyadh" <?php if($timezone == 'Asia/Riyadh') { echo 'selected="selected"'; } ?> data-select2-id="281">Asia/Riyadh - UTC/GMT +03:00</option>

        <option value="Asia/Sakhalin" <?php if($timezone == 'Asia/Sakhalin') { echo 'selected="selected"'; } ?> data-select2-id="282">Asia/Sakhalin - UTC/GMT +11:00</option>

        <option value="Asia/Samarkand" <?php if($timezone == 'Asia/Samarkand') { echo 'selected="selected"'; } ?> data-select2-id="283">Asia/Samarkand - UTC/GMT +05:00</option>

        <option value="Asia/Seoul" <?php if($timezone == 'Asia/Seoul') { echo 'selected="selected"'; } ?> data-select2-id="284">Asia/Seoul - UTC/GMT +09:00</option>

        <option value="Asia/Shanghai" <?php if($timezone == 'Asia/Shanghai') { echo 'selected="selected"'; } ?> data-select2-id="285">Asia/Shanghai - UTC/GMT +08:00</option>

        <option value="Asia/Singapore" <?php if($timezone == 'Asia/Singapore') { echo 'selected="selected"'; } ?> data-select2-id="286">Asia/Singapore - UTC/GMT +08:00</option>

        <option value="Asia/Srednekolymsk" <?php if($timezone == 'Asia/Srednekolymsk') { echo 'selected="selected"'; } ?> data-select2-id="287">Asia/Srednekolymsk - UTC/GMT +11:00</option>

        <option value="Asia/Taipei" <?php if($timezone == 'Asia/Taipei') { echo 'selected="selected"'; } ?> data-select2-id="288">Asia/Taipei - UTC/GMT +08:00</option>

        <option value="Asia/Tashkent" <?php if($timezone == 'Asia/Tashkent') { echo 'selected="selected"'; } ?> data-select2-id="289">Asia/Tashkent - UTC/GMT +05:00</option>

        <option value="Asia/Tbilisi" <?php if($timezone == 'Asia/Tbilisi') { echo 'selected="selected"'; } ?> data-select2-id="290">Asia/Tbilisi - UTC/GMT +04:00</option>

        <option value="Asia/Tehran" <?php if($timezone == 'Asia/Tehran') { echo 'selected="selected"'; } ?> data-select2-id="291">Asia/Tehran - UTC/GMT +03:30</option>

        <option value="Asia/Thimphu" <?php if($timezone == 'Asia/Thimphu') { echo 'selected="selected"'; } ?> data-select2-id="292">Asia/Thimphu - UTC/GMT +06:00</option>

        <option value="Asia/Tokyo" <?php if($timezone == 'Asia/Tokyo') { echo 'selected="selected"'; } ?> data-select2-id="293">Asia/Tokyo - UTC/GMT +09:00</option>

        <option value="Asia/Tomsk" <?php if($timezone == 'Asia/Tomsk') { echo 'selected="selected"'; } ?> data-select2-id="294">Asia/Tomsk - UTC/GMT +07:00</option>

        <option value="Asia/Ulaanbaatar" <?php if($timezone == 'Asia/Ulaanbaatar') { echo 'selected="selected"'; } ?> data-select2-id="295">Asia/Ulaanbaatar - UTC/GMT +08:00</option>

        <option value="Asia/Urumqi" <?php if($timezone == 'Asia/Urumqi') { echo 'selected="selected"'; } ?> data-select2-id="296">Asia/Urumqi - UTC/GMT +06:00</option>

        <option value="Asia/Ust-Nera" <?php if($timezone == 'Asia/Ust-Nera') { echo 'selected="selected"'; } ?> data-select2-id="297">Asia/Ust-Nera - UTC/GMT +10:00</option>

        <option value="Asia/Vientiane" <?php if($timezone == 'Asia/Vientiane') { echo 'selected="selected"'; } ?> data-select2-id="298">Asia/Vientiane - UTC/GMT +07:00</option>

        <option value="Asia/Vladivostok" <?php if($timezone == 'Asia/Vladivostok') { echo 'selected="selected"'; } ?> data-select2-id="299">Asia/Vladivostok - UTC/GMT +10:00</option>

        <option value="Asia/Yakutsk" <?php if($timezone == 'Asia/Yakutsk') { echo 'selected="selected"'; } ?> data-select2-id="300">Asia/Yakutsk - UTC/GMT +09:00</option>

        <option value="Asia/Yangon" <?php if($timezone == 'Asia/Yangon') { echo 'selected="selected"'; } ?> data-select2-id="301">Asia/Yangon - UTC/GMT +06:30</option>

        <option value="Asia/Yekaterinburg" <?php if($timezone == 'Asia/Yekaterinburg') { echo 'selected="selected"'; } ?> data-select2-id="302">Asia/Yekaterinburg - UTC/GMT +05:00</option>

        <option value="Asia/Yerevan" <?php if($timezone == 'Asia/Yerevan') { echo 'selected="selected"'; } ?> data-select2-id="303">Asia/Yerevan - UTC/GMT +04:00</option>

        <option value="Atlantic/Azores" <?php if($timezone == 'Atlantic/Azores') { echo 'selected="selected"'; } ?> data-select2-id="304">Atlantic/Azores - UTC/GMT -01:00</option>

        <option value="Atlantic/Bermuda" <?php if($timezone == 'Atlantic/Bermuda') { echo 'selected="selected"'; } ?> data-select2-id="305">Atlantic/Bermuda - UTC/GMT -04:00</option>

        <option value="Atlantic/Canary" <?php if($timezone == 'Atlantic/Canary') { echo 'selected="selected"'; } ?> data-select2-id="306">Atlantic/Canary - UTC/GMT +00:00</option>

        <option value="Atlantic/Cape_Verde" <?php if($timezone == 'Atlantic/Cape_Verde') { echo 'selected="selected"'; } ?> data-select2-id="307">Atlantic/Cape_Verde - UTC/GMT -01:00</option>

        <option value="Atlantic/Faroe" <?php if($timezone == 'Atlantic/Faroe') { echo 'selected="selected"'; } ?> data-select2-id="308">Atlantic/Faroe - UTC/GMT +00:00</option>

        <option value="Atlantic/Madeira" <?php if($timezone == 'Atlantic/Madeira') { echo 'selected="selected"'; } ?> data-select2-id="309">Atlantic/Madeira - UTC/GMT +00:00</option>

        <option value="Atlantic/Reykjavik" <?php if($timezone == 'Atlantic/Reykjavik') { echo 'selected="selected"'; } ?> data-select2-id="310">Atlantic/Reykjavik - UTC/GMT +00:00</option>

        <option value="Atlantic/South_Georgia" <?php if($timezone == 'Atlantic/South_Georgia') { echo 'selected="selected"'; } ?> data-select2-id="311">Atlantic/South_Georgia - UTC/GMT -02:00</option>

        <option value="Atlantic/St_Helena" <?php if($timezone == 'Atlantic/St_Helena') { echo 'selected="selected"'; } ?> data-select2-id="312">Atlantic/St_Helena - UTC/GMT +00:00</option>

        <option value="Atlantic/Stanley" <?php if($timezone == 'Atlantic/Stanley') { echo 'selected="selected"'; } ?> data-select2-id="313">Atlantic/Stanley - UTC/GMT -03:00</option>

        <option value="Australia/Adelaide" <?php if($timezone == 'Australia/Adelaide') { echo 'selected="selected"'; } ?> data-select2-id="314">Australia/Adelaide - UTC/GMT +10:30</option>

        <option value="Australia/Brisbane" <?php if($timezone == 'Australia/Brisbane') { echo 'selected="selected"'; } ?> data-select2-id="315">Australia/Brisbane - UTC/GMT +10:00</option>

        <option value="Australia/Broken_Hill" <?php if($timezone == 'Australia/Broken_Hill') { echo 'selected="selected"'; } ?> data-select2-id="316">Australia/Broken_Hill - UTC/GMT +10:30</option>

        <option value="Australia/Currie" <?php if($timezone == 'Australia/Currie') { echo 'selected="selected"'; } ?> data-select2-id="317">Australia/Currie - UTC/GMT +11:00</option>

        <option value="Australia/Darwin" <?php if($timezone == 'Australia/Darwin') { echo 'selected="selected"'; } ?> data-select2-id="318">Australia/Darwin - UTC/GMT +09:30</option>

        <option value="Australia/Eucla" <?php if($timezone == 'Australia/Eucla') { echo 'selected="selected"'; } ?> data-select2-id="319">Australia/Eucla - UTC/GMT +08:45</option>

        <option value="Australia/Hobart" <?php if($timezone == 'Australia/Hobart') { echo 'selected="selected"'; } ?> data-select2-id="320">Australia/Hobart - UTC/GMT +11:00</option>

        <option value="Australia/Lindeman" <?php if($timezone == 'Australia/Lindeman') { echo 'selected="selected"'; } ?> data-select2-id="321">Australia/Lindeman - UTC/GMT +10:00</option>

        <option value="Australia/Lord_Howe" <?php if($timezone == 'Australia/Lord_Howe') { echo 'selected="selected"'; } ?> data-select2-id="322">Australia/Lord_Howe - UTC/GMT +11:00</option>

        <option value="Australia/Melbourne" <?php if($timezone == 'Australia/Melbourne') { echo 'selected="selected"'; } ?> data-select2-id="323">Australia/Melbourne - UTC/GMT +11:00</option>

        <option value="Australia/Perth" <?php if($timezone == 'Australia/Perth') { echo 'selected="selected"'; } ?> data-select2-id="324">Australia/Perth - UTC/GMT +08:00</option>

        <option value="Australia/Sydney" <?php if($timezone == 'Australia/Sydney') { echo 'selected="selected"'; } ?> data-select2-id="325">Australia/Sydney - UTC/GMT +11:00</option>

        <option value="Europe/Amsterdam" <?php if($timezone == 'Europe/Amsterdam') { echo 'selected="selected"'; } ?> data-select2-id="326">Europe/Amsterdam - UTC/GMT +01:00</option>

        <option value="Europe/Andorra" <?php if($timezone == 'Europe/Andorra') { echo 'selected="selected"'; } ?> data-select2-id="327">Europe/Andorra - UTC/GMT +01:00</option>

        <option value="Europe/Astrakhan" <?php if($timezone == 'Europe/Astrakhan') { echo 'selected="selected"'; } ?> data-select2-id="328">Europe/Astrakhan - UTC/GMT +04:00</option>

        <option value="Europe/Athens" <?php if($timezone == 'Europe/Athens') { echo 'selected="selected"'; } ?> data-select2-id="329">Europe/Athens - UTC/GMT +02:00</option>

        <option value="Europe/Belgrade" <?php if($timezone == 'Europe/Belgrade') { echo 'selected="selected"'; } ?> data-select2-id="330">Europe/Belgrade - UTC/GMT +01:00</option>

        <option value="Europe/Berlin" <?php if($timezone == 'Europe/Berlin') { echo 'selected="selected"'; } ?> data-select2-id="331">Europe/Berlin - UTC/GMT +01:00</option>

        <option value="Europe/Bratislava" <?php if($timezone == 'Europe/Bratislava') { echo 'selected="selected"'; } ?> data-select2-id="332">Europe/Bratislava - UTC/GMT +01:00</option>

        <option value="Europe/Brussels" <?php if($timezone == 'Europe/Brussels') { echo 'selected="selected"'; } ?> data-select2-id="333">Europe/Brussels - UTC/GMT +01:00</option>

        <option value="Europe/Bucharest" <?php if($timezone == 'Europe/Bucharest') { echo 'selected="selected"'; } ?> data-select2-id="334">Europe/Bucharest - UTC/GMT +02:00</option>

        <option value="Europe/Budapest" <?php if($timezone == 'Europe/Budapest') { echo 'selected="selected"'; } ?> data-select2-id="335">Europe/Budapest - UTC/GMT +01:00</option>

        <option value="Europe/Busingen" <?php if($timezone == 'Europe/Busingen') { echo 'selected="selected"'; } ?> data-select2-id="336">Europe/Busingen - UTC/GMT +01:00</option>

        <option value="Europe/Chisinau" <?php if($timezone == 'Europe/Chisinau') { echo 'selected="selected"'; } ?> data-select2-id="337">Europe/Chisinau - UTC/GMT +02:00</option>

        <option value="Europe/Copenhagen" <?php if($timezone == 'Europe/Copenhagen') { echo 'selected="selected"'; } ?> data-select2-id="338">Europe/Copenhagen - UTC/GMT +01:00</option>

        <option value="Europe/Dublin" <?php if($timezone == 'Europe/Dublin') { echo 'selected="selected"'; } ?> data-select2-id="339">Europe/Dublin - UTC/GMT +00:00</option>

        <option value="Europe/Gibraltar" <?php if($timezone == 'Europe/Gibraltar') { echo 'selected="selected"'; } ?> data-select2-id="340">Europe/Gibraltar - UTC/GMT +01:00</option>

        <option value="Europe/Guernsey" <?php if($timezone == 'Europe/Guernsey') { echo 'selected="selected"'; } ?> data-select2-id="341">Europe/Guernsey - UTC/GMT +00:00</option>

        <option value="Europe/Helsinki" <?php if($timezone == 'Europe/Helsinki') { echo 'selected="selected"'; } ?> data-select2-id="342">Europe/Helsinki - UTC/GMT +02:00</option>

        <option value="Europe/Isle_of_Man" <?php if($timezone == 'Europe/Isle_of_Man') { echo 'selected="selected"'; } ?> data-select2-id="343">Europe/Isle_of_Man - UTC/GMT +00:00</option>

        <option value="Europe/Istanbul" <?php if($timezone == 'Europe/Istanbul') { echo 'selected="selected"'; } ?> data-select2-id="344">Europe/Istanbul - UTC/GMT +03:00</option>

        <option value="Europe/Jersey" <?php if($timezone == 'Europe/Jersey') { echo 'selected="selected"'; } ?> data-select2-id="345">Europe/Jersey - UTC/GMT +00:00</option>

        <option value="Europe/Kaliningrad" <?php if($timezone == 'Europe/Kaliningrad') { echo 'selected="selected"'; } ?> data-select2-id="346">Europe/Kaliningrad - UTC/GMT +02:00</option>

        <option value="Europe/Kiev" <?php if($timezone == 'Europe/Kiev') { echo 'selected="selected"'; } ?> data-select2-id="347">Europe/Kiev - UTC/GMT +02:00</option>

        <option value="Europe/Kirov" <?php if($timezone == 'Europe/Kirov') { echo 'selected="selected"'; } ?> data-select2-id="348">Europe/Kirov - UTC/GMT +03:00</option>

        <option value="Europe/Lisbon" <?php if($timezone == 'Europe/Lisbon') { echo 'selected="selected"'; } ?> data-select2-id="349">Europe/Lisbon - UTC/GMT +00:00</option>

        <option value="Europe/Ljubljana" <?php if($timezone == 'Europe/Ljubljana') { echo 'selected="selected"'; } ?> data-select2-id="350">Europe/Ljubljana - UTC/GMT +01:00</option>

        <option value="Europe/London" <?php if($timezone == 'Europe/London') { echo 'selected="selected"'; } ?> data-select2-id="351">Europe/London - UTC/GMT +00:00</option>

        <option value="Europe/Luxembourg" <?php if($timezone == 'Europe/Luxembourg') { echo 'selected="selected"'; } ?> data-select2-id="352">Europe/Luxembourg - UTC/GMT +01:00</option>

        <option value="Europe/Madrid" <?php if($timezone == 'Europe/Madrid') { echo 'selected="selected"'; } ?> data-select2-id="353">Europe/Madrid - UTC/GMT +01:00</option>

        <option value="Europe/Malta" <?php if($timezone == 'Europe/Malta') { echo 'selected="selected"'; } ?> data-select2-id="354">Europe/Malta - UTC/GMT +01:00</option>

        <option value="Europe/Mariehamn" <?php if($timezone == 'Europe/Mariehamn') { echo 'selected="selected"'; } ?> data-select2-id="355">Europe/Mariehamn - UTC/GMT +02:00</option>

        <option value="Europe/Minsk" <?php if($timezone == 'Europe/Minsk') { echo 'selected="selected"'; } ?> data-select2-id="356">Europe/Minsk - UTC/GMT +03:00</option>

        <option value="Europe/Monaco" <?php if($timezone == 'Europe/Monaco') { echo 'selected="selected"'; } ?> data-select2-id="357">Europe/Monaco - UTC/GMT +01:00</option>

        <option value="Europe/Moscow" <?php if($timezone == 'Europe/Moscow') { echo 'selected="selected"'; } ?> data-select2-id="358">Europe/Moscow - UTC/GMT +03:00</option>

        <option value="Europe/Oslo" <?php if($timezone == 'Europe/Oslo') { echo 'selected="selected"'; } ?> data-select2-id="359">Europe/Oslo - UTC/GMT +01:00</option>

        <option value="Europe/Paris" <?php if($timezone == 'Europe/Paris') { echo 'selected="selected"'; } ?> data-select2-id="360">Europe/Paris - UTC/GMT +01:00</option>

        <option value="Europe/Podgorica" <?php if($timezone == 'Europe/Podgorica') { echo 'selected="selected"'; } ?> data-select2-id="361">Europe/Podgorica - UTC/GMT +01:00</option>

        <option value="Europe/Prague" <?php if($timezone == 'Europe/Prague') { echo 'selected="selected"'; } ?> data-select2-id="362">Europe/Prague - UTC/GMT +01:00</option>

        <option value="Europe/Riga" <?php if($timezone == 'Europe/Riga') { echo 'selected="selected"'; } ?> data-select2-id="363">Europe/Riga - UTC/GMT +02:00</option>

        <option value="Europe/Rome" <?php if($timezone == 'Europe/Rome') { echo 'selected="selected"'; } ?> data-select2-id="364">Europe/Rome - UTC/GMT +01:00</option>

        <option value="Europe/Samara" <?php if($timezone == 'Europe/Samara') { echo 'selected="selected"'; } ?> data-select2-id="365">Europe/Samara - UTC/GMT +04:00</option>

        <option value="Europe/San_Marino" <?php if($timezone == 'Europe/San_Marino') { echo 'selected="selected"'; } ?> data-select2-id="366">Europe/San_Marino - UTC/GMT +01:00</option>

        <option value="Europe/Sarajevo" <?php if($timezone == 'Europe/Sarajevo') { echo 'selected="selected"'; } ?> data-select2-id="367">Europe/Sarajevo - UTC/GMT +01:00</option>

        <option value="Europe/Saratov" <?php if($timezone == 'Europe/Saratov') { echo 'selected="selected"'; } ?> data-select2-id="368">Europe/Saratov - UTC/GMT +04:00</option>

        <option value="Europe/Simferopol" <?php if($timezone == 'Europe/Simferopol') { echo 'selected="selected"'; } ?> data-select2-id="369">Europe/Simferopol - UTC/GMT +03:00</option>

        <option value="Europe/Skopje" <?php if($timezone == 'Europe/Skopje') { echo 'selected="selected"'; } ?> data-select2-id="370">Europe/Skopje - UTC/GMT +01:00</option>

        <option value="Europe/Sofia" <?php if($timezone == 'Europe/Sofia') { echo 'selected="selected"'; } ?> data-select2-id="371">Europe/Sofia - UTC/GMT +02:00</option>

        <option value="Europe/Stockholm" <?php if($timezone == 'Europe/Stockholm') { echo 'selected="selected"'; } ?> data-select2-id="372">Europe/Stockholm - UTC/GMT +01:00</option>

        <option value="Europe/Tallinn" <?php if($timezone == 'Europe/Tallinn') { echo 'selected="selected"'; } ?> data-select2-id="373">Europe/Tallinn - UTC/GMT +02:00</option>

        <option value="Europe/Tirane" <?php if($timezone == 'Europe/Tirane') { echo 'selected="selected"'; } ?> data-select2-id="374">Europe/Tirane - UTC/GMT +01:00</option>

        <option value="Europe/Ulyanovsk" <?php if($timezone == 'Europe/Ulyanovsk') { echo 'selected="selected"'; } ?> data-select2-id="375">Europe/Ulyanovsk - UTC/GMT +04:00</option>

        <option value="Europe/Uzhgorod" <?php if($timezone == 'Europe/Uzhgorod') { echo 'selected="selected"'; } ?> data-select2-id="376">Europe/Uzhgorod - UTC/GMT +02:00</option>

        <option value="Europe/Vaduz" <?php if($timezone == 'Europe/Vaduz') { echo 'selected="selected"'; } ?> data-select2-id="377">Europe/Vaduz - UTC/GMT +01:00</option>

        <option value="Europe/Vatican" <?php if($timezone == 'Europe/Vatican') { echo 'selected="selected"'; } ?> data-select2-id="378">Europe/Vatican - UTC/GMT +01:00</option>

        <option value="Europe/Vienna" <?php if($timezone == 'Europe/Vienna') { echo 'selected="selected"'; } ?> data-select2-id="379">Europe/Vienna - UTC/GMT +01:00</option>

        <option value="Europe/Vilnius" <?php if($timezone == 'Europe/Vilnius') { echo 'selected="selected"'; } ?> data-select2-id="380">Europe/Vilnius - UTC/GMT +02:00</option>

        <option value="Europe/Volgograd" <?php if($timezone == 'Europe/Volgograd') { echo 'selected="selected"'; } ?> data-select2-id="381">Europe/Volgograd - UTC/GMT +04:00</option>

        <option value="Europe/Warsaw" <?php if($timezone == 'Europe/Warsaw') { echo 'selected="selected"'; } ?> data-select2-id="382">Europe/Warsaw - UTC/GMT +01:00</option>

        <option value="Europe/Zagreb" <?php if($timezone == 'Europe/Zagreb') { echo 'selected="selected"'; } ?> data-select2-id="383">Europe/Zagreb - UTC/GMT +01:00</option>

        <option value="Europe/Zaporozhye" <?php if($timezone == 'Europe/Zaporozhye') { echo 'selected="selected"'; } ?> data-select2-id="384">Europe/Zaporozhye - UTC/GMT +02:00</option>

        <option value="Europe/Zurich" <?php if($timezone == 'Europe/Zurich') { echo 'selected="selected"'; } ?> data-select2-id="385">Europe/Zurich - UTC/GMT +01:00</option>

        <option value="Indian/Antananarivo" <?php if($timezone == 'Indian/Antananarivo') { echo 'selected="selected"'; } ?> data-select2-id="386">Indian/Antananarivo - UTC/GMT +03:00</option>

        <option value="Indian/Chagos" <?php if($timezone == 'Indian/Chagos') { echo 'selected="selected"'; } ?> data-select2-id="387">Indian/Chagos - UTC/GMT +06:00</option>

        <option value="Indian/Christmas" <?php if($timezone == 'Indian/Christmas') { echo 'selected="selected"'; } ?> data-select2-id="388">Indian/Christmas - UTC/GMT +07:00</option>

        <option value="Indian/Cocos" <?php if($timezone == 'Indian/Cocos') { echo 'selected="selected"'; } ?> data-select2-id="389">Indian/Cocos - UTC/GMT +06:30</option>

        <option value="Indian/Comoro" <?php if($timezone == 'Indian/Comoro') { echo 'selected="selected"'; } ?> data-select2-id="390">Indian/Comoro - UTC/GMT +03:00</option>

        <option value="Indian/Kerguelen" <?php if($timezone == 'Indian/Kerguelen') { echo 'selected="selected"'; } ?> data-select2-id="391">Indian/Kerguelen - UTC/GMT +05:00</option>

        <option value="Indian/Mahe" <?php if($timezone == 'Indian/Mahe') { echo 'selected="selected"'; } ?> data-select2-id="392">Indian/Mahe - UTC/GMT +04:00</option>

        <option value="Indian/Maldives" <?php if($timezone == 'Indian/Maldives') { echo 'selected="selected"'; } ?> data-select2-id="393">Indian/Maldives - UTC/GMT +05:00</option>

        <option value="Indian/Mauritius" <?php if($timezone == 'Indian/Mauritius') { echo 'selected="selected"'; } ?> data-select2-id="394">Indian/Mauritius - UTC/GMT +04:00</option>

        <option value="Indian/Mayotte" <?php if($timezone == 'Indian/Mayotte') { echo 'selected="selected"'; } ?> data-select2-id="395">Indian/Mayotte - UTC/GMT +03:00</option>

        <option value="Indian/Reunion" <?php if($timezone == 'Indian/Reunion') { echo 'selected="selected"'; } ?> data-select2-id="396">Indian/Reunion - UTC/GMT +04:00</option>

        <option value="Pacific/Apia" <?php if($timezone == 'Pacific/Apia') { echo 'selected="selected"'; } ?> data-select2-id="397">Pacific/Apia - UTC/GMT +14:00</option>

        <option value="Pacific/Auckland" <?php if($timezone == 'Pacific/Auckland') { echo 'selected="selected"'; } ?> data-select2-id="398">Pacific/Auckland - UTC/GMT +13:00</option>

        <option value="Pacific/Bougainville" <?php if($timezone == 'Pacific/Bougainville') { echo 'selected="selected"'; } ?> data-select2-id="399">Pacific/Bougainville - UTC/GMT +11:00</option>

        <option value="Pacific/Chatham" <?php if($timezone == 'Pacific/Chatham') { echo 'selected="selected"'; } ?> data-select2-id="400">Pacific/Chatham - UTC/GMT +13:45</option>

        <option value="Pacific/Chuuk" <?php if($timezone == 'Pacific/Chuuk') { echo 'selected="selected"'; } ?> data-select2-id="401">Pacific/Chuuk - UTC/GMT +10:00</option>

        <option value="Pacific/Easter" <?php if($timezone == 'Pacific/Easter') { echo 'selected="selected"'; } ?> data-select2-id="402">Pacific/Easter - UTC/GMT -05:00</option>

        <option value="Pacific/Efate" <?php if($timezone == 'Pacific/Efate') { echo 'selected="selected"'; } ?> data-select2-id="403">Pacific/Efate - UTC/GMT +11:00</option>

        <option value="Pacific/Enderbury" <?php if($timezone == 'Pacific/Enderbury') { echo 'selected="selected"'; } ?> data-select2-id="404">Pacific/Enderbury - UTC/GMT +13:00</option>

        <option value="Pacific/Fakaofo" <?php if($timezone == 'Pacific/Fakaofo') { echo 'selected="selected"'; } ?> data-select2-id="405">Pacific/Fakaofo - UTC/GMT +13:00</option>

        <option value="Pacific/Fiji" <?php if($timezone == 'Pacific/Fiji') { echo 'selected="selected"'; } ?> data-select2-id="406">Pacific/Fiji - UTC/GMT +12:00</option>

        <option value="Pacific/Funafuti" <?php if($timezone == 'Pacific/Funafuti') { echo 'selected="selected"'; } ?> data-select2-id="407">Pacific/Funafuti - UTC/GMT +12:00</option>

        <option value="Pacific/Galapagos" <?php if($timezone == 'Pacific/Galapagos') { echo 'selected="selected"'; } ?> data-select2-id="408">Pacific/Galapagos - UTC/GMT -06:00</option>

        <option value="Pacific/Gambier" <?php if($timezone == 'Pacific/Gambier') { echo 'selected="selected"'; } ?> data-select2-id="409">Pacific/Gambier - UTC/GMT -09:00</option>

        <option value="Pacific/Guadalcanal" <?php if($timezone == 'Pacific/Guadalcanal') { echo 'selected="selected"'; } ?> data-select2-id="410">Pacific/Guadalcanal - UTC/GMT +11:00</option>

        <option value="Pacific/Guam" <?php if($timezone == 'Pacific/Guam') { echo 'selected="selected"'; } ?> data-select2-id="411">Pacific/Guam - UTC/GMT +10:00</option>

        <option value="Pacific/Honolulu" <?php if($timezone == 'Pacific/Honolulu') { echo 'selected="selected"'; } ?> data-select2-id="412">Pacific/Honolulu - UTC/GMT -10:00</option>

        <option value="Pacific/Kiritimati" <?php if($timezone == 'Pacific/Kiritimati') { echo 'selected="selected"'; } ?> data-select2-id="413">Pacific/Kiritimati - UTC/GMT +14:00</option>

        <option value="Pacific/Kosrae" <?php if($timezone == 'Pacific/Kosrae') { echo 'selected="selected"'; } ?> data-select2-id="414">Pacific/Kosrae - UTC/GMT +11:00</option>

        <option value="Pacific/Kwajalein" <?php if($timezone == 'Pacific/Kwajalein') { echo 'selected="selected"'; } ?> data-select2-id="415">Pacific/Kwajalein - UTC/GMT +12:00</option>

        <option value="Pacific/Majuro" <?php if($timezone == 'Pacific/Majuro') { echo 'selected="selected"'; } ?> data-select2-id="416">Pacific/Majuro - UTC/GMT +12:00</option>

        <option value="Pacific/Marquesas" <?php if($timezone == 'Pacific/Marquesas') { echo 'selected="selected"'; } ?> data-select2-id="417">Pacific/Marquesas - UTC/GMT -09:30</option>

        <option value="Pacific/Midway" <?php if($timezone == 'Pacific/Midway') { echo 'selected="selected"'; } ?> data-select2-id="418">Pacific/Midway - UTC/GMT -11:00</option>

        <option value="Pacific/Nauru" <?php if($timezone == 'Pacific/Nauru') { echo 'selected="selected"'; } ?> data-select2-id="419">Pacific/Nauru - UTC/GMT +12:00</option>

        <option value="Pacific/Niue" <?php if($timezone == 'Pacific/Niue') { echo 'selected="selected"'; } ?> data-select2-id="420">Pacific/Niue - UTC/GMT -11:00</option>

        <option value="Pacific/Norfolk" <?php if($timezone == 'Pacific/Norfolk') { echo 'selected="selected"'; } ?> data-select2-id="421">Pacific/Norfolk - UTC/GMT +12:00</option>

        <option value="Pacific/Noumea" <?php if($timezone == 'Pacific/Noumea') { echo 'selected="selected"'; } ?> data-select2-id="422">Pacific/Noumea - UTC/GMT +11:00</option>

        <option value="Pacific/Pago_Pago" <?php if($timezone == 'Pacific/Pago_Pago') { echo 'selected="selected"'; } ?> data-select2-id="423">Pacific/Pago_Pago - UTC/GMT -11:00</option>

        <option value="Pacific/Palau" <?php if($timezone == 'Pacific/Palau') { echo 'selected="selected"'; } ?> data-select2-id="424">Pacific/Palau - UTC/GMT +09:00</option>

        <option value="Pacific/Pitcairn" <?php if($timezone == 'Pacific/Pitcairn') { echo 'selected="selected"'; } ?> data-select2-id="425">Pacific/Pitcairn - UTC/GMT -08:00</option>

        <option value="Pacific/Pohnpei" <?php if($timezone == 'Pacific/Pohnpei') { echo 'selected="selected"'; } ?> data-select2-id="426">Pacific/Pohnpei - UTC/GMT +11:00</option>

        <option value="Pacific/Port_Moresby" <?php if($timezone == 'Pacific/Port_Moresby') { echo 'selected="selected"'; } ?> data-select2-id="427">Pacific/Port_Moresby - UTC/GMT +10:00</option>

        <option value="Pacific/Rarotonga" <?php if($timezone == 'Pacific/Rarotonga') { echo 'selected="selected"'; } ?> data-select2-id="428">Pacific/Rarotonga - UTC/GMT -10:00</option>

        <option value="Pacific/Saipan" <?php if($timezone == 'Pacific/Saipan') { echo 'selected="selected"'; } ?> data-select2-id="429">Pacific/Saipan - UTC/GMT +10:00</option>

        <option value="Pacific/Tahiti" <?php if($timezone == 'Pacific/Tahiti') { echo 'selected="selected"'; } ?> data-select2-id="430">Pacific/Tahiti - UTC/GMT -10:00</option>

        <option value="Pacific/Tarawa" <?php if($timezone == 'Pacific/Tarawa') { echo 'selected="selected"'; } ?> data-select2-id="431">Pacific/Tarawa - UTC/GMT +12:00</option>

        <option value="Pacific/Tongatapu" <?php if($timezone == 'Pacific/Tongatapu') { echo 'selected="selected"'; } ?> data-select2-id="432">Pacific/Tongatapu - UTC/GMT +13:00</option>

        <option value="Pacific/Wake" <?php if($timezone == 'Pacific/Wake') { echo 'selected="selected"'; } ?> data-select2-id="433">Pacific/Wake - UTC/GMT +12:00</option>

        <option value="Pacific/Wallis" <?php if($timezone == 'Pacific/Wallis') { echo 'selected="selected"'; } ?> data-select2-id="434">Pacific/Wallis - UTC/GMT +12:00</option>

        <option value="UTC" <?php if($timezone == 'UTC') { echo 'selected="selected"'; } ?> data-select2-id="435">UTC - UTC/GMT +00:00</option> -->

    </select>
<?php }
