<?php

namespace ImageGenerator;

final class Image extends \SplFileInfo
{
    private int $width;

    private int $height;

    private string $mimeType;

    /**
     * Creates a new image.
     *
     * @param string $path The file path.
     * @param int $width The image width.
     * @param int $height The image height.
     * @param string $mimeType The image MIME type.
     */
    public function __construct(string $path, int $width, int $height, string $mimeType)
    {
        parent::__construct($path);

        $this->width = $width;
        $this->height = $height;
        $this->mimeType = $mimeType;
    }

    /**
     * Returns the image width.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Returns the image height.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Returns the image MIME type.
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}
