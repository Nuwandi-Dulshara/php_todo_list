document.addEventListener("DOMContentLoaded", function() {
    const taskInput = document.querySelector('input[name="task"]');
    const addTaskButton = document.querySelector('button[type="submit"]');
    const tasksList = document.querySelector('ul');
    
    // Validate task input
    addTaskButton.addEventListener('click', function(event) {
        if (taskInput.value.trim() === "") {
            event.preventDefault();
            alert("Task cannot be empty");
        }
    });

    // Handle edit task functionality
    tasksList.addEventListener('click', function(event) {
        if (event.target.classList.contains('edit')) {
            const taskItem = event.target.closest('li');
            const taskText = taskItem.querySelector('.task-text');
            const taskInputField = document.createElement('input');
            taskInputField.type = 'text';
            taskInputField.value = taskText.textContent;
            taskItem.insertBefore(taskInputField, taskText);
            taskItem.removeChild(taskText);
            event.target.textContent = 'Save';
            event.target.classList.remove('edit');
            event.target.classList.add('save');
        } else if (event.target.classList.contains('save')) {
            const taskItem = event.target.closest('li');
            const taskInputField = taskItem.querySelector('input[type="text"]');
            const newTaskText = taskInputField.value;
            const taskText = document.createElement('span');
            taskText.classList.add('task-text');
            taskText.textContent = newTaskText;
            taskItem.insertBefore(taskText, taskInputField);
            taskItem.removeChild(taskInputField);
            event.target.textContent = 'Edit';
            event.target.classList.remove('save');
            event.target.classList.add('edit');

            // Send the updated task to the server
            const taskId = taskItem.dataset.taskId;
            fetch('tasks.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `update=true&task_id=${taskId}&task=${newTaskText}`
            }).then(response => response.text())
            .then(data => {
                console.log(data);
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    });
});
