<html>
<head>
    <title>Athlete Information</title>
    <style>
        body {
            background-image: url("mainBG.png");
            background-repeat: no-repeat, repeat;
            margin: 30px 30px;
        }
        p {font-family:Gill Sans; font-size:20px;}
    </style>




</head>

<body>

<b style="font-family:cursive; font-size:60px">Athlete Information</b>
<br /><br /><br />
<b style="font-family:Gill Sans; font-size:20px;">Hi, visitor! Press one of the following buttons to look for the Athlete information you wanted!</b>
<br /><br />

<h2>Select Athletes from a Country</h2>
<form method="POST" action="Athlete.php">
    <input style="font-family:Gill Sans; font-size:18px;" type="hidden" id="selectQueryRequest" name="selectQueryRequest">
    <p> Country Name:&nbsp;
        <input style="font-family:Gill Sans; font-size:18px;" type="text" name="countryName"></p>
    <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Submit" name="selectSubmit"></p>
</form>



<h2>Find All Competitions that each Athlete Participates in</h2>


<form method="GET" action="Athlete.php">
    <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
    <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Submit" name="joinSubmit"></p>
</form>



<h2>Find the Number of Specific Medals with Athlete Names in a Selected Country</h2>
<form method="POST" action="Athlete.php">
    <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">
    <p> Country Name: <input style="font-family:Gill Sans; font-size:18px;" type="text" name="countryName"></p>
    <p>Medal Type:
    <select style="font-family:Gill Sans; font-size:18px;" id="medal" name="medal">
        <option value='Gold'>Gold Medal</option>
        <option value='Silver'>Silver Medal</option>
        <option value='Bronze'>Bronze Medal</option>
    </select>
    <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Submit" name="projectionSubmit"></p>
</form>


    <a href="https://www.students.cs.ubc.ca/~lzy0606/mainPage.php">
        <button style="font-family:Gill Sans; font-size:18px;">Back</button>
    </a>



<?php
//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function executeBoundSQL($cmdstr, $list) {
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}


function printAthlete_Country($result) {
    echo "<br><br>";

    echo "<p style='font-size: 20px; font-family:Gill Sans;background-color:lightskyblue; border: 2px solid #ddd; padding: 6px;line-height:40px; display:inline-block;'>Athlete Names</p>";
    echo "<table>";
    echo "<tr>
          <th><p style='font-size: 18px; font-family:Gill Sans;'>Name</p></th>
          <tr>";

    While ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[0] . "</p></td></tr>";
    }

    echo "</table>";

}

function printAthlete_Competition($result) {
    echo "<br> <br>";
    echo "<p style='font-size: 20px; font-family:Gill Sans;background-color:lightskyblue;border: 2px solid #ddd; padding: 6px;line-height:40px; display:inline-block;'>All Athletes and their Competitions</p>";

    echo "<table>";
    echo "<tr>
          <th><p style='font-size: 18px; font-family:Gill Sans;'>Athlete</p></th>
          <th><p style='font-size: 18px; font-family:Gill Sans;'>Competition Event</p></th>
          <tr>";

    While ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[0] . "</p></td>
                  <td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[1] . "</p></td></tr>";
    }

    echo "</table>";
}


function printAthlete_Medal($result) {
    echo "<br> <br>";
    echo "<p style='font-size: 20px; font-family:Gill Sans;background-color:lightskyblue;border: 2px solid #ddd; padding: 6px;line-height:40px; display:inline-block;'>Athletes and their Medal Counts</p>";
    echo "<table>";
    echo "<tr>
          <th><p style='font-size: 18px; font-family:Gill Sans;'>Athlete</p></th>
          <th><p style='font-size: 18px; font-family:Gill Sans;'>Medal Counts</p></th>
          <tr>";

    While ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[0] . "</p></td>
                  <td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[1] . "</p></td></tr>";
    }

    echo "</table>";
}


