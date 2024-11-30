<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delavci</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Roboto', sans-serif;
        }
        .wrapper {
            max-width: 100%;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .page-header h2 {
            margin-top: 0;
            font-size: 1.8rem;
        }
        .btn-custom {
            background-color: #5cb85c;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #4cae4c;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #343a40;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="page-header clearfix">
        <h2 class="pull-left">Seznam Delavcev</h2>
        <?php
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
       
        if (!isset($_SESSION['pravica']) || $_SESSION['pravica'] != 0) {
            echo '<a href="create.php" class="btn btn-custom pull-right">Dodaj delavca</a>';
        }
        ?>
    </div>
    
    <?php
   
    require_once "config.php";

   
    $sql = "SELECT 
        d.id_delavec, 
        d.ime, 
        d.priimek, 
        d.e_naslov, 
        d.tel_stevilka, 
        u.up_ime AS uporabnik_ime,
        b.opis AS bivanje_opis,
        n.ulica AS naslov_ulica,
        n.hisna_stevilka AS naslov_hisna_stevilka,
        p.kraj AS posta_kraj,
        p.postna_stevilka AS posta_stevilka
    FROM delavec d
    LEFT JOIN uporabnik u ON d.TK_uporabnik = u.id_uporabnik
    LEFT JOIN bivanje b ON b.TK_delavec = d.id_delavec
    LEFT JOIN naslov n ON b.TK_naslov = n.id_naslov
    LEFT JOIN posta p ON n.TK_posta = p.id_posta";

    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table class='table table-bordered table-striped'>";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th>Id</th>";
                        echo "<th>Ime</th>";
                        echo "<th>Priimek</th>";
                        echo "<th>E-naslov</th>";
                        echo "<th>Tel Številka</th>";
                        echo "<th>Uporabnik Ime</th>";
                        echo "<th>Bivanje Opis</th>";
                        echo "<th>Naslov</th>";
                        echo "<th>Pošta</th>";
                        echo "<th>Akcije</th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while($row = mysqli_fetch_array($result)){
                    echo "<tr>";
                        echo "<td>" . $row['id_delavec'] . "</td>";
                        echo "<td>" . $row['ime'] . "</td>";
                        echo "<td>" . $row['priimek'] . "</td>";
                        echo "<td>" . $row['e_naslov'] . "</td>";
                        echo "<td>" . $row['tel_stevilka'] . "</td>";
                        echo "<td>" . $row['uporabnik_ime'] . "</td>";
                        echo "<td>" . $row['bivanje_opis'] . "</td>";
                        echo "<td>" . $row['naslov_ulica'] . " " . $row['naslov_hisna_stevilka'] . "</td>";
                        echo "<td>" . $row['posta_kraj'] . " " . $row['posta_stevilka'] . "</td>";
                        echo "<td>";
                           
                            echo "<a href='read.php?id_delavec=". $row['id_delavec']."' class='text-info' title='Preglej'><span class='glyphicon glyphicon-eye-open'></span></a>";
                            
                            
                            if (!isset($_SESSION['pravica']) || $_SESSION['pravica'] != 0) {
                                echo "<a href='update.php?id_delavec=". $row['id_delavec'] ."' class='text-warning' title='Uredi'><span class='glyphicon glyphicon-pencil'></span></a>";
                                echo "<a href='delete.php?id_delavec=". $row['id_delavec'] ."' class='text-danger' title='Izbris'><span class='glyphicon glyphicon-trash'></span></a>";
                            }
                        echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";                            
            echo "</table>";
            
            mysqli_free_result($result);
        } else{
            echo "<p class='lead'><em>No records were found.</em></p>";
        }
    } else{
        echo "ERROR: Could not execute $sql. " . mysqli_error($link);
    }

    mysqli_close($link);
    ?>
</div>

<footer>
    <p>©2024 Delavci - All rights reserved.</p>
</footer>

</body>
</html>