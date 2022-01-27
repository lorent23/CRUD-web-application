<?php
// Përfshijmë file-n config
require_once "config.php";
 
// Përcaktojmë variablat dhe u japim vlera boshe
$emri = $adresa = $paga = "";
$emri_err = $adresa_err = $paga_err = "";
 
// Përpunimi i të dhënave të formularit kur formulari dorëzohet
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vlefshmëria e emrit
    $input_emri = trim($_POST["emri"]);
    if(empty($input_emri)){
        $emri_err = "Ju lutemi shtoni emrin";
    } elseif(!filter_var($input_emri, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $emri_err = "Ju lutemi shtoni një emër të vlefshëm";
    } else{
        $emri = $input_emri;
    }
    
    // Vlefshmëria e adresës
    $input_adresa = trim($_POST["adresa"]);
    if(empty($input_adresa)){
        $adresa_err = "Ju lutem shtoni adresën";     
    } else{
        $adresa = $input_adresa;
    }
    
    // Vlefshmëria e pagës
    $input_paga = trim($_POST["paga"]);
    if(empty($input_paga)){
        $paga_err = "Ju lutemi shtoni shumën e pagesës";     
    } elseif(!ctype_digit($input_paga)){
        $paga_err = "Ju lutemi shtoni vetëm vlera pozitive";
    } else{
        $paga = $input_paga;
    }
    
    // Kontrolloni gabimet e inputeve përpara se t'i vendosni në bazën e të dhënave
    if(empty($emri_err) && empty($adresa_err) && empty($paga_err)){
        // Përgatisim një deklarim për inputet
        $sql = "INSERT INTO employees (emri, adresa, paga) VALUES (?, ?, ?)";
 
        if($stmt = $mysqli->prepare($sql)){
            // Lidhni variablat me deklaratën e përgatitur si parametra
            $stmt->bind_param("sss", $param_emri, $param_adresa, $param_paga);
            
            // Vendos parametrat
            $param_emri = $emri;
            $param_adresa = $adresa;
            $param_paga = $paga;
            
            // Provojmë të ekzekutojmë deklarimin e përgatitur
            if($stmt->execute()){
                // Rekordet u krijuan me sukses. Ridrejto te faqja kryesore
                header("location: index.php");
                exit();
            } else{
                echo "Diçka shkoi keq provo përsëri!";
            }
        }
         
        // Mbylle deklarimin
        $stmt->close();
    }
    
    // Mbylle lidhjen
    $mysqli->close();
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Krijo rekord</title>
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
                    <h2 class="mt-5">Krijo rekord</h2>
                    <p>
                     Ju lutemi plotësoni këtë formular dhe dorëzojeni për të shtuar të dhënat e punonjësve në bazën e të dhënave.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Anulo</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>