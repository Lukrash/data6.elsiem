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
                    <?=$arrResponse["framework"]?>
                </h1>
                <p class="lead"><?=$arrResponse["description"]?></p>
                <hr class="my-4">
                <p><?=$arrResponse["long_description"]?></p>
                <p class="lead">
                    <div class="card border-dark" >
                        <div class="card-header"><?=$arrResponse["company_application"]?></div>
                        <div class="card-body text-dark">
                            <h5 class="card-title"><?=$arrResponse["application"]?></h5>
                            <p class="card-text"><?=$arrResponse["description_application"]?></p>
                            <p class="card-text">version <?=$arrResponse["version_application"]?></p>
                        </div>
                    </div>
                    <br>
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseDb" aria-expanded="false" aria-controls="collapseDb">
                                Database Module
                                </button>
                                <small class="text-muted">
                                    <?php
                                        print (empty($arrResponse['database']['state']) ? "Undefined" : "Defined"); 
                                    ?>
                                </small>
                            </h5>
                            </div>
                            <div id="collapseDb" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead class="thead-dark">
                                            <tr>
                                            <th scope="col" colspan=2>General Information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <th scope="row">Name</th>
                                            <td><?=$arrResponse['database']['dbName']?></td>
                                            </tr>
                                            <tr>
                                            <th scope="row">Driver</th>
                                            <td><?=$arrResponse['database']['driver']?></td>
                                            </tr>
                                            <tr>
                                            <th scope="row">State</th>
                                            <td><?=$arrResponse['database']['state']?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSec" aria-expanded="false" aria-controls="collapseSec">
                                Security Module
                                </button>
                                <small class="text-muted">
                                    <?php
                                        print (empty($arrResponse['security']['state']) ? "Undefined" : "Defined"); 
                                    ?>
                                </small>
                            </h5>
                            </div>
                            <div id="collapseSec" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead class="thead-dark">
                                            <tr>
                                            <th scope="col" colspan=2>General Information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <th scope="row">Type</th>
                                            <td><?=$arrResponse['security']['type']?></td>
                                            </tr>
                                            <tr>
                                            <th scope="row">Description</th>
                                            <td><?=$arrResponse['security']['desc']?></td>
                                            </tr>
                                            <tr>
                                            <th scope="row">More Info:</th>
                                            <td>
                                                <?php
                                                    print "<a href='".$arrResponse['security']['info']."' target='_blank'>". $arrResponse['security']['info'] . "</a>";
                                                ?>
                                            </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseCom" aria-expanded="false" aria-controls="collapseCom">
                                Communication Module
                                </button>
                                <small class="text-muted">
                                    <?php
                                        print (empty($arrResponse['communication']['state']) ? "Undefined" : "Defined"); 
                                    ?>
                                </small>
                            </h5>
                            </div>
                            <div id="collapseCom" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                <table class="table table-sm">
                                        <thead class="thead-dark">
                                            <tr>
                                            <th scope="col" colspan=2>General Information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $arrApis = $arrResponse['communication']['Apis'];
                                                foreach($arrApis as $key => $value ){
                                                    print ("<thead class='thead-light'><tr><th scope='col' colspan=2>".$key."</th></tr></thead>"); 
                                                    print ("<tr><th scope='row'>Api</th><td><a href='".$value['Url']."' target='blank'>".$value['Url']."</td></tr>");
                                                    print ("<tr><th scope='row'>Description</th><td>". $value['Description'] ."</td></tr>");
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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
