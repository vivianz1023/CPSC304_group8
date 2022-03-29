<!DOCTYPE html>
<html>
    <head>
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
        
        <form method="GET" action="livePlatform.php">
        <p>Learn about the informmation of the LivePlatform, please press:&nbsp; 
            <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Live Platform" name="LivePlatform"></p>
        </form>

        <form method="GET" action="updateMedal.php">
        <p>Update the athletes' medals, please press:&nbsp; 
            <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Update Medals" name="UpdateMedals"></p>
        </form>

    </body>


</html>