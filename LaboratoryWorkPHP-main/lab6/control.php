<?php
mb_internal_encoding('UTF-8');

$dataDir = __DIR__.'/data';
if (!is_dir($dataDir)) @mkdir($dataDir, 0777, true);

function compare_files_stream($f1, $f2, &$error = null) {
  $error = null;
  if (!is_file($f1)) { $error = "Fișierul 1 nu există"; return false; }
  if (!is_file($f2)) { $error = "Fișierul 2 nu există"; return false; }
  if (filesize($f1) !== filesize($f2)) return false;
  $h1 = @fopen($f1, 'rb');
  $h2 = @fopen($f2, 'rb');
  if (!$h1 || !$h2) { $error = "Nu pot deschide fișierele"; return false; }
  $eq = true; $buf = 8192;
  while (!feof($h1) && !feof($h2)) {
    $a = fread($h1, $buf);
    $b = fread($h2, $buf);
    if ($a !== $b) { $eq = false; break; }
  }
  fclose($h1); fclose($h2);
  return $eq;
}

function safe_int_get($path) {
  return is_file($path) ? (int)trim((string)@file_get_contents($path)) : 0;
}
function safe_int_put($path, $val) {
  @file_put_contents($path, (string)$val, LOCK_EX);
}

// Download handler for task 3 (counts + serve sample file)
if (isset($_GET['download'])) {
  $dataDir = __DIR__.'/data';
  if (!is_dir($dataDir)) @mkdir($dataDir, 0777, true);
  $downloadsFile = $dataDir.'/downloads.txt';
  $count = safe_int_get($downloadsFile) + 1;
  safe_int_put($downloadsFile, $count);

  $name = basename((string)$_GET['download']);
  $file = __DIR__ . '/'.$name;
  if (!is_file($file)) $file = __DIR__.'/sample.txt';
  if (!is_file($file)) {
    header('HTTP/1.1 404 Not Found');
    echo 'Fișier lipsă.'; exit;
  }
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="'.basename($file).'"');
  header('Content-Length: '.filesize($file));
  readfile($file);
  exit;
}

// Page view counter (increments on each normal page load)
$viewsFile = $dataDir.'/page_views.txt';
$views = safe_int_get($viewsFile) + 1; safe_int_put($viewsFile, $views);
$downloads = safe_int_get($dataDir.'/downloads.txt');

?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lab 6 | Fișiere și operare</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../styles.css" />
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">Cristina</div>
      <ul class="nav-links">
        <li><a href="../index.html">Projects</a></li>
        <li><a href="../about.html">About</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section class="project-page">
      <h1>Lucrul cu fișiere</h1>
      <p class="project-desc">1) Comparare caractere a două fișiere, 2) Numere distincte din numere.in → numere.out, 3) Contor accesări și descărcări.</p>

      <div class="task-grid">
        <!-- 1. COMPARARE -->
        <article class="task-card" id="l6-1">
          <h3>1. Comparare caracter cu caracter</h3>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="task" value="1" />
            <label>Fișier 1 (încărcare): <input type="file" name="f1" accept=".txt,text/plain" /></label>
            <label>Fișier 2 (încărcare): <input type="file" name="f2" accept=".txt,text/plain" /></label>
            <p style="margin:8px 0; color:#666">Alternativ, nume din server (relative la acest folder):</p>
            <div style="display:flex; gap:10px">
              <input type="text" name="p1" placeholder="ex: sample.txt" />
              <input type="text" name="p2" placeholder="ex: sample.txt" />
            </div>
            <button class="btn" type="submit">Compară</button>
          </form>
          <?php if(($_POST['task']??'')==='1'){
            $err = null; $path1 = null; $path2 = null;
            if (!empty($_FILES['f1']['tmp_name']) && is_uploaded_file($_FILES['f1']['tmp_name'])) $path1 = $_FILES['f1']['tmp_name'];
            if (!empty($_FILES['f2']['tmp_name']) && is_uploaded_file($_FILES['f2']['tmp_name'])) $path2 = $_FILES['f2']['tmp_name'];
            if (!$path1 && !empty($_POST['p1'])) $path1 = realpath(__DIR__ . '/' . trim((string)$_POST['p1']));
            if (!$path2 && !empty($_POST['p2'])) $path2 = realpath(__DIR__ . '/' . trim((string)$_POST['p2']));
            if ($path1 && $path2) {
              $same = compare_files_stream($path1, $path2, $err);
              if ($err) echo '<p>'.htmlspecialchars($err).'</p>';
              else echo '<p>Rezultat: <strong>'.($same?'TRUE (identice)':'FALSE (diferite)').'</strong></p>';
            } else {
              echo '<p>Specificați ambele fișiere (încărcare sau căi relative).</p>';
            }
          } ?>
        </article>

        <!-- 2. NUMERE DISTINCTE -->
        <article class="task-card" id="l6-2">
          <h3>2. Numere distincte și frecvențe (numere.in → numere.out)</h3>
          <form method="post">
            <input type="hidden" name="task" value="2" />
            <label>Conținut pentru numere.in (opțional, înlocuiește fișierul existent):
              <textarea name="nums" rows="4" placeholder="ex: 1 2 2 5 1 7&#10;8 8 8 9"></textarea>
            </label>
            <button class="btn" type="submit">Procesează</button>
            <a class="btn" href="data/numere.out" target="_blank" style="background:#eee;color:#4d0000;border:1px solid #4d0000">Deschide numere.out</a>
          </form>
          <?php if(($_POST['task']??'')==='2'){
            $inFile = $dataDir.'/numere.in';
            $outFile = $dataDir.'/numere.out';
            $numsText = (string)($_POST['nums']??'');
            if (trim($numsText) !== '') {
              @file_put_contents($inFile, $numsText, LOCK_EX);
            }
            if (!is_file($inFile)) { echo '<p>Nu există numere.in. Introdu numere și retrimite.</p>'; }
            else {
              $content = (string)@file_get_contents($inFile);
              preg_match_all('/\d+/', $content, $m);
              $vals = array_map('intval', $m[0] ?? []);
              $freq = [];
              foreach ($vals as $v) $freq[$v] = ($freq[$v]??0) + 1;
              ksort($freq, SORT_NUMERIC);
              $lines = [];
              foreach ($freq as $k=>$v) $lines[] = $k.' '.$v;
              @file_put_contents($outFile, implode(PHP_EOL, $lines).PHP_EOL);
              echo '<p>Distincte: <strong>'.count($freq).'</strong> | Total valori: <strong>'.count($vals).'</strong></p>';
              echo '<pre>'.htmlspecialchars(implode(PHP_EOL, $lines)).'</pre>';
            }
          } ?>
        </article>

        <!-- 3. CONTOR -->
        <article class="task-card" id="l6-3">
          <h3>3. Contor pagină și descărcări</h3>
          <p>Accesări pagină: <strong><?= (int)$views ?></strong></p>
          <p>Descărcări efectuate: <strong><?= (int)$downloads ?></strong></p>
          <div style="display:flex;gap:10px;align-items:center;margin-top:8px;">
            <a class="btn" href="?download=sample.txt">Descarcă fișierul de test</a>
            <small>Fișierul este servit de acest script și contorizat.</small>
          </div>
        </article>

      </div>

      <p style="margin-top:2rem"><a class="back-btn" href="../index.html">Înapoi la proiecte</a></p>
    </section>
  </main>

  <footer>
    <p>© 2025 Cristina — Minimal Web Design</p>
  </footer>
</body>
</html>
