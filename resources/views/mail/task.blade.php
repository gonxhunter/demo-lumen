<h1>Overdue date tasks</h1>
<table border="1">
    <tr>
        <td>ID</td>
        <td>Title</td>
        <td>Description</td>
        <td>Assignees</td>
        <td>Parent task</td>
        <td>Due date</td>
        <td>Created at</td>
        <td>Updated at</td>
    </tr>
    @foreach($tasks as $task)
        <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->title }}</td>
            <td>{{ $task->description }}</td>
            <td>
                @foreach($task->user as $user)
                    {{$user->name}}
                @endforeach
            </td>
            <td>{{ $task->getTaskById($task->parent_id) }}</td>
            <td>{{ $task->due_date ? date('Y-m-d', strtotime($task->due_date)) : "" }}</td>
            <td>{{ $task->created_at }}</td>
            <td>{{ $task->updated_at }}</td>
        </tr>
    @endforeach
</table>
