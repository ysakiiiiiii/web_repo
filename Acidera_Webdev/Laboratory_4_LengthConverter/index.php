<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>


    <?php
        $convertedLength = $choice = $length = $error = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST"){

            $length = $_POST["length"];
            $choice = $_POST["choice"];

            if (is_numeric($length)){
                switch($choice){
                    case "meterToKilometer":
                        $convertedLength = $length / 1000;
                        $convertedLength = "$convertedLength km";
                        break;
                    case "kilometerToMeter":
                        $convertedLength = $length * 1000;
                        $convertedLength = "$convertedLength m";
                        break;
                    case "inchesToCentimeter":
                        $convertedLength = $length * 2.54;
                        $convertedLength = "$convertedLength cm";
                        break;
                    case "centimeterToInches":
                        $convertedLength = $length / 2.54;
                        $convertedLength = "$convertedLength in";
                        break;
                    default:
                        $convertedLength = "Invalid Length Value.";
                }      
            }else{
                $error = "Not an invalid input. Please enter a valid integer";
            }
            
        }  
    ?>
    
    <div id="container">

    <form id="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
   
        <p id="title_head"><b>Length Conversion</b></p><br>

        <div>
        Length:
            <input type="text" name="length" id="input" value="<?php if(isset($_POST['length'])){echo $_POST['length'];} ?>" >
            <select name="choice" id="inputLength">
                <option value="meterToKilometer">Meters to Kilometers</option>
                <option value="kilometerToMeter">Kilometers to Meters</option>
                <option value="inchesToCentimeter">Inches to Centimeters</option>
                <option value="centimeterToInches">Centimeters to Inches</option>
            </select>
        </div>
        <span style="color: red; font-size: 13px;"><?php echo $error ?></span>
        <br>

        <input id="submit-text" type="submit" value= "Convert">

        <p>Converted Length: <?php echo $convertedLength ?></p>

   </form>

    </div>
  


</body>
</html>