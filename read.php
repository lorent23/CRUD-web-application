<?php
// Kontrollojmë ekzistencën e parametrit ID përpara se të vazhdojmë
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Përfshijmë file-n config
    require_once "config.php";
    
    // Shtojmë një deklarim për përzgjedhje
    $sql = "SELECT * FROM employees WHERE id = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Lidhim variablat me deklaratën e përgatitur si parametra
        $stmt->bind_param("i", $param_id);
        
        // Vendosim parametrat
        $param_id = trim($_GET["id"]);
        
        // Provojmë të ekzekutojmë deklarimin e përgatitur
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            if($result->num_rows == 1){
                /* Merr rreshtin si vektor */
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                // Merr vlerën individuale të fushës
                $emri = $row["emri"];
                $adresa = $row["adresa"];
                $paga = $row["paga"];
            } else{
                // URL-ja nuk përmban parametër të vlefshëm id. Ridrejto te faqja e gabimit
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Diçka shkoi keq provo përsëri!";
        }
    }
     
    // Mbyll deklarimin
    $stmt->close();
    
    // Mbyll lidhjen
    $mysqli->close();
} else{
    // URL-ja nuk përmban parametër të vlefshëm id. Ridrejto te faqja e gabimit
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shiko Rekordet</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">Shiko Rekordet</h1>
                    <div class="form-group">
                        <label>Emri</label>
                        <p><b><?php echo $row["emri"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Adresa</label>
                        <p><b><?php echo $row["adresa"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Paga</label>
                        <p><b><?php echo $row["paga"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Prapa</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
