<?php

namespace Teo\ProductBundle\Form;

class UploadManager
{
    protected $base_path;

    /**
     * The kernel root dir
     */
    public function __construct($path)
    {
        $this->base_path = dirname($path);
    }

    public function getUploadRootDir()
    {
        return $this->getWebDir() . DIRECTORY_SEPARATOR . $this->getUploadDir();
    }

    public function getWebDir()
    {
        return $this->base_path . '/web';
    }

    protected function getUploadDir()
    {
        return 'uploads/documents';
    }

    public function upload($image, $file)
    {
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        $cleanName = $this->cleanFileName($file);

        // move takes the target directory and then the
        // target filename to move to
        $file->move(
            $this->getUploadRootDir(),
            $cleanName
        );

        // set the path property to the filename where you've saved the file
        $image->setPath(DIRECTORY_SEPARATOR . $this->getUploadDir() . DIRECTORY_SEPARATOR . $cleanName);
    }

    public function uploadFile($file)
    {
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        $cleanName = $this->cleanFileName($file);

        // move takes the target directory and then the
        // target filename to move to
        $file->move(
            $this->getUploadRootDir(),
            $cleanName
        );

        return $this->getUploadRootDir() . "/" . $cleanName;
    }

    public function cleanFileName($file)
    {
        $name = $file->getClientOriginalName();
        $name = preg_replace('/[\s]+/', '_', $name);
        $name = preg_replace('/[\-]+/', '-', $name);

        return $name;
    }

    public function getImagePath()
    {
        if (!empty($this->image)) {
            return $this->getImage();
        }

        return null;
    }
}
