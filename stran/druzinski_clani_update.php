<?php

require_once "config.php";

$ime = $priimek = $sorodstveni_polozaj = "";
$ime_err = $priimek_err = $sorodstveni_polozaj_err = "";

$param_id_delavec = isset($_GET["id_delavec"]) ? trim($_GET["id_delavec"]) : '';
$param_id_druzinski_clani = isset($_GET["id_druzinski_clani"]) ? trim($_GET["id_druzinski_clani"]) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $input_ime = trim($_POST["ime"]);
    if (empty($input_ime)) {
        $ime_err = "Vnesite ime.";
    } else {
        $ime = $input_ime;
    }

    $input_priimek = trim($_POST["priimek"]);
    if (empty($input_priimek)) {
        $priimek_err = "Vnesite priimek.";
    } else {
        $priimek = $input_priimek;
    }

    $input_sorodstveni_polozaj = trim($_POST["sorodstveni_polozaj"]);
    if (empty($input_sorodstveni_polozaj)) {
        $sorodstveni_polozaj_err = "Vnesite sorodstveni položaj.";
    } else {
        $sorodstveni_polozaj = $input_sorodstveni_polozaj;
    }

    if (empty($ime_err) && empty($priimek_err) && empty($sorodstveni_polozaj_err)) {
       
        $sql = "UPDATE druzinski_clani SET ime = ?, priimek = ?, sorodstveni_polozaj = ? WHERE id_druzinski_clani = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            
            mysqli_stmt_bind_param($stmt, "sssi", $param_ime, $param_priimek, $param_sorodstveni_polozaj, $param_id_druzinski_clani);

            $param_ime = $ime;
            $param_priimek = $priimek;
            $param_sorodstveni_polozaj = $sorodstveni_polozaj;
            $param_id_druzinski_clani = $param_id_druzinski_clani;

           
            if (mysqli_stmt_execute($stmt)) {
                
                header("location: druzinski_clani.php?id_delavec=" . $param_id_delavec);
                exit();
            } else {
                echo "Nekaj je šlo narobe. Poskusite znova.";
            }
        }

       
        mysqli_stmt_close($stmt);
    }

   
    mysqli_close($link);
} else {
   
    if (isset($param_id_druzinski_clani) && !empty(trim($param_id_druzinski_clani))) {
      
        $sql = "SELECT * FROM druzinski_clani WHERE id_druzinski_clani = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
        
            mysqli_stmt_bind_param($stmt, "i", $param_id_druzinski_clani);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                  
                    $ime = $row["ime"];
                    $priimek = $row["priimek"];
                    $sorodstveni_polozaj = $row["sorodstveni_polozaj"];
                } else {
                   
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($link);
            }
        }

     
        mysqli_stmt_close($stmt);
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
    <title>Posodobi družinskega člana</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper { width: 500px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Posodobi družinskega člana</h2>
        <p>Uredite podatke družinskega člana.</p>
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
            <div class="form-group <?php echo (!empty($sorodstveni_polozaj_err)) ? 'has-error' : ''; ?>">
                <label>Sorodstveni položaj</label>
                <input type="text" name="sorodstveni_polozaj" class="form-control" value="<?php echo $sorodstveni_polozaj; ?>">
                <span class="help-block"><?php echo $sorodstveni_polozaj_err;?></span>
            </div>
            <input type="hidden" name="id_druzinski_clani" value="<?php echo $param_id_druzinski_clani; ?>"/>
            <input type="hidden" name="id_delavec" value="<?php echo $param_id_delavec; ?>"/>
            <input type="submit" class="btn btn-primary" value="Posodobi">
            <a href="druzinski_clani.php?id_delavec=<?php echo $param_id_delavec ?>" class="btn btn-default">Prekliči</a>
        </form>
    </div>    
</body>
</html>
