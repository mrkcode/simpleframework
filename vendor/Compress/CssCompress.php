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
class CssCompress extends Compresser{
    protected $minify = [
        '#\s*/\*.*?\*/\s*#s'      => '',
        '#\s*(\:|\;|\{|\}|,)\s*#' => '$1',
        '#\s+#'                   => ' ',
        //'#</?style>#'             => ''
    ];
}
