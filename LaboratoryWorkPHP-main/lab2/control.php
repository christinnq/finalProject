<?php
mb_internal_encoding('UTF-8');
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lab 2 | Structuri de control</title>
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
      <h1>Lucrare 2 — Structuri de control și repetitive</h1>
      <p class="project-desc">Exerciții 1–15. Fiecare card are un formular cu valori implicite; poți modifica și reevalua fără ca pagina să se miște.</p>

      <div class="task-grid">
        <!-- 1 -->
        <article class="task-card" id="t2-1">
          <h3>1. Evaluarea expresiei de paritate</h3>
          <form method="post">
            <input type="hidden" name="task" value="1" />
            <label>a: <input type="number" name="a" value="<?= ($_POST['task']??'')==='1' ? (int)($_POST['a']??5) : 5 ?>" /></label>
            <label>b: <input type="number" name="b" value="<?= ($_POST['task']??'')==='1' ? (int)($_POST['b']??10) : 10 ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='1'){ $a=(int)($_POST['a']??0); $b=(int)($_POST['b']??0); $rez = (($a%2==0)&&($b%2==0)) || (($a%2==1)&&($b%2==1)); echo '<p>Rezultat: <strong>'.($rez?'adevărat':'fals').'</strong></p>'; } ?>
        </article>

        <!-- 2 -->
        <article class="task-card" id="t2-2">
          <h3>2. x și y sunt consecutive?</h3>
          <form method="post">
            <input type="hidden" name="task" value="2" />
            <label>x: <input type="number" name="x" value="<?= ($_POST['task']??'')==='2' ? (int)($_POST['x']??7) : 7 ?>" /></label>
            <label>y: <input type="number" name="y" value="<?= ($_POST['task']??'')==='2' ? (int)($_POST['y']??8) : 8 ?>" /></label>
            <button class="btn" type="submit">Verifică</button>
          </form>
          <?php if(($_POST['task']??'')==='2'){ $x=(int)($_POST['x']??0); $y=(int)($_POST['y']??0); echo '<p><strong>'.(abs($x-$y)==1?'adevărat':'fals').'</strong></p>'; } ?>
        </article>

        <!-- 3 -->
        <article class="task-card" id="t2-3">
          <h3>3. Interschimbare circulară la dreapta (a,b,c → c,a,b)</h3>
          <form method="post">
            <input type="hidden" name="task" value="3" />
            <label>a: <input type="text" name="a" value="<?= ($_POST['task']??'')==='3' ? htmlspecialchars($_POST['a']??'3') : '3' ?>" /></label>
            <label>b: <input type="text" name="b" value="<?= ($_POST['task']??'')==='3' ? htmlspecialchars($_POST['b']??'16') : '16' ?>" /></label>
            <label>c: <input type="text" name="c" value="<?= ($_POST['task']??'')==='3' ? htmlspecialchars($_POST['c']??'4.2') : '4.2' ?>" /></label>
            <button class="btn" type="submit">Schimbă</button>
          </form>
          <?php if(($_POST['task']??'')==='3'){ $a=$_POST['a']??0; $b=$_POST['b']??0; $c=$_POST['c']??0; $na=$c; $nb=$a; $nc=$b; echo '<p>a=<strong>'.$na.'</strong>, b=<strong>'.$nb.'</strong>, c=<strong>'.$nc.'</strong></p>'; } ?>
        </article>

        <!-- 4 -->
        <article class="task-card" id="t2-4">
          <h3>4. Suma primelor n numere pare</h3>
          <form method="post">
            <input type="hidden" name="task" value="4" />
            <label>n: <input type="number" name="n" min="0" value="<?= ($_POST['task']??'')==='4' ? (int)($_POST['n']??4) : 4 ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='4'){ $n=max(0,(int)($_POST['n']??0)); $s=0; for($i=1;$i<=$n;$i++) $s+=2*$i; echo '<p>Suma: <strong>'.$s.'</strong></p>'; } ?>
        </article>

        <!-- 5 -->
        <article class="task-card" id="t2-5">
          <h3>5. Verifică triunghiul, aria, perimetrul și tipul</h3>
          <form method="post">
            <input type="hidden" name="task" value="5" />
            <label>a: <input type="number" step="any" name="a" value="<?= ($_POST['task']??'')==='5' ? htmlspecialchars($_POST['a']??'3') : '3' ?>" /></label>
            <label>b: <input type="number" step="any" name="b" value="<?= ($_POST['task']??'')==='5' ? htmlspecialchars($_POST['b']??'4') : '4' ?>" /></label>
            <label>c: <input type="number" step="any" name="c" value="<?= ($_POST['task']??'')==='5' ? htmlspecialchars($_POST['c']??'5') : '5' ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='5'){ $a=(float)($_POST['a']??0); $b=(float)($_POST['b']??0); $c=(float)($_POST['c']??0); if($a+$b>$c && $a+$c>$b && $b+$c>$a){ $p=$a+$b+$c; $s=0.5*$p; $aria=sqrt($s*($s-$a)*($s-$b)*($s-$c)); $type='Oarecare'; if(abs($a-$b)<1e-9 && abs($b-$c)<1e-9) $type='Echilateral'; elseif(abs($a-$b)<1e-9 || abs($a-$c)<1e-9 || abs($b-$c)<1e-9) $type='Isoscel'; $arr=[$a,$b,$c]; sort($arr); if(abs($arr[0]**2 + $arr[1]**2 - $arr[2]**2) < 1e-9) $type='Dreptunghic'; echo '<p>Perimetru: <strong>'.$p.'</strong></p><p>Aria: <strong>'.$aria.'</strong></p><p>Tip: <strong>'.$type.'</strong></p>'; } else { echo '<p>Nu formează triunghi.</p>'; } } ?>
        </article>

        <!-- 6 -->
        <article class="task-card" id="t2-6">
          <h3>6. Perimetru și aria triunghiului</h3>
          <form method="post">
            <input type="hidden" name="task" value="6" />
            <label>a: <input type="number" step="any" name="a" value="<?= ($_POST['task']??'')==='6' ? htmlspecialchars($_POST['a']??'3') : '3' ?>" /></label>
            <label>b: <input type="number" step="any" name="b" value="<?= ($_POST['task']??'')==='6' ? htmlspecialchars($_POST['b']??'4') : '4' ?>" /></label>
            <label>c: <input type="number" step="any" name="c" value="<?= ($_POST['task']??'')==='6' ? htmlspecialchars($_POST['c']??'5') : '5' ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='6'){ $a=(float)($_POST['a']??0); $b=(float)($_POST['b']??0); $c=(float)($_POST['c']??0); $p=$a+$b+$c; $s=0.5*$p; $aria=sqrt(max(0,$s*($s-$a)*($s-$b)*($s-$c))); echo '<p>P=<strong>'.$p.'</strong>, A=<strong>'.$aria.'</strong></p>'; } ?>
        </article>

        <!-- 7 -->
        <article class="task-card" id="t2-7">
          <h3>7. Ultima cifră a lui n</h3>
          <form method="post">
            <input type="hidden" name="task" value="7" />
            <label>n: <input type="number" name="n" value="<?= ($_POST['task']??'')==='7' ? (int)($_POST['n']??127) : 127 ?>" /></label>
            <button class="btn" type="submit">Afișează</button>
          </form>
          <?php if(($_POST['task']??'')==='7'){ $n=(int)($_POST['n']??0); echo '<p>Ultima cifră: <strong>'.(abs($n)%10).'</strong></p>'; } ?>
        </article>

        <!-- 8 -->
        <article class="task-card" id="t2-8">
          <h3>8. Media numerelor negative impare > -n</h3>
          <form method="post">
            <input type="hidden" name="task" value="8" />
            <label>n: <input type="number" name="n" min="1" value="<?= ($_POST['task']??'')==='8' ? (int)($_POST['n']??10) : 10 ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='8'){ $n=max(1,(int)($_POST['n']??1)); $sum=0;$cnt=0; for($i=-$n+1;$i<0;$i++){ if($i%2!=0){ $sum+=$i; $cnt++; } } echo '<p>'.($cnt? 'Media: <strong>'.($sum/$cnt).'</strong>' : 'Nu există').'</p>'; } ?>
        </article>

        <!-- 9 -->
        <article class="task-card" id="t2-9">
          <h3>9. Afișează în ordine descrescătoare</h3>
          <form method="post">
            <input type="hidden" name="task" value="9" />
            <label>a: <input type="number" step="any" name="a" value="<?= ($_POST['task']??'')==='9' ? htmlspecialchars($_POST['a']??'7') : '7' ?>" /></label>
            <label>b: <input type="number" step="any" name="b" value="<?= ($_POST['task']??'')==='9' ? htmlspecialchars($_POST['b']??'2') : '2' ?>" /></label>
            <label>c: <input type="number" step="any" name="c" value="<?= ($_POST['task']??'')==='9' ? htmlspecialchars($_POST['c']??'10') : '10' ?>" /></label>
            <button class="btn" type="submit">Afișează</button>
          </form>
          <?php if(($_POST['task']??'')==='9'){ $arr=[(float)($_POST['a']??0),(float)($_POST['b']??0),(float)($_POST['c']??0)]; rsort($arr); echo '<pre>'.htmlspecialchars(implode(' ', $arr)).'</pre>'; } ?>
        </article>

        <!-- 10 -->
        <article class="task-card" id="t2-10">
          <h3>10. Câte numere divizibile prin 3 sunt în [a, b]?</h3>
          <form method="post">
            <input type="hidden" name="task" value="10" />
            <label>a: <input type="number" name="a" value="<?= ($_POST['task']??'')==='10' ? (int)($_POST['a']??1) : 1 ?>" /></label>
            <label>b: <input type="number" name="b" value="<?= ($_POST['task']??'')==='10' ? (int)($_POST['b']??20) : 20 ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='10'){ $a=(int)$_POST['a']; $b=(int)$_POST['b']; if($a>$b){$t=$a;$a=$b;$b=$t;} $count=0; for($i=$a;$i<=$b;$i++) if($i%3==0) $count++; echo '<p>Număr: <strong>'.$count.'</strong></p>'; } ?>
        </article>

        <!-- 11 -->
        <article class="task-card" id="t2-11">
          <h3>11. Dreptunghi — perimetru, arie, diagonală</h3>
          <form method="post">
            <input type="hidden" name="task" value="11" />
            <label>a: <input type="number" step="any" name="a" value="<?= ($_POST['task']??'')==='11' ? htmlspecialchars($_POST['a']??'3') : '3' ?>" /></label>
            <label>b: <input type="number" step="any" name="b" value="<?= ($_POST['task']??'')==='11' ? htmlspecialchars($_POST['b']??'4') : '4' ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='11'){ $a=(float)($_POST['a']??0); $b=(float)($_POST['b']??0); $p=2*($a+$b); $aria=$a*$b; $d=sqrt($a*$a+$b*$b); echo '<p>P=<strong>'.$p.'</strong>, A=<strong>'.$aria.'</strong>, D=<strong>'.$d.'</strong></p>'; } ?>
        </article>

        <!-- 12 -->
        <article class="task-card" id="t2-12">
          <h3>12. Modulul unui număr</h3>
          <form method="post">
            <input type="hidden" name="task" value="12" />
            <label>x: <input type="number" step="any" name="x" value="<?= ($_POST['task']??'')==='12' ? htmlspecialchars($_POST['x']??'-15') : '-15' ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='12'){ $x=(float)($_POST['x']??0); echo '<p>|x| = <strong>'.abs($x).'</strong></p>'; } ?>
        </article>

        <!-- 13 -->
        <article class="task-card" id="t2-13">
          <h3>13. An bisect</h3>
          <form method="post">
            <input type="hidden" name="task" value="13" />
            <label>an: <input type="number" name="n" value="<?= ($_POST['task']??'')==='13' ? (int)($_POST['n']??2024) : 2024 ?>" /></label>
            <button class="btn" type="submit">Verifică</button>
          </form>
          <?php if(($_POST['task']??'')==='13'){ $n=(int)($_POST['n']??0); $bis=(($n%4==0 && $n%100!=0) || $n%400==0); echo '<p><strong>'.($bis?'Bisect':'Nu bisect').'</strong></p>'; } ?>
        </article>

        <!-- 14 -->
        <article class="task-card" id="t2-14">
          <h3>14. Suma primelor n impare</h3>
          <form method="post">
            <input type="hidden" name="task" value="14" />
            <label>n: <input type="number" name="n" min="0" value="<?= ($_POST['task']??'')==='14' ? (int)($_POST['n']??4) : 4 ?>" /></label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='14'){ $n=max(0,(int)($_POST['n']??0)); $s=0; for($i=0;$i<$n;$i++) $s+=2*$i+1; echo '<p>Suma: <strong>'.$s.'</strong></p>'; } ?>
        </article>

        <!-- 15 -->
        <article class="task-card" id="t2-15">
          <h3>15. N are două cifre?</h3>
          <form method="post">
            <input type="hidden" name="task" value="15" />
            <label>n: <input type="number" name="n" value="<?= ($_POST['task']??'')==='15' ? (int)($_POST['n']??57) : 57 ?>" /></label>
            <button class="btn" type="submit">Verifică</button>
          </form>
          <?php if(($_POST['task']??'')==='15'){ $n=(int)($_POST['n']??0); echo '<p>Rezultat: <strong>'.($n>=10 && $n<=99 ? 'Da' : 'Nu').'</strong></p>'; } ?>
        </article>

      </div>

      <p style="margin-top:2rem"><a class="back-btn" href="../index.html">Înapoi la proiecte</a></p>
    </section>
  </main>

  <footer>
    <p>© 2025 Cristina — Minimal Web Design</p>
  </footer>

  <script>
    // no-scroll: submit each card via AJAX and replace only that card's HTML
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
