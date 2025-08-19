<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Visitor;


class VisitorPhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:receptionist');
    }

    public function uploadPhoto(Request $request, Visitor $visitor)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Delete old photo if exists
            if ($visitor->hasPhoto()) {
                $visitor->deletePhoto();
            }

            $file = $request->file('photo');

            // Generate unique filename
            $filename = 'visitor_' . $visitor->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store in visitor_photos directory
            $path = $file->storeAs('visitor_photos', $filename, 'public');

            // Update visitor record
            $visitor->update(['photo' => $path]);

            return response()->json([
                'success' => true,
                'photo_url' => $visitor->photo_url,
                'message' => 'Photo uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to upload photo: ' . $e->getMessage()
            ], 500);
        }
    }

    // Save photo from base64 data (for camera captures)
    public function savePhotoFromBase64(Request $request, Visitor $visitor)
    {
        $validator = Validator::make($request->all(), [
            'photo_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            $photoData = $request->input('photo_data');

            // Remove data URL prefix if present
            if (strpos($photoData, 'data:image/') === 0) {
                $photoData = substr($photoData, strpos($photoData, ',') + 1);
            }

            // Decode base64
            $imageData = base64_decode($photoData);

            if ($imageData === false) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid image data'
                ], 422);
            }

            // Delete old photo if exists
            if ($visitor->hasPhoto()) {
                $visitor->deletePhoto();
            }

            // Generate unique filename
            $filename = 'visitor_' . $visitor->id . '_' . time() . '.png';
            $path = 'visitor_photos/' . $filename;

            // Store the image
            Storage::disk('public')->put($path, $imageData);

            // Update visitor record
            $visitor->update(['photo' => $path]);

            return response()->json([
                'success' => true,
                'photo_url' => $visitor->photo_url,
                'message' => 'Photo saved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to save photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function savePhotoFromUrl(Request $request, Visitor $visitor)
    {
        $validator = Validator::make($request->all(), [
            'photo_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            $photoUrl = $request->input('photo_url');

            // Store URL directly
            if ($visitor->hasPhoto() && !filter_var($visitor->photo, FILTER_VALIDATE_URL)) {
                $visitor->deletePhoto(); // Only delete if it's a local file
            }

            $visitor->update(['photo' => $photoUrl]);

            return response()->json([
                'success' => true,
                'photo_url' => $visitor->photo_url,
                'message' => 'Photo URL saved successfully'
            ]);

            // Download and store locally
            /*
            $imageContent = file_get_contents($photoUrl);

            if ($imageContent === false) {
                return response()->json([
                    'success' => false,
                    'error' => 'Could not download image from URL'
                ], 422);
            }

            // Delete old photo if exists
            if ($visitor->hasPhoto()) {
                $visitor->deletePhoto();
            }

            // Get file extension from URL or default to jpg
            $extension = pathinfo(parse_url($photoUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'visitor_' . $visitor->id . '_' . time() . '.' . $extension;
            $path = 'visitor_photos/' . $filename;

            // Store the image
            Storage::disk('public')->put($path, $imageContent);

            // Update visitor record
            $visitor->update(['photo' => $path]);

            return response()->json([
                'success' => true,
                'photo_url' => $visitor->photo_url,
                'message' => 'Photo downloaded and saved successfully'
            ]);
            */

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to save photo from URL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePhoto(Visitor $visitor)
    {
        try {
            $visitor->deletePhoto();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPhoto(Visitor $visitor)
    {
        return response()->json([
            'success' => true,
            'has_photo' => $visitor->hasPhoto(),
            'photo_url' => $visitor->photo_url
        ]);
    }
}
