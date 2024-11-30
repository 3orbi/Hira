<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office - Chansons</title>
    <!-- Inclure Bootstrap depuis un CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php

require_once('database.php');

$sql = "SELECT * FROM users"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Créé le</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["created_at"] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "0 résultats";
}

$conn->close();
?>




    <div class="container-fluid mt-5">
        <h1 class="mb-4">Gestion des Chansons</h1>
        <table class="table table-striped" id="songsTable">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Artiste</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module">
        import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm';

        const supabaseUrl = 'https://vtippiwsuhcvidotouio.supabase.co';
        const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZ0aXBwaXdzdWhjdmlkb3RvdWlvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzI1MzM4MTcsImV4cCI6MjA0ODEwOTgxN30.KREf1haK7umgD36WxPoo499hxy7_CDilIYHUGRTm1_g';
        const supabase = createClient(supabaseUrl, supabaseKey);

        async function fetchSongs() {
            const { data, error } = await supabase
                .from('songs')
                .select('*');

            if (error) {
                console.error('Erreur lors de la récupération des chansons:', error.message);
                return;
            }

            const tableBody = document.querySelector('#songsTable tbody');
            tableBody.innerHTML = '';  // Efface les lignes existantes

            data.forEach(song => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${song.id}</td>
                    <td>${song.title}</td>
                    <td>${song.artist}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="deleteSong(${song.id})">Supprimer</button></td>
                `;
                tableBody.appendChild(row);
            });
        }

        async function deleteSong(id) {
            const { error } = await supabase
                .from('songs')
                .delete()
                .eq('id', id);

            if (error) {
                console.error('Erreur lors de la suppression de la chanson:', error.message);
                return;
            }

            fetchSongs();  // Actualise la liste après suppression
        }

        document.addEventListener('DOMContentLoaded', fetchSongs);
    </script>
</body>
</html>
