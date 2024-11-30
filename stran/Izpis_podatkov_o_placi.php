<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_GET["id_delavec"]) && !empty(trim($_GET["id_delavec"]))){
   
    require_once "config.php";
    
   
    $sql = "SELECT * FROM pogodba_placa WHERE TK_delavec=?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        
        $param_id = trim($_GET["id_delavec"]);
        
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
               
                $st_pogodbe = $row['st_pogodbe'];
                $trajanje_pogodbe = $row['trajanje_pogodbe'];
                $placa = $row['placa'];
            } else{
                
                header("location: create_placa_pogodba.php?id_delavec=" .  $_GET["id_delavec"]);
                exit();
            }
            
        } else{
            echo "Napaka. Prosim poskusite pozneje.";
        }
    }
     
   
    mysqli_close($link);
} else{
   
    header("location: create_placa_pogodba.php?id_delavec=" .  $_GET["id_delavec"]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plača in pogodba</title>
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
                        <h1>Plača in Pogodba</h1>
                    </div>
                    <div class="form-group">
                        <label>Št pogodbe</label>
                        <p class="form-control-static"><?php echo htmlspecialchars($st_pogodbe); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Trajanje pogodbe</label>
                        <p class="form-control-static"><?php echo htmlspecialchars($trajanje_pogodbe); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Plača</label>
                        <p class="form-control-static"><?php echo htmlspecialchars($placa) . " €"; ?></p>
                    </div>
                    <div class="form-group">
                        <?php 
                      
                        if (!isset($_SESSION['pravica']) || $_SESSION['pravica'] != 0) {
                            echo '<a href="placa_pogodba_update.php?id_delavec=' . htmlspecialchars($param_id) . '" class="btn btn-primary">Uredi</a>';
                        }
                        ?>
                        <a href="read.php?id_delavec=<?php echo htmlspecialchars($param_id); ?>" class="btn btn-default">Nazaj</a>
                    </div>
                    <input type="hidden" name="id_delavec" value="<?php echo htmlspecialchars($param_id); ?>">
                </div>
            </div>        
        </div>
    </div>
</body>
</html>