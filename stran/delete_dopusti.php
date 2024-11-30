<?php

        $param_id_delavec = $_GET["id_delavec"];
        $param_id = $_GET['id'];
if(isset($_POST["id_delavec"]) && !empty($_POST["id_delavec"]) && isset($_POST["id"]) && !empty($_POST["id"])){
  
    require_once "config.php";
    
   
    $sql = "DELETE FROM dopusti WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
      
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        
        $param_id = $_POST["id"];

        $id_delavec = $_POST["id_delavec"];
      
        if(mysqli_stmt_execute($stmt)){
           
            header("location: dopust.php?id_delavec=" .  $_POST["id_delavec"]);
            exit();
        } else{
            echo "Napaka. Prosim poskusite pozneje.";
        }
    }
     
    
    mysqli_stmt_close($stmt);
    
  
    mysqli_close($link);
} else{
    
    if(empty(trim($_GET["id_delavec"]))){
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Izbriši dopust</title>
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
                        <h1>Izbris</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                            <input type="hidden" name="id_delavec" value="<?php echo ($_GET['id_delavec']); ?>">
                            <p>Želite izbrisati podatke?</p><br>
                            <p>
                                <input type="submit" value="Da" class="btn btn-danger">
                                <a href="dopust.php?id_delavec=<?php echo $_GET["id_delavec"] ?>" class="btn btn-default">Ne</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>