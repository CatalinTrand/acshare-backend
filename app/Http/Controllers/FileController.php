<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $myFiles = File::where('owner_id', $request->id)->get();
        $sharedWithMe = File::where('shared_with', 'LIKE', '%' . $request->email . '%')->get();

        $fileData = new \stdClass();
        $fileData->myFiles = $myFiles;
        $fileData->sharedWithMe = $sharedWithMe;

        return response()->json($fileData, 200);
    }

    public function arrContains($file, $list){
        foreach ($list as $item)
            if ($item->id == $file->id)
                return true;

        return false;
    }

    public function download(Request $request){
        $file = File::find($request->file_id);

        if ($file == null)
            return response()->json(['message', 'No such file with id.'], 500);

        $myFiles = File::where('owner_id', $request->id)->get();

        if (!FileController::arrContains($file, $myFiles)) {
            $sharedWithMe = File::where('shared_with', 'LIKE', '%' . $request->email . '%')->get();
            if (!FileController::arrContains($file, $sharedWithMe))
                return response()->json(['message', 'No permission to view this file.'], 500);
        }

        $fileData = Storage::get($file->path);

        if ($fileData == null)
            return response()->json(['message', 'Error downloading file.'], 500);

        return $fileData;
    }

    public function store(Request $request)
    {
        $file = new File();
        $file->name = $request->name;
        $file->owner_id = $request->id;
        $file->shared_with = $request->shared_with;
        $file->path = "";

        if ($file->save()) {
            $arr = explode('.', $request->file("fileData")->getClientOriginalName());
            $extension = $arr[count($arr) - 1];
            $file->path = '/uploads/' . $file->name . "." . $extension;
            Storage::put($file->path, $request->file("fileData"));
            if ($file->save())
                return response()->json(['message' => 'File created successfully'], 201);
            else {
                File::destroy($file->id);
                return response()->json(['message' => 'There was an error storing the data.'], 500);
            }
        } else
            return response()->json(['message' => 'There was an error saving the file.'], 500);
    }

    public function delete(Request $request)
    {
        $id = $request->file_id;
        if (File::destroy($id))
            return response()->json(['message' => 'File deleted successfully.'], 200);
        else
            return response()->json(['message' => 'There was an error deleting the file.'], 500);
    }

    public function update(Request $request)
    {
        $file = File::find($request->file_id);

        $file->name = $request->name;
        $file->shared_with = $request->shared_with;

        if ($file->save())
            return response()->json(['message' => 'File updated successfully.'], 200);
        else
            return response()->json(['message' => 'There was an error updating the file.'], 500);
    }
}
