<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'fileType'=> ['string', 'string', 'in:image,video'],
            ], [
                'fileType.in' => "Only allow 'image' & 'video'"
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => null,
                ], 400);
            }

            $medias = Media::latest();

            if (!empty($this->fileType)) {
                $medias = $medias->where('filetype', 'LIKE', $request->fileType.'%');
            }

            $medias = $medias->paginate(10);

            $code = 200;
            $message = 'Success';
            if (empty($medias)) {
                $message = 'Error';
                $code = 404;
            }

            return response()->json([
                'message'   => $message,
                'data'      => $medias,
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
        try {
            $user = Auth::user();
            $validation = Validator::make($request->all(), [
                'file' => ['required', 'file', 'max:2048', 'mimetypes:video/*,image/jpg,image/jpeg,image/png'],
            ], [
                'file.mimetypes' => "Only allow 'image' & 'video'"
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => null,
                ], 400);
            }

            $file = $request->file('file');
            $filetype = $file->getMimeType();
            $fileName = $file->hashName();
            Storage::disk('public')->putFileAs('photos', $file, $fileName);

            $media = new Media();
            $media->filename = $fileName;
            $media->filetype = $filetype;
            $media->uploaded_by = $user->id;
            $media->save();

            return response()->json([
                'message'   => 'success',
                'data'      => $media,
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
            $checkOnPage = Page::where('banner', $id)->exists();

            if (!$checkOnPage) {
                $media = Media::findOrFail($id);
                Storage::delete('photos/'.$media->filename);
                $media->delete();

                return response()->json([
                    'message'   => 'success',
                    'data'      => 'deleted',
                ], 201);
            } else {
                return response()->json([
                    'message'   => 'success',
                    'data'      => 'Media is not deleted, this media is attached to a page!',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }
}
