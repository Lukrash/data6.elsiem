<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <title>Elsiem Info</title>
    </head>
    <body>
        <div class="container-fluid bg-register text-center">
            <div class="jumbotron">
                <h1 class="display-4">
                <img src="./core/arts/elsiem.png" alt="Elsiem Logo" width="150px">
                    404 not found!!
                </h1>
                <p class="lead"><?=$arrResponse["framework"]?></p>
                <hr class="my-4">
                <p class="lead">
                    <div class="card-deck">
                        <div class="card border-dark" >
                            <div class="card-header"><?=$arrResponse["application"]?></div>
                            <div class="card-body text-dark">
                                <h5 class="card-title">Error: <?=$arrResponse["message"]?></h5>
                                <h6 class="card-title">Controller: <?=$arrResponse["controller"]?></h6>
                                <h6 class="card-title">Action: <?=$arrResponse["action"]?></h6>
                            </div>
                        </div>
                    </div>
                </p>
            </div>
        </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
    <footer class='container-fluid bg-footer text-center'>
        <p><b><?=$arrResponse["framework"]?></b> Created by <a href="<?=$arrResponse["url_author"]?>" target="_blank"><?=$arrResponse["author"]?></a> in 
        <?=$arrResponse["first_version"]?>.<br> Copyright version <?=$arrResponse["version"]?> [<?=$arrResponse["current_version"]?>]</p>
        <p>Powered by Bootstrap 4.0</p> 
    </footer>
</html>