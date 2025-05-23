<!DOCTYPE html>
<html>
    <head>
        <title>Tasks</title>
    </head>

    <body>
        <h1>Tasks</h1>
        <a href="{{ route('tasks.create') }}">Create a new task</a>
        <h2>DevOps</h2>

        <ul>
            @foreach($tasks as $task)
                @if($task->section == 'Dev Ops')
                    <li>{{ $task->name }}</li>
                @endif
            @endforeach
        </ul>
    </body>
</html>
