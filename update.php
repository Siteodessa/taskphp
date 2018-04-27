<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$zadanie = $project = $data = $comment = $price = "";
$zadanie_err = $project_err = $data_err = $comment_err = $price_err = "";





// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate zadanie
    $input_zadanie = trim($_POST["zadanie"]);
    if(empty($input_zadanie)){
        $zadanie_err = "Please enter a zadanie.";
    } elseif(!filter_var(trim($_POST["zadanie"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $zadanie_err = 'Please enter a valid zadanie.';
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
        $data_err = "Please enter the data .";
    } elseif(!ctype_digit($input_data)){
        $data_err = 'Please enter a positive data.';
    } else{
        $data = $input_data;
    }

    // Validate comment
    $input_comment = trim($_POST["comment"]);
    if(empty($input_comment)){
        $comment_err = "Please enter the comment.";
    } elseif(!ctype_digit($input_comment)){
        $comment_err = 'Please enter a positive comment.';
    } else{
        $comment = $input_comment;
    }

    // Validate price
    $price_comment = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the price.";
    } elseif(!ctype_digit($input_price)){
        $price_err = 'Please enter a positive price.';
    } else{
        $price = $input_price;
    }




    // Check input errors before inserting in database
    if(empty($zadanie_err) && empty($project_err) && empty($data_err) && empty($comment_err) && empty($price_err)){
        // Prepare an insert statement
        $sql = "UPDATE zadaniya SET zadanie=?, project=?, data=?, comment=?, price=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_zadanie, $param_project, $param_data,  $param_comment,  $param_price, $param_id);

            // Set parameters
            $param_zadanie = $zadanie;
            $param_project = $project;
            $param_data = $data;
            $param_comment = $comment;
            $param_price = $price;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    $trim_id  = trim($_GET["id"]);
    if(isset($_GET["id"]) && !empty($trim_id)){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM zadaniya WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $zadanie = $row["zadanie"];
                    $project = $row["project"];
                    $data = $row["data"];
                    $comment = $row["comment"];
                    $price = $row["price"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Обновить задание</title>
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
                        <h2>Обновить задание</h2>
                    </div>
                    <p>Убедитесь что все поля заполнены.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                            <input type="text" name="salary" class="form-control" value="<?php echo $data; ?>">
                            <span class="help-block"><?php echo $salary_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($comment_err)) ? 'has-error' : ''; ?>">
                            <label>Комментарий</label>
                            <input type="text" name="comment" class="form-control" value="<?php echo $comment; ?>">
                            <span class="help-block"><?php echo $comment_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                            <label>Цена</label>
                            <input type="text" name="salary" class="form-control" value="<?php echo $price; ?>">
                            <span class="help-block"><?php echo $price_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Отменить</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
