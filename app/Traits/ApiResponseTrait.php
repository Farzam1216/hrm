<?php
namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * @var array
     */
    protected $responseData = [
        'response' => 0,
        'message' => "Not Found",
        'data' => null
    ];
    /**
     * @var int
     */
    protected $status = 404;
    /**
     * @return mixed
     */
    public function apiResponse()
    {
        return response()->json($this->responseData, $this->status);
    }
}
