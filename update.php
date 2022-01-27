<?php
// Perfshijme filen config
require_once "config.php";
 
// Deklarojme variablat dhe ju atribuojmë vlera boshe
$emri = $adresa = $paga = "";
$emri_err = $adresa_err = $paga_err = "";
 
// Procesojmë të dhënat e formularit në momentin kur të dhënat bëhen submit
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Marrim të dhënat e fshehura
    $id = $_POST["id"];
    
    // Verifikojmë vlefshmërinë e emrit
    $input_emri = trim($_POST["emri"]);
    if(empty($input_emri)){
        $emri_err = "Ju lutemi fusni emrin";
    } elseif(!filter_var($input_emri, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $emri_err = "Ju lutem fusni një emër të vlefshëm";
    } else{
        $emri = $input_emri;
    }
    
    // Verifikojmë vlefshmërinë e adresës
    $input_adresa = trim($_POST["adresa"]);
    if(empty($input_adresa)){
        $adresa_err = "Ju lutemi shtoni adresën";     
    } else{
        $adresa = $input_adresa;
    }
    
    // Verifikojmë vlefshmërinë e pagës
    $input_paga = trim($_POST["paga"]);
    if(empty($input_paga)){
        $paga_err = "Ju lutemi fusni shumën e pagës";     
    } elseif(!ctype_digit($input_paga)){
        $paga_err = "Ju lutemi shtoni vlera pozitive";
    } else{
        $paga = $input_paga;
    }
    
    // Kontrollojmë gabimet e inputeve përpara se t'i vendosim në bazën e të dhënave
    if(empty($emri_err) && empty($adresa_err) && empty($paga_err)){
        // Përditëso | Përgatit deklarimin për përditësim
        $sql = "UPDATE employees SET emri=?, adresa=?, paga=? WHERE id=?";
 
        if($stmt = $mysqli->prepare($sql)){
            // Lidh variablat me deklaratën e përgatitur si parametra
            $stmt->bind_param("sssi", $param_emri, $param_adresa, $param_paga, $param_id);
            
            // Vendos parametrat
            $param_emri = $emri;
            $param_adresa = $adresa;
            $param_paga = $paga;
            $param_id = $id;
            
            // Provojmë të ekzekutojmë deklaratën e para përgatitur
            if($stmt->execute()){
                // Rekordet u përditësuan me sukses,ridrejto tek faqja kryesore
                header("location: index.php");
                exit();
            } else{
                echo "Diçka shkoi keq provo përsëri!";
            }
        }
         
        // Deklaro mbylljen
        $stmt->close();
    }
    
    // Mbyll lidhjen
    $mysqli->close();
} else{
    // Kontrollo vleren e parametrit id përpara se të vazhdosh më tej
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Mer parametrin nga URL
        $id =  trim($_GET["id"]);
        
        // Përgatit një deklarim përzgjedhjeje
        $sql = "SELECT * FROM employees WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Lidh variablat me deklarimin e përgatitur si parametra
            $stmt->bind_param("i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Provojmë të ekzekutojmë deklaratën e para përgatitur
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    /* Merr rreshtin e rezultateve si vektor*/
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Merr vlerën individuale të fushës
                    $emri = $row["emri"];
                    $adresa = $row["adresa"];
                    $paga = $row["paga"];
                } else{
                    // URL-ja nuk përmban ID të vlefshme. Ridrejto te faqja e gabimit
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Diçka shkoi keq provo përsëri!";
            }
        }
        
        //Deklaro mbylljen
        $stmt->close();
        
        // Mbyll lidhjen
        $mysqli->close();
    }  else{
        // URL-ja nuk përmban ID të vlefshme. Ridrejto te faqja e gabimit
        header("location: error.php");
        exit();
    }
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Përditëso Rekordet</title>
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
                    <h2 class="mt-5">Përditëso Rekordet</h2>
                    <p>Ju lutemi ndryshoni vlerat e dhëna dhe dorëzojini për ti përditësuar të dhënat e punonjësve.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Emri</label>
                            <input type="text" name="emri" class="form-control <?php echo (!empty($emri_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $emri; ?>">
                            <span class="invalid-feedback"><?php echo $emri_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Adresa</label>
                            <textarea name="adresa" class="form-control <?php echo (!empty($adresa_err)) ? 'is-invalid' : ''; ?>"><?php echo $adresa; ?></textarea>
                            <span class="invalid-feedback"><?php echo $adresa_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Paga</label>
                            <input type="text" name="paga" class="form-control <?php echo (!empty($paga_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $paga; ?>">
                            <span class="invalid-feedback"><?php echo $paga_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Anulo</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>