<?php
// Projet TraceGPS
// fichier : modele/Trace.class.php
// Rôle : la classe Trace représente une trace ou un parcours
// Dernière mise à jour : 9/7/2021 par dPlanchet
include_once ('PointDeTrace.class.php');
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
        $this->lesPointsDeTrace = array();
        $this->id = $unId;
        $this->dateHeureDebut = $uneDateHeureDebut;
        $this->dateHeureFin = $uneDateHeureFin;
        $this->terminee = $terminee;
        $this->idUtilisateur = $unIdUtilisateur;
        
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
    
    public function getNombrePoints(){
        $nbrePoints = sizeof($this->lesPointsDeTrace);
        return $nbrePoints;
    }
    
    public function getCentre(){
        $nbPoints = $this->getNombrePoints();
        
        $LatitudeMini = ($this->lesPointsDeTrace[0])->getLatitude();
        $LatitudeMaxi = ($this->lesPointsDeTrace[0])->getLatitude();
        $LongitudeMini = ($this->lesPointsDeTrace[0])->getLongitude();
        $LongitudeMaxi = ($this->lesPointsDeTrace[0])->getLongitude();
        for ($i=1; $i < $nbPoints; $i++){
            $lePoint = $this->lesPointsDeTrace[$i];
            if ($lePoint->getLatitude() < $LatitudeMini)
            {
                $LatitudeMini = $lePoint->getLatitude();
            }
            if ($lePoint->getLatitude() > $LatitudeMaxi)
            {
                $LatitudeMaxi = $lePoint->getLatitude();
            }
            if ($lePoint->getLongitude() < $LongitudeMini)
            {
                $LongitudeMini = $lePoint->getLongitude();
            }
            if ($lePoint->getLongitude() > $LongitudeMaxi)
            {
                $LongitudeMaxi = $lePoint->getLongitude();
            }
        }
        $latitudeMoyenne = ($LatitudeMaxi + $LatitudeMini) / 2;
        $longitudeMoyenne = ($LongitudeMaxi + $LongitudeMini) / 2;
        $pointCentre = new Point($latitudeMoyenne, $longitudeMoyenne, 0);
        return $pointCentre;
    }
    
    public function getDenivele(){
        $nbPoints = $this->getNombrePoints();
        
        
        if ($nbPoints == 0)
        {
            return 0;
        }
        $AltitudeMini = ($this->lesPointsDeTrace[0])->getAltitude();
        $AltitudeMaxi = ($this->lesPointsDeTrace[0])->getAltitude();
        for ($i=1; $i < $nbPoints; $i++){
            $lePoint = $this->lesPointsDeTrace[$i];
            if ($lePoint->getAltitude() < $AltitudeMini)
            {
                $AltitudeMini = $lePoint->getAltitude();
            }
            
            if ($lePoint->getAltitude() > $AltitudeMaxi)
            {
                $AltitudeMaxi = $lePoint->getAltitude();
            }
            
        }
        $denivele = $AltitudeMaxi - $AltitudeMini;
        return $denivele;
        
    }
    
    public function getDureeEnSecondes(){
        $nbPoints = $this->getNombrePoints();
        
        
        if ($nbPoints == 0)
        {
            return 0;
        }
        
        else
        {
            $unPoint = $this->lesPointsDeTrace[$nbPoints -1] ;
            return $unPoint->getTempsCumule();
        }
    }
    
    public function getDureeTotale(){
        $nbPoints = $this->getNombrePoints();
        if ($nbPoints == 0)
        {
            return "00:00:00";
        }
        else
        {
            $duree = $this->getDureeEnSecondes();
            $heures = ($duree - $duree % 3600) / 3600;
            $minutes = (($duree - $heures * 3600) - $duree % 60) / 60;
            $secondes = ($duree - $heures * 3600) % 60;
            $message = sprintf('%02d',$heures) .':'.sprintf('%02d',$minutes) .':'.sprintf('%02d',$secondes);
            return $message;
        }
        
    }
    
    public function getDistanceTotale(){
        $nbPoints = $this->getNombrePoints();
        
        
        if ($nbPoints == 0)
        {
            return 0;
        }
        
        else
        {
            
            $dernierPoint = $this->lesPointsDeTrace[$nbPoints -1];
            $distance = $dernierPoint->getDistanceCumulee();
            return $distance;
        }
    }
    
    public function getDenivelePositif(){
        $nbPoints = $this->getNombrePoints();
        if ($nbPoints == 0){
            return 0;
        }
        
        $denivele = 0;
        for ($i=0; $i < $nbPoints-1; $i++){
            $altitude1 = $this->lesPointsDeTrace[$i]->getAltitude();
            $altitude2 = $this->lesPointsDeTrace[$i+1]->getAltitude();
            if($altitude2 > $altitude1)
            {
                $denivele = $denivele + $altitude2 - $altitude1;
            }
            
        }
        return $denivele;
    }
    
    public function getDeniveleNegatif(){
        $nbPoints = $this->getNombrePoints();
        if ($nbPoints == 0)
        {
            return 0;
        }
        
        $denivele = 0;
        for ($i=0; $i < $nbPoints-1; $i++){
            $altitude1 = $this->lesPointsDeTrace[$i]->getAltitude();
            $altitude2 = $this->lesPointsDeTrace[$i+1]->getAltitude();
            if($altitude2 < $altitude1)
            {
                $denivele = $denivele + $altitude1 - $altitude2;
            }
            
        }
        return $denivele;
    }
    
    public function getVitesseMoyenne(){
        $nbPoints = $this->getNombrePoints();
        if ($nbPoints == 0)
        {
            return 0;
        }
        else
        {
            $distance = $this->getDistanceTotale();
            $duree = ($this->lesPointsDeTrace[$nbPoints -1])->getTempsCumule() / 3600;
            $vitesse = $distance / $duree;
            return $vitesse;
        }
        
    }
    
    public function AjouterPoint($unPoint){
        $nbPoints = $this->getNombrePoints();
        if ($nbPoints == 0)
        {
            $unPoint->setDistanceCumulee(0);
            $unPoint->setTempsCumule(0);
            $unPoint->setVitesse(0);
        }
        else
        {
            $dernierPoint = $this->lesPointsDeTrace[$nbPoints -1];
            $distanceCumulee = Point::getDistance($dernierPoint, $unPoint) + $dernierPoint->getDistanceCumulee();
            $tempsCumule = strtotime($unPoint->getDateHeure())- strtotime($this->lesPointsDeTrace[0]->getDateHeure());
            $vitesse = Point::getDistance($unPoint, $dernierPoint) / (($tempsCumule - $dernierPoint->getTempsCumule())/3600);
            $unPoint->setDistanceCumulee($distanceCumulee);
            $unPoint->setTempsCumule($tempsCumule);
            $unPoint->setVitesse($vitesse);
        }
        
        $this->lesPointsDeTrace[] = $unPoint;
    }
} // fin de la classe Trace
// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!
