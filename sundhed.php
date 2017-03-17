<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 09-03-2017
 * Time: 12:07
 */


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    lavVar($_POST);

    //udskriv en form med alle data!
    printPage($fornavn, $efternavn, $vej, $vejnummer, $by, $postnummer, $vaegt, $hoejde, $tlf, $foedselsdag);

    if (testVar($vaegt, $hoejde, $postnummer, $vejnummer, $tlf, $foedselsdag)) {
        calcBMI($vaegt, $hoejde);
        bestemHvilkenRegion($postnummer);
        beregnAlder($foedselsdag);
        dageTil($foedselsdag);
        SkrivNavnBaglens($efternavn, $fornavn);


    }
} else {
    //print en tom form
    printPage("", "", "", "", "", "", "", "", "", "");
}


function lavVar()
{
    global $fornavn;
    global $efternavn;
    global $vej;
    global $vejnummer;
    global $by;
    global $postnummer;
    global $vaegt;
    global $hoejde;
    global $tlf;
    global $foedselsdag;

// sikrer at der ikke er gris i vores variabler
    $fornavn = htmlspecialchars($_POST["fornavn"]);
    $efternavn = htmlspecialchars($_POST["efternavn"]);
    $vej = htmlspecialchars($_POST["vej"]);
    $vejnummer = htmlspecialchars($_POST["vejnummer"]);
    $by = htmlspecialchars($_POST["by"]);
    $postnummer = htmlspecialchars($_POST["postnummer"]);
    $vaegt = htmlspecialchars($_POST["vaegt"]);
    $hoejde = htmlspecialchars($_POST["hoejde"]);
    $tlf = htmlspecialchars($_POST["tlf"]);
    $foedselsdag = htmlspecialchars($_POST["foedselsdag"]);
}

function testVar($vaegt, $hoejde, $postnummer, $vejnummer, $tlf, $foedselsdag)
{    // tester hver variable og afbryder så snart den finder en fejl
    if (!is_numeric($vaegt)) {
        echo "<div class='fejl'>Feltet vægt indeholder ikke et tal!<br></div>";
        return false;
    }
    if ($vaegt > 250) {
        echo "<div class='fejl'>phyyyy så er man altså tung, men jeg tror ikke på dig!<br></div>";
        return false;
    }
    if ($vaegt < 10) {
        echo "<div class='fejl'>HAHA - måske i førsteklasse!<br></div>";
        return false;
    }
    if (!is_numeric($hoejde)) {
        echo "<div class='fejl'>højde ikke et tal<br></div>";
        return false;
    }
    if ($hoejde > 200) {
        echo "<div class='fejl'>Så høj er der vist ikke nogen som er..<br></div>";
        return false;
    }
    if ($hoejde < 50) {
        echo "<div class='fejl'>Så lav er der vist ikke nogen som er..<br></div>";
        return false;
    }
    if (!is_numeric($postnummer)) {
        echo "<div class='fejl'>postnummer er ikke et tal<br></div>";
        return false;
    }
    if (!is_numeric($vejnummer)) {
        echo "<div class='fejl'>vejnummer er ikke et tal<br></div>";
        return false;
    }
    if (!is_numeric($tlf)) {
        echo "<div class='fejl'>Telefonnummer er ikke et tal<br></div>";
        return false;
    }

    if (!testDato($foedselsdag)) {
        echo "<div class='fejl'>Fødselsdag er ikke rigtig<br></div>";
        return false;
    }
    return true;
}

function calcBMI($vaegt, $hoejde)
{
    $hoejde = intval($hoejde);
    $vaegt = intval($vaegt);
    $bmi = $vaegt / (($hoejde / 100) * ($hoejde / 100));
    echo "<div class='output'>Jeg har beregnet dit bmi til: " . round($bmi) . "<br></div>";
    //return $bmi;
}


