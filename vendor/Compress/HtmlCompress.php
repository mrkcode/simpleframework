<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Compress;

/**
 * Description of Css
 *
 * @author Алексей
 */
class HtmlCompress extends Compresser {
    protected $minify = [
        '#\s*(<|>|/>)\s*#s' => '$1',
        '#[^\w]\s+[^\w]#'   => '',
        '#<\!\-\-.*\-\->#'  => ''
    ];
}
