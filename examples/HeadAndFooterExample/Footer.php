<?php
/**
 * This file is part of the PXP package.
 *
 * (c) Matthew Heroux <matthewheroux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pxp\Component;

class Footer extends Component
{

    public function onRender()
    {
        $year = date('Y');
        return <<<HTML
<footer>
    <hr/>
    <p>&copy; {$year} </p>
</footer>
<script/>
HTML;
    }
}