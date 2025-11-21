<?php
mb_internal_encoding('UTF-8');

function parse_date_time($s) {
  $ts = strtotime($s);
  return $ts !== false ? $ts : null;
}

function is_weekend($ts) {
  $w = (int)date('N', $ts); // 6=Sat, 7=Sun
  return $w >= 6;
}

function is_holiday($ts, $holidays) {
  $d = date('m-d', $ts);
  return in_array($d, $holidays, true);
}

function next_business_day($ts, $holidays) {
  do {
    $ts = strtotime('+1 day', $ts);
  } while (is_weekend($ts) || is_holiday($ts, $holidays));
  return $ts;
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dată și Oră</title>
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
    <section class="project-page lab5">
      <h1>Funcții pentru prelucrarea datei și orei</h1>
      <p class="project-desc">Exerciții: livrare în zi lucrătoare, zile până la ziua de naștere și salutări dinamice.</p>

      <div class="task-grid">
        <!-- 1. Data livrării -->
        <article class="task-card" id="t5-1">
          <h3>1. Data livrării</h3>
          <form method="post">
            <input type="hidden" name="task" value="1" />
            <label>Data comenzii:
              <input type="datetime-local" name="order_dt" value="<?= ($_POST['task']??'')==='1' ? htmlspecialchars($_POST['order_dt']??'') : date('Y-m-d\T') . '10:00' ?>" />
            </label>
            <label>Zile libere (MM-DD, separate prin virgulă):
              <input type="text" name="hol" value="<?= ($_POST['task']??'')==='1' ? htmlspecialchars($_POST['hol']??'01-01,03-08') : '01-01,03-08,05-01,12-25' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='1'){
            $raw = (string)($_POST['order_dt']??'');
            $ts = parse_date_time($raw);
            $holidays = array_values(array_filter(array_map('trim', explode(',', (string)($_POST['hol']??'')))));
            if ($ts===null) { echo '<p>Dată invalidă.</p>'; }
            else {
              // Dacă e weekend/zi liberă, setăm livrarea în următoarea zi lucrătoare.
              if (is_weekend($ts) || is_holiday($ts,$holidays)) {
                $ts = next_business_day($ts, $holidays);
                $delivery = date('Y-m-d', $ts);
              } else {
                // Dacă până la 12:00 -> aceeași zi altfel ziua lucrătoare următoare
                $hour = (int)date('H', $ts);
                if ($hour < 12) {
                  $delivery = date('Y-m-d', $ts);
                } else {
                  $ts = next_business_day($ts, $holidays);
                  $delivery = date('Y-m-d', $ts);
                }
              }
              echo '<p>Data livrării: <strong>'.$delivery.'</strong></p>';
            }
          } ?>
        </article>

        <!-- 2. La mulți ani -->
        <article class="task-card" id="t5-2">
          <h3>2. La Mulți Ani</h3>
          <form method="post">
            <input type="hidden" name="task" value="2" />
            <label>Data nașterii:
              <input type="date" name="dob" value="<?= ($_POST['task']??'')==='2' ? htmlspecialchars($_POST['dob']??'2000-03-08') : '2000-03-08' ?>" />
            </label>
            <button class="btn" type="submit">Află</button>
          </form>
          <?php if(($_POST['task']??'')==='2'){
            $dob = (string)($_POST['dob']??'');
            $dob_ts = strtotime($dob);
            if ($dob_ts===false) { echo '<p>Dată invalidă.</p>'; }
            else {
              $today = strtotime(date('Y-m-d'));
              $thisYear = date('Y');
              $nextBDay = strtotime($thisYear.'-'.date('m-d',$dob_ts));
              if ($nextBDay < $today) $nextBDay = strtotime(($thisYear+1).'-'.date('m-d',$dob_ts));
              $days = (int)ceil(($nextBDay - $today)/86400);
              $isToday = $days===0;
              if ($isToday) {
                $age = (int)date('Y') - (int)date('Y',$dob_ts);
                if (strtotime(date('Y').'-'.date('m-d',$dob_ts))>time()) $age--;
                echo '<p><strong>La Mulți Ani!</strong> Împlinești <strong>'.$age.'</strong> ani.</p>';
              } else {
                echo '<p>Zile rămase până la ziua ta: <strong>'.$days.'</strong></p>';
              }
            }
          } ?>
        </article>

        <!-- 3. Salutare -->
        <article class="task-card" id="t5-3">
          <h3>3. Salutare în funcție de oră</h3>
          <form method="post">
            <input type="hidden" name="task" value="3" />
            <label>Oră curentă (opțional):
              <input type="time" name="time" value="<?= ($_POST['task']??'')==='3' ? htmlspecialchars($_POST['time']??'') : '' ?>" />
              <small>(dacă e gol, se folosește ora sistemului)</small>
            </label>
            <button class="btn" type="submit">Salută</button>
          </form>
          <?php if(($_POST['task']??'')==='3'){
            $t = (string)($_POST['time']??'');
            if ($t==='') { $h=(int)date('G'); $m=(int)date('i'); }
            else { [$hh,$mm] = array_map('intval', explode(':',$t.':0')); $h=$hh; $m=$mm; }
            $msg = '';
            $mins = $h*60+$m;
            if ($mins >= 7*60 && $mins < 11*60) $msg = 'Bună dimineața';
            elseif ($mins >= 11*60 && $mins < 18*60) $msg = 'Bună ziua';
            elseif ($mins >= 18*60 && $mins < 22*60) $msg = 'Bună seara';
            elseif ($mins >= 22*60 || $mins < 24*60) $msg = 'Noapte bună';
            if ($mins < 60) $msg = 'De ce nu dormi, mâine ai Programarea Web';
            echo '<p><strong>'.$msg.'</strong></p>';
          } ?>
        </article>

      </div>

      <p style="margin-top:2rem"><a class="back-btn" href="../index.html">Înapoi la proiecte</a></p>
    </section>
  </main>

  <footer>
    <p>© 2025 Cristina — Minimal Web Design</p>
  </footer>

  <script>
    // no-scroll AJAX submit per card
    document.addEventListener('submit', function (e) {
      var form = e.target.closest('form');
      if (!form || (form.method || 'GET').toUpperCase() !== 'POST') return;
      var card = form.closest('.task-card');
      if (!card || !card.id) return;
      e.preventDefault();
      var fd = new FormData(form);
      fetch(window.location.href, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'fetch' } })
        .then(function (r) { return r.text(); })
        .then(function (html) {
          var doc = new DOMParser().parseFromString(html, 'text/html');
          var updated = doc.getElementById(card.id);
          if (updated) card.innerHTML = updated.innerHTML;
        })
        .catch(function () {});
    }, true);
  </script>
</body>
</html>
