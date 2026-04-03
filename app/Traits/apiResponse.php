<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait ApiResponse
{
    /**
     * Convert any model or collection into plain array recursively
     */
    protected function formatModelData($items)
    {
        if ($items instanceof Collection) {
            return $items->map(fn($item) => $this->formatModelData($item))->all();
        } elseif ($items instanceof Model) {
            return $items->toArray();
        } elseif (is_array($items)) {
            return array_map(fn($item) => $this->formatModelData($item), $items);
        }

        return $items;
    }

    /**
     * Standard API response
     */
    protected function apiResponse($data, $status = 200, $message = null)
    {
        $formattedData = $this->formatModelData($data);

        if ($message) {
            return response()->json(['data' => $formattedData, 'message' => $message], $status);
        }

        return response()->json(['data' => $formattedData], $status);
    }

    /**
     * Standard API error
     */
    protected function apiErrorResponse($message, $status = 400)
    {
        return response()->json(['error' => $message], $status);
    }

    /**
     * Check if request is API
     */
    protected function isApiRequest()
    {
        return request()->is('api/*');
    }
    protected function generateResponse(array $data = [])
    {
        if ($this->isApiRequest()) {
            return $this->apiResponse($data);
        }

        return redirect()->route($this->getIndexRoute());
    }
}
