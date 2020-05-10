<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Image;

use Minecord\Model\Image\Image;
use Minecord\Model\Image\ImageData;
use Minecord\Model\Image\ImageFacade;
use Minecord\Module\Admin\BaseAdminPresenter;

class ImagePresenter extends BaseAdminPresenter
{
	private ImageFacade $imageFacade;

	public function __construct(
		ImageFacade $imageFacade
	) {
		parent::__construct();
		$this->imageFacade = $imageFacade;
	}

	public function actionUpload(): void
	{
		if (isset($_FILES['file']['name'])) {
			if (!$_FILES['file']['error']) {
				$data = new ImageData();
				$data->type = Image::TYPE_HTML_EDITOR;
				$data->originalName = $_FILES['file']['name'];

				$image = $this->imageFacade->create($data, function (string $destination) use ($data) {
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
