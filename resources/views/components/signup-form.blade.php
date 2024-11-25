<div class="container max-w-md p-4 mx-auto">
    <h5 class="text-lg font-bold mb-4">Personal Information :</h5>
    <form action="{{ $action ?? route('register') }}" method="post">
        @csrf
        <div class="mb-4">
            <label for="firstname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">First Name</label>
            <input type="text" id="firstname" name="firstname" placeholder="First Name" required="" class="block w-full pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="lastname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Last Name</label>
            <input type="text" id="lastname" name="lastname" placeholder="Last Name" required="" class="block w-full pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Email Address" required="" class="block w-full pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Gender*</label>
            <div class="flex items-center mb-4">
                <input id="male" type="radio" name="gender" value="male" required="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="male" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Male</label>
            </div>
            <div class="flex items-center mb-4">
                <input id="female" type="radio" name="gender" value="female" required="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="female" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Female</label>
            </div>
        </div>
        <h6 class="text-lg font-bold mb-4">Login Information :</h6>
        <div class="mb-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required="" class="block w-full pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required="" class="block w-full pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div class="mb-4">
            <input type="submit" value="Submit" class="bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
        </div>
        <div class="registration">
            Already Registered.
            <a href="{{ route('login') }}" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                Login
            </a>
        </div>
    </form>
</div>