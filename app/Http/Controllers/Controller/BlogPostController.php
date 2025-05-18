<?php

namespace App\Http\Controllers\Controller;

use Inertia\Inertia;
use App\Models\BlogPost;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogPostsRequest;
use App\Models\Author;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $authors;
    public function __construct(){
         $this->authors = Author::select('id', 'name')->orderBy('name')->get();
    }

    public function index()
    {
        $search = request('search');
        $status = request('status');

        $query = BlogPost::with('author:id,name')->select('id','title','status');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status && in_array($status, ['Published', 'Unpublished'])) {
            $query->where('status', $status);
        }

        $blog_posts = $query->paginate(1)->withQueryString();

        return inertia('blog_posts/Index', [
            'blog_posts' => $blog_posts,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('blog_posts/Create',[
            'authors' =>  $this->authors,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPostsRequest $request)
    {
        BlogPost::create($request->validated());
        return redirect()->route('blog-posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost  $blog_post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost  $blog_post)
    {

        return Inertia::render('blog_posts/Edit', [
            'blog_post' => $blog_post,
            'authors' =>  $this->authors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPostsRequest $request, BlogPost  $blog_post)
    {
        $blog_post->update($request->validated());
        return redirect()->route('blog-posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( BlogPost  $blog_post)
    {
        $blog_post->delete();
    }
}
