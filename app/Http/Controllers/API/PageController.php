<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $pages = Page::latest();

            if ($request->has('search') && !empty($request->search)) {
                $pages = $pages->where('title', 'LIKE', '%'.$request->search.'%');
            }

            $pages = $pages->paginate(10);

            $code = 200;
            $message = 'Success';
            if (empty($pages)) {
                $message = 'Error';
                $code = 404;
            }

            return response()->json([
                'message'   => $message,
                'data'      => $pages,
            ], $code);
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $page = null;
        try {
            $user = Auth::user();
            $validation = Validator::make($request->all(), [
                'title'     => ['required', 'string', 'min:5'],
                'slug'      => ['string', 'min:5', 'unique:pages'],
                'content'   => ['required', 'string', 'min:5'],
                'banner'    => ['file', 'max:2048', 'mimetypes:video/*,image/jpg,image/jpeg,image/png'],
                'status'    => ['required', 'string', 'in:draft,publish'],
            ], [
                'status.in' => "Only allow 'draft' & 'publish'"
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => $page,
                ], 400);
            }

            $mediaId = null;
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filetype = $file->getMimeType();
                $fileName = $file->hashName();
                Storage::disk('public')->putFileAs('photos', $file, $fileName);

                $media = new Media();
                $media->filename = $fileName;
                $media->filetype = $filetype;
                $media->uploaded_by = $user->id;
                $media->save();

                $mediaId = $media->id;
            }

            $slug = $request->slug;
            if (empty($slug)) {
                $slug = Str::of($request->title)->slug('-');
            }
            $counter = Page::where('slug', 'LIKE', $slug.'%')->count();
            if ($counter > 0) {
                $slug = $slug.'-'.$counter;
            }

            $page = new Page();
            $page->title = $request->title;
            $page->slug = $slug;
            $page->content = $request->content;
            $page->banner = $mediaId;
            $page->author = $user->id;
            $page->status = $request->status;
            $page->save();

            return response()->json([
                'message'   => 'success',
                'data'      => $page,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => $page,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $page = Page::findOrFail($id);

            return response()->json([
                'message'   => 'success',
                'data'      => $page,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $page = Page::findOrFail($id);

            $user = Auth::user();
            $validation = Validator::make($request->all(), [
                'title'     => ['required', 'string', 'min:5'],
                'slug'      => ['string', 'min:5', 'unique:pages'],
                'content'   => ['required', 'string', 'min:5'],
                'banner'    => ['file', 'max:2048', 'mimetypes:video/*,image/jpg,image/jpeg,image/png'],
                'status'    => ['required', 'string', 'in:draft,publish'],
            ], [
                'status.in' => "Only allow 'draft' & 'publish'"
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => $page,
                ], 400);
            }

            $mediaId = null;
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filetype = $file->getMimeType();
                $fileName = $file->hashName();
                Storage::disk('public')->putFileAs('photos', $file, $fileName);

                $media = new Media();
                $media->filename = $fileName;
                $media->filetype = $filetype;
                $media->uploaded_by = $user->id;
                $media->save();

                $mediaId = $media->id;
                $page->banner = $mediaId;
            }

            $slug = $request->slug;
            if ($request->slug != $page->slug) {
                if (empty($slug)) {
                    $slug = Str::of($request->title)->slug('-');
                }
                $counter = Page::where('slug', 'LIKE', $slug.'%')->count();
                if ($counter > 0) {
                    $slug = $slug.'-'.$counter;
                }
            }

            $page->title = $request->title;
            $page->slug = $slug;
            $page->content = $request->content;
            $page->last_updated_by = $user->id;
            $page->status = $request->status;
            $page->save();

            return response()->json([
                'message'   => 'success',
                'data'      => $page,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $page = Page::findOrFail($id);
            $page->delete();

            return response()->json([
                'message'   => 'success',
                'data'      => 'deleted',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource for public.
     */
    public function publicShow(string $slug)
    {
        try {
            $page = Page::where('slug', $slug)->where('status', 'publish')->first();

            if (!$page) {
                return response()->json([
                    'message'   => 'not_found',
                    'data'      => $page,
                ], 404);
            }

            return response()->json([
                'message'   => 'success',
                'data'      => $page->makeHidden(['status', 'deleted_at', 'banner', 'id', 'user', 'last_updated_by', 'author']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }
}
