<?php
mb_internal_encoding('UTF-8');

function is_vowel($ch) { return (bool)preg_match('/[AEIOUaeiou]/u', $ch); }
function is_letter($ch) { return (bool)preg_match('/\p{L}/u', $ch); }

function caesar_shift($text, $k) {
  $out=''; $k=(int)$k;
  for ($i=0,$n=mb_strlen($text); $i<$n; $i++) {
    $ch=mb_substr($text,$i,1);
    if ($ch>='a' && $ch<='z')      $out .= chr(((ord($ch)-97+$k)%26)+97);
    elseif ($ch>='A' && $ch<='Z')  $out .= chr(((ord($ch)-65+$k)%26)+65);
    else                           $out .= $ch;
  }
  return $out;
}

function parse_expr_coeffs($expr){
  $expr=trim($expr);
  $expr=preg_replace('/\s+/', '', $expr);
  $expr=str_replace('-', '+-', $expr);
  if ($expr!=='' && $expr[0]==='+') $expr=substr($expr,1);
  $parts=array_filter(explode('+',$expr),fn($t)=>$t!=='' && $t!=='+');
  $a=0.0; $b=0.0;
  foreach($parts as $t){
    if (str_contains($t,'x')){
      $coef=str_replace('x','',$t);
      if ($coef===''||$coef==='+') $coef=1; if ($coef==='-') $coef=-1;
      $a+=(float)$coef;
    } else { $b+=(float)$t; }
  }
  return [$a,$b];
}

