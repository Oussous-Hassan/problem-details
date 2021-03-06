<?php

namespace ProblemDetailsTest;

use PHPUnit\Framework\TestCase;
use ProblemDetails\ProblemDetailsException;
use ProblemDetails\CommonProblemDetailsException;

class ProblemDetailsExceptionTest extends TestCase
{
    protected $status = 403;
    protected $detail = 'You are not authorized to do that';
    protected $title = 'Unauthorized';
    protected $type = 'https://httpstatus.es/403';
    protected $additional = [
        'foo' => 'bar',
    ];

    protected function setUp()
    {
        $this->exception = new class (
            $this->status,
            $this->detail,
            $this->title,
            $this->type,
            $this->additional
        ) implements ProblemDetailsException {
            use CommonProblemDetailsException;

            private $status;
            private $type;
            private $title;
            private $detail;
            private $additional;

            public function __construct(int $status, string $detail, string $title, string $type, array $additional)
            {
                $this->status = $status;
                $this->detail = $detail;
                $this->title = $title;
                $this->type = $type;
                $this->additional = $additional;
            }
        };
    }

    public function testCanPullDetailsIndividually()
    {
        $this->assertEquals($this->status, $this->exception->getStatus());
        $this->assertEquals($this->detail, $this->exception->getDetail());
        $this->assertEquals($this->title, $this->exception->getTitle());
        $this->assertEquals($this->type, $this->exception->getType());
        $this->assertEquals($this->additional, $this->exception->getAdditionalData());
    }

    public function testCanCastDetailsToArray()
    {
        $this->assertEquals([
            'status' => $this->status,
            'detail' => $this->detail,
            'title'  => $this->title,
            'type'   => $this->type,
            'foo'    => 'bar',
        ], $this->exception->toArray());
    }

    public function testIsJsonSerializable()
    {
        $problem = json_decode(json_encode($this->exception), true);

        $this->assertEquals([
            'status' => $this->status,
            'detail' => $this->detail,
            'title'  => $this->title,
            'type'   => $this->type,
            'foo'    => 'bar',
        ], $problem);
    }
}
