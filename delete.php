<?php
// Procedon operacionin e fshirjes pas konfirmimit
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Përfshijmë file-n config
    require_once "config.php";
    
    // Përgatit një deklaratë fshirjeje
    $sql = "DELETE FROM employees WHERE id = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Lidhni variablat me deklaratën e përgatitur si parametra
        $stmt->bind_param("i", $param_id);
        
        // Vendos parametrat
        $param_id = trim($_POST["id"]);
        
        // Provojmë të ekzekutojmë deklaratën e përgatitur
        if($stmt->execute()){
            // Rekordet u fshinë me sukses. Ridrejto te faqja kryesore
            header("location: index.php");
            exit();
        } else{
            echo "Diçka shkoi shkoi keq provo përsëri!";
        }
    }
     
    // Mbyll deklarimin
    $stmt->close();
    
    // Mbyll lidhjen
    $mysqli->close();
} else{
    // Kontrollo ekzistencën e parametrit ID
    if(empty(trim($_GET["id"]))){
        // URL-ja nuk përmban parametrin id. Ridrejto te faqja e gabimit
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fshi rekordet</title>
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
                    <h2 class="mt-5 mb-3">Fshi rekordet</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Jeni i sigurt që dëshironi të fshini këtë rekord punonjës?</p>
                            <p>
                                <input type="submit" value="Po" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary ml-2">Jo</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
