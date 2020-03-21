<?php
require_once("connect_db.php");

session_start();
if(!isset($_SESSION["ERR"])and !isset($_SESSION["inputs"])){
    unset($_SESSION["s/u"]);}

//for error cheking (i didnt bring the values back to the inputs !)
if(isset($_SESSION["ERR"])){
    $text_ERR=$_SESSION["ERR"]["text"];
    $date_ERR=$_SESSION["ERR"]["date"];
    unset($_SESSION["ERR"]);
}
//for the update
if(isset($_SESSION["inputs"])){
    $catg_input=$_SESSION["inputs"]["catg"];
    $text_input=$_SESSION["inputs"]["text"];
    $date_input=$_SESSION["inputs"]["date"];
    $_SESSION["s/u"]="u";
    unset($_SESSION["inputs"]);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="container">
    <h1>New Goal</h1>
    <form action="process.php" method="post">
    <label for="catg">Category</label>
    <select name="catg" id="catg">
        <option value="0" <?php 
        if(isset($catg_input) and $catg_input==0){
echo "selected" ;
        }else{echo "great";} ?>>Personal</option>
        <option value="1" <?php 
        if(isset($catg_input) and $catg_input==1){
echo "selected";
        }else{echo "great";} ?>>Professional</option>
        <option value="2" <?php 
        if(isset($catg_input) and $catg_input==2){
echo "selected";
        }else{echo "great";} ?>>Other</option>
    </select>
    <label for="text">Goal</label>
    <textarea name="text" id="text" placeholder="set your goal" ><?php 
        if(isset($text_input)){
echo $text_input;
        } ?></textarea>
        <?php
if(isset($text_ERR)){
    echo "<span class='error'>$text_ERR</span>";
}
        ?>
   
    <label for="goal_date">Date</label>
    <input type="date" name="goal_date" id="goal_date" value="<?php 
        if(isset($date_input) ){
echo $date_input;
        } ?>">        <?php
if(isset($date_ERR)){
    echo "<span class='error'>$date_ERR</span>";
}
        ?>
    <label for="complete">Goal completed</label>
    <input type="checkbox" name="completed" id="completed" value="1" >  <br>

    <?php
    if(isset($_SESSION["s/u"]) and $_SESSION["s/u"]=="u"){
        echo "<input class='button' type='submit' name='update' value='update goal'>";
    }else {    echo "<input class='button' type='submit' name='submit' value='submit Goal'>";
    }


    ?>
    </form>
    
    <h1>Incomplete goals</h1>
    
    <?php
    try{
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stmt=$db->prepare("SELECT * FROM goals WHERE goal_completed=:compl ORDER BY goal_date DESC");
        $stmt->execute([":compl"=>0]);
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
            foreach($result as $row){
                $id=$row["goal_id"];
                $catg=$row["goal_category"];
                $catg2="Personal";
                if($catg==1){
                    $catg2="Professional";
                }elseif($catg==2){
                    $catg2="Other";

                }
                $text=$row["goal_text"];
                $date=$row["goal_date"];


                echo "<div class='wrapper container'>
                <h4>$catg2</h4>
                <P>$text</p>
                <br>
                <h5>goal Date : $date</h5>

                <a class='button floating' href='process.php?complete=$id'>Complete</a>
                <a class='button floating2' href='process.php?update=$id'>Update</a>


                </div>";
            }
        }else{
            echo "<h4>No records for the Moment</h4>";
        }
    }catch(PDOException $e){
        echo "error : ".$e->getMessage();
        exit;
    }
    
    ?>

<h1>completed goals</h1>
    
    <?php
    try{
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stmt=$db->prepare("SELECT * FROM goals WHERE goal_completed=:compl ORDER BY goal_date DESC");
        $stmt->execute([":compl"=>1]);
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
            foreach($result as $row){
                $id=$row["goal_id"];
                $catg=$row["goal_category"];
                $catg2="Personal";
                if($catg=1){
                    $catg2="Professional";
                }elseif($catg=2){
                    $catg2="Other";

                }
                $text=$row["goal_text"];
                $date=$row["goal_date"];


                echo "<div class='wrapper container'>
                <h4>$catg2</h4>
                <P>$text</p>
                <br>
                <h5>goal Date : $date</h5>

                <a class='button floating' href='process.php?delete=$id'>Delete</a>

                </div>";
            }
        }else{
            echo "<h4>No records for the Moment</h4>";
        }
    }catch(PDOException $e){
        echo "error : ".$e->getMessage();
        exit;
    }
    
    ?>
    </div>
</body>
</html>