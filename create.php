<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$zadanie = $project = $data = $comment = $price = "";
$zadanie_err = $project_err = $data_err = $comment_err = $price_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){




    // Validate name
    $input_zadanie = trim($_POST["zadanie"]);
    if(empty($input_zadanie)){
        $zadanie_err = "Введи задание.";
    } elseif(!filter_var(trim($_POST["zadanie"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $zadanie_err = 'Введи корректное задание.';
    } else{
        $zadanie = $input_zadanie;
    }

    // Validate project
    $input_project = trim($_POST["project"]);
    if(empty($input_project)){
        $project_err = 'Please enter a project.';
    } else{
        $project = $input_project;
    }



    // Validate data
    $input_data = trim($_POST["data"]);
    if(empty($input_data)){
        $data_err = 'Please enter a data.';
    } else{
        $data = $input_data;
    }




    // Validate comment
    $input_comment = trim($_POST["comment"]);
    if(empty($input_comment)){
        $comment_err = 'Please enter a comment.';
    } else{
        $comment = $input_comment;
    }

    // Validate price
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the price amount.";
    } elseif(!ctype_digit($input_price)){
        $price_err = 'Please enter a positive price value.';
    } else{
        $price = $input_price;
    }


    // Check input errors before inserting in database
    if(empty($zadanie_err) && empty($project_err) && empty($data_err) && empty($comment_err) && empty($price_err)){
        // Prepare an insert statement


        $sql = "INSERT INTO zadaniya ( zadanie, project, data, comment, price ) VALUES (?, ?, ?, ?, ?)";


        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_zadanie, $param_project, $param_data,  $param_comment,  $param_price);


                        // Set parameters
                        $param_zadanie = $zadanie;
                        $param_project = $project;
                        $param_data = $data;
                        $param_comment = $comment;
                        $param_price = $price;



            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Создать запись</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Создать запись</h2>
                    </div>
                    <p>Заполните все поля.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


                      <div class="form-group <?php echo (!empty($zadanie_err)) ? 'has-error' : ''; ?>">
                          <label>Задание</label>
                          <input type="text" name="zadanie" class="form-control" value="<?php echo $zadanie; ?>">
                          <span class="help-block"><?php echo $zadanie_err;?></span>
                      </div>
                      <div class="form-group <?php echo (!empty($project_err)) ? 'has-error' : ''; ?>">
                          <label>Проект</label>
                          <textarea name="project" class="form-control"><?php echo $project; ?></textarea>
                          <span class="help-block"><?php echo $project_err;?></span>
                      </div>
                      <div class="form-group <?php echo (!empty($data_err)) ? 'has-error' : ''; ?>">
                          <label>Дата</label>
                          <input type="text" name="data" class="form-control" value="<?php echo $data; ?>">
                          <span class="help-block"><?php echo $data_err;?></span>
                      </div>
                      <div class="form-group <?php echo (!empty($comment_err)) ? 'has-error' : ''; ?>">
                          <label>Комментарий</label>
                          <input type="text" name="comment" class="form-control" value="<?php echo $comment; ?>">
                          <span class="help-block"><?php echo $comment_err;?></span>
                      </div>
                      <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                          <label>Цена</label>
                          <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
                          <span class="help-block"><?php echo $price_err;?></span>
                      </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Отменить</a>


                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
