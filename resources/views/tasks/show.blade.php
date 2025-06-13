<!DOCTYPE html>
<html>
<head>
    <title>View Task</title>
</head>
<body>
<h1>{{ $task->name }}</h1>
<p><strong>Section:</strong> {{ $task->section }}</p>
<p><strong>Complete:</strong> {{ $task->complete ? 'Yes' : 'No' }}</p>

<a href="{{ route('tasks.edit', $task) }}">Edit Task</a>

<form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Are you sure you want to delete this task?')">Delete Task</button>
</form>

<br><br>
<a href="{{ route('tasks.index') }}">Back to Task List</a>
</body>
</html>
