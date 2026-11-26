<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\FileRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends BaseController
{
    public function index(Request $request)
    {
        $files = File::with('uploader:id,name,avatar')
            ->when($request->fileable_type, fn ($q) => $q->where('fileable_type', $request->fileable_type))
            ->when($request->fileable_id, fn ($q) => $q->where('fileable_id', $request->fileable_id))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return $this->success($files);
    }

    public function store(FileRequest $request)
    {
        $uploaded = $request->file('file');
        $path = $uploaded->store('uploads', 'public');

        $file = File::create([
            'file_path'     => $path,
            'file_name'     => $uploaded->getClientOriginalName(),
            'user_id'       => $request->user()->id,
            'fileable_id'   => $request->fileable_id,
            'fileable_type' => $request->fileable_type,
        ]);

        return $this->success($file->load('uploader'), 'File uploaded', 201);
    }

    public function show(File $file)
    {
        return $this->success($file->load('uploader'));
    }

    public function update(FileRequest $request, File $file)
    {
        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($file->file_path);
            $uploaded = $request->file('file');
            $file->file_path = $uploaded->store('uploads', 'public');
            $file->file_name = $uploaded->getClientOriginalName();
        }

        $file->save();

        return $this->success($file, 'File updated');
    }

    public function destroy(File $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return $this->success(null, 'File deleted');
    }
}