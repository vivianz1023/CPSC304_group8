<!DOCTYPE html>
<html>

<head>
    <br />
    <title>UpdateMedals Page</title>
    <style>
        body {
            background-image: url("mainBG.png");
            background-repeat: no-repeat, repeat;
            margin: 10px 30px;
        }
        p {font-family: Gill Sans; font-size: 20px;}
        TD{font-family: Gill Sans; font-size: 18px; 
            background-color:#bee6ff; border: 2px solid #ddd; padding: 6px;}
        Th{font-family: Gill Sans; font-size: 18px;
            background-color:#bee6ff; border: 2px solid #ddd; padding: 6px;}
    </style>
</head>

<body>
    <b style="font-family:cursive; font-size:60px">Update Medals Operating Deck</b>
    <br /><br /><br />
    <b style="font-family:Gill Sans; font-size:20px;">Hi, visitor! Please choose the operations as follows!</b>
    <br /><br /><br />

    <form style="font-family:Gill Sans; font-size:20px;" 
    method="POST" action="updateMedal.php">
        <input type="hidden" id="updateMedal" name="updateMedal">
        Athlete's ID: &nbsp; <input type="text" name="athleteID"><br /><br />
        New medal number: &nbsp; <input type="text" name="newNum"> &nbsp;(>=0)<br /><br />

        <select style="font-family:Gill Sans; font-size:16px;" name="medalTypes" >MedalTypes
        <option value="Choose">-- Choose Medal Types --</option>
        <option value="Gold">Gold Medal</option>
        <option value="Silver">Silver Medal</option>
        <option value="Bronze">Bronze Medal</option>
        </select>&nbsp;&nbsp;
       
        <input style="font-family:Gill Sans; font-size:18px;" 
        type="submit" value="Submit" name="UpdateSubmit"></p>
    </form>
    <br />


    <form method="GET" action="https://www.students.cs.ubc.ca/~lzy0606/mainPage.php">
        <input style="font-family:Gill Sans; font-size:18px;" 
        type="submit" value="Back" name="Back"></p>
    </form>


<?php
    $success = True;
    $db_conn = NULL; 
    $show_debug_alert_messages = False; 

    function debugAlertMessage($message)
    {
        global $show_debug_alert_messages;

        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
    }

    function executePlainSQL($cmdstr)
    {   global $db_conn, $success;

        $statement = OCIParse($db_conn, $cmdstr);
        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn); 
            echo htmlentities($e['message']);
            $success = False;
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); 
            echo htmlentities($e['message']);
            $success = False;
        }

        return $statement;
    }

    function printResult($result)
     {  
         $current = OCI_Fetch_Array($result, OCI_BOTH);

         if ($current[0] == NULL){
             echo "<font color='green' face='Bradley Hand' size='5'><br /> Sorry! No such Competition Events.</font>";

         } else {
             echo "<table>";
             echo "<tr><th>ID</th>&nbsp;&nbsp;&nbsp;&nbsp;<th>GoldNumber</th>&nbsp;&nbsp;&nbsp;&nbsp;
         <th>SilverNumber</th>&nbsp;&nbsp;&nbsp;&nbsp;<th>BronzeNumber</th></tr>";
             echo "<tr><td>" . $current["ID"] . "</td>&nbsp;&nbsp;&nbsp;&nbsp;<td>" . $current["GOLDNUMBER"] . "</td> &nbsp;&nbsp;&nbsp;&nbsp;<td>" . $current["SILVERNUMBER"] . "</td>&nbsp;&nbsp;&nbsp;&nbsp; <td>" . $current["BRONZENUMBER"] . "</td></tr>";
             while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["ID"] . "</td>&nbsp;&nbsp;&nbsp;&nbsp;<td>" . $row["GOLDNUMBER"] . "</td> &nbsp;&nbsp;&nbsp;&nbsp;<td>" . $row["SILVERNUMBER"] . "</td>&nbsp;&nbsp;&nbsp;&nbsp; <td>" . $row["BRONZENUMBER"] . "</td></tr>";
    
         }
         echo "</table>";

        }
    }
    

    function connectToDB()
    {
        global $db_conn;

        $db_conn = OCILogon("ora_lzy0606", "a71133417", "dbhost.students.cs.ubc.ca:1522/stu");

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

    function disconnectFromDB()
    {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        OCILogoff($db_conn);
    }


    function handleUpdateRequest()
    {
        global $db_conn;
        $chosenAthleteID = $_POST['athleteID'];
        $num = $_POST['newNum'];
        $modelFunction = $_POST['medalTypes'];

        if (is_numeric($num) == 1 && intval($num)>=0 ){
            $chosenNewNum = intval($num);

            $check = executePlainSQL("SELECT ID 
                                  FROM   Athlete
                                  WHERE  ID = '" . $chosenAthleteID . "'");

        if (OCI_Fetch_Array($check, OCI_BOTH)[0] == NULL){
            echo "<font color='red' face='Bradley Hand' size='5'><br />&nbsp; Warning: Invalid athlete ID. Please try again.</font>";
        } 



        else {

            if ($modelFunction == 'Choose'){
            echo "<font color='red' face='Bradley Hand' size='5'><br />&nbsp; Warning: Please choose a medal type.</font>";
        } else {

            $before = executePlainSQL("SELECT * 
                                       FROM Athlete 
                                       WHERE ID = '" . $chosenAthleteID . "'");

        if ($before != false) {
            echo "<font color='green' face='Bradley Hand' size='5'>Before updating: <br /></font>";
            printResult($before);
        }
    
        if ($modelFunction == 'Gold'){
            $plan = "UPDATE Athlete 
                     SET    GoldNumber = $chosenNewNum
                     WHERE  ID = '" . $chosenAthleteID . "'";    
            executePlainSQL($plan);
            $change = true;
        } else if ($modelFunction == 'Silver'){
            $plan = "UPDATE Athlete 
                     SET    SilverNumber = $chosenNewNum
                     WHERE  ID = '" . $chosenAthleteID . "'";
            executePlainSQL($plan);
            $change = true;
        } else if ($modelFunction == 'Bronze'){
            $plan = "UPDATE Athlete 
                     SET    BronzeNumber = $chosenNewNum 
                     WHERE  ID = '" . $chosenAthleteID . "'";
            executePlainSQL($plan);
            $change = true;
        } 

        $after = executePlainSQL("SELECT * 
                                  FROM Athlete 
                                  WHERE ID = '" . $chosenAthleteID . "'");

        if ($change == true) {
            echo "<font color='green' face='Bradley Hand' size='5'><br />After updating: <br /></font>";
            printResult($after);
        }
        

    }}
        } else {
            echo "<font color='red' face='Bradley Hand' size='5'><br />&nbsp; Warning: Invalid number. Please try again.</font>";
        }
        

        OCICommit($db_conn);
        }


    function handlePOSTRequest()
    {
        if (connectToDB()) {
            if (array_key_exists('UpdateSubmit', $_POST)) {
                handleUpdateRequest();
            }
            disconnectFromDB();
        }
    }

    if (isset($_POST['updateMedal']) ) {
        handlePOSTRequest();
    }





    ?> 

<body>
<html>