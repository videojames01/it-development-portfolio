<!DOCTYPE html>
<html>
<head>
    <title>Create</title>
</head>

<body>
    <h1>Create a new task</h1>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <label for="section">Section</label>
        <select name="section" id="section">
            <option value="">-- Choose Section --</option>
            <option value="Dev Ops" {{ old('section') == 'Dev Ops' ? 'selected' : '' }}>Dev Ops</option>
            <option value="Usability" {{ old('section') == 'Usability' ? 'selected' : '' }}>Usability</option>
            <option value="Innovation" {{ old('section') == 'Innovation' ? 'selected' : '' }}>Innovation</option>
        </select>

        <label for="name">Task Name</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}">

        <label for="completed">Complete</label>
        <input type="checkbox" name="complete" id="complete" value="1" {{ old('complete') ? 'checked' : '' }}>
        <button type="submit">Save</button>
    </form>
</body>
</html>
