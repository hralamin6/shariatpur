<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post List PDF</title>
    <style>
        /* Inline CSS for PDF Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
        }
        .status {
            padding: 5px 10px;
            border-radius: 3px;
            color: #fff;
        }
        .status.published {
            background-color: #28a745;
        }
        .status.draft {
            background-color: #dc3545;
        }
        .tags span {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
        .excerpt {
            color: #666;
            font-size: 12px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Post List</h1>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Tags</th>
            <th>Excerpt</th>
            <th>Author</th>
            <th>Published At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $index => $post)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="title">{{ $post->title }}</td>
                <td>{{ $post->slug }}</td>
                <td>
                            <span class="status {{ $post->status == 'published' ? 'published' : 'draft' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                </td>
                <td class="tags">
                    @if($post->tags)
                        @foreach(json_decode($post->tags) as $tag)
                            <span>{{ $tag }}</span>
                        @endforeach
                    @else
                        No Tags
                    @endif
                </td>
                <td class="excerpt">{{ \Illuminate\Support\Str::limit($post->content, 50) }}</td>
                <td>{{ $post->user->name }}</td>
                <td>{{ $post->published_at ? $post->published_at->format('d M Y') : 'Not Published' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
