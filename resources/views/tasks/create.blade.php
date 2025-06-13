<!DOCTYPE html>
<html>
<head>
    <title>Create</title>
    <link rel="stylesheet" href="{{ asset('css/create-form.css') }}">
</head>

<body>
<section class="create-form">
    <h1 class="form-title">Create a new task</h1>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <fieldset>
            <label for="section">Section*</label>
            <select name="section" id="section" class="form-select {{ $errors->has('section') ? 'input-error' : '' }}">
                <option value="">-- Choose Section --</option>
                <option value="Dev Ops" {{ old('section') == 'Dev Ops' ? 'selected' : '' }}>Dev Ops</option>
                <option value="Usability" {{ old('section') == 'Usability' ? 'selected' : '' }}>Usability</option>
                <option value="Innovation" {{ old('section') == 'Innovation' ? 'selected' : '' }}>Innovation</option>
            </select>
            @error('section')
            <div style="color: red;">{{ $message }}</div>
            @enderror
        </fieldset>

        <fieldset>
            <label for="name">Task Name*</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="E.g. Assignment 1..." minlength="3" maxlength="255" class="form-control {{ $errors->has('name') ? 'input-error' : '' }}">
            @error('name')
            <div style="color: red;">{{ $message }}</div>
            @enderror
        </fieldset>

        <fieldset>
            <label for="completed">Complete</label>
            <input type="checkbox" name="complete" id="complete" value="1" {{ old('complete') ? 'checked' : '' }}>
        </fieldset>

        <p class="required-note">* = Required</p>

        <section class="button-container">
            <button type="submit" class="btn-save" id="saveBtn">Save</button>
            <a class="btn-cancel" href="{{ route('tasks.index') }}" style="text-decoration: none">Cancel</a>
        </section>
    </form>
</section>
</body>
</html>
