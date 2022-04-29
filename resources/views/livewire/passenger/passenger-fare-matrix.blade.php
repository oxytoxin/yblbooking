<div>
    <x-tungsten.header heading="Fare Matrix" />
    <section class="text-gray-600 body-font">
        <div class="container mx-auto">
            <div class="flex flex-wrap -m-4">
                @foreach ($dispatch_routes as $dispatch_route)
                <div class="xl:w-1/3  md:w-1/2 p-4">
                    <div class="border bg-white border-gray-200  p-6 rounded-lg">
                        <div
                            class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                        <h2 class="badge-success badge font-medium text-lg title-font mb-2">{{
                            Akaunting\Money\Money::PHP($dispatch_route->fare, true) }}</h2>
                        <p class="leading-relaxed text-base">{{ $dispatch_route->dispatch_route_name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>