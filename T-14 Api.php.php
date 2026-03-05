<?php

if (isset($_GET['fetch'])) {

    header("Content-Type: application/json");

    $apiKey = "9bccd715065751a2a6f67eed739e5cfc";

    $page = 1;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }

    $url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=en-US&page=$page";

    $response = file_get_contents($url);

    if ($response === FALSE) {
        echo json_encode(["error" => "Failed to fetch movies"]);
    } else {
        echo $response;
    }

    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Movies</title>
    <style>
        body {
            font-family: Arial;
        }

        .movie-card {
            background: white;
            border-radius: 6px;
            box-shadow: 0 1px 2px black;
            margin-bottom: 20px;
            width: 350px;
            transition: 0.3s;
        }
        
        .movie-card img {
            width: 120px;
            height: 200px;
            object-fit: cover;
        }

        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 2px 4px 8px black;
        }

        .movie-content {
            display: flex;
            gap: 10px;
            padding: 10px;
        }

        .movie-overview {
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn {
            padding: 15px;
            background: #007bff;
            margin-left: 10px;
            color: white;
            border-radius: 4px;
            border: none;
        }

        #movies {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding: 20px;
        }

        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>

<body>

    <div id="movies"></div>

    <button onclick="loadMore()" class="btn">Load More</button>

    <script>
        let currentPage = 1;
        let totalPages = 1;

        function fetchMovies(page = 1) {
            fetch(`?fetch=1&page=${page}`)
                .then(response => response.json())
                .then(data => {
                    totalPages = data.total_pages;
                    renderMovies(data.results);
                })
                .catch(error => console.log("Error:", error));
        }

        function renderMovies(movies) {
            const container = document.getElementById("movies");
            
            movies.forEach(movie => {
                const card = document.createElement("div");
                card.classList.add("movie-card");

                card.innerHTML = `
                    <div class="movie-content">
                        <a href="https://www.themoviedb.org/movie/${movie.id}" target="_blank">
                            <img src="https://image.tmdb.org/t/p/w200${movie.poster_path}" alt="${movie.title}">
                        <div>
                            <h3>${movie.title}</h3>
                        </a>
                            <div class="movie-overview">
                                ${movie.overview || "No description available."}
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function loadMore() {
            if (currentPage < totalPages) {
                currentPage++;
                fetchMovies(currentPage);
            }
        }
        fetchMovies();
    </script>
</body>
</html>