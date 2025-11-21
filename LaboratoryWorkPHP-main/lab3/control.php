<?php
mb_internal_encoding('UTF-8');

function parse_numbers($s) {
  $parts = preg_split('/[\s,;]+/', trim((string)$s), -1, PREG_SPLIT_NO_EMPTY);
  return array_map(fn($x) => is_numeric($x) ? 0 + $x : 0, $parts);
}

function parse_integers($s) {
  return array_map('intval', parse_numbers($s));
}

function is_sorted_asc_arr($a) {
  for ($i = 1; $i < count($a); $i++) if ($a[$i] < $a[$i-1]) return false; return true;
}

function bubble_sort_asc($a) {
  $n = count($a);
  for ($i=0; $i<$n-1; $i++)
    for ($j=0; $j<$n-$i-1; $j++)
      if ($a[$j] > $a[$j+1]) [$a[$j],$a[$j+1]] = [$a[$j+1],$a[$j]];
  return $a;
}

function insertion_sort_desc($a) {
  for ($i=1; $i<count($a); $i++) {
    $key = $a[$i]; $j = $i-1;
    while ($j>=0 && $a[$j] < $key) { $a[$j+1] = $a[$j]; $j--; }
    $a[$j+1] = $key;
  }
  return $a;
}

function counting_sort_asc($a) {
  if (!$a) return [];
  $min = $max = $a[0];
  foreach ($a as $v) { $v = (int)$v; if ($v < $min) $min = $v; if ($v > $max) $max = $v; }
  $count = array_fill($min, $max-$min+1, 0);
  foreach ($a as $v) $count[(int)$v]++;
  $res = [];
  for ($i=$min; $i<=$max; $i++) while ($count[$i]-- > 0) $res[] = $i;
  return $res;
}

function mean_positive_odd_positions($a) {
  $sum = 0; $cnt = 0; // positions are 1-based odd
  for ($i=0; $i<count($a); $i++) if ((($i+1)%2)==1 && $a[$i]>0) { $sum += $a[$i]; $cnt++; }
  return $cnt ? $sum/$cnt : 0;
}

function binary_search_sorted($a, $val) {
  $l=0; $r=count($a)-1;
  while ($l <= $r) { $m = intdiv($l+$r, 2); if ($a[$m]==$val) return $m; if ($a[$m] < $val) $l=$m+1; else $r=$m-1; }
  return -1;
}

function dot_product($x,$y){ if(count($x)!==count($y)) return null; $s=0; for($i=0;$i<count($x);$i++) $s+=$x[$i]*$y[$i]; return $s; }
function set_intersection_vals($x,$y){ return array_values(array_unique(array_intersect($x,$y))); }
function set_union_vals($x,$y){ return array_values(array_unique(array_merge($x,$y))); }
function count_y_in_x($x,$y){ $c=0; $set = array_flip(array_values(array_unique($x))); foreach($y as $v) if (isset($set[$v])) $c++; return $c; }
function is_subsequence_xy($x,$y){ $i=0;$j=0; while($i<count($x)&&$j<count($y)){ if($x[$i]==$y[$j]) $i++; $j++; } return $i==count($x); }
function merge_sorted_arrays($a,$b){ $i=0;$j=0;$r=[]; while($i<count($a)&&$j<count($b)){ if($a[$i]<=$b[$j]) $r[]=$a[$i++]; else $r[]=$b[$j++]; } while($i<count($a)) $r[]=$a[$i++]; while($j<count($b)) $r[]=$b[$j++]; return $r; }
function insert_B_into_A_at($A,$B,$poz){ if($poz>=count($A)) return array_merge($A,$B); $start=array_slice($A,0,$poz); $end=array_slice($A,$poz); return array_merge($start,$B,$end); }

function freq_manual_notes($notes){ $freq=array_fill(1,10,0); $sum=0; $n=0; foreach($notes as $v){ $v=(int)$v; if($v>=1 && $v<=10){ $freq[$v]++; $sum+=$v; $n++; } } $avg=$n? $sum/$n : 0; return [$freq,$avg]; }
function freq_builtin_notes($notes){ $filtered=array_filter(array_map('intval',$notes), fn($v)=>$v>=1&&$v<=10); $freq=array_fill(1,10,0); foreach(array_count_values($filtered) as $k=>$v){ if($k>=1&&$k<=10) $freq[$k]=$v; } $avg = $filtered? array_sum($filtered)/count($filtered) : 0; return [$freq,$avg]; }

