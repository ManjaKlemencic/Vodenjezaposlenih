<?php
require_once "config.php";

$ime = $priimek = $sorodstveni_polozaj = "";
$ime_err = $priimek_err = $sorodstveni_polozaj_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_delavec = filter_input(INPUT_POST, 'id_delavec', FILTER_VALIDATE_INT);
    if ($id_delavec === false || $id_delavec === null) {
        die("Invalid employee ID.");
    }

   
    $input_ime = trim($_POST["ime"]);
    if (empty($input_ime)) {
        $ime_err = "Vnesite ime.";
    } elseif (!preg_match("/^[a-zA-ZčćžšđČĆŽŠĐ\s]+$/u", $input_ime)) {
        $ime_err = "Ime lahko vsebuje samo črke in presledke.";
    } else {
        $ime = $input_ime;
    }

    $input_priimek = trim($_POST["priimek"]);
    if (empty($input_priimek)) {
        $priimek_err = "Vnesite priimek.";
    } elseif (!preg_match("/^[a-zA-ZčćžšđČĆŽŠĐ\s]+$/u", $input_priimek)) {
        $priimek_err = "Priimek lahko vsebuje samo črke in presledke.";
    } else {
        $priimek = $input_priimek;
    }

    $input_sorodstveni_polozaj = trim($_POST["sorodstveni_polozaj"]);
    if (empty($input_sorodstveni_polozaj)) {
        $sorodstveni_polozaj_err = "Vnesite sorodstveni položaj.";
    } elseif (!preg_match("/^[a-zA-ZčćžšđČĆŽŠĐ\s]+$/u", $input_sorodstveni_polozaj)) {
        $sorodstveni_polozaj_err = "Sorodstveni položaj lahko vsebuje samo črke in presledke.";
    } else {
        $sorodstveni_polozaj = $input_sorodstveni_polozaj;
    }


    if (empty($ime_err) && empty($priimek_err) && empty($sorodstveni_polozaj_err)) {

        $sql = "INSERT INTO druzinski_clani (ime, priimek, sorodstveni_polozaj, TK_delavec) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {

            mysqli_stmt_bind_param($stmt, "sssi", $param_ime, $param_priimek, $param_sorodstveni_polozaj, $id_delavec);


            $param_ime = $ime;
            $param_priimek = $priimek;
            $param_sorodstveni_polozaj = $sorodstveni_polozaj;

            if (mysqli_stmt_execute($stmt)) {

                header("location: druzinski_clani.php?" . http_build_query(array('id_delavec' => $id_delavec)));
                exit();
            } else {
                echo "Napaka pri dodajanju. Prosim poskusite pozneje.";
            }
           
            mysqli_stmt_close($stmt);
        } else {
            echo "Napaka pri pripravi poizvedbe.";
        }
    }
}

$id_delavec = filter_input(INPUT_GET, 'id_delavec', FILTER_VALIDATE_INT);
if ($id_delavec === false || $id_delavec === null) {
    die("Invalid employee ID.");
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj družinskega člana</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Dodaj družinskega člana</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($ime_err)) ? 'has-error' : ''; ?>">
                            <label>Ime</label>
                            <input type="text" name="ime" class="form-control" value="<?php echo htmlspecialchars($ime); ?>">
                            <span class="help-block"><?php echo htmlspecialchars($ime_err); ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($priimek_err)) ? 'has-error' : ''; ?>">
                            <label>Priimek</label>
                            <input type="text" name="priimek" class="form-control" value="<?php echo htmlspecialchars($priimek); ?>">
                            <span class="help-block"><?php echo htmlspecialchars($priimek_err); ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($sorodstveni_polozaj_err)) ? 'has-error' : ''; ?>">
                            <label>Sorodstveni položaj</label>
                            <input type="text" name="sorodstveni_polozaj" class="form-control" value="<?php echo htmlspecialchars($sorodstveni_polozaj); ?>">
                            <span class="help-block"><?php echo htmlspecialchars($sorodstveni_polozaj_err); ?></span>
                        </div>
                        <input type="hidden" name="id_delavec" value="<?php echo htmlspecialchars($id_delavec); ?>">
                        <input type="submit" class="btn btn-primary" value="Dodaj">
                        <a href="druzinski_clani.php?id_delavec=<?php echo htmlspecialchars($id_delavec); ?>" class="btn btn-default">Nazaj</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>