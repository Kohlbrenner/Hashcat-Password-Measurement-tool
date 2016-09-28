<!DOCTYPE html>
<html lang="en">


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
////////////////////////// RANDOM SECURE PASSWORD GENERATOR ////////////////////

// returns a random password of the given length
function rand_Pass($length, $charSet) {
    $result = '';
    for ($i=0; $i < $length; $i++) {
      $result .= random_char($charSet);
    }
    return $result;
}

//array for holding super global values
$options = array('length' => $_GET['length'],
                  'lower' => $_GET['lower'],
                  'upper' => $_GET['upper'],
                  'special' => $_GET['special'],
                  'numbers' => $_GET['numbers']);

function generate_password($options) {

  //NOTE: regular expressions are causing errors where special chars are always considered.
  //HYPOTHESIS: It could be the interpretation of the // and [] chars
  $lower = 'qwertyuioplkjhgfdsazxcvbnm';
  $upper = 'QWERTYUIOPLKJHGFDSAZXCVBNM';
  $special = '!@#$%^&*()-_+=:;?/><';
  $numbers = '1234567890';

  $use_lower = isset($options['lower']) ? $options['lower'] : '0';
  $use_upper = isset($options['upper']) ? $options['upper'] : '0';
  $use_special = isset($options['special']) ? $options['special'] : '0';
  $use_numbers = isset($options['numbers']) ? $options['numbers'] : '0';

  $chars = '';
  if($use_lower == '1') { $chars .= $lower; }
  if ($use_upper == '1') {$chars .= $upper;}
  if ($use_special == '1') {$chars .= $special;}
  if ($use_numbers == '1') {$chars .= $numbers;}

  $length = $options['length'] !== '' ? $options['length'] : '8';
  return rand_Pass($length, $chars);
}

$Gen_Pass = generate_password($options);
?>
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
                <h1>Complex Password Generator</h1>
                

<p>Generate a random secure password using the parameters below: </p>






    <form action="" method="get">

    <div class="form-group">
    <label for="formGroupExampleInput">Length of Password (default 8): </label>
    <input type="text" class="form-control" id="formGroupExampleInput" placeholder=""
    name="length" value="<?php if(isset($_GET['length'])) { echo $_GET['length']; } ?>"/>
  </div>


     <div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="checkbox" name="lower" value="1"<?php if ($_GET['lower'] == 1) {echo 'checked';} ?>>
     Lowercase characters?
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="checkbox" name="upper" value="1"<?php if ($_GET['upper'] == 1) {echo 'checked';} ?>>
     Uppercase characters?
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="checkbox" name="special" value="1"<?php if ($_GET['special'] == 1) {echo 'checked';} ?>>
     Special characters?
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="checkbox" name="numbers" value="1"<?php if ($_GET['numbers'] == 1) {echo 'checked';} ?>>
    Numbers?
  </label>
</div>


    </br>
      <button type="submit" class="btn btn-primary">Submit</button>
      </form>
</br>
</br>
 <font size="4" color="black">Generated Password: 
      <?php if (ctype_digit($_GET['length']) || $_GET['length'] == '') {echo $Gen_Pass;} else { echo 'Only include numeric characters';} ?> </font>


</br>
    <font size="4" color="black">Password Hash (BCRYPT): </font>
      <p><?php echo password_hash($Gen_Pass, PASSWORD_BCRYPT); ?>
      <br />
      <br />
      <br />
      <br /></p>


    <!-- jQuery -->
    <script src="js/jquery.js"></script>

</body>

</html>
