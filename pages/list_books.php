<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../functions/load_books.php';


try {
    loadBooksFromCSV(__DIR__ . '/../books.csv');
} catch (Exception $e) {
    $error = "Wystąpił problem podczas ładowania danych z pliku CSV: " . htmlspecialchars($e->getMessage());
}


$books = isset($_SESSION['books']) ? $_SESSION['books'] : [];


$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
unset($_SESSION['form_errors']);

?>



<?php
include __DIR__ . '/../includes/header.php';
?>

<main>
    <?php if (isset($error)): ?>
        <p style="color: red;">Błąd: <?= htmlspecialchars($error) ?></p>
    <?php elseif (empty($books)): ?>
        <p>Brak książek do wyświetlenia.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Tytuł</th>
                <th>Autor</th>
                <th>Wydawca</th>
                <th>Rok</th>
                <th>Stron</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book->id) ?></td>
                    <td><?= htmlspecialchars($book->title) ?></td>
                    <td><?= htmlspecialchars($book->author) ?></td>
                    <td><?= htmlspecialchars($book->publisher) ?></td>
                    <td><?= htmlspecialchars($book->year) ?></td>
                    <td><?= htmlspecialchars($book->pageCount) ?></td>
                    <td>
                        <form method="post" action="/logic/delete_book.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($book->id) ?>">
                            <button class="book_delete" type="submit">X</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="form-section">
        <h2>➕ Dodaj nową książkę</h2>

        <?php if (!empty($errors)): ?>
            <ul style="color: red;">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="/logic/add_books.php" class="book-form">
            <div class="form-group">
                <label for="title">Tytuł:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="author">Autor:</label>
                <input type="text" id="author" name="author" required>
            </div>

            <div class="form-group">
                <label for="publisher">Wydawnictwo:</label>
                <input type="text" id="publisher" name="publisher" required>
            </div>

            <div class="form-group">
                <label for="year">Rok wydania:</label>
                <input type="number" id="year" name="year" required>
            </div>

            <div class="form-group">
                <label for="pageCount">Liczba stron:</label>
                <input type="number" id="pageCount" name="pageCount" required>
            </div>

            <button type="submit">➕ Dodaj książkę</button>
        </form>
    </div>

</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
