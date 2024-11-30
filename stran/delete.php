<?php

if (isset($_POST["id_delavec"]) && !empty($_POST["id_delavec"])) {
    
    require_once "config.php";

    
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    
    
    $sql = "DELETE FROM delavec WHERE id_delavec = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
       
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        
        $param_id = $_POST["id_delavec"];
        
        
        if (mysqli_stmt_execute($stmt)) {
            
            header("location: index.php");
            exit();
        } else {
            echo "Napaka. Prosim poskusite pozneje.";
        }
        
       
        mysqli_stmt_close($stmt);
    } else {
        
        echo "ERROR: Could not prepare statement. " . mysqli_error($link);
    }

  
    mysqli_close($link);
} else {
    
    if (empty(trim($_GET["id_delavec"]))) {
        
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Izbris</title>
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
                        <h1>Izbris</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id_delavec" value="<?php echo trim($_GET["id_delavec"]); ?>"/>
                            <p>Želite izbrisati podatke?</p><br>
                            <p>
                                <input type="submit" value="Da" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">Ne</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
