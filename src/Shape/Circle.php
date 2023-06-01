<?php

declare(strict_types=1);

namespace ImageGenerator\Shape;

use ImageGenerator\Canvas;
use ImageGenerator\Color;

final class Circle extends Shape
{
    /**
     * Creates a new circle.
     *
     * @param float $ratio The circle size ratio. Must be between 0.0 and 1.0.
     */
    public function __construct(float $ratio)
    {
        $this->setRatio($ratio);
    }

    /**
     * @internal
     */
    public function draw(Canvas $canvas, Color $color): void
    {
        $radius = (int)($this->getRatio() * $canvas->getGeometricMean() / 2);

        $x = rand(-$radius, $canvas->getWidth() + $radius);
        $y = rand(-$radius, $canvas->getHeight() + $radius);

        $color = imagecolorallocatealpha(
            $canvas->getImage(),
            $color->getRed(),
            $color->getGreen(),
            $color->getBlue(),
            (int)(127 - $color->getAlpha() * 127)
        );

        if (false === $color) {
            throw new \RuntimeException('Could not allocate color.');
        }

        imagefilledellipse($canvas->getImage(), $x, $y, $radius * 2, $radius * 2, $color);
        imagecolordeallocate($canvas->getImage(), $color);
    }

    /**
     * Creates a random circle.
     *
     * @param array{min_ratio?: float, max_ratio?: float} $options An optional array of options.
     *
     * The following options are supported:
     *
     * min_ratio - The circle minimum size ratio. Must be between 0.0 and 1.0. Defaults to 0.125.
     * max_ratio - The circle maximum size ratio. Must be between 0.0 and 1.0. Defaults to 0.25.
     *
     * @return Circle
     *
     * @phpstan-ignore-next-line
     */
    public static function random(array $options = []): Circle
    {
        $minRatio = (int)(($options['min_ratio'] ?? 0.125) * 100);
        $maxRatio = (int)(($options['max_ratio'] ?? 0.25) * 100);
        $ratio = rand($minRatio, $maxRatio) / 100;

        return new Circle($ratio);
    }
}
