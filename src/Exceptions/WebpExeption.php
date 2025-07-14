<?php

namespace Cow\Gallery\Exceptions;

use Exception;

class WebpExeption extends Exception
{
    public function render($request)
    {
        return response()->json(["error" => true, "message" => $this->getMessage()]);
    }
}
