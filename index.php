<html>


<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script src="script.js"></script>
    <meta charset="UTF-8" />
    <title>Search</title>

    <style>
        h1 {
            color: green;
        }

        input[type=text] {
            padding: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
        }
    </style>

</head>

<body>
    <h1 style="text-align:center">SEARCH</h1>
    <form method="post" name="search_form" id="search_form">
        <strong>Gender: </strong><input type="radio" name="gender" value="male"> Male
        <input type="radio" name="gender" value="female"> Female
        <input type="radio" name="gender" value="both" checked> Both <br><br>
        <hr>
        <strong>Name: </strong><input type="text" id="name" name="name" value="<?= isset($_POST["name"]) ? $_POST["name"] : ""; ?>" />
        <strong>Lastname: </strong><input type="text" id="lastname" name="lastname" value="<?= isset($_POST["lastname"]) ? $_POST["lastname"] : ""; ?>" /> <br><br>
        <hr>
        <div class="column">
            <div class="education_level" method="post">
                <strong>Education Level: </strong><br>
                <select id="education_level" name="education_level[]" multiple>
                    <option value="Graduate Doctoral Degree" <?= isset($_POST["education_level"]) ? "selected" : ""; ?>>Graduate Doctoral Degree</option>
                    <option value="Graduate Master's Degree">Graduate Master's Degree</option>
                    <option value="4 year Undergraduate Bachelor's Degree">4 year Undergraduate Bachelor's Degree</option>
                    <option value="2 year Undergraduate Associate's Degree">2 year Undergraduate Associate's Degree</option>
                    <option value="High School Diploma">High School Diploma</option>
                    <option value="Vocational High School Diploma">Vocational High School Diploma</option>
                    <option value="Primary School Diploma">Primary School Diploma</option>
                </select>
            </div>
            <div class="job_field">
                <strong>Job Field: </strong><br>
                <select id="job_field" name="job_field[]" multiple>
                    <option value="Business Development">Business Development</option>
                    <option value="Marketing & Sales">Marketing & Sales</option>
                    <option value="Proposal">Proposal</option>
                    <option value="Planing">Planing</option>
                    <option value="Procurement">Procurement</option>
                    <option value="Logistics">Logistics</option>
                    <option value="Finance">Finance</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Construction-Site Works">Construction-Site Works</option>
                    <option value="Technical Office">Technical Office</option>
                    <option value="Quality">Quality</option>
                    <option value="Health, Safety, Environment">Health, Safety, Environment</option>
                    <option value="Commissioning">Commissioning</option>
                    <option value="Documentation">Documentation</option>
                    <option value="Facilities & Admin">Facilities & Admin</option>
                    <option value="Human Resources">Human Resources</option>
                    <option value="Legal">Legal</option>
                    <option value="Corporate Communication">Corporate Communication</option>
                    <option value="Other (Please specify in Short Description field)">Other</option>
                </select>
            </div>
            <div class="language">
                <strong>Language: </strong><br>
                <select id="language" name="language[]" multiple>
                    <option value="English">English</option>
                    <option value="Arabic">Arabic</option>
                    <option value="Russian">Russian</option>
                    <option value="French">French</option>
                    <option value="German">German</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <hr>
            <button type="submit" style="background-color:green; border: ridge lightgrey; color:white" class="btn" name="btn-search" id="btn-search">Search</button>
            <button onclick="clearFields()"  style="background-color:brown; border: ridge lightgrey; color:white" class="btn" name="btn-clear" id="btn-clear" value="Reset">Clear</button>

        </div>
        <hr>
    </form>
    <?php
    if ($_POST) {
        $search_name = $_POST["name"];
        $search_lastname = $_POST["lastname"];
        $search_gender = $_POST["gender"];
        if ($search_gender == "male") {
            $search_gender = "1";
        } else if ($search_gender == "female") {
            $search_gender = "2";
        } else if ($search_gender == "both") {
            $search_gender = "both";
        }

        $sql = "SELECT * FROM db_gama.gh_jobapplications";

        $connection = mysqli_connect("127.0.0.1", "wpgama", "Eq9wbxx!S3crt@gama", "db_gama");
        $connection->query("SET CHARACTER SET utf8");

        $query = mysqli_query($connection, $sql);

        while ($find = mysqli_fetch_array($query)) {
            $json[] = $find["data"];
        }

        // flags
        $name_is_empty = false;
        $lastname_is_empty = false;
        $education_is_empty = false;
        $job_is_empty = false;
        $language_is_empty = false;

        if (empty($search_name)) { // name flag
            $name_is_empty = true;
        }
        if (empty($search_lastname)) { // lastname flag
            $lastname_is_empty = true;
        }
        if (isset($_POST['education_level']) == 0) { // education level flag
            $education_is_empty = true;
        }
        if (isset($_POST['job_field']) == 0) { // job field flag
            $job_is_empty = true;
        }
        if (isset($_POST['language']) == 0) { // language flag
            $language_is_empty = true;
        }

        foreach ($json as $r) { // parse each person
            // lists
            $education_list = array();
            $education_list2 = array();
            $job_list = array();
            $job_list2 = array();
            $language_list = array();
            $language_list2 = array();

            $arr = json_decode($r, true);
            // gender correction
            if ($arr["candidate_gender"] == "1") {
                $arr["candidate_gender"] = "Male";
            } else {
                $arr["candidate_gender"] = "Female";
            }
            // education correction
            if (isset($arr["candidate_education"]))
                foreach ($arr["candidate_education"] as $education) {
                    if (is_array($education)) {
                        if ($education["level"] == "1")
                            $education["level"] = "Graduate Doctoral Degree";
                        if ($education["level"] == "2")
                            $education["level"] = "Graduate Master's Degree";
                        if ($education["level"] == "3")
                            $education["level"] = "4 year Undergraduate Bachelor's Degree";
                        if ($education["level"] == "4")
                            $education["level"] = "2 year Undergraduate Associate's Degree";
                        if ($education["level"] == "5")
                            $education["level"] = "High School Diploma";
                        if ($education["level"] == "6")
                            $education["level"] = "Vocational High School Diploma";
                        if ($education["level"] == "7")
                            $education["level"] = "Primary School Diploma";

                        array_push($education_list2, $education["level"]);
                        array_push($education_list, $education["level"] . " - " . $education["education_type"] . " - " . $education["education_field"]);
                    }
                    if (is_string($education)) {
                        $education2 = json_decode($education, true, 512, JSON_UNESCAPED_UNICODE);
                        if ($education2[0]["level"] == "1")
                            $education2[0]["level"] = "Graduate Doctoral Degree";
                        if ($education2[0]["level"] == "2")
                            $education2[0]["level"] = "Graduate Master's Degree";
                        if ($education2[0]["level"] == "3")
                            $education2[0]["level"] = "4 year Undergraduate Bachelor's Degree";
                        if ($education2[0]["level"] == "4")
                            $education2[0]["level"] = "2 year Undergraduate Associate's Degree";
                        if ($education2[0]["level"] == "5")
                            $education2[0]["level"] = "High School Diploma";
                        if ($education2[0]["level"] == "6")
                            $education2[0]["level"] = "Vocational High School Diploma";
                        if ($education2[0]["level"] == "7")
                            $education2[0]["level"] = "Primary School Diploma";

                        array_push($education_list2, $education2[0]["level"]);
                        array_push($education_list, $education2[0]["level"] . " - " . $education2[0]["education_type"] . " - " . $education2[0]["education_field"]);
                    }
                }

            // job correction
            if (isset($arr["candidate_experience"]))
                foreach ($arr["candidate_experience"] as $candidate_experience) {
                    // candidate experience correction
                    if (is_array($candidate_experience)) {
                        if ($candidate_experience["field"] == "1")
                            $candidate_experience["field"] = "Business Development";
                        if ($candidate_experience["field"] == "2")
                            $candidate_experience["field"] = "Marketing & Sales";
                        if ($candidate_experience["field"] == "3")
                            $candidate_experience["field"] = "Proposal";
                        if ($candidate_experience["field"] == "4")
                            $candidate_experience["field"] = "Planing";
                        if ($candidate_experience["field"] == "5")
                            $candidate_experience["field"] = "Procurement";
                        if ($candidate_experience["field"] == "6")
                            $candidate_experience["field"] = "Logistics";
                        if ($candidate_experience["field"] == "7")
                            $candidate_experience["field"] = "Finance";
                        if ($candidate_experience["field"] == "8")
                            $candidate_experience["field"] = "Accounting";
                        if ($candidate_experience["field"] == "9")
                            $candidate_experience["field"] = "Engineering";
                        if ($candidate_experience["field"] == "10")
                            $candidate_experience["field"] = "Construction-Site Works";
                        if ($candidate_experience["field"] == "11")
                            $candidate_experience["field"] = "Technical Office";
                        if ($candidate_experience["field"] == "12")
                            $candidate_experience["field"] = "Quality";
                        if ($candidate_experience["field"] == "13")
                            $candidate_experience["field"] = "Health, Safety, Environment";
                        if ($candidate_experience["field"] == "14")
                            $candidate_experience["field"] = "Commissioning";
                        if ($candidate_experience["field"] == "15")
                            $candidate_experience["field"] = "Documentation";
                        if ($candidate_experience["field"] == "16")
                            $candidate_experience["field"] = "Facilities & Admin";
                        if ($candidate_experience["field"] == "17")
                            $candidate_experience["field"] = "Human Resources";
                        if ($candidate_experience["field"] == "18")
                            $candidate_experience["field"] = "Legal";
                        if ($candidate_experience["field"] == "19")
                            $candidate_experience["field"] = "Corporate Communication";
                        if ($candidate_experience["field"] == "20")
                            $candidate_experience["field"] = "Other (Please specify in Short Description field)";

                        array_push($job_list2, $candidate_experience["field"]);
                        array_push($job_list, $candidate_experience["field"] . " - " . $candidate_experience["company"] . " - " . $candidate_experience["position"]);
                    }
                    if (is_string($candidate_experience)) {
                        $candidate_experience2 = json_decode($candidate_experience, true, 512, JSON_UNESCAPED_UNICODE);
                        if ($candidate_experience2[0]["field"] == "1")
                            $candidate_experience2[0]["field"] = "Business Development";
                        if ($candidate_experience2[0]["field"] == "2")
                            $candidate_experience2[0]["field"] = "Marketing & Sales";
                        if ($candidate_experience2[0]["field"] == "3")
                            $candidate_experience2[0]["field"] = "Proposal";
                        if ($candidate_experience2[0]["field"] == "4")
                            $candidate_experience2[0]["field"] = "Planing";
                        if ($candidate_experience2[0]["field"] == "5")
                            $candidate_experience2[0]["field"] = "Procurement";
                        if ($candidate_experience2[0]["field"] == "6")
                            $candidate_experience2[0]["field"] = "Logistics";
                        if ($candidate_experience2[0]["field"] == "7")
                            $candidate_experience2[0]["field"] = "Finance";
                        if ($candidate_experience2[0]["field"] == "8")
                            $candidate_experience2[0]["field"] = "Accounting";
                        if ($candidate_experience2[0]["field"] == "9")
                            $candidate_experience2[0]["field"] = "Engineering";
                        if ($candidate_experience2[0]["field"] == "10")
                            $candidate_experience2[0]["field"] = "Construction-Site Works";
                        if ($candidate_experience2[0]["field"] == "11")
                            $candidate_experience2[0]["field"] = "Technical Office";
                        if ($candidate_experience2[0]["field"] == "12")
                            $candidate_experience2[0]["field"] = "Quality";
                        if ($candidate_experience2[0]["field"] == "13")
                            $candidate_experience2[0]["field"] = "Health, Safety, Environment";
                        if ($candidate_experience2[0]["field"] == "14")
                            $candidate_experience2[0]["field"] = "Commissioning";
                        if ($candidate_experience2[0]["field"] == "15")
                            $candidate_experience2[0]["field"] = "Documentation";
                        if ($candidate_experience2[0]["field"] == "16")
                            $candidate_experience2[0]["field"] = "Facilities & Admin";
                        if ($candidate_experience2[0]["field"] == "17")
                            $candidate_experience2[0]["field"] = "Human Resources";
                        if ($candidate_experience2[0]["field"] == "18")
                            $candidate_experience2[0]["field"] = "Legal";
                        if ($candidate_experience2[0]["field"] == "19")
                            $candidate_experience2[0]["field"] = "Corporate Communication";
                        if ($candidate_experience2[0]["field"] == "20")
                            $candidate_experience2[0]["field"] = "Other (Please specify in Short Description field)";

                        array_push($job_list2, $candidate_experience2[0]["field"]);
                        array_push($job_list, $candidate_experience2[0]["field"] . " - " . $candidate_experience2[0]["company"] . " - " . $candidate_experience2[0]["position"]);
                    }
                }

            // language correction
            if (isset($arr["candidate_language"]))
                foreach ($arr["candidate_language"] as $language) {
                    if (is_array($language)) {
                        if ($language["language"] == "1")
                            $language["language"] = "English";
                        if ($language["language"] == "2")
                            $language["language"] = "Arabic";
                        if ($language["language"] == "3")
                            $language["language"] = "Russian";
                        if ($language["language"] == "4")
                            $language["language"] = "French";
                        if ($language["language"] == "5")
                            $language["language"] = "German";
                        if ($language["language"] == "6")
                            $language["language"] = $language["other_language"];

                        array_push($language_list2, $language["language"]);
                        array_push($language_list, $language["language"]);
                    }
                }


            // search for name or lastname or both
            if (!$name_is_empty && $lastname_is_empty && strcasecmp($search_name, $arr["candidate_name"]) == 0) { // search for name
                printPerson($arr, $language_list, $education_list, $job_list);
            } else if (!$lastname_is_empty && $name_is_empty && strcasecmp($search_lastname, $arr["candidate_lastname"]) == 0) { // search for lastname
                printPerson($arr, $language_list, $education_list, $job_list);
            } else if (!$name_is_empty && !$lastname_is_empty && strcasecmp($search_name, $arr["candidate_name"]) == 0 && strcasecmp($search_lastname, $arr["candidate_lastname"]) == 0) { // search for name and lastname 
                printPerson($arr, $language_list, $education_list, $job_list);
            }

            if ($search_gender == "both") { // search for both gender
                if (!$education_is_empty && $job_is_empty && $language_is_empty) { // search for only education level
                    foreach ($_POST['education_level'] as $search_education) {
                        foreach ($education_list2 as $e) {
                            if ($e == $search_education) {
                                printPerson($arr, $language_list, $education_list, $job_list);
                            }
                        }
                    }
                }
                if ($education_is_empty && !$job_is_empty && $language_is_empty) { // search for only job field
                    foreach ($_POST['job_field'] as $search_job) {
                        foreach ($job_list2 as $j) {
                            if ($j == $search_job) {
                                printPerson($arr, $language_list, $education_list, $job_list);
                            }
                        }
                    }
                }
                if ($education_is_empty && $job_is_empty && !$language_is_empty) { // search for only language
                    foreach ($_POST['language'] as $search_language) {
                        foreach ($language_list2 as $l) {
                            if ($l == $search_language || ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other")) {
                                printPerson($arr, $language_list, $education_list, $job_list);
                            }
                        }
                    }
                }
                if (!$education_is_empty && !$job_is_empty && $language_is_empty) { // search for education and job
                    foreach ($_POST['education_level'] as $search_education) {
                        foreach ($_POST['job_field'] as $search_job) {
                            foreach ($education_list2 as $e) {
                                foreach ($job_list2 as $j) {
                                    if ($e == $search_education && $j == $search_job) {
                                        printPerson($arr, $language_list, $education_list, $job_list);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                if (!$education_is_empty && $job_is_empty && !$language_is_empty) { // search for education and language
                    foreach ($_POST['education_level'] as $search_education) {
                        foreach ($_POST['language'] as $search_language) {
                            foreach ($education_list2 as $e) {
                                foreach ($language_list2 as $l) {
                                    if (($e == $search_education && $l == $search_language) || ($e == $search_education && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                        printPerson($arr, $language_list, $education_list, $job_list);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                if ($education_is_empty && !$job_is_empty && !$language_is_empty) { // search for job and language
                    foreach ($_POST['job_field'] as $search_job) {
                        foreach ($_POST['language'] as $search_language) {
                            foreach ($job_list2 as $j) {
                                foreach ($language_list2 as $l) {
                                    if (($j == $search_job && $l == $search_language) || ($j == $search_job && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                        printPerson($arr, $language_list, $education_list, $job_list);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                if (!$education_is_empty && !$job_is_empty && !$language_is_empty) { // search for education, job and language
                    foreach ($_POST['education_level'] as $search_education) {
                        foreach ($_POST['job_field'] as $search_job) {
                            foreach ($_POST['language'] as $search_language) {
                                foreach ($education_list2 as $e) {
                                    foreach ($job_list2 as $j) {
                                        foreach ($language_list2 as $l) {
                                            if (($e == $search_education && $j == $search_job && $search_language == $l) || ($e == $search_education && $j == $search_job && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                                printPerson($arr, $language_list, $education_list, $job_list);
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else if ($search_gender == "1") { // search for gender male
                if ($arr["candidate_gender"] == "Male") {
                    if (!$education_is_empty && $job_is_empty && $language_is_empty) { // search for only education level
                        foreach ($_POST['education_level'] as $search_education) {
                            foreach ($education_list2 as $e) {
                                if ($e == $search_education) {
                                    printPerson($arr, $language_list, $education_list, $job_list);
                                }
                            }
                        }
                    }
                    if ($education_is_empty && !$job_is_empty && $language_is_empty) { // search for only job field
                        foreach ($_POST['job_field'] as $search_job) {
                            foreach ($job_list2 as $j) {
                                if ($j == $search_job) {
                                    printPerson($arr, $language_list, $education_list, $job_list);
                                }
                            }
                        }
                    }
                    if ($education_is_empty && $job_is_empty && !$language_is_empty) { // search for only language
                        foreach ($_POST['language'] as $search_language) {
                            foreach ($language_list2 as $l) {
                                if ($l == $search_language || ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other")) {
                                    printPerson($arr, $language_list, $education_list, $job_list);
                                }
                            }
                        }
                    }
                    if (!$education_is_empty && !$job_is_empty && $language_is_empty) { // search for education and job
                        foreach ($_POST['education_level'] as $search_education) {
                            foreach ($_POST['job_field'] as $search_job) {
                                foreach ($education_list2 as $e) {
                                    foreach ($job_list2 as $j) {
                                        if ($e == $search_education && $j == $search_job) {
                                            printPerson($arr, $language_list, $education_list, $job_list);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (!$education_is_empty && $job_is_empty && !$language_is_empty) { // search for education and language
                        foreach ($_POST['education_level'] as $search_education) {
                            foreach ($_POST['language'] as $search_language) {
                                foreach ($education_list2 as $e) {
                                    foreach ($language_list2 as $l) {
                                        if (($e == $search_education && $l == $search_language) || ($e == $search_education && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                            printPerson($arr, $language_list, $education_list, $job_list);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($education_is_empty && !$job_is_empty && !$language_is_empty) { // search for job and language
                        foreach ($_POST['job_field'] as $search_job) {
                            foreach ($_POST['language'] as $search_language) {
                                foreach ($job_list2 as $j) {
                                    foreach ($language_list2 as $l) {
                                        if (($j == $search_job && $l == $search_language) || ($j == $search_job && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                            printPerson($arr, $language_list, $education_list, $job_list);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (!$education_is_empty && !$job_is_empty && !$language_is_empty) { // search for education, job and language
                        foreach ($_POST['education_level'] as $search_education) {
                            foreach ($_POST['job_field'] as $search_job) {
                                foreach ($_POST['language'] as $search_language) {
                                    foreach ($education_list2 as $e) {
                                        foreach ($job_list2 as $j) {
                                            foreach ($language_list2 as $l) {
                                                if (($e == $search_education && $j == $search_job && $search_language == $l) || ($e == $search_education && $j == $search_job && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                                    printPerson($arr, $language_list, $education_list, $job_list);
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else if ($search_gender == "2") { // search for gender female
                if ($arr["candidate_gender"] == "Female") {
                    if (!$education_is_empty && $job_is_empty && $language_is_empty) { // search for only education level
                        foreach ($_POST['education_level'] as $search_education) {
                            foreach ($education_list2 as $e) {
                                if ($e == $search_education) {
                                    printPerson($arr, $language_list, $education_list, $job_list);
                                }
                            }
                        }
                    }
                    if ($education_is_empty && !$job_is_empty && $language_is_empty) { // search for only job field
                        foreach ($_POST['job_field'] as $search_job) {
                            foreach ($job_list2 as $j) {
                                if ($j == $search_job) {
                                    printPerson($arr, $language_list, $education_list, $job_list);
                                }
                            }
                        }
                    }
                    if ($education_is_empty && $job_is_empty && !$language_is_empty) { // search for only language
                        foreach ($_POST['language'] as $search_language) {
                            foreach ($language_list2 as $l) {
                                if ($l == $search_language || ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other")) {
                                    printPerson($arr, $language_list, $education_list, $job_list);
                                }
                            }
                        }
                    }
                    if (!$education_is_empty && !$job_is_empty && $language_is_empty) { // search for education and job
                        foreach ($_POST['education_level'] as $search_education) {
                            foreach ($_POST['job_field'] as $search_job) {
                                foreach ($education_list2 as $e) {
                                    foreach ($job_list2 as $j) {
                                        if ($e == $search_education && $j == $search_job) {
                                            printPerson($arr, $language_list, $education_list, $job_list);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (!$education_is_empty && $job_is_empty && !$language_is_empty) { // search for education and language
                        foreach ($_POST['education_level'] as $search_education) {
                            foreach ($_POST['language'] as $search_language) {
                                foreach ($education_list2 as $e) {
                                    foreach ($language_list2 as $l) {
                                        if (($e == $search_education && $l == $search_language) || ($e == $search_education && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                            printPerson($arr, $language_list, $education_list, $job_list);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($education_is_empty && !$job_is_empty && !$language_is_empty) { // search for job and language
                        foreach ($_POST['job_field'] as $search_job) {
                            foreach ($_POST['language'] as $search_language) {
                                foreach ($job_list2 as $j) {
                                    foreach ($language_list2 as $l) {
                                        if (($j == $search_job && $l == $search_language) || ($j == $search_job && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                            printPerson($arr, $language_list, $education_list, $job_list);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (!$education_is_empty && !$job_is_empty && !$language_is_empty) { // search for education, job and language
                        foreach ($_POST['education_level'] as $search_education) {
                            foreach ($_POST['job_field'] as $search_job) {
                                foreach ($_POST['language'] as $search_language) {
                                    foreach ($education_list2 as $e) {
                                        foreach ($job_list2 as $j) {
                                            foreach ($language_list2 as $l) {
                                                if (($e == $search_education && $j == $search_job && $search_language == $l) || ($e == $search_education && $j == $search_job && ($l != "English" && $l != "Arabic" && $l != "Russian" && $l != "French" && $l != "German" && $search_language == "Other"))) {
                                                    printPerson($arr, $language_list, $education_list, $job_list);
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function printPerson($arr, $language_list, $education_list, $job_list)
    {
        echo "<b>" . $arr["candidate_name"] . " " .
            $arr["candidate_lastname"] . " </b> || " .
            $arr["candidate_email"] . " || " .
            $arr["candidate_gender"] . " || <br>\n";

        echo "<br><b>\n Languages: </b><br>\n";
        foreach ($language_list as $l) {
            echo $l . "<br>\n";
        }

        echo "<br><b>\n Educations: </b><br>\n";
        foreach ($education_list as $e) {
            echo $e . "<br>\n";
        }

        echo "<br><b>\n Job Fields: </b><br>\n";
        foreach ($job_list as $j) {
            echo $j . "<br>\n";
        }

        echo "________________________________________________________________________________________________________<br>\n";
    }
    ?>
</body>

</html>

<script>
    $(document).ready(function() {
        $('#education_level').multiselect({
            nonSelectedText: 'Select Education Level'

        });
        $('#job_field').multiselect({
            nonSelectedText: 'Select Job Field'
        });
        $('#language').multiselect({
            nonSelectedText: 'Select Language'
        });

    });
    function clearFields(){
        //$("#name, #lastname").val("");
        document.getElementById("name").value = "";
        document.getElementById("lastname").value = "";

    }
</script>