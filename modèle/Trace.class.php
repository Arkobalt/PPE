<?php
// Projet TraceGPS
// fichier : modele/Trace.class.php
// Rôle : la classe Trace représente une trace ou un parcours
// Dernière mise à jour : 9/7/2021 par dPlanchet
include_once ('PointDeTrace.class.php');
include_once ('Outils.class.php');

class Trace
{
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------- Attributs privés de la classe -------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    private $id; // identifiant de la trace
    private $dateHeureDebut; // date et heure de début
    private $dateHeureFin; // date et heure de fin
    private $terminee; // true si la trace est terminée, false sinon
    private $idUtilisateur; // identifiant de l'utilisateur ayant créé la trace
    private $lesPointsDeTrace; // la collection (array) des objets PointDeTrace formant la trace
    
    // ------------------------------------------------------------------------------------------------------
    // ----------------------------------------- Constructeur -----------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function __construct($unId, $uneDateHeureDebut, $uneDateHeureFin, $terminee, $unIdUtilisateur) {
        $this->id = $unId;
        $this->dateHeureDebut = $uneDateHeureDebut;
        $this->dateHeureFin = $uneDateHeureFin;
        $this->terminee = $terminee;
        $this->idUtilisateur = $unIdUtilisateur;
        $this->lesPointsDeTrace = array();
    }
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Getters et Setters ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function getId() {return $this->id;}
    public function setId($unId) {$this->id = $unId;}
    
    public function getDateHeureDebut() {return $this->dateHeureDebut;}
    public function setDateHeureDebut($uneDateHeureDebut) {$this->dateHeureDebut = $uneDateHeureDebut;}
    public function getDateHeureFin() {return $this->dateHeureFin;}
    public function setDateHeureFin($uneDateHeureFin) {$this->dateHeureFin= $uneDateHeureFin;}
    
    public function getTerminee() {return $this->terminee;}
    public function setTerminee($terminee) {$this->terminee = $terminee;}
    
    public function getIdUtilisateur() {return $this->idUtilisateur;}
    public function setIdUtilisateur($unIdUtilisateur) {$this->idUtilisateur = $unIdUtilisateur;}
    public function getLesPointsDeTrace() {return $this->lesPointsDeTrace;}
    public function setLesPointsDeTrace($lesPointsDeTrace) {$this->lesPointsDeTrace = $lesPointsDeTrace;}
    // Fournit une chaine contenant toutes les données de l'objet
    public function toString() {
        $msg = "Id : " . $this->getId() . "<br>";
        $msg .= "Utilisateur : " . $this->getIdUtilisateur() . "<br>";
        if ($this->getDateHeureDebut() != null) {
            $msg .= "Heure de début : " . $this->getDateHeureDebut() . "<br>";
        }
        if ($this->getTerminee()) {
            $msg .= "Terminée : Oui <br>";
        }
        else {
            $msg .= "Terminée : Non <br>";
        }
        $msg .= "Nombre de points : " . $this->getNombrePoints() . "<br>";
        if ($this->getNombrePoints() > 0) {
            if ($this->getDateHeureFin() != null) {
                $msg .= "Heure de fin : " . $this->getDateHeureFin() . "<br>";
            }
            $msg .= "Durée en secondes : " . $this->getDureeEnSecondes() . "<br>";
            $msg .= "Durée totale : " . $this->getDureeTotale() . "<br>";
            $msg .= "Distance totale en Km : " . $this->getDistanceTotale() . "<br>";
            $msg .= "Dénivelé en m : " . $this->getDenivele() . "<br>";
            $msg .= "Dénivelé positif en m : " . $this->getDenivelePositif() . "<br>";
            $msg .= "Dénivelé négatif en m : " . $this->getDeniveleNegatif() . "<br>";
            $msg .= "Vitesse moyenne en Km/h : " . $this->getVitesseMoyenne() . "<br>";
            $msg .= "Centre du parcours : " . "<br>";
            $msg .= " - Latitude : " . $this->getCentre()->getLatitude() . "<br>";
            $msg .= " - Longitude : " . $this->getCentre()->getLongitude() . "<br>";
            $msg .= " - Altitude : " . $this->getCentre()->getAltitude() . "<br>";
        }
        return $msg;
    }
    // ------------------------------------------------------------------------------------------------------
    public function getNombrePoints(){ return sizeof($this->lesPointsDeTrace);}
    
    public function getCentre(){
        
        if ($this->getNombrePoints() !=0 ){
            $unPoint = $this->lesPointsDeTrace[0];
            $longmax = $unPoint->getLongitude();
            $latmax = $unPoint->getLatitude();
            $longmin = $unPoint->getLongitude();
            $latmin = $unPoint->getLatitude();
            foreach($this->lesPointsDeTrace as $unPointDeTrace){
                if ($latmax < $unPointDeTrace->getLatitude()) {
                    $latmax = $unPointDeTrace->getLatitude();
                }
                if ($latmin > $unPointDeTrace->getLatitude()) {
                    $latmin = $unPointDeTrace->getLatitude();
                }
                if ($longmax < $unPointDeTrace->getLongitude()) {
                    $longmax = $unPointDeTrace->getLongitude();
                }
                if ($longmin > $unPointDeTrace->getLongitude()) {
                    $longmin = $unPointDeTrace->getLongitude();
                }
            }
            $point = new Point(($latmax+$latmin)/2,($longmax+$longmin)/2, 0);
            return $point;
        }else{
            return null;
        }
    }
    
    public function getDenivele()
    {
        if ($this->getNombrePoints() != 0)
        {
            $p = $this->lesPointsDeTrace[0];
            $altMax = $p->getAltitude();
            $altMin = $p->getAltitude();
            foreach($this->lesPointsDeTrace as $unPointDeTrace){
                $p = $unPointDeTrace;
                if ($altMax < $p->getAltitude())
                {
                    $altMax = $p->getAltitude();
                }
                if ($altMin > $p->getAltitude())
                {
                    $altMin = $p->getAltitude();
                }
            }
            return $altMax - $altMin;
        }
        else
        {
            return 0;
        }
    }
    
    public function getDureeEnSecondes()
    {    
        if ($this->getNombrePoints() != 0)
        {
            $p = $this->lesPointsDeTrace[$this->getNombrePoints() - 1];
            return $p->getTempsCumule();
        }
        else
        {
            return 0;
        }
    }
    
    public function getDureeTotale()
    {
        $heures = 0;
        $minutes = 0;
        $secondes = 0;
        $tCumul = $this->getDureeEnSecondes();
        if ($tCumul > 3600){
            $heures = $tCumul/3600;
            $tCumul = $tCumul%3600;
        }
        if ($tCumul > 60){
            $minutes = $tCumul/60;
            $tCumul = $tCumul%60;
        }
        $secondes = $tCumul;
        return "".sprintf("%02d", $heures).":".sprintf("%02d", $minutes).":".sprintf("%02d", $secondes);
    }
    
    public function getDistanceTotale()
    {
        if ($this->getNombrePoints() != 0)
        {
            $p = $this->lesPointsDeTrace[$this->getNombrePoints() - 1];
            return $p->getDistanceCumulee();
        }
        else
        {
            return 0;
        }
    }
    
    public function getDenivelePositif()
    {
        if ($this->getNombrePoints() != 0)
        {
            $denivelePos = 0;
            $p = $this->lesPointsDeTrace[0];
            $altPre = $p->getAltitude();
            for ($i = 0; $i < $this->getNombrePoints(); $i++)
            {
                $p = $this->lesPointsDeTrace[$i];
                if ($altPre < $p->getAltitude())
                {
                    $denivelePos = $denivelePos + ($p->getAltitude() - $altPre);
                }
                $altPre = $p->getAltitude();
            }
            return Round($denivelePos, 2);
        }
        else
        {
            return 0;
        }
    }
    
    public function getDeniveleNegatif()
    {
        if ($this->getNombrePoints() != 0)
        {
            $deniveleNeg = 0;
            $p = $this->lesPointsDeTrace[0];
            $altPre = $p->getAltitude();
            for ($i = 0; $i < $this->getNombrePoints(); $i++)
            {
                $p = $this->lesPointsDeTrace[$i];
                if ($altPre > $p->getAltitude())
                {
                    $deniveleNeg = $deniveleNeg + ($altPre - $p->getAltitude());
                }
                $altPre = $p->getAltitude();
            }
            return Round($deniveleNeg, 2);
        }
        else
        {
            return 0;
        }
    }
    
    public function getVitesseMoyenne()
    {
        if ($this->getNombrePoints() != 0)
        {
            return Round($this->getDistanceTotale() / ($this->getDureeEnSecondes() /3600),2);
        }
        else
        {
            return 0;
        }
    }
    
    public function ajouterPoint($pdt)
    {
        if ($this->getNombrePoints() == 0)
        {
            $pdt->setDistanceCumulee(0);
            $pdt->setVitesse(0);
            $pdt->setTempsCumule(0);
            $this->lesPointsDeTrace[] = $pdt;
        }
        else
        {
            $dernierPoint = $this->lesPointsDeTrace[$this->getNombrePoints() -1];
            $pdt->setTempsCumule(strtotime($pdt->getDateHeure()) - strtotime($this->getDateHeureDebut()));
            $distanceCumulee = $this->getDistanceTotale() + $dernierPoint->getDistance($dernierPoint, $pdt);
            $pdt->setDistanceCumulee($distanceCumulee);
            if ($this->getDureeEnSecondes() != 0){
                $pdt->setVitesse(($pdt->getDistance($pdt , $dernierPoint) / $this->getDureeEnSecondes())*3600);
            }else{
                $pdt->setVitesse(0);
            }
            $this->lesPointsDeTrace[] = $pdt;
        }
    }
    
    public function viderListePoints(){
        $this->lesPointsDeTrace = null;
    }
} // fin de la classe Trace
// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!
