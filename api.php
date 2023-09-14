<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "localisations_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérer la liste des localisations
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    header("Content-Type: application/json"); // Définir le type de contenu comme JSON
    $sql = "SELECT * FROM localisations";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $localisations = [];
        while ($row = $result->fetch_assoc()) {
            $localisations[] = $row;
        }
        echo json_encode(["data" => $localisations]); // Encapsuler les données dans un objet JSON
    } else {
        echo json_encode(["data" => []]); // Retourner un tableau JSON vide si aucune localisation n'est trouvée
    }
}

// Ajouter une localisation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json"); // Définir le type de contenu comme JSON

    $nom = $_POST["nom"];
    $sql = "INSERT INTO localisations (nom, visite) VALUES ('$nom', '0')";

    if ($conn->query($sql) === TRUE) {
        $response = ["message" => "Localisation ajoutée avec succès."];
        echo json_encode($response);
    } else {
        $response = ["error" => "Erreur lors de l'ajout de la localisation : " . $conn->error];
        echo json_encode($response);
    }
}

// Mettre à jour l'état de visite
if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    header("Content-Type: application/json"); // Définir le type de contenu comme JSON

    parse_str(file_get_contents("php://input"), $putData);
    $id = $putData["id"];
    $visite = $putData["visite"];
    $sql = "UPDATE localisations SET visite='$visite' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $response = ["message" => "État de visite mis à jour avec succès."];
        echo json_encode($response);
    } else {
        $response = ["error" => "Erreur lors de la mise à jour de l'état de visite : " . $conn->error];
        echo json_encode($response);
    }
}

$conn->close();
?>
