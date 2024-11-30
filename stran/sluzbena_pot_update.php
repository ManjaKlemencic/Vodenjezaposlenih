<?php

require_once "config.php";


$od = $do = $vozilo = "";
$od_err = $do_err = $vozilo_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_delavec"]) && !empty($_POST["id_delavec"])) {
   
    $id_delavec = $_POST["id_delavec"];
    
 
    $input_od = trim($_POST["od"]);
    if (empty($input_od)) {
        $od_err = "Vnesite podatek."; 
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input_od)) {
        $od_err = "Vnesite veljaven datum.";
    } else {
        $od = $input_od;
    }
    
 
    $input_do = trim($_POST["do"]);
    if (empty($input_do)) {
        $do_err = "Vnesite do.";   
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input_do)) {
        $do_err = "Vnesite veljaven datum."; 
    } else {
        $do = $input_do;
    }

 
    $input_vozilo = trim($_POST["vozilo"]);
    if (empty($input_vozilo)) {
        $vozilo_err = "Vnesite vozilo.";   
    } else {
        $vozilo = $input_vozilo;
    }

    if (empty($od_err) && empty($do_err) && empty($vozilo_err)) {
       
        $sql = "UPDATE sluzbena_pot SET od=?, do=?, vozilo=? WHERE id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
        
            mysqli_stmt_bind_param($stmt, "sssi", $param_od, $param_do, $param_vozilo, $param_id);
            
       
            $param_od = $od;
            $param_do = $do;
            $param_vozilo = $vozilo;
            $param_id = $_GET["id"];

        
            if (mysqli_stmt_execute($stmt)) {
                
                $success_msg = "Podatki so bili uspešno posodobljeni.";
            } else {
                echo "Napaka pri posodobitvi. Prosim poskusite pozneje.";
            }
        }
      
        mysqli_stmt_close($stmt);
    }
    

    mysqli_close($link);
} else {
   
    if (isset($_GET["id_delavec"]) && !empty(trim($_GET["id_delavec"]))) {
        
        $id_delavec = trim($_GET["id_delavec"]);
        $id = trim($_GET["id"]);
        
        
        $sql = "SELECT * FROM sluzbena_pot WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
      
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
           
            $param_id = $id;
            
           
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    
                    $od = htmlspecialchars($row["od"]);
                    $do = htmlspecialchars($row["do"]);
                    $vozilo = htmlspecialchars($row["vozilo"]);
                } else {
                   
                    header("location: error.php");
                    exit();
                }
                
            } else {
                echo "Napaka pri pridobivanju podatkov. Prosim poskusite pozneje.";
            }
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
    <title>Uredi službeno pot</title>
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
                        <h2>Urejanje službene poti</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($od_err)) ? 'has-error' : ''; ?>">
                            <label>Od</label>
                            <input type="date" name="od" class="form-control" value="<?php echo $od; ?>">
                            <span class="help-block"><?php echo $od_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($do_err)) ? 'has-error' : ''; ?>">
                            <label>Do</label>
                            <input type="date" name="do" class="form-control" value="<?php echo $do; ?>">
                            <span class="help-block"><?php echo $do_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($vozilo_err)) ? 'has-error' : '';?>">
                            <label>Vozilo</label>
                            <input type="text" name="vozilo" class="form-control" value="<?php echo $vozilo; ?>">
                            <span class="help-block"><?php echo $vozilo_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"/>
                        <input type="hidden" name="id_delavec" value="<?php echo htmlspecialchars($id_delavec); ?>"/>
                        <input type="submit" class="btn btn-primary" value="Uredi">
                        <a href="Izpis_podatkov_o_sluzbenih_poteh.php?id_delavec=<?php echo urlencode($id_delavec)?>" class="btn btn-default">Nazaj</a>
                    </form>
                    <?php if (isset($success_msg)) { ?>
                        <div class="alert alert-success" style="margin-top: 20px;">
                            <?php echo $success_msg; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
