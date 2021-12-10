<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Re-Cipe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <header class="page-header wrapper">
      <h3 class="logo">Re-cipe</h3>
      <p class="menu-btn">三</p>
    </header>
    <?php
      $inputNum = $_POST['number'];

      $recipe = nl2br($_POST['recipe']);
      $recipe = str_replace(array("\r\n", "\r", "\n"), "\n", $recipe);
      $recipeArr = explode("\n", $recipe);
      //var_dump($recipeArr);

      $multipleNum = getMultipleNum($recipeArr, $inputNum);
      $num =  calcRecipe($recipeArr);
      $replaceNum = replaceNum($recipeArr, $num, $multipleNum);
      echo "<textarea>";
      foreach ($replaceNum as $key => $value) {
        // code...
        echo htmlspecialchars($value) ;
        echo "\n";
      }
      echo "</textarea>";

      //材料○○人分から○○（数字）を取り出し、掛ける数を算出
      function getMultipleNum($recipeArr, $inputNum){
          $string = $recipeArr[0];
          $num = preg_replace('/[^0-9]/', '', $string);
          $multipleNum = $inputNum / $num;
          return $multipleNum;
      }


      /*recipeArrの数字のみ取り出し、配列に入れる
      分数の場合は少数に直してから計算する*/
      function calcRecipe($recipeArr){
          $nums = [];
          //preg_matchで抽出した値を保存しておく
          $keep = [];
          for ($i=0; $i < count($recipeArr); $i++) {

              $string = $recipeArr[$i];
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
  </body>
</html>
