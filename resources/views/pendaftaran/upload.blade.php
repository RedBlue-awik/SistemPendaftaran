@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">
    <h2 class="text-xl font-bold">Upload Dokumen</h2>
    <form method="POST" action="{{ route('pendaftaran.upload.post', $pendaftaran) }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 gap-2">
            <label>KK</label>
            <input type="file" name="kk" class="filepond" />
            <label>Akta</label>
            <input type="file" name="akta" class="filepond" />
            <label>Ijazah</label>
            <input type="file" name="ijazah" class="filepond" />
            <label>Foto</label>
            <input type="file" name="foto" class="filepond" />
            <label>Sertifikat (opsional)</label>
            <input type="file" name="sertifikat" class="filepond" />
            <label>KTP Orangtua (opsional)</label>
            <input type="file" name="ktp_orangtua" class="filepond" />
        </div>
        <div class="mt-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
        </div>
    </form>
</div>
@endsection

<!-- FilePond initialization (CDN) -->
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        if(window.FilePond) FilePond.parse(document.body);
    });
</script>
