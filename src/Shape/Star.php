<?php

declare(strict_types=1);

namespace ImageGenerator\Shape;

use ImageGenerator\Canvas;
use ImageGenerator\Color;

final class Star extends Shape
{
    private int $points;

    private int $rotation;

    /**
     * Creates a new star.
     *
     * @param float $ratio The star size ratio. Must be between 0.0 and 1.0.
     * @param int $points The star number of points. Must be between 4 and 8.
     * @param int $rotation The star rotation angle in degrees. Must be between 0 and 360.
     */
    public function __construct(float $ratio, int $points, int $rotation)
    {
        $this->setRatio($ratio);
        $this->setPoints($points);
        $this->setRotation($rotation);
    }

    /**
     * Returns the star number of points.
     *
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * Sets the star number of points
     *
     * @param int $points The star number of points. Must be between 4 and 8.
     *
     * @return Star
     */
    public function setPoints(int $points): Star
    {
        if ($points < 4 || $points > 8) {
            throw new \InvalidArgumentException('The number of points must be between 4 and 8.');
        }

        $this->points = $points;
        return $this;
    }

    /**
     * Returns the star rotation angle.
     *
     * @return int
     */
    public function getRotation(): int
    {
        return $this->rotation;
    }

    /**
     * Sets the star rotation angle.
     *
     * @param int $rotation The star rotation angle in degrees. Must be between 0 and 360.
     *
     * @return Star
     */
    public function setRotation(int $rotation): Star
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

        $sides = $this->getPoints() * 2;
        $points = [];

        for ($side = 0; $side < $sides; $side++) {
            $angle = deg2rad($this->getRotation() + 360 / $sides * $side);
            $distance = (int)($side % 2 === 0 ? $radius : $radius / 2);
            $points[] = $x + $distance * cos($angle);
            $points[] = $y + $distance * sin($angle);
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
     * Creates a random star.
     *
     * @param array{
     *     min_ratio?: float,
     *     max_ratio?: float,
     *     min_points?: int,
     *     max_points?: int,
     *     min_rotation?: int,
     *     max_rotation?: int
     * } $options An optional array of options.
     *
     * The following options are supported:
     *
     * min_ratio - The star minimum size ratio. Must be between 0.0 and 1.0. Defaults to 0.125.
     * max_ratio - The star maximum size ratio. Must be between 0.0 and 1.0. Defaults to 0.25.
     * min_points - The star minimum number of points. Must be between 4 and 8. Defaults to 4.
     * max_points - The star maximum number of points. Must be between 4 and 8. Defaults to 8.
     * min_rotation - The star minimum rotation angle in degrees. Must be between 0 and 360. Defaults to 0.
     * max_rotation - The star maximum rotation angle in degrees. Must be between 0 and 360. Defaults to 360.
     *
     * @return Star
     *
     * @phpstan-ignore-next-line
     */
    public static function random(array $options = []): Star
    {
        $minRatio = (int)(($options['min_ratio'] ?? 0.125) * 100);
        $maxRatio = (int)(($options['max_ratio'] ?? 0.25) * 100);
        $ratio = rand($minRatio, $maxRatio) / 100;

        $minPoints = $options['min_points'] ?? 4;
        $maxPoints = $options['max_points'] ?? 8;
        $points = rand($minPoints, $maxPoints);

        $minRotation = $options['min_rotation'] ?? 0;
        $maxRotation = $options['max_rotation'] ?? 360;
        $rotation = rand($minRotation, $maxRotation);

        return new Star($ratio, $points, $rotation);
    }
}
