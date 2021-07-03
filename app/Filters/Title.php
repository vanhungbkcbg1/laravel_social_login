<?php


namespace App\Filters;


class Title
{
    public function handle($request, \Closure $next){
        if( request()->has("title")){
            $builder = $next($request);
            return $builder->where("title",request()->get('title'));
        }
        return $next($request);
    }
}
