<?php

$errors = array();
$success_msg = "";


require_once "config.php";


if($_SERVER["REQUEST_METHOD"] == "POST"){

    $ime = trim($_POST["ime"]);
    $priimek = trim($_POST["priimek"]);
    $email = trim($_POST["email"]);
    $tel = trim($_POST["tel_stevilka"]);
    $up_ime = trim($_POST["up_ime"]);
    $password = trim($_POST["password"]); 
    $ulica = trim($_POST["ulica"]);
    $hisna_st = trim($_POST["hisna_stevilka"]);
    $postna_st = trim($_POST["postna_stevilka"]);
    $kraj = trim($_POST["kraj"]);
    $bivanje_opis = trim($_POST["bivanje_opis"]);

   
    if(empty($ime)) $errors[] = "Prosim vnesite ime.";
    if(empty($priimek)) $errors[] = "Prosim vnesite priimek.";
    if(empty($email)) {
        $errors[] = "Prosim vnesite email.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Prosim vnesite veljaven email naslov.";
    }
    
    if(empty($password)) $errors[] = "Prosim vnesite geslo.";
    if(empty($up_ime)) $errors[] = "Prosim vnesite uporabniško ime.";
    
   
    if(empty($errors)){
        mysqli_begin_transaction($link);
        try {
            
            $hashed_password = sha1($password);
            
            $sql_uporabnik = "INSERT INTO uporabnik (up_ime, geslo) VALUES (?, ?)";
            $stmt = mysqli_prepare($link, $sql_uporabnik);
            mysqli_stmt_bind_param($stmt, "ss", $up_ime, $hashed_password);
            mysqli_stmt_execute($stmt);
            $id_uporabnik = mysqli_insert_id($link);
            
        
            $sql_delavec = "INSERT INTO delavec (ime, priimek, e_naslov, tel_stevilka, TK_uporabnik) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql_delavec);
            mysqli_stmt_bind_param($stmt, "ssssi", $ime, $priimek, $email, $tel, $id_uporabnik);
            mysqli_stmt_execute($stmt);
            $id_delavec = mysqli_insert_id($link);
            
          
            $sql_check_posta = "SELECT id_posta FROM posta WHERE postna_stevilka = ? AND kraj = ? LIMIT 1";
            $stmt = mysqli_prepare($link, $sql_check_posta);
            mysqli_stmt_bind_param($stmt, "ss", $postna_st, $kraj);
            mysqli_stmt_execute($stmt);
            $posta_result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($posta_result) > 0){
                $posta_row = mysqli_fetch_assoc($posta_result);
                $id_posta = $posta_row['id_posta'];
            } else {
                $sql_posta = "INSERT INTO posta (postna_stevilka, kraj) VALUES (?, ?)";
                $stmt = mysqli_prepare($link, $sql_posta);
                mysqli_stmt_bind_param($stmt, "ss", $postna_st, $kraj);
                mysqli_stmt_execute($stmt);
                $id_posta = mysqli_insert_id($link);
            }
            
         
            $sql_naslov = "INSERT INTO naslov (ulica, hisna_stevilka, TK_posta) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql_naslov);
            mysqli_stmt_bind_param($stmt, "ssi", $ulica, $hisna_st, $id_posta);
            mysqli_stmt_execute($stmt);
            $id_naslov = mysqli_insert_id($link);
            
            
            $sql_bivanje = "INSERT INTO bivanje (opis, TK_delavec, TK_naslov) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql_bivanje);
            mysqli_stmt_bind_param($stmt, "sii", $bivanje_opis, $id_delavec, $id_naslov);
            mysqli_stmt_execute($stmt);
            
            mysqli_commit($link);
            $success_msg = "Delavec uspešno dodan.";
            header("location: index.php");
            exit();
            
        } catch (Exception $e) {
            mysqli_rollback($link);
            $errors[] = "Napaka pri dodajanju podatkov: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Delavca</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Dodaj Delavca</h2>
                    </div>
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if($success_msg): ?>
                        <div class="alert alert-success">
                            <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Ime</label>
                            <input type="text" name="ime" class="form-control" value="<?php echo isset($ime) ? htmlspecialchars($ime) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Priimek</label>
                            <input type="text" name="priimek" class="form-control" value="<?php echo isset($priimek) ? htmlspecialchars($priimek) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>E-naslov</label>
                            <input type="email" name="email" class="form-control" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Telefonska številka</label>
                            <input type="text" name="tel_stevilka" class="form-control" value="<?php echo isset($tel) ? htmlspecialchars($tel) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Uporabniško ime</label>
                            <input type="text" name="up_ime" class="form-control" value="<?php echo isset($up_ime) ? htmlspecialchars($up_ime) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Geslo</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Ulica</label>
                            <input type="text" name="ulica" class="form-control" value="<?php echo isset($ulica) ? htmlspecialchars($ulica) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Hišna številka</label>
                            <input type="text" name="hisna_stevilka" class="form-control" value="<?php echo isset($hisna_st) ? htmlspecialchars($hisna_st) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Poštna številka</label>
                            <input type="text" name="postna_stevilka" class="form-control" value="<?php echo isset($postna_st) ? htmlspecialchars($postna_st) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Kraj</label>
                            <input type="text" name="kraj" class="form-control" value="<?php echo isset($kraj) ? htmlspecialchars($kraj) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Opis bivanja</label>
                            <textarea name="bivanje_opis" class="form-control"><?php echo isset($bivanje_opis) ? htmlspecialchars($bivanje_opis) : ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Dodaj">
                            <a href="index.php" class="btn btn-default">Prekliči</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>