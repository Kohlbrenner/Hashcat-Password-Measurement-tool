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







////////////////////////// HUMAN READABLE PASSWORD GENERATOR ///////////////////

$basic_words = read_dictionary('friendly_words.txt');
$brand_words = read_dictionary('brand_words.txt');
$words = array_merge($brand_words, $basic_words);

function pick_random_filler($length) {
  $special = '!@#$%&=?';
  $numbers = '0123456789';
  $set = $special . $numbers;
  $result = '';

  for ($i=0; $i < $length; $i++) { $result .= $set[random_int(0, strlen($set))];}
  return $result;
}

function pick_random_word($array) {
  //random_int is crypto secure num gen in php 7
  //mt_rand is fastest random num gen in current php build
  $i = random_int(0, count($array) -1);
  return $array[$i];
}

function generate_human_password($num_words, $wordlist) {
  $password= '';
  for ($i = 0; $i < $num_words; $i++) {
    $pass = pick_random_word($wordlist);
    $filler = pick_random_filler(random_int(1, 3));
    $password .= $pass;
    $password .= $filler;
  } return $password;
}

$Hum_Pass = generate_human_password($_GET['words'], $words);


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

<h2>Dictionary Password Creator</h2>

<p>Generates a random password that is "Human readable" i.e. it inserts dictionary words into the password.

</p>
</br>


<form>


    <div class="form-group">
    <label for="formGroupExampleInput">Number of Words: </label>
    <input type="text" name="words" class="form-control" id="words" value="<?php if (isset($_GET['words'])) { echo $_GET['words']; } ?>">
    </div>

       <button type="submit" class="btn btn-primary">Submit</button>
    </form>



</br>
    <font size="4" color="black">Readable Password: 
      <?php if (ctype_digit($_GET['words'])) {echo "$Hum_Pass";} else { echo 'Please include only include numeric characters';} ?>  </font>
</br>
    <font size="4" color="black">Password Hash (BCRYPT): 
      <?php echo password_hash("$Hum_Pass", PASSWORD_BCRYPT); ?> </font>




      <br />
      <br />
      <br />
      <br />
      <br /></p>

            </div>
        </div>




    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

</body>

</html>
