<?php
$param_id_delavec = trim($_GET["id_delavec"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dopust</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper {
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2 {
            margin-top: 0;
        }
        table tr td:last-child a {
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Dopusti</h2>
                        <a href="create_dopusti.php?id_delavec=<?php echo $param_id_delavec ?>" class="btn btn-success pull-right">Dodaj dopust</a>
                    </div>
                    <?php
                    
                    require_once "config.php";

                    
                    $sql = "SELECT * FROM dopusti WHERE TK_delavec = ?";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        
                        mysqli_stmt_bind_param($stmt, "i", $param_id_delavec);

                       
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);

                            if (mysqli_num_rows($result) > 0) {
                                echo "<table class='table table-bordered table-striped'>";
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Id</th>";
                                            echo "<th>Od</th>";
                                            echo "<th>Do</th>";
                                            echo "<th>Akcije</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                            echo "<td>" . $row['id_dopusti'] . "</td>";
                                            echo "<td>" . $row['datum_od'] . "</td>";
                                            echo "<td>" . $row['datum_do'] . "</td>";
                                            echo "<td>";
                                            
                                                echo "<a href='delete_dopusti.php?id_delavec=$param_id_delavec&id_dopusti=" . $row['id_dopusti'] . "' title='Izbris' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                
                                mysqli_free_result($result);
                            } else {
                                echo "<p class='lead'><em>No records were found.</em></p>";
                            }
                        } else {
                            echo "ERROR: Could not execute $sql. " . mysqli_error($link);
                        }
                    }

                    
                    mysqli_close($link);
                    ?>
                    <input type="hidden" name="id_delavec" value="<?php echo $param_id_delavec ?>">
                    <p><a href="read.php?id_delavec=<?php echo $param_id_delavec ?>" class="btn btn-default">Nazaj</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
