<div>
    <x-tungsten.header heading="Announcements" />
    <section class="text-gray-600 body-font">
        <div class="container px-5 py-12 mx-auto">
            <div class="flex flex-wrap -m-4">
                @forelse ($announcements as $announcement)
                <div class="p-4 md:w-1/3">
                    <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                        <img class="lg:h-48 md:h-36 w-full object-cover object-center"
                            src="/storage/{{ $announcement->image }}" alt="blog">
                        <div class="p-6 prose text-gray-600">
                            <h2 class="tracking-widest text-xs title-font font-medium text-gray-400 mb-1">{{
                                $announcement->created_at->format('h:i A M d, Y') }}</h2>
                            <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{ $announcement->title }}
                            </h1>
                            <div class="leading-relaxed text-ellipsis max-h-40 overflow-hidden mb-3">{!!
                                $announcement->content
                                !!}</div>
                            <div class="flex justify-end flex-wrap ">
                                <a href="{{ route('conductor.announcement',['announcement' => $announcement]) }}"
                                    class="text-yellow-500 inline-flex items-center md:mb-2 lg:mb-0">Read More
                                    <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center title-font text-lg font-medium text-gray-900 mb-3">No announcements yet.</div>
                @endforelse
            </div>
        </div>
    </section>
</div>