<?php

require_once "config.php";


$up_ime = $geslo = $potrdi_geslo = "";
$up_ime_err = $geslo_err = $potrdi_geslo_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  
if (empty(trim($_POST["up_ime"]))) {
    $up_ime_err = "Vnesite uporabniško ime.";
} else {
   
    $sql = "SELECT id_uporabnik FROM uporabnik WHERE up_ime = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
       
        mysqli_stmt_bind_param($stmt, "s", $param_up_ime);

        
        $param_up_ime = trim($_POST["up_ime"]);

       
        if (mysqli_stmt_execute($stmt)) {
           
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                $up_ime_err = "Uporabniško ime ni na voljo.";
            } else {
                $up_ime = trim($_POST["up_ime"]);
            }
        } else {
            echo "Oops! Nekaj je narobe. Prosimo, poskusite pozneje.";
        }

     
        mysqli_stmt_close($stmt);
    }
}


   
    if (empty(trim($_POST["geslo"]))) {
        $geslo_err = "Vnesite geslo.";
    } elseif (strlen(trim($_POST["geslo"])) < 6) {
        $geslo_err = "Geslo mora vsebovati vsaj 6 znakov.";
    } else {
        $geslo = trim($_POST["geslo"]);
    }

 
    if (empty(trim($_POST["potrdi_geslo"]))) {
        $potrdi_geslo_err = "Potrdite geslo.";
    } else {
        $potrdi_geslo = trim($_POST["potrdi_geslo"]);
        if (empty($geslo_err) && ($geslo != $potrdi_geslo)) {
            $potrdi_geslo_err = "Gesli se ne ujemata.";
        }
    }

if (empty($up_ime_err) && empty($geslo_err) && empty($potrdi_geslo_err)) {

    
    $sql = "INSERT INTO uporabnik (up_ime, geslo, pravica) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
       
        mysqli_stmt_bind_param($stmt, "ssi", $param_up_ime, $param_geslo, $param_pravica);

    
        $param_up_ime = $up_ime;
        $param_geslo = password_hash($geslo, PASSWORD_DEFAULT); 
        $param_pravica = 0; 

      
        if (mysqli_stmt_execute($stmt)) {
            
            header("location: login.php");
            exit();
        } else {
            echo "Nekaj je narobe. Prosimo, poskusite pozneje.";
        }

   
        mysqli_stmt_close($stmt);
    }
}


  
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pridružitev</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .register-container {
            max-width: 400px;
            margin: 2rem auto;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="register-container bg-white">
            <h2 class="mb-4">Pridružite se</h2>
            <p class="text-muted mb-4">Prosimo vnesite podatke.</p>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="up_ime" class="form-label">Uporabniško ime</label>
                    <input type="text" 
                           name="up_ime" 
                           class="form-control <?php echo (!empty($up_ime_err)) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo htmlspecialchars($up_ime); ?>"
                           id="up_ime">
                    <div class="invalid-feedback"><?php echo $up_ime_err; ?></div>
                </div>

                <div class="mb-3">
                    <label for="geslo" class="form-label">Geslo</label>
                    <input type="password" 
                           name="geslo" 
                           class="form-control <?php echo (!empty($geslo_err)) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($geslo); ?>"
                           id="geslo">
                    <div class="invalid-feedback"><?php echo $geslo_err; ?></div>
                </div>

                <div class="mb-3">
                    <label for="potrdi_geslo" class="form-label">Potrdite geslo</label>
                    <input type="password" 
                           name="potrdi_geslo" 
                           class="form-control <?php echo (!empty($potrdi_geslo_err)) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($potrdi_geslo); ?>"
                           id="potrdi_geslo">
                    <div class="invalid-feedback"><?php echo $potrdi_geslo_err; ?></div>
                </div>

                <div class="mb-3 d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Registriraj se</button>
                    <button type="reset" class="btn btn-secondary">Ponastavi</button>
                </div>

                <p class="text-center">Že imate račun? <a href="login.php">Prijavite se tukaj</a>.</p>
            </form>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>