function solve_linear_eq($eq){
  $eq=trim($eq); if($eq==='') return null; $parts=explode('=',$eq); if(count($parts)!==2) return [null,null,'format'];
  [$a1,$b1]=parse_expr_coeffs($parts[0]); [$a2,$b2]=parse_expr_coeffs($parts[1]);
  $a=$a1-$a2; $b=$b1-$b2;
  if (abs($a)<1e-12 && abs($b)<1e-12) return ['inf',null,'inf'];
  if (abs($a)<1e-12) return [null,null,'none'];
  return [-$b/$a,[$a,$b],null];
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tipul de date String | Cristina</title>
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
      <h1>Tipul de date String</h1>
      <p class="project-desc">Exerciții 1–14 cu prelucrarea șirurilor și a datelor text. Mai jos, fiecare cerință are un mic formular.</p>

      <div class="task-grid">

        <article class="task-card">
          <h3>1. Raportul dintre numărul vocalelor și numărul cifrelor</h3>
          <form method="post">
            <input type="hidden" name="task" value="1" />
            <label>Șirul S:
              <input type="text" name="s" value="<?= isset($_POST['s']) && ($_POST['task']??'')==='1' ? htmlspecialchars($_POST['s']) : 'Ionel are 10 lei noi' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='1'){ $s=(string)($_POST['s']??'');
            preg_match_all('/[AEIOUaeiou]/u',$s,$m1); preg_match_all('/[0-9]/',$s,$m2);
            $v=count($m1[0]); $d=count($m2[0]); $rap=$d>0? intdiv($v,$d) : 'nedefinit (fără cifre)';
            echo '<p>Vocale: '.$v.'; Cifre: '.$d.'; Raport (parte întreagă): <strong>'.$rap.'</strong></p>'; } ?>
        </article>

        <article class="task-card">
          <h3>2. Poziția primei litere mari (0‑based)</h3>
          <form method="post">
            <input type="hidden" name="task" value="2" />
            <label>Șirul S:
              <input type="text" name="s" value="<?= isset($_POST['s']) && ($_POST['task']??'')==='2' ? htmlspecialchars($_POST['s']) : 'tatal lui Gigel merge la Metrou' ?>" />
            </label>
            <button class="btn" type="submit">Găsește</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='2'){ $s=(string)($_POST['s']??''); $pos=-1; for($i=0,$n=mb_strlen($s);$i<$n;$i++){ $ch=mb_substr($s,$i,1); if(preg_match('/\p{Lu}/u',$ch)){ $pos=$i; break; } } echo '<p>Poziție: <strong>'.($pos>=0?$pos:'nu există').'</strong></p>'; } ?>
        </article>

        <article class="task-card">
          <h3>3. Numărul vocalelor pe poziții impare între litere</h3>
          <form method="post">
            <input type="hidden" name="task" value="3" />
            <label>Șirul S:
              <input type="text" name="s" value="<?= isset($_POST['s']) && ($_POST['task']??'')==='3' ? htmlspecialchars($_POST['s']) : 'mama spala vase' ?>" />
            </label>
            <button class="btn" type="submit">Numără</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='3'){ $s=(string)($_POST['s']??''); $cnt=0; $li=0; for($i=0,$n=mb_strlen($s);$i<$n;$i++){ $ch=mb_substr($s,$i,1); if(is_letter($ch)){ $li++; if($li%2==1 && is_vowel($ch)) $cnt++; } } echo '<p>Număr vocale pe poziții impare (pe litere): <strong>'.$cnt.'</strong></p>'; } ?>
        </article>

        <article class="task-card">
          <h3>4. Poziția ultimei cifre (0‑based)</h3>
          <form method="post">
            <input type="hidden" name="task" value="4" />
            <label>Text:
              <input type="text" name="s" value="<?= isset($_POST['s']) && ($_POST['task']??'')==='4' ? htmlspecialchars($_POST['s']) : '2+3 fac cinci' ?>" />
            </label>
            <button class="btn" type="submit">Găsește</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='4'){ $s=(string)($_POST['s']??''); $pos=-1; for($i=0,$n=mb_strlen($s);$i<$n;$i++){ $ch=mb_substr($s,$i,1); if(preg_match('/[0-9]/',$ch)) $pos=$i; } echo '<p>Poziția ultimei cifre: <strong>'.($pos>=0?$pos:'nu există').'</strong></p>'; } ?>
        </article>

        <article class="task-card">
          <h3>5. Procentul literelor mari</h3>
          <form method="post">
            <input type="hidden" name="task" value="5" />
            <label>Șir (doar litere):
              <input type="text" name="s" value="<?= isset($_POST['s']) && ($_POST['task']??'')==='5' ? htmlspecialchars($_POST['s']) : 'euMERg' ?>" />
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='5'){ $s=(string)($_POST['s']??''); $len=0; $up=0; for($i=0,$n=mb_strlen($s);$i<$n;$i++){ $ch=mb_substr($s,$i,1); if(is_letter($ch)){ $len++; if(preg_match('/\p{Lu}/u',$ch)) $up++; } } $pct=$len>0? round($up*100/$len) : 0; echo '<p>Litere mari: '.$up.' din '.$len.' → <strong>'.$pct.'%</strong></p>'; } ?>
        </article>

        <article class="task-card">
          <h3>6. Comparați aparițiile caracterelor C1 și C2</h3>
          <form method="post">
            <input type="hidden" name="task" value="6" />
            <label>Șirul S:
              <input type="text" name="s" value="<?= isset($_POST['s']) && ($_POST['task']??'')==='6' ? htmlspecialchars($_POST['s']) : 'aurul alb' ?>" />
            </label>
            <div style="display:flex;gap:10px;">
              <label style="flex:1;">C1:
                <input type="text" name="c1" maxlength="1" value="<?= isset($_POST['c1']) && ($_POST['task']??'')==='6' ? htmlspecialchars($_POST['c1']) : 'a' ?>" />
              </label>
              <label style="flex:1;">C2:
                <input type="text" name="c2" maxlength="1" value="<?= isset($_POST['c2']) && ($_POST['task']??'')==='6' ? htmlspecialchars($_POST['c2']) : 'u' ?>" />
              </label>
            </div>
            <button class="btn" type="submit">Verifică</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='6'){ $s=(string)($_POST['s']??''); $c1=mb_substr((string)($_POST['c1']??''),0,1); $c2=mb_substr((string)($_POST['c2']??''),0,1); $n1=$c1!==''?preg_match_all('/'.preg_quote($c1,'/').'/u',$s,$m):0; $n2=$c2!==''?preg_match_all('/'.preg_quote($c2,'/').'/u',$s,$m):0; echo '<p>Apariții C1: '.$n1.'; C2: '.$n2.' → <strong>'.($n1===$n2?'Da':'Nu').'</strong></p>'; } ?>
        </article>

        <article class="task-card">
          <h3>7. Criptare și decriptare cu K (1–20)</h3>
          <form method="post">
            <input type="hidden" name="task" value="7" />
            <label>Text:
              <input type="text" name="s" value="<?= isset($_POST['s']) && ($_POST['task']??'')==='7' ? htmlspecialchars($_POST['s']) : 'acasa' ?>" />
            </label>
            <label>K:
              <input type="number" name="k" min="1" max="20" value="<?= isset($_POST['k']) && ($_POST['task']??'')==='7' ? (int)$_POST['k'] : 1 ?>" style="width:120px;" />
            </label>
            <button class="btn" type="submit">Procesează</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='7'){ $s=(string)($_POST['s']??''); $k=max(1,min(20,(int)($_POST['k']??1))); $enc=caesar_shift($s,$k); $dec=caesar_shift($enc,26-$k); echo '<p>Criptat: <strong>'.htmlspecialchars($enc).'</strong></p>'; echo '<p>Decriptat: <strong>'.htmlspecialchars($dec).'</strong></p>'; } ?>
        </article>

        <article class="task-card">
          <h3>8. Piramida literelor</h3>
          <form method="post" style="display:flex;gap:10px;align-items:center;">
            <input type="hidden" name="task" value="8" />
            <label>N (1–26):
              <input type="number" name="n" min="1" max="26" value="<?= isset($_POST['n']) && ($_POST['task']??'')==='8' ? (int)$_POST['n'] : 4 ?>" style="width:120px;" />
            </label>
            <button class="btn" type="submit">Generează</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='8'){ $n=max(1,min(26,(int)($_POST['n']??1))); echo '<pre style="text-align:left;">'; for($i=1;$i<=$n;$i++){ $ch=chr(64+$i); echo implode(' ',array_fill(0,$i,$ch))."\n"; } echo '</pre>'; } ?>
        </article>

        <article class="task-card">
          <h3>9. Adrese e‑mail distincte, ordonate (2 pe rând)</h3>
          <form method="post">
            <input type="hidden" name="task" value="9" />
            <label>Adrese (separate prin virgulă):
              <textarea name="emails" rows="3"><?= isset($_POST['emails']) && ($_POST['task']??'')==='9' ? htmlspecialchars($_POST['emails']) : 'ana@x.com, bob@y.com, ana@x.com, ion@x.com, zed@z.com' ?></textarea>
            </label>
            <button class="btn" type="submit">Procesează</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='9'){ $raw=(string)($_POST['emails']??''); $list=array_filter(array_map(fn($e)=>strtolower(trim($e)), explode(',',$raw))); $uniq=array_values(array_unique($list)); sort($uniq,SORT_STRING); if(!$uniq) echo '<p>Nimic de afișat.</p>'; echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">'; foreach($uniq as $email) echo '<div>'.htmlspecialchars($email).'</div>'; echo '</div>'; } ?>
        </article>

        <article class="task-card">
          <h3>10. Serverul cu cele mai multe conturi și username‑ul cel mai frecvent</h3>
          <form method="post">
            <input type="hidden" name="task" value="10" />
            <label>Adrese e‑mail (virgulă):
              <textarea name="emails" rows="3"><?= isset($_POST['emails']) && ($_POST['task']??'')==='10' ? htmlspecialchars($_POST['emails']) : 'ana@x.com, bob@y.com, ana@x.com, ion@x.com, zed@z.com' ?></textarea>
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='10'){ $raw=(string)($_POST['emails']??''); $list=array_filter(array_map(fn($e)=>strtolower(trim($e)), explode(',',$raw))); $servers=[];$users=[]; foreach($list as $e){ if(!str_contains($e,'@')) continue; [$u,$srv]=explode('@',$e,2); if($u==='') continue; $servers[$srv]=($servers[$srv]??0)+1; $users[$u]=($users[$u]??0)+1; } arsort($servers); arsort($users); $topSrv=$servers?array_key_first($servers):'—'; $topUser=$users?array_key_first($users):'—'; echo '<p>Server: <strong>'.htmlspecialchars($topSrv).'</strong></p>'; echo '<p>Username: <strong>'.htmlspecialchars($topUser).'</strong></p>'; } ?>
        </article>

        <article class="task-card">
          <h3>11. Extrage cuvintele și ordonează (lungime crescător, apoi alfabetic)</h3>
          <form method="post">
            <input type="hidden" name="task" value="11" />
            <label>Text:
              <textarea name="text" rows="3"><?= isset($_POST['text']) && ($_POST['task']??'')==='11' ? htmlspecialchars($_POST['text']) : 'Ana, are. mere; sau? nu!' ?></textarea>
            </label>
            <button class="btn" type="submit">Ordonează</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='11'){ $t=(string)($_POST['text']??''); $words=preg_split('/[\s,.;!?]+/u',$t,-1,PREG_SPLIT_NO_EMPTY); usort($words,function($a,$b){ $la=mb_strlen($a); $lb=mb_strlen($b); if($la!==$lb) return $la<=>$lb; return strcasecmp($a,$b); }); echo '<p>Rezultat:</p><pre>'.htmlspecialchars(implode(' ',$words)).'</pre>'; } ?>
        </article>

        <article class="task-card">
          <h3>12. Salarii: minim, maxim și listele aferente</h3>
          <form method="post">
            <input type="hidden" name="task" value="12" />
            <label>Introduceți fiecare angajat pe linie (ex: Nume: 5000):
              <textarea name="sal" rows="5"><?= isset($_POST['sal']) && ($_POST['task']??'')==='12' ? htmlspecialchars($_POST['sal']) : "Ana: 5000\nMihai: 4200\nIoana: 5000\nRadu: 3900" ?></textarea>
            </label>
            <button class="btn" type="submit">Calculează</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='12'){ $raw=(string)($_POST['sal']??''); $lines=preg_split('/\r?\n/',$raw); $arr=[]; foreach($lines as $line){ if(preg_match('/^\s*(.+?)\s*[:=\-]?\s*(\d+(?:\.\d+)?)\s*$/u',$line,$m)){ $arr[trim($m[1])] = (float)$m[2]; } } if(!$arr){ echo '<p>Date invalide.</p>'; } else { $vals=array_values($arr); $min=min($vals); $max=max($vals); $minNames=array_keys(array_filter($arr,fn($v)=>$v==$min)); $maxNames=array_keys(array_filter($arr,fn($v)=>$v==$max)); echo '<p>Minim: <strong>'.$min.'</strong> — '.htmlspecialchars(implode(', ',$minNames)).'</p>'; echo '<p>Maxim: <strong>'.$max.'</strong> — '.htmlspecialchars(implode(', ',$maxNames)).'</p>'; } } ?>
        </article>

        <article class="task-card">
          <h3>13. Împărțirea șirului în blocuri de X caractere</h3>
          <form method="post">
            <input type="hidden" name="task" value="13" />
            <label>S:
              <input type="text" name="s" value="<?= isset($_POST['s']) && ($_POST['task']??'')==='13' ? htmlspecialchars($_POST['s']) : 'abcdefghij' ?>" />
            </label>
            <label>X:
              <input type="number" name="x" min="1" value="<?= isset($_POST['x']) && ($_POST['task']??'')==='13' ? (int)$_POST['x'] : 3 ?>" style="width:120px;" />
            </label>
            <button class="btn" type="submit">Construiește</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='13'){ $s=(string)($_POST['s']??''); $x=max(1,(int)($_POST['x']??1)); $n=mb_strlen($s); if($x>=$n){ $T=[$s]; } else { $T=[]; for($i=0;$i<$n;$i+=$x) $T[]=mb_substr($s,$i,$x); } echo '<pre>['.htmlspecialchars(implode(', ',array_map(fn($e)=>'"'.$e.'"',$T))).']</pre>'; } ?>
        </article>

        <article class="task-card">
          <h3>14. Rezolvați ecuațiile liniare din șir</h3>
          <form method="post">
            <input type="hidden" name="task" value="14" />
            <label>Ecuații (separate prin virgulă):
              <textarea name="eqs" rows="3"><?= isset($_POST['eqs']) && ($_POST['task']??'')==='14' ? htmlspecialchars($_POST['eqs']) : '2x+4=0, x-2=0, -3x-6=0, 2=0, 0x+0=0' ?></textarea>
            </label>
            <button class="btn" type="submit">Rezolvă</button>
          </form>
          <?php if (($_POST['task'] ?? '')==='14'){ $eqs=(string)($_POST['eqs']??''); $items=array_filter(array_map('trim', explode(',', $eqs))); echo '<div style="margin-top:10px">'; foreach($items as $idx=>$eq){ [$x,$ab,$err]=solve_linear_eq($eq); if($err==='inf'){ $msg='Ecuația '.($idx+1).' are soluția: x aparține lui R'; } elseif($err){ $msg='Ecuația '.($idx+1).' are soluția: nu are soluție'; } else { $msg='Ecuația '.($idx+1).' are soluția: '.rtrim(rtrim(number_format($x,10,'.',''),'0'),'.'); } echo '<p>'.htmlspecialchars($msg).'</p>'; } echo '</div>'; } ?>
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
