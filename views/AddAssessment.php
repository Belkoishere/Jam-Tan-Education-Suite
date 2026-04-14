<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une évaluation</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_add_assessment {
            font-weight: bold;
        }
        #s_add_assessment {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div id="main">
        <h2>Ajouter une évaluation</h2>

        <div class="form-container">
            <form action="#" method="POST">
                <label for="Program">Programme</label>
                <select name="Program" id="">

                </select>
                <label for="Title">Titre</label>
                <input type="text">
                <label for="Type">Type</label>
                <select name="Type" id="">

                </select>
                <label for="Deadline">Date Limite</label>
                <input type="date" name="Deadline">
                <label for="MaxGrade">Points maximums</label>
                <input type="number" name="MaxGrade">
                <label for="PassGrade">Points de réussite</label>
                <input type="number" name="PassGrade">
                <input type="submit" value="Ajouter">
            </form>
        </div>
    </div>
</body>
</html>