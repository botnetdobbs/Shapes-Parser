<?php

namespace App;

interface ShapesParser
{
    /**
     * Function to parse the input string
     *
     * @param String $input
     * @return string
     */
    public function parse(String $input);
}