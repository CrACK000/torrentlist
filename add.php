<?php

require 'app/app.php';
require 'app/Functions.php';

$category = $db->select(
    'SELECT * FROM categories'
);

if (!$auth->isLoggedIn()) { header('Location: '.URL); }

if (isset($_POST['add'])) {

    $filename = toAscii($_POST['title'], '_');

    $filesymbol = generateRandomString(10);

    $storage = new \Upload\Storage\FileSystem('uploads/torrents');
    $file = new \Upload\File('file', $storage);

    $file->addValidations(array(
        new \Upload\Validation\Mimetype('application/x-bittorrent')
    ));

    $file->setName('[Torrentlist]('.$filesymbol.')'.$filename);

    try {

        $file->upload();

        if ($inuser['important'] == 1){
            $disabled = 0;
        } else {
            $disabled = 1;
        }

        $insert = $db->insert(
            'torrents',
            [
                'title' => $_POST['title'],
                'category' => $_POST['category'],
                'file' => '[Torrentlist]('.$filesymbol.')'.$filename.'.torrent',
                'file_symbol' => $filesymbol,
                'description' => $_POST['description'],
                'owner' => $auth->getUserId(),
                'disabled' => $disabled,
                'date_added' => time()
            ]
        );

        if ($insert){
            $errors = '<div class="alert alert-success">torrent has been successfully added to the database</div>';
        }

    } catch (\Exception $e) {
        $errors = '<div class="alert alert-danger">file failed to upload</div>';
    }

}

$title = 'Add torrent';

echo '
<!DOCTYPE html>
<html lang="en">

    <head>'; require 'template/template_headtags.php'; echo '</head>

    <body class="app sidebar-mini rtl">
    
        '; require 'template/template_head.php';

           require 'template/template_sidebar.php'; echo '
        
        
        
        <main class="app-content">
        
            <div class="app-title">
            
                <div>
                    <h1><i class="fas fa-plus-circle"></i> Add torrent</h1>
                    <p>Do you want to create your own torrent? Go to the <a href="'.URL.'/create-torrent">Create torrent</a> page.</p>
                </div>
                
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
                    <li class="breadcrumb-item"><a href="' . URL . '/add">add torrent</a></li>
                </ul>
                
            </div>
            
            <div class="row">
                <div class="col-md-12">
                
                    '.$errors.'
                
                    <div class="tile">
                        <h3 class="tile-title">Add torrent</h3>
                        <div class="tile-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label" for="title">Title</label>
                                    <input class="form-control" type="text" name="title" id="title" placeholder="Lorem ipsum dolor sit amet, consectetuer...">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="category">Category</label>
                                    <select class="form-control" id="category" name="category">
                                        <optgroup label="Category">';

                                            foreach ($category as $cat) {

                                                echo '<option value="'.$cat['id'].'">'.$cat['title'].'</option>';

                                            }

                                        echo '
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="file">Torrent file <small class="text-muted">(.torrent)</small></label>
                                    <input class="form-control" name="file" id="file" type="file" accept="application/x-bittorrent">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="ckeditor">Description</label>
                                    <textarea class="form-control" name="description" rows="5" id="ckeditor"></textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary mr-3" type="submit" name="add"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add</button> <a class="btn btn-secondary" href="'.URL.'"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                
                </div>
            </div>
            
            '; require 'template/template_footer.php'; echo '
            
        </main>
        
        '; require 'template/template_scripts.php'; echo '

        <script src="'.URL.'/assets/js/plugins/select2.min.js"></script>
        
        <script>
            $(\'#category\').select2();
        </script>
        
    </body>
</html>';