<?php
/**
 * This file is part of the PXP package.
 *
 * (c) Matthew Heroux <matthewheroux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require '../../vendor/autoload.php';

// instantiate Director
$director = new Pxp\Director();

// instantiate Builder
$builder = new Pxp\Builder\DynamicPageBuilder();

// define build parameters
$parameters = [
    'filename' => __DIR__ . DIRECTORY_SEPARATOR . 'input.html',
    'handlers' => [
        '//img'   => 'Pxp\Component\Img',
    ],
    'hooks' => [
        'onRender'      => 'RETURN_CALL',
    ]
];

// echo Director build PageBuilder
echo $director->build($builder, $parameters);