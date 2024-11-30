<?php
require_once "config.php";


$st_pogodbe = $trajanje_pogodbe = $placa = "";
$st_pogodbe_err = $trajanje_pogodbe_err = $placa_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input_st_pogodbe = trim($_POST["st_pogodbe"]);
    if (empty($input_st_pogodbe)) {
        $st_pogodbe_err = "Vnesite st_pogodbe.";
    } else {
        $st_pogodbe = $input_st_pogodbe;
    }

    
    $input_trajanje_pogodbe = trim($_POST["trajanje_pogodbe"]);
    if (empty($input_trajanje_pogodbe)) {
        $trajanje_pogodbe_err = "Vnesite trajanje_pogodbe."; 
    } else {
        $trajanje_pogodbe = $input_trajanje_pogodbe;
    }

    $input_placa = trim($_POST["placa"]);
    if (empty($input_placa)) {
        $placa_err = "Vnesite placa.";    
    } else {
        $placa = $input_placa;
    }

    if (empty($st_pogodbe_err) && empty($trajanje_pogodbe_err) && empty($placa_err)) {
        $sql = "INSERT INTO pogodba_placa (st_pogodbe, trajanje_pogodbe, placa, TK_delavec) VALUES (?, ?, ?, ?)"; 

        if ($stmt = mysqli_prepare($link, $sql)) {

            mysqli_stmt_bind_param($stmt, "ssdi", $param_st_pogodbe, $param_trajanje_pogodbe, $param_placa, $param_id_delavec);
            
            
            $param_st_pogodbe = $st_pogodbe;
            $param_trajanje_pogodbe = $trajanje_pogodbe;
            $param_placa = (float)$placa; 
            $param_id_delavec = trim($_POST["id_delavec"]); 

       
            if (mysqli_stmt_execute($stmt)) {
                
                header("location: Izpis_podatkov_o_placi.php?" . http_build_query(array('id_delavec' => $param_id_delavec)));
                exit();
            } else {
                echo "Napaka pri izvajanju izjave: " . mysqli_error($link); 
            }
        } else {
            echo "Napaka pri pripravi izjave: " . mysqli_error($link); 
        }

        
        mysqli_stmt_close($stmt);
    }

    
    mysqli_close($link); 
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Dodaj pogodbo</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($st_pogodbe_err)) ? 'has-error' : ''; ?>">
                            <label>Št pogodbe</label>
                            <input type="number" name="st_pogodbe" class="form-control" value="<?php echo htmlspecialchars($st_pogodbe); ?>">
                            <span class="help-block"><?php echo $st_pogodbe_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($trajanje_pogodbe_err)) ? 'has-error' : ''; ?>">
                            <label>Trajanje pogodbe</label>
                            <input type="number" name="trajanje_pogodbe" class="form-control" value="<?php echo htmlspecialchars($trajanje_pogodbe); ?>">
                            <span class="help-block"><?php echo $trajanje_pogodbe_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($placa_err)) ? 'has-error' : ''; ?>">
                            <label>Plača</label>
                            <input type="number" name="placa" class="form-control" value="<?php echo htmlspecialchars($placa); ?>">
                            <span class="help-block"><?php echo $placa_err;?></span>
                        </div>                      
                        <input type="hidden" name="id_delavec" value="<?php echo htmlspecialchars($_GET['id_delavec']); ?>">
                        <input type="submit" class="btn btn-primary" value="Dodaj">
                        <a href="druzinski_clani.php?id_delavec=<?php echo htmlspecialchars($_GET['id_delavec']); ?>" class="btn btn-default">Nazaj</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
