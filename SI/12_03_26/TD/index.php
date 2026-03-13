<?php
require('fpdf.php');

class PDF extends FPDF
{
// En-tête
function Header()
{
    // Logo
    $this->Image('img/image.png',10,6,30);
    // Police Arial gras 15
     $this->SetFont('Arial','',12);
    // Décalage à droite
    $this->Cell(120);
    // Titre
    $this->Cell(30,10,'Annee universitaire 2015-2016');
    // Saut de ligne
    $this->Ln(20);
    //TITRE
    $this->Cell(50);
    $this->Cell(30,10,'RELEVE DE NOTE ET RESULTAT','C');
    $this->Ln(20);


    
}

// Pied de page
function Footer()
{

}
}

// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Nom:');
$pdf->Ln();
$pdf->Cell(40,10,'Prenom(s):');
$pdf->Ln();
$pdf->Cell(40,10,'Nee le :');
$pdf->Ln();
$pdf->Cell(40,10,'Numero dinscription :');
$pdf->Ln();
$pdf->Cell(40,10,'Inscrit en :');
$pdf->Ln();
$pdf->Cell(40,10,' a obtenu les notes suivantes:');



$pdf->Output();


?>