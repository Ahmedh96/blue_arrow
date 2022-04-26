<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <a href="{{ route('notes.create') }}">Add</a>
            <br><br>
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md table-responsive sm:rounded-lg">
                    <table class="table w-full text-sm text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Content
                            </th>

                            <th scope="col" class="px-6 py-3">
                                User
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Image
                            </th>

                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($notes as $note)
                            <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $note->type }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $note->content }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $note->user->name }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"><img src="{{url($note->image_path)}}" height="100" width="100" alt=""></td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    <button onclick="prompt('Press Ctrl + C, then Enter to copy to clipboard',window.location.href + '/share/{{ $note->id }}')">Generate Link</button>

                                    <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-warning btn-sm"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                                    <form action="{{ route('notes.destroy', $note->id) }}" class="my-1 my-xl-0" method="post" style="display: inline-block;">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm delete"><i class="mdi mdi-account-minus"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if (!count($notes) > 0)
                            <tr>
                                <td rowspan="5" class="p-5">There is no results</td>
                            </tr>
                            @endif
                        </tbody>
                        {{$notes->links()}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

