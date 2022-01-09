<?php
  $inputNum = filter_input(INPUT_POST, 'number');
  $recipe = nl2br(filter_input(INPUT_POST, 'recipe'));
  $null = "";
  if(isset($inputNum) && isset($recipe)){
      $recipe = str_replace(array("\r\n", "\r", "\n"), "\n", $recipe);
      $recipeArr = explode("\n", $recipe);
      //var_dump($recipeArr);
      $multipleNum = getMultipleNum($recipeArr, $inputNum);
      $num =  calcRecipe($recipeArr);
      $replaceNum = replaceNum($recipeArr, $num, $multipleNum);
  }

  //材料○○人分から○○（数字）を取り出し、掛ける数を算出
  function getMultipleNum($recipeArr, $inputNum){
      $string = $recipeArr[0];
      //～があった場合削除する
      if(preg_match('/～/',$string)){
          $string = strstr($string, '～', true);
      }
      $num = preg_replace('/[^0-9]/', '', $string);
      if($num == ''){
          echo "数字を入力してください";
      }else{
      $multipleNum = $inputNum / $num;
      return $multipleNum;
    }
  }


  /*recipeArrの数字のみ取り出し、配列に入れる
  分数の場合は少数に直してから計算する*/
  function calcRecipe($recipeArr){
      $nums = [];
      //preg_matchで抽出した値を保存しておく
      $keep = [];
      for ($i=0; $i < count($recipeArr); $i++) {

          $string = $recipeArr[$i];
          if(preg_match('/～/',$string)){
              $string = strstr($string, '～', true);
          }
          if(preg_match('/[0-9]\/[0-9]/',$string, $keep)){
            //$keepから値を区切り文字ごとに取り出し計算。その後$numに代入する
              $fraction = $keep[0];
              list($numer, $denom) = explode('/', $fraction);
              $num = $numer / $denom;
          }elseif(preg_match('/[0-9]\.[0-9]/', $string, $keep)){
              $num = $keep[0];
          }else{
              $num = preg_replace('/[^0-9]/', '', $string);
          }
            array_push($nums,(float)$num); //(int)$num

        }
      return $nums;
  }

  //var_dump($num);

  /*function mathRecipe($n, $multipleNum){
    return $n * $multipleNum;
  }
  $b = array_map('mathRecipe', $num);
  print_r($b);*/

  //分数を生成
  //計算後の数字を元の配列の数字と置き換える
  function replaceNum($recipeArr, $num, $multipleNum){
      $nums = [];
      for ($i=0; $i < count($recipeArr); $i++) {

          $string = $recipeArr[$i];
          if(preg_match('/～/',$string)){
              $string = strstr($string, '～', true);
          }
          $numA = $num[$i] * $multipleNum;
          //var_dump($numA);
          if(preg_match('/[0-9]\/[0-9]/',$string)){
            if($numA >= 1 && preg_match('/[0-9]\.[0-9]/', $numA)){
              $cut = explode('.', round($numA, 2));
              $connect = $cut[0];
              $numA = $cut[1] * 0.1;
            }
            if($numA < 1 && $numA >= 0.85){
              $numA = "4/5";
            }elseif($numA < 0.85 && $numA >= 0.7){
              $numA = "3/4";
            }elseif($numA < 0.7 && $numA >= 0.65){
              $numA = "2/3";
            }elseif($numA < 0.65 && $numA >= 0.55){
              $numA = "3/5";
            }elseif($numA < 0.55 && $numA >= 0.45){
              $numA = "1/2";
            }elseif($numA < 0.45 && $numA >= 0.275){
              $numA = "1/3";
            }elseif($numA < 0.275 && $numA >= 0.235){
              $numA = "1/4";
            }elseif($numA < 0.235 && $numA >= 0.175){
              $numA = "1/5";
            }elseif($numA < 0.175 && $numA >= 0.15){
              $numA = "1/6";
            }elseif($numA < 0.15 && $numA >= 0.135){
              $numA = "1/7";
            }elseif($numA < 0.135 && $numA >= 0.11){
              $numA = "1/8";
            }elseif($numA < 0.11 && $numA >= 0.05){
              $numA ="1/10";
            }

            if(isset($connect)){
              $numA = $connect . "と" . $numA;
            }

              $newNum = preg_replace('/[0-9]\/[0-9]/',$numA, $string);
          }elseif(preg_match('/[0-9]\.[0-9]/', $string)){
            //１より小さくなる場合か、小数が入る場合は条件分岐作る
              $newNum = preg_replace('/[0-9]\.[0-9]/',$numA, $string);
          }else{
          $newNum = preg_replace('/[0-9]+/u',$numA, $string);
        }
          array_push($nums, $newNum);
      }
      return $nums;
  }

  //print_r($replaceNum);
 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Re-Cipe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>
  </head>
  <body>
    <header class="wrapper">
      <div class="page-header">
        <h3 class="logo">Re-cipe</h3>

      </div>
    </header>
    <main>
      <div class="container wrapper">
        <div class="main-bg">
          <!--<img src="images/AdobeStock_123485919.jpeg" alt="料理">-->
          <h1 class="page-title">作りたい分量に あっという間に</h1>
          <a href="./howto.php" class="howto-btn"><span>Re-cipe</span>の使い方</a>
        </div>
        <div class="form-container">
          <div class="form-part">
            <form class="form-text" action="" method="post">
              <div class="change-num">
                <span>材料を</span>
                <select class="" name="number">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
                <span>人分に変える</span>
              </div>
              <br>
              <div class="form-textarea">
                <textarea id="form" name="recipe" rows="15" cols="40"></textarea>
                <ul class="caution">
                  <li>※先頭が『材料(○○人分)』となるようにしてください。</li>
                  <li>※括弧（）で括られた数字は計算の対象にはなりません。</li>
                  <li>※1～2個のように書かれている場合、左側の数字のみ計算されます。</li>
                </ul>
                <button type="button" value="クリア" onclick="clearTextarea()" class="clear-btn">クリア</button>
                <button type="submit" name="button" class="button">計算する</button>
              </div>
            </form>
          </div>
          <div class="answer-part" id="flash">
            <textarea rows="15" cols="40" id="copy" readonly>
              <?php if (isset($replaceNum)):?>
                  <?php foreach ($replaceNum as $key => $value):?>
                      <?php $val_replace = preg_replace("/<br \/>/", "", $value);?>
                      <?=htmlspecialchars($val_replace, ENT_QUOTES, 'UTF-8')?>
                      <?=htmlspecialchars($null, ENT_QUOTES, 'UTF-8')?>
                  <?php endforeach;?>
              <?php endif; ?>
            </textarea>
            <br>
              <div class="flash-msg" v-if="show">
                {{message}}
              </div>
            <button type="button" name="button" onclick ="copy()" class="copy-btn" @click="showFlash">
              Copy
            </button>
          </div>
        </div>

      </div>
    </main>
    <footer>
      <p>©2021 MiguelAoyama</p>
    </footer>
    <script src="js/main.js"></script>
    <script type="text/javascript">
    new Vue({
      el: '#flash',
      data:{
        show:false,
        message:"コピーしました"
      },
      methods:{
        showFlash(){
        this.show = true;
        setTimeout(() => {
          this.show = false },2000)
        }
      }
    })
    </script>
  </body>
</html>
