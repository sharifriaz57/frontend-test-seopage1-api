<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpg,png,,jpeg,gif,pdf|max:3072',
        ]);

        try {
            if ($request->hasFile('files')) {
                $files = $request->file('files');

                foreach ($files as $file) {
                    $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('uploads', $fileName, 'public');
                    FileUpload::create([
                        'filename' => $fileName,
                        'type' => $file->getClientOriginalExtension()
                    ]);
                }
            }

            return response()->json(['message' => 'Files uploaded successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error while uploading files!', 'error' => $th->getMessage()]);
        }
    }
}
