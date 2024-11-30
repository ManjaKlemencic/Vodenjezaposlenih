<?php
if (isset($_GET["id_delavec"]) && !empty(trim($_GET["id_delavec"]))) {
   
    require_once "config.php";


    $sql = "
        SELECT 
            d.id_delavec, d.ime, d.priimek, d.e_naslov, d.tel_stevilka,
            n.ulica, n.hisna_stevilka, p.kraj, p.postna_stevilka
        FROM 
            delavec d
        LEFT JOIN 
            bivanje b ON d.id_delavec = b.TK_delavec
        LEFT JOIN 
            naslov n ON b.TK_naslov = n.id_naslov
        LEFT JOIN 
            posta p ON n.TK_posta = p.id_posta
        WHERE 
            d.id_delavec = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        
        $param_id = trim($_GET["id_delavec"]);

        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                
                $ime = $row["ime"];
                $priimek = $row["priimek"];
                $e_naslov = $row["e_naslov"];
                $tel_stevilka = $row["tel_stevilka"];
                $ulica = $row["ulica"];
                $hisna_stevilka = $row["hisna_stevilka"];
                $kraj = $row["kraj"];
                $postna_stevilka = $row["postna_stevilka"];
            } else {
                echo "No records found!";
            }
        } else {
            echo "Error executing query!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo "$ime $priimek" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-header">
                        <h1>Pregled</h1>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">Ime</div>
                        <div class="info-value"><?php echo $ime; ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Priimek</div>
                        <div class="info-value"><?php echo $priimek; ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Kraj</div>
                        <div class="info-value"><?php echo $kraj; ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">E-mail</div>
                        <div class="info-value"><?php echo $e_naslov; ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Telefonska številka</div>
                        <div class="info-value"><?php echo $tel_stevilka; ?></div>
                    </div>

                    <div class="actions-group">
                        <a href="Izpis_podatkov_o_placi.php?id_delavec=<?php echo $param_id ?>" 
                           class="btn btn-primary">Plača in Pogodba</a>
                        
                        <a href="Izpis_podatkov_o_sluzbenih_poteh.php?id_delavec=<?php echo $param_id ?>" 
                           class="btn btn-primary">Službene poti</a>
                        
                        <a href="druzinski_clani.php?id_delavec=<?php echo $param_id ?>" 
                           class="btn btn-primary">Družinski Člani</a>
                        
                        <a href="dopust.php?id_delavec=<?php echo $param_id ?>" 
                           class="btn btn-primary">Dopust</a>
                    </div>

                    <div class="back-button">
                        <a href="index.php" class="btn btn-secondary">Nazaj</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>