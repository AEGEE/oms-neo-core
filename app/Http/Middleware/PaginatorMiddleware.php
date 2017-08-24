<?php

namespace App\Http\Middleware;

use Closure;
use Input;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatorMiddleware {    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        $result = $next($request);

        $page = Input::get('page', 1);
        $per_page = Input::get('per_page', 10);
        $response = $result->getOriginalContent();

        if (isset($response['data'])) {
            if (is_a($response['data'], 'Illuminate\Database\Eloquent\Builder')) {
                $response['data'] = $response['data']->paginate($per_page);
                $response = json_encode($response);
                $result->setContent($response);
            } else if(is_a($response['data'], 'Illuminate\Support\Collection')) {
                $response['data'] = $this->paginateCollection($request, $response['data'], $per_page, $page);
                $response = json_encode($response);
                $result->setContent($response);
            }
        }
        return $result;
    }

    private function paginateCollection($request, $collection, $per_page, $page) {
        $offset = ($page * $per_page) - $per_page;

        return new LengthAwarePaginator(
            array_slice($collection->toArray(), $offset, $per_page, true), // Only grab the items we need
            count($collection), // Total items
            $per_page, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
        );
    }
}
