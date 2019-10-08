<?php

use PHPUnit\Framework\TestCase;
use App\Container;

final class ShapeParserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testValidSquare()
    {
        $this->assertEquals(true, $this->container->parse("[13]"));
    }

    public function testValidNestedSquares()
    {
        $this->assertEquals(true, $this->container->parse("[13[29]]"));
        $this->assertEquals(true, $this->container->parse("[13[29][89]]"));
    }

    public function testValidMultipleSquares()
    {
        $this->assertEquals(true, $this->container->parse("[12][14]"));
        $this->assertEquals(true, $this->container->parse("[12][14][18]"));
    }

    public function testValidCircle()
    {
        $this->assertEquals(true, $this->container->parse("(CAT)"));
        $this->assertEquals(true, $this->container->parse("(CONTAINER)"));
    }

    public function testValidCircleWithNestedSquare()
    {
        $this->assertEquals(true, $this->container->parse("(CAT[15])"));
        $this->assertEquals(true, $this->container->parse("(CAT[15][18][23])"));
    }

    public function testValidCircleWithNestedCircle()
    {
        $this->assertEquals(true, $this->container->parse("(CAT(FOOD))"));
        $this->assertEquals(true, $this->container->parse("(CAT(FOOD)(PLAY)(MEOW))"));
    }

    public function testValidCircleWithNestedCircleAndSquare()
    {
        $this->assertEquals(true, $this->container->parse("(CAT(FOOD)[19])"));
        $this->assertEquals(true, $this->container->parse("(CAT(FOOD)(MEOW)[19][9])"));
    }

    public function testInvalidInput()
    {
        try {
            $this->container->parse("$@#");
        } catch (\Exception $e) {
            return;
        }
         
        $this->fail("Invalid input");
    }

    public function testMalformedInput()
    {
        try {
            $this->container->parse("[98)");
        } catch (\Exception $e) {
            return;
        }
         
        $this->fail("Invalid input");
    }

    public function testNoTags()
    {
        try {
            $this->container->parse("HELLO");
        } catch (\Exception $e) {
            return;
        }
        $this->fail("Invalid input");
    }

    public function testInvalidInnerShapeCircleInSquare()
    {
        $this->assertEquals(false, $this->container->parse("[89(HELLO]"));
    }

    public function testInvalidLabel()
    {
        try {
            $this->container->parse("[HELLO]");
        } catch (\Exception $e) {
            return;
        }
        $this->fail("Invalid label 'HELLO' for shape 'Square");

    }

    public function testNoLabel()
    {
        try {
            $this->container->parse("([])");
        } catch (\Exception $e) {
            return;
        }
        $this->fail("Invalid label '' for shape 'Circle'");
        
    }
}