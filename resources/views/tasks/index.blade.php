<!DOCTYPE html>
<html>
    <head>
        <title>Tasks</title>
    </head>

    <body>
        <h1>IT Development Portfolio Tasks</h1>

        <a href="{{ route('tasks.create') }}">Create a new task</a>

        {{-- Dev Ops --}}
        <h2>DevOps</h2>
        <ul>
            @foreach($tasks as $task)
                @if($task->section == 'Dev Ops')
                    <li>{{ $task->name }}</li>
                    <li>{{ $task->complete }}</li>
                @endif
            @endforeach
        </ul>

        {{-- Usability --}}
        <h2>Usability</h2>
        <ul>
            @foreach($tasks as $task)
                @if($task->section == 'Usability')
                    <li>{{ $task->name }}</li>
                    <li>{{ $task->complete }}</li>
                @endif
            @endforeach
        </ul>

        {{-- Innovation --}}
        <h2>Innovation</h2>
        <ul>
            @foreach($tasks as $task)
                @if($task->section == 'Innovation')
                    <li>{{ $task->name }}</li>
                    <li>{{ $task->complete }}</li>
                @endif
            @endforeach
        </ul>
    </body>
</html>
