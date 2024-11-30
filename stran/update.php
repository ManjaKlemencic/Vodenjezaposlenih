<?php

require_once "config.php";


$ime = $priimek = $e_naslov = $tel_stevilka = "";
$ime_err = $priimek_err = $e_naslov_err = $tel_stevilka_err = "";


if (isset($_POST["id_delavec"]) && !empty($_POST["id_delavec"])) {

    $id = $_POST["id_delavec"];

    $input_ime = trim($_POST["ime"]);
    if (empty($input_ime)) {
        $ime_err = "Vnesite ime.";
    } elseif (!filter_var($input_ime, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $ime_err = "Vnesite veljavno ime.";
    } else {
        $ime = $input_ime;
    }

    
    $input_priimek = trim($_POST["priimek"]);
    if (empty($input_priimek)) {
        $priimek_err = "Vnesite priimek.";
    } elseif (!filter_var($input_priimek, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $priimek_err = "Vnesite veljaven priimek.";
    } else {
        $priimek = $input_priimek;
    }

   
    $input_e_naslov = trim($_POST["e_naslov"]);
    if (empty($input_e_naslov)) {
        $e_naslov_err = "Vnesite email.";
    } elseif (!filter_var($input_e_naslov, FILTER_VALIDATE_EMAIL)) {
        $e_naslov_err = "Vnesite veljaven email.";
    } else {
        $e_naslov = $input_e_naslov;
    }

  
    $input_tel_stevilka = trim($_POST["tel_stevilka"]);
    if (empty($input_tel_stevilka)) {
        $tel_stevilka_err = "Vnesite telefonsko številko.";
    } elseif (!ctype_digit($input_tel_stevilka)) {
        $tel_stevilka_err = "Vnesite veljavno telefonsko številko.";
    } else {
        $tel_stevilka = $input_tel_stevilka;
    }

   
    if (empty($ime_err) && empty($priimek_err) && empty($e_naslov_err) && empty($tel_stevilka_err)) {
        
        $sql = "UPDATE delavec SET ime=?, priimek=?, e_naslov=?, tel_stevilka=? WHERE id_delavec=?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
           
            mysqli_stmt_bind_param($stmt, "ssssi", $param_ime, $param_priimek, $param_e_naslov, $param_tel_stevilka, $param_id);

         
            $param_ime = $ime;
            $param_priimek = $priimek;
            $param_e_naslov = $e_naslov;
            $param_tel_stevilka = $tel_stevilka;
            $param_id = $id;

          
            if (mysqli_stmt_execute($stmt)) {
               
                header("location: index.php");
                exit();
            } else {
                echo "Napaka. Prosim poskusite pozneje.";
            }
        } else {
           
            echo "Error preparing statement: " . mysqli_error($link);
        }

      
        if (isset($stmt) && $stmt) {
            mysqli_stmt_close($stmt);
        }
    }

    
    mysqli_close($link);
} else {
   
    if (isset($_GET["id_delavec"]) && !empty(trim($_GET["id_delavec"]))) {
       
        $id = trim($_GET["id_delavec"]);

       
        $sql = "SELECT ime, priimek, e_naslov, tel_stevilka FROM delavec WHERE id_delavec = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
     
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            $param_id = $id;

            
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    
                    $ime = $row["ime"];
                    $priimek = $row["priimek"];
                    $e_naslov = $row["e_naslov"];
                    $tel_stevilka = $row["tel_stevilka"];
                } else {
                    
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Napaka. Prosim poskusite pozneje.";
            }
        }

  
        if (isset($stmt) && $stmt) {
            mysqli_stmt_close($stmt);
        }
    } else {
        
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Uredi</h2>
                    </div>
                   <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($ime_err)) ? 'has-error' : ''; ?>">
                            <label>Ime</label>
                            <input type="text" name="ime" class="form-control" value="<?php echo $ime; ?>">
                            <span class="help-block"><?php echo $ime_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($priimek_err)) ? 'has-error' : ''; ?>">
                            <label>Priimek</label>
                            <input type="text" name="priimek" class="form-control" value="<?php echo $priimek; ?>">
                            <span class="help-block"><?php echo $priimek_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($e_naslov_err)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <input type="text" name="e_naslov" class="form-control" value="<?php echo $e_naslov; ?>">
                            <span class="help-block"><?php echo $e_naslov_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($tel_stevilka_err)) ? 'has-error' : ''; ?>">
                            <label>Telefonska številka</label>
                            <input type="text" name="tel_stevilka" class="form-control" value="<?php echo $tel_stevilka; ?>">
                            <span class="help-block"><?php echo $tel_stevilka_err;?></span>
                        </div>
                        <input type="hidden" name="id_delavec" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Shrani">
                        <a href="index.php" class="btn btn-default">Prekliči</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
