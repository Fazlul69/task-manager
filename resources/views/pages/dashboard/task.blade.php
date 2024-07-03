<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <!-- Add Task button -->

                <div x-data="{ open: false, mode: 'add', tasks: { title: '', description: '', completed: false }, taskLists: [] }">
                    <button x-on:click="open = ! open" @click="toggleDialog('add')"
                        class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                        <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="hidden xs:block ml-2">Add Task</span>
                    </button>
                    {{-- <button x-on:click="open = ! open" @click="toggleDialog('add')">Add</button> --}}
                    {{-- <button x-on:click="open = ! open" @click="toggleDialog('update')">Update</button> --}}

                    <div x-show="open" class="absolute mx-auto">
                        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                            <h2 class="text-xl font-bold mb-4" x-text="mode === 'add' ? 'Add' : 'Edit'"> </h2>
                            <form @submit.prevent="submitForm">
                                <div class="mb-4">
                                    <label class="block text-gray-700">Title</label>
                                    <input type="text" class="w-full border-gray-300 rounded" x-model='tasks.title'
                                        required>

                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Description</label>
                                    <textarea class="w-full border-gray-300 rounded" x-model='tasks.description' required></textarea>

                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Completed</label>
                                    <input type="checkbox" x-model='tasks.completed'>
                                    {{-- <span x-text='tasks.completed'></span> --}}
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" @click="cancelForm"
                                        class="btn bg-gray-500 hover:bg-gray-600 text-white mx-2">Cancel</button>
                                    <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                                        <span x-text="mode === 'add' ? 'Save' : 'Update'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tasks Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead class="text-xs font-semibold uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="p-2">
                            <div class="font-semibold text-left">Title</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-left">Description</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-left">Completed</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Actions</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @foreach ($tasks as $task)
                        <tr>
                            <td class="p-2">
                                <div class="text-left">{{ $task->title }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-left">{{ $task->description }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-left">{{ $task->completed ? 'Yes' : 'No' }}</div>
                            </td>
                            <td class="p-2">
                                <div class="flex justify-center">
                                    <button @click="confirmEdit({{ $task->id }})"
                                        class="btn bg-yellow-500 hover:bg-yellow-600 text-white mx-1">
                                        Edit
                                    </button>
                                    <button @click="confirmDelete({{ $task->id }})"
                                        class="btn bg-red-500 hover:bg-red-600 text-white mx-1">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

<script>
    function toggleDialog(mode) {
        // this.mode = mode;
        this.mode = mode;
        this.open = true;
        if (mode === 'add') {
            this.tasks = { id: null, title: '', description: '', completed: false };
        }
    }

    function cancelForm() {
        this.open = false;
    }
    // function submitForm() {
    //     let formData = {
    //         title: this.tasks.title,
    //         description: this.tasks.description,
    //         completed: this.tasks.completed,
    //         _token: '{{ csrf_token() }}' 
    //     };

    //     fetch('/tasks-store', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //         },
    //         body: JSON.stringify(formData)
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         console.log('Success:', data);
    //         this.taskLists.push(data);
    //         this.tasks = {}; 
    //         this.open = false; 
    //         location.reload();
    //     })
    //     .catch((error) => {
    //         console.error('Error:', error);
    //     });
    // }
    function submitForm() {
        const formData = {
            title: this.tasks.title,
            description: this.tasks.description,
            completed: this.tasks.completed,
            _token: '{{ csrf_token() }}'
        };

        // Determine update or create based on mode
        const url = this.mode === 'add' ? '/tasks-store' : `/tasks-update/${taskId}`;

        const method = this.mode === 'add' ? 'POST' : 'PUT';

        fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                if (this.mode === 'add') {
                    this.taskLists.push(data);
                    location.reload();
                }
                this.tasks = {};
                this.open = false;
                location.reload();
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    const confirmEdit = (taskId) => {
        this.open = true;
        this.mode = 'update';
        fetch(`/tasks-edit/${taskId}`)
            .then(task => task.json()) 
            .then(data => {
                console.log(data.title);
                this.tasks = data;
                this.open = true;
            })
            .catch(error => {
                console.error('Error fetching task details:', error);
            });
    }

    function confirmDelete(taskId) {
        if (confirm('Are you sure you want to delete this task?')) {
            fetch(`/tasks-delete/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(() => {
                    console.log('Task deleted successfully');
                    location.reload();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    }
</script>
