<?php

namespace ImageGenerator;

use InvalidArgumentException;

final class Color
{
    private int $red;

    private int $green;

    private int $blue;

    private float $alpha;

    /**
     * Creates a new color.
     *
     * @param int $red The color red component value. Must be between 0 and 255.
     * @param int $green The color green component value. Must be between 0 and 255.
     * @param int $blue The color blue component value. Must be between 0 and 255.
     * @param float $alpha The color alpha value. Must be between 0.0 (fully transparent) and 1.0 (fully opaque).
     */
    public function __construct(int $red, int $green, int $blue, float $alpha)
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);
        $this->setAlpha($alpha);
    }

    /**
     * Returns the color red component value.
     *
     * @return int
     */
    public function getRed(): int
    {
        return $this->red;
    }

    /**
     * Sets the color red component value.
     *
     * @param int $red The color red component value. Must be between 0 and 255.
     *
     * @return Color
     */
    public function setRed(int $red): Color
    {
        if ($red < 0 || $red > 255) {
            throw new InvalidArgumentException('The red component value must be between 0 and 255.');
        }

        $this->red = $red;
        return $this;
    }

    /**
     * Returns the color green component value.
     *
     * @return int
     */
    public function getGreen(): int
    {
        return $this->green;
    }

    /**
     * Sets the color green component value.
     *
     * @param int $green The color green component value. Must be between 0 and 255.
     *
     * @return Color
     */
    public function setGreen(int $green): Color
    {
        if ($green < 0 || $green > 255) {
            throw new InvalidArgumentException('The green component value must be between 0 and 255.');
        }

        $this->green = $green;
        return $this;
    }

    /**
     * Returns the color blue component value.
     *
     * @return int
     */
    public function getBlue(): int
    {
        return $this->blue;
    }

    /**
     * Sets the color blue component value.
     *
     * @param int $blue The color blue component value. Must be between 0 and 255.
     *
     * @return Color
     */
    public function setBlue(int $blue): Color
    {
        if ($blue < 0 || $blue > 255) {
            throw new InvalidArgumentException('The blue component value must be between 0 and 255.');
        }

        $this->blue = $blue;
        return $this;
    }

    /**
     * Returns the color alpha value.
     *
     * @return float
     */
    public function getAlpha(): float
    {
        return $this->alpha;
    }

    /**
     * Sets the color alpha value.
     *
     * @param float $alpha The color alpha value. Must be between 0.0 (fully transparent) and 1.0 (fully opaque).
     */
    public function setAlpha(float $alpha): Color
    {
        if ($alpha < 0 || $alpha > 1) {
            throw new InvalidArgumentException('The alpha value must be between 0 and 1.');
        }

        $this->alpha = $alpha;
        return $this;
    }

    /**
     * Creates a new color.
     *
     * @param int $red The color red component value. Must be between 0 and 255.
     * @param int $green The color green component value. Must be between 0 and 255.
     * @param int $blue The color blue component value. Must be between 0 and 255.
     * @param float $alpha The color alpha value. Must be between 0.0 (fully transparent) and 1.0 (fully opaque).
     *
     * @return Color
     */
    public static function create(int $red, int $green, int $blue, float $alpha): Color
    {
        return new Color($red, $green, $blue, $alpha);
    }

    /**
     * Creates a random color.
     *
     * @param array{
     *     min_red?: int,
     *     max_red?: int,
     *     min_green?: int,
     *     max_green?: int,
     *     min_blue?: int,
     *     max_blue?: int,
     *     min_alpha?: float,
     *     max_alpha?: float
     * } $options An optional array of options. The following options are supported:
     *
     * min_red - The color red component minimum value. Must be between 0 and 255. Defaults to 0.
     * max_red - The color red component maximum value. Must be between 0 and 255. Defaults to 255.
     * min_green - The color green component minimum value. Must be between 0 and 255. Defaults to 0.
     * max_green - The color green component maximum value. Must be between 0 and 255. Defaults to 255.
     * min_blue - The color blue component minimum value. Must be between 0 and 255. Defaults to 0.
     * max_blue - The color blue component maximum value. Must be between 0 and 255. Defaults to 255.
     * min_alpha - The color minimum alpha value. Must be between 0.0 and 1.0. Defaults to 0.6.
     * max_alpha - The color maximum alpha value. Must be between 0.0 and 1.0. Defaults to 0.8.
     *
     * @return Color
     */
    public static function random(array $options = []): Color
    {
        $minRed = $options['min_red'] ?? 0;
        $maxRed = $options['max_red'] ?? 255;
        $red = rand($minRed, $maxRed);

        $minGreen = $options['min_green'] ?? 0;
        $maxGreen = $options['max_green'] ?? 255;
        $green = rand($minGreen, $maxGreen);

        $minBlue = $options['min_blue'] ?? 0;
        $maxBlue = $options['max_blue'] ?? 255;
        $blue = rand($minBlue, $maxBlue);

        $minAlpha = (int)(($options['min_alpha'] ?? 0.6) * 100);
        $maxAlpha = (int)(($options['max_alpha'] ?? 0.8) * 100);
        $alpha = rand($minAlpha, $maxAlpha) / 100;

        return new Color($red, $green, $blue, $alpha);
    }
}
