<!DOCTYPE html>
<html>
<head>
    <script src="https://kit.fontawesome.com/f41258b310.js" crossorigin="anonymous"></script>


    <title>MainPage Information</title>
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
<b style="font-family:cursive; font-size:60px">Olympic Information Menu Page</b>
<br /><br /><br />
<b style="font-family:Gill Sans; font-size:20px;">Hi, visitor! Press one of the following buttons to looking for the Olympic information you wanted!</b>
<br /><br />

<form method="GET" action="https://www.students.cs.ubc.ca/~xnie0204/VolunteerPage.php">
    <p><i class="fa-solid fa-handshake-angle" style="font-size:35px;color:lightskyblue" ></i>&nbsp;&nbsp;Learn about the information of the Volunteer, please press:&nbsp;
        <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Volunteer" name="Volunteer"></p>
</form>

<form method="GET" action="https://www.students.cs.ubc.ca/~lzy0606/livePlatform.php">
    <p><i class="fa-solid fa-tv" style="font-size:35px;color:moccasin"></i>&nbsp;&nbsp;Learn about the information of the LivePlatform, please press:&nbsp;
        <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Live Platform" name="LivePlatform"></p>
</form>
<form method="GET" action="https://www.students.cs.ubc.ca/~lzy0606/updateMedal.php">
    <p><i class="fa-solid fa-medal" style="font-size:35px;color:palegreen"></i>&nbsp;&nbsp;Update the athletes' medals, please press:&nbsp;
        <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Update Medals" name="UpdateMedals"></p>
</form>
<p><i class="fa-solid fa-person-skating" style="font-size:35px;color:lightpink"></i>&nbsp;&nbsp;Get information of Athletes, please press:&nbsp;
    <a href="https://www.students.cs.ubc.ca/~yzhu33/Athlete.php">
        <button style="font-family:Gill Sans; font-size:18px;">Athlete</button>
    </a>
</p>
<p><i class="fa-solid fa-building" style="font-size:35px;color:cornflowerblue"></i>&nbsp;&nbsp;Find Max Amount of Money Support by the Sponsor, please press:&nbsp;
    <a href="https://www.students.cs.ubc.ca/~yzhu33/Sponsor.php">
        <button style="font-family:Gill Sans; font-size:18px;">Sponsor</button>
    </a>
</p>

<p><i class="fa-solid fa-circle-info" style="font-size:35px;color:mediumaquamarine"></i>&nbsp;&nbsp;Find Extra information , please press:&nbsp;
    <a href="https://www.students.cs.ubc.ca/~xnie0204/Extra.php">
        <button style="font-family:Gill Sans; font-size:18px;">Extra</button>
    </a>
</p>
</body>
</html>
