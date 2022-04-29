<div>
    <x-tungsten.header heading="View Booking" />
    <div>
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 mx-auto">
                <div class="lg:w-4/5 mx-auto flex flex-wrap">
                    <div class="lg:w-1/2 w-full lg:pr-10 lg:py-6 mb-6 lg:mb-0">
                        <h2 class="text-lg title-font text-gray-500 tracking-widest">
                            Transaction ID: {{ $booking->transaction_id }}
                        </h2>
                        <h1 class="text-gray-900 text-2xl title-font font-medium mb-4">
                            {{ $booking->dispatch_route->dispatch_route_name }}
                        </h1>
                        <div class="flex border-t border-gray-200 py-2">
                            <span class="text-gray-500">Status</span>
                            <span class="ml-auto badge badge-lg badge-primary">{{ $booking->status_name
                                }}</span>
                        </div>
                        <div class="flex border-t border-gray-200 py-2">
                            <span class="text-gray-500">Schedule</span>
                            <span class="ml-auto text-gray-900">{{ $booking->dispatch->schedule->format('h:i A M d, Y')
                                }}</span>
                        </div>
                        <div class="flex border-t border-b border-gray-200 py-2">
                            <span class="text-gray-500">Booked At</span>
                            <span class="ml-auto text-gray-900">{{ $booking->created_at->format('h:i A M d, Y')
                                }}</span>
                        </div>
                        <div class="flex my-4 justify-center">
                            <span class="title-font font-medium text-2xl text-gray-900">{{
                                Akaunting\Money\Money::PHP($booking->dispatch_route->fare, true) }}</span>
                        </div>
                        <div class="border-t border-b mb-6 border-gray-200 py-2">
                            <h5 class="text-center text-gray-900">{{ $booking->reference_number }}</h5>
                        </div>
                    </div>
                    @if ($booking->status == \App\Models\Booking::APPROVED)
                    <div x-cloak x-data x-init="
                        $nextTick(() => { 
                            new QRCode($refs.qrc, '{{ $booking->secret .'-'. $booking->transaction_id }}');
                        })
                    " class="lg:w-1/2 flex justify-center w-full">
                        <div x-ref="qrc"></div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>