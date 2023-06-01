<?php

namespace ImageGenerator;

use ImageGenerator\Shape\Shape;
use InvalidArgumentException;

final class Canvas
{
    private \GdImage $image;

    private int $width;

    private int $height;

    /**
     * Creates a new canvas.
     *
     * @param int $width The canvas width in pixels. Must be greater than 0.
     * @param int $height The canvas height in pixels. Must be greater than 0.
     */
    public function __construct(int $width, int $height)
    {
        if ($width <= 0) {
            throw new InvalidArgumentException('The width must be greater than 0.');
        }

        if ($height <= 0) {
            throw new InvalidArgumentException('The height must be greater than 0.');
        }

        $this->width = $width;
        $this->height = $height;

        $image = imagecreatetruecolor($width, $height);

        if (false === $image) {
            throw new \RuntimeException('Could not create the image');
        }

        $this->image = $image;
    }

    /**
     * Returns the canvas width.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Returns the canvas height.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Enables background transparency.
     *
     * @return Canvas
     */
    public function enableTransparency(): Canvas
    {
        $color = imagecolorallocatealpha($this->image, 0, 0, 0, 127);

        if (false === $color) {
            throw new \RuntimeException('Could not allocate color.');
        }

        imagefill($this->image, 0, 0, $color);
        imagesavealpha($this->image, true);

        return $this;
    }

    /**
     * Draws a shape on the canvas.
     *
     * @param Shape $shape The shape to draw.
     * @param Color $color The color used to draw the shape.
     *
     * @return Canvas
     */
    public function draw(Shape $shape, Color $color): Canvas
    {
        $shape->draw($this, $color);
        return $this;
    }

    /**
     * Fills the canvas with a given color.
     *
     * @param Color $color The color used to fill the canvas.
     *
     * @return Canvas
     */
    public function fill(Color $color): Canvas
    {
        $color = imagecolorallocatealpha(
            $this->image,
            $color->getRed(),
            $color->getGreen(),
            $color->getBlue(),
            (int)(127 - $color->getAlpha() * 127)
        );

        if (false === $color) {
            throw new \RuntimeException('Could not allocate color.');
        }

        imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $color);
        imagecolordeallocate($this->image, $color);

        return $this;
    }

    /**
     * Render the canvas to an image.
     *
     * @param string|null $file The destination file path. When omitted a temporary file is created.
     * @param string $format The image format. Must be one of 'avif', 'bmp', 'gif', 'jpeg', 'png', 'wbmp', 'webp' or 'xbm'. Defaults to 'png'.
     * @param mixed ...$options Additional options passed directly to the underlying image generation function.
     *
     * @return Image
     */
    public function render(string $file = null, string $format = 'png', mixed ...$options): Image
    {
        if (is_null($file)) {
            $file = tempnam(sys_get_temp_dir(), 'img');

            if ($file === false) {
                throw new \RuntimeException('Could not create temporary file.');
            }
        }

        switch ($format) {
            case 'avif':
                if (version_compare(PHP_VERSION, '8.1.0') < 0) {
                    throw new \RuntimeException('The AVIF format is available starting with PHP 8.1.');
                }

                $mimeType = image_type_to_mime_type(IMG_AVIF);
                imageavif($this->image, $file, ...$options);
                break;

            case 'bmp':
                $mimeType = image_type_to_mime_type(IMG_BMP);
                imagebmp($this->image, $file, ...$options);
                break;

            case 'gif':
                $mimeType = image_type_to_mime_type(IMG_GIF);
                imagegif($this->image, $file);
                break;

            case 'jpeg':
                $mimeType = image_type_to_mime_type(IMG_JPG);
                imagejpeg($this->image, $file, ...$options);
                break;

            case 'png':
                $mimeType = image_type_to_mime_type(IMG_PNG);
                imagepng($this->image, $file, ...$options);
                break;

            case 'wbmp':
                $mimeType = image_type_to_mime_type(IMG_WBMP);
                imagewbmp($this->image, $file);
                break;

            case 'webp':
                $mimeType = image_type_to_mime_type(IMG_WEBP);
                imagewebp($this->image, $file, ...$options);
                break;

            case 'xbm':
                $mimeType = image_type_to_mime_type(IMG_XPM);
                imagexbm($this->image, $file);
                break;

            default:
                throw new \LogicException(sprintf('Unsupported image format "%s".', $format));
        }

        return new Image($file, $this->width, $this->height, $mimeType);
    }

    /**
     * Creates a new canvas.
     *
     * @param int $width The canvas width in pixels. Must be greater than 0.
     * @param int $height The canvas height in pixels. Must be greater than 0.
     *
     * @return Canvas
     */
    public static function create(int $width, int $height): Canvas
    {
        return new Canvas($width, $height);
    }

    /**
     * @internal
     */
    public function getGeometricMean(): float
    {
        return sqrt($this->width * $this->height);
    }

    /**
     * @internal
     */
    public function getImage(): \GdImage
    {
        return $this->image;
    }
}
