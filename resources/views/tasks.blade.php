<!DOCTYPE html>
<html>
<head>
    <title>Task Management</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>

</head>
<body>
<div class="container mt-5">
    <h1>Task Management</h1>
    <form id="addTaskForm" class="mt-3">
        @csrf
        <div class="input-group">
            <input type="text" id="taskTitle" class="form-control" placeholder="Task Title">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Add Task</button>
            </div>
        </div>
    </form>
    <!-- Sorting Options -->
    <div class="form-group mt-3">
        <label for="sortOption">Sort by:</label>
        <select id="sortOption" class="form-control">
            <option value="asc">Newest First</option>
            <option value="desc">Oldest First</option>
        </select>
    </div>

    <!-- Search Bar -->
    <div class="form-group mt-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by Title">
    </div>
    <h2>list Tasks</h2>
    <!-- Response Messages -->
    <div id="successMessage" class="alert alert-success mt-3" style="display: none;"></div>
    <div id="dangerMessage" class="alert alert-danger mt-3" style="display: none;"></div>
    <!-- Task List -->
    <ul id="taskList" class="list-group mt-4"></ul>
    {{--edit task--}}
    <div id="editTaskModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="editTaskTitle" class="form-control" placeholder="Edit Task Title">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveTaskChanges">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    {{--    delete task --}}
    <div class="modal fade" id="deleteTaskModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Include Bootstrap JS and jQuery (AJAX) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('js/tasks.js') }}"></script>
</body>
</html>
