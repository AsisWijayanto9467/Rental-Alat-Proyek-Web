<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class CrossStorageController extends Controller
{
    /**
     * Menyajikan file gambar dari shared storage (D:\Cross_Storage\Sistem_Proyek).
     *
     * File hanya boleh bertipe gambar dan berada di dalam root disk 'cross'.
     * Path traversal dicegah oleh Flysystem (PathTraversalDetected).
     */
    public function show(string $path)
    {
        $path = trim(str_replace('\\', '/', $path), '/');

        abort_if(
            $path === '' || ! in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true),
            404
        );

        try {
            $disk = Storage::disk('cross');

            abort_unless($disk->exists($path), 404);

            return $disk->response($path);
        } catch (Throwable) {
            abort(404);
        }
    }
}
