<?php

declare(strict_types=1);

namespace ImageGenerator\Shape;

use ImageGenerator\Canvas;
use ImageGenerator\Color;

abstract class Shape
{
    protected float $ratio;

    /**
     * Returns the shape size ratio.
     *
     * @return float
     */
    public function getRatio(): float
    {
        return $this->ratio;
    }

    /**
     * Sets the shape size ratio.
     *
     * @param float $ratio The shape size ratio. Must be between 0.0 and 1.0.
     *
     * @return self
     */
    public function setRatio(float $ratio): self
    {
        if ($ratio < 0 || $ratio > 1) {
            throw new \InvalidArgumentException('The size ratio must be between 0.0 and 1.0.');
        }

        $this->ratio = $ratio;
        return $this;
    }

    abstract public function draw(Canvas $canvas, Color $color): void;

    /**
     * Creates a random shape.
     *
     * @param array{
     *     circle?: array{
     *         min_ratio?: float,
     *         max_ratio?: float
     *     },
     *     polygon?: array{
     *         min_ratio?: float,
     *         max_ratio?: float,
     *         min_sides?: int,
     *         max_sides?: int,
     *         min_rotation?: int,
     *         max_rotation?: int
     *     },
     *     star?: array{
     *         min_ratio?: float,
     *         max_ratio?: float,
     *         min_points?: int,
     *         max_points?: int,
     *         min_rotation?: int,
     *         max_rotation?: int
     *     }
     * } $options An optional array of options for each shape.
     *
     * See each individual shape `random()` method for a list of supported options.
     *
     * @return Shape
     */
    public static function random(array $options = []): Shape
    {
        $shapes = ['circle', 'polygon', 'star'];
        $shape = $shapes[array_rand($shapes)];

        return match ($shape) {
            'circle' => Circle::random($options['circle'] ?? []),
            'polygon' => Polygon::random($options['polygon'] ?? []),
            'star' => Star::random($options['star'] ?? []),
        };
    }
}
