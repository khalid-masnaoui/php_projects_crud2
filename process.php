<?php 
require_once("connect_db.php");
session_start();

//inserting the data

if(isset($_POST["submit"])){

    $errors=["text"=>"","date"=>""];
    $catg=$_POST["catg"];
    $text=$_POST["text"];
    $date=$_POST["goal_date"];
    $comlpeted=0;
    
    if(isset($_POST["completed"])){
        $comlpeted=1; //only checkboxes ??
    }

    //the only cheking we gonna do is cheking for emoty goals and dates;

    if(empty($text)){
        $errors["text"]=" * field is required";
    }
    if(empty($date)){
        $errors["date"]=" * field is required";
    }

    if(array_filter($errors)){
       $_SESSION["ERR"]=$errors;
       header("location:index.php");
    }else{
        try{
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
            $stmt=$db->prepare("INSERT INTO goals (goal_category,goal_text,goal_date,goal_completed)  VALUES (:catg,:text,:date,:compl)");
            $stmt->execute([":catg"=>$catg,":text"=>$text,":date"=>$date,":compl"=>$comlpeted]);
            header("location:index.php");
            
        }catch(PDOException $e){
            echo "error : ".$e->getMessage();
            exit;
        }
        
    }
}


//updating the completed status

if(isset($_GET["complete"])){
    $id=$_GET["complete"];
    $compl=1;
    try{
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stmt=$db->prepare("UPDATE goals SET goal_completed = :compl WHERE goal_id = :id");
        $stmt->execute([":compl"=>$compl,":id"=>$id]);
        header("location:index.php");
        
    }catch(PDOException $e){
        echo "error : ".$e->getMessage();
        exit;
    }

    
}

//the update option

if(isset($_GET["update"])){
    $id=$_GET["update"];
    $_SESSION["s/u"]="u";
    $_SESSION["id"]=$id;
    try{
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stmt=$db->prepare("SELECT * From goals  WHERE goal_id = :id");
        $stmt->execute([":id"=>$id]);
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row){$inputs=["catg"=>$row["goal_category"],"text"=>$row["goal_text"],"date"=>$row["goal_date"],"compl"=>$row["goal_completed"]]; }
        $_SESSION["inputs"]=$inputs;
        header("location:index.php");
        
    }catch(PDOException $e){
        echo "error : ".$e->getMessage();
        exit;
    }

    
}

if(isset($_POST["update"])){

    $errors=["text"=>"","date"=>""];
    $catg=$_POST["catg"];
    $text=$_POST["text"];
    $date=$_POST["goal_date"];
    $comlpeted=0;
    
    if(isset($_POST["completed"])){
        $comlpeted=1;
    }

    //the only cheking we gonna do is cheking for emoty goals and dates;

    if(empty($text) || $text==" "){
        $errors["text"]=" * field is required";
    }
    if(empty($date)){
        $errors["date"]=" * field is required";
    }

    if(array_filter($errors)){
       $_SESSION["ERR"]=$errors;
       header("location:index.php");
    }else{
        try{
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
            $stmt=$db->prepare("UPDATE goals SET goal_category=:catg ,goal_text=:text,goal_date=:date,goal_completed=:compl WHERE goal_id=:id");
            $id=$_SESSION["id"];
            $stmt->execute([":catg"=>$catg,":text"=>$text,":date"=>$date,":compl"=>$comlpeted,":id"=>$id]);
           unset($_SESSION["s/u"],$_SESSION["id"]);
            header("location:index.php");
            
        }catch(PDOException $e){
            echo "error : ".$e->getMessage();
            exit;
        }
        
    }
}


//when using get link use ,php?--=--&not -php/?---

//deleting
if(isset($_GET["delete"])){
    $id=$_GET["delete"];
    try{
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stmt=$db->prepare("DELETE FROM goals WHERE goal_id = :id");
        $stmt->execute([":id"=>$id]);
        header("location:index.php");
        
    }catch(PDOException $e){
        echo "error : ".$e->getMessage();
        exit;
    }

}







?>