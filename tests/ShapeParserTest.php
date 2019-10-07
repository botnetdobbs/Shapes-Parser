<?php

use PHPUnit\Framework\TestCase;
use App\Container;

final class ShapeParserTest extends TestCase
{
    // protected function setUp(): void
    // {

    // }

    public function testValidSquare()
    {
        $this->assertTrue(true, "");
    }

    public function testValidNestedSquares()
    {
        $this->assertTrue(true, "");
    }

    public function testValidMultipleSquares()
    {
        $this->assertTrue(true, "");
    }

    public function testValidCircle()
    {
        $this->assertTrue(true, "");
    }

    public function testValidCircleWithNestedSquare()
    {
        $this->assertTrue(true, "");
    }

    public function testValidCircleWithNestedCircle()
    {
        $this->assertTrue(true, "");
    }

    public function testValidCircleWithNestedCircleAndSquare()
    {
        $this->assertTrue(true, "");
    }

    public function testInvalidInput()
    {
        $this->assertTrue(true, "");
    }

    public function testMalformedInput()
    {
        $this->assertTrue(true, "");
    }

    public function testNoTags()
    {
        $this->assertTrue(true, "");
    }

    public function testInvalidInnerShapeCircleInSquare()
    {
        $this->assertTrue(true, "");
    }

    public function testInvalidLabel()
    {
        $this->assertTrue(true, "");
    }

    public function testNoLabel()
    {
        $this->assertTrue(true, "");
    }
}