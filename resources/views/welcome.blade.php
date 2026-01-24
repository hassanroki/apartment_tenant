@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen bg-fixed bg-cover bg-center"
     style="background-image:linear-gradient(rgba(245,247,250,0.85),rgba(245,247,250,0.85)),url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688')">

    <div class="max-w-7xl mx-auto px-4 py-12">

        <!-- Page Title -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">
                Available Apartments
            </h2>
            <p class="text-gray-500 mt-2">
                Choose your apartment & book easily
            </p>
        </div>

        <!-- Apartment Grid -->
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">

            <!-- Apartment Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                <img src="https://picsum.photos/400/300?random=1"
                     class="w-full h-48 object-cover"
                     alt="Apartment">

                <div class="p-5">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Green View Apartment
                    </h3>

                    <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                        📍 Dhanmondi, Dhaka
                    </p>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-blue-600 font-semibold text-lg">
                            ৳ 25,000 / Month
                        </span>
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                            Available
                        </span>
                    </div>

                    <!-- Date Range -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Booking Date
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date"
                                   class="border rounded-md px-2 py-1 text-sm focus:ring focus:ring-blue-200">
                            <input type="date"
                                   class="border rounded-md px-2 py-1 text-sm focus:ring focus:ring-blue-200">
                        </div>
                    </div>

                    <!-- Button -->
                    <button
                        class="mt-5 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-full font-medium transition">
                        📅 Book Now
                    </button>
                </div>
            </div>

            <!-- Apartment Card (Not Available) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden opacity-90">
                <img src="https://picsum.photos/400/300?random=2"
                     class="w-full h-48 object-cover"
                     alt="Apartment">

                <div class="p-5">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Green View Apartment
                    </h3>

                    <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                        📍 Dhanmondi, Dhaka
                    </p>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-blue-600 font-semibold text-lg">
                            ৳ 25,000 / Month
                        </span>
                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                            Not Available
                        </span>
                    </div>

                    <!-- Date Range -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Booking Date
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" disabled
                                   class="border rounded-md px-2 py-1 text-sm bg-gray-100">
                            <input type="date" disabled
                                   class="border rounded-md px-2 py-1 text-sm bg-gray-100">
                        </div>
                    </div>

                    <!-- Button -->
                    <button
                        disabled
                        class="mt-5 w-full bg-gray-400 text-white py-2 rounded-full font-medium cursor-not-allowed">
                        🚫 Not Available
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
