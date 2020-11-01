<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

<?php
$errors = [];
$fileErrors = [];

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $order = array_map('trim', $_POST);
    $inputLength = 100;

    if (empty($order['firstname'])) {
        $errors[] = 'Le champ prénom est obligatoire';
    }
    if (!empty($order['firstname']) && strlen($order['firstname']) > $inputLength) {
        $errors[] = 'Le champ prénom doit contenir moins de ' . $inputLength . ' caractères';
    }
    if (empty($order['lastname'])) {
        $errors[] = 'Le champ nom est obligatoire';
    }
    if (!empty($order['lastname']) && strlen($order['lastname']) > $inputLength) {
        $errors[] = 'Le champ nom doit faire contenir de ' . $inputLength . ' caractères';
    }


    if (empty($errors) && !empty($_FILES['userLogos']['name'][0])) {
        $uploadDir = 'uploads/';

        $extensions = ['.png', '.gif', '.jpg', '.jpeg'];
        $maxSize = 100000;

        for($i=0; $i<count($_FILES['userLogos']['name']); $i++) {
            $extension = strrchr($_FILES['userLogos']['name'][$i], '.');
            $size = filesize($_FILES['userLogos']['tmp_name'][$i]);

            if (!in_array($extension, $extensions)) {
                $fileErrors[] = 'Fichier n°' . $i . ': vous devez uploader un fichier de type png, gif, jpg ou jpeg';
            }
            if ($size > $maxSize) {
                $fileErrors[] = 'Fichier n°' . $i . ': le fichier doit faire moins de ' . $maxSize / 100000 . " Mo";
            }

            if (empty($fileErrors)) {
                $tmpFilePath = $_FILES['userLogos']['tmp_name'][$i];
                $newFileName = uniqid() . $extension;
                move_uploaded_file($tmpFilePath , $uploadDir . $newFileName);
            }
        }
    }
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1> Formulaire </h1>
        </div>
    </div>
    <div class="row">
        <?php if (!empty($errors)) {
            foreach ($errors as $error) { ?>
                <div class="col-md-8 offset-md-2">
                    <?= $error ?>
                </div>
            <?php } } ?>
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Vos informations</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="lastname"> Nom : </label>
                    <input type="text" name="lastname" id="lastname" placeholder="Nom" value="<?= $order['lastname'] ?? '' ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="firstname"> Prénom : </label>
                    <input type="text" name="firstname" id="firstname" placeholder="Prénom" value="<?= $order['firstname'] ?? '' ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="userLogos">Votre fichier</label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                    <input type="file" id="userLogos" name="userLogos[]" multiple="multiple" class="form-control-file">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-dark">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$files = new FilesystemIterator(__DIR__.'/uploads', FilesystemIterator::SKIP_DOTS);

foreach ($files as $file) { ?>
    <figure>
        <img src="uploads/<?= $file->getFilename() ?>"
             alt="image <?= $file->getFilename() ?>">
    </figure>
<?php  }
?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>

