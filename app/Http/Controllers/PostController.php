<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {

        $activeClients = request('status') === 'active';
        $inactiveClients = request('status') === 'inactive';
        $sortOrder = request('sort') === 'desc' ? 'desc' : 'asc';
        $dateFrom = request('dateFrom');
        $dateTo   = request('dateTo');
        $dateRange = [$dateFrom, $dateTo];

        $data = Post::withTrashed()->when($activeClients, function ($query) {
            return $query->where('deleted_at', "=", null);
        })->when($inactiveClients, function ($query) {
            return $query->where('deleted_at', "!=", null);
        })->when($dateFrom and $dateTo, function ($query) use ($dateRange) {
            return $query->whereBetween('created_at', $dateRange);
        })->orderBy('created_at', $sortOrder);

        return response()->json([
            'message' => 'Displaying client list',
            'data' => $data->get()
        ]);


        // $query = Post::query();

        // $query->when(request('status') === 'Active', function($cute){
        //     return $cute->where
        // })


        // $data = Post::withTrashed();

        // $filtered = $data->where('deleted_at', "!=", null);

        // return response()->json([
        //     'message' => 'active data',
        //     'data' => $filtered->all()
        // ]);

        // $data = Post::withTrashed()->when(request('status') === 'Active', function ($query) {
        //     return $query->where('deleted_at', "=", null);
        // })->when(request('status') === 'Inactive', function ($query) {
        //     return $query->where('deleted_at', "!=", null);
        // }, function ($query) {
        //     return $query->orderBy('created_at', request('sort') === 'desc' ? 'desc' : 'asc');
        // });


        // if ($activeClients) {
        //     $posts = Post::orderBy('created_at', $sortOrder)->get();
        //     return response()->json([
        //         'message' => 'active data',
        //         'data' => $posts,
        //     ]);
        // } elseif ($inactiveClients) {
        //     $posts = Post::onlyTrashed()->orderBy('created_at', $sortOrder)->get();
        //     return response()->json([
        //         'message' => 'inactive data',
        //         'data' => $posts,
        //     ]);
        // } else {
        //     // $posts = Post::withTrashed()->orderBy('created_at', $sortOrder)->whereBetween('created_at', $dateRange)->get();
        //     $posts = Post::withTrashed()->orderBy('created_at', $sortOrder)->get();
        //     return response()->json([
        //         'message' => 'all data',
        //         'data' => $posts,
        //     ]);
        // }






    }

    public function delete($id)
    {
        Post::find($id)->delete();

        return back()->with('success', 'Post Deleted successfully');
    }

    public function restore($id)
    {
        Post::withTrashed()->find($id)->restore();

        return back()->with('success', 'Post Restore successfully');
    }

    public function restore_all()
    {
        Post::onlyTrashed()->restore();

        return back()->with('success', 'All Post Restored successfully');
    }
}