function bestemHvilkenRegion($postnummer)
{
    if ($postnummer > 800 and $postnummer < 3790)
        $region = "Region Hovedstaden<br>";
    elseif ($postnummer >= 4000 and $postnummer <= 4990)
        $region = "Region Sjælland<br>";
    elseif ($postnummer >= 5000 and $postnummer <= 7300)
        $region = "Region Syddanmark<br>";
    elseif ($postnummer >= 5000 and $postnummer < 9000)
        $region = "Region Midtjylland<br>";
    elseif ($postnummer >= 9000 and $postnummer < 9990)
        $region = "Region Midtjylland<br>";

    echo "<div class='output'><br>Du bor i: " . $region . "</div>";
}

function testDato($bday)
{
    if ($bday <> "") {
        $year = substr($bday, -4);
        $month = substr($bday, -6, 2);
        $day = substr($bday, -8, 2);
        if (checkdate($month, $day, $year)) {
            return true;
        } else {
            return false;
        }
    } else
        return false;
}

function beregnAlder($bday)
{
    $year = substr($bday, -4);
    $month = substr($bday, -6, 2);
    $day = substr($bday, -8, 2);
    $thisYear = date("Y");
    $thisMonth = date("m");
    $age = date("Y") - $year;
    echo "<div class='output'>Du fylder: " . $age . " i år<br></div>";
}

function dageTil($bday)
{
    $year = substr($bday, -4);
    $month = substr($bday, -6, 2);
    $day = substr($bday, -8, 2);
    $thisYear = date("Y");

    $your_date = strtotime($thisYear . "-" . $month . "-" . $day);

    //$your_date = strtotime("2010/01/01");
    $now = time();

    $datediff = $your_date - $now;

    if ($datediff < 0) {
        $datediff = floor($datediff / (60 * 60 * 24)) + 365;

    } else {
        $datediff = floor($datediff / (60 * 60 * 24)) + 1;

    }
    echo "<div class='output'>Du har fødselsdag om: " . $datediff . " dage.<br></div>";

}

function printPage($fornavn, $efternavn, $vej, $vejnummer, $by, $postnummer, $vaegt, $hoejde, $tlf, $foedselsdag)
{

    echo "
    
        <html lang=\"dk\">
        <head>
            <meta charset=\"UTF-8\">
            <title>Sundhedsprogram</title>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"stylesheet.css\" \">
        </head>
        <body>
            <div class='backgroundimage'>
                <div class=\"heading\">
                    <div class=\"box\">
                       <h1> Sundhedsprogram</h1>
                    </div>
                </div> 
                <div class=\"container\">   
                    <div class=\"box\">
                        <form method=\"post\" action=\"sundhed.php\">
                            <ul>
                                
                                <li>";
    echo " <input type='text' placeholder='fornavn' name='fornavn' value=$fornavn>";
    echo "</li>
                                <li>";
    echo " <input type='text' placeholder='efternavn' name='efternavn' value=$efternavn>";
    echo "</li>
                                <li>";
    echo "<input type='text' placeholder='vej' name='vej' value=$vej>";
    echo "</li>
                                <li>";
    echo "<input type='text' placeholder='vejnummer' name='vejnummer' value=$vejnummer>";
    echo "</li>
                                <li>";
    echo "<input type='text' placeholder='postnummer' name='postnummer' value=$postnummer>";
    echo "</li>
                                    
                                <li>";
    echo "<input type='text' placeholder='by' name='by' value=$by>";
    echo "</li>
                                    
                                <li>";
    echo "<input type='text' placeholder='tlf' name='tlf' value=$tlf>";
    echo "</li>
                                    
                                <li>";
    echo "<input type='text' placeholder='fødselsdag DDMMÅÅÅÅ' name='foedselsdag' value=$foedselsdag>";
    echo "</li>
                                    
                                <li>";
    echo "<input type='text' placeholder='vægt' name='vaegt' value=$vaegt>";
    echo "</li>
                                    
                                <li>";
    echo "<input type='hoejde' placeholder='højde' name='hoejde' value=$hoejde>";
    echo "</li>
                                    
                                <li><input type=\"submit\" value=\"Submit\">    <input type=\"reset\" value=\"reset\"></li>
                            </ul>
                        </form>
                    </div>
                
        </body>";
}

function skrivNavnBaglens($fornavn, $efternavn)
{
    $reversedName = strrev($efternavn . " " . $fornavn);

    echo "<div class=output>" . $reversedName . "<br></div>";
}