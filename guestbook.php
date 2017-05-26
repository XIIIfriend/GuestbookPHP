
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- link rel="icon" href="../../favicon.ico" -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Sulzer Guestbook</title>
    <![endif]-->
</head>

<body>

<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="index.php">Home</a></li>
                <li role="presentation"><a href="#">About</a></li>
                <li role="presentation"><a href="#">Contact</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Sulzer Guestbook</h3>
    </div>

    <div class="jumbotron">
        <h1>Our guestbook:</h1>
    </div>


    <!-- Here comes the text!  -->
    <?php

    if (isset($_POST["commit"])) {

        //Textfeldeingaben filtern
        function daten_reiniger($inhalt)
        {
            if (!empty($inhalt)) {
                //HTML- und PHP-Code entfernen.
                $inhalt = strip_tags($inhalt);
                //Umlaute und Sonderzeichen in
                //HTML-Schreibweise umwandeln
                $inhalt = htmlentities($inhalt);
                //Entfernt überflüssige Zeichen
                //Anfang und Ende einer Zeichenkette
                $inhalt = trim($inhalt);
                //Backslashes entfernen
                $inhalt = stripslashes($inhalt);
            }
            return $inhalt;
        }

        foreach ($_POST as $key => $element) {
            //Dynamische Variablen erzeugen, wie g_fname, etc.
            //und die Eingaben Filtern
            ${"g_" . $key} = daten_reiniger($element);
        }


        if(strlen($g_fname) <3 || strlen($g_finhalt)<3) {
            $eintrag="";
        }
        else {
            $g_fdatum = date("Y-m-d H:i:s");
            $g_finhalt = nl2br($g_finhalt);
            $eintrag = " 
            <div class=\"form-group\">
            <label>Datum: </label>
            $g_fdatum
            <p/>
            <label>Name:</label>
            $g_fname
            <p/>
            <label>Email:</label>
            $g_femail
            <p/>
            <label>Comment:</label>
            $g_finhalt
            <p/>
            <hr>
            </div>";
        }
    }

    // Buchdatei
    $datei = "buch_inhalt.htm";

        if (file_exists($datei)) {
            // Falls die Datei existiert, wird sie ausgelesen und
            // die enthaltenen Daten werden durch den neuen Beitrag
            // ergänzt
            $fp=fopen($datei,"r+");
            $daten=fread($fp,filesize($datei));
            rewind($fp);
            flock($fp,2);
            fputs($fp,"$eintrag \n $daten");
            flock($fp,3);
            fclose($fp);
            echo "$eintrag \n $daten" ;
            //include("autorespond.php");
            //header("Location:buch.php");
        }else if (!file_exists($datei) && isset($_POST["commit"])) {
            // Die Datei buch_inhalt.htm existiert nicht, sie wird
            // neu angelegt und mit dem aktuellen Beitrag gespeichert.
            $fp=fopen($datei,"w");
            fputs($fp,"$eintrag \n");
            fclose($fp);
            echo $eintrag;
            //header("Location:buch.php");
        }
    ?>

    <form method='post' action='guestbook.php'>
        <div class="form-group">
            <label for="usr">Name:</label>
            <input type="text" name="fname" class="form-control" id="usr">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" name="femail" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea name="finhalt" class="form-control" rows="5" id="comment"></textarea>
        </div>

        <button input type='submit' type="button" class="btn" name="commit" value="commit"> Commit</button>

        <footer class="footer">
        <p>&copy; 2017 Sulzer, GmbH</p>
    </footer>
    </form>

</div> <!-- /container -->

</body>
</html>