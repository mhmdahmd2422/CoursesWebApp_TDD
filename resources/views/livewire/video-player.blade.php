<div>
    <h3>{{ $video->title }} ({{ $video->getReadableDuration() }})</h3>
    <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <p>{{ $video->description }}</p>

    <ul>
        @foreach($courseVideos as $courseVideo)
            <a href="{{ route('page.course-videos', $courseVideo) }}"></a>
            <li>{{ $courseVideo->title }}</li>
        @endforeach
    </ul>
</div>
