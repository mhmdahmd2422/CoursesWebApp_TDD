<div>
    <h3>{{ $video->title }} ({{ $video->getReadableDuration() }})</h3>
    <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <p>{{ $video->description }}</p>

    <ul>
        @foreach($courseVideos as $courseVideo)
            <li>
                @if($this->isCurrentVideo($courseVideo))
                    {{ $courseVideo->title }}
                @else
                    <a href="{{ route('page.course-videos', $courseVideo) }}">
                        {{ $courseVideo->title }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</div>
