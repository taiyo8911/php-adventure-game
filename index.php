<?php
    // POSTデータの削除
    // $_POST = array();


    // --------- ゲームロジック --------- //

    // 変数
    $player_name = "ゆうしゃ";
    $player_lv = 1;
    $player_hp = 10;
    $monster_hp = 7;
    $msg = "モンスターがあらわれた!<br>";
    $player_damage; // 敵に与えるダメージ
    $monster_damage; // 敵からのダメージ
    $isFinish = False; // 勝利フラグ
    $isRecovery = False; // 回復フラグ
    $isDeath = False; // 死亡フラグ
    $isClear = False; // クリアフラグ


    // 定数
    $recovery_point = 5; // 回復量


    // フォームボタンが押された時にデータを更新

    // こうげきボタンか、回復ボタンか、次のモンスターと戦うボタンが押された場合
    if (isset($_POST["player_lv"])) {
        $player_lv = $_POST["player_lv"];
    }
    if (isset($_POST["player_hp"])) {
        $player_hp = $_POST["player_hp"];
    }
    if (isset($_POST["monster_hp"])) {
        $monster_hp = $_POST["monster_hp"];
    }

    // こうげきボタンが押された場合
    if (isset($_POST["battle"])) {
        // モンスターに攻撃
        $player_damage = mt_rand(1, 3); // ダメージ量はランダム
        $msg = "モンスターに{$player_damage}ダメージあたえた!<br>";
        $monster_hp -= $player_damage;

        // モンスターのHPが1以上残っていた場合
        // 攻撃を受ける
        if ($monster_hp >= 1) {
            $monster_damage = mt_rand(1,3); // ダメージ量はランダム
            $msg .= "あなたは{$monster_damage}ダメージうけた<br>";
            $player_hp -= $monster_damage;
        }

        // プレイヤーのHPが0以下になった場合
        if ($player_hp <= 0) {
            $isDeath = True; // 死亡フラグをたてる
            $player_hp = 0;
            $_POST = array(); // データを初期化する
            $msg .= "あなたはしにました<br>";
        }

        // モンスターのHPが0以下になった場合
        else if ($monster_hp <= 0) {
            $isFinish = True; // 戦闘終了フラグをたてる
            $player_lv ++;
            $msg .= "モンスターを倒した!<br>";
            // レベルが一定を超えたらクリア
            if ($player_lv >= 5) {
                $msg .= "世界に 平和が もどったのだ!";
                $isClear = True;
            }
        }
    }

    // 回復ボタンが押された場合
    if (isset($_POST["recovery"])) {
        $isRecovery = True;
        $player_hp += $recovery_point;
        $msg = "HPが{$recovery_point}かいふくした!";
    }

    // 次のモンスターと戦うボタンが押された場合
    if (isset($_POST["next"])) {
        $isFinish = False;
        $monster_hp = 7;
        $msg = "モンスターがあらわれた!<br>";
    }




    // --------- HTMLソースの出力 --------- //

    // スマホ対応
    echo '<meta name="viewport" content="width=device-width,initial-scale=1.0" />' . "\n";


    // ステータス表示
    echo $player_name . '<br>' . "\n";
    echo 'LV:'. $player_lv . '<br>' . "\n";
    echo 'HP:'. $player_hp . '<br>' . "\n";
    echo '<br>' . "\n";
    echo $msg . "\n";


    // フォームボタン
    echo '<form action="index.php" method="post">'. "\n";
    echo '<input type="hidden" name="player_lv" value="'.$player_lv.'">'. "\n";
    echo '<input type="hidden" name="player_hp" value="'.$player_hp.'">'. "\n";
    echo '<input type="hidden" name="monster_hp" value="'.$monster_hp.'">'. "\n";

    // モンスターを倒していない、プレイヤーが死んでいない、回復していない、クリアしていない場合
    if ($isFinish == False and $isDeath == False and $isRecovery == False and $isClear == False) {
        echo '<img src="img/grey_purple.png" width="50">';
        echo '<br>' . "\n";
        echo '<br>' . "\n";

        // こうげきボタンを表示
        echo '<img src="img/sword_blue.png" width="50">' . "\n";
        echo '<input type="submit" name="battle" value="こうげき">' . "\n";
    }

    // モンスターを倒した場合
    if ($isFinish == True and $isClear == False) {
        // 次のモンスターと戦うボタンを表示
        echo '<img src="img/map_beige.png" width="50">' . "\n";
        echo '<input type="submit" name="next" value="次のモンスターと戦う">'. "\n";
        // 回復ボタンを表示
        echo '<img src="img/portion_01_lightblue_01.png" width="50">' . "\n";
        echo '<input type="submit" name="recovery" value="回復">'. "\n";
    }

    // プレイヤーが死んだ場合
    if ($isDeath == True) {
        // トップページへのリンクを表示
        echo '<a href="index.php">もう一度冒険する</a>'. "\n";
    }

    // 回復した場合
    if ($isRecovery == True){
        // 次のモンスターと戦うボタンを表示
        echo '<br>' . "\n";
        echo '<img src="img/map_beige.png" width="50">' . "\n";
        echo '<input type="submit" name="next" value="次のモンスターと戦う">'. "\n";
    }

    // クリアした場合
    if ($isClear == True) {
        echo '<br>' . "\n";
        echo '<img src="img/crystal_sphere_purple.png" width="50">' . "\n";
        echo '<br>' . "\n";
        echo '<br>' . "\n";
        // トップページへのリンクを表示
        echo '<a href="index.php">もう一度冒険する</a>'. "\n";
    }

    echo '</form>'. "\n";

?>