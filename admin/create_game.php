<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Создание игры</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Создание игры</h1>
    <div class="card shadow-sm mt-5">
        <div class="card-body">
            <form action="create_game_process.php" method="POST">
                <div class="form-group">
                    <label for="num_countries">Количество стран (2-10):</label>
                    <input type="number" id="num_countries" name="num_countries" class="form-control" min="2" max="10" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Создать игру</button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
