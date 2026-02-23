<?php

use App\Models\Admin;
use App\Models\JobPosition;
use App\Models\Site;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function catchReponse($e)
{
    return response()->json([
        'error' => true,
        'errors' => $e->getMessage(),
        'success' => false,
        'msg' => "Something went wrong",
    ]);
}



function catchAPIReponse($e)
{
    return Response::json(
        array(
            'success' => false,
            'data' => [],
            'message' => "Ghare ni error mali",
            'errors' => [
                "error" => json_encode($e),
            ],
            'meta' => []
        )
    );
}

function setDateFormat($data)
{
    return date('M d, Y', strtotime($data));
}

function fileupload($value, $rand = NULL, $dir = NULL)
{
    $imageName = $value->getClientOriginalName();

    $fileNameWithoutExtension = pathinfo($imageName, PATHINFO_FILENAME);
    $slug = Str::slug($fileNameWithoutExtension, '-');
    if (($rand != NULL) && $rand == "Yes") {
        $slug .= date('YmdHis') . '-' . env('APP_ENV');
    }

    $fileExtension = $value->getClientOriginalExtension();
    $imageName = $slug . '.' . $fileExtension;

    if (($dir != NULL)) {
        $directory = $dir . "/" . date('Y') . "/" . ((strlen(date('m') == "1")) ? "0" . date('m') : date('m'));
    } else {
        $directory = "uploads/" . date('Y') . "/" . ((strlen(date('m') == "1")) ? "0" . date('m') : date('m'));
    }

    if (!Storage::disk('public_upload')->exists($directory)) {
        // Create the directory with 755 permissions
        Storage::disk('public_upload')->makeDirectory($directory, 0755, true);
    }

    // Check if a file with the same name already exists in the directory
    if (Storage::disk('public_upload')->exists($directory . '/' . $imageName)) {
        $extension = $value->getClientOriginalExtension();
        $basename = pathinfo($imageName, PATHINFO_FILENAME);
        $counter = 1;

        // Append a dynamic number to the filename until it's unique
        while (Storage::disk('public_upload')->exists($directory . '/' . $imageName)) {
            $imageName = $basename . '_' . $counter . '.' . $extension;
            $counter++;
        }
    }

    Storage::disk('public_upload')->putFileAs($directory, $value, $imageName, 'public');
    $path = $directory . '/' . $imageName;

    return $path;
}

function FeaturedImageSize($file, $path, $width, $height, $uniqid)
{
    if (!isset($uniqid)) {
        $uniqid = uniqid();
    } else {
        $uniqid = $uniqid;
    }
    $imagePath = $path . $uniqid . '.' . $file->getClientOriginalExtension();
    $image = Image::make($file);

    // Set the target dimensions (370x414)
    $targetWidth = $width;
    $targetHeight = $height;

    // Calculate the scaling factors for width and height to fit within the canvas
    $widthScale = $targetWidth / $image->width();
    $heightScale = $targetHeight / $image->height();

    // Use the smaller scaling factor to ensure the entire image fits within the canvas
    $scale = min($widthScale, $heightScale);

    // Calculate the new dimensions based on the scale
    $newWidth = round($image->width() * $scale);
    $newHeight = round($image->height() * $scale);

    // Create a new canvas with the specified dimensions and a white background
    $canvas = Image::canvas($targetWidth, $targetHeight, '#ffffff');

    // Calculate the position to center the adjusted image on the canvas
    $x = round(($targetWidth - $newWidth) / 2);
    $y = round(($targetHeight - $newHeight) / 2);

    // Paste the adjusted image onto the canvas at the calculated position
    $canvas->insert($image->resize($newWidth, $newHeight), 'top-left', $x, $y);
    // dd($canvas, $image, $imagePath);

    // Create the directory if it doesn't exist
    $directory = public_path($path);
    if (!file_exists($directory)) {
        mkdir($directory, 0755, true);
    }
    // Save the canvas image with the correct path
    $canvas->save(public_path($imagePath));
    return [
        'uniqid' => $uniqid,
        'path' => $path,
        'height' => $height,
        'width' => $width,
        'mime' => mime_content_type(public_path($imagePath)),
    ];
}

function truncateString($str, $maxLength = 120)
{
    if (strlen($str) <= $maxLength) {
        return $str;
    } else {
        return substr($str, 0, $maxLength - 3) . '...';
    }
}

function generateCaptcha()
{
    $first = rand(0, 9);
    $second = rand(0, 9);
    $captcha = [
        $first,
        $second,
        ($first + $second)
    ];
    return view('admin.layout.sections.captcha', compact('captcha'));
}

function celebrationfileupload($value)
{
    $imageName = $value->getClientOriginalName();
    Storage::disk('public_upload')->putFileAs('uploads/celebration/original', $value, $imageName, 'public');
    $path = "uploads/celebration/original/" . $imageName;
    return $path;
}
function generateUniqueSlug($model, $title, $id = NULL, $separator = '-')
{
    $slug = Str::slug($title, $separator);

    $originalSlug = $slug;
    $count = 1;

    if ($id != NULL) {
        while (Admin::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . $separator . $count;
            $count++;
        }
    } else {
        while (Admin::where('slug', $slug)->exists()) {
            $slug = $originalSlug . $separator . $count;
            $count++;
        }
    }

    return $slug;
}

function generatePrefix()
{
    $suffix = rand(111, 999);
    $existingUser = Site::where(['id' => $suffix])->exists();
    if ($existingUser) {
        return generatePrefix();
    }
    return "PRE" . $suffix;
}
