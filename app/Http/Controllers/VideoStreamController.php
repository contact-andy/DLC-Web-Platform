<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamController extends Controller
{
     /**
     * Stream video in chunks to handle byte-range requests.
     *
     * @param Request $request
     * @param string $filename
     * @return StreamedResponse
     */
    public function stream(Request $request, $filename)
    {
        // return "hello";
        // Path to your video storage
        $filePath = public_path('videos/' . $filename);

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'Video not found');
        }
        
        $size = filesize($filePath);
        $start = 0;
        $end = $size - 1;

        // Check if 'Range' header exists (for byte-range requests)
        if ($request->headers->has('Range')) {
            $range = $request->header('Range');
            list(, $range) = explode('=', $range, 2);
            list($start, $end) = explode('-', $range);
            $end = ($end == '') ? $size - 1 : (int)$end;
            $start = (int)$start;

            if ($start > $end || $end >= $size) {
                abort(416, 'Requested Range Not Satisfiable');
            }
        }

        // Open the video file for streaming
        $file = fopen($filePath, 'rb');
        fseek($file, $start);

        // Define response headers for partial content
        $headers = [
            'Content-Type' => mime_content_type($filePath),
            'Content-Length' => ($end - $start) + 1,
            'Accept-Ranges' => 'bytes',
            'Content-Range' => "bytes $start-$end/$size"
        ];

        // Return streamed response
        return response()->stream(function () use ($file, $start, $end) {
            $chunkSize = 1024 * 8; // 8KB chunks
            $bytesLeft = ($end - $start) + 1;

            while ($bytesLeft > 0 && !feof($file)) {
                $bytesToRead = min($chunkSize, $bytesLeft);
                echo fread($file, $bytesToRead);
                $bytesLeft -= $bytesToRead;
                flush();
            }

            fclose($file);
        }, 206, $headers); // 206 = Partial Content response
    }
    public function streamOnce(Request $request, $filename)
    {
        // Path to your video storage
        $filePath = public_path('videos/' . $filename);
    
        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'Video not found');
        }
        
        // Get the file size
        $size = filesize($filePath);
    
        // Open the video file for streaming
        $file = fopen($filePath, 'rb');
    
        // Define response headers for the full content
        $headers = [
            'Content-Type' => mime_content_type($filePath),
            'Content-Length' => $size,
            'Accept-Ranges' => 'bytes',
        ];
    
        // Return streamed response (stream the whole file at once)
        return response()->stream(function () use ($file) {
            fpassthru($file); // Send the entire file
            fclose($file); // Close file after streaming
        }, 200, $headers); // 200 = OK response
    }
}


