<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Paneli</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
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
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Të dhënat e punonjësve</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Shto punonjës të ri</a>
                    </div>
                    <?php
                    // Shto file-in config
                    require_once "config.php";
                    
                    // Ekzekuto querry
                    $sql = "SELECT * FROM employees";
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Emri</th>";
                                        echo "<th>Adresa</th>";
                                        echo "<th>Paga</th>";
                                        echo "<th>Veprime</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch_array()){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['emri'] . "</td>";
                                        echo "<td>" . $row['adresa'] . "</td>";
                                        echo "<td>" . $row['paga'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="read.php?id='. $row['id'] .'" class="mr-3" title="Shiko rekordet" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update.php?id='. $row['id'] .'" class="mr-3" title="Përditëso Rekordet" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete.php?id='. $row['id'] .'" title="Fshi Rekordet" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Afisho rezultatin
                            $result->free();
                        } else{
                            echo '<div class="alert alert-danger"><em>Nuk u gjeten rezultate</em></div>';
                        }
                    } else{
                        echo "Diçka shkoi keq provo përsëri!.";
                    }
                    
                    // Mbyll lidhjen
                    $mysqli->close();
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>