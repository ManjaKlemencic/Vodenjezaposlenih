<?php
require_once "config.php";


$st_pogodbe = $trajanje_pogodbe = $placa = "";
$st_pogodbe_err = $trajanje_pogodbe_err = $placa_err = "";


if (isset($_POST["id_delavec"]) && !empty($_POST["id_delavec"])) {
  
    $id_delavec = $_POST["id_delavec"];
  
   
    $input_trajanje_pogodbe = trim($_POST["trajanje_pogodbe"]);
    if (empty($input_trajanje_pogodbe)) {
        $trajanje_pogodbe_err = "Vnesite trajanje pogodbe."; 
    } elseif (!filter_var($input_trajanje_pogodbe, FILTER_VALIDATE_INT)) {   
        $trajanje_pogodbe_err = "Trajanje pogodbe mora biti celo število." ;
    } else {
        $trajanje_pogodbe = $input_trajanje_pogodbe;
    }
    
   
    $input_placa = trim($_POST["placa"]);
    if (empty($input_placa)) {
        $placa_err = "Vnesite plačo.";    
    } elseif (!filter_var($input_placa, FILTER_VALIDATE_FLOAT)) {
        $placa_err = "Plača mora biti številka." ;
    } else {
        $placa = $input_placa;
    }

 
    if (empty($st_pogodbe_err) && empty($trajanje_pogodbe_err) && empty($placa_err)) {
        $sql = "UPDATE pogodba_placa SET trajanje_pogodbe=?, placa=? WHERE TK_delavec=?";
         
        if ($stmt = mysqli_prepare($link, $sql)) {
           
            mysqli_stmt_bind_param($stmt, "ssi", $param_trajanje_pogodbe, $param_placa, $param_id_delavec);
            
           
            $param_trajanje_pogodbe = $trajanje_pogodbe;
            $param_placa = $placa;
            $param_id_delavec = $id_delavec;

         
            if (mysqli_stmt_execute($stmt)) {
               
                header("location: Izpis_podatkov_o_placi.php?" . http_build_query(array('id_delavec' => $param_id_delavec)));
                exit();
            } else {
                echo "Napaka. Prosim poskusite pozneje.";
            }
        }
         
      
        mysqli_stmt_close($stmt);
    }

    
    mysqli_close($link);
} else {
  
    if (isset($_GET["id_delavec"]) && !empty(trim($_GET["id_delavec"]))) {
        
        $id_delavec = trim($_GET["id_delavec"]);
        
     
        $sql = "SELECT * FROM pogodba_placa WHERE TK_delavec = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
           
            mysqli_stmt_bind_param($stmt, "i", $param_id_delavec);
            
           
            $param_id_delavec = $id_delavec;
            
           
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
    
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
            
                    $st_pogodbe = $row["st_pogodbe"];
                    $trajanje_pogodbe = $row["trajanje_pogodbe"];
                    $placa = $row["placa"];
                } else {
               
                    header("location: error.php");
                    exit();
                }
                
            } else {
                echo "Napaka. Prosim poskusite pozneje.";
            }
        }
        
       
        mysqli_stmt_close($stmt);
        
     
        mysqli_close($link);
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
    <title>Uredi Plačo in pogodbo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
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
                        <div class="form-group <?php echo (!empty($st_pogodbe_err)) ? 'has-error' : ''; ?>">
                            <label>Št pogodbe</label>
                            <input type="number" name="st_pogodbe" class="form-control" value="<?php echo $st_pogodbe; ?>" readonly>
                            <span class="help-block"><?php echo $st_pogodbe_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($trajanje_pogodbe_err)) ? 'has-error' : ''; ?>">
                            <label>Trajanje pogodbe</label>
                            <input type="number" name="trajanje_pogodbe" class="form-control" value="<?php echo $trajanje_pogodbe; ?>">
                            <span class="help-block"><?php echo $trajanje_pogodbe_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($placa_err)) ? 'has-error' : ''; ?>">
                            <label>Plača</label>
                            <input type="number" step="0.01" name="placa" class="form-control" value="<?php echo $placa; ?>">
                            <span class="help-block"><?php echo $placa_err; ?></span>
                        </div>
                        <input type="hidden" name="id_delavec" value="<?php echo $id_delavec; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Shrani">
                        <a href="Izpis_podatkov_o_placi.php?id_delavec=<?php echo $id_delavec; ?>" class="btn btn-default">Prekliči</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