function simulate_dice($n){ $vals=[]; for($i=0;$i<$n;$i++) $vals[] = random_int(1,6); $freq=array_fill(1,6,0); foreach($vals as $v) $freq[$v]++; return [$vals,$freq]; }

function gcd_via_stacks($a,$b){
  $S1=[]; $S2=[];
  for($i=1;$i<=$a;$i++) if($a%$i==0) array_push($S1,$i); // push divisors in order
  for($j=1;$j<=$b;$j++) if($b%$j==0) array_push($S2,$j);
  // ensure tops are largest
  $g=1;
  while(!empty($S1) && !empty($S2)){
    $x = end($S1); $y = end($S2);
    if ($x==$y){ $g=$x; break; }
    if ($x>$y) array_pop($S1); else array_pop($S2);
  }
  return $g;
}

function primes_with_queues($n){
  if($n<2) return [];
  $C1=[]; $C2=[]; for($i=2;$i<=$n;$i++) array_push($C1,$i);
  while(!empty($C1)){
    $x = array_shift($C1); // dequeue from C1
    array_push($C2,$x);    // enqueue into C2
    $len = count($C1);
    for($i=0;$i<$len;$i++){
      $y = array_shift($C1);
      if ($y % $x != 0) array_push($C1,$y); // keep non-multiples
    }
  }
  return $C2; // primes
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tipul de date Array | Cristina</title>
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
      <h1>Tipul de date Array</h1>
      <p class="project-desc">Lucrare la tema: operații fundamentale cu tablouri (1–5) și exerciții avansate (6–7). Completați câmpurile și trimiteți fiecare card.</p>

      <div class="task-grid">
        <!-- 1a Min -->
        <article class="task-card" id="t-1a">
          <h3>1.a Elementul minim</h3>
          <form method="post" data-anchor="t-1a">
            <input type="hidden" name="task" value="1a" />
            <label>A (numere separate prin virgulă):
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1a' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9,-2,8' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='1a'){ $A=parse_numbers($_POST['A']??''); if($A){ $min=$A[0]; foreach($A as $x) if($x<$min) $min=$x; echo '<p>Minim: <strong>'.htmlspecialchars((string)$min).'</strong></p>'; } } ?>
        </article>

        <!-- 1b Min & Max -->
        <article class="task-card" id="t-1b">
          <h3>1.b Minim și maxim</h3>
          <form method="post" data-anchor="t-1b">
            <input type="hidden" name="task" value="1b" />
            <label>A:
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1b' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9,-2,8' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='1b'){ $A=parse_numbers($_POST['A']??''); if($A){ $min=$max=$A[0]; foreach($A as $x){ if($x<$min)$min=$x; if($x>$max)$max=$x; } echo '<p>Min: <strong>'.$min.'</strong> · Max: <strong>'.$max.'</strong></p>'; } } ?>
        </article>

        <!-- 1c sorted asc -->
        <article class="task-card" id="t-1c">
          <h3>1.c Sunt sortate crescător?</h3>
          <form method="post" data-anchor="t-1c">
            <input type="hidden" name="task" value="1c" />
            <label>A:
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1c' ? htmlspecialchars($_POST['A']) : '1,2,3,3,5' ?>" />
            </label>
            <button class="btn" type="submit">Verifică</button>
          </form>
          <?php if(($_POST['task']??'')==='1c'){ $A=parse_numbers($_POST['A']??''); echo '<p>Răspuns: <strong>'.(is_sorted_asc_arr($A)?'Da':'Nu').'</strong></p>'; } ?>
        </article>

        <!-- 1d mean -->
        <article class="task-card" id="t-1d">
          <h3>1.d Media elementelor pozitive pe poziții impare</h3>
          <form method="post" data-anchor="t-1d">
            <input type="hidden" name="task" value="1d" />
            <label>A:
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1d' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9,-2,8' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='1d'){ $A=parse_numbers($_POST['A']??''); echo '<p>Media: <strong>'.(string)mean_positive_odd_positions($A).'</strong></p>'; } ?>
        </article>

        <!-- 1e bubble sort -->
        <article class="task-card" id="t-1e">
          <h3>1.e Ordonare crescător — BubbleSort</h3>
          <form method="post" data-anchor="t-1e">
            <input type="hidden" name="task" value="1e" />
            <label>A:
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1e' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9,-2,8' ?>" />
            </label>
            <button class="btn" type="submit">Sortează</button>
          </form>
          <?php if(($_POST['task']??'')==='1e'){ $A=parse_numbers($_POST['A']??''); echo '<pre>'.htmlspecialchars('['.implode(', ', bubble_sort_asc($A)).']').'</pre>'; } ?>
        </article>

        <!-- 1f insertion desc -->
        <article class="task-card" id="t-1f">
          <h3>1.f Ordonare descrescător — Inserție</h3>
          <form method="post" data-anchor="t-1f">
            <input type="hidden" name="task" value="1f" />
            <label>A:
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1f' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9,-2,8' ?>" />
            </label>
            <button class="btn" type="submit">Sortează</button>
          </form>
          <?php if(($_POST['task']??'')==='1f'){ $A=parse_numbers($_POST['A']??''); echo '<pre>'.htmlspecialchars('['.implode(', ', insertion_sort_desc($A)).']').'</pre>'; } ?>
        </article>

        <!-- 1g counting sort -->
        <article class="task-card" id="t-1g">
          <h3>1.g Ordonare crescător — Numărare</h3>
          <form method="post" data-anchor="t-1g">
            <input type="hidden" name="task" value="1g" />
            <label>A (numere întregi):
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1g' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9,-2,8' ?>" />
            </label>
            <button class="btn" type="submit">Sortează</button>
          </form>
          <?php if(($_POST['task']??'')==='1g'){ $A=parse_integers($_POST['A']??''); echo '<pre>'.htmlspecialchars('['.implode(', ', counting_sort_asc($A)).']').'</pre>'; } ?>
        </article>

        <!-- 1h belongs -->
        <article class="task-card" id="t-1h">
          <h3>1.h Aparține valoarea?</h3>
          <form method="post" data-anchor="t-1h">
            <input type="hidden" name="task" value="1h" />
            <label>A:
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1h' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9,-2,8' ?>" />
            </label>
            <label>Valoare:
              <input type="number" name="v" value="<?= isset($_POST['v']) && ($_POST['task']??'')==='1h' ? htmlspecialchars($_POST['v']) : '5' ?>" />
            </label>
            <button class="btn" type="submit">Verifică</button>
          </form>
          <?php if(($_POST['task']??'')==='1h'){ $A=parse_numbers($_POST['A']??''); $v=0+($_POST['v']??0); echo '<p>Rezultat: <strong>'.(in_array($v,$A,true)?'Da':'Nu').'</strong></p>'; } ?>
        </article>

        <!-- 1i is set -->
        <article class="task-card" id="t-1i">
          <h3>1.i Tabloul poate fi considerat mulțime?</h3>
          <form method="post" data-anchor="t-1i">
            <input type="hidden" name="task" value="1i" />
            <label>A:
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1i' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9,-2,8' ?>" />
            </label>
            <button class="btn" type="submit">Verifică</button>
          </form>
          <?php if(($_POST['task']??'')==='1i'){ $A=parse_numbers($_POST['A']??''); echo '<p>Rezultat: <strong>'.(count($A)==count(array_unique($A, SORT_REGULAR))?'Da':'Nu').'</strong></p>'; } ?>
        </article>

        <!-- 1j binary search -->
        <article class="task-card" id="t-1j">
          <h3>1.j Căutare binară (ipoteza: A sortat crescător)</h3>
          <form method="post" data-anchor="t-1j">
            <input type="hidden" name="task" value="1j" />
            <label>A (sortat crescător):
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='1j' ? htmlspecialchars($_POST['A']) : '1,1,3,4,5,8,9' ?>" />
            </label>
            <label>Valoare:
              <input type="number" name="v" value="<?= isset($_POST['v']) && ($_POST['task']??'')==='1j' ? htmlspecialchars($_POST['v']) : '5' ?>" />
            </label>
            <button class="btn" type="submit">Caută</button>
          </form>
          <?php if(($_POST['task']??'')==='1j'){ $A=parse_numbers($_POST['A']??''); $v=0+($_POST['v']??0); $ok=is_sorted_asc_arr($A); $idx=$ok? binary_search_sorted($A,$v) : -1; echo '<p>'.($ok? 'Index: <strong>'.$idx.'</strong>' : 'A nu este sortat crescător').'</p>'; } ?>
        </article>

        <!-- 2a dot product -->
        <article class="task-card" id="t-2a">
          <h3>2.a Suma produselor S = Σ X[i]*Y[i]</h3>
          <form method="post" data-anchor="t-2a">
            <input type="hidden" name="task" value="2a" />
            <label>X:
              <input type="text" name="X" value="<?= isset($_POST['X']) && ($_POST['task']??'')==='2a' ? htmlspecialchars($_POST['X']) : '1,2,3,4' ?>" />
            </label>
            <label>Y:
              <input type="text" name="Y" value="<?= isset($_POST['Y']) && ($_POST['task']??'')==='2a' ? htmlspecialchars($_POST['Y']) : '3,4,5,6' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='2a'){ $X=parse_numbers($_POST['X']??''); $Y=parse_numbers($_POST['Y']??''); $s=dot_product($X,$Y); echo '<p>'.($s===null? 'Lungimi diferite!' : 'S = <strong>'.$s.'</strong>').'</p>'; } ?>
        </article>

        <!-- 2b intersect -->
        <article class="task-card" id="t-2b">
          <h3>2.b Intersecția mulțimilor X și Y</h3>
          <form method="post" data-anchor="t-2b">
            <input type="hidden" name="task" value="2b" />
            <label>X:
              <input type="text" name="X" value="<?= isset($_POST['X']) && ($_POST['task']??'')==='2b' ? htmlspecialchars($_POST['X']) : '1,2,3,4' ?>" />
            </label>
            <label>Y:
              <input type="text" name="Y" value="<?= isset($_POST['Y']) && ($_POST['task']??'')==='2b' ? htmlspecialchars($_POST['Y']) : '3,4,5,6' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='2b'){ $X=parse_numbers($_POST['X']??''); $Y=parse_numbers($_POST['Y']??''); echo '<pre>'.htmlspecialchars('['.implode(', ', set_intersection_vals($X,$Y)).']').'</pre>'; } ?>
        </article>

        <!-- 2c union -->
        <article class="task-card" id="t-2c">
          <h3>2.c Reuniunea mulțimilor X și Y</h3>
          <form method="post" data-anchor="t-2c">
            <input type="hidden" name="task" value="2c" />
            <label>X:
              <input type="text" name="X" value="<?= isset($_POST['X']) && ($_POST['task']??'')==='2c' ? htmlspecialchars($_POST['X']) : '1,2,3,4' ?>" />
            </label>
            <label>Y:
              <input type="text" name="Y" value="<?= isset($_POST['Y']) && ($_POST['task']??'')==='2c' ? htmlspecialchars($_POST['Y']) : '3,4,5,6' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='2c'){ $X=parse_numbers($_POST['X']??''); $Y=parse_numbers($_POST['Y']??''); echo '<pre>'.htmlspecialchars('['.implode(', ', set_union_vals($X,$Y)).']').'</pre>'; } ?>
        </article>

        <!-- 2d count Y in X -->
        <article class="task-card" id="t-2d">
          <h3>2.d Câte elemente din Y apar în X?</h3>
          <form method="post" data-anchor="t-2d">
            <input type="hidden" name="task" value="2d" />
            <label>X:
              <input type="text" name="X" value="<?= isset($_POST['X']) && ($_POST['task']??'')==='2d' ? htmlspecialchars($_POST['X']) : '1,2,3,4' ?>" />
            </label>
            <label>Y:
              <input type="text" name="Y" value="<?= isset($_POST['Y']) && ($_POST['task']??'')==='2d' ? htmlspecialchars($_POST['Y']) : '3,4,5,6' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='2d'){ $X=parse_numbers($_POST['X']??''); $Y=parse_numbers($_POST['Y']??''); echo '<p>Număr: <strong>'.count_y_in_x($X,$Y).'</strong></p>'; } ?>
        </article>

        <!-- 2e subsequence -->
        <article class="task-card" id="t-2e">
          <h3>2.e X este subșir în Y?</h3>
          <form method="post" data-anchor="t-2e">
            <input type="hidden" name="task" value="2e" />
            <label>X:
              <input type="text" name="X" value="<?= isset($_POST['X']) && ($_POST['task']??'')==='2e' ? htmlspecialchars($_POST['X']) : '1,3,5' ?>" />
            </label>
            <label>Y:
              <input type="text" name="Y" value="<?= isset($_POST['Y']) && ($_POST['task']??'')==='2e' ? htmlspecialchars($_POST['Y']) : '1,2,3,4,5,6' ?>" />
            </label>
            <button class="btn" type="submit">Verifică</button>
          </form>
          <?php if(($_POST['task']??'')==='2e'){ $X=parse_numbers($_POST['X']??''); $Y=parse_numbers($_POST['Y']??''); echo '<p>Rezultat: <strong>'.(is_subsequence_xy($X,$Y)?'Da':'Nu').'</strong></p>'; } ?>
        </article>

        <!-- 2f merge after sorting -->
        <article class="task-card" id="t-2f">
          <h3>2.f Sortează și interclasează</h3>
          <form method="post" data-anchor="t-2f">
            <input type="hidden" name="task" value="2f" />
            <label>X:
              <input type="text" name="X" value="<?= isset($_POST['X']) && ($_POST['task']??'')==='2f' ? htmlspecialchars($_POST['X']) : '1,4,2,8' ?>" />
            </label>
            <label>Y:
              <input type="text" name="Y" value="<?= isset($_POST['Y']) && ($_POST['task']??'')==='2f' ? htmlspecialchars($_POST['Y']) : '3,6,5' ?>" />
            </label>
            <button class="btn" type="submit">Interclasează</button>
          </form>
          <?php if(($_POST['task']??'')==='2f'){ $X=parse_numbers($_POST['X']??''); $Y=parse_numbers($_POST['Y']??''); sort($X); sort($Y); echo '<pre>'.htmlspecialchars('['.implode(', ', merge_sorted_arrays($X,$Y)).']').'</pre>'; } ?>
        </article>

        <!-- 3 insert B into A at Poz -->
        <article class="task-card" id="t-3">
          <h3>3. Inserează elementele lui B în A de la poziția Poz</h3>
          <form method="post" data-anchor="t-3">
            <input type="hidden" name="task" value="3" />
            <label>A:
              <input type="text" name="A" value="<?= isset($_POST['A']) && ($_POST['task']??'')==='3' ? htmlspecialchars($_POST['A']) : '3,1,4,1,5,9' ?>" />
            </label>
            <label>B:
              <input type="text" name="B" value="<?= isset($_POST['B']) && ($_POST['task']??'')==='3' ? htmlspecialchars($_POST['B']) : '7,8,9' ?>" />
            </label>
            <label>Poz:
              <input type="number" name="poz" min="0" value="<?= isset($_POST['poz']) && ($_POST['task']??'')==='3' ? htmlspecialchars($_POST['poz']) : '3' ?>" />
            </label>
            <button class="btn" type="submit">Inserează</button>
          </form>
          <?php if(($_POST['task']??'')==='3'){ $A=parse_numbers($_POST['A']??''); $B=parse_numbers($_POST['B']??''); $poz=max(0,(int)($_POST['poz']??0)); echo '<pre>'.htmlspecialchars('['.implode(', ', insert_B_into_A_at($A,$B,$poz)).']').'</pre>'; } ?>
        </article>

        <!-- 4 notes frequency and average -->
        <article class="task-card" id="t-4">
          <h3>4. Frecvența notelor și media (două variante)</h3>
          <form method="post" data-anchor="t-4">
            <input type="hidden" name="task" value="4" />
            <label>NOTE (1–10):
              <input type="text" name="notes" value="<?= isset($_POST['notes']) && ($_POST['task']??'')==='4' ? htmlspecialchars($_POST['notes']) : '7,8,9,5,10,7,6,8,9,7,5,6,7,8,9,10,4,3,7,8,9,6,5,7,8,9,10,7' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='4'){ $N=parse_integers($_POST['notes']??''); [$f1,$avg1]=freq_manual_notes($N); [$f2,$avg2]=freq_builtin_notes($N); echo '<p><strong>Varianta 1</strong> (fără funcții tablouri):</p>'; echo '<pre>'.htmlspecialchars(json_encode($f1, JSON_UNESCAPED_UNICODE))."\nMedia= ".$avg1.'</pre>'; echo '<p><strong>Varianta 2</strong> (array_count_values, array_sum):</p>'; echo '<pre>'.htmlspecialchars(json_encode($f2, JSON_UNESCAPED_UNICODE))."\nMedia= ".$avg2.'</pre>'; } ?>
        </article>

        <!-- 5 dice simulation -->
        <article class="task-card" id="t-5">
          <h3>5. Simulare aruncare zar (N ori)</h3>
          <form method="post" data-anchor="t-5" style="display:flex;gap:10px;align-items:center;">
            <input type="hidden" name="task" value="5" />
            <label>N:
              <input type="number" name="n" min="1" value="<?= isset($_POST['n']) && ($_POST['task']??'')==='5' ? htmlspecialchars($_POST['n']) : '20' ?>" style="width:120px;" />
            </label>
            <button class="btn" type="submit">Simulează</button>
          </form>
          <?php if(($_POST['task']??'')==='5'){ $n=max(1,(int)($_POST['n']??1)); [$vals,$freq]=simulate_dice($n); echo '<p>Valori:</p><pre>'.htmlspecialchars('['.implode(', ',$vals).']').'</pre>'; echo '<p>Frecvențe:</p><pre>'.htmlspecialchars(json_encode($freq, JSON_UNESCAPED_UNICODE)).'</pre>'; } ?>
        </article>

        <!-- 6 GCD via stacks -->
        <article class="task-card" id="t-6">
          <h3>6. CMMDC cu stive (S1, S2)</h3>
          <form method="post" data-anchor="t-6">
            <input type="hidden" name="task" value="6" />
            <label>a:
              <input type="number" name="a" min="1" value="<?= isset($_POST['a']) && ($_POST['task']??'')==='6' ? htmlspecialchars($_POST['a']) : '84' ?>" />
            </label>
            <label>b:
              <input type="number" name="b" min="1" value="<?= isset($_POST['b']) && ($_POST['task']??'')==='6' ? htmlspecialchars($_POST['b']) : '126' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if(($_POST['task']??'')==='6'){ $a=max(1,(int)($_POST['a']??1)); $b=max(1,(int)($_POST['b']??1)); $g=gcd_via_stacks($a,$b); echo '<p>Cel mai mare divizor comun: <strong>'.$g.'</strong></p>'; } ?>
        </article>

        <!-- 7 primes via queues -->
        <article class="task-card" id="t-7">
          <h3>7. Numere prime în [2, n] cu cozi</h3>
          <form method="post" data-anchor="t-7" style="display:flex;gap:10px;align-items:center;">
            <input type="hidden" name="task" value="7" />
            <label>n:
              <input type="number" name="n" min="2" value="<?= isset($_POST['n']) && ($_POST['task']??'')==='7' ? htmlspecialchars($_POST['n']) : '50' ?>" style="width:120px;" />
            </label>
            <button class="btn" type="submit">Generează</button>
          </form>
          <?php if(($_POST['task']??'')==='7'){ $n=max(2,(int)($_POST['n']??2)); $pr=primes_with_queues($n); echo '<pre>'.htmlspecialchars('['.implode(', ',$pr).']').'</pre>'; } ?>
        </article>

      </div>

      <p style="margin-top:2rem"><a class="back-btn" href="../index.html">Înapoi la proiecte</a></p>
    </section>
  </main>

  <footer>
    <p>© 2025 Cristina — Minimal Web Design</p>
  </footer>
  <script>
    // AJAX submit per-card to avoid any page scroll/jump
    document.addEventListener('submit', function (e) {
      var form = e.target.closest('form');
      if (!form || (form.method || 'GET').toUpperCase() !== 'POST') return;
      var card = form.closest('.task-card');
      if (!card || !card.id) return;
      e.preventDefault();

      var fd = new FormData(form);
      fetch(window.location.href, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'fetch' }
      })
      .then(function (res) { return res.text(); })
      .then(function (html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var updated = doc.getElementById(card.id);
        if (updated) {
          card.innerHTML = updated.innerHTML; // replace card content only
        }
      })
      .catch(function () { /* ignore errors; no navigation change */ });
    }, true);
  </script>
</body>
</html>
