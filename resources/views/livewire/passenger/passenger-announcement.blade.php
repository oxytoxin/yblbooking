<section class="text-gray-600 body-font overflow-hidden">
    <div class="container px-5 py-24 mx-auto">
        <div class="lg:w-4/5 mx-auto flex flex-wrap">
            <img alt="ecommerce" class="lg:w-1/2 w-full lg:h-auto h-64 object-cover object-center rounded"
                src="/storage/{{ $announcement->image }}">
            <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                <div class="prose text-gray-600 mx-auto">
                    <h2 class="text-sm title-font text-gray-500 tracking-widest">{{
                        $announcement->created_at->format('h:i A
                        M d, Y') }}</h2>
                    <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">{{ $announcement->title }}</h1>
                    {!! $announcement->content !!}
                </div>
            </div>
        </div>
    </div>
</section>