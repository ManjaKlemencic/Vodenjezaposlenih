<?php

require_once "config.php";


$param_id_delavec = isset($_GET["id_delavec"]) ? trim($_GET["id_delavec"]) : '';
$param_id_druzinski_clani = isset($_GET["id_druzinski_clani"]) ? trim($_GET["id_druzinski_clani"]) : '';

if (!ctype_digit($param_id_delavec) || !ctype_digit($param_id_druzinski_clani)) {
    header("location: error.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_druzinski_clani"]) && ctype_digit($_POST["id_druzinski_clani"])) {
    
    $sql = "DELETE FROM druzinski_clani WHERE id_druzinski_clani = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        
        mysqli_stmt_bind_param($stmt, "i", $id_to_delete);

        
        $id_to_delete = $_POST["id_druzinski_clani"];

        
        if (mysqli_stmt_execute($stmt)) {
            
            header("location: druzinski_clani.php?id_delavec=" . urlencode($_POST["id_delavec"]));
            exit();
        } else {
            echo "Napaka pri brisanju zapisa. Poskusite znova.";
        }
    } else {
        echo "Napaka pri pripravi SQL poizvedbe.";
    }

    
    mysqli_stmt_close($stmt);
}


mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Izbriši družinskega člana</title>
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
        <h2>Izbriši družinskega člana</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id_delavec=' . urlencode($param_id_delavec) . '&id_druzinski_clani=' . urlencode($param_id_druzinski_clani); ?>" method="post">
            <div class="alert alert-danger">
                <input type="hidden" name="id_druzinski_clani" value="<?php echo htmlspecialchars($param_id_druzinski_clani); ?>"/>
                <input type="hidden" name="id_delavec" value="<?php echo htmlspecialchars($param_id_delavec); ?>"/>
                <p>Ali ste prepričani, da želite izbrisati tega družinskega člana?</p>
                <p>
                    <input type="submit" value="Da" class="btn btn-danger">
                    <a href="druzinski_clani.php?id_delavec=<?php echo htmlspecialchars($param_id_delavec); ?>" class="btn btn-default">Ne</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
