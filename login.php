<?php

//Guard
require_once '_guards.php';
Guard::guestOnly();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DRHE</title>
    <link rel="shortcut icon" href="photos/logo.png">
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Add Ionicons CSS link -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <style>
        body {
            background-color: rgb(34, 46, 60);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #login {
            background-color: rgb(247,248,245);
            padding: 80px;
            width: 500px;
            box-shadow: 0 0 10px rgb(34, 46, 60);
        }

        .custom-button {
            background-color: rgb(34, 46, 60);
            color: #fff;
            border: none;
        }

        .custom-button:hover {
            background-color: rgb(34, 46, 60);
            color: white;
        }

        .input-group-text:focus-within {
            border-color: rgb(34, 46, 60);
            box-shadow: 0 0 0 0.9rem rgb(34, 46, 60);
        }

        .custom-input:focus {
            border-color: rgb(34, 46, 60);
            box-shadow: 0 0 0 0.1rem rgb(34, 46, 60);
        }
    </style>
</head>

<body>
    <div id="login">
        <form method="POST" action="api/login_controller.php">
            <div class="text-center">
                <img class="image-fluid" src="photos/logo.png" style="height: 200px; width: 240px;" alt="">
            </div>
            <?php displayFlashMessage('login') ?>
            <br><br>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <!-- Use Bootstrap Icons for the user icon -->
                        <i class="ion-person"></i>
                    </span>
                </div>
                <input type="text" class="form-control custom-input" name="username" autocomplete="off"
                    placeholder="Username" required="true" />
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <!-- Use Bootstrap Icons for the lock icon -->
                        <i class="ion-locked"></i>
                    </span>
                </div>
                <input type="password" class="form-control custom-input" name="password" autocomplete="off"
                    placeholder="Password" required="true" />
            </div>


            <div class="text-center">

                <button class="btn btn-lg custom-button" type="submit">
                    <!-- Use Bootstrap Icons for the sign-in icon -->
                    <i class="ion-log-in"></i> Login
                </button>
            </div>
        </form>
    </div>

    <!-- Add Bootstrap JS and Popper.js scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>