<?php

namespace App\Http\Middleware;

use Closure;
use Input;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatorMiddleware {

    public function handle($request, Closure $next, $guard = null)
    {
        $result = $next($request);

        $page = Input::get('page', 1);
        $per_page = Input::get('per_page', 10);
        $data = $result->getInnerData();

        $paginator = null;
        if (is_a($data, 'Illuminate\Database\Eloquent\Builder')) {
            $paginator = $data->paginate($per_page);
        } else if(is_a($data, 'Illuminate\Support\Collection')) {
            $paginator = $this->paginateCollection($request, $data, $per_page, $page);
        }

        if ($paginator) {
            $data = $paginator->items();
            $result->addInnerMeta('pagination', $this->buildMeta($request, $paginator));
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


    private function buildMeta($request, $paginator) {
        return [
            "current_page"  => $paginator->currentPage(),
            "from"          => $paginator->firstItem(),
            "last_page"     => $paginator->lastPage(),
            "next_page_url" => $paginator->nextPageUrl(),
            "path"          => $request->url(),
            "per_page"      => $paginator->perPage(),
            "prev_page_url" => $paginator->previousPageUrl(),
            "to"            => $paginator->lastItem(),
            "total"         => $paginator->total(),
        ];
    }
}
