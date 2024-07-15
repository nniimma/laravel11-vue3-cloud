<h2>Hello <u>{{ $user->name }}</u></h2>

<h4>User <u>{{ $author->name }}</u> share the following files to you.</h4>
<hr>
@foreach ($files as $file)
    <p>{{ $file->is_folder ? 'Folder: ' : 'File: ' }}{{ $file->name }}</p>
@endforeach
