<?php

declare(strict_types=1);

namespace App\Module\Admin\Image;

use App\Model\Image\Image;
use App\Model\Image\ImageData;
use App\Model\Image\ImageFacade;
use App\Module\Admin\BaseAdminPresenter;

class ImagePresenter extends BaseAdminPresenter
{
    public function __construct(
        private ImageFacade $imageFacade
    ) {
        parent::__construct();
    }

    public function actionUpload(): void
    {
        if (isset($_FILES['file']['name'])) {
            if (!$_FILES['file']['error']) {
                $data = new ImageData();
                $data->type = Image::TYPE_HTML_EDITOR;
                $data->originalName = $_FILES['file']['name'];

                $image = $this->imageFacade->create($data, function (string $destination) {
                    move_uploaded_file($_FILES['file']['tmp_name'], $destination);
                });
                
                echo $image->getPublicPath();
                
            } else {
                echo sprintf('The upload failed, error: %s', $_FILES['file']['error']);
            }
        }
        
        exit;
    }
}
