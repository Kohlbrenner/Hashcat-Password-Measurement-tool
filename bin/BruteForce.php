<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PHP Password Utility</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/half-slider.css" rel="stylesheet">

    <style>
    #meter div {
    height: 20px; width: 20px;
    margin: 0 1px 0 0; padding: 0;
    float: left;
    background-color: #DDDDDD;
  }
  #meter div.rating-1, #meter div.rating-2 {
    background-color: red;
  }
  #meter div.rating-3, #meter div.rating-4 {
    background-color: orange;
  }
  #meter div.rating-5, #meter div.rating-6 {
    background-color: yellow;
  }
  #meter div.rating-7, #meter div.rating-8 {
    background-color: blue;
  }
  #meter div.rating-9, #meter div.rating-10 {
    background-color: green;
  }
    </style>


<?php
///////////////////////////// HELPER FUNCTIONS /////////////////////////////////

function detect_any_uppercase($string) {
  return strtolower($string) != $string;
}

function detect_any_lowercase($string) {
  return strtoupper($string) != $string;
}

function count_numbers($string) {
  return preg_match_all('/[0-9]/', $string);
}

function count_symbols($string) {
  return preg_match_all('/[\W]/', $string);
}

function random_char($string) {
  $i = mt_rand(0, strlen($string)-1);
  return $string[$i];
}

