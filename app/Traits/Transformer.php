<?php

namespace App\Traits;

trait Transformer
{
    /**
     * Pagination function to display number of results on a page
     * Author: Blezour blec
     */

   

    public static function transformCollection($collection)
    {
        $params = http_build_query(request()->except('page'));
        $next = $collection->nextPageUrl();
        $previous = $collection->previousPageUrl();
        if ($params) {
            if ($next) {
                $next .= "&{$params}";
            }
            if ($previous) {
                $previous .= "&{$params}";
            }
        }
        $meta = [
            "next" => (string) $next,
            "previous" => (string) $previous,
            "per_page" => (int) $collection->perPage(),
            "total" => (int) $collection->total(),
            "total_pages" => (int) $collection->lastPage()
        ];
        return $meta;
    }

    

}