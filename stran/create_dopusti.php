<?php
require_once "config.php";

$naziv = $opis = $datum_od = $datum_do = "";
$naziv_err = $opis_err = $datum_od_err = $datum_do_err = $tk_delavec_err = "";


$id_delavec = isset($_GET['id_delavec']) ? trim($_GET['id_delavec']) : '';


if(!empty($id_delavec)) {
    $check_sql = "SELECT id_delavec FROM delavec WHERE id_delavec = ?";
    if($check_stmt = mysqli_prepare($link, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "i", $id_delavec);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if(mysqli_stmt_num_rows($check_stmt) == 0) {
            
            header("location: error.php");
            exit();
        }
        mysqli_stmt_close($check_stmt);
    }
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $input_naziv = isset($_POST["naziv"]) ? trim($_POST["naziv"]) : "";
    if(empty($input_naziv)){
        $naziv_err = "Vnesite naziv dopusta.";
    } else {
        $naziv = $input_naziv;
    }
    
    
    $input_opis = isset($_POST["opis"]) ? trim($_POST["opis"]) : "";
    if(empty($input_opis)){
        $opis_err = "Vnesite opis dopusta.";
    } else {
        $opis = $input_opis;
    }
    
    
    $input_datum_od = isset($_POST["datum_od"]) ? trim($_POST["datum_od"]) : "";
    if(empty($input_datum_od)){
        $datum_od_err = "Vnesite začetni datum.";
    } else {
        $datum_od = $input_datum_od;
    }
    
    
    $input_datum_do = isset($_POST["datum_do"]) ? trim($_POST["datum_do"]) : "";
    if(empty($input_datum_do)){
        $datum_do_err = "Vnesite končni datum.";
    } else {
        $datum_do = $input_datum_do;
    }

    
    $tk_delavec = isset($_POST["id_delavec"]) ? trim($_POST["id_delavec"]) : "";
    if(empty($tk_delavec)){
        $tk_delavec_err = "Napaka pri identifikaciji delavca.";
    }

   
    if(empty($naziv_err) && empty($opis_err) && empty($datum_od_err) && empty($datum_do_err) && empty($tk_delavec_err)){
        $sql = "INSERT INTO dopusti (naziv, opis, datum_od, datum_do, TK_delavec) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "ssssi", $param_naziv, $param_opis, $param_datum_od, $param_datum_do, $param_tk_delavec);
            
            
            $param_naziv = $naziv;
            $param_opis = $opis;
            $param_datum_od = $datum_od;
            $param_datum_do = $datum_do;
            $param_tk_delavec = $tk_delavec;
            
           
            if(mysqli_stmt_execute($stmt)){
                
                header("location: dopust.php?id_delavec=" . $param_tk_delavec);
                exit();
            } else {
                echo "Napaka pri dodajanju dopusta. Prosim poskusite pozneje.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dodaj dopust</title>
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
                        <h2>Dodaj dopust</h2>
                    </div>
                    <?php 
                    if(!empty($tk_delavec_err)){
                        echo '<div class="alert alert-danger">' . $tk_delavec_err . '</div>';
                    }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id_delavec=' . htmlspecialchars($id_delavec); ?>" method="post">
                        <div class="form-group <?php echo (!empty($naziv_err)) ? 'has-error' : ''; ?>">
                            <label>Naziv</label>
                            <input type="text" name="naziv" class="form-control" value="<?php echo htmlspecialchars($naziv); ?>">
                            <span class="help-block"><?php echo $naziv_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($opis_err)) ? 'has-error' : ''; ?>">
                            <label>Opis</label>
                            <textarea name="opis" class="form-control"><?php echo htmlspecialchars($opis); ?></textarea>
                            <span class="help-block"><?php echo $opis_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($datum_od_err)) ? 'has-error' : ''; ?>">
                            <label>Od</label>
                            <input type="date" name="datum_od" class="form-control" value="<?php echo htmlspecialchars($datum_od); ?>">
                            <span class="help-block"><?php echo $datum_od_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($datum_do_err)) ? 'has-error' : ''; ?>">
                            <label>Do</label>
                            <input type="date" name="datum_do" class="form-control" value="<?php echo htmlspecialchars($datum_do); ?>">
                            <span class="help-block"><?php echo $datum_do_err;?></span>
                        </div>
                        <input type="hidden" name="id_delavec" value="<?php echo htmlspecialchars($id_delavec); ?>">
                        <input type="submit" class="btn btn-primary" value="Dodaj">
                        <a href="dopust.php?id_delavec=<?php echo htmlspecialchars($id_delavec); ?>" class="btn btn-default">Nazaj</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>