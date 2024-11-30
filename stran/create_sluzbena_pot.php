<?php
require_once "config.php";


$od = $do = $vozilo = "";
$od_err = $do_err = $vozilo_err = "";


$tk_delavec = filter_input(INPUT_GET, 'id_delavec', FILTER_VALIDATE_INT);
if ($tk_delavec === false || $tk_delavec === null) {
    die("Neveljaven ID zaposlenega.");
}


$check_sql = "SELECT id_delavec FROM delavec WHERE id_delavec = ?";
if ($check_stmt = mysqli_prepare($link, $check_sql)) {
    mysqli_stmt_bind_param($check_stmt, "i", $tk_delavec);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    if (mysqli_stmt_num_rows($check_stmt) == 0) {
        die("Zaposleni ne obstaja.");
    }
    mysqli_stmt_close($check_stmt);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input_od = trim($_POST["od"]);
    if (empty($input_od)) {
        $od_err = "Vnesite datum začetka.";
    } elseif (!strtotime($input_od)) {
        $od_err = "Neveljaven datum začetka.";
    } else {
        $od = date('Y-m-d', strtotime($input_od));
    }
    
  
    $input_do = trim($_POST["do"]);
    if (empty($input_do)) {
        $do_err = "Vnesite datum konca.";
    } elseif (!strtotime($input_do)) {
        $do_err = "Neveljaven datum konca.";
    } else {
        $do = date('Y-m-d', strtotime($input_do));
        
        
        if (!empty($od) && strtotime($do) < strtotime($od)) {
            $do_err = "Datum konca mora biti po datumu začetka.";
        }
    }
    

    $input_vozilo = trim($_POST["vozilo"]);
    if (empty($input_vozilo)) {
        $vozilo_err = "Vnesite vozilo.";
    } elseif (!preg_match("/^[a-zA-Z0-9čćžšđČĆŽŠĐ\s-]+$/u", $input_vozilo)) {
        $vozilo_err = "Vozilo lahko vsebuje samo črke, številke in pomišljaje.";
    } else {
        $vozilo = $input_vozilo;
    }

    if (empty($od_err) && empty($do_err) && empty($vozilo_err)) {
       
        $sql = "INSERT INTO sluzbena_pot (od, do, vozilo, TK_delavec) VALUES (?, ?, ?, ?)";
         
        if ($stmt = mysqli_prepare($link, $sql)) {
            
            mysqli_stmt_bind_param($stmt, "sssi", $param_od, $param_do, $param_vozilo, $param_tk_delavec);
            
          
            $param_od = $od;
            $param_do = $do;
            $param_vozilo = $vozilo;
            $param_tk_delavec = $tk_delavec;

        
            if (mysqli_stmt_execute($stmt)) {
             
                header("location: Izpis_podatkov_o_sluzbenih_poteh.php?" . http_build_query(array('id_delavec' => $tk_delavec)));
                exit();
            } else {
                echo "Napaka pri dodajanju podatkov: " . mysqli_error($link);
            }

            
            mysqli_stmt_close($stmt);
        } else {
            echo "Napaka pri pripravi poizvedbe: " . mysqli_error($link);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj službeno pot</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .help-block {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Dodaj službeno pot</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id_delavec=' . htmlspecialchars($tk_delavec); ?>" method="post">
                        <div class="form-group <?php echo (!empty($od_err)) ? 'has-error' : ''; ?>">
                            <label>Od</label>
                            <input type="date" name="od" class="form-control" value="<?php echo htmlspecialchars($od); ?>">
                            <span class="help-block"><?php echo htmlspecialchars($od_err); ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($do_err)) ? 'has-error' : ''; ?>">
                            <label>Do</label>
                            <input type="date" name="do" class="form-control" value="<?php echo htmlspecialchars($do); ?>">
                            <span class="help-block"><?php echo htmlspecialchars($do_err); ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($vozilo_err)) ? 'has-error' : ''; ?>">
                            <label>Vozilo</label>
                            <input type="text" name="vozilo" class="form-control" value="<?php echo htmlspecialchars($vozilo); ?>">
                            <span class="help-block"><?php echo htmlspecialchars($vozilo_err); ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Dodaj">
                        <a href="Izpis_podatkov_o_sluzbenih_poteh.php?id_delavec=<?php echo htmlspecialchars($tk_delavec); ?>" class="btn btn-default">Nazaj</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>