<?php
// Fehler!!!  im Dev-Modus sichtbar machen       Aktivieren von Fehlern
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1); // Verbindung mit 

// ---------- DB KONFIG ----------
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'library';  
$mysqli = new mysqli($host, $user, $pass, $db);
$mysqli->set_charset('utf8mb4');//alle Charaktere wurden unterstützt
 //entgeht HTML
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$errors = [];
$success = false;

// ---------- FORMULAR -Bücher hinzufügen/aktualisieren (POST-Formular) val id?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idRaw        = $_POST['id'] ?? '';
    $titleRaw     = $_POST['title'] ?? '';
    $descRaw      = $_POST['description'] ?? '';
    $yearRaw      = $_POST['publishing_year'] ?? '';
    $publisherRaw = $_POST['publisher_id'] ?? '';

   // $id        = filter_var($idRaw, FILTER_VALIDATE_INT);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title     = trim($_POST['title'] ?? '');
    $desc      = trim($_POST['description'] ?? '');
    $year      = filter_var($_POST['publishing_year'] ?? '', FILTER_VALIDATE_INT);
    $publisher = filter_var($_POST['publisher_id'] ?? '', FILTER_VALIDATE_INT);
    //$title     = trim($titleRaw);
    //$desc      = trim($descRaw);
   // $year      = filter_var($yearRaw, FILTER_VALIDATE_INT);
   // $publisher = filter_var($publisherRaw, FILTER_VALIDATE_INT);
    $errors = [];

    if ($title === '' || mb_strlen($title) > 255) {
        $errors[] = 'Titel darf nicht leer sein (max. 255 Zeichen).';
    }
    if ($publisher === false) {
        $errors[] = 'Ungültiger Verlag.';
    }


    if (!$errors) { 


$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id > 0) {
    // UPDATE
} else {
    // INSERT
}

      //var_dump($_POST);
      //var_dump($id);
      //exit;

     // var_dump($_POST);
     // exit;

      // var_dump($id);  
      // exit;
        if ($id > 0) {

        //              if ($id !== false && $id > 0) {
          // ---------- UPDATE ----------
            $stmt = $mysqli->prepare(
                'UPDATE books SET title=?, description=?, publishing_year=?, publisher_id=? WHERE id=?'
            );
            $stmt->bind_param('ssiii', $title, $desc, $year, $publisher, $id);
            $stmt->execute();
            header('Location: ' . $_SERVER['PHP_SELF'] . '?updated=1');
            exit;
        } else {
           // ---------- INSERT ----------
            $stmt = $mysqli->prepare(
                'INSERT INTO books (title, description, publishing_year, publisher_id) VALUES (?, ?, ?, ?)'
            );
            $stmt->bind_param('ssii', $title, $desc, $year, $publisher);
            $stmt->execute();
            header('Location: ' . $_SERVER['PHP_SELF'] . '?added=1');
            exit;
        }
    }
}

// ---------- LÖSCHEN ----------post forma
if (isset($_GET['delete'])) {
    $delId = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($delId) {
        $stmt = $mysqli->prepare('DELETE FROM books WHERE id = ?');
        $stmt->bind_param('i', $delId);
        $stmt->execute();
        header('Location: ' . $_SERVER['PHP_SELF'] . '?deleted=1');
        exit;
    }
}

/// ---------- BEARBEITEN ----------
$editingBook = null;

if (isset($_GET['edit'])) {
    $editId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);

      if ($editId !== false && $editId > 0) {

        $stmt = $mysqli->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->bind_param('i', $editId);
        $stmt->execute();
        //$editingBook = $stmt->get_result()->fetch_assoc();
        $editingBook = array_change_key_case($stmt->get_result()->fetch_assoc(), CASE_LOWER);

    }
}