function connectToDB() {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_yzhu33", "a38246906", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}

//SELECT
function handleSelectRequest() {
    global $db_conn;
    $country = $_POST['countryName'];
    $check = executePlainSQL("SELECT OTMS.Country 
                                     FROM OlympicTeamMember_Sponsor OTMS, Athlete A
                                     WHERE OTMS.ID = A.ID AND OTMS.Country = '". $country ."'");
    if (OCI_Fetch_Array($check,OCI_BOTH)[0] == NULL){
        echo "<br> <br>";
        echo "<font color='red' face='Bradley Hand' size='5'><br />&nbsp; Invalid Country Name. No Relative Information!</font>";
    } else {
        $result = executePlainSQL("SELECT DISTINCT SN.Name FROM OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN, Athlete A WHERE OTMS.ID = A.ID and OTMS.SinNumOrEqual = SN.SinNumOrEqual and OTMS.Country='" . $country . "'");
        if ($result != false) {
            printAthlete_Country($result);
        }
    }
    OCICommit($db_conn);
}





//JOIN
function handleJoinQueryRequest() {
    global $db_conn;
    $result = executePlainSQL(
        "SELECT SN.Name, CE.CompetitionEvent_Name
                    FROM Participate p
                    JOIN OlympicTeamMember_Sponsor OTMS
                    ON p.Athlete_ID = OTMS.ID
                    JOIN SinNumOrEqual_Name SN
                    ON SN.SinNumOrEqual = OTMS.SinNumOrEqual
                    JOIN CompetitionEvent CE
                    ON CE.ID = p.CompetitionEvent_ID");
    printAthlete_Competition($result);
    OCICommit($db_conn);
}

//PROJECTION
function handleProjectionQueryRequest() {
    global $db_conn;
    $country = $_POST['countryName'];
    $medal = $_POST['medal'];
    $check = executePlainSQL("SELECT OTMS.Country 
                                     FROM OlympicTeamMember_Sponsor OTMS, Athlete A
                                     WHERE OTMS.ID = A.ID AND OTMS.Country = '". $country ."'");
    if (OCI_Fetch_Array($check,OCI_BOTH)[0] == NULL){
        echo "<br> <br>";
        echo "<font color='red' face='Bradley Hand' size='5'><br />&nbsp; Invalid Country Name. No Relative Information!</font>";
    } else {
        if ($medal == "Gold") {
            $result = executePlainSQL("SELECT SN.Name, A.GoldNumber
                                      FROM Athlete A, OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN
                                      WHERE A.ID = OTMS.ID AND OTMS.SinNumOrEqual = SN.SinNumOrEqual AND OTMS.Country = '" . $country . "'");
        } elseif ($medal == "Silver") {
            $result = executePlainSQL("SELECT SN.Name, A.SilverNumber
                                      FROM Athlete A, OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN
                                      WHERE A.ID = OTMS.ID AND OTMS.SinNumOrEqual = SN.SinNumOrEqual AND OTMS.Country = '" . $country . "'");
        } else{
            $result = executePlainSQL("SELECT SN.Name, A.BronzeNumber
                                      FROM Athlete A, OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN
                                      WHERE A.ID = OTMS.ID AND OTMS.SinNumOrEqual = SN.SinNumOrEqual AND OTMS.Country = '" . $country . "'");
        }
        if ($result != false) {
            printAthlete_Medal($result);
        }
    }

//    $result = executePlainSQL("SELECT DISTINCT SN.Name FROM OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN, Athlete A WHERE OTMS.ID = A.ID and OTMS.SinNumOrEqual = SN.SinNumOrEqual and OTMS.Country='" . $country . "'");




//    printAthlete_Medal($result);
    OCICommit($db_conn);
}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('selectQueryRequest', $_POST)) {
            handleSelectRequest();
        } elseif (array_key_exists('projectionQueryRequest', $_POST)) {
            handleProjectionQueryRequest();
        }

    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('joinQueryRequest', $_GET)) {
            handleJoinQueryRequest();
        }

        disconnectFromDB();
    }
}

if (isset($_POST['selectSubmit']) || isset($_POST['projectionSubmit'])) {
    handlePOSTRequest();
} else if (isset($_GET['joinSubmit'])) {
    handleGETRequest();
}
?>
</body>
</html>

