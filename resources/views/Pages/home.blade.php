@foreach($courses as $course)
    <h1>{{$course->title}}</h1>
    <p>{{$course->description}}</p>
@endforeach