function read_dictionary($filename="") {
  $disctionary_file = "dictionaries/{$filename}";
  return file($disctionary_file,  FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

/////////////////////// PHP PASSWORD CALCULATIONS /////////////////////

//converts the number of seconds to a more human-friendly form
function secondsToStr($seconds) {

  $result = '';
  $realSec = floor($seconds);

  $numyears = number_format(floor($realSec / 31536000));
  if ($numyears > 0) {
    $result .= "{$numyears}" . ' year' . (($numyears > 1) ? 's, ' : ', ');
  }

  $numdays = floor(($seconds % 31536000) / 86400);
  if ($numdays > 0) {
    $result .= "{$numdays}" . ' day' . (($numdays > 1) ? 's, ' : ', ');
  }

  $numhours = floor((($seconds % 31536000) % 86400) / 3600);
  if ($numhours > 0) {
    $result .= "{$numhours}" . ' hour' . (($numhours > 1) ? 's, ' : ', ');
  }

  $numminutes = floor(((($seconds % 31536000) % 86400) % 3600) / 60);
  if ($numminutes > 0) {
    $result .= "{$numminutes}" . ' minute' . (($numminutes > 1) ? 's, ' : ', ');
  }

  $numseconds = floor((($seconds % 31536000) % 86400) % 3600) % 60;
  if ($numseconds > 0) {
    $result .= "{$numseconds}" . ' second' . (($numseconds > 1) ? 's' : '');
  }

  if ($result == '') { $result = "Less than a second."; return $result; }
  else {return $result;}
}

// return the time it would take to brute force password with different hash algs
function strengthCalculator($password, $cores) {

  $bad_input = FALSE;
  if (strlen($password) == 0 || $cores == 0) { $bad_input = TRUE; }

  $character_set_size = 0;

  $lower = '/[a-z]/';
  $upper = '/[A-Z]/';
  $special = '/[\W]/';
  $numbers = '/[0-9]/';

  if (detect_any_uppercase($password)) {$character_set_size += 26;}
  if (detect_any_lowercase($password)) {$character_set_size += 26;}
  if (count_symbols($password) > 0) {$character_set_size += 25;}
  if (count_numbers($password) > 0) {$character_set_size += 10;}

  $password_strength = pow($character_set_size, strlen($password));

  //cracker speeds of slowest computer at http://hashcat.net/oclhashcat-plus/
  //NOTE: these are minimum speeds.
  $rateMD5 = 1333000000;
  $rateSHA1 = 433000000;
  $rateMD5crypt = 855000;
  $rateBCRYPT = 604;

  //calculate cracking time and save important vars in array
  $timeMD5 = secondsToStr($password_strength/($rateMD5 * $cores));
  $timeSHA1 = secondsToStr($password_strength/($rateSHA1 * $cores));
  $timeMD5crypt = secondsToStr($password_strength/($rateMD5crypt * $cores));
  $timeBCRYPT = secondsToStr($password_strength/($rateBCRYPT * $cores));
  $display =  array('md5' => $timeMD5,
                    'sha1' => $timeSHA1,
                    'md5Crypt' => $timeMD5crypt,
                    'bcrypt' => $timeBCRYPT,
                    'bad_input' => $bad_input);
    return $display;
}

///////////////////////////// PASSWORD STRENGTH METER //////////////////////////

function password_strength_meter($password) {
  $strength = 0;
  $possible_points = 12;
  $length = strlen($password);

  if (detect_any_uppercase($password)) { $strength += 1; }
  if (detect_any_lowercase($password)) { $strength += 1; }

  $strength += min(count_numbers($password), 2);
  $strength += min(count_symbols($password), 2);

  if ($length >= 8) {
    $strength += 2;
    $strength += min(($length - 8) * 0.5, 4);
  }

  $strength_percent = $strength / (float) $possible_points;
  $rating = floor($strength_percent * 10);
  return $rating;
}

$pass_meter = $_POST['rate'];
$pass_meter_rating = password_strength_meter($pass_meter);

?>

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Readme</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="BruteForce.php">Brute Force Calculator</a>
                    </li>
                    <li>
                        <a href="Complex.php">Complex Passwords</a>
                    </li>
                    <li>
                        <a href="Dictionary.php">Dictionary Passwords</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

    </br>
    </br>
    </br>

        <div class="row">
            <div class="col-lg-12">
                <h1>Password Cracking Calculator</h1>
<p>
The main utility is a password strength meter. This meter uses the minimum cracking speeds found on the <a href="http://hashcat.net/oclhashcat-plus/">hashcat website</a> where they used a rainbow table type attack based on known hashes. 



 and uses those speeds along with a point value system to measure the "strength" of the password according to the time it would take to brute force the hash value of the password. 

The goal of this project is to learn PHP. There now exist better ways of calculating the brute force times. </p>
<p>

* As of now, the article based on these results has been moved.
</p>
            </div>
        </div>

 <p>Rate the strength of your password: </p>

<form action="" method="post">

    <div class="form-group">
    <label for="formGroupExampleInput">Password: </label>
    <input type="text" name="rate" class="form-control" id="words" value="">
    <label for="formGroupExampleInput">Number of computers: </label>
    <input type="text" name="threads" class="form-control" value="">
    </div>

       <button type="submit" class="btn btn-primary">Submit</button>

    </form>

   </br>


      <h2>Password Strength Meter</h2>

      <font size="4" color="black">Your password rating is: <?php echo $pass_meter_rating; ?> </font>


      <div id="meter">
        <?php
          for($i=0; $i < 10; $i++) {
            echo "<div";
            if ($pass_meter_rating > $i) {
              echo " class=\"rating-{$pass_meter_rating}\"";
            }
            echo "></div>";
          }
        ?>
        </div>
        </br>
     <font size="4" color="black">Time to crack password: 
     <?php echo $_POST['rate']; ?>   </font>
</br>

     <font size="4" color="black">Using: 
     <?php echo $_POST['threads']; ?>  computer(s): </font>

        <?php
        $getPass = $_POST['rate'];
        $getThreads = $_POST['threads'];
        $refCalc = strengthCalculator($getPass, $getThreads);
        ?>
        </br>

         <font size="4" color="black">MD5: <?php echo $refCalc['md5']; ?></font>
           </br>

      <font size="4" color="black">SHA1: <?php echo $refCalc['md5']; ?> </font>
        </br>

      <font size="4" color="black">MD5Crypt: <?php echo $refCalc['md5Crypt']; ?></font>
        </br>

      <font size="4" color="black">BCRYPT: <?php echo $refCalc['bcrypt']; ?> </font>
</br>
</br>
</br>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

</body>

</html>
