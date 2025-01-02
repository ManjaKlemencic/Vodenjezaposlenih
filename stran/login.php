<?php

session_start();


require_once "config.php";


$up_ime = $geslo = "";
$up_ime_err = $geslo_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
    if (empty(trim($_POST["up_ime"]))) {
        $up_ime_err = "Vnesite uporabniško ime.";
    } else {
        $up_ime = trim($_POST["up_ime"]);
    }

  
    if (empty(trim($_POST["geslo"]))) {
        $geslo_err = "Vnesite geslo.";
    } else {
        $geslo = trim($_POST["geslo"]);
    }

    
    if (empty($up_ime_err) && empty($geslo_err)) {
       
        $sql = "SELECT id_uporabnik, up_ime, geslo, pravica FROM uporabnik WHERE up_ime = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
           
            mysqli_stmt_bind_param($stmt, "s", $param_up_ime);

          
            $param_up_ime = $up_ime;

          
            if (mysqli_stmt_execute($stmt)) {
              
                mysqli_stmt_store_result($stmt);

                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    
                    mysqli_stmt_bind_result($stmt, $id_uporabnik, $up_ime, $hashed_geslo, $pravica);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($geslo, $hashed_geslo)) {
                            
                            session_start();

                    
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id_uporabnik;
                            $_SESSION["up_ime"] = $up_ime;
                            $_SESSION["pravica"] = $pravica; 

                            
                            header("location: index.php");
                        } else {
                            
                            $geslo_err = "Vnešeno geslo ni pravilno.";
                        }
                    }
                } else {
                    
                    $up_ime_err = "Najden ni bil noben račun s tem imenom.";
                }
            } else {
                echo "Oops! Nekaj je narobe. Prosimo poskusite pozneje.";
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
    <title>Prijava</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
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
        <div class="login-container bg-white">
            <h2 class="mb-4">Prijava</h2>
            <p class="text-muted mb-4">Vnesite podatke.</p>
            
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
                           id="geslo">
                    <div class="invalid-feedback"><?php echo $geslo_err; ?></div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">Prijava</button>
                </div>

                <p class="text-center">Nimate računa? <a href="register.php">Pridružite se</a></p>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>