// ---------- DATEN LADEN ----------
$res = $mysqli->query('SELECT b.id, b.title, b.description, b.publishing_year, p.title AS verlag, b.publisher_id 
                       FROM books b JOIN publisher p ON b.publisher_id = p.id
                       ORDER BY b.id ASC');
$books = $res->fetch_all(MYSQLI_ASSOC);

// ---------- PUBLISHER LADEN ----------
//$pubResult = $mysqli->query("SELECT id, title FROM publisher");

$pubResult = $mysqli->query("SELECT id, title FROM publisher");
$publishers = $pubResult->fetch_all(MYSQLI_ASSOC);

?>
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">   
<!--  <link href="css/bootstrap.min.css" rel="stylesheet">  -->
  <!-- Korrekt, wenn sich bootstrap.min.css tatsächlich in befindet bootstrap-5.3.7-dist/css/ -->
<link href="bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!-- C:\xampp\htdocs\php\snowfall.js <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
-->   
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- snowfall plugin -->


   <script src="snowfall.js"></script>
   <script>
document.addEventListener("DOMContentLoaded", function() {
    new Snowfall({
       minSize: 5,
        maxSize: 15,
        interval: 120,
        content: "❄"
        
    });
});
</script>


   
<!--<script>
    $(document).ready(function() {
        $(document.body).snowfall({
           round: true,
           minSize: 2,
           maxSize: 5,
           flakeCount: 100
        });
    });
   </script> -->


<style>
.table-soft-yellow {
  background-color: #f5dbe0ff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(110, 106, 106, 0.1);
  width: 100%;
}
.table-soft-yellow td,
.table-soft-yellow th {
  padding: 12px;
  vertical-align: middle;
  background-color: #f9e1f3ff;
  /* Hier darf der Hintergrund NICHT festgelegt werden! */
}
.table-soft-yellow thead {
  background-color: #cba116;
  color: #fff;
}

.table-soft-yellow tbody tr:nth-child(odd) {
  background-color: #fff8d6; /* heller Hintergrund */
}

.table-soft-yellow tbody tr:nth-child(even) {
  background-color: #bcb79cff; /* dunkleres, aber sanftes Gelb*/
}
.card-soft-yellow {
    background-color: #f5efd6ff; /* Zartes Grün */
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(184, 49, 49, 0.1);
}  

.btn-soft-green {
    background-color: #afd5beff; /* Zartes Grün */
    border-color: #b8d6c4ff;
    border-radius: 7px;
    color: #000; /* Der Text ist dunkel, um Kontrast zu erzielen.*/
}

.btn-soft-green:hover {
    background-color: #a4d0b4ff;
    border-color: #a1cab0ff;
    color: #000;
}
.snowfield {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 9999;
}

.snowflake {
    position: absolute;
    color: white;
    user-select: none;
}
</style>

 
  <title>Bibliothek</title>
</head>
<body class="bg-light">

<div class="container my-5">

  <!-- ГЛАВНЫЙ ЗАГОЛОВОК -->
  <h1 class="mb-4 text-center">📚 Unsere Bibliothek</h1>

  <!-- СООБЩЕНИЯ -->
  <?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success text">✅ Buch wurde gespeichert.</div>
  <?php endif; ?>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success text">✏️ Buch wurde aktualisiert.</div>
  <?php endif; ?>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-danger text">🗑️ Buch wurde gelöscht.</div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $e): ?>
          <li><?= h($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- FORMULARKARTE -- -->
 
<div class="row justify-content-center">
  <div class="col-md-6 mx-auto">

    <div class="card card-soft-yellow mb-4">
      <div class="card-body">

        <h2 class="h4 mb-3 text-center">
          <?= $editingBook ? "Buch bearbeiten" : "Neues Buch anlegen" ?>
        </h2>

<!--<?php
//if ($editingBook) {
//   var_dump($editingBook);
//} else {
//    echo "Das zu bearbeitende Buch wurde nicht gefunden!";
//}
//?>  -->



        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" class="text-center">
        <!--  <input type="hidden" name="id" value="<?= isset($editingBook['id']) ? (int)$editingBook['id'] : '' ?>">   -->
          <input type="hidden" name="id" value="<?= isset($editingBook['id']) ? $editingBook['id'] : 0 ?>">



           <div class="mb-3 text-start">
                <label class="form-label">Buchtitel</label>
                <input type="text" name="title" class="form-control"
                       value="<?= h($editingBook['title'] ?? '') ?>" required>
           </div>

           <div class="mb-3 text-start">
               <label class="form-label">Kurzbeschreibung</label>
               <textarea name="description" class="form-control"><?= h($editingBook['description'] ?? '') ?></textarea>
           </div>

           <div class="mb-3 text-start">
                <label class="form-label">Verlag</label>
                <select name="publisher_id" class="form-select">
                    <?php foreach ($publishers as $pub): ?>
                         <option value="<?= $pub['id'] ?>"
                             <?= isset($editingBook['publisher_id']) && $editingBook['publisher_id'] == $pub['id'] ? 'selected' : '' ?>>
                             <?= h($pub['title']) ?>
                         </option>
                    <?php endforeach; ?>
                </select>
           </div>

           <div class="text-center mt-3">
               <button class="btn btn-soft-green">
               <?= $editingBook ? "Speichern" : "Neues Buch erstellen" ?>
           </button>
          </div>
        </form>


        
      </div>
    </div>

  </div>
</div>

  <!--  BÜCHERLISTE -->
  <h2 class="h4 mb-3 text-center">Alle Bücher</h2>

  
<table class="table table-bordered table-soft-yellow">  
    <thead>
    <tr>
      <th>ID</th>
      <th>Titel</th>
      <th>Beschreibung</th>
      <th>Verlag</th>
      <th>Aktionen</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($books as $b): ?>
      <tr>
        <td><?= h($b['id']) ?></td>
        <td><?= h($b['title']) ?></td>
        <td><?= $b['description'] !== '' ? h($b['description']) : '<span class="muted">—</span>' ?></td>
        <td><?= h($b['verlag']) ?></td>
        <td>
          <a href="?edit=<?= $b['id'] ?>" class="btn btn-sm btn-success">
            <i class="bi bi-pencil"></i>
          </a>
          <a href="?delete=<?= $b['id'] ?>" onclick="return confirm('Wirklich löschen?');" class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</div>
<!--<script src="bootstrap-5.3.7-dist/bootstrap-5.3.7-dis/js/bootstrap.bundle.min.js"></script>  -->

</body>
</html>




