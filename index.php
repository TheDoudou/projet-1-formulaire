<?

if ($_POST) {

    $sdata = array(
        'fname'     => array(   'filter'    => FILTER_CALLBACK,
                                'options'   => 'parseString'
                            ),
        'sname'     => array(   'filter'    => FILTER_CALLBACK,
                                'options'   => 'parseString'
                            ),
        'email'     => FILTER_SANITIZE_EMAIL,
        'country'   => array(   'filter'    => FILTER_CALLBACK,
                                'options'   => 'parseString'
                            ),
        'message'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'genre'     => array(   'filter'    => FILTER_VALIDATE_INT,
                                'options'   => array('min_range' => 1, 'max_range' => 2)
                            ),
        'check1'    => array(   'filter'    => FILTER_VALIDATE_INT,
                                'options'   => array('min_range' => 0, 'max_range' => 1)
                            ),
        'check2'    => array(   'filter'    => FILTER_VALIDATE_INT,
                                'options'   => array('min_range' => 0, 'max_range' => 1)
                            ),
        'check3'    => array(   'filter'    => FILTER_VALIDATE_INT,
                                'options'   => array('min_range' => 0, 'max_range' => 1)
                            ),

    );

    function parseString($s) {
        if (preg_match('/^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\.\-]{2,50}$/i', $s) == 1)
            return htmlspecialchars(trim($s)); // Not realy utile but why not it's for sample
        else
            return false;
    }

    function error_str($e)  // Chaines d'erreur
    {
        $return = null;
        foreach ($e as $k) {
            if($k=='fname')
                $return .= "Veuillez replir convenablement le champ prénom".chr(10);
            else if($k=='sname')
                $return .= "Veuillez replir convenablement le champ nom".chr(10);
            else if($k=='email')
                $return .= "Veuillez replir convenablement le champ E-Mail".chr(10);
            else if($k=='country')
                $return .= "Veuillez replir convenablement le champ pays".chr(10);	
            else if($k=='message')
                $return .= "Veuillez mettre un message".chr(10);	
            else if($k=='genre')
                $return .= "Veuillez choisir un genre convenable".chr(10);
        }
        return $return;
    }

    function error_check($d) {
        $err = [];
        $i = 0;
        foreach ($d as $k => $v) {
            if (array_key_exists($k, $_POST)) {
                if ($v != $_POST[$k] || $v == false) {
                    array_push($err, $k);
                } 
            }
            $i++;
        }

        return (count($err) == 0) ? false : $err; 
    }

    function mailed($d) { // Or PHPMailer ;) but time

        $sujet = $d['check1'].' '.$d['check2'].' '.$d['check3'];
        $message = "Mr ".$d['fname']." ".$d['sname'].", genre :".$d['genre'].", pays".$d['country'].chr(10).$d['message'];
        return mail($d['mail'], $sujet, $message); // Mettre le mail de l'admin du site bien sur

    }

    $spost = filter_input_array(INPUT_POST, $sdata);

    //var_dump($spost);
    //if (error_check($spost)) {
    //    echo error_str(error_check($spost));
    //}

    // Or my satanization GRAAAAAA
    
    $cpost = [];
    $sKey = [   'fname'     => 'String',
                'sname'     => 'String',
                'email'     => 'Email',
                'country'   => 'String',
                'message'   => 'Text',
                'genre'     => 'Int',
                'check1'    => 'Int',
                'check2'    => 'Int',
                'check3'    => 'Int'
            ];
    // Or make array with patern regex, and just one fuction with $string, $sKey and patern regex

    function parseEmail($s) { // Sure it's long but it's rfc ;)
        if (preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $s) == 1)
            return htmlspecialchars(trim($s)); 
    }

    function parseText($s) {
        if (strlen($s) >= 1)
            return htmlspecialchars(trim($s));
        else
            return 'vide';
    }

    function parseInt($s) {
        if (preg_match('/^0|1|2{1}$/', $s) == 1)
            return htmlspecialchars(trim($s));
    }
    
    foreach ($_POST as $k => $v) {
        if (key_exists($k, $sKey))
            $cpost[$k] = call_user_func('parse'.$sKey[$k], $v);
    }

    if ($_POST['api'] == '1') {

        if (error_check($cpost))
            print_r(json_encode(['error' => error_str(error_check($cpost))]));
        else {
            // if (mailed($cpost)) Mail server not active
                print_r(json_encode(['ok' => $cpost]));
        }
        exit;
    }

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hacker Poulette</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>

    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container custom-view">
        <form id="contactForm" method="POST" action="index.php">
            <div class="row">
                <div class="col-6 d-flex justify-content-center align-items-center flex-column">
                    <h2 class="text-center">Formulaire avec sanitization</h2>
                    <img class="img-fluid" src="assets/img/hackers-poulette-logo.png">
                    <div id="return-info">
                    <? if (filter_has_var(INPUT_POST, 'submit')) { ?>
                        <? if (error_check($spost)) { ?>
                            <p class="text-center error" aria-label="Error">
                                <? echo error_str(error_check($spost)); ?>
                            </p>
                        <?  } else { ?>
                            <p class="text-center success" aria-label="Success">
                                Vos inputs sont ok un mail sera peut etre envoyé.
                            </p>
                        <? } ?>
                    <? } ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="fname" id="lfname">Prénom</label>
                        <div class="input-group" data-validate="string">
                            <input type="text" id="fname" name="fname" class="left field form-control" value="<? echo $spost['fname']; ?>" size="10" aria-labelledby="lfname" required>
                            <span class="input-group-addon danger d-flex justify-content-center align-items-center"><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sname" id="lsname">Nom</label>
                        <div class="input-group" data-validate="string">
                            <input type="text" id="sname" name="sname" class="left field form-control" value="<? echo $spost['sname']; ?>" aria-labelledby="lfname" required>
                            <span class="input-group-addon danger d-flex justify-content-center align-items-center"><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" id="lemail">E-Mail</label>
                        <div class="input-group" data-validate="email">
                            <input type="email" id="email" name="email" class="left field form-control" value="<? echo $spost['email']; ?>" aria-labelledby="lemail" required>
                            <span class="input-group-addon danger d-flex justify-content-center align-items-center"><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country" id="lcountry">Pays</label>
                        <div class="input-group" data-validate="string">
                            <input type="text" id="country" name="country" class="left field form-control" value="<? echo $spost['country']; ?>" aria-labelledby="lcountry" required>
                            <span class="input-group-addon danger d-flex justify-content-center align-items-center"><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                    <div class="form-group custom  d-flex justify-content-between">
                        <div class="form-check ">
                            <input class="form-check-input" type="checkbox" value="1" name="check1" id="check1" <? ($spost['check1']) ? print_r('checked') : false; ?> aria-labelledby="s1">
                            <label class="form-check-label" for="check1" id="s1">Sujet 1</label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="checkbox" value="1" name="check2" id="check2" <? ($spost['check2']) ? print_r('checked') : false; ?> aria-labelledby="s2">
                            <label class="form-check-label" for="check2" id="s2">Sujet 2</label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="checkbox" value="1" name="check2" id="check2" <? ($spost['check2']) ? print_r('checked') : false; ?> aria-labelledby="s3">
                            <label class="form-check-label" for="check3" id="s3">Sujet 3</label>
                        </div>
                        <span class="input-group-addon info d-flex justify-content-center align-items-center"><i class="fas fa-star-of-life"></i></span>
                    </div>
                    <div class="form-group">
                        <label for="message" id="lmessage">Message</label>
                        <div class="input-group d-flex mb-2" data-validate="">
                            <textarea name="message" id="message" cols="" class="left field form-control" rows="3" required aria-labelledby="lmessage"><? echo $spost['message']; ?></textarea>
                            <span class="input-group-addon danger d-flex justify-content-center align-items-center"><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="genre" >Genre</label>
                        <div class="input-group" data-validate="number-genre">
                            <select name="genre" id="genre" class="left field form-control" required>
                                <option value="1" <? ($spost['genre'] == 1) ? print_r('selected="selected"') : false; ?> aria-label="Homme">Homme</option>
                                <option value="2" <? ($spost['genre'] == 2) ? print_r('selected="selected"') : false; ?> aria-label="Femme">Femme</option>
                            </select>
                            <span class="input-group-addon danger d-flex justify-content-center align-items-center"><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" id="submit" class="btn btn-primary" role="button" aria-label="Valider">Valider</button>
                        <button type="submit" name="submitAPI" id="submitAPI" class="btn btn-primary" role="button" aria-label="Valider API">Valider API</button>
                    </div>
                </div>
            </div>
        </form><p><?/* echo 'Array Filtre :<br>';
                    var_dump($spost);
                    echo '<br>My sanit :<br>';
                    var_dump($cpost); */?>
                </p>
    </div>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/jquery.validate.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        
    </script>

</body>

</html>