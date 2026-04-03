<select name="{{ class_basename($modelClass) }}_id" class="form-control" required>
    <option value="">Select a {{ class_basename($modelClass) }}</option>

    @foreach($options as $value => $label)
    <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>