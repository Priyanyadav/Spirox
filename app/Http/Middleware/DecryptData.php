<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('data')) {
            $encryptedData = $request->input('data');
            $key = env('ENCRYPT_KEY'); // Use the same secure key as in JavaScript
            $iv = env('ENCRYPT_KEY'); // Use the same secure IV as in JavaScript

            $decryptedData = openssl_decrypt(base64_decode($encryptedData), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

            if ($decryptedData) {
                $request->replace(json_decode($decryptedData, true));
            }
        }
        return $next($request);
    }
}
