$(function () {
    const sortOption = $('#sortOption');
    const searchInput = $('#searchInput');
    const taskList = $('#taskList');
    const taskTitle = $('#taskTitle');
    const addTaskForm = $('#addTaskForm');
    const deleteTaskModal = $('#deleteTaskModal');
    const editTaskModal = $('#editTaskModal');

    // Function to refresh the task list
    function refreshTaskList() {
        $.ajax({
            url: '/api/tasks',
            method: 'GET',
            data: {
                sort: sortOption.val(),
                filter: searchInput.val(),
            },
            success: function (tasks) {
                taskList.empty();
                tasks.forEach(function (task) {
                    const listItem = $(`<li class="list-group-item">${task.title}</li>`);
                    const editButton = $(`<button class="btn btn-primary btn-sm editTask" data-id="${task.id}" data-title="${task.title}">Edit</button>`);
                    const deleteButton = $(`<button class="btn btn-danger btn-sm deleteTask" data-id="${task.id}">Delete</button>`);
                    listItem.append(editButton).append(deleteButton);
                    taskList.append(listItem);
                });
            },
        });
    }

    // Event listener for sorting select
    sortOption.on('change', refreshTaskList);

    // Event listener for search input
    searchInput.on('input', refreshTaskList);

    // Initial load of the task list
    refreshTaskList();

    addTaskForm.submit(function (e) {
        e.preventDefault();
        const title = taskTitle.val();
        $.ajax({
            url: '/api/tasks',
            method: 'POST',
            data: { title: title },
            success: function (response) {
                taskTitle.val('');
                refreshTaskList();
                if (response.message) {
                    showResponseMessage(response.message, 'success');
                } else {
                    showResponseMessage(response.message, 'danger');
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors) showResponseMessage(errors.title[0], 'danger');
            },
        });
    });

    // Delete Task
    $(document).on('click', '.deleteTask', function () {
        const taskId = $(this).data('id');
        deleteTaskModal.data('task-id', taskId).modal('show');
    });

    $('#confirmDelete').on('click', function () {
        const taskId = deleteTaskModal.data('task-id');
        $.ajax({
            url: `/api/tasks/${taskId}`,
            method: 'DELETE',
            success: function () {
                refreshTaskList();
                deleteTaskModal.modal('hide');
            },
            error: function () {
                // Handle error
            },
        });
    });

    $(document).on('click', '.editTask', function () {
        const taskId = $(this).data('id');
        const taskTitle = $(this).data('title');
        $('#editTaskId').val(taskId);
        $('#editTaskTitle').val(taskTitle);
        editTaskModal.modal('show');

        $('#saveTaskChanges').on('click', function () {
            const newTitle = $('#editTaskTitle').val();
            if (newTitle) {
                $.ajax({
                    url: `/api/tasks/${taskId}`,
                    method: 'PUT',
                    data: { title: newTitle },
                    success: function () {
                        editTaskModal.modal('hide');
                        refreshTaskList();
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        showResponseMessage(`Error updating task: ${textStatus}`, 'danger');
                    },
                });
            }
        });
    });
});

function showResponseMessage(message, messageType = 'success', duration = 2000) {
    const successMessage = $('#successMessage');
    const dangerMessage = $('#dangerMessage');

    successMessage.hide();
    dangerMessage.hide();

    if (messageType === 'success') {
        successMessage.html(message).show();
    } else if (messageType === 'danger') {
        dangerMessage.html(message).show();
    }

    setTimeout(() => {
        successMessage.hide();
        dangerMessage.hide();
    }, duration);
}
