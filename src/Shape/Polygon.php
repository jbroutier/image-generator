<?php

declare(strict_types=1);

namespace ImageGenerator\Shape;

use ImageGenerator\Canvas;
use ImageGenerator\Color;

final class Polygon extends Shape
{
    private int $sides;

    private int $rotation;

    /**
     * Creates a new polygon.
     *
     * @param float $ratio The polygon size ratio. Must be between 0.0 and 1.0.
     * @param int $sides The polygon number of sides. Must be between 3 and 12.
     * @param int $rotation The polygon rotation angle in degrees. Must be between 0 and 360.
     */
    public function __construct(float $ratio, int $sides, int $rotation)
    {
        $this->setRatio($ratio);
        $this->setSides($sides);
        $this->setRotation($rotation);
    }

    /**
     * Returns the polygon number of sides.
     *
     * @return int
     */
    public function getSides(): int
    {
        return $this->sides;
    }

    /**
     * Sets the polygon number of sides.
     *
     * @param int $sides The polygon number of sides. Must be between 3 and 12.
     *
     * @return Polygon
     */
    public function setSides(int $sides): Polygon
    {
        if ($sides < 3 || $sides > 12) {
            throw new \InvalidArgumentException('The number of sides must be between 3 and 12.');
        }

        $this->sides = $sides;
        return $this;
    }

    /**
     * Returns the polygon rotation angle.
     *
     * @return int
     */
    public function getRotation(): int
    {
        return $this->rotation;
    }

    /**
     * Sets the polygon rotation angle.
     *
     * @param int $rotation The polygon rotation angle in degrees. Must be between 0 and 360.
     *
     * @return Polygon
     */
    public function setRotation(int $rotation): Polygon
    {
        if ($rotation < 0 || $rotation > 360) {
            throw new \InvalidArgumentException('The rotation angle must be between 0 and 360.');
        }

        $this->rotation = $rotation;
        return $this;
    }

    /**
     * @internal
     */
    public function draw(Canvas $canvas, Color $color): void
    {
        $radius = (int)($this->getRatio() * $canvas->getGeometricMean() / 2);

        $x = rand(-$radius, $canvas->getWidth() + $radius);
        $y = rand(-$radius, $canvas->getHeight() + $radius);

        $points = [];

        for ($side = 0; $side < $this->getSides(); $side++) {
            $angle = deg2rad($this->getRotation() + 360 / $this->getSides() * $side);
            $points[] = $x + $radius * cos($angle);
            $points[] = $y + $radius * sin($angle);
        }

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

        imagefilledpolygon($canvas->getImage(), $points, $color);
        imagecolordeallocate($canvas->getImage(), $color);
    }

    /**
     * Creates a random polygon.
     *
     * @param array{
     *     min_ratio?: float,
     *     max_ratio?: float,
     *     min_sides?: int,
     *     max_sides?: int,
     *     min_rotation?: int,
     *     max_rotation?: int
     * } $options An optional array of options.
     *
     * The following options are supported:
     *
     * min_radius - The polygon minimum size ratio. Must be between 0.0 and 1.0. Defaults to 0.125.
     * max_radius - The polygon maximum size ratio. Must be between 0.0 and 1.0. Defaults to 0.25.
     * min_sides - The polygon minimum number of sides. Must be between 3 and 12. Defaults to 3.
     * max_sides - The polygon maximum number of sides. Must be between 3 and 12. Defaults to 12.
     * min_rotation - The polygon minimum rotation angle in degrees. Must be between 0 and 360. Defaults to 0.
     * max_rotation - The polygon maximum rotation angle in degrees. Must be between 0 and 360. Defaults to 360.
     *
     * @return Polygon
     *
     * @phpstan-ignore-next-line
     */
    public static function random(array $options = []): Polygon
    {
        $minRatio = (int)(($options['min_ratio'] ?? 0.125) * 100);
        $maxRatio = (int)(($options['max_ratio'] ?? 0.25) * 100);
        $ratio = rand($minRatio, $maxRatio) / 100;

        $minSides = $options['min_sides'] ?? 3;
        $maxSides = $options['max_sides'] ?? 12;
        $sides = rand($minSides, $maxSides);

        $minRotation = $options['min_rotation'] ?? 0;
        $maxRotation = $options['max_rotation'] ?? 360;
        $rotation = rand($minRotation, $maxRotation);

        return new Polygon($ratio, $sides, $rotation);
    }
}
