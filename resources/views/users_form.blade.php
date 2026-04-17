@section('content')    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add User</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('first_name') }}" required>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('last_name') }}" required>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700">Role</label>
                            <select name="role" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('role')=='user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700">Email</label>
                            <input type="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('email') }}" required>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700">Password</label>
                            <input type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700">Phone No</label>
                            <input type="text" name="phone_no" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('phone_no') }}">
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700">Address</label>
                            <input type="text" name="address" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('address') }}" required>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add User</button>
                        <a href="{{ route('users.show') }}" class="ml-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
