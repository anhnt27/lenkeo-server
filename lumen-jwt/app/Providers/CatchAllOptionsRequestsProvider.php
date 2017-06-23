<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
/**
 * If the incoming request is an OPTIONS request
 * we will register a handler for the requested route
 */
class CatchAllOptionsRequestsProvider extends ServiceProvider {
  public function register()
  {
    $request = app('request');
    // dd($request);
    // dd($request->isMethod('OPTIONS'));
    if ($request->isMethod('OPTIONS'))
    {
      // app()->options($request->path(), function() { return response('', 200); });
    }
  }